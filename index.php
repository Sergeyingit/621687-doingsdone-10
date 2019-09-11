<?php
// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);

require_once('functions.php');

require_once('db.php');



if(!$link) {
    $error = mysqli_connect_error();
    print($error);
} else {
    $sort_field = '';

    if ($_GET['id']) {
        $sort_field = 'AND ' . 'p.id = ' . $_GET['id'];
    }

    $sql_projects = "SELECT p.name, p.id FROM projects p JOIN users u ON p.user_id = u.id WHERE u.name = 'newuser'";
    $sql_tasks = "SELECT t.name AS task, t.date_completed AS date, p.name AS category, t.complete AS is_complete FROM tasks t JOIN projects p ON t.project_id = p.id JOIN users u ON p.user_id = u.id WHERE u.name = 'newuser'" . $sort_field;



    $projects = get_data_from_db ($link, $sql_projects);
    $tasks = get_data_from_db ($link, $sql_tasks);


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
