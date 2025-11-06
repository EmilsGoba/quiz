<?php
$style = "/css/index.css";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Read result from session and show view (no leaderboard)
$result = $_SESSION['last_quiz_result'] ?? null;

if (!$result) {
    header('Location: /posts');
    exit();
}

$title = "Quiz Result";
$style = "/css/result.css";

// Render the simplified result view
require __DIR__ . '/../../views/posts/result.view.php';
?>