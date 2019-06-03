<?php
require_once 'init.php';
require_once "vendor/autoload.php";

$transport = new Swift_SmtpTransport('phpdemo.ru', 25);

$transport = (new Swift_SmtpTransport('phpdemo.ru', 25))
    ->setUsername('keks@phpdemo.ru')
    ->setPassword('htmlacademy');

$users = getAllUsers();

foreach ($users as $user) {
    if (isset($user['name'], $user['email'], $user['id'])) {
        sendNotification($user['id'], $user['name'], $user['email'], $transport);
    }
};
