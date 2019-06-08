<?php
define('SECS_IN_HOUR', 3600);

/**
 * Класс для создания соедениния
 * с базой данных
 */
class DbConnectionProvider
{
    protected static $connection;

    /**
     * Соединяет с базой данных
     *
     * @return object Ресурс соединения
     */
    public static function getConnection()
    {
        if (self::$connection === null) {
            self::$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
            if (!self::$connection) {
                exit('Ошибка MySQL: connection failed');
            }

            mysqli_set_charset(self::$connection, 'utf8');
        }

        return self::$connection;
    }
}

/**
 * Считает количество часов
 * с текущего момента
 * до полуночи заданной даты
 * @param string $date Дата
 *
 * @return int Количество часов
 */
function checkExpiration($date)
{
    $tsDate = strtotime($date);
    $tsNow = time();
    $tsDiff = $tsDate - $tsNow;
    $hoursDiff = floor($tsDiff / SECS_IN_HOUR);

    return $hoursDiff;
}

/**
 * Получает записи из базы данных
 * @param object $link Ресурс соединения
 * @param string $sql SQL-запрос
 * @param array $data Параметры запроса
 *
 * @return array Записи из базы данных
 */
function dbFetchData($link, $sql, $data = [])
{
    $result = [];
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if ($res) {
        $result = mysqli_fetch_all($res, MYSQLI_ASSOC);
    }

    return $result;
}

/**
 * Добавляет новую запись в базу данных
 * @param object $link Ресурс соединения
 * @param string $sql SQL-запрос
 * @param array $data Параметры запроса
 *
 * @return int|string Автоматически генерируемый ID, используя последний запрос
 */
function dbInsertData($link, $sql, $data = [])
{
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        $result = mysqli_insert_id($link);
    }

    return $result;
}

/**
 * Получает из базы данных
 * данные пользователя
 * @param string $email Емейл пользователя
 *
 * @return array|null Массив данных пользователя
 * (id, дата регистрации, email, имя, пароль)
 */
function getUser($email): ?array
{
    $con = DbConnectionProvider::getConnection();

    $sql = 'select id, dt_reg, email, name, password ';
    $sql .= 'from users ';
    $sql .= 'where email = ?';

    $result = dbFetchData($con, $sql, [$email]);

    return $result[0] ?? null;
}

/**
 * Получает из базы данных
 * данные всех пользователей
 *
 * @return array Массив данных пользователей
 * (id, email, имя)
 */
function getAllUsers()
{
    $con = DbConnectionProvider::getConnection();

    $sql = 'select id, email, name ';
    $sql .= 'from users';

    $result = dbFetchData($con, $sql, []);

    return $result;
}

/**
 * Получает из базы данных
 * данные проекта
 * @param int $userId Id пользователя
 *
 * @return array Массив с данными проекта
 * (id, название, число задач)
 */
function getProjects($userId): ?array
{
    $con = DbConnectionProvider::getConnection();

    $sql = 'select p.id, p.name, count(t.id) as tasks_count ';
    $sql .= 'from projects p ';
    $sql .= 'left join tasks t on p.id = t.project_id ';
    $sql .= 'where p.user_id = ? ';
    $sql .= 'group by p.id';

    $result = dbFetchData($con, $sql, [$userId]);

    return $result;
}

/**
 * Получает из базы данных
 * задачи пользователя
 * @param int $userId Id пользователя
 * @param int $projectId Id проекта
 * @param string $taskDate Дата завершения задачи
 * @param string $search Данные из строки поиска
 *
 * @return array Массив с данными задач
 * (id, дата создания, статус, название, прикрепленный файл, дата завершения, id проекта, id пользователя)
 */
function getTasks(int $userId = null, ?int $projectId = null, string $taskDate = null, $search = null): array
{
    $con = DbConnectionProvider::getConnection();

    $sql = 'select id, dt_create, status, name, file, deadline, project_id, user_id ';
    $sql .= 'from tasks ';
    $sql .= 'where user_id = ?';

    $parameters = [$userId];

    if ($projectId !== null) {
        $sql .= ' and project_id = ?';
        $parameters[] = $projectId;
    }

    if ($taskDate !== null) {
        if ($taskDate === 'today') {
            $sql .= ' and date(deadline) = date(now())';
        }

        if ($taskDate === 'tomorrow') {
            $sql .= ' and date(deadline) = date(now() + interval 1 day)';
        }

        if ($taskDate === 'last') {
            $sql .= ' and date(deadline) < date(now())';
        }
    }

    if (($search !== null) && (!empty($search))) {
        $sql .= ' and match(name) against(? in boolean mode)';
        $parameters[] = $search;
    }

    $result = dbFetchData($con, $sql, $parameters);

    return $result;
}

/**
 * Получает из базы данных
 * задачи пользователя
 * с датой завершения сегодня
 * @param int $userId Id пользователя
 *
 * @return array Массив с данными задач
 * (id, дата создания, статус, название, прикрепленный файл, дата завершения, id проекта, id пользователя)
 */
function getTodayTasks(int $userId = null)
{
    $con = DbConnectionProvider::getConnection();

    $sql = 'select id, dt_create, status, name, file, deadline, project_id, user_id ';
    $sql .= 'from tasks ';
    $sql .= 'where user_id = ? and status = 0 and date(deadline) = date(now())';

    $result = dbFetchData($con, $sql, [$userId]);

    return $result;
}

/**
 * Добавляет в базу данных
 * новую задачу
 * @param string $taskName Название задачи
 * @param int $projectId Id проекта
 * @param int $userId Id пользователя
 * @param string $file Название файла
 * @param string $deadline Дата завершения
 */
function addTask($taskName, $projectId, $userId, $file, $deadline)
{
    $con = DbConnectionProvider::getConnection();
    $sql = 'insert into tasks set name = ?, project_id = ?, user_id = ?, file = ?, deadline = ?';

    dbInsertData($con, $sql, [$taskName, $projectId, $userId, $file, $deadline]);
}

/**
 * Добавляет в базу данных
 * новые проект задачу
 * @param string $projectName Название проекта
 * @param int $userId Id пользователя
 */
function addProject($projectName, $userId)
{
    $con = DbConnectionProvider::getConnection();
    $sql = 'insert into projects set name = ?, user_id = ?';

    dbInsertData($con, $sql, [$projectName, $userId]);
}

/**
 * Добавляет в базу данных
 * нового пользователя
 * @param string $email Email
 * @param string $password Пароль
 * @param string $name Имя
 */
function addUser($email, $password, $name)
{
    $con = DbConnectionProvider::getConnection();
    $sql = 'insert into users set email = ?, password = ?, name = ?';

    $email = strtolower($email);
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    dbInsertData($con, $sql, [$email, $passwordHash, $name]);
}

/**
 * Проверяет является ли
 * дата будущей
 * @param string $date Дата
 *
 * @return bool
 */
function checkIfDateFuture(string $date) : bool
{
    $today = strtotime('00:00:00');
    $date = strtotime($date);

    return ($date >= $today);
}

/**
 * Проверяет существует ли
 * в базе данных
 * пользователь с данным email
 * @param string $email Email
 *
 * @return bool
 */
function checkIfUserExist($email)
{
    $con = DbConnectionProvider::getConnection();
    $sql = 'select email from users where email = ?';

    $result = dbFetchData($con, $sql, [$email]);

    return !empty($result);
}

/**
 * Проверяет существует ли
 * в базе данных
 * проект с данным названием
 * для данного пользователя
 * @param int $userId Id пользователя
 * @param string $projectName Название проекта
 *
 * @return bool
 */
function checkIfProjectExist($userId, $projectName)
{
    $con = DbConnectionProvider::getConnection();
    $sql = 'select id from projects where name = ? and user_id = ?';

    $result = dbFetchData($con, $sql, [$projectName, $userId]);

    return !empty($result);
}

/**
 * Проверяет выполнена ли задача
 * @param int $taskId Id задачи
 *
 * @return int 1 если задача выполнена
 * 0 если задача не выполнена
 */
function getTaskStatus($taskId)
{
    $con = DbConnectionProvider::getConnection();
    $sql = 'select status from tasks where id = ?';

    $result = dbFetchData($con, $sql, [$taskId]);
    $status = null;

    if (count($result)) {
        $status = ($result[0]['status'] === 1) ? 0 : 1;
    }

    return $status;
}

/**
 * Меняет стутус задачи в базе данных
 * @param int $taskId Id задачи
 */
function switchTaskStatus($taskId)
{
    $con = DbConnectionProvider::getConnection();
    $sql = "update tasks set status = ? where id = ?";

    $status = getTaskStatus($taskId);

    dbInsertData($con, $sql, [$status, $taskId]);
}

/**
 * Отправляет email оповещения
 * о задачах, запланированных на сегодня
 * всем пользователям,
 * запланировавшим что-то на сегодня
 * Для отправки используется
 * библиотека SwiftMailer
 * @param int $userId Id пользователя
 * @param string $userName Имя пользователя
 * @param string $userEmail Email пользователя
 * @param object $transport Instance класса Swift_SmtpTransport
 */
function sendNotification($userId, $userName, $userEmail, $transport)
{
    $tasks = getTodayTasks($userId) ?? null;
    $time = date('Y-m-d');

    if (!count($tasks)) {
        return;
    }

    $tasksNames = array_map(function ($task) {
        if (isset($task['name'])) {
            return $task['name'];
        }

        return null;
    }, $tasks);

    $tasksNames = implode(', ', $tasksNames);

    $messageBody = "Уважаемый, $userName. У вас запланирована ";
    $messageBody .= "задача $tasksNames на $time";

    $message = new Swift_Message("Уведомление от сервиса «Дела в порядке»");
    $message->setTo([$userEmail => $userName]);
    $message->setBody($messageBody);
    $message->setFrom("keks@phpdemo.ru", "DoingsDone");
    $message->setContentType('text/plain');

    $mailer = new Swift_Mailer($transport);
    $mailer->send($message);
}
