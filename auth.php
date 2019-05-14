<?php
require_once 'init.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $required = ['email', 'password'];
    $errors = [];
    $user = [
        'email' => $_POST['email'] ?? null,
        'password' => $_POST['password'] ?? null
    ];

    if (!empty($_POST['email'])) {
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'E-mail введён некорректно';
        }

        if (!checkIfUserExist($_POST['email'])) {
            $errors['email'] = 'Пользователя с этим e-mail не существует';
        }
    }

    if (!empty($_POST['password'])) {
        if (!password_verify($_POST['password'], getUser($_POST['email'])['password'])) {
            $errors['password'] = 'Пароль неверный';
        }
    }

    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] = 'Это поле надо заполнить';
        }
    }

    if (count($errors)) {
        $pageContent = include_template('auth.php', [
            'errors' => $errors
        ]);

    } else {
        header('Location: /index.php');

        $_SESSION['user'] = getUser($_POST['email']);
    }

} else {
    $pageContent = include_template('auth.php');
}

$layoutContent = include_template('layout.php', [
    'pageContent' => $pageContent,
    'pageTitle' => 'Дела в порядке',
]);

print($layoutContent);
