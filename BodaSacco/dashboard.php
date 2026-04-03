<?php
    session_start();
    require "includes/config.php";
    
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
        }
        
    $user_id = $_SESSION['user_id'];

    // Balance
    $stmt = $pdo->prepare("SELECT balance FROM savings WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $balance = $stmt->fetchColumn();

    // Last transaction
    $stmt = $pdo->prepare("
        SELECT type, amount, created_at 
        FROM transactions 
        WHERE user_id = ? 
        ORDER BY created_at DESC 
        LIMIT 1
    ");
    $stmt->execute([$user_id]);
    $last = $stmt->fetch(PDO::FETCH_ASSOC);

    // Total deposits
    $stmt = $pdo->prepare("SELECT SUM(amount) FROM transactions WHERE user_id = ? AND type = 'deposit'");
    $stmt->execute([$user_id]);
    $total_deposits = $stmt->fetchColumn() ?? 0;

    // Transaction count
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM transactions WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $total_transactions = $stmt->fetchColumn();

    // Recent transactions
    $stmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
    $stmt->execute([$user_id]);
    $recent = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Monthly deposits
    $stmt = $pdo->prepare("
        SELECT MONTH(created_at) as month, SUM(amount) as total
        FROM transactions
        WHERE user_id = ? AND type = 'deposit'
        GROUP BY MONTH(created_at)
    ");
    $stmt->execute([$user_id]);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $months = [];
    $totals = [];

    foreach ($data as $d) {
        $monthNames = [
            1=>"Jan",2=>"Feb",3=>"Mar",4=>"Apr",
            5=>"May",6=>"Jun",7=>"Jul",8=>"Aug",
            9=>"Sep",10=>"Oct",11=>"Nov",12=>"Dec"
        ];

        $months[] = $monthNames[$d['month']];
        $totals[] = $d['total'];
    }

    // ================= LOANS DATA =================

    // Active loans (approved but not paid)
    $stmt = $pdo->prepare("
        SELECT COUNT(*) 
        FROM loans 
        WHERE user_id = ? AND status = 'approved'
    ");
    $stmt->execute([$user_id]);
    $active_loans = $stmt->fetchColumn();

    // Total loan amount (approved loans)
    $stmt = $pdo->prepare("
        SELECT SUM(amount) 
        FROM loans 
        WHERE user_id = ? AND status = 'approved'
    ");
    $stmt->execute([$user_id]);
    $total_loans = $stmt->fetchColumn() ?? 0;

    // Latest loan status
    $stmt = $pdo->prepare("
        SELECT status 
        FROM loans 
        WHERE user_id = ? 
        ORDER BY date_applied DESC 
        LIMIT 1
    ");
    $stmt->execute([$user_id]);
    $loan_status = $stmt->fetchColumn();

    $active_loans = $active_loans ?? 0;
    $total_loans = $total_loans ?? 0;
?>

<?php if (empty($recent)): ?>
    <p style="text-align:center; color:#777; margin-top:20px;">
        No transactions yet
    </p>
<?php endif; ?>

<?php include "includes/header.php"; ?>
<link rel="stylesheet" href="assets/css/savings.css">

<main class="dashboard">

    <div class="container">

        <h2 class="welcome">Welcome, <?php echo $_SESSION['user_name']; ?> 👋</h2>
        <?php if ($last): ?>
            <div class="notification">
                Last transaction: 
                <strong><?php echo ucfirst($last['type']); ?></strong> 
                of KES <?php echo number_format($last['amount'], 2); ?>
            </div>
        <?php endif;?>
        
        <!-- ACTIONS -->
        <div class="actions">
            <a href="deposit.php" class="btn-primary">Deposit</a>
            <a href="withdraw.php" class="btn-secondary">Withdraw</a>
            <a href="apply_loan.php" class="btn-primary">Apply Loan</a>
            <a href="repay_loan.php" class="btn-primary">Repay Loan</a>
            <a href="payment_history.php" class="btn-outline">Payment History</a>
            <a href="transactions.php" class="btn-outline">View All</a>
        </div>

        <!-- CARDS -->
        <div class="cards">

            <div class="card balance">
                <h4>Total Balance</h4>
                <p id="balance">KES <?php echo number_format($balance, 2); ?></p>
            </div>

            <div class="card">
                <h4>Total Deposits</h4>
                <p>KES <?php echo number_format($total_deposits, 2); ?></p>
            </div>

            <div class="card">
                <h4>Transactions</h4>
                <p><?php echo $total_transactions; ?></p>
            </div>

            <div class="card">
                <h4>Active Loans</h4>
                <p><?php echo $active_loans; ?></p>
            </div>

            <div class="card">
                <h4>Total Loan Amount</h4>
                <p>KES <?php echo number_format($total_loans, 2); ?></p>
            </div>

            <div class="card">
                <h4>Loan Status</h4>
                <p>
                    <?php 
                        echo $loan_status ? ucfirst($loan_status) : "No loans";
                    ?>
                </p>
            </div>
        </div>


        <!-- Chart -->
        <div class="chart-box">
            <h3>📈 Savings Overview</h3>
            <p style="color:#777; font-size:14px;">Track your monthly deposits at a glance</p>
            <canvas id="chart"></canvas>
        </div>

        <!-- Chart Script -->
        <script>
            const ctx = document.getElementById('chart').getContext('2d');

            // Gradient fill
            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(255, 152, 0, 0.4)');
            gradient.addColorStop(1, 'rgba(255, 152, 0, 0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: <?php echo json_encode($months); ?>,
                    datasets: [{
                        label: 'Savings Trend',
                        data: <?php echo json_encode($totals); ?>,
                        borderColor: '#ff9800',
                        backgroundColor: gradient,
                        fill: true,
                        tension: 0.45,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#ff9800',
                        pointBorderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,

                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#0d1b2a',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            padding: 10,
                            callbacks: {
                                label: function(context) {
                                    return "KES " + context.parsed.y.toLocaleString();
                                }
                            }
                        }
                    },

                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#eee'
                            },
                            ticks: {
                                callback: function(value) {
                                    return "KES " + value;
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        </script>

        <!-- LOANS SECTION -->
        <div class="recent loans">
            <h3>📄 My Loans</h3>

            <?php
            $stmt = $pdo->prepare("SELECT * FROM loans WHERE user_id = ? ORDER BY date_applied DESC");
            $stmt->execute([$_SESSION['user_id']]);
            $loans = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <?php if (empty($loans)): ?>
                <p style="color:#777; margin-top:10px;">No loans yet</p>
            <?php else: ?>
                <table class="table">
                    <!-- <tr>
                        <th>Amount</th>
                        <th>Period</th>
                        <th>Status</th>
                        <th>Date Applied</th>
                    </tr> -->
                    <tr>
                        <th>Amount</th>
                        <th>Interest</th>
                        <th>Total Payable</th>
                        <th>Monthly Payment</th>
                        <th>Period</th>
                        <th>Status</th>
                        <th>Date Applied</th>
                    </tr>

                    <?php foreach ($loans as $loan): 

                        $interest = ($loan['amount'] * $loan['interest_rate']) / 100;
                        $total = $loan['amount'] + $interest;
                        $monthly = $total / $loan['repayment_period'];

                    ?>

                    <tr>
                        <td>KES <?php echo number_format($loan['amount'], 2); ?></td>
                        <td>KES <?php echo number_format($interest, 2); ?></td>
                        <td>KES <?php echo number_format($total, 2); ?></td>
                        <td>KES <?php echo number_format($monthly, 2); ?></td>
                        <td><?php echo $loan['repayment_period']; ?> months</td>
                        <td>
                            <span class="badge <?php echo $loan['status']; ?>">
                                <?php echo ucfirst($loan['status']); ?>
                            </span>
                        </td>
                        <td><?php echo date("d-M-Y", strtotime($loan['date_applied'])); ?></td>
                    </tr>

                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        </div>

        <!-- RECENT TRANSACTIONS -->
        <div class="recent">
            <h3>Recent Transactions</h3>

            <table class="table">
                <tr>
                    <th>Ref</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Balance</th>
                </tr>

                <?php foreach ($recent as $t): ?>
                <tr>
                    <td><?php echo $t['reference']; ?></td>
                    <td><?php echo ucfirst($t['type']); ?></td>
                    <td>KES <?php echo number_format($t['amount'], 2); ?></td>
                    <td>KES <?php echo number_format($t['balance_after'], 2); ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>

    </div>

</main>

<?php include "includes/footer.php"; ?>