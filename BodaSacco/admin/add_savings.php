<?php
require_once "../includes/config.php";

if($_SERVER["REQUEST_METHOD"] == "POST") {

    $member_id = $_POST['member_id'];
    $amount = $_POST['amount'];

    if($amount <= 0) {
        echo "Invalid amount";
        exit();
    }

    // Insert savings
    $stmt = $pdo->prepare("INSERT INTO savings (member_id, amount, date_paid)
                           VALUES (?, ?, CURDATE())");
    $stmt->execute([$member_id, $amount]);

    // Record transaction
    $trx = $pdo->prepare("INSERT INTO transactions (member_id, type, amount)
                          VALUES (?, 'deposit', ?)");
    $trx->execute([$member_id, $amount]);

    echo "Savings recorded successfully";
}
?>

<form method="POST">
    <input type="number" name="member_id" placeholder="Member ID" required><br>
    <input type="number" name="amount" placeholder="Amount" required><br>
    <button type="submit">Add Savings</button>
</form>