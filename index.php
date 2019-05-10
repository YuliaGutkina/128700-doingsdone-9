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

$user = getUser(2);
$projects = getProjects($user['id']);

if (isset($_GET['id'])) {
    $tasks = getTasks($user['id'], $_GET['id']);

    if (empty($tasks) || empty($_GET['id'])) {
        http_response_code(404);
    }
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
    'projects' => $projects,
    'tasks' => $tasks
]);

print($layoutContent);
