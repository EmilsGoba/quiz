<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get the results from the session
$result = $_SESSION['last_quiz_result'] ?? null;

// If there are no results, redirect home
if (!$result) {
    header('Location: /posts');
    exit();
}

// Clear the session variable so it's not shown again on refresh
unset($_SESSION['last_quiz_result']);

$page_title = "Quiz Results";

// Load the view from the correct path (based on your screenshot)
require "views/posts/result.view.php";