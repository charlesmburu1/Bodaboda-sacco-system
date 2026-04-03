<?php
require_once "../includes/admin_session.php";
require_once "../includes/config.php";

if (!isset($_GET['id'])) {
    header("Location: manage_members.php");
    exit();
}

$memberId = intval($_GET['id']);
$message = "";

// Fetch member data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
$stmt->execute([$memberId]);
$member = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$member) {
    header("Location: manage_members.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $status = $_POST['status'];

    $stmt = $pdo->prepare("UPDATE users SET full_name=?, email=?, phone=?, status=? WHERE id=?");
    if ($stmt->execute([$name, $email, $phone, $status, $memberId])) {
        $message = "Member updated successfully!";
        // Refresh member data
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
        $stmt->execute([$memberId]);
        $member = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        $message = "Something went wrong!";
    }
}

$statusLabels = ['active' => 'Active', 'inactive' => 'Inactive'];

?>

<link rel="stylesheet" href="../assets/css/savings.css">
<?php include "../includes/admin_header.php"; ?>

<div class="dashboard">
    <div class="container">
        <h2>Edit Member</h2>

        <?php if ($message): ?>
            <p class="success"><?php echo $message; ?></p>
        <?php endif; ?>

        <form method="POST" class="form">
            <label>Member FullName</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($member['full_name']); ?>" required>

            <label>Member Email Address</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($member['email']); ?>" required>

            <label>Member Phone No.</label>
            <input type="text" name="phone" value="<?php echo htmlspecialchars($member['phone'] ?? ''); ?>">

            <label>Member Status</label>
            <select name="status" required>
                <?php foreach ($statusLabels as $key => $label): ?>
                    <option value="<?php echo $key; ?>" <?php echo ($member['status'] === $key) ? 'selected' : ''; ?>>
                        <?php echo $label; ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="btn-primary">Update Member</button>
            <a href="manage_members.php" class="btn-secondary">Back</a>
        </form>
    </div>
</div>

<?php include "../includes/admin_footer.php"; ?>