insert into projects (name, user_id) values ('Входящие', 2);
insert into projects (name, user_id) values ('Учеба', 1);
insert into projects (name, user_id) values ('Работа', 1);
insert into projects (name, user_id) values ('Домашние дела', 2);
insert into projects (name, user_id) values ('Авто', 1);

insert into users (email, name, password) values ('vasya@gmail.com', 'Вася', 'qwerty');
insert into users (email, name, password) values ('kolya@gmail.com', 'Коля', 'admin');
insert into users (email, name, password) values ('petya@gmail.com', 'Петя', '1111');

insert into tasks set name = 'Собеседование в IT компании',
                      project_id = 3,
                      user_id = 1,
                      status = 1;
insert into tasks set name = 'Выполнить тестовое задание',
                      project_id = 3,
                      user_id = 1,
                      status = 0;
insert into tasks set name = 'Сделать задание первого раздела',
                      project_id = 2,
                      user_id = 1,
                      status = 1;
insert into tasks set name = 'Встреча с другом',
                      project_id = 1,
                      user_id = 2,
                      status = 0;
insert into tasks set name = 'Купить корм для кота',
                      project_id = 4,
                      user_id = 2,
                      status = 0;
insert into tasks set name = 'Заказать пиццу',
                      project_id = 4,
                      user_id = 2,
                      status = 0;

# получить список из всех проектов для одного пользователя.
# Объедините проекты с задачами, чтобы посчитать количество задач в каждом проекте и в дальнейшем выводить эту цифру рядом с именем проекта;
select p.id, p.name, count(t.id) as tasks_count from projects p
left join tasks t on p.id = t.project_id
where p.user_id = 1
group by p.id;

# получить список из всех задач для одного проекта;
select id, dt_create, status, name, file, deadline, project_id, user_id from tasks
where project_id = 3;

# пометить задачу как выполненную;
update tasks
set status = 1
where id = 6;

# обновить название задачи по её идентификатору.
update tasks
set name = 'Купить корм для кошки'
where id = 5;

# создание полнотекстового индекса для поля «название» в таблице задач
create fulltext index tasks_ft_search
on tasks(name);
