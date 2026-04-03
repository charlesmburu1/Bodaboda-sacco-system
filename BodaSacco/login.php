<?php include "includes/header.php"; ?>

<main class="auth-page">
    <div class="auth-container">
        <h2>Login</h2>

        <?php
        if (isset($_SESSION['success'])) {
            echo "<p class='success'>" . $_SESSION['success'] . "</p>";
            unset($_SESSION['success']);
        }

        if (isset($_SESSION['error'])) {
            echo "<p class='error'>" . $_SESSION['error'] . "</p>";
            unset($_SESSION['error']);
        }
        ?>

        <form action="actions/login_process.php" method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>

            <button type="submit" class="btn-primary">Login</button>
            <p class="forgot-link">
                <a href="forgot_password.php">Forgot Password?</a>
            </p>
        </form>
    </div>
</main>

<?php include "includes/footer.php"; ?>