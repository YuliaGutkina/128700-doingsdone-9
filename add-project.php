<?php
require_once 'init.php';

if (!isset($_SESSION['user'])) {
    header('Location: index.php');

    exit();
}

$user = $_SESSION['user'];
$projects = getProjects($user['id']);
$errors = [];
$project = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $required = ['name'];
    $project = [
        'name' => $_POST['name'] ?? null
    ];

    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] = 'Это поле надо заполнить';
        }
    }

    if(!empty($project['name']) && checkIfProjectExist( $user['id'], $project['name'])) {
        $errors['name'] = 'Проект с таким названием уже существует';
    }

    if (!count($errors)) {
        addProject($project['name'], $user['id']);
        header('Location: /index.php');
    }
}

$pageContent = include_template('add-project.php', [
    'project' => $project,
    'errors' => $errors
]);

$layoutContent = include_template('layout.php', [
    'pageContent' => $pageContent,
    'pageTitle' => 'Дела в порядке',
    'user' => $user,
    'projects' => $projects
]);

print($layoutContent);
