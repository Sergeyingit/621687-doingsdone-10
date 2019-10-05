<?php
require_once('functions.php');

require_once('db.php');
require_once('init.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $form = $_POST;

    $rules = [
        'email' => function() {
            return validate_filled('email');
        },
        'password' => function() {
            return validate_filled('password');
        }
    ];

    $errors = [];

    foreach ($_POST as $input_name => $input_value) {
        if (isset($rules[$input_name])) {
            $rule = $rules[$input_name];
            $errors[$input_name] = $rule();
        }
        if($input_name == 'email' AND empty($errors['email'])) {
            $errors['email'] = !filter_var($input_value, FILTER_VALIDATE_EMAIL) ? 'Email должен быть корректным' : null;
        }
    }

    $errors = array_filter($errors);

    $sql = 'SELECT * FROM users WHERE email = ?';
    $stmt = db_get_prepare_stmt($link, $sql, [$_POST['email']]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_all($result, MYSQLI_ASSOC);

    if (!count($errors) AND $user) {
        if (password_verify($_POST['password'], $user['password'])) {
            $_SESSION['user'] = $user;
        }
        else {
            $errors['password'] = 'Неверный пароль';
        }
    }
    else {
        $errors['email'] = 'Такой пользователь не найден';
    }

    if (!count($errors)) {
        header('Location: /index.php');
        exit();
    }
} else {
    if (isset($_SESSION['user'])) {
        header('Location: /index.php');
        exit();
    }
}


$page_content = include_template('auth.php', [
    'errors' => $errors
]);


$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'user' => $user,
    'title' => 'Дела в порядке'
]);

print($layout_content);
