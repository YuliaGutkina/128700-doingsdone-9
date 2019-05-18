<?php
require_once 'init.php';

if (!isset($_SESSION['user'])) {
    $guestContent = include_template('guest.php');

    $layoutContent = include_template('layout.php', [
        'guestContent' => $guestContent,
        'pageTitle' => 'Дела в порядке'
    ]);

    print($layoutContent);

    exit();
}

$user = $_SESSION['user'];
$projects = getProjects($user['id']);

if (isset($_GET['task_id'])) {
    switchTaskStatus($_GET['task_id']);
}

$projectId = $_GET['id'] ?? null;
$taskDate = $_GET['date'] ?? null;
$search = $_GET['search'] ?? null;

$tasks = getTasks($user['id'], $projectId, $taskDate, $search);

if (isset($_GET['id']) && empty($_GET['id'])) {
    http_response_code(404);
}

if (isset($_GET['date']) && empty($_GET['date'])) {
    http_response_code(404);
}

if (empty($tasks)) {
    http_response_code(404);
}

$pageContent = include_template('index.php', [
    'tasks' => $tasks,
    'showCompleteTasks' => $showCompleteTasks
]);

$layoutContent = include_template('layout.php', [
    'pageContent' => $pageContent,
    'pageTitle' => 'Дела в порядке',
    'user' => $user,
    'projects' => $projects
]);

print($layoutContent);
