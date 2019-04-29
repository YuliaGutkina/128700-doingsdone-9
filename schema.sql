create database doingsdone
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

use doingsdone;

create table projects (
  id int auto_increment primary key,
  name CHAR(128) NOT NULL,
  user_id int NOT NULL
);

create table tasks (
  id int auto_increment primary key,
  dt_create datetime NOT NULL DEFAULT NOW(),
  status int DEFAULT 0,
  name CHAR(128) NOT NULL,
  file CHAR(128),
  deadline datetime,
  project_id int NOT NULL,
  user_id int NOT NULL
);

create table users (
  id int auto_increment primary key,
  dt_reg datetime NOT NULL DEFAULT NOW(),
  email CHAR(128) NOT NULL UNIQUE,
  name CHAR(128) NOT NULL,
  password CHAR(64) NOT NULL
);
