<?php include "includes/header.php"; ?>

<main class="auth-page">
    <div class="auth-container">
        <h2>Reset Password</h2>

        <form action="actions/reset_process.php" method="POST">
            <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>">

            <input type="password" name="password" placeholder="New Password" required>

            <button type="submit" class="btn-primary">Update Password</button>
        </form>
    </div>
</main>

<?php include "includes/footer.php"; ?>