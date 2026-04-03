<?php
session_start();
require "../includes/config.php";
require "../helpers/functions.php";

// ✅ Return JSON always
header('Content-Type: application/json');

// ✅ Check login
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        "status" => "error",
        "message" => "Unauthorized"
    ]);
    exit();
}

$user_id = $_SESSION['user_id'];
$amount = $_POST['amount'] ?? 0;

// ✅ VALIDATION
if (!is_numeric($amount) || $amount <= 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid amount"
    ]);
    exit();
}

try {
    $pdo->beginTransaction();

    // 🔒 Lock user savings row
    $stmt = $pdo->prepare("SELECT balance FROM savings WHERE user_id = ? FOR UPDATE");
    $stmt->execute([$user_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        throw new Exception("Savings account not found");
    }

    $current_balance = $row['balance'];
    $new_balance = $current_balance + $amount;

    // ✅ Update balance
    $stmt = $pdo->prepare("UPDATE savings SET balance = ? WHERE user_id = ?");
    $stmt->execute([$new_balance, $user_id]);

    // ✅ Generate reference (use helper if exists)
    if (function_exists('generateTransactionRef')) {
        $reference = generateTransactionRef();
    } else {
        $reference = 'TXN' . date('Ymd') . strtoupper(substr(uniqid(), -6));
    }

    // ✅ Insert transaction
    $stmt = $pdo->prepare("
        INSERT INTO transactions (user_id, type, amount, reference, balance_after) 
        VALUES (?, 'deposit', ?, ?, ?)
    ");
    $stmt->execute([$user_id, $amount, $reference, $new_balance]);

    $pdo->commit();

    // ✅ SUCCESS RESPONSE
    echo json_encode([
        "status" => "success",
        "message" => "Deposit successful",
        "new_balance" => $new_balance,
        "reference" => $reference
    ]);

} catch (Exception $e) {
    $pdo->rollBack();
    error_log($e->getMessage());

    echo json_encode([
        "status" => "error",
        "message" => "Transaction failed. Please try again."
    ]);
}

exit();