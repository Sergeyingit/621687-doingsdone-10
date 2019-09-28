<?php
require_once('functions.php');
require_once('db.php');

// получаю список проектов для валидации
$sql_projects = 'SELECT p.name, p.id FROM projects p';
$projects = get_data_from_db ($link, $sql_projects);
$projects_id = array_column($projects, 'id');
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
        'project_id' => function() use ($projects_id) {
            return validateProject('id');
        }
    ];

    foreach ($_POST as $key => $value) {
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule();
        }
    }

    $date = $_POST['date'];


    if(is_date_valid($date)) {
        $dt_now = date_create('now');
        $dt_diff = date_diff($date, $dt_now);
        $days_count = date_interval_format($dt_diff, "%a");
        $errors['date'] = ($days_count < 0) ? 'Дата не может быть меньше текущей' : '';
    } else {
        $errors['date'] = 'Не верный формат даты';
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
