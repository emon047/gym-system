<?php
require_once 'config.php';

// Route back to sign in window frame if missing active credentials
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = intval($_SESSION['user_id']);

// Securely Pull attendance record data counters using prepared statements
$stmt_att = mysqli_prepare($conn, "SELECT COUNT(*) as total FROM attendance WHERE user_id = ?");
mysqli_stmt_bind_param($stmt_att, "i", $user_id);
mysqli_stmt_execute($stmt_att);
$att_count_res = mysqli_stmt_get_result($stmt_att);
$att_count = mysqli_fetch_assoc($att_count_res)['total'];

// Securely Pull transaction structural metadata updates
$stmt_pay = mysqli_prepare($conn, "SELECT SUM(amount) as total FROM payments WHERE user_id = ? AND payment_status = 'Success'");
mysqli_stmt_bind_param($stmt_pay, "i", $user_id);
mysqli_stmt_execute($stmt_pay);
$pay_count_res = mysqli_stmt_get_result($stmt_pay);
$total_paid = mysqli_fetch_assoc($pay_count_res)['total'] ?? 0.00;

// Securely Gather structural latest log actions
$stmt_log = mysqli_prepare($conn, "SELECT login_time FROM login_history WHERE user_id = ? ORDER BY id DESC LIMIT 1");
mysqli_stmt_bind_param($stmt_log, "i", $user_id);
mysqli_stmt_execute($stmt_log);
$log_count_res = mysqli_stmt_get_result($stmt_log);
$last_login = mysqli_fetch_assoc($log_count_res)['login_time'] ?? 'First entry';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        /* Consistent Light UI Dashboard Enhancements */
        .metric-card {
            border: 1px solid #e0e0e0 !important;
            border-top: 4px solid !important;
            border-radius: 12px !important;
            background-color: #ffffff;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .metric-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05) !important;
        }
        .border-primary-accent { border-color: #0d6efd !important; }
        .border-success-accent { border-color: #198754 !important; }
        .border-info-accent { border-color: #0dcaf0 !important; }
        
        .icon-box {
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
        }

        .action-card-btn {
            background-color: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: #212529;
            transition: all 0.2s ease;
        }
        .action-card-btn:hover {
            background-color: #f8f9fa;
            border-color: #adb5bd;
            color: #000000;
            transform: translateY(-2px);
        }

        .pulse-indicator {
            width: 8px;
            height: 8px;
            background-color: #198754;
            border-radius: 50%;
            display: inline-block;
            animation: pulse-glow 2s infinite;
        }
        @keyframes pulse-glow {
            0% { transform: scale(0.95); opacity: 0.5; }
            50% { transform: scale(1.1); opacity: 1; }
            100% { transform: scale(0.95); opacity: 0.5; }
        }
    </style>
</head>
<body class="bg-light">
    <?php include 'navbar.php'; ?>

    <main class="container my-5">
        <!-- Dashboard Header -->
        <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom">
            <div>
                <h2 class="fw-bold text-dark mb-0">Welcome, <?php echo sanitize($_SESSION['user_name']); ?></h2>
            </div>
            <div class="d-flex align-items-center gap-2 px-3 py-1 bg-white border rounded-pill shadow-sm small">
                <span class="pulse-indicator"></span>
                <span class="text-secondary fw-semibold text-uppercase small">Active</span>
            </div>
        </div>
        
        <!-- Metrics Performance Display Layout Grid -->
        <div class="row g-4 mb-4">
            <!-- Metric 1: Attendance Checkins -->
            <div class="col-md-4">
                <div class="card p-3 shadow-sm border-0 metric-card border-primary-accent">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6 class="text-muted fw-semibold mb-0">Attendance</h6>
                        <div class="icon-box bg-primary bg-opacity-10 text-primary">
                            <i class="fa-solid fa-calendar-check fs-5"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-dark mb-1"><?php echo $att_count; ?></h3>
                    <p class="mb-0 text-muted small">Total recorded sessions</p>
                </div>
            </div>

            <!-- Metric 2: Financial Investments -->
            <div class="col-md-4">
                <div class="card p-3 shadow-sm border-0 metric-card border-success-accent">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6 class="text-muted fw-semibold mb-0">Payments</h6>
                        <div class="icon-box bg-success bg-opacity-10 text-success">
                            <i class="fa-solid fa-credit-card fs-5"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-dark mb-1">$<?php echo number_format($total_paid, 2); ?></h3>
                    <p class="mb-0 text-muted small">Successful plan renewals</p>
                </div>
            </div>

            <!-- Metric 3: Log Activity Entry Tracker -->
            <div class="col-md-4">
                <div class="card p-3 shadow-sm border-0 metric-card border-info-accent">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h6 class="text-muted fw-semibold mb-0">Last Active</h6>
                        <div class="icon-box bg-info bg-opacity-10 text-info">
                            <i class="fa-solid fa-clock fs-5"></i>
                        </div>
                    </div>
                    <h6 class="text-dark font-monospace fw-bold text-truncate my-1" style="font-size: 1.05rem;">
                        <?php echo $last_login; ?>
                    </h6>
                    <p class="mb-0 text-muted small">System entry validation</p>
                </div>
            </div>
        </div>

        <!-- Quick Functional Navigation Panel -->
        <div class="card p-4 shadow-sm border-0 rounded-3 bg-white">
            <h5 class="fw-bold text-dark mb-3">Quick Actions</h5>
            
            <div class="row g-3">
                <div class="col-sm-6 col-md-3">
                    <a href="attendance.php" class="action-card-btn">
                        <i class="fa-solid fa-clipboard-user text-primary fs-5"></i>
                        <span class="fw-semibold small">Check-in Today</span>
                    </a>
                </div>
                <div class="col-sm-6 col-md-3">
                    <a href="membership.php" class="action-card-btn">
                        <i class="fa-solid fa-tags text-success fs-5"></i>
                        <span class="fw-semibold small">Browse Tiers</span>
                    </a>
                </div>
                <div class="col-sm-6 col-md-3">
                    <a href="profile.php" class="action-card-btn">
                        <i class="fa-solid fa-user-gear text-secondary fs-5"></i>
                        <span class="fw-semibold small">Update Account</span>
                    </a>
                </div>
                <div class="col-sm-6 col-md-3">
                    <a href="bmi.php" class="action-card-btn">
                        <i class="fa-solid fa-heart-pulse text-danger fs-5"></i>
                        <span class="fw-semibold small">Calculate BMI</span>
                    </a>
                </div>
            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>