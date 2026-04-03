<?php
session_start();
require "includes/config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT r.*, l.amount 
    FROM repayments r
    JOIN loans l ON r.loan_id = l.id
    WHERE r.user_id = ?
    ORDER BY r.payment_date DESC
");
$stmt->execute([$user_id]);
$payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include "includes/header.php"; ?>

<main class="dashboard">
<div class="container">

<h2>📊 Payment History</h2>

<?php if (empty($payments)): ?>
    <p>No payments yet</p>
<?php else: ?>

<table class="table">
<tr>
    <th>Loan Amount</th>
    <th>Paid</th>
    <th>Date</th>
</tr>

<?php foreach ($payments as $pay): ?>
<tr>
    <td>KES <?php echo number_format($pay['amount'], 2); ?></td>
    <td>KES <?php echo number_format($pay['amount_paid'], 2); ?></td>
    <td><?php echo date("d-M-Y", strtotime($pay['payment_date'])); ?></td>
</tr>
<?php endforeach; ?>

</table>

<?php endif; ?>

</div>
</main>

<?php include "includes/footer.php"; ?>