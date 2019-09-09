<?php
/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */
function include_template($name, array $data = []) {
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}

/**
 * Возвращает количество задач в проекте
 *
 * @param array $tasks_list Первый параметр функции. Массив задач
 * @param string $project Второй параметр функции. Имя проекта
 * @return integer
 */
function get_sum_tasks ($tasks_list, $project) {
    $count = 0;
    foreach ($tasks_list as $task) {
        if ($task['category'] === $project) {
            $count++;
        }
    }
    return $count;
}

/**
 * Проверяет сколько часов осталось до завершения задачи
 *
 * Принимает строковый аргумент - дату, если дата есть - проверяет
 * сколько часов осталось до её наступления, если меньше суток - возвращает true
 *
 * @param string $task_execution_time Параметр функции. Дата проекта
 * @return boolean
 */
function checks_urgency ($task_execution_time) {
    if ($task_execution_time) {
        $hour = 24;
        $dt_now = date_create('now');
        $dt_end = date_create($task_execution_time);
        $dt_diff = date_diff($dt_end, $dt_now);
        $days_count = date_interval_format($dt_diff, "%a");
        $hours_count = $days_count * $hour;

        if ($hours_count <= $hour) {
            return true;
        }
    }

    return false;
}


/**
 * Возвращает массив засроса sql
 *
 * Принимает ресурс соединения и sql запрос
 * сохраняет результат запроса в переменную, если нет ошибки - возвращает массив
 * с данными из БД
 *
 * @param $link Принимает ресурс соединения
 * @param string $sql Принимает запрос sql
 * @return array
 */
function get_data_from_db ($link, $sql) {
    $result = mysqli_query($link, $sql);
    if (!$result) {
        $error = mysqli_error($link);
        print($error);
    } else {
        $array = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    return $array;
}
