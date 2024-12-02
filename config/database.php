<?php

try {
    $dsn = "mysql:host=" . getenv('DB_HOST') . ";dbname=" . getenv('DB_NAME');
    $pdo = new PDO($dsn, getenv('DB_USER'), getenv('DB_PASSWORD'));
    // Set error mode to exception to handle errors effectively
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Catch any errors and display an appropriate message
    die("Error: Could not connect. " . $e->getMessage());
}