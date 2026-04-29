<?php
/**
 * Quiz Seeding Script
 * 
 * This script populates the database with example quizzes.
 * Run this once from the CLI or include it in your setup process.
 */

// Load required files
require_once __DIR__ . '/../helper/database.php';
require_once __DIR__ . '/../helper/quiz.php';
require_once __DIR__ . '/quiz-example.php';

// ============================================================================
// Tech Stack Compatibility Quiz
// ============================================================================

echo "Creating 'Tech Stack Compatibility Check' quiz...\n";

$quizId = QuizHelper::createOrUpdateQuiz(
    'tech-stack-check',
    'Tech Stack Compatibility Check',
    'Determine if your technology stack is compatible with SwimResults.',
    $techStackQuizConfig
);

if ($quizId) {
    echo "✓ Quiz created with ID: $quizId\n";
    
    // Add results
    $results = [
        [
            "slug" => "php-fully-compatible",
            "title" => "✅ Fully Compatible",
            "description" => "Your PHP setup is fully compatible with SwimResults!",
            "conditions" => [
                [
                    "questionId" => "q1_platform",
                    "value" => "php",
                    "operator" => "==="
                ],
                [
                    "questionId" => "q2_php_version",
                    "value" => ["php8plus", "php7.4"],
                    "operator" => "in"
                ]
            ],
            "compatibility_status" => "compatible",
            "icon" => "check-circle",
            "recommendations" => "
                <h3>Recommendations:</h3>
                <ul>
                    <li>Your PHP version is fully supported</li>
                    <li>Ensure you have MySQL 5.7+ or equivalent database</li>
                    <li>Install required PHP extensions: PDO, JSON, curl</li>
                    <li>You're ready to deploy SwimResults!</li>
                </ul>
            "
        ],
        [
            "slug" => "php-minimum-version",
            "title" => "⚠️ Likely Compatible",
            "description" => "Your setup meets minimum requirements but upgrades are recommended.",
            "conditions" => [
                [
                    "questionId" => "q1_platform",
                    "value" => "php",
                    "operator" => "==="
                ],
                [
                    "questionId" => "q2_php_version",
                    "value" => "php7",
                    "operator" => "==="
                ]
            ],
            "compatibility_status" => "likely-compatible",
            "icon" => "alert-circle",
            "recommendations" => "
                <h3>Recommendations:</h3>
                <ul>
                    <li>⚠️ PHP 7.0-7.3 has reached end-of-life</li>
                    <li>Please upgrade to PHP 7.4 or PHP 8.0+</li>
                    <li>Newer versions provide better performance and security</li>
                    <li>SwimResults will work but you won't get latest optimizations</li>
                </ul>
            "
        ],
        [
            "slug" => "php-not-compatible",
            "title" => "❌ Not Compatible",
            "description" => "Your PHP version is not supported.",
            "conditions" => [
                [
                    "questionId" => "q1_platform",
                    "value" => "php",
                    "operator" => "==="
                ],
                [
                    "questionId" => "q2_php_version",
                    "value" => "php5",
                    "operator" => "==="
                ]
            ],
            "compatibility_status" => "incompatible",
            "icon" => "x-circle",
            "recommendations" => "
                <h3>You Need to Upgrade:</h3>
                <ul>
                    <li>❌ PHP 5.x is no longer supported</li>
                    <li>Please upgrade immediately to PHP 7.4 or later</li>
                    <li>PHP 5 has critical security vulnerabilities</li>
                    <li>Contact your hosting provider about PHP upgrades</li>
                </ul>
            "
        ],
        [
            "slug" => "java-modern-stack",
            "title" => "✅ Fully Compatible",
            "description" => "Your Java setup is state-of-the-art and fully compatible!",
            "conditions" => [
                [
                    "questionId" => "q1_platform",
                    "value" => "java",
                    "operator" => "==="
                ],
                [
                    "questionId" => "q2_java_version",
                    "value" => ["java17plus", "java11"],
                    "operator" => "in"
                ]
            ],
            "compatibility_status" => "compatible",
            "icon" => "check-circle",
            "recommendations" => "
                <h3>Great Choice!</h3>
                <ul>
                    <li>Your Java version is fully supported and modern</li>
                    <li>Ensure you have Docker or container support for deployment</li>
                    <li>SwimResults provides excellent performance with modern Java</li>
                    <li>Ready to deploy!</li>
                </ul>
            "
        ],
        [
            "slug" => "nodejs-compatible",
            "title" => "✅ Fully Compatible",
            "description" => "Node.js integration with SwimResults is fully supported.",
            "conditions" => [
                [
                    "questionId" => "q1_platform",
                    "value" => "nodejs",
                    "operator" => "==="
                ]
            ],
            "compatibility_status" => "compatible",
            "icon" => "check-circle",
            "recommendations" => "
                <h3>Node.js Setup Tips:</h3>
                <ul>
                    <li>Ensure you have Node.js 14+ installed</li>
                    <li>Use npm or yarn for dependency management</li>
                    <li>Consider using Express.js or similar frameworks</li>
                    <li>SwimResults APIs work seamlessly with Node.js backends</li>
                </ul>
            "
        ],
        [
            "slug" => "generic-compatible",
            "title" => "💬 Contact Us",
            "description" => "Your tech stack may require custom integration.",
            "conditions" => [
                [
                    "questionId" => "q1_platform",
                    "value" => ["dotnet", "python", "other"],
                    "operator" => "in"
                ]
            ],
            "compatibility_status" => "unknown",
            "icon" => "help-circle",
            "recommendations" => "
                <h3>Let's Work Together:</h3>
                <ul>
                    <li>SwimResults provides comprehensive REST APIs</li>
                    <li>Most tech stacks can integrate via our API</li>
                    <li>Please contact our support team for integration guidance</li>
                    <li>We'll help you find the best solution for your platform</li>
                </ul>
            "
        ],
        [
            "slug" => "limited-database-support",
            "title" => "⚙️ Partial Support",
            "description" => "You can use SwimResults with some workarounds or limitations.",
            "conditions" => [
                [
                    "questionId" => "q1_platform",
                    "value" => "php",
                    "operator" => "==="
                ],
                [
                    "questionId" => "q3_database",
                    "value" => ["mongodb", "other"],
                    "operator" => "in"
                ]
            ],
            "compatibility_status" => "partial-compatible",
            "icon" => "alert-circle",
            "recommendations" => "
                <h3>Partial Support:</h3>
                <ul>
                    <li>Your platform can work with SwimResults, but may require custom adapters</li>
                    <li>API integration is fully supported</li>
                    <li>Some database-specific features may need workarounds</li>
                    <li>Contact us for technical guidance on your specific setup</li>
                </ul>
            "
    foreach ($results as $result) {
        QuizHelper::createOrUpdateResult(
            $quizId,
            $result['slug'],
            $result['title'],
            $result['description'],
            $result['conditions'],
            $result['compatibility_status'],
            $result['icon'],
            $result['recommendations']
        );
        echo "  ✓ Added result: {$result['slug']}\n";
    }
    
    echo "✓ Quiz setup complete!\n";
} else {
    echo "✗ Failed to create quiz\n";
}

echo "\nDone! You can now visit /quiz to see the compatibility quiz.\n";
?>
