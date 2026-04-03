<?php
require_once "../includes/admin_session.php";
require_once "../includes/config.php";

// Fetch all members
$stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
$members = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Status labels
$statusLabels = [
    'active' => 'Active',
    'inactive' => 'Inactive'
];

?>

<link rel="stylesheet" href="../assets/css/savings.css">
<?php include "../includes/admin_header.php"; ?>

<div class="dashboard">
    <div class="container">
        <h2>Manage Members</h2>

        <!-- SEARCH -->
        <input 
            type="text" 
            placeholder="Search members..." 
            onkeyup="filterTable('membersTable', this.value)"
            class="search-box"
        >

        <?php if (empty($members)): ?>
            <p>No members found.</p>
        <?php else: ?>
            <div class="transactions">
                <table id="membersTable">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Membership Id</th>
                            <th>Date Joined</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($members as $member): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($member['full_name']); ?></td>
                                <td><?php echo htmlspecialchars($member['email']); ?></td>
                                <td><?php echo htmlspecialchars($member['phone'] ?? '-'); ?></td>
                                <td><?php echo htmlspecialchars($member['membership_id'] ?? '-'); ?></td>
                                <td><?php echo date("d M Y", strtotime($member['created_at'])); ?></td>
                                <td>
                                    <span class="badge <?php echo $member['status']; ?>">
                                        <?php echo $statusLabels[$member['status']] ?? ucfirst($member['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="member_action.php?id=<?php echo $member['id']; ?>&action=edit" class="btn-action edit">Edit</a>
                                    <?php if ($member['status'] === 'active'): ?>
                                        <a href="member_action.php?id=<?php echo $member['id']; ?>&action=deactivate" class="btn-action deactivate">Deactivate</a>
                                    <?php else: ?>
                                        <a href="member_action.php?id=<?php echo $member['id']; ?>&action=activate" class="btn-action activate">Activate</a>
                                    <?php endif; ?>
                                    <a href="member_action.php?id=<?php echo $member['id']; ?>&action=delete" class="btn-action delete" onclick="return confirm('Are you sure?')">Delete</a>
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