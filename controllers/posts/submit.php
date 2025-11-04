<?php
// controllers/posts/submit.php
// Uses $db created in your main index.php

$style = "/css/quiz.css";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: /posts");
    exit;
}

$quizId   = (int)($_POST["quiz_id"] ?? 0);
$selected = $_POST["answers"] ?? []; // [question_id => answer_id]

if ($quizId <= 0 || !is_array($selected)) {
    $error = "Invalid submission.";
    require "views/posts/error.view.php";
    exit;
}

// Load quiz + category name
$quiz = $db->query(
    "SELECT q.quiz_id, q.title, q.category_id, c.name AS category_name
     FROM quizzes q
     LEFT JOIN categories c ON c.category_id = q.category_id
     WHERE q.quiz_id = :qid",
    ["qid" => $quizId]
)->fetch();

if (!$quiz) {
    $error = "Quiz not found.";
    require "views/posts/error.view.php";
    exit;
}

// Map: question_id => correct answer_id
$rows = $db->query(
    "SELECT a.question_id, a.answer_id
     FROM answers a
     INNER JOIN questions q ON q.question_id = a.question_id
     WHERE q.quiz_id = :qid AND a.is_correct = 1",
    ["qid" => $quizId]
)->fetchAll();

$correctMap = [];
foreach ($rows as $r) {
    $correctMap[(int)$r["question_id"]] = (int)$r["answer_id"];
}

$total = count($correctMap);
$correctCount = 0;
$breakdown = [];

// Load question texts
$qRows = $db->query(
    "SELECT question_id, question_text FROM questions WHERE quiz_id = :qid",
    ["qid" => $quizId]
)->fetchAll();
$qText = [];
foreach ($qRows as $q) {
    $qText[(int)$q['question_id']] = $q['question_text'];
}

foreach ($correctMap as $qid => $correctAid) {
    $chosenAid = isset($selected[$qid]) ? (int)$selected[$qid] : 0;

    $chosen = $chosenAid ? $db->query(
        "SELECT answer_text FROM answers WHERE answer_id = :aid",
        ["aid" => $chosenAid]
    )->fetch() : null;

    $correct = $db->query(
        "SELECT answer_text FROM answers WHERE answer_id = :aid",
        ["aid" => $correctAid]
    )->fetch();

    $isCorrect = ($chosenAid === $correctAid);
    if ($isCorrect) $correctCount++;

    $breakdown[] = [
        "question"     => $qText[$qid] ?? ("Question #".$qid),
        "chosen_text"  => $chosen['answer_text'] ?? "(no answer)",
        "correct_text" => $correct['answer_text'] ?? "(missing)",
        "is_correct"   => $isCorrect
    ];
}

$scorePct = $total > 0 ? round(($correctCount / $total) * 100, 2) : 0;

require "views/posts/result.view.php";
