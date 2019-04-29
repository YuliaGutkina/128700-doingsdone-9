CREATE DATABASE doingsdone
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;
USE doingsdone;
CREATE TABLE projects (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name CHAR(128) NOT NULL,
  user_id INT NOT NULL
);
CREATE TABLE tasks (
  id INT AUTO_INCREMENT PRIMARY KEY,
  dt_create DATETIME NOT NULL DEFAULT NOW(),
  status INT DEFAULT 0,
  name CHAR(128) NOT NULL,
  file CHAR(128),
  deadline DATETIME,
  project_id INT NOT NULL,
  user_id INT NOT NULL
);
CREATE TABLE users (
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  dt_reg DATETIME NOT NULL DEFAULT NOW(),
  email CHAR(128) NOT NULL,
  name CHAR(128) NOT NULL,
  password CHAR(64) NOT NULL
);
CREATE UNIQUE INDEX p_name ON projects(name);
CREATE UNIQUE INDEX email ON users(email);
CREATE INDEX t_status ON tasks(status);
CREATE INDEX t_name ON tasks(name);
CREATE INDEX deadline ON tasks(deadline);
