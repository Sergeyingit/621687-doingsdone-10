<?php

    session_start();
    date_default_timezone_set('Europe/Moscow');

    $sql_projects = 'SELECT p.name, p.id FROM projects p JOIN users u ON p.user_id = u.id';
    $sql_tasks = 'SELECT t.name AS task, t.date_completed AS date, p.name AS category, t.complete AS is_complete FROM tasks t JOIN projects p ON t.project_id = p.id JOIN users u ON p.user_id = u.id';
    $projects = get_prepare_request($link, $sql_projects);
    $tasks_all = get_prepare_request($link, $sql_tasks);
