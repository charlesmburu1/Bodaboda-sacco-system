<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require "../includes/config.php"; // gives $pdo

$email = trim($_POST['email']);

// CHECK IF USER EXISTS
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$email]);

if ($stmt->rowCount() == 0) {
    // Always show same message (security best practice)
    $_SESSION['message'] = "If the email exists, a reset link has been sent.";
    header("Location: ../forgot_password.php");
    exit();
}

// GENERATE TOKEN
$token = bin2hex(random_bytes(50));
$expires = date("Y-m-d H:i:s", strtotime("+1 hour"));

// SAVE TOKEN
$stmt = $pdo->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
$stmt->execute([$email, $token, $expires]);

// TEMP: show link (Phase 2)
// $_SESSION['message'] = "Reset link: http://localhost/BodaSacco/reset_password.php?token=$token";
$_SESSION['message'] = "Click below to reset your password: <a href='" . BASE_URL . "reset_password.php?token=$token'>Reset Password</a>";

header("Location: ../forgot_password.php");
exit();