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

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $tasks = getTasks($user['id'], $_GET['id']);

    if (empty($tasks)) {
        http_response_code(404);
    }

} else if (isset($_GET['id']) && empty($_GET['id'])) {
    $tasks = [];
    http_response_code(404);

} else {
    $tasks = getTasks($user['id']);
}

$pageContent = (http_response_code() !== 404) ? include_template('index.php', [
    'tasks' => $tasks,
    'showCompleteTasks' => $showCompleteTasks
]) : '404 - задач не найдено';

$layoutContent = include_template('layout.php', [
    'pageContent' => $pageContent,
    'pageTitle' => 'Дела в порядке',
    'user' => $user,
    'projects' => $projects
]);

print($layoutContent);
