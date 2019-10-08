<?php

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
    $user = get_result_prepare_request($link, $sql, [$_POST['email']]);

    if (!count($errors) AND $user) {
        if (password_verify($_POST['password'], $user['password'])) {
            $_SESSION['user'] = $user;
        } else {
            $errors['password'] = 'Вы ввели неверный пароль';
        }
    } else {
        $errors['email'] = $errors['email'] ?? 'Вы ввели неверный email';
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
