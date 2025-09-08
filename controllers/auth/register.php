<?php
guest();

require "Validator.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = [];
    if (!Validator::string($_POST["username"], $min=3, $max=50)) {
        $errors["username"] = "Username has to be 3-50 chars!";
    }
    
    
    $result = $auth->getUser($_POST["username"]);

    if ($result) {
        $errors["username"] = "Username already taken!";
    }

    if (empty($errors)) {
        $auth->register($_POST["username"], $_POST["password"], $_POST["email"]);

        $_SESSION["flash"] = "Registered";
        header("Location: /login");
        die();
    }
}

$title = "Register";
require "views/auth/register.view.php";