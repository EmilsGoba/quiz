<?php
// controllers/posts/index.php
$style = "/css/index.css";

// Topics
$topics = $db->query(
    "SELECT category_id, name FROM categories ORDER BY name ASC",
    []
)->fetchAll();

$selectedTopic = isset($_GET['topic']) ? (int)$_GET['topic'] : 0;
$selectedQuiz  = isset($_GET['quiz'])  ? (int)$_GET['quiz']  : 0;

$quizzes = [];
if ($selectedTopic > 0) {
    $quizzes = $db->query(
        "SELECT quiz_id, title
         FROM quizzes
         WHERE category_id = :cid
         ORDER BY created_at DESC, quiz_id DESC",
        ["cid" => $selectedTopic]
    )->fetchAll();

    // Ensure selected quiz actually belongs to this topic
    if ($selectedQuiz) {
        $ids = array_column($quizzes, 'quiz_id');
        if (!in_array($selectedQuiz, $ids, true)) {
            $selectedQuiz = 0;
        }
    }
}

$top_scores_sql = "
    SELECT
        u.username,
        MAX(qa.score) AS highest_score
    FROM quiz_attempts qa
    JOIN users u ON qa.user_id = u.user_id
    WHERE qa.score IS NOT NULL
    GROUP BY qa.user_id, u.username
    ORDER BY highest_score DESC
    LIMIT 5
";
$top_scores = $db->query($top_scores_sql, [])->fetchAll();

// Load the view (which now has access to $topics, $quizzes, and $top_scores)
require "views/posts/index.view.php";
