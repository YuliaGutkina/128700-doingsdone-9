<?php
require_once 'init.php';

$projects = getProjects($user['id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task = $_POST;
    $required = ['name', 'project'];
    $errors = [];

    $task = [
        'name' => $_POST['name'] ?? null,
        'project' => $_POST['project'] ?? null,
        'date' => $_POST['date'] ?? null
    ];

    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] = 'Это поле надо заполнить';
        }
    }

    if (!empty($task['date']) && !is_date_valid($task['date'])) {
        $errors['date'] = 'Введите дату в корректном формате';
    }

    if (!empty($task['date']) && !isDateFuture($task['date'])) {
        $errors['date'] = 'Введите не прошедшую дату';
    }

    if (isset($_FILES['file']) && isset($_FILES['file']['error'])) {
        if (($_FILES['file']['error'] === UPLOAD_ERR_OK)) {
            if (isset($_FILES['file']['name'])&& isset($_FILES['file']['tmp_name']) && !count($errors)) {
                $fileName = $_FILES['file']['name'];
                $filePath = __DIR__ . '/uploads/';
                $fileUrl = '/uploads/' . $fileName;
                move_uploaded_file($_FILES['file']['tmp_name'], $filePath . $fileName);
                $task['file'] = $fileName;
            }
        } else if ($_FILES['file']['error'] !== UPLOAD_ERR_NO_FILE) {
            $errors['file'] = 'Не удалось загрузить файл';
        }
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
