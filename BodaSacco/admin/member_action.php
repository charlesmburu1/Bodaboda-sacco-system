<?php
require_once "../includes/admin_session.php";
require_once "../includes/config.php";

if (!isset($_GET['id'], $_GET['action'])) {
    header("Location: manage_members.php");
    exit();
}

$memberId = intval($_GET['id']);
$action = $_GET['action'];

// Validate action
$validActions = ['activate', 'deactivate', 'delete', 'edit'];
if (!in_array($action, $validActions)) {
    header("Location: manage_members.php");
    exit();
}

switch($action) {
    case 'activate':
        $stmt = $pdo->prepare("UPDATE users SET status='active' WHERE id=?");
        $stmt->execute([$memberId]);
        break;

    case 'deactivate':
        $stmt = $pdo->prepare("UPDATE users SET status='inactive' WHERE id=?");
        $stmt->execute([$memberId]);
        break;

    case 'delete':
        $stmt = $pdo->prepare("DELETE FROM users WHERE id=?");
        $stmt->execute([$memberId]);
        break;

    case 'edit':
        header("Location: edit_member.php?id=$memberId");
        exit();
}

header("Location: manage_members.php");
exit();