<?php
date_default_timezone_set('Asia/Jerusalem');

require_once('helpers.php');
require_once('functions.php');

$con = mysqli_connect('127.0.0.1', 'root', '','doingsdone');
mysqli_set_charset($con, 'utf8');

$userId = 1;
$userName = 'Константин';

$showCompleteTasks = 1;

$sql = [
    'projects' => 'select p.id, p.name, count(t.id) as tasks_count '
                . 'from projects p '
                . 'left join tasks t on p.id = t.project_id '
                . 'where p.user_id = ? '
                . 'group by p.id',
    'tasks' => 'select * from tasks where user_id = ?'
];

if ($con === false) {
    print("Ошибка подключения: " . mysqli_connect_error());

} else {
    $resProjects = mysqli_prepare($con, $sql['projects']);
    $projects = dbFetchData($con, $sql['projects'], [$userId]);

    $resTasks = mysqli_prepare($con, $sql['tasks']);
    $tasks = dbFetchData($con, $sql['tasks'], [$userId]);

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
}
