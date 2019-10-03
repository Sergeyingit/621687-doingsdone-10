<?php
$db = ['host' => 'localhost', 'user' => 'root', 'password' => '', 'database' => 'doingsdone'];

$link = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);
mysqli_set_charset($link, 'utf8');

if(!$link) {
    $error = mysqli_connect_error();
    print($error);
}

date_default_timezone_set('Europe/Moscow');
