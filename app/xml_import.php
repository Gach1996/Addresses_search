<?php

/**
 * @var  $dbParameters
 */
require_once dirname(__DIR__) . '/app/db_connection.php';

$connection = connectionDb();

mysqli_query($connection, "DROP TABLE IF EXISTS addresses1");
$sql = "CREATE TABLE IF NOT EXISTS `addresses1`(
`id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
`addresses_id` INT NOT NULL,
`addresses_address` varchar(255) NOT NULL,
`addresses_street` varchar(255) NOT NULL,
`addresses_street_name` varchar(255) NOT NULL,
`addresses_street_type` varchar(255) NOT NULL,
`addresses_adm` varchar(255) NOT NULL,
`addresses_adm1` varchar(255) NOT NULL,
`addresses_adm2` varchar(255) NOT NULL,
`addresses_cord_y` varchar(255) NOT NULL,
`addresses_cord_x` varchar(255) NOT NULL,
FULLTEXT(addresses_address,addresses_street)
)";

mysqli_query($connection, $sql);

$data = simplexml_load_file('../addresses.xml');

$data = json_decode(json_encode($data), true)['addresses'];

foreach ($data as $row) {
    $address_id = $row['addresses_id'];
    $address = $row['addresses_address'];
    $street = $row['addresses_street'];
    $street_name = $row['addresses_street_name'];
    $street_type = $row['addresses_street_type'];
    $adm = $row['addresses_adm'];
    $adm1 = $row['addresses_adm1'];
    $adm2 = $row['addresses_adm2'];
    $cord_y = $row['addresses_cord_y'];
    $cord_x = $row['addresses_cord_x'];

    $sql = "INSERT INTO addresses1(addresses_id, addresses_address, addresses_street, addresses_street_name,
addresses_street_type, addresses_adm, addresses_adm1, addresses_adm2, addresses_cord_y, addresses_cord_x) VALUES
('" . $address_id . "','" . $address . "','" . $street . "','" . $street_name . "','" . $street_type . "',
'" . $adm . "','" . $adm1 . "','" . $adm2 . "','" . $cord_y . "','" . $cord_x . "')";

    mysqli_query($connection, $sql);

}

$connection->close();

?>