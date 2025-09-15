<?php
guest();

require "Validator.php";

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!Validator::string($_POST["username"], min:2, max:50)) {
        $errors["content"] = "Too short username or too long";
    }

    if (empty($errors)) {
    $sql = "INSERT INTO users (username, email, password_hash, role) 
            VALUES (:username, :email, :password_hash, :role)";
    $params = [
        "username" => $_POST["username"],
        "email" => $_POST["email"],
        "password_hash" => password_hash($_POST["password"], PASSWORD_BCRYPT),
        "role" => "student"   // 👈 always assign "student"
    ];
    $db->query($sql, $params);

    header("Location: /posts"); 
    exit();
}
};

$title = "Register";

require "views/auth/register.view.php";