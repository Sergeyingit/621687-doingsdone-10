<?php
// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);

require_once('functions.php');

require_once('db.php');

    $sql_projects = 'SELECT p.name, p.id FROM projects p JOIN users u ON p.user_id = u.id';
    $sql_tasks = 'SELECT t.name AS task, t.date_completed AS date, p.name AS category, t.complete AS is_complete FROM tasks t JOIN projects p ON t.project_id = p.id JOIN users u ON p.user_id = u.id';



    $projects = get_data_from_db ($link, $sql_projects);
    $tasks = get_data_from_db ($link, $sql_tasks);



    if (!empty($_GET['id'])) {
        $sql_tasks .= ' WHERE p.id = ?';
        $stmt = db_get_prepare_stmt($link, $sql_tasks, [$_GET['id']]);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    // Собираю массив id проектов, для проверки есть ли в нём id из параметра запроса
    $id_projects = [];
    foreach($projects as $project) {
        $id_projects[] = $project['id'];
    }

    if (!isset($_GET['id']) OR !in_array($_GET['id'], $id_projects)) {
        http_response_code(404);
        $tasks = [];
    }


$page_content = include_template('main.php', [
    'projects' => $projects,
    'tasks' => $tasks,
    'show_complete_tasks' => $show_complete_tasks
]);







$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'user' => $user,
    'title' => 'Дела в порядке'
]);

print($layout_content);
