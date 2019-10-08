<?php
// показывать или нет выполненные задачи
// $show_complete_tasks = rand(0, 1);

require_once('functions.php');

require_once('db.php');
require_once('init.php');

if(isset($_SESSION['user'])){
    $tasks = $tasks_all;

    if(isset($_GET['show_completed'])) {
        $show_completed = intval($_GET['show_completed']) ?? null;
        if($show_completed !== null) {
            $_SESSION['show_completed'] = $show_completed;
        }
    }

    if (!empty($_GET['id'])) {
        $sql_tasks = 'SELECT t.name AS task, t.date_completed AS date, p.name AS category, t.complete AS is_complete, t.file AS file, t.id AS id FROM tasks t JOIN projects p ON t.project_id = p.id JOIN users u ON p.user_id = u.id WHERE p.id = ?';
        $tasks = get_result_prepare_request($link, $sql_tasks, [$_GET['id']]);
    }

    // Собираю массив id проектов, для проверки есть ли в нём id из параметра запроса
    $id_projects = [];
    foreach($projects as $project) {
        $id_projects[] = $project['id'];
    }

    if (isset($_GET['id']) AND !in_array($_GET['id'], $id_projects)) {
        http_response_code(404);
        $page_content = include_template('main.php', [
            'error_message' => 'Задач не найдено'
        ]);
    } else {
        $page_content = include_template('main.php', [
            'projects' => $projects,
            'tasks' => $tasks,
            'show_complete_tasks' => $_SESSION['show_completed']
        ]);
    }

    if(isset($_GET['search'])) {
        $search = trim($_GET['search']);
        $sql_tasks = 'SELECT t.name AS task, t.date_completed AS date, p.name AS category, t.complete AS is_complete FROM tasks t JOIN projects p ON t.project_id = p.id JOIN users u ON p.user_id = u.id WHERE u.id = ? AND MATCH(t.name) AGAINST(?)';
        $tasks = get_result_prepare_request($link, $sql_tasks, [$_SESSION['user']['id'], $search]);

        if($tasks) {
            $page_content = include_template('main.php', [
                'projects' => $projects,
                'tasks' => $tasks,
                'show_complete_tasks' => $_SESSION['show_completed']
            ]);
        } else {
            $page_content = include_template('main.php', [
                'error_message' => 'Ничего не найдено по вашему запросу'
            ]);
        }
    }

    if(isset($_GET['tasks-filter'])) {
        $sql_tasks = 'SELECT t.name AS task, t.date_completed AS date, p.name AS category, t.complete AS is_complete FROM tasks t JOIN projects p ON t.project_id = p.id JOIN users u ON p.user_id = u.id WHERE u.id = ?';

        switch($_GET['tasks-filter']) {
            case 'today':
                $sql_tasks .= ' AND t.date_completed = CURDATE()';
                break;
            case 'tomorrow':
                $sql_tasks .= ' AND t.date_completed = ADDDATE(CURDATE(), INTERVAL 1 DAY)';
                break;
            case 'past_due':
                $sql_tasks .= ' AND t.complete = 0 AND t.date_completed < CURDATE()';
                break;
        }

        $stmt = db_get_prepare_stmt($link, $sql_tasks, [$_SESSION['user']['id']]);
        $tasks = get_result_prepare_request($link, $sql_tasks, [$_SESSION['user']['id']]);

        if($tasks) {
            $page_content = include_template('main.php', [
                'projects' => $projects,
                'tasks' => $tasks,
                'show_complete_tasks' => $_SESSION['show_completed']
            ]);
        } else {
            $page_content = include_template('main.php', [
                'error_message' => 'Ничего не найдено по вашему запросу'
            ]);
        }
    }

    if(isset($_GET['task_id']) AND isset($_GET['completed'])) {
        if($_GET['completed'] == 1) {
            $sql_update = 'UPDATE tasks t JOIN projects p ON t.project_id = p.id JOIN users u ON p.user_id = u.id SET t.complete = 1 WHERE u.id = ? AND t.id = ?';
            $stmt = db_get_prepare_stmt($link, $sql_update, [$_SESSION['user']['id'], $_GET['task_id']]);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

        } else {
            $sql_update = 'UPDATE tasks t JOIN projects p ON t.project_id = p.id JOIN users u ON p.user_id = u.id SET t.complete = 0 WHERE u.id = ? AND t.id = ?';
            $stmt = db_get_prepare_stmt($link, $sql_update, [$_SESSION['user']['id'], $_GET['task_id']]);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
        }
        header('Location: index.php');
    }

    $navigation = include_template('navigation.php', [
        'projects' => $projects,
        'tasks' => $tasks_all
    ]);

} else {
    $page_content = include_template('guest.php', []);
}


$layout_content = include_template('layout.php', [
    'navigation' => $navigation,
    'content' => $page_content,
    'user' => $_SESSION['user'],
    'title' => 'Дела в порядке'
]);

print($layout_content);
