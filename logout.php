<?php
require_once 'init.php';

session_start();
unset($_SESSION['user']);

header('Location: index.php');
