<?php
// controllers/posts/start.php
$style = "/css/quiz.css";

$categoryId = isset($_GET['topic']) ? (int)$_GET['topic'] : 0;
$quizId     = isset($_GET['quiz'])  ? (int)$_GET['quiz']  : 0;

if ($categoryId <= 0) {
    http_response_code(400);
    $error = "No topic selected.";
    require "views/posts/error.view.php";
    exit;
}

// Load category
$category = $db->query(
    "SELECT category_id, name FROM categories WHERE category_id = :id",
    ["id" => $categoryId]
)->fetch();

if (!$category) {
    http_response_code(404);
    $error = "Selected topic not found.";
    require "views/posts/error.view.php";
    exit;
}

// Determine quiz
if ($quizId > 0) {
    // Explicit quiz chosen
    $quiz = $db->query(
        "SELECT quiz_id, title, description, category_id, created_at
         FROM quizzes
         WHERE quiz_id = :qid",
        ["qid" => $quizId]
    )->fetch();

    if (!$quiz) {
        $error = "Quiz not found.";
        require "views/posts/error.view.php";
        exit;
    }
    if ((int)$quiz['category_id'] !== $categoryId) {
        $error = "Selected quiz does not belong to this topic.";
        require "views/posts/error.view.php";
        exit;
    }
} else {
    // Fallback: latest quiz in this topic
    $quiz = $db->query(
        "SELECT quiz_id, title, description, category_id, created_at
         FROM quizzes
         WHERE category_id = :cid
         ORDER BY created_at DESC, quiz_id DESC
         LIMIT 1",
        ["cid" => $categoryId]
    )->fetch();

    if (!$quiz) {
        $error = "No quiz found for the selected topic.";
        require "views/posts/error.view.php";
        exit;
    }
}

// Questions
$questions = $db->query(
    "SELECT question_id, question_text
     FROM questions
     WHERE quiz_id = :qid
     ORDER BY question_id ASC",
    ["qid" => $quiz["quiz_id"]]
)->fetchAll();

// Answers (randomize on every load)
$qa = [];
foreach ($questions as $q) {
    $answers = $db->query(
        "SELECT answer_id, answer_text, is_correct
         FROM answers
         WHERE question_id = :qid",
        ["qid" => $q["question_id"]]
    )->fetchAll();
    shuffle($answers);
    $qa[] = ["question" => $q, "answers" => $answers];
}

require "views/posts/start.view.php";
