<?php
require_once 'init.php';
require_once "vendor/autoload.php";

$transport = new Swift_SmtpTransport('phpdemo.ru', 25);

$transport = (new Swift_SmtpTransport('phpdemo.ru', 25))
    ->setUsername('keks@phpdemo.ru')
    ->setPassword('htmlacademy')
;

$users = getAllUsers();

foreach ($users as $user) {
    if (isset($user['name'], $user['email'], $user['id'])) {
        sendNotification($user['id'], $user['name'], $user['email'], $transport);
    }
};

function sendNotification($userId, $userName, $userEmail, $transport) {
    $tasks = getTodayTasks($userId) ?? null;
    $time = date('Y-m-d');

    if (!count($tasks)) {
        return;
    }

    $tasksNames = array_map(function ($task) {
        if (isset($task[name])) {
            return $task['name'];
        }
    }, $tasks);

    $tasksNames = implode(', ', $tasksNames);

    $messageBody = "Уважаемый, $userName. У вас запланирована ";
    $messageBody .= "задача $tasksNames на $time";

    $message = new Swift_Message("Уведомление от сервиса «Дела в порядке»");
    $message->setTo([$userEmail => $userName]);
    $message->setBody($messageBody);
    $message->setFrom("keks@phpdemo.ru", "DoingsDone");
    $message->setContentType('text/plain');

    $mailer = new Swift_Mailer($transport);
    $mailer->send($message);
}
