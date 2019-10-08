<?php

require_once('init.php');
if(isset($_SESSION['user'])){
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $errors = [];

        $rules = [
            'name' => function() {
                return validate_filled('name');
            }
        ];

        foreach ($_POST as $input_name => $input_value) {
            if (isset($rules[$input_name])) {
                $rule = $rules[$input_name];
                $errors[$input_name] = $rule();
            }

            $errors = array_filter($errors);

            if(!$errors) {
                foreach($projects as $project) {
                    if($input_value == $project['name']) {
                        $errors[$input_name] = 'Проект с таким названием уже существует';
                    }
                }
            }
        }

        $errors = array_filter($errors);

        if(!count($errors)) {
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

    $page_content = include_template('add-form.php', [
        'navigation' => $navigation,
        'projects' => $projects,
        'errors' => $errors
    ]);
} else {
    $page_content = include_template('guest.php', []);
}
$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'user' => $_SESSION['user'],
    'title' => 'Дела в порядке'
]);

print($layout_content);
