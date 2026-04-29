<?php

class QuizHelper {

    public static function getQuizBySlug($slug) {
        $pdo = DatabaseHelper::getPDO();
        if (!$pdo) return null;

        $sql = "SELECT * FROM quizzes WHERE slug = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$slug]);
        $quiz = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($quiz && $quiz['config']) {
            $quiz['config'] = json_decode($quiz['config'], TRUE);
        }
        
        return $quiz;
    }

    public static function createQuizSession($quizId) {
        $pdo = DatabaseHelper::getPDO();
        if (!$pdo) return null;

        $sessionToken = bin2hex(random_bytes(32));
        
        $sql = "INSERT INTO quiz_sessions (quiz_id, session_token, answers) 
                VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$quizId, $sessionToken, json_encode([])]);
        
        return $sessionToken;
    }

    public static function getQuizSession($sessionToken) {
        $pdo = DatabaseHelper::getPDO();
        if (!$pdo) return null;

        $sql = "SELECT * FROM quiz_sessions WHERE session_token = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$sessionToken]);
        $session = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($session && $session['answers']) {
            $session['answers'] = json_decode($session['answers'], TRUE);
        }
        
        return $session;
    }

    public static function saveAnswer($sessionToken, $questionId, $answer) {
        $pdo = DatabaseHelper::getPDO();
        if (!$pdo) return false;

        $session = self::getQuizSession($sessionToken);
        if (!$session) return false;

        $answers = $session['answers'] ?? [];
        $answers[$questionId] = $answer;

        $sql = "UPDATE quiz_sessions SET answers = ? WHERE session_token = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([json_encode($answers), $sessionToken]);
    }

    public static function getNextQuestion($sessionToken) {
        $pdo = DatabaseHelper::getPDO();
        if (!$pdo) return null;

        $session = self::getQuizSession($sessionToken);
        if (!$session) return null;

        $quiz = self::getQuizById($session['quiz_id']);
        if (!$quiz) return null;

        $config = $quiz['config'];
        $answers = $session['answers'] ?? [];
        
        // Find the next question based on current answers and flow rules
        return self::evaluateFlow($config, $answers);
    }

    public static function evaluateFlow($config, $answers) {
        // Recursively evaluate the flow based on conditions
        if (!isset($config['flow']) || empty($config['flow'])) {
            return null;
        }

        foreach ($config['flow'] as $flowItem) {
            // Skip questions that have already been answered
            if ($flowItem['type'] === 'question' && isset($answers[$flowItem['id']])) {
                continue;
            }
            
            // Check if conditions are met
            if (self::conditionsMet($flowItem['conditions'] ?? [], $answers)) {
                // Return the next action
                if ($flowItem['type'] === 'question') {
                    return $flowItem;
                } elseif ($flowItem['type'] === 'result') {
                    return $flowItem;
                }
            }
        }

        return null;
    }

    private static function conditionsMet($conditions, $answers) {
        if (empty($conditions)) {
            return true;
        }


        foreach ($conditions as $condition) {
            $questionId = $condition['questionId'];
            $operator = $condition['operator'] ?? '===';

            // Handle existence checks first (they don't need a value)
            if ($operator === 'exists') {
                if (!isset($answers[$questionId])) return false;
                continue;
            }
            
            if ($operator === 'notExists') {
                if (isset($answers[$questionId])) return false;
                continue;
            }

            // For other operators, we need both the question to be answered and a value to compare
            if (!isset($answers[$questionId])) {
                return false;
            }

            $expectedValue = $condition['value'] ?? null;
            $actualValue = $answers[$questionId];

            switch ($operator) {
                case '===':
                    if ($actualValue !== $expectedValue) return false;
                    break;
                case 'in':
                    if (!is_array($expectedValue) || !in_array($actualValue, $expectedValue)) return false;
                    break;
            }
        }
        return true;
    }

    public static function getResultByAnswers($quizId, $answers) {
        $pdo = DatabaseHelper::getPDO();
        if (!$pdo) return null;

        $sql = "SELECT * FROM quiz_results WHERE quiz_id = ? ORDER BY id ASC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$quizId]);

        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $conditions = json_decode($result['conditions'], TRUE);
            
            if (self::conditionsMet($conditions, $answers)) {
                return $result;
            }
        }

        return null;
    }

    public static function completeQuizSession($sessionToken, $resultId) {
        $pdo = DatabaseHelper::getPDO();
        if (!$pdo) return false;

        $sql = "UPDATE quiz_sessions SET result_id = ?, completed_at = NOW() 
                WHERE session_token = ?";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([$resultId, $sessionToken]);
    }

    public static function getQuizById($quizId) {
        $pdo = DatabaseHelper::getPDO();
        if (!$pdo) return null;

        $sql = "SELECT * FROM quizzes WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$quizId]);
        $quiz = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($quiz && $quiz['config']) {
            $quiz['config'] = json_decode($quiz['config'], TRUE);
        }
        
        return $quiz;
    }

    public static function getAllQuizzes() {
        $pdo = DatabaseHelper::getPDO();
        if (!$pdo) return [];

        $sql = "SELECT id, slug, title, description FROM quizzes ORDER BY created_at DESC";
        $result = [];
        foreach ($pdo->query($sql) as $row) {
            $result[] = $row;
        }
        return $result;
    }

    public static function createOrUpdateQuiz($slug, $title, $description, $config) {
        $pdo = DatabaseHelper::getPDO();
        if (!$pdo) return null;

        $configJson = json_encode($config);
        
        // Check if exists
        $sql = "SELECT id FROM quizzes WHERE slug = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$slug]);
        $existing = $stmt->fetch();

        if ($existing) {
            $sql = "UPDATE quizzes SET title = ?, description = ?, config = ? WHERE slug = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$title, $description, $configJson, $slug]);
            return $existing['id'];
        } else {
            $sql = "INSERT INTO quizzes (slug, title, description, config) VALUES (?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$slug, $title, $description, $configJson]);
            return $pdo->lastInsertId();
        }
    }

    public static function createOrUpdateResult($quizId, $slug, $title, $description, $conditions, $compatibilityStatus = 'unknown', $icon = 'help-circle', $recommendations = '') {
        $pdo = DatabaseHelper::getPDO();
        if (!$pdo) return null;

        $conditionsJson = json_encode($conditions);
        
        $sql = "INSERT INTO quiz_results (quiz_id, slug, title, description, conditions, compatibility_status, icon, recommendations) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE 
                title = ?, description = ?, conditions = ?, compatibility_status = ?, icon = ?, recommendations = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $quizId, $slug, $title, $description, $conditionsJson, $compatibilityStatus, $icon, $recommendations,
            $title, $description, $conditionsJson, $compatibilityStatus, $icon, $recommendations
        ]);
        
        return $pdo->lastInsertId();
    }
}

QuizHelper::class;
