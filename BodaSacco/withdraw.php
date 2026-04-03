<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<?php include "includes/header.php"; ?>
<link rel="stylesheet" href="assets/css/savings.css">

<main class="auth-page">
    <div class="auth-container">
        <h2>Withdraw Money</h2>

        <?php
            if (isset($_SESSION['success'])) {
                echo "<p class='success'>" . $_SESSION['success'] . "</p>";
                unset($_SESSION['success']);
            }
        ?>

        <?php
            if (isset($_SESSION['error'])) {
                echo "<p class='error'>" . $_SESSION['error'] . "</p>";
                unset($_SESSION['error']);
            }
        ?>

        <!-- <form action="actions/withdraw_process.php" method="POST">
            <input type="number" name="amount" placeholder="Enter amount" required>
            <button type="submit" class="btn-primary">Withdraw</button>
        </form> -->

        <form id="withdrawForm">
            <input type="number" name="amount" id="amount" placeholder="Enter amount" required>
            <button type="submit" class="btn-primary" id="withdrawBtn">Withdraw</button>
        </form>

        <p id="message"></p>
    </div>
</main>

<script>
    document.getElementById('withdrawForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const amount = document.getElementById('amount').value;
        const btn = document.getElementById('withdrawBtn');
        const message = document.getElementById('message');

        btn.disabled = true;
        btn.innerText = "Processing...";

        fetch('actions/withdraw_process.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'amount=' + amount
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            btn.innerText = "Withdraw";

            if (data.status === 'success') {
                message.className = 'success';
                message.innerText = data.message + " (Ref: " + data.reference + ")";

                document.getElementById('amount').value = '';

                const balanceEl = document.getElementById('balance');

                if (balanceEl) {
                    let current = parseFloat(balanceEl.innerText.replace(/[^0-9.-]+/g,"")) || 0;
                    let updated = parseFloat(data.new_balance);

                    animateBalance(balanceEl, current, updated);
                }

            } else {
                message.className = 'error';
                message.innerText = data.message;
            }
        })
        .catch(() => {
            message.className = 'error';
            message.innerText = "Something went wrong";
            btn.disabled = false;
        });
    });
</script>

<?php include "includes/footer.php"; ?>