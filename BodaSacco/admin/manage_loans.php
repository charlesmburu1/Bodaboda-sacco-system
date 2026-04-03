<?php
require_once "includes/admin_session.php";
require_once "../includes/config.php";

$stmt = $pdo->query("
    SELECT l.*, m.full_name 
    FROM loans l
    JOIN members m ON l.member_id = m.id
    ORDER BY l.date_applied DESC
");

$loans = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<link rel="stylesheet" href="../assets/css/admin.css">

<h2>Manage Loans</h2>

<table>
<tr>
    <th>Member</th>
    <th>Amount</th>
    <th>Status</th>
    <th>Action</th>
</tr>

<?php foreach($loans as $loan): ?>
<tr>
    <td><?php echo $loan['full_name']; ?></td>
    <td><?php echo $loan['amount']; ?></td>
    <td><?php echo $loan['status']; ?></td>
    <td>
        <?php if($loan['status'] == 'pending'): ?>
            <a href="approve_loan.php?id=<?php echo $loan['id']; ?>">Approve</a>
            <a href="reject_loan.php?id=<?php echo $loan['id']; ?>">Reject</a>
        <?php endif; ?>
    </td>
</tr>
<?php endforeach; ?>
</table>