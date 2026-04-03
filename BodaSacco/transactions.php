<?php
session_start();
require "includes/config.php";

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);

$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include "includes/header.php"; ?>

<main class="container">
    <h2>Transaction History</h2>

    <table class="table">
        <tr>
            <th>Reference</th>
            <th>Type</th>
            <th>Amount</th>
            <th>Balance After</th>
            <th>Date</th>
        </tr>

        <?php foreach ($transactions as $t): ?>
        <tr>
            <td><?php echo $t['reference']; ?></td>
            <td><?php echo ucfirst($t['type']); ?></td>
            <td>KES <?php echo number_format($t['amount'], 2); ?></td>
            <td>KES <?php echo number_format($t['balance_after'], 2); ?></td>
            <td><?php echo $t['created_at']; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</main>

<?php include "includes/footer.php"; ?>