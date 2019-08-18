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
