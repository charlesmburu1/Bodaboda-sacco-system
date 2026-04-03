<?php include "includes/header.php"; ?>

<main class="auth-page">
    <div class="auth-container">
        <h2>Create Account</h2>

        <?php
        if (isset($_SESSION['errors'])) {
            foreach ($_SESSION['errors'] as $error) {
                echo "<p class='error'>$error</p>";
            }
            unset($_SESSION['errors']);
        }
        ?>

        <form action="actions/register_process.php" method="POST">
            <input type="text" name="full_name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="phone" placeholder="Phone Number" required>
            <input type="password" name="password" placeholder="Password" required>

            <button type="submit" class="btn-primary">Register</button>
        </form>
    </div>
</main>

<?php include "includes/footer.php"; ?>