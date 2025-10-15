<?php

function dd($data) {
    echo "<pre>";
    var_dump($data);
    echo "</pre>";
    die();
}

function redirectIfNotFound($location = "/") {
    http_response_code(404);
    header("Location: $location", 302);
    exit();
}

function guest() {
    if (isset($_SESSION["logged_in"])) {
        header("Location: /");
        exit;
    }
}

function Student() {
    if (!isset($_SESSION["logged_in"]) || $_SESSION["role"] !== 'student') {
        header("Location: /login");
        exit;
    }
}

function Teacher() {
    if (!isset($_SESSION["logged_in"]) || $_SESSION["role"] !== 'teacher') {
        header("Location: /login");
        exit;
    }
}


function auth() {
    if (!isset($_SESSION["logged_in"])) {
        header("Location: /login");
        exit;
    }
}