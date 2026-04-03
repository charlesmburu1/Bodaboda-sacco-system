<?php
session_start();
require "includes/config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$message = "";
$loan_success = false;

// Initialize defaults
$amount = 1000; // default loan amount
$period = 6;    // default repayment period in months
$total_payable = 0;
$monthly_payment = 0;

$user_id = $_SESSION['user_id'];

// Get user's savings balance
$stmt = $pdo->prepare("SELECT balance FROM savings WHERE user_id = ?");
$stmt->execute([$user_id]);
$savings_balance = $stmt->fetchColumn() ?? 0;

// Check for existing pending loan
$stmt = $pdo->prepare("SELECT COUNT(*) FROM loans WHERE user_id = ? AND status = 'pending'");
$stmt->execute([$user_id]);
$pending_loan_count = $stmt->fetchColumn();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $amount = floatval($_POST['amount']);
    $period = intval($_POST['repayment_period']);

        // Validation
    if ($pending_loan_count > 0) {
        $message = "You already have a pending loan. Please wait for approval.";

    } elseif ($savings_balance <= 0) {
        $message = "You must have savings to apply for a loan.";

    } elseif ($amount < 500) {
        $message = "Minimum loan amount is KES 500";

    } elseif ($period > 24) {
        $message = "Repayment period cannot exceed 24 months";

    } elseif ($amount <= 0 || $period <= 0) {
        $message = "Invalid loan amount or repayment period";

    } else {

        // Max loan check
        $maxLoan = $savings_balance * 3;

        if ($amount > $maxLoan) {
            $message = "Loan exceeds your limit (Max: KES " . number_format($maxLoan, 2) . ")";
        } else {

            $interest_rate = 10;

            $interest = ($amount * $interest_rate) / 100;
            $total_payable = $amount + $interest;
            $monthly_payment = $total_payable / $period;

            $stmt = $pdo->prepare("
                INSERT INTO loans 
                (user_id, amount, repayment_period, interest_rate, total_payable, monthly_payment, remaining_balance, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')
            ");

            if($stmt->execute([
                $user_id,
                $amount,
                $period,
                $interest_rate,
                $total_payable,
                $monthly_payment,
                $total_payable // initial balance = total payable
            ])){
                 $message = "Loan application submitted successfully!";
                $loan_success = true;
                $pending_loan_count = 1; // prevent multiple submissions
            } else {
                $message = "Something went wrong. Please try again.";
            }
        }
    }
}
?>

<?php include "includes/header.php"; ?>
<link rel="stylesheet" href="assets/css/savings.css">

<main class="dashboard">
    <div class="container">

        <h2 style="text-align:center;">💰 Apply for Loan</h2>
        <p style="text-align:center; color:#777;">
            Get quick access to funds based on your savings
        </p>

        <?php if ($message): ?>
            <p class="<?php echo $loan_success ? 'success' : 'error'; ?>" style="text-align:center;">
                <?php echo $message; ?>
            </p>
        <?php endif; ?>

        <?php
            $stmt = $pdo->prepare("SELECT status FROM loans WHERE user_id = ? ORDER BY date_applied DESC LIMIT 1");
            $stmt->execute([$user_id]);
            $currentLoan = $stmt->fetch(PDO::FETCH_ASSOC);

            // ✅ Define here (safe place)
            $statusLabels = [
                'pending' => 'Pending Approval',
                'approved' => 'Active Loan',
                'paid' => 'Completed'
            ];
        ?>

        <?php if ($currentLoan): ?>
            <div style="text-align:center; margin-bottom:15px;">
                <p>
                    Current Loan Status:
                    <span class="badge <?php echo $currentLoan['status']; ?>">
                        <?php echo $statusLabels[$currentLoan['status']] ?? ucfirst($currentLoan['status']); ?>
                    </span>
                </p>
            </div>
        <?php endif; ?>

        <form method="POST" class="form" <?php echo ($pending_loan_count > 0) ? 'style="opacity:0.6; pointer-events:none;"' : ''; ?>>

            <label>Loan Amount (KES)</label>
            <input type="number" id="amount" name="amount" placeholder="Enter amount" 
                   value="<?php echo htmlspecialchars($amount); ?>" 
                   min="1" max="<?php echo $savings_balance * 3; ?>" required>

            <label>Repayment Period (Months)</label>
            <input type="number" id="period" name="repayment_period" placeholder="e.g. 6, 12" 
                   value="<?php echo htmlspecialchars($period); ?>" 
                   min="1" required>

            <button type="submit" class="btn-primary" <?php echo ($pending_loan_count > 0) ? 'disabled' : ''; ?>>
                Apply Loan
            </button>

            <?php if ($pending_loan_count > 0): ?>
                <p style="color:#ff5722; margin-top:10px; font-weight:bold; text-align:center;">
                    ⚠ You already have a pending loan. Please wait for approval.
                </p>
            <?php endif; ?>

        </form>

        <div class="loan-preview">
            <h4>Loan Preview</h4>
            <p>Max Loan Limit: <strong>KES <?php echo number_format($savings_balance * 3, 2); ?></strong></p>
            <p>Total Payable: <strong id="total_payable">KES 0.00</strong></p>
            <p>Monthly Payment: <strong id="monthly_payment">KES 0.00</strong></p>
            <p>Interest Rate: <strong>10%</strong></p>
        </div>

    </div>
</main>

<script>
    const amountInput = document.getElementById('amount');
    const periodInput = document.getElementById('period');
    const totalPayableEl = document.getElementById('total_payable');
    const monthlyPaymentEl = document.getElementById('monthly_payment');
    const interestRate = 10;

    function updateLoanPreview() {
        const amount = parseFloat(amountInput.value) || 0;
        const period = parseInt(periodInput.value) || 1;

        const interest = (amount * interestRate) / 100;
        const total = amount + interest;
        const monthly = total / period;

        totalPayableEl.textContent = 'KES ' + total.toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2});
        monthlyPaymentEl.textContent = 'KES ' + monthly.toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2});
    }

    // Update preview on input
    amountInput.addEventListener('input', updateLoanPreview);
    periodInput.addEventListener('input', updateLoanPreview);

    // Initialize preview
    updateLoanPreview();
</script>

<?php include "includes/footer.php"; ?>