<?php

function getDistance($newId, $oldId) {
    global $connection;
    $earthRadius = 6378;
    $sql = "SELECT addresses_cord_x, addresses_cord_y FROM addresses1 WHERE id=$newId OR id=$oldId";
    $query = mysqli_fetch_all(mysqli_query($connection, $sql));
    $fromX = deg2rad($query[0][0]);
    $fromY = deg2rad($query[0][1]);
    $toX = deg2rad($query[1][0]);
    $toY = deg2rad($query[1][1]);
    $xDelta = abs($toX - $fromX);
    $yDelta = abs($toY - $fromY);
    $angle = 2 * asin(sqrt(pow(sin($xDelta / 2), 2) + cos($fromX) * cos($toX) * pow(sin($yDelta / 2), 2)));
    $distance = $angle * $earthRadius;
    if ($distance > 30) {
        $distance = round($distance, 0);
    } else {
        $distance = round($distance, 1);
    }
    return $distance;
}