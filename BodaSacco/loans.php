<?php
session_start();
require "includes/config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT * FROM loans 
    WHERE user_id = ? 
    ORDER BY date_applied DESC
");
$stmt->execute([$user_id]);
$loans = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include "includes/header.php"; ?>

<main class="dashboard">
    <div class="container">

        <h2>My Loans</h2>

        <?php if (empty($loans)): ?>
            <p>No loans yet</p>
        <?php else: ?>

        <table class="table">
            <tr>
                <th>Amount</th>
                <th>Period</th>
                <th>Remaining Balance</th>
                <th>Status</th>
                <th>Date</th>
            </tr>

            <?php
                $statusLabels = [
                    'pending' => 'Pending Approval',
                    'approved' => 'Active Loan',
                    'paid' => 'Completed'
                ];
            ?>

            <?php foreach ($loans as $loan): ?>
            <tr>
                <td>KES <?php echo number_format($loan['amount'], 2); ?></td>

                <td><?php echo $loan['repayment_period']; ?> months</td>

                <td>KES <?php echo number_format($loan['remaining_balance'], 2); ?></td>

                <td>
                    <span class="badge <?php echo $loan['status']; ?>">
                        <?php echo $statusLabels[$loan['status']] ?? ucfirst($loan['status']); ?>
                    </span>
                </td>

                <td><?php echo $loan['date_applied']; ?></td>
            </tr>
            <?php endforeach; ?>
        </table>

        <?php endif; ?>

    </div>
</main>

<?php include "includes/footer.php"; ?>