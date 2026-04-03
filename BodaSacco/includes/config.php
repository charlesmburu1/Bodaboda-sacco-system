<?php

    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    require_once "constants.php";

    try {
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
            DB_USER,
            DB_PASS
        );

        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
?>