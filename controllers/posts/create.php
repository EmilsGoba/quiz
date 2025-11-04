<?php
// create.php
// Assumes index.php already set up $db and session.
// This file handles both GET (render form) and POST (save quiz).

$style = "css/create.css";

$errors = [];
$success = null;

// Load topics (categories) for the select
$categories = $db->query(
    "SELECT category_id, name FROM categories ORDER BY name ASC",
    []
)->fetchAll();

// Keep previous values on validation error
$old = [
    "title" => $_POST["title"] ?? "",
    "category_id" => $_POST["category_id"] ?? "",
    "description" => $_POST["description"] ?? "",
    "questions" => $_POST["questions"] ?? []
];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validate topic
    $category_id = (int)($_POST["category_id"] ?? 0);
    if ($category_id <= 0) {
        $errors["category_id"] = "Please select a topic.";
    } else {
        $exists = $db->query(
            "SELECT 1 FROM categories WHERE category_id = :id",
            ["id" => $category_id]
        )->fetch();
        if (!$exists) {
            $errors["category_id"] = "Selected topic was not found.";
        }
    }

    // Validate title
    $title = trim($_POST["title"] ?? "");
    if ($title === "") {
        $errors["title"] = "Quiz title is required.";
    } elseif (mb_strlen($title) > 255) {
        $errors["title"] = "Title must be at most 255 characters.";
    }
    $description = trim($_POST["description"] ?? "");

    // Validate questions
    $questions = $_POST["questions"] ?? [];
    $validQuestions = [];

    foreach ($questions as $i => $q) {
        $qText = trim($q["text"] ?? "");
        $choices = $q["choices"] ?? [];
        $choices = array_values(array_map(fn($v) => trim((string)$v), (array)$choices));

        // Skip completely empty blocks
        $anyFilled = ($qText !== "") || implode("", $choices) !== "";
        if (!$anyFilled) {
            continue;
        }

        if ($qText === "") {
            $errors["q{$i}_text"] = "Question " . ($i + 1) . " text is required.";
        }

        if (count($choices) !== 4 || in_array("", $choices, true)) {
            $errors["q{$i}_choices"] = "Question " . ($i + 1) . " must have 4 non-empty options.";
        }

        $correct = $q["correct"] ?? null;
        if ($correct === null || !in_array((string)$correct, ["0", "1", "2", "3"], true)) {
            $errors["q{$i}_correct"] = "Select the correct option for question " . ($i + 1) . ".";
        }

        $validQuestions[] = [
            "text" => $qText,
            "choices" => $choices,
            "correct" => (int)$correct
        ];
    }

    if (count($validQuestions) < 15) {
        $errors["min"] = "Please provide at least 15 complete questions (you have " . count($validQuestions) . ").";
    }

    // Insert if everything is valid
    if (!$errors) {
        try {
            $db->pdo->beginTransaction();

            // created_by can be set from session if you have auth; NULL is allowed
            $db->query(
                "INSERT INTO quizzes (title, description, category_id, created_by)
                 VALUES (:title, :description, :category_id, :created_by)",
                [
                    "title" => $title,
                    "description" => $description,
                    "category_id" => $category_id,
                    "created_by" => null
                ]
            );
            $quiz_id = $db->lastInsertId();

            foreach ($validQuestions as $q) {
                $db->query(
                    "INSERT INTO questions (quiz_id, question_text, question_type)
                     VALUES (:quiz_id, :question_text, 'multiple_choice')",
                    [
                        "quiz_id" => $quiz_id,
                        "question_text" => $q["text"]
                    ]
                );
                $question_id = $db->lastInsertId();

                foreach ($q["choices"] as $idx => $answerText) {
                    $db->query(
                        "INSERT INTO answers (question_id, answer_text, is_correct)
                         VALUES (:question_id, :answer_text, :is_correct)",
                        [
                            "question_id" => $question_id,
                            "answer_text" => $answerText,
                            "is_correct" => ($idx === $q["correct"]) ? 1 : 0
                        ]
                    );
                }
            }

            $db->pdo->commit();
            $success = "Quiz created successfully with " . count($validQuestions) . " questions.";
            // Clear form
            $old = ["title" => "", "category_id" => "", "description" => "", "questions" => []];
        } catch (Throwable $e) {
            if ($db->pdo->inTransaction()) {
                $db->pdo->rollBack();
            }
            $errors["db"] = "Failed to save the quiz. Please try again.";
            // Optionally log $e->getMessage()
        }
    }
}

require "views/posts/create.view.php";
