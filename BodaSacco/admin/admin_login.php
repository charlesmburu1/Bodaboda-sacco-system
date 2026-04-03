<?php
session_start();
require_once "../includes/config.php";
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];

        header("Location: dashboard.php"); 
        exit();
    } else {
        $message = "Invalid username or password";
    }
}
?>

<link rel="stylesheet" href="../assets/css/auth.css">

<main class="auth-page">
    <div class="auth-container">
        <h2>Admin Login</h2>

        <?php if ($message): ?>
            <p class="error"><?php echo $message; ?></p>
        <?php endif; ?>

        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>

            <input type="password" name="password" placeholder="Password" required>

            <button type="submit" class="btn-primary">Login</button>
        </form>
    </div>
</main>