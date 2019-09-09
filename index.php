<?php
// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);
// $projects = [
//     'inbox' => 'Входящие',
//     'study' => 'Учеба',
//     'work' => 'Работа',
//     'home' => 'Домашние дела',
//     'auto' => 'Авто'
// ];
/*$tasks = [
    [
        'task' => 'Собеседование в IT компании',
        'date' => '01.12.2019',
        'category' => $projects['work'],
        'is_complete' => false
    ],
    [
        'task' => 'Выполнить тестовое задание',
        'date' => '25.12.2019',
        'category' => $projects['work'],
        'is_complete' => false
    ],
    [
        'task' => 'Сделать задание первого раздела',
        'date' => '21.12.2019',
        'category' => $projects['study'],
        'is_complete' => true
    ],
    [
        'task' => 'Встреча с другом',
        'date' => '22.12.2019',
        'category' => $projects['inbox'],
        'is_complete' => false
    ],
    [
        'task' => 'Купить корм для кота',
        'date' => null,
        'category' => $projects['home'],
        'is_complete' => false
    ],
    [
        'task' => 'Заказать пиццу',
        'date' => null,
        'category' => $projects['home'],
        'is_complete' => false
    ],
];
*/
require_once('functions.php');

require_once('db.php');



if(!$link) {
    $error = mysqli_connect_error();
    print($error);
} else {
    $sql_projects = "SELECT p.name FROM projects p JOIN users u ON p.user_id = u.id WHERE u.name = 'newuser'";
    $sql_tasks = "SELECT t.name AS task, t.date_completed AS date, p.name AS category, t.complete AS is_complete FROM tasks t JOIN projects p ON t.project_id = p.id JOIN users u ON p.user_id = u.id WHERE u.name = 'newuser'";

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
