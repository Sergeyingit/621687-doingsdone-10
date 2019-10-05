<?php
require_once('functions.php');
require_once('db.php');
require_once('init.php');

// получаю список проектов для валидации
// $sql_projects = 'SELECT p.name, p.id FROM projects p';
// $projects = get_prepare_request($link, $sql_projects);
// $sql_tasks = 'SELECT t.name AS task, t.date_completed AS date, p.name AS category, t.complete AS is_complete FROM tasks t JOIN projects p ON t.project_id = p.id JOIN users u ON p.user_id = u.id';
// $tasks = get_data_from_db($link, $sql_tasks);
$projects_id = array_column($projects, 'id');
// print_r($projects);
// echo '<br><br><br>';
// print_r($projects_name);

// проверка была ли отправлена форма
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $form_data = $_POST;
    $required = [
        'name',
        'project'
    ];

    $errors = [];

    $rules = [
        'name' => function() {
            return validate_filled('name');
        },
        'project' => function() {
            return validate_filled('project');
        },
        'project_id' => function() use ($projects_id) {
            return validate_project('project_id', $projects_id);
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
    }

    $errors = array_filter($errors);

    if (isset($_FILES['file']['name'])) {
        $tmp_name = $_FILES['file']['tmp_name'];
        $filename = uniqid() . basename($_FILES['file']['name']);
        move_uploaded_file($tmp_name, 'uploads/' . $filename);
        $form_data['file'] = $filename;
    }

    // // Если массив ошибок пустой -
    if (!count($errors)) {
    // сохраняю данные формы в БД
        $sql = 'INSERT INTO tasks (name, project_id, date_completed, file) VALUES (?, ?, ?, ?)';
        $stmt = db_get_prepare_stmt($link, $sql, $form_data);
        $result = mysqli_stmt_execute($stmt);

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

$page_content = include_template('add.php', [
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
