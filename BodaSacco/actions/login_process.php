<?php
session_start();
require "../includes/config.php";

$email = trim($_POST['email']);
$password = $_POST['password'];

// Fetch user data including status
$stmt = $pdo->prepare("SELECT id, full_name, password, status FROM users WHERE email = ?");
$stmt->execute([$email]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {

    // Check password
    if (password_verify($password, $user['password'])) {

        // Check if user is inactive
        if ($user['status'] === 'inactive') {
            $_SESSION['error'] = "Your account has been deactivated. Contact admin.";
            header("Location: ../login.php");
            exit();
        }

        // Login successful
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['full_name'];

        header("Location: ../dashboard.php");
        exit();

    } else {
        $_SESSION['error'] = "Invalid password";
    }

} else {
    $_SESSION['error'] = "User not found";
}

// Redirect back to login if any error
header("Location: ../login.php");
exit();