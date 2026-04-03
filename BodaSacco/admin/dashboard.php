<?php
require_once "../includes/admin_session.php";
require_once "../includes/config.php";

// Dashboard stats
$members = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$loans = $pdo->query("SELECT COUNT(*) FROM loans")->fetchColumn();
$pending = $pdo->query("SELECT COUNT(*) FROM loans WHERE status='pending'")->fetchColumn();
$savings = $pdo->query("SELECT SUM(balance) FROM savings")->fetchColumn() ?? 0;

?>

<?php
    // Recent Loans (correct columns)
    $recentLoans = $pdo->query("
        SELECT l.id, u.full_name, l.amount, l.status, l.date_applied
        FROM loans l
        JOIN users u ON l.user_id = u.id
        ORDER BY l.date_applied DESC
        LIMIT 5
    ")->fetchAll(PDO::FETCH_ASSOC);

    // Recent Deposits (from transactions table)
    $recentDeposits = $pdo->query("
        SELECT t.id, u.full_name, t.amount, t.created_at
        FROM transactions t
        JOIN users u ON t.user_id = u.id
        WHERE t.type = 'deposit'
        ORDER BY t.created_at DESC
        LIMIT 5
    ")->fetchAll(PDO::FETCH_ASSOC);
?>

<link rel="stylesheet" href="../assets/css/savings.css">
<?php include "../includes/admin_header.php"; ?>

<div class="dashboard">
    <div class="container">

        <h2>Welcome Admin 👋</h2>
        
        <div class="actions">
            <a href="./admin_loans.php" class="btn-primary">Manage Loans</a>
            <a href="./manage_members.php" class="btn-primary">Members</a>
            <a href="./manage_savings.php" class="btn-primary">Savings</a>
        </div>

        <div class="cards">
            <div class="card">
                <h3>Total Members</h3>
                <p><?php echo $members; ?></p>
            </div>

            <div class="card">
                <h3>Total Loans</h3>
                <p><?php echo $loans; ?></p>
            </div>

            <div class="card">
                <h3>Pending Loans</h3>
                <p><?php echo $pending; ?></p>
            </div>

            <div class="card">
                <h3>Total Savings</h3>
                <p>KES <?php echo number_format($savings, 2); ?></p>
            </div>
        </div>

        <div class="recent">
            <h3>Recent Loans</h3>
            <table class="table">
                <tr>
                    <th>ID</th>
                    <th>Member</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>

                <?php foreach ($recentLoans as $loan): ?>
                <tr>
                    <td><?= $loan['id']; ?></td>
                    <td><?= htmlspecialchars($loan['full_name']); ?></td>
                    <td>KES <?= number_format($loan['amount'],2); ?></td>
                    <td><span class="badge <?= $loan['status']; ?>">
                        <?= ucfirst($loan['status']); ?>
                    </span></td>
                    <td><?= date("d M Y", strtotime($loan['date_applied'])); ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>

        <div class="recent">
            <h3>Recent Deposits</h3>
            <table class="table">
                <tr>
                    <th>ID</th>
                    <th>Member</th>
                    <th>Amount</th>
                    <th>Date</th>
                </tr>

                <?php foreach ($recentDeposits as $deposit): ?>
                <tr>
                    <td><?= $deposit['id']; ?></td>
                    <td><?= htmlspecialchars($deposit['full_name']); ?></td>
                    <td>KES <?= number_format($deposit['amount'],2); ?></td>
                    <td><?= date("d M Y", strtotime($deposit['created_at'])); ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</div>

<?php include "../includes/admin_footer.php"; ?>