<?php

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to Quiz App</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding-top: 100px;
            background: #f4f4f9;
        }
        .container {
            background: white;
            padding: 40px;
            margin: auto;
            width: 300px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        h1 {
            color: #333;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            padding: 10px 20px;
            background: #4CAF50;
            color: white;
            border-radius: 8px;
            transition: background 0.3s;
        }
        a:hover {
            background: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to QuizzIt 🎉</h1>
        <p>Please login to continue.</p>
        <a href="/login">Go to Login</a>
        <p>If you don't have an account, register</p>
        <a href="/register">Register</a>
    </div>
</body>
</html>
