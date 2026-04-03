<?php
session_start();
require "../includes/config.php";
require "../helpers/functions.php";

$full_name = trim($_POST['full_name']);
$email = trim($_POST['email']);
$phone = trim($_POST['phone']);
$password = $_POST['password'];

$errors = [];

// VALIDATION
if (strlen($full_name) < 3) $errors[] = "Full name must be at least 3 characters.";
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email.";
if (strlen($password) < 6) $errors[] = "Password must be at least 6 characters.";

// CHECK DUPLICATES
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? OR phone = ?");
$stmt->execute([$email, $phone]);

if ($stmt->rowCount() > 0) {
    $errors[] = "Email or phone already exists.";
}

// HANDLE ERRORS
if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header("Location: ../register.php");
    exit();
}

// GENERATE MEMBERSHIP ID
$membership_id = generateMembershipID($pdo);

// HASH PASSWORD
$hashed = password_hash($password, PASSWORD_DEFAULT);

// INSERT
$stmt = $pdo->prepare("INSERT INTO users (full_name, email, phone, membership_id, password) VALUES (?, ?, ?, ?, ?)");
$stmt->execute([$full_name, $email, $phone, $membership_id, $hashed]);

// Get last inserted user ID
$user_id = $pdo->lastInsertId();

// Create savings account
$stmt = $pdo->prepare("INSERT INTO savings (user_id, balance) VALUES (?, 0)");
$stmt->execute([$user_id]);

$_SESSION['success'] = "Account created 🎉 Membership ID: $membership_id";
header("Location: ../login.php");
exit();