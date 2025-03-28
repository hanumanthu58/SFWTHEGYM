<?php
require __DIR__ . '/vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Database connection using .env variables
$serverName = $_ENV['DB_SERVER'];
$connectionOptions = [
    "Database" => $_ENV['DB_NAME'],
    "Uid" => $_ENV['DB_USER'],
    "PWD" => $_ENV['DB_PASS'],
];

$conn = sqlsrv_connect($serverName, $connectionOptions);
if ($conn === false) {
    die("Database connection failed: " . print_r(sqlsrv_errors(), true));
}
?>
