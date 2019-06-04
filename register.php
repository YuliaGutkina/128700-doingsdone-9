<?php
require_once 'init.php';

if (isset($_SESSION['user'])) {
    header('Location: index.php');

    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $required = ['email', 'password', 'name'];
    $errors = [];
    $user = [
        'email' => $_POST['email'] ?? null,
        'password' => $_POST['password'] ?? null,
        'name' => $_POST['name'] ?? null
    ];

    if (!empty($_POST['email'])) {
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'E-mail введён некорректно';
        }

        if (checkIfUserExist($_POST['email'])) {
            $errors['email'] = 'Пользователь с таким e-mail уже существует';
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
