<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}

require_once "admin_session.php"; // Check admin logged in
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<header class="admin-header">
    <div class="container nav">
        <h1 class="logo">BodaSacco Admin</h1>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="admin_loans.php">Loans</a>
            <a href="manage_members.php">Members</a>
            <a href="manage_savings.php">Savings</a>
            <a href="../logout.php" class="logout">Logout</a>
        </nav>
    </div>
</header>