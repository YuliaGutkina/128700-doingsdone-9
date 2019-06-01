<?php
require_once 'init.php';

if (!isset($_SESSION['user'])) {
    header('Location: index.php');

    exit();
}

$user = $_SESSION['user'];
$projects = getProjects($user['id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

    if (!empty($task['date']) && !checkIfDateFuture($task['date'])) {
        $errors['date'] = 'Введите не прошедшую дату';
    }

    if (isset($_FILES['file'], $_FILES['file']['error']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        if ((count($errors) === 0) && isset($_FILES['file']['name'], $_FILES['file']['tmp_name'])) {
            $fileName = $_FILES['file']['name'];
            $filePath = __DIR__ . '/uploads/';
            $fileUrl = '/uploads/' . $fileName;
            move_uploaded_file($_FILES['file']['tmp_name'], $filePath . $fileName);
            $task['file'] = $fileName;
        }

    } else if (isset($_FILES['file'], $_FILES['file']['error']) && $_FILES['file']['error'] !== UPLOAD_ERR_NO_FILE) {
        $errors['file'] = 'Не удалось загрузить файл';
    }

    if (count($errors)) {
        $pageContent = include_template('add-task.php', [
            'projects' => $projects,
            'errors' => $errors,
            'task' => $task
        ]);

    } else {
        $task['file'] = ($task['file'] && !empty($task['file'])) ? $task['file'] : null;
        $task['date'] = ($task['date'] && !empty($task['date'])) ? $task['date'] : null;

        addTask($task['name'], $task['project'], $user['id'], $task['file'], $task['date']);
        header('Location: /index.php');
    }

} else {
    $pageContent = include_template('add-task.php', ['projects' => $projects]);
}

$layoutContent = include_template('layout.php', [
    'pageContent' => $pageContent,
    'pageTitle' => 'Дела в порядке',
    'user' => $user,
    'projects' => $projects
]);

print($layoutContent);
