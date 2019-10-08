<?php

require_once('functions.php');
require_once('db.php');
session_start();
date_default_timezone_set('Europe/Moscow');

if (isset($_SESSION['user'])) {
    $sql_projects = 'SELECT p.name, p.id FROM projects p JOIN users u ON p.user_id = u.id WHERE u.id = ?';
    $projects = get_result_prepare_request($link, $sql_projects, [$_SESSION['user']['id']]);

    $sql_tasks = 'SELECT t.name AS task, t.date_completed AS date, p.name AS category, t.complete AS is_complete, t.file AS file, t.id AS id FROM tasks t JOIN projects p ON t.project_id = p.id JOIN users u ON p.user_id = u.id WHERE u.id = ?';
    $tasks_all = get_result_prepare_request($link, $sql_tasks, [$_SESSION['user']['id']]);
}
