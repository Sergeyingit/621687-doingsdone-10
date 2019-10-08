<?php
require_once('functions.php');
require_once('db.php');
require_once('init.php');


if(isset($_SESSION['user'])) {


// проверка была ли отправлена форма
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $projects_id = array_column($projects, 'id');
        $form_data = $_POST;

        $errors = [];

        $rules = [
            'name' => function() {
                return validate_filled('name');
            },
            'project' => function() {
                return validate_filled('project');
            },
            'date' => function() {
                return validate_date('date');
            }
        ];

        foreach ($_POST as $input_name => $input_value) {
            if (isset($rules[$input_name])) {
                $rule = $rules[$input_name];
                $errors[$input_name] = $rule();
            }

            if ($input_name == 'project' AND empty($errors['project'])) {
                $rule = function() use ($projects_id) {
                    return validate_project('project', $projects_id);
                };

                $errors[$input_name] = $rule();
            }
        }

        $errors = array_filter($errors);


        if (isset($_FILES['file']['name'])) {
            $tmp_name = $_FILES['file']['tmp_name'];
            $filename = basename($_FILES['file']['name']);
            $file_path = 'uploads/';
            move_uploaded_file($tmp_name, $file_path . $filename);
            $form_data['file'] = ($filename) ?? null;
        }

        // // Если массив ошибок пустой -
        if (!count($errors)) {
        // сохраняю данные формы в БД
            $form_data['date'] = empty($form_data['date']) ? null : $form_data['date'];
            $sql = 'INSERT INTO tasks (name, project_id, date_completed, file) VALUES (?, ?, ?, ?)';
            $result = set_result_prepare_request($link, $sql, $form_data);

            // при успешном сохранении отправляю на главную
            if ($result) {
                header('Location: index.php');
            }
        }
    }



    $navigation = include_template('navigation.php', [
        'projects' => $projects,
        'tasks' => $tasks_all
    ]);

    $page_content = include_template('add-task.php', [
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
