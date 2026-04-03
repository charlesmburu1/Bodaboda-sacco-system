<?php
session_start();
require "includes/config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

// Get active loan
$stmt = $pdo->prepare("
    SELECT * FROM loans 
    WHERE user_id = ? AND status = 'approved'
    LIMIT 1
");
$stmt->execute([$user_id]);
$loan = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$loan) {
    $message = "No active approved loan found.";
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && $loan) {
    if ($loan['status'] === 'paid') {
        $message = "Loan already cleared.";
    } else {
        $amount_paid = floatval($_POST['amount_paid']);

        if ($amount_paid <= 0) {
            $message = "Invalid payment amount";
            } elseif ($amount_paid > $loan['remaining_balance']) {
            $message = "Payment exceeds remaining balance";
            } else {

                // Insert repayment
                $stmt = $pdo->prepare("
                    INSERT INTO repayments (loan_id, user_id, amount_paid)
                    VALUES (?, ?, ?)
                ");
                $stmt->execute([$loan['id'], $user_id, $amount_paid]);

                // Update remaining balance
                $new_balance = $loan['remaining_balance'] - $amount_paid;

                $status = ($new_balance <= 0) ? 'paid' : 'approved';

                $stmt = $pdo->prepare("
                    UPDATE loans 
                    SET remaining_balance = ?, status = ?
                    WHERE id = ?
                ");
                $stmt->execute([$new_balance, $status, $loan['id']]);

                $message = "Payment successful!";
                
                // Refresh loan data
                header("Location: repay_loan.php");
                exit();
            }
        }
    }

?>


<?php include "includes/header.php"; ?>

<?php
  if (isset($loan['total_payable']) && (float)$loan['total_payable'] > 0) {
    $paid = (float)$loan['total_payable'] - (float)($loan['remaining_balance'] ?? 0);
    $progress = ($paid / (float)$loan['total_payable']) * 100;
} else {
    $progress = 0;
}
?>

<div style="margin:15px 0;">
    <p>Progress: <?php echo round($progress); ?>%</p>
    <div style="background:#eee; height:10px; border-radius:5px;">
        <div style="width:<?php echo $progress; ?>%; background:green; height:100%; border-radius:5px;"></div>
    </div>
</div>

<main class="dashboard">
<div class="container">

<h2>💳 Repay Loan</h2>

<?php if ($message): ?>
<p><?php echo $message; ?></p>
<?php endif; ?>

<?php if ($loan): ?>

<p>Total Loan: KES <?php echo number_format($loan['total_payable'], 2); ?></p>
<p>Remaining Balance: KES <?php echo number_format($loan['remaining_balance'], 2); ?></p>

<form method="POST">
    <label>Amount to Pay</label>
    <input 
        type="number" 
        name="amount_paid" 
        max="<?php echo $loan['remaining_balance']; ?>" 
        placeholder="Max: <?php echo number_format($loan['remaining_balance'], 2); ?>"
        required
    >
    <!-- <input type="number" name="amount_paid" required> -->

    <button type="submit">Make Payment</button>
</form>

<?php endif; ?>

</div>
</main>

<?php include "includes/footer.php"; ?>