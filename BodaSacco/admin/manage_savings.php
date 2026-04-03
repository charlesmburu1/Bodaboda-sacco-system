<?php
require_once "../includes/admin_session.php";
require_once "../includes/config.php";

// ==========================
// FETCH SAVINGS DATA
// ==========================
$stmt = $pdo->query("
    SELECT 
        u.id,
        u.full_name,
        u.email,
        s.balance,
        s.updated_at
    FROM savings s
    JOIN users u ON s.user_id = u.id
    ORDER BY s.updated_at DESC
");
$savings = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ==========================
// TOTAL SAVINGS
// ==========================
$totalSavings = $pdo->query("SELECT SUM(balance) FROM savings")->fetchColumn();
$totalSavings = $totalSavings ? $totalSavings : 0;
?>

<link rel="stylesheet" href="../assets/css/savings.css">
<?php include "../includes/admin_header.php"; ?>

<div class="dashboard">
    <div class="container">

        <h2>Manage Savings</h2>

        <!-- TOTAL CARD -->
        <div class="cards">
            <div class="card">
                <h3>Total Savings</h3>
                <p>KES <?php echo number_format($totalSavings, 2); ?></p>
            </div>
        </div>

        <!-- SEARCH -->
        <input 
            type="text" 
            placeholder="Search member savings..." 
            onkeyup="filterTable('savingsTable', this.value)"
            class="search-box"
        >

        <?php if (empty($savings)): ?>
            <p>No savings records found.</p>
        <?php else: ?>

        <!-- TABLE -->
        <div class="transactions">
            <table id="savingsTable">
                <thead>
                    <tr>
                        <th>Member</th>
                        <th>Email</th>
                        <th>Balance</th>
                        <th>Last Updated</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($savings as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td>KES <?php echo number_format($row['balance'], 2); ?></td>
                            <td><?php echo date("d M Y", strtotime($row['updated_at'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php endif; ?>

    </div>
</div>

<?php include "../includes/admin_footer.php"; ?>