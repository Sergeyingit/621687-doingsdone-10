<?php

    require_once('functions.php');

    require_once('db.php');

    session_start();
    date_default_timezone_set('Europe/Moscow');

    $sql_projects = 'SELECT p.name, p.id FROM projects p JOIN users u ON p.user_id = u.id WHERE u.id = ?';
    $stmt = db_get_prepare_stmt($link, $sql_projects, [$_SESSION['user']['id']]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $projects = mysqli_fetch_all($result, MYSQLI_ASSOC);
    // $projects = get_prepare_request($link, $sql_projects, [$_SESSION['user']['id']]);
    // $tasks_all = get_prepare_request($link, $sql_tasks);

    $sql_tasks = 'SELECT t.name AS task, t.date_completed AS date, p.name AS category, t.complete AS is_complete FROM tasks t JOIN projects p ON t.project_id = p.id JOIN users u ON p.user_id = u.id WHERE u.id = ?';
    $stmt = db_get_prepare_stmt($link, $sql_tasks, [$_SESSION['user']['id']]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $tasks_all = mysqli_fetch_all($result, MYSQLI_ASSOC);
