<?php
define('SECS_IN_HOUR', 3600);

class DbConnectionProvider {
    protected static $connection;

    public static function getConnection() {
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

function checkExpiration($date) {
    $tsDate = strtotime($date);
    $tsNow = time();
    $tsDiff = $tsDate - $tsNow;
    $hoursDiff = floor($tsDiff / SECS_IN_HOUR);

    return $hoursDiff;
}

function dbFetchData($link, $sql, $data = []) {
    $result = [];
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if ($res) {
        $result = mysqli_fetch_all($res, MYSQLI_ASSOC);
    }

    return $result;
}

function dbInsertData($link, $sql, $data = []) {
    $stmt = db_get_prepare_stmt($link, $sql, $data);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        $result = mysqli_insert_id($link);
    }

    return $result;
}

function getUser($email): ?array {
    $con = DbConnectionProvider::getConnection();

    $sql = 'select id, dt_reg, email, name, password ';
    $sql .= 'from users ';
    $sql .= 'where email = ?';

    $result = dbFetchData($con, $sql, [$email]);

    return $result[0] ?? null;
}

function getAllUsers() {
    $con = DbConnectionProvider::getConnection();

    $sql = 'select id, email, name ';
    $sql .= 'from users';

    $result = dbFetchData($con, $sql, []);

    return $result;
}

function getProjects($userId): ?array {
    $con = DbConnectionProvider::getConnection();

    $sql = 'select p.id, p.name, count(t.id) as tasks_count ';
    $sql .= 'from projects p ';
    $sql .= 'left join tasks t on p.id = t.project_id ';
    $sql .= 'where p.user_id = ? ';
    $sql .= 'group by p.id';

    $result = dbFetchData($con, $sql, [$userId]);

    return $result;
}

function getTasks(int $userId = null, ?int $projectId = null, string $taskDate = null, $search = null): array {
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

function getTodayTasks(int $userId = null) {
    $con = DbConnectionProvider::getConnection();

    $sql = 'select id, dt_create, status, name, file, deadline, project_id, user_id ';
    $sql .= 'from tasks ';
    $sql .= 'where user_id = ? and status = 0 and date(deadline) = date(now())';

    $result = dbFetchData($con, $sql, [$userId]);

    return $result;
}

function addTask($taskName, $project, $userId, $file, $deadline) {
    $con = DbConnectionProvider::getConnection();
    $sql = 'insert into tasks set name = ?, project_id = ?, user_id = ?, file = ?, deadline = ?';

    dbInsertData($con, $sql, [$taskName, $project, $userId, $file, $deadline]);
}

function addProject($projectName, $userId) {
    $con = DbConnectionProvider::getConnection();
    $sql = 'insert into projects set name = ?, user_id = ?';

    dbInsertData($con, $sql, [$projectName, $userId]);
}

function checkIfDateFuture(string $date) : bool {
    $today = strtotime('00:00:00');
    $date = strtotime($date);

    return ($date >= $today);
}

function addUser($email, $password, $name) {
    $con = DbConnectionProvider::getConnection();
    $sql = 'insert into users set email = ?, password = ?, name = ?';

    $email = strtolower($email);
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    dbInsertData($con, $sql, [$email, $passwordHash, $name]);
}

function checkIfUserExist($email) {
    $con = DbConnectionProvider::getConnection();
    $sql = 'select email from users where email = ?';

    $result = dbFetchData($con, $sql, [$email]);

    return !empty($result);
}

function checkIfProjectExist($userId, $projectName) {
    $con = DbConnectionProvider::getConnection();
    $sql = 'select id from projects where name = ? and user_id = ?';

    $result = dbFetchData($con, $sql, [$projectName, $userId]);

    return !empty($result);
}

function getTaskStatus($taskId) {
    $con = DbConnectionProvider::getConnection();
    $sql = 'select status from tasks where id = ?';

    $result = dbFetchData($con, $sql, [$taskId]);
    $status = null;

    if (count($result)) {
        $status = ($result[0]['status'] === 1) ? 0 : 1;
    }

    return $status;
}

function switchTaskStatus($taskId) {
    $con = DbConnectionProvider::getConnection();
    $sql = "update tasks set status = ? where id = ?";

    $status = getTaskStatus($taskId);

    dbInsertData($con, $sql, [$status, $taskId]);
}
