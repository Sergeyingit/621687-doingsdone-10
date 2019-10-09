<?php

require_once('init.php');
require_once('user-data.php');

if(empty($_SESSION['user'])) {
    $page_content = include_template('guest.php', []);
    $layout_content = include_template('layout.php', [
        'content' => $page_content
    ]);
    print($layout_content);
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $errors = [];

    $required = ['name'];

    $rules = [
        'name' => function () {
            return validate_length('name', 128);
        }
    ];

    foreach ($_POST as $input_name => $input_value) {
        if (isset($rules[$input_name])) {
            $rule = $rules[$input_name];
            $errors[$input_name] = $rule();
        }

        $errors = array_filter($errors);
        if (empty($errors)) {
            foreach ($projects as $project) {
                $project_field = mb_strtolower ($input_value);
                $project_db = mb_strtolower($project['name']);
                if ($project_field === $project_db) {
                    $errors[$input_name] = 'Проект с таким названием уже существует';

                }
            }
        }
    }

    foreach ($required as $key) {
        if (empty(trim($_POST[$key]))) {
            $errors[$key] = 'Это поле должно быть заполнено';
        }
    }

    $errors = array_filter($errors);

    if (empty($errors)) {
        $sql = 'INSERT INTO projects (name, user_id) VALUES (?, ?)';
        $result = set_result_prepare_request($link, $sql, [$_POST['name'], $_SESSION['user']['id']]);

        if ($result) {
            header('Location: index.php');
        }
    }

}

$navigation = include_template('navigation.php', [
    'projects' => $projects,
    'tasks' => $tasks_all
]);

$page_content = include_template('add-project.php', [
    'navigation' => $navigation,
    'projects' => $projects,
    'errors' => $errors
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'user' => $_SESSION['user'],
    'title' => 'Дела в порядке'
]);

print($layout_content);
