<?php
session_start();
require "../includes/config.php";

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        "status" => "error",
        "message" => "Unauthorized"
    ]);
    exit();
}

$user_id = $_SESSION['user_id'];
$amount = $_POST['amount'] ?? 0;

if ($amount <= 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid amount"
    ]);
    exit();
}

// Get balance
$stmt = $pdo->prepare("SELECT balance FROM savings WHERE user_id = ?");
$stmt->execute([$user_id]);
$balance = $stmt->fetchColumn();

if ($amount > $balance) {
    echo json_encode([
        "status" => "error",
        "message" => "Insufficient balance"
    ]);
    exit();
}

// New balance
$new_balance = $balance - $amount;

// Update savings
$stmt = $pdo->prepare("UPDATE savings SET balance = ? WHERE user_id = ?");
$stmt->execute([$new_balance, $user_id]);

function generateReference() {
    return 'TXN' . date('Ymd') . strtoupper(substr(uniqid(), -6));
}

$reference = generateReference();

// Insert transaction
$stmt = $pdo->prepare("
    INSERT INTO transactions (user_id, type, amount, balance_after, reference)
    VALUES (?, 'withdraw', ?, ?, ?)
");
$stmt->execute([$user_id, $amount, $new_balance, $reference]);

echo json_encode([
    "status" => "success",
    "message" => "Transaction successful",
    "new_balance" => $new_balance,
    "reference" => $reference
]);