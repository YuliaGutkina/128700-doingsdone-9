<?php
date_default_timezone_set('Asia/Jerusalem');

if(file_exists('config.php')) {
    require_once 'config.php';
} else {
    exit('Скопируйте config.default.php в config.php и установите настройки приложения');
}

require_once('helpers.php');
require_once('functions.php');

session_start();
