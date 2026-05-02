class QuizEngine {
    constructor(config) {
        this.apiUrl = config.apiUrl;
        this.quizSlug = config.quizSlug;
        this.containerId = config.containerId;
        
        this.sessionToken = localStorage.getItem(`quiz_session_${this.quizSlug}`);
        this.currentQuiz = null;
        this.currentQuestion = null;
        this.selectedAnswer = null;
        this.answersHistory = [];
        
        this.elements = {
            wrapper: document.getElementById(this.containerId),
            loading: document.getElementById('quiz-loading'),
            question: document.getElementById('quiz-question'),
            result: document.getElementById('quiz-result'),
            error: document.getElementById('quiz-error'),
            
            progressBar: document.getElementById('quiz-progress'),
            questionTitle: document.getElementById('question-title'),
            questionDescription: document.getElementById('question-description'),
            questionOptions: document.getElementById('question-options'),
            nextBtn: document.getElementById('quiz-next-btn'),
            
            resultTitle: document.getElementById('result-title'),
            resultScore: document.getElementById('result-score'),
            resultDescription: document.getElementById('result-description'),
            resultRecommendations: document.getElementById('result-recommendations'),
            
            errorMessage: document.getElementById('error-message')
        };
    }

    async initialize() {
        try {
            if (!this.sessionToken) {
                // Create new session
                const response = await this.apiCall('init');
                this.sessionToken = response.sessionToken;
                localStorage.setItem(`quiz_session_${this.quizSlug}`, this.sessionToken);
                
                this.currentQuiz = response.quiz;
                this.currentQuestion = response.currentQuestion;
            } else {
                // Restore existing session
                const sessionResponse = await this.apiCall('session', { sessionToken: this.sessionToken });
                
                if (sessionResponse.session.completed_at) {
                    // Quiz is complete, start a fresh session
                    const response = await this.apiCall('init');
                    this.sessionToken = response.sessionToken;
                    localStorage.setItem(`quiz_session_${this.quizSlug}`, this.sessionToken);
                    
                    this.currentQuestion = response.currentQuestion;
                    this.currentQuiz = response.quiz;
                } else {
                    // Quiz is still in progress, restart from first question
                    const response = await this.apiCall('init');
                    this.sessionToken = response.sessionToken;
                    localStorage.setItem(`quiz_session_${this.quizSlug}`, this.sessionToken);
                    
                    this.currentQuestion = response.currentQuestion;
                    this.currentQuiz = response.quiz;
                }
            }
            
            this.showQuestion();
        } catch (error) {
            this.showError('Failed to initialize quiz: ' + error.message);
        }
    }

    async apiCall(action, additionalParams = {}) {
        const params = {
            action: action,
            quiz: this.quizSlug,
            ...additionalParams
        };

        const queryString = new URLSearchParams(params).toString();
        const response = await fetch(`${this.apiUrl}?${queryString}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            }
        });

        if (!response.ok) {
            const error = await response.json();
            throw new Error(error.error || 'API call failed');
        }

        return response.json();
    }

    showQuestion() {
        if (!this.currentQuestion) {
            this.showError('No question found');
            return;
        }

        // Hide other states
        this.hideAllStates();
        this.elements.question.style.display = 'block';
        
        // Update question content
        this.elements.questionTitle.textContent = this.currentQuestion.label;
        this.elements.questionDescription.textContent = this.currentQuestion.description || '';
        
        // Render options based on type
        this.renderOptions();
        
        // Update progress
        this.updateProgress();
        
        // Reset selected answer
        this.selectedAnswer = null;
        this.elements.nextBtn.style.display = 'none';
    }

    renderOptions() {
        const optionsContainer = this.elements.questionOptions;
        optionsContainer.innerHTML = '';
        
        // Always use type_of_question for input type (radio, checkbox, dropdown)
        // The 'type' field indicates if it's a question or result in the flow
        const questionType = this.currentQuestion.type_of_question || this.currentQuestion.type || 'radio';
        const options = this.currentQuestion.options || [];

        if (questionType === 'radio' || questionType === 'single') {
            // Radio button options
            options.forEach((option, index) => {
                const label = document.createElement('label');
                label.className = 'quiz-option-label';
                
                const input = document.createElement('input');
                input.type = 'radio';
                input.name = `question-${this.currentQuestion.id}`;
                input.value = option.value;
                input.addEventListener('change', () => {
                    this.selectedAnswer = option.value;
                    this.elements.nextBtn.style.display = 'block';
                });
                
                label.appendChild(input);
                label.appendChild(document.createTextNode(option.label));
                optionsContainer.appendChild(label);
            });

        } else if (questionType === 'checkbox' || questionType === 'multiple') {
            // Checkbox options (multiple selections)
            options.forEach((option, index) => {
                const label = document.createElement('label');
                label.className = 'quiz-option-label';
                
                const input = document.createElement('input');
                input.type = 'checkbox';
                input.name = `question-${this.currentQuestion.id}`;
                input.value = option.value;
                input.addEventListener('change', () => {
                    const checkedValues = Array.from(
                        document.querySelectorAll(`input[name="question-${this.currentQuestion.id}"]:checked`)
                    ).map(el => el.value);
                    
                    this.selectedAnswer = checkedValues;
                    this.elements.nextBtn.style.display = checkedValues.length > 0 ? 'block' : 'none';
                });
                
                label.appendChild(input);
                label.appendChild(document.createTextNode(option.label));
                optionsContainer.appendChild(label);
            });

        } else if (questionType === 'dropdown' || questionType === 'select') {
            // Dropdown selection
            const select = document.createElement('select');
            select.className = 'quiz-select';
            select.addEventListener('change', () => {
                this.selectedAnswer = select.value;
                this.elements.nextBtn.style.display = select.value ? 'block' : 'none';
            });
            
            const emptyOption = document.createElement('option');
            emptyOption.value = '';
            emptyOption.textContent = 'Select an option...';
            select.appendChild(emptyOption);
            
            options.forEach(option => {
                const optionEl = document.createElement('option');
                optionEl.value = option.value;
                optionEl.textContent = option.label;
                select.appendChild(optionEl);
            });
            
            optionsContainer.appendChild(select);
        }

        // Bind next button click
        this.elements.nextBtn.onclick = () => this.submitAnswer();
    }

    async submitAnswer() {
        if (this.selectedAnswer === null) return;

        try {
            const response = await this.apiCall('answer', {
                sessionToken: this.sessionToken,
                questionId: this.currentQuestion.id,
                answer: Array.isArray(this.selectedAnswer) 
                    ? JSON.stringify(this.selectedAnswer) 
                    : this.selectedAnswer
            });

            if (response.isComplete) {
                // Quiz is complete, show result
                this.showResult(response.result);
            } else {
                // Load next question
                this.currentQuestion = response.currentQuestion;
                this.answersHistory.push({
                    id: this.currentQuestion.id,
                    answer: this.selectedAnswer
                });
                this.showQuestion();
            }
        } catch (error) {
            this.showError('Failed to submit answer: ' + error.message);
        }
    }

    showResult(result) {
        this.hideAllStates();
        this.elements.result.style.display = 'block';
        
        if (!result) {
            this.elements.resultTitle.textContent = 'No matching result found';
            return;
        }
        
        this.elements.resultTitle.textContent = result.title;
        
        // Update status badge with color and icon
        const statusEl = document.getElementById('result-status');
        const status = result.compatibility_status || 'unknown';
        const icon = result.icon || 'help-circle';
        
        if (statusEl) {
            statusEl.className = 'compatibility-status status-' + status;
            statusEl.innerHTML = this.getStatusIcon(icon) + ' <span>' + this.getStatusLabel(status) + '</span>';
        }
        
        this.elements.resultDescription.textContent = result.description || '';
        
        if (result.recommendations) {
            this.elements.resultRecommendations.innerHTML = result.recommendations;
        } else {
            this.elements.resultRecommendations.innerHTML = '';
        }
    }

    getStatusIcon(iconName) {
        const icons = {
            'check-circle': '✓',
            'alert-circle': '⚠',
            'x-circle': '✕',
            'help-circle': '?',
            'info-circle': 'i'
        };
        return '<span class="status-icon">' + (icons[iconName] || '•') + '</span>';
    }

    getStatusLabel(status) {
        const labels = {
            'compatible': 'Vollständig Kompatibel',
            'likely-compatible': 'Wahrscheinlich Kompatibel',
            'partial-compatible': 'Teilweise Kompatibel',
            'incompatible': 'Nicht Kompatibel',
            'unknown': 'Unbekannt'
        };
        return labels[status] || 'Unknown';
    }

    showError(message) {
        this.hideAllStates();
        this.elements.error.style.display = 'block';
        this.elements.errorMessage.textContent = message;
    }

    hideAllStates() {
        this.elements.loading.style.display = 'none';
        this.elements.question.style.display = 'none';
        this.elements.result.style.display = 'none';
        this.elements.error.style.display = 'none';
    }

    updateProgress() {
        // Simple progress indicator (current question number / total questions estimated)
        // In a real scenario, you'd calculate total questions from config
        const progressText = `Frage ${this.answersHistory.length + 1}`;
        this.elements.progressBar.textContent = progressText;
    }
}
