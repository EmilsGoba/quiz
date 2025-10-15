<?php

$title = "Register";
$style = "/css/register.css";
require "Validator.php";

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate username length
    if (!Validator::string($_POST["username"], min:2, max:50)) {
        $errors["username"] = "Too short username or too long";
    }

    // Check if username already exists
    $existingUser = $db->query(
        "SELECT * FROM users WHERE username = :username LIMIT 1",
        ["username" => $_POST["username"]]
    )->fetch();

    if ($existingUser) {
        $errors["username"] = "This username is already taken";
    }

    // Check if email already exists
    $existingEmail = $db->query(
        "SELECT * FROM users WHERE email = :email LIMIT 1",
        ["email" => $_POST["email"]]
    )->fetch();

    if ($existingEmail) {
        $errors["email"] = "This email is already registered";
    }

    // If no errors, insert new user
    if (empty($errors)) {
        $sql = "INSERT INTO users (username, email, password_hash, role) 
                VALUES (:username, :email, :password_hash, :role)";
        $params = [
            "username" => $_POST["username"],
            "email" => $_POST["email"],
            "password_hash" => password_hash($_POST["password"], PASSWORD_BCRYPT),
            "role" => "student"
        ];
        $db->query($sql, $params);

        header("Location: /posts"); 
        exit();
    }
}



require "views/auth/register.view.php";
