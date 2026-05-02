<?php
/**
 * TEMPLATE: Custom Quiz Configuration
 * 
 * Copy this file and modify it to create your own quiz.
 * Then use QuizHelper::createOrUpdateQuiz() to save it to the database.
 */

// ============================================================================
// STEP 1: DEFINE YOUR QUIZ CONFIGURATION
// ============================================================================

$myCustomQuizConfig = [
    // Your question flow - all questions and their conditions
    "flow" => [
        
        // Question 1: Initial question (no conditions = shown first)
        [
            "id" => "q1_initial",
            "type" => "question",
            "label" => "Your first question?",
            "description" => "Optional helper text",
            "type_of_question" => "radio",  // Can be: radio, checkbox, dropdown
            "options" => [
                ["value" => "option_a", "label" => "Option A"],
                ["value" => "option_b", "label" => "Option B"],
                ["value" => "option_c", "label" => "Option C"]
            ],
            "conditions" => []  // Empty = shown to everyone
        ],
        
        // Question 2: Only shown if Q1 answer was "option_a"
        [
            "id" => "q2_followup_a",
            "type" => "question",
            "label" => "Follow-up for Option A?",
            "description" => "",
            "type_of_question" => "radio",
            "options" => [
                ["value" => "a1", "label" => "Sub-option 1"],
                ["value" => "a2", "label" => "Sub-option 2"]
            ],
            "conditions" => [
                [
                    "questionId" => "q1_initial",    // Must match question ID
                    "value" => "option_a",           // The answer value
                    "operator" => "==="              // Exact match
                ]
            ]
        ],
        
        // Question 3: Only shown if Q1 answer was "option_b"
        [
            "id" => "q2_followup_b",
            "type" => "question",
            "label" => "Follow-up for Option B?",
            "description" => "",
            "type_of_question" => "checkbox",  // Multiple selection
            "options" => [
                ["value" => "b1", "label" => "Checkbox 1"],
                ["value" => "b2", "label" => "Checkbox 2"],
                ["value" => "b3", "label" => "Checkbox 3"]
            ],
            "conditions" => [
                [
                    "questionId" => "q1_initial",
                    "value" => "option_b",
                    "operator" => "==="
                ]
            ]
        ],
        
        // Question 4: Multiple condition example
        // Only shown if Q1=option_a AND Q2=a1
        [
            "id" => "q3_deep_followup",
            "type" => "question",
            "label" => "Deep follow-up question?",
            "description" => "",
            "type_of_question" => "dropdown",
            "options" => [
                ["value" => "drop1", "label" => "Dropdown 1"],
                ["value" => "drop2", "label" => "Dropdown 2"]
            ],
            "conditions" => [
                [
                    "questionId" => "q1_initial",
                    "value" => "option_a",
                    "operator" => "==="
                ],
                [
                    "questionId" => "q2_followup_a",
                    "value" => "a1",
                    "operator" => "==="
                ]
            ]
        ],
        
        // Question 5: Using "in" operator (multiple values)
        // Shown if Q1 was either option_a OR option_c
        [
            "id" => "q2_either_a_or_c",
            "type" => "question",
            "label" => "Question for A or C?",
            "description" => "",
            "type_of_question" => "radio",
            "options" => [
                ["value" => "yes", "label" => "Yes"],
                ["value" => "no", "label" => "No"]
            ],
            "conditions" => [
                [
                    "questionId" => "q1_initial",
                    "value" => ["option_a", "option_c"],  // Array for "in" operator
                    "operator" => "in"                     // Check if value in array
                ]
            ]
        ],
        
        // Question 6: Using "exists" operator
        // Shown only if a question was answered (not their value)
        [
            "id" => "q4_after_any_answer",
            "type" => "question",
            "label" => "Do you need more help?",
            "description" => "",
            "type_of_question" => "radio",
            "options" => [
                ["value" => "yes", "label" => "Yes, more info"],
                ["value" => "no", "label" => "No, I'm done"]
            ],
            "conditions" => [
                [
                    "questionId" => "q1_initial",
                    "operator" => "exists"  // Just check if answered, no value needed
                ]
            ]
        ]
        
    ]
];

// ============================================================================
// STEP 2: DEFINE YOUR RESULTS
// ============================================================================

$myCustomResults = [
    
    // Result 1: When Q1=option_a AND Q2=a1
    [
        "slug" => "result-path-a1",
        "title" => "Result A1 Title",
        "description" => "You took path A → A1",
        "conditions" => [
            [
                "questionId" => "q1_initial",
                "value" => "option_a",
                "operator" => "==="
            ],
            [
                "questionId" => "q2_followup_a",
                "value" => "a1",
                "operator" => "==="
            ]
        ],
        "compatibility_score" => 100,
        "recommendations" => "
            <h3>Your Results</h3>
            <p>You selected A then A1. Here's what we recommend:</p>
            <ul>
                <li>Recommendation 1</li>
                <li>Recommendation 2</li>
                <li>Recommendation 3</li>
            </ul>
        "
    ],
    
    // Result 2: When Q1=option_a AND Q2=a2
    [
        "slug" => "result-path-a2",
        "title" => "Result A2 Title",
        "description" => "You took path A → A2",
        "conditions" => [
            [
                "questionId" => "q1_initial",
                "value" => "option_a",
                "operator" => "==="
            ],
            [
                "questionId" => "q2_followup_a",
                "value" => "a2",
                "operator" => "==="
            ]
        ],
        "compatibility_score" => 75,
        "recommendations" => "
            <h3>Your Results</h3>
            <p>You selected A then A2.</p>
            <ul>
                <li>Different recommendation 1</li>
                <li>Different recommendation 2</li>
            </ul>
        "
    ],
    
    // Result 3: When Q1=option_b (regardless of checkboxes selected)
    [
        "slug" => "result-path-b",
        "title" => "Result B Title",
        "description" => "You took path B",
        "conditions" => [
            [
                "questionId" => "q1_initial",
                "value" => "option_b",
                "operator" => "==="
            ]
        ],
        "compatibility_score" => 80,
        "recommendations" => "
            <h3>Path B Results</h3>
            <p>You chose option B:</p>
            <ul>
                <li>Path B specific advice</li>
            </ul>
        "
    ],
    
    // Result 4: Default/fallback (should be last as first match wins)
    // Using "in" operator - matches anyone who selected option_c
    [
        "slug" => "result-path-c",
        "title" => "Result C Title",
        "description" => "You took path C",
        "conditions" => [
            [
                "questionId" => "q1_initial",
                "value" => "option_c",
                "operator" => "==="
            ]
        ],
        "compatibility_score" => 60,
        "recommendations" => "
            <h3>Path C Results</h3>
            <p>You chose option C.</p>
        "
    ]
    
];

// ============================================================================
// STEP 3: SAVE TO DATABASE
// ============================================================================

/**
 * To use this configuration:
 * 
 * 1. Include the helper files:
 *    require_once 'php/helper/database.php';
 *    require_once 'php/helper/quiz.php';
 * 
 * 2. Create the quiz:
 *    $quizId = QuizHelper::createOrUpdateQuiz(
 *        'my-quiz-slug',              // Unique slug for URL
 *        'My Quiz Title',             // Display title
 *        'My quiz description',       // Description text
 *        $myCustomQuizConfig          // Config array
 *    );
 * 
 * 3. Add the results:
 *    foreach ($myCustomResults as $result) {
 *        QuizHelper::createOrUpdateResult(
 *            $quizId,
 *            $result['slug'],
 *            $result['title'],
 *            $result['description'],
 *            $result['conditions'],
 *            $result['compatibility_score'],
 *            $result['recommendations']
 *        );
 *    }
 * 
 * 4. Test at: /quiz/my-quiz-slug
 */

// ============================================================================
// REFERENCE: OPERATORS & OPTIONS
// ============================================================================

/**
 * QUESTION TYPES:
 * - "radio"     - Single selection (mutually exclusive)
 * - "checkbox"  - Multiple selections allowed
 * - "dropdown"  - Dropdown select (single selection)
 * 
 * CONDITION OPERATORS:
 * - "==="       - Exact value match. Example: value: "option_a"
 * - "in"        - Value in array. Example: value: ["option_a", "option_b"]
 * - "exists"    - Question has been answered. No value field needed.
 * - "notExists" - Question has NOT been answered. No value field needed.
 * 
 * IMPORTANT NOTES:
 * - Question IDs must match exactly in conditions
 * - Results are checked in order - first match wins!
 * - Put more specific results before general ones
 * - All conditions in an object must be true (AND logic)
 * - Multiple results can match same path (first one wins)
 */

?>
