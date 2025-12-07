<?php

$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'devclub';
$dbport = '3306';

$dsn = "mysql:host=$host;port=$dbport;dbname=$dbname;charset=utf8mb4";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
];

try {
    $conn = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
