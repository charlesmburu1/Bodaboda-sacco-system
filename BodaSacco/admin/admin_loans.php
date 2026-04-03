<?php
require_once "../includes/admin_session.php";
require_once "../includes/config.php";

// Fetch all loans with member info
$stmt = $pdo->query("
    SELECT loans.*, users.full_name AS member_name, users.email AS member_email
    FROM loans
    JOIN users ON loans.user_id = users.id
    ORDER BY loans.date_applied DESC
");
$loans = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Status labels
$statusLabels = [
    'pending' => 'Pending Approval',
    'approved' => 'Active Loan',
    'paid' => 'Completed'
];

?>

<link rel="stylesheet" href="../assets/css/savings.css">
<?php include "../includes/admin_header.php"; ?>

<div class="dashboard">
    <div class="container">
        <h2>Manage Loans</h2>

        <!-- SEARCH -->
        <input 
            type="text" 
            placeholder="Search loans..." 
            onkeyup="filterTable('loansTable', this.value)"
            class="search-box"
        >

        <?php if (empty($loans)): ?>
            <p>No loans available.</p>
        <?php else: ?>
            <div class="transactions">
                <table id="loansTable">
                    <thead>
                        <tr>
                            <th>Member</th>
                            <th>Email</th>
                            <th>Amount (KES)</th>
                            <th>Period (Months)</th>
                            <th>Status</th>
                            <th>Date Applied</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($loans as $loan): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($loan['member_name']); ?></td>
                                <td><?php echo htmlspecialchars($loan['member_email']); ?></td>
                                <td><?php echo number_format($loan['amount'], 2); ?></td>
                                <td><?php echo $loan['repayment_period']; ?></td>
                                <td>
                                    <span class="badge <?php echo $loan['status']; ?>">
                                        <?php echo $statusLabels[$loan['status']] ?? ucfirst($loan['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo date("d M Y", strtotime($loan['date_applied'])); ?></td>
                                <td>
                                    <?php if ($loan['status'] === 'pending'): ?>
                                        <a href="loan_action.php?id=<?php echo $loan['id']; ?>&action=approve" class="btn-action approve">Approve</a>
                                        <a href="loan_action.php?id=<?php echo $loan['id']; ?>&action=reject" class="btn-action reject">Reject</a>
                                    <?php elseif ($loan['status'] === 'approved'): ?>
                                        <a href="loan_action.php?id=<?php echo $loan['id']; ?>&action=paid" class="btn-action paid">Mark Paid</a>
                                    <?php else: ?>
                                        <span style="color: #777;">No actions</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include "../includes/admin_footer.php"; ?>