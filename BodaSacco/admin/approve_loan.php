<?php
require_once "includes/admin_session.php";
require_once "../includes/config.php";

$id = $_GET['id'];

$stmt = $pdo->prepare("UPDATE loans SET status='approved' WHERE id=?");
$stmt->execute([$id]);

header("Location: manage_loans.php");