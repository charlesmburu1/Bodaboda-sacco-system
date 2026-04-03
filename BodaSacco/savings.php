<?php
session_start();
require_once "includes/config.php";

    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
        }

$user_id = $_SESSION['user_id'];

// ==========================
// ENSURE USER HAS SAVINGS RECORD
// ==========================
$stmt = $pdo->prepare("SELECT * FROM savings WHERE user_id = ?");
$stmt->execute([$user_id]);
$savings = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$savings) {
    $pdo->prepare("INSERT INTO savings (user_id, balance) VALUES (?, 0)")
        ->execute([$user_id]);

    $balance = 0;
} else {
    $balance = $savings['balance'];
}

// ==========================
// HANDLE TRANSACTION
// ==========================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $amount = floatval($_POST['amount']);
    $type = $_POST['type'];

    if ($amount <= 0) {
        $error = "Invalid amount.";
    } else {

        if ($type === 'deposit') {

            $newBalance = $balance + $amount;

        } elseif ($type === 'withdraw') {

            if ($amount > $balance) {
                $error = "Insufficient balance.";
            } else {
                $newBalance = $balance - $amount;
            }
        }

        // If no error → process transaction
        if (!isset($error)) {

            $pdo->beginTransaction();

            try {
                // Update balance
                $update = $pdo->prepare("UPDATE savings SET balance = ? WHERE user_id = ?");
                $update->execute([$newBalance, $user_id]);

                // Save transaction
                $insert = $pdo->prepare("
                    INSERT INTO transactions (user_id, type, amount, balance_after)
                    VALUES (?, ?, ?, ?)
                ");
                $insert->execute([$user_id, $type, $amount, $newBalance]);

                $pdo->commit();

                // Refresh balance
                $balance = $newBalance;

                $success = ucfirst($type) . " successful!";

            } catch (Exception $e) {
                $pdo->rollBack();
                $error = "Transaction failed.";
            }
        }
    }
}

// ==========================
// FETCH TRANSACTION HISTORY
// ==========================
$stmt = $pdo->prepare("
    SELECT type, amount, balance_after, created_at
    FROM transactions
    WHERE user_id = ?
    ORDER BY created_at DESC
");
$stmt->execute([$user_id]);
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<link rel="stylesheet" href="./assets/css/savings.css">
<?php include "includes/header.php"; ?>

<div class="dashboard">
    <div class="container">

        <h2>My Savings</h2>

        <!-- ACTIONS -->
        <div class="actions">
            <a href="deposit.php" class="btn-primary">Deposit</a>
            <a href="withdraw.php" class="btn-secondary">Withdraw</a>
        </div>

        <!-- BALANCE CARD -->
        <div class="cards">
            <div class="card">
                <h3>Current Balance</h3>
                <p id="balance">KES <?php echo number_format($balance, 2); ?></p>
            </div>
        </div>

        <!-- ALERTS -->
        <?php if (isset($error)): ?>
            <div class="alert error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if (isset($success)): ?>
            <div class="alert success"><?php echo $success; ?></div>
        <?php endif; ?>


        <!-- TRANSACTION HISTORY -->
        <div class="recent">
            <h3>Transaction History</h3>

            <?php if (empty($transactions)): ?>
                <p>No transactions yet.</p>
            <?php else: ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Balance After</th>
                            <th>Date</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($transactions as $tx): ?>
                            <tr>
                                <td>
                                    <span class="badge <?php echo $tx['type']; ?>">
                                        <?php echo ucfirst($tx['type']); ?>
                                    </span>
                                </td>
                                <td>KES <?php echo number_format($tx['amount'], 2); ?></td>
                                <td>KES <?php echo number_format($tx['balance_after'], 2); ?></td>
                                <td><?php echo date("d M Y", strtotime($tx['created_at'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

    </div>
</div>

<?php include "includes/footer.php"; ?>