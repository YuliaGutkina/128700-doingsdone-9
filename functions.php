<?php
define('SECS_IN_HOUR', 3600);

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
