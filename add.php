<?php
require_once('functions.php');
require_once('db.php');

// получаю список проектов для валидации
$sql_projects = 'SELECT p.name, p.id FROM projects p';
$projects = get_data_from_db ($link, $sql_projects);
$projects_name = array_column($projects, 'name');
print_r($projects);
echo '<br><br><br>';
print_r($projects_name);

// проверка была ли отправлена форма
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = $_POST;
    $required = [
        'name',
        'project'
    ];

    $errors = [];

    $rules = [
        'name' => function() {
            return validateFilled('name');
        },
        'project' => function() {
            return validateFilled('project');
        },
        'project' => function() use ($projects_name) {
            return validateProject('project');
        }
    ];

    foreach ($_POST as $key => $value) {
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule();
        }
    }

    $errors = array_filter($errors);



// сохраняю данные формы в БД
    $sql = 'INSERT INTO tasks (name, file, date_completed, project_id) VALUES (?, ?, ?, ?)';
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    $result = mysqli_stmt_execute($stmt);

    // при успешном сохранении отправляю на главную
    if ($result) {
        header('Location: index.php');
    }
}



$content = include_template('add.php', [
    'projects' => $projects
]);



$layout_content = include_template('layout.php', [
    'content' => $content,
    'user' => $user,
    'title' => 'Дела в порядке'
]);

print($content);
