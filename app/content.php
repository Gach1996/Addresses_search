<?php
require_once dirname(__DIR__) . '/app/db_connection.php';

$term = $_GET['name'] ?? '';

$connection = connectionDb();

$result = null;

if (mb_strlen($term) >= 3) {

    $term = trim($term);

    $term = str_replace(' ', '-', $term);
    $term = '+' . $term;

    $fullTextSearchSql = "SELECT * FROM addresses1
 WHERE MATCH(addresses_address,addresses_street) AGAINST('{$term}'IN BOOLEAN MODE)";
    $result = $connection->query($fullTextSearchSql);

    if ($result) {
        $k = $result->fetch_all(MYSQLI_ASSOC);

        echo json_encode($k);
    }
}

$connection->close();
