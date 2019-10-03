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
function get_data_from_db($link, $sql) {
    $result = mysqli_query($link, $sql);
    if (!$result) {
        return mysqli_error($link);
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = []) {
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
        die($errorMsg);
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 's';

            if (is_int($value)) {
                $type = 'i';
            }
            else if (is_string($value)) {
                $type = 's';
            }
            else if (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

        if (mysqli_errno($link) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
            die($errorMsg);
        }
    }

    return $stmt;
}

/**
 * Получает результат выполнения подготовленного выражения
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return array Массив с данными из БД
 */
function get_prepare_request($link, $sql, $data = []) {
    $stmt = db_get_prepare_stmt($link, $sql, $data = []);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * Получает значение поля
 *
 * @param $name Имя поля
 * @return Значение поля полученное из формы
 */
function get_post_val($name) {
    return $_POST[$name] ?? "";
}

/**
 * Функция проверки обязательного заполнения поля
 *
 * @param $name Имя поля
 * @return Текст ошибки или ничего, если ошибки нет
 */
function validate_filled($name) {
    if (empty(trim($_POST[$name]))) {
        return "Это поле должно быть заполнено";
    }

    return null;
}

/**
 * Функция проверки категории проекта
 *
 * @param $name Имя поля
 * @return Текст ошибки или ничего, если ошибки нет
 */
function validate_project($name, $allowed_list) {
    $id = $_POST[$name];

    if (!in_array($name, $allowed_list)) {
        return "Указана несуществующая категория";
    }

    return null;
}

/**
 * Проверяет переданную дату на соответствие формату 'ГГГГ-ММ-ДД'
 *
 * Примеры использования:
 * is_date_valid('2019-01-01'); // true
 * is_date_valid('2016-02-29'); // true
 * is_date_valid('2019-04-31'); // false
 * is_date_valid('10.10.2010'); // false
 * is_date_valid('10/10/2010'); // false
 *
 * @param string $date Дата в виде строки
 *
 * @return bool true при совпадении с форматом 'ГГГГ-ММ-ДД', иначе false
 */
function is_date_valid(string $date) : bool {
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}

/**
 * Проверяет переданную дату на соответствие формату 'ГГГГ-ММ-ДД' и чтобы не была меньше текущей даты
 *
 * Внутри использует другую функцию "is_date_valid"
 * @param $name Имя поля
 *
 * @return Возвращает текст ошибки или null, если ошибки нет
 */
function validate_date($name) {
    $date = $_POST[$name];

    if(empty($date)) {
        return null;
    } elseif (is_date_valid($date)) {
        $dt_now = date_create('now');
        $dt_end = date_create($date);
        $dt_diff = date_diff($dt_now, $dt_end);
        $days_count = date_interval_format($dt_diff, '%r%a');
        return ($days_count < 0) ? 'Дата не может быть меньше текущей' : null;
    } else {
        return 'Не верный формат даты';
    }
}
