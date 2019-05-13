<?php
require_once 'init.php';

$pageContent = include_template('register.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];
    $user = [
        'email' => $_POST['email'] ?? null,
        'password' => $_POST['password'] ?? null,
        'name' => $_POST['name'] ?? null
    ];

    foreach ($_POST as $key => $value) {
        if (($key == "email") && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $errors[$key] = 'E-mail введён некорректно';
        }

        if (($key == "email") && checkIfUserExist($value)) {
            $errors[$key] = 'Пользователь с эти e-mail уже существует';
        }

        if (empty($_POST[$key])) {
            $errors[$key] = 'Это поле надо заполнить';
        }
    }

    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] = 'Это поле надо заполнить';
        }
    }

    if (count($errors)) {
        $pageContent = include_template('register.php', [
            'errors' => $errors
        ]);
    } else {
        addUser($_POST['email'], $_POST['password'], $_POST['name']);
        header('Location: /index.php');
    }
} else {
    $pageContent = include_template('register.php');
}

$layoutContent = include_template('layout.php', [
    'pageContent' => $pageContent,
    'pageTitle' => 'Дела в порядке',
]);

print($layoutContent);
