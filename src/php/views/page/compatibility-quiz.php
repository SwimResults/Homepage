<?php
    // Quiz page view - allows selecting which quiz to take
    $quizzes = QuizHelper::getAllQuizzes();
?>

<div class="section">
    <h1><?php T::e("NAV.COMPATIBILITY_QUIZ"); ?></h1>
    <p><?php T::e("CONTENT.QUIZ.INTRO"); ?></p>

    <?php if (!empty($quizzes)): ?>
        <div class="quiz-list">
            <?php foreach ($quizzes as $quiz): ?>
                <div class="quiz-card">
                    <h2><?php echo htmlspecialchars($quiz['title']); ?></h2>
                    <p><?php echo htmlspecialchars($quiz['description']); ?></p>
                    <a href="/quiz/<?php echo htmlspecialchars($quiz['slug']); ?>" class="btn">
                        <?php T::e("CONTENT.QUIZ.START_BUTTON"); ?>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p><?php T::e("CONTENT.QUIZ.NO_QUIZZES"); ?></p>
    <?php endif; ?>
</div>
