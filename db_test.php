<?php
$host = "localhost";
$port = "5432";
$dbname = "OULAD";
$user = "postgres";
$password = "postgres";

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Database connection successful";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>