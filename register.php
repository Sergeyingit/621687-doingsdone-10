<?php

require_once('init.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $errors = [];

    $required = ['email', 'password', 'name'];



    $rules = [
        'email' => function () {
            return validate_length('email', 128);
        },
        'name' => function () {
            return validate_length('name', 128);
        },
        'password' => function () {
            return validate_length('password', 128);
        }
    ];

    foreach ($_POST as $input_name => $input_value) {
        if (isset($rules[$input_name])) {
            $rule = $rules[$input_name];
            $errors[$input_name] = $rule();
        }
        if ($input_name === 'email' AND empty($errors['email'])) {
            $errors['email'] = !filter_var($input_value, FILTER_VALIDATE_EMAIL) ? 'Email должен быть корректным' : null;
        }
    }

    $errors = array_filter($errors);

    foreach ($required as $key) {
        if (empty(trim($_POST[$key]))) {
            $errors[$key] = 'Это поле должно быть заполнено';
        }
    }


    $errors = array_filter($errors);
    if (empty($errors)) {
        $sql = 'SELECT id FROM users WHERE email = ?';
        $user_id = get_result_prepare_request($link, $sql, [$_POST['email']]);

        if ($user_id) {
            $errors['email'] = 'Пользователь с этим email уже зарегистрирован';
        } else {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            $sql = 'INSERT INTO users (date_add, email, name, password) VALUES (NOW(), ?, ?, ?)';
            $result = set_result_prepare_request($link, $sql, [$_POST['email'], $_POST['name'], $password]);
        }

        if ($result AND !$errors) {
            header('Location: index.php');
            exit();
        }
    }

}


$page_content = include_template('register.php', [
    'errors' => $errors
]);


$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'user' => $user,
    'title' => 'Дела в порядке'
]);

print($layout_content);
