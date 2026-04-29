<?php
/**
 * Example: Tech Stack Compatibility Quiz Seed
 * 
 * This script demonstrates how to create quizzes with the flexible conditional flow system.
 * It creates a complete quiz with conditional branching based on user answers.
 */

// Example quiz configuration structure
$techStackQuizConfig = [
    "title" => "Tech Stack Compatibility Check",
    "description" => "Determine if your technology stack is compatible with SwimResults.",
    
    // Flow defines the sequence and branching of questions
    "flow" => [
        // START: Initial question for all users
        [
            "id" => "q1_platform",
            "type" => "question",
            "label" => "What is your primary backend platform?",
            "description" => "This helps us understand your infrastructure",
            "type_of_question" => "radio",
            "options" => [
                ["value" => "java", "label" => "Java"],
                ["value" => "php", "label" => "PHP"],
                ["value" => "nodejs", "label" => "Node.js"],
                ["value" => "dotnet", "label" => ".NET"],
                ["value" => "python", "label" => "Python"],
                ["value" => "other", "label" => "Other"]
            ],
            // No conditions means this is shown first
            "conditions" => []
        ],
        
        // BRANCHING: PHP-specific follow-up
        [
            "id" => "q2_php_version",
            "type" => "question",
            "label" => "Which PHP version are you using?",
            "description" => "SwimResults requires PHP 7.4 or higher",
            "type_of_question" => "radio",
            "options" => [
                ["value" => "php8plus", "label" => "PHP 8.0 or higher"],
                ["value" => "php7.4", "label" => "PHP 7.4"],
                ["value" => "php7", "label" => "PHP 7.0-7.3"],
                ["value" => "php5", "label" => "PHP 5.x"]
            ],
            // Only show if user selected PHP
            "conditions" => [
                [
                    "questionId" => "q1_platform",
                    "value" => "php",
                    "operator" => "==="
                ]
            ]
        ],
        
        // BRANCHING: Java-specific follow-up
        [
            "id" => "q2_java_version",
            "type" => "question",
            "label" => "Which Java version are you using?",
            "description" => "SwimResults requires Java 11 or higher",
            "type_of_question" => "radio",
            "options" => [
                ["value" => "java17plus", "label" => "Java 17 or higher"],
                ["value" => "java11", "label" => "Java 11-16"],
                ["value" => "java8", "label" => "Java 8"],
                ["value" => "java7", "label" => "Java 7 or lower"]
            ],
            "conditions" => [
                [
                    "questionId" => "q1_platform",
                    "value" => "java",
                    "operator" => "==="
                ]
            ]
        ],
        
        // Universal follow-up: Database
        [
            "id" => "q3_database",
            "type" => "question",
            "label" => "What database do you use?",
            "description" => "",
            "type_of_question" => "radio",
            "options" => [
                ["value" => "mysql8", "label" => "MySQL 8.0+"],
                ["value" => "mysql5.7", "label" => "MySQL 5.7"],
                ["value" => "postgresql", "label" => "PostgreSQL"],
                ["value" => "mongodb", "label" => "MongoDB"],
                ["value" => "other", "label" => "Other"]
            ],
            // Show for all users (empty conditions or after each platform question)
            "conditions" => [
                [
                    "questionId" => "q1_platform",
                    "operator" => "exists"
                ]
            ]
        ],
        
        // Follow-up: API Integration capabilities
        [
            "id" => "q4_api_integration",
            "type" => "question",
            "label" => "Do you need to integrate with external APIs?",
            "description" => "",
            "type_of_question" => "radio",
            "options" => [
                ["value" => "yes", "label" => "Yes"],
                ["value" => "no", "label" => "No"]
            ],
            "conditions" => [
                [
                    "questionId" => "q3_database",
                    "operator" => "exists"
                ]
            ]
        ],
        
        // Conditional: Show API details if needed
        [
            "id" => "q5_api_types",
            "type" => "question",
            "label" => "Which type of APIs do you need?",
            "description" => "Select all that apply",
            "type_of_question" => "checkbox",
            "options" => [
                ["value" => "rest", "label" => "REST APIs"],
                ["value" => "graphql", "label" => "GraphQL"],
                ["value" => "soap", "label" => "SOAP"],
                ["value" => "webhooks", "label" => "Webhooks"]
            ],
            "conditions" => [
                [
                    "questionId" => "q4_api_integration",
                    "value" => "yes",
                    "operator" => "==="
                ]
            ]
        ]
    ]
];

$phpCompatibilityResults = [
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
    ]
];

// Example of how to seed the database
echo "<!-- Quiz Configuration Examples -->\n";
echo "<!-- \n\nTo seed these quizzes into your database, you would call:\n\n";
echo "QuizHelper::createOrUpdateQuiz(\n";
echo "    'tech-stack-check',\n";
echo "    'Tech Stack Compatibility Check',\n";
echo "    'Determine if your technology stack is compatible with SwimResults.',\n";
echo "    " . json_encode($techStackQuizConfig, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
echo ");\n\n";
echo "// Then add results...\n";
echo "// QuizHelper::createOrUpdateResult(...)\n";
echo "-->\n";

/**
 * CONFIGURATION DOCUMENTATION
 * 
 * QUIZ STRUCTURE:
 * {
 *   "flow": [
 *     {
 *       "id": "unique_question_id",
 *       "type": "question|result",
 *       "label": "Question text displayed to user",
 *       "description": "Optional helper text",
 *       "type_of_question": "radio|checkbox|dropdown",
 *       "options": [
 *         { "value": "answer_key", "label": "Display text" }
 *       ],
 *       "conditions": [
 *         {
 *           "questionId": "q1_platform",
 *           "value": "php" | ["php", "nodejs"] (for 'in' operator),
 *           "operator": "===" | "in" | "exists" | "notExists"
 *         }
 *       ]
 *     }
 *   ]
 * }
 * 
 * CONDITION OPERATORS:
 * - "===": Exact value match (single value required)
 * - "in": Value is in array (value should be an array)
 * - "exists": Question has been answered (regardless of value)
 * - "notExists": Question has NOT been answered
 * 
 * QUESTION TYPES:
 * - "radio": Single selection (mutually exclusive)
 * - "checkbox": Multiple selections
 * - "dropdown": Dropdown select with single selection
 * 
 * RESULTS:
 * Results are matched based on conditions. The first matching result is shown.
 * Order your results from most specific to most general for proper matching.
 */
?>
