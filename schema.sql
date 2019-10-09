CREATE DATABASE doingsdone
DEFAULT CHARACTER SET utf8
DEFAULT COLLATE utf8_general_ci;

USE doingsdone;

CREATE TABLE projects (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name CHAR(128) NOT NULL,
  user_id INT
);

CREATE INDEX project_name ON projects(name);

CREATE TABLE tasks (
  id INT AUTO_INCREMENT PRIMARY KEY,
  date_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  complete TINYINT DEFAULT 0,
  name CHAR(128) NOT NULL,
  file VARCHAR(255),
  date_completed date DEFAULT NULL,
  project_id INT
);

CREATE INDEX task_name ON tasks(name);
CREATE FULLTEXT INDEX task_search ON tasks(name);

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  date_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  email CHAR(128) NOT NULL UNIQUE,
  name CHAR(128) NOT NULL,
  password CHAR(128) NOT NULL
);





