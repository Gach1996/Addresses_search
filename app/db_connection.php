<?php

error_reporting(E_ALL);
function connectionDb()
{
    $dbParamsPath = $_SERVER['DOCUMENT_ROOT'] . '/Task/config/db_parameters.php';

    $dbParameters = require $dbParamsPath;
    
    $connection = mysqli_connect($dbParameters['host'], $dbParameters['user'],$dbParameters['password']);

    if (!$connection) {
        echo "Error: Unable to connect to MySQL. Please check your DB connection parameters.\n";
        exit;
    }

    $selDb = mysqli_select_db($connection, "{$dbParameters['dbname']}");

    if (!$selDb) {
        if ($connection->query("CREATE DATABASE {$dbParameters['dbname']}") === TRUE) {
            echo 'Database created successfully';
        } else {
            exit("Error creating database: " . $connection->error);
        }
    }
    $connection->select_db($dbParameters['dbname']);

    return $connection;
}

?>