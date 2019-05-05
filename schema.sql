create database doingsdone
  default character set utf8
  default collate utf8_general_ci;

use doingsdone;

create table projects (
  id int auto_increment primary key,
  name char(128) not null,
  user_id int not null
);

create table tasks (
  id int auto_increment primary key,
  dt_create datetime not null default now(),
  status int default 0,
  name char(128) not null,
  file char(128),
  deadline datetime,
  project_id int not null,
  user_id int not null
);

create table users (
  id int auto_increment primary key,
  dt_reg datetime not null default now(),
  email char(128) not null unique,
  name char(128) not null,
  password char(64) not null
);