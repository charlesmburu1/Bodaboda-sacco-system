<?php include "includes/header.php"; ?>

<main class="auth-page">
    <div class="auth-container">
        <h2>Forgot Password</h2>

        <?php
        if (isset($_SESSION['message'])) {
            echo "<p class='success'>" . $_SESSION['message'] . "</p>";
            unset($_SESSION['message']);
        }
        ?>

        <form action="actions/forgot_process.php" method="POST">
            <input type="email" name="email" placeholder="Enter your email" required>

            <button type="submit" class="btn-primary">Reset Password</button>
        </form>
    </div>
</main>

<?php include "includes/footer.php"; ?>