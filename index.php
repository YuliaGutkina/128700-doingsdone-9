<?php
date_default_timezone_set('Asia/Jerusalem');

require_once('helpers.php');
require_once('functions.php');
require_once('data.php');

$showCompleteTasks = rand(0, 1);
$userName = 'Константин';

$pageContent = include_template('index.php', [
    'tasks' => $tasks,
    'showCompleteTasks' => $showCompleteTasks
]);
$layoutContent = include_template('layout.php', [
    'pageContent' => $pageContent,
    'pageTitle' => 'Дела в порядке',
    'userName' => $userName,
    'projects' => $projects,
    'tasks' => $tasks
]);

print($layoutContent);
