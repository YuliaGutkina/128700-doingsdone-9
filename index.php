<?php
require_once('helpers.php');

$showCompleteTasks = rand(0, 1);
$projects = ['Входящие', 'Учеба', 'Работа', 'Домашние дела', 'Авто'];
$tasks = [
    [
        'name' => 'Собеседование в IT компании',
        'date' => '01.12.2018',
        'category' => 'Работа',
        'done' => true
    ],
    [
        'name' => 'Выполнить тестовое задание',
        'date' => '	25.12.2018',
        'category' => 'Работа',
        'done' => false
    ],
    [
        'name' => 'Сделать задание первого раздела',
        'date' => '21.12.2018',
        'category' => 'Учеба',
        'done' => true
    ],
    [
        'name' => 'Встреча с другом',
        'date' => '	22.12.2018',
        'category' => 'Входящие',
        'done' => false
    ],
    [
        'name' => 'Купить корм для кота',
        'date' => null,
        'category' => 'Домашние дела',
        'done' => false
    ],
    [
        'name' => 'Заказать пиццу',
        'date' => null,
        'category' => 'Домашние дела',
        'done' => false
    ]
];
$userName = 'Константин';

function countTasks(array $tasks, $projectName) {
    $count = 0;

    foreach($tasks as $task) {
        if (isset($task['category']) && ($task['category'] === $projectName)) {
            $count++;
        }
    }

    return $count;
}

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
