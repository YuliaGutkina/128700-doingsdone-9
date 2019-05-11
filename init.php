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

$user = getUser(1);

if (!isset($user['id'])) {
    $layoutContent = include_template('guest.php');
    print($layoutContent);

    die;
}
