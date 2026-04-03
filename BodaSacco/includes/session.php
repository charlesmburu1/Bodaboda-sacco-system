<?php
// includes/session.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require "config.php";

if (!isset($_SESSION['user_id'])) {
    // Not logged in
    header("Location: ../includes/login.php");
    exit();
}

// Fetch user status from database
$stmt = $pdo->prepare("SELECT status FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    // User not found (deleted)
    session_destroy();
    header("Location: ../includes/login.php");
    exit();
}

// If user is deactivated, log out
if ($user['status'] === 'inactive') {
    session_destroy();
    $_SESSION['error'] = "Your account has been deactivated. Contact admin.";
    header("Location: ../includes/login.php");
    exit();
}