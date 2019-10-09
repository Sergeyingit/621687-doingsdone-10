<?php

require_once('init.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form = $_POST;

    $errors = [];
    $required = [
        'email',
        'password'
    ];

    if (isset($_POST['email']) AND empty($errors['email'])) {
        $errors['email'] = !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ? 'Email должен быть корректным' : null;
    }

    foreach ($required as $key) {
        if (empty(trim($_POST[$key]))) {
            $errors[$key] = 'Это поле должно быть заполнено';
        }
    }

    $errors = array_filter($errors);

    $sql = 'SELECT * FROM users WHERE email = ?';
    $stmt = db_get_prepare_stmt($link, $sql, [$_POST['email']]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_array($result, MYSQLI_ASSOC);

    if (empty($errors) AND $user) {
        if (password_verify($_POST['password'], $user['password'])) {
            $_SESSION['user'] = $user;
        } else {
            $errors['password'] = 'Вы ввели неверный пароль';
        }
    } else {
        if(!isset($errors['email']) AND $user['email'] === $_POST['email']) {
            $errors['email'] = null;
        } else {
            $errors['email'] = (isset($errors['email'])) ? $errors['email'] :  'Вы ввели неверный email';
        }
    }

    $errors = array_filter($errors);
    if (empty($errors)) {
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
