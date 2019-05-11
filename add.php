<?php
date_default_timezone_set('Asia/Jerusalem');

if(file_exists('config.php')) {
    require_once 'config.php';
} else {
    exit('Скопируйте config.default.php в config.php и установите настройки приложения');
}

require_once('helpers.php');
require_once('functions.php');

$showCompleteTasks = 1;

$user = getUser(1);

if (!isset($user['id']))
{
    $layoutContent = include_template('guest.php');
    print($layoutContent);

    die;
}

$projects = getProjects($user['id']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $task = $_POST;

    $required = ['name', 'project'];

    $task = [
        'name' => $_POST['name'],
        'project' => $_POST['project'],
        'date' => $_POST['date']
    ];

    $errors = [];

    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] = 'Это поле надо заполнить';
        }
    }

    foreach ($_POST as $key => $value) {
        if ($key === "date") {
            if (!empty($value) && !is_date_valid($value)) {
                $errors[$key] = 'Введите дату в корректном формате';
            }
        }
    }

    if (isset($_FILES['file'])) {
        $fileName = $_FILES['file']['name'];
        $filePath = __DIR__ . '/uploads/';
        $fileUrl = '/uploads/' . $fileName;
        move_uploaded_file($_FILES['file']['tmp_name'], $filePath . $fileName);
        $task['file'] = $fileName;
    }

    if (count($errors)) {
        $pageContent = include_template('form-task.php', [
            'projects' => $projects,
            'errors' => $errors,
            'task' => $task
        ]);
    } else {
        addTask($task, $user['id']);
        header('Location: /index.php');
    }
} else {
    $pageContent = include_template('form-task.php', ['projects' => $projects]);
}

$layoutContent = include_template('layout.php', [
    'pageContent' => $pageContent,
    'pageTitle' => 'Дела в порядке',
    'user' => $user,
    'projects' => $projects
]);

print($layoutContent);
