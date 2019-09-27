<?php
require_once('functions.php');
require_once('db.php');

$sql_projects = "SELECT p.name, p.id FROM projects p JOIN users u ON p.user_id = u.id";



$projects = get_data_from_db ($link, $sql_projects);

$content = include_template('add.php', [
    'projects' => $projects
]);



$layout_content = include_template('layout.php', [
    'content' => $content,
    'user' => $user,
    'title' => 'Дела в порядке'
]);

print($content);
