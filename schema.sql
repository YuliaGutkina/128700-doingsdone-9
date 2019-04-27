CREATE DATABASE doingsdone
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;
USE doingsdone;
CREATE TABLE projects (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name CHAR(128)
);
CREATE TABLE tasks (
  id INT AUTO_INCREMENT PRIMARY KEY,
  dt_create DATETIME,
  status INT DEFAULT 0,
  name CHAR(128),
  file CHAR(128),
  deadline DATETIME
);
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  dt_reg DATETIME,
  email CHAR(128) NOT NULL,
  name CHAR(128),
  password CHAR(64)
);
CREATE UNIQUE INDEX p_name ON projects(name);
CREATE UNIQUE INDEX email ON users(email);
CREATE INDEX t_status ON tasks(status);
CREATE INDEX t_name ON tasks(name);
CREATE INDEX deadline ON tasks(deadline);
