insert into projects (name, user_id) values ('Входящие', 2);
insert into projects (name, user_id) values ('Учеба', 1);
insert into projects (name, user_id) values ('Работа', 1);
insert into projects (name, user_id) values ('Домашние дела', 2);
insert into projects (name, user_id) values ('Авто', 1);

insert into users (email, name, password) values ('vasya@gmail.com', 'Вася', 'qwerty');
insert into users (email, name, password) values ('kolya@gmail.com', 'Коля', 'admin');
insert into users (email, name, password) values ('petya@gmail.com', 'Петя', '1111');

insert into tasks SET name = 'Собеседование в IT компании',
                      project_id = 3,
                      user_id = 1;
insert into tasks SET name = 'Выполнить тестовое задание',
                      project_id = 3,
                      user_id = 1;
insert into tasks SET name = 'Сделать задание первого раздела',
                      project_id = 2,
                      user_id = 1;
insert into tasks SET name = 'Встреча с другом',
                      project_id = 1,
                      user_id = 2;
insert into tasks SET name = 'Купить корм для кота',
                      project_id = 4,
                      user_id = 2;
insert into tasks SET name = 'Заказать пиццу',
                      project_id = 4,
                      user_id = 2;


