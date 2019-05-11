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

function checkExpiration($date, $expirationHours = 24) {
    $tsDate = strtotime($date);
    $tsNow = time();
    $tsDiff = $tsDate - $tsNow;
    $hoursDiff = floor($tsDiff / SECS_IN_HOUR);

    return ($hoursDiff <= $expirationHours);
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

function getUser(int $id): ?array
{
    $con = DbConnectionProvider::getConnection();

    $sql = 'select id, dt_reg, email, name ';
    $sql .= 'from users ';
    $sql .= 'where id = ?';

    $result = dbFetchData($con, $sql, [$id]);

    return $result[0] ?? null;
}

function getProjects(int $userId): ?array
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

function getTasks(int $userId = null, ?int $projectId = null): array
{
    $con = DbConnectionProvider::getConnection();

    $sql = 'select id, dt_create, status, name, file, deadline, project_id, user_id ';
    $sql .= 'from tasks ';
    $sql .= ' where user_id = ?';

    $parameters = [$userId];

    if ($projectId !== null) {
        $sql .= ' and project_id = ?';
        $parameters[] = $projectId;
    }

    $result = dbFetchData($con, $sql, $parameters);

    return $result;
}
