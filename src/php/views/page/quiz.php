<?php
    // Individual quiz page - this is the interactive quiz
    // The slug is validated in index.php and passed via $quiz_slug variable
    // This file assumes the quiz is valid
    
    $quiz = isset($quiz_slug) ? QuizHelper::getQuizBySlug($quiz_slug) : null;
?>

<div class="section quiz-container">
    <?php if ($quiz): ?>
        <h1><?php echo htmlspecialchars($quiz['title']); ?></h1>
        <p class="quiz-intro"><?php echo htmlspecialchars($quiz['description']); ?></p>
    <?php endif; ?>
    <div id="quiz-wrapper">
        <div id="quiz-loading" class="quiz-state">
            <p><?php T::e("CONTENT.QUIZ.LOADING"); ?></p>
        </div>
        
        <div id="quiz-question" class="quiz-state" style="display: none;">
            <div id="quiz-progress" class="quiz-progress"></div>
            
            <h2 id="question-title" class="quiz-question-title"></h2>
            <p id="question-description" class="quiz-question-description"></p>
            
            <div id="question-options" class="quiz-options"></div>
            
            <button id="quiz-next-btn" class="btn btn-primary" style="display: none;">
                <?php T::e("CONTENT.QUIZ.NEXT_BUTTON"); ?>
            </button>
        </div>

        <div id="quiz-result" class="quiz-state" style="display: none;">
            <h2 id="result-title" class="result-title"></h2>
            
            <div id="result-status" class="compatibility-status"></div>
            
            <p id="result-description" class="result-description"></p>
            
            <div id="result-recommendations" class="result-recommendations"></div>
            
            <a href="/compatibility-quiz" class="btn btn-secondary">
                <?php T::e("CONTENT.QUIZ.BACK_BUTTON"); ?>
            </a>
        </div>

        <div id="quiz-error" class="quiz-state error" style="display: none;">
            <p id="error-message"></p>
            <a href="/compatibility-quiz" class="btn">
                <?php T::e("CONTENT.QUIZ.BACK_BUTTON"); ?>
            </a>
        </div>
    </div>
</div>

<script>
    const quizEngine = new QuizEngine({
        apiUrl: '/api/quiz/index.php',
        quizSlug: '<?php echo htmlspecialchars($quiz_slug); ?>',
        containerId: 'quiz-wrapper'
    });
    
    quizEngine.initialize();
</script>
