<?php

require_once "config.php";

// TOTAL SAVINGS
function getTotalSavings($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT SUM(amount) as total FROM savings WHERE member_id = ?");
    $stmt->execute([$user_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['total'] ?? 0;
}

// ACTIVE LOANS
function getActiveLoans($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM loans WHERE member_id = ? AND status = 'approved'");
    $stmt->execute([$user_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
}

// PENDING LOANS
function getPendingLoans($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM loans WHERE member_id = ? AND status = 'pending'");
    $stmt->execute([$user_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
}

// RECENT TRANSACTIONS
function getTransactions($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT * FROM transactions WHERE member_id = ? ORDER BY created_at DESC LIMIT 5");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}