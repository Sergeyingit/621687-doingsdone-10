<?php
require_once ('init.php');
require_once ('vendor/autoload.php');

$transport = new Swift_SmtpTransport("phpdemo.ru", 25);
$transport->setUsername("keks@phpdemo.ru");
$transport->setPassword("htmlacademy");

$mailer = new Swift_Mailer($transport);

$sql = 'SELECT t.name AS task, t.date_completed AS date, p.name AS category, t.complete AS is_complete, u.name, u.email, u.id FROM tasks t JOIN projects p ON t.project_id = p.id JOIN users u ON p.user_id = u.id WHERE t.date_completed = CURDATE() AND t.complete = 0';

$result = get_data_from_db($link, $sql);

if($result) {
    foreach ($result as $index => $field) {
        $users[] = $field['id'];
    }

    $user_unique = array_count_values($users);

    foreach ($user_unique as $users_id => $count_tasks) {
        $sql_user_tasks = 'SELECT t.name AS task, t.date_completed AS date, p.name AS category, t.complete AS is_complete, u.name, u.email, u.id FROM tasks t JOIN projects p ON t.project_id = p.id JOIN users u ON p.user_id = u.id WHERE t.date_completed = CURDATE() AND t.complete = 0 AND u.id = ' . $users_id;
        $user_tasks = get_data_from_db($link, $sql_user_tasks);

        $tasks_list = '';
        foreach ($user_tasks as $task => $field) {
            $recipients[$field['email']] = $field['name'];
            if($task == 0) {
                $tasks_list .= $field['task'];
            } elseif ($task > 0) {
                $tasks_list .= ', ' . $field['task'];
            }
            $tasks_date = $field['date'];
            $user_name = $field['name'];
        }

        $msg_content = 'Уважаемый, ' . $user_name . '. У вас запланированы задачи: ' . $tasks_list .' на ' . $tasks_date;

        $message = new Swift_Message();
        $message->setSubject('Уведомление от сервиса «Дела в порядке»');
        $message->setFrom(['keks@phpdemo.ru' => 'Дела в порядке']);
        $message->setBcc($recipients);
        $message->setBody($msg_content, 'text/plain');
        $result = $mailer->send($message);


    }
    if ($result) {
        print('Письмо успешно отправлено');
    }
    else {
        print('Не удалось отправить письмо');
    }

}
