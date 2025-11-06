<?php
$style = "/css/index.css";

$sql = "
    SELECT
        u.username,
        MAX(qa.score) AS highest_score
    FROM quiz_attempts qa
    JOIN users u ON qa.user_id = u.user_id
    WHERE qa.score IS NOT NULL
    GROUP BY qa.user_id, u.username
    ORDER BY highest_score DESC
";

$statement = $db->query($sql, []);
$leaderboard_data = $statement->fetchAll();

$page_title = "Full Leaderboard";

// Load the view from the correct path (based on your screenshot)
require "views/posts/leaderboard.view.php";