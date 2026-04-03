<?php
require_once "../includes/admin_session.php";
require_once "../includes/config.php";

if (!isset($_GET['id'], $_GET['action'])) {
    header("Location: dashboard.php");
    exit();
}

$loanId = intval($_GET['id']);
$action = $_GET['action'];

// Determine new status
$validActions = ['approve' => 'approved', 'reject' => 'paid', 'paid' => 'paid'];
if (!array_key_exists($action, $validActions)) {
    header("Location: admin_loans.php");
    exit();
}

$newStatus = $validActions[$action];

// Update loan status
$stmt = $pdo->prepare("UPDATE loans SET status = ? WHERE id = ?");
$stmt->execute([$newStatus, $loanId]);

header("Location: admin_loans.php");
exit();