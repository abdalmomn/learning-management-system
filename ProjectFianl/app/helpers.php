<?php

function hmsToSeconds($hms) {
    list($hours, $minutes, $seconds) = sscanf($hms, '%d:%d:%d');
    return $hours * 3600 + $minutes * 60 + $seconds;
}

function secondsToHms($seconds) {
    $hours = floor($seconds / 3600);
    $minutes = floor(($seconds % 3600) / 60);
    $seconds = $seconds % 60;
    return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
}
