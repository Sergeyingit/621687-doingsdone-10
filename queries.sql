INSERT INTO projects (name, user_id)
VALUES ('Входящие', 1), ('Учеба', 1), ('Работа', 2), ('Домашние дела', 2), ('Авто', 2);

INSERT INTO users (email, name, password)
VALUES ('snow@gmail.com', 'snow', 'password1234'), ('stark@gmail.com', 'stark', 'password1234');

INSERT INTO tasks
SET name = 'Собеседование в IT компании', date_completed = '01.12.2019', project_id = 3;

INSERT INTO tasks
SET name = 'Выполнить тестовое задание', date_completed = '25.12.2019', project_id = 3;

INSERT INTO tasks
SET complete = 1, name = 'Сделать задание первого раздела', date_completed = '21.12.2019', project_id = 2;

INSERT INTO tasks
SET  name = 'Встреча с другом', date_completed = '22.12.2019', project_id = 1;

INSERT INTO tasks
SET  name = 'Купить корм для кота', project_id = 4;

INSERT INTO tasks
SET  name = 'Заказать пиццу', project_id = 4;

/*
получить список из всех проектов для одного пользователя
*/
SELECT u.name AS User, p.name AS Project FROM users u JOIN projects p ON u.id = p.user_id WHERE u.name = 'snow';

/*
получить список из всех задач для одного проекта
*/
SELECT t.name AS Task FROM tasks t JOIN projects p ON t.project_id = p.id WHERE p.name = 'Работа';

/*
пометить задачу как выполненную
*/
UPDATE tasks SET complete = 1 WHERE name = 'Заказать пиццу';

/*
обновить название задачи по её идентификатору
*/

UPDATE tasks SET name = 'Сделать новое задание' WHERE id = 3;


INSERT INTO users (email, name, password)
VALUES ('new@gmail.com', 'newuser', 'password1234');

INSERT INTO projects (name, user_id)
VALUES ('Развлечения', 3), ('Покупки', 3);

INSERT INTO tasks
SET name = 'Сходить в кино на новый фильм', date_completed = '01.12.2019', project_id = 6;

INSERT INTO tasks
SET name = 'Посетить выставку', date_completed = '01.11.2019', project_id = 6;

INSERT INTO tasks
SET name = 'Купить продукты', date_completed = '02.12.2019', project_id = 7;




