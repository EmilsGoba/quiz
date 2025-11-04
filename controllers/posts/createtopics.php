<?php

$style = "/css/topics.css";

require "Validator.php";

$errors = [];
$success = null;

$old = [
    "name" => $_POST["name"] ?? "",
];

// Handle POST (either create OR delete)
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // 1) DELETE topic
    if (isset($_POST['delete_id'])) {
        $deleteId = (int)($_POST['delete_id'] ?? 0);

        if ($deleteId > 0) {
            try {
                $db->query(
                    "DELETE FROM categories WHERE category_id = :id",
                    ["id" => $deleteId]
                );
                $success = "Topic deleted.";
            } catch (Throwable $e) {
                // Likely a FK constraint (quizzes still reference this topic)
                $errors["delete"] = "Cannot delete this topic because it is used by existing quizzes.";
            }
        } else {
            $errors["delete"] = "Invalid topic selected for deletion.";
        }

    // 2) CREATE topic
    } else {
        $name = trim($_POST["name"] ?? "");

        // Validation via Validator.php
        if ($msg = Validator::validateTopicName($name)) {
            $errors["name"] = $msg;
        }

        // Uniqueness check
        if (!$errors && !Validator::unique($db, 'categories', 'name', $name)) {
            $errors["name"] = "A topic with this name already exists.";
        }

        if (!$errors) {
            // No description anymore
            $db->query(
                "INSERT INTO categories (name) VALUES (:name)",
                ["name" => $name]
            );
            $success = "Topic created successfully.";
            $old = ["name" => ""];
        }
    }
}

// Fetch topics to display
$categories = $db->query(
    "SELECT category_id, name FROM categories ORDER BY category_id DESC",
    []
)->fetchAll();

require "views/posts/createtopics.view.php";
