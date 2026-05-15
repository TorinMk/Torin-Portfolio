<?php

$env = parse_ini_file(__DIR__ . '/.env');

function openConnection() {
    global $env;

    $connection = new mysqli(
        $env['db_host'],
        $env['db_user'],
        $env['db_pass'],
        $env['db_name']
    );

    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }

    return $connection;
}

function closeConnection($connection) {
    $connection->close();
}
?>