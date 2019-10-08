<?php
$db = [
    'host' =>
        'localhost',
    'user' =>
        'root',
    'password' => '',
    'database' => 'doingsdone'
];

$link = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);
mysqli_set_charset($link, 'utf8');

if (!$link) {
    $error = mysqli_connect_error();
    $layout_content = include_template('error.php', [
        'error_message' => $error
    ]);

    print($layout_content);
}

