<?php
    session_start();
    date_default_timezone_set("Europe/Berlin");
    
    require_once("../../php/helper/database.php");
    require_once("../../php/helper/quiz.php");
    require_once("../../php/helper/env.php");

    header('Content-Type: application/json');

    $action = $_REQUEST['action'] ?? null;
    $quizSlug = $_REQUEST['quiz'] ?? null;

    if (!$action || !$quizSlug) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing action or quiz parameter']);
        exit;
    }

    // Get quiz
    $quiz = QuizHelper::getQuizBySlug($quizSlug);
    if (!$quiz) {
        http_response_code(404);
        echo json_encode(['error' => 'Quiz not found']);
        exit;
    }

    switch ($action) {
        case 'init':
            // Initialize a new quiz session
            $sessionToken = QuizHelper::createQuizSession($quiz['id']);
            $firstQuestion = QuizHelper::evaluateFlow($quiz['config'], []);
            
            echo json_encode([
                'success' => true,
                'sessionToken' => $sessionToken,
                'quiz' => [
                    'id' => $quiz['id'],
                    'slug' => $quiz['slug'],
                    'title' => $quiz['title'],
                    'description' => $quiz['description']
                ],
                'currentQuestion' => $firstQuestion
            ]);
            break;

        case 'answer':
            // Save answer and get next question
            $sessionToken = $_REQUEST['sessionToken'] ?? null;
            $questionId = $_REQUEST['questionId'] ?? null;
            $answer = $_REQUEST['answer'] ?? null;

            if (!$sessionToken || !$questionId) {
                http_response_code(400);
                echo json_encode(['error' => 'Missing sessionToken or questionId']);
                exit;
            }

            $session = QuizHelper::getQuizSession($sessionToken);
            if (!$session || $session['quiz_id'] != $quiz['id']) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid session']);
                exit;
            }

            // Save the answer
            QuizHelper::saveAnswer($sessionToken, $questionId, $answer);
            
            // Get updated session
            $updatedSession = QuizHelper::getQuizSession($sessionToken);
            
            // Evaluate next flow item
            $nextItem = QuizHelper::evaluateFlow($quiz['config'], $updatedSession['answers']);

            if (!$nextItem) {
                // Quiz is complete, evaluate results
                $result = QuizHelper::getResultByAnswers($quiz['id'], $updatedSession['answers']);
                
                if ($result) {
                    QuizHelper::completeQuizSession($sessionToken, $result['id']);
                }

                echo json_encode([
                    'success' => true,
                    'isComplete' => true,
                    'result' => $result
                ]);
            } else {
                echo json_encode([
                    'success' => true,
                    'isComplete' => false,
                    'currentQuestion' => $nextItem
                ]);
            }
            break;

        case 'session':
            // Get current session state
            $sessionToken = $_REQUEST['sessionToken'] ?? null;
            if (!$sessionToken) {
                http_response_code(400);
                echo json_encode(['error' => 'Missing sessionToken']);
                exit;
            }

            $session = QuizHelper::getQuizSession($sessionToken);
            if (!$session || $session['quiz_id'] != $quiz['id']) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid session']);
                exit;
            }

            echo json_encode([
                'success' => true,
                'session' => $session
            ]);
            break;

        default:
            http_response_code(400);
            echo json_encode(['error' => 'Unknown action']);
    }
?>
