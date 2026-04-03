<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
include "../includes/config.php";

$token = $_POST['token'];
$password = $_POST['password'];

// VALIDATE PASSWORD
if (strlen($password) < 6) {
    die("Password must be at least 6 characters.");
}

// CHECK TOKEN
$stmt = $pdo->prepare("SELECT email, expires_at FROM password_resets WHERE token = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$stmt->bind_result($email, $expires_at);

if ($stmt->fetch()) {

    if (strtotime($expires_at) < time()) {
        die("Token expired");
    }

    // HASH NEW PASSWORD
    $hashed = password_hash($password, PASSWORD_DEFAULT);

    // UPDATE USER PASSWORD
    $stmt2 = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
    $stmt2->bind_param("ss", $hashed, $email);
    $stmt2->execute();

    // DELETE TOKEN
    $pdo->query("DELETE FROM password_resets WHERE email = '$email'");

    $_SESSION['success'] = "Password updated successfully. Please login.";
    header("Location: ../login.php");
    exit();

} else {
    die("Invalid token");
}
?>