CREATE DATABASE doingsdone;

USE doingsdone;

CREATE TABLE projects (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name CHAR(128) NOT NULL UNIQUE,
  user_id INT
);

CREATE TABLE tasks (
  id INT AUTO_INCREMENT PRIMARY KEY,
  date_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  complete TINYINT DEFAULT 0,
  name CHAR(128) NOT NULL,
  file TEXT,
  date_completed date,
  user_id INT,
  project_id INT
);

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  date_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  email CHAR(128) NOT NULL UNIQUE,
  name CHAR(128) NOT NULL,
  password  CHAR(64) NOT NULL
);

CREATE INDEX project_name ON projects(name);
CREATE INDEX task_name ON tasks(name);
CREATE UNIQUE INDEX email ON users(email);

