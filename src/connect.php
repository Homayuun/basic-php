<?php
$host = "php81-dev-environment-database";
$user = "root";
$password = "1234";
$database = "php81_dev_environment";

$connection = new mysqli($host, $user, $password, $database);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

?>