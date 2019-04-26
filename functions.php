<?php
define('SECS_IN_HOUR', 3600);

function countTasks(array $tasks, $projectName) {
    $count = 0;

    foreach($tasks as $task) {
        if (isset($task['category']) && ($task['category'] === $projectName)) {
            $count++;
        }
    }

    return $count;
}

function checkExpiration($date, $expirationHours = 24) {
    $tsDate = strtotime($date);
    $tsNow = time();
    $tsDiff = $tsDate - $tsNow;
    $hoursDiff = floor($tsDiff / SECS_IN_HOUR);

    return ($hoursDiff <= $expirationHours);
}
