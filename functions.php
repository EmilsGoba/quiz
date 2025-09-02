<?php

function dd($data){
    echo "<pre>";
    var_dump($data);
    echo "</pre>";
    die();
}

function redirectIfNotFound($location = "/"){
    http_response_code(404);
    header("Location: $location", 302);
    exit();
}
function guest() {
    if (isset($_SESSION["logged_in"])) {
        header("Location: /"); // redirect to homepage or dashboard
        exit;
    }
}

// 👨‍🎓 Student Middleware — Can only view their own grades
function studentOnly() {
    if (!isset($_SESSION["logged_in"]) || $_SESSION["role"] !== 'student') {
        header("Location: /login");
        exit;
    }
}

// 👩‍🏫 Teacher Middleware — Can see everything (admin-like)
function teacherOnly() {
    if (!isset($_SESSION["logged_in"]) || $_SESSION["role"] !== 'teacher') {
        header("Location: /login");
        exit;
    }
}

// ✅ Authenticated (general check)
function auth() {
    if (!isset($_SESSION["logged_in"])) {
        header("Location: /login");
        exit;
    }
}