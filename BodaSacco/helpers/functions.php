<?php

function generateMembershipID($pdo) {
    $year = date("Y");

    $stmt = $pdo->query("SELECT id FROM users ORDER BY id DESC LIMIT 1");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $number = $row ? $row['id'] + 1 : 1;

    return "BODA-" . $year . "-" . str_pad($number, 4, "0", STR_PAD_LEFT);
}

function generateTransactionRef() {
    return "TXN" . time() . rand(100, 999);
}