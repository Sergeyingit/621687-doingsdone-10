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
    $urgency = false;
    if ($task_execution_time) {
        $ts = time();
        $end_ts = strtotime($task_execution_time);
        $hour = 24;
        $secs_in_hour = 3600;
        $ts_diff = $end_ts - $ts;
        $days_until_end = floor($ts_diff / $secs_in_hour);

        if ($days_until_end <= $hour) {
            $urgency = true;
        }
    }

    return $urgency;
}
