<?php


$style = "/css/login.css";

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"] ?? '';
    $password = $_POST["password"] ?? '';

    if (empty($email) || empty($password)) {
        $errors["form"] = "Email and password are required.";
    } else {
        $user = $db->query(
            "SELECT * FROM users WHERE email = :email LIMIT 1",
            ["email" => $email]
        )->fetch();

        if (!$user) {
            $errors["email"] = "No account found with this email.";
        } elseif (!password_verify($password, $user["password_hash"])) {
            $errors["password"] = "Incorrect password.";
        } else {
            $_SESSION["logged_in"] = true;
            $_SESSION["user"] = [
                "id" => $user["user_id"],
                "username" => $user["username"],
                "role" => $user["role"]
            ];
            $_SESSION["role"] = $user["role"];
            header("Location: /posts");
            exit();
        }
    }
}

$title = "Login";
require "views/auth/login.view.php";