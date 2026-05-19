<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Pull internal tracing sequences sequentially
$query = "SELECT ip_address, login_time FROM login_history WHERE user_id = ? ORDER BY login_time DESC";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$logs_res = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Audit and Activity Logs</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        /* Force single screen view matching Dashboard/BMI layout styles */
        html, body {
            height: 100vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        
        main.container {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding-top: 1rem !important;
            padding-bottom: 1rem !important;
        }

        .custom-card {
            border: 1px solid #e0e0e0 !important;
            border-top: 4px solid #0dcaf0 !important; /* Cyan Info Accent color */
            border-radius: 12px !important;
            background-color: #ffffff;
        }

        /* Prevent page scrolling by capping the table element */
        .table-scroll-wrapper {
            max-height: 280px;
            overflow-y: auto;
        }

        /* Clean table aesthetics */
        .table th {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6c757d;
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }

        .table td {
            font-size: 0.9rem;
            vertical-align: middle;
        }
    </style>
</head>
<body class="bg-light">
    <?php include 'navbar.php'; ?>

    <main class="container">
        <div class="text-center mb-4">
            <h3 class="fw-bold text-dark mb-1">Security Audit Terminal</h3>
            <p class="text-muted small mb-2">Monitor active system session connections and verified access parameters safely.</p>
            <div class="mx-auto bg-info rounded" style="width: 40px; height: 3px;"></div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <div class="card p-4 shadow-sm border-0 custom-card">
                    <h5 class="fw-bold text-dark mb-3">
                        <i class="fa-solid fa-shield-halved text-info me-2"></i>Login Activity History Logs
                    </h5>
                    
                    <div class="table-scroll-wrapper">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="position-sticky top-0 z-1">
                                    <tr>
                                        <th>IP Endpoint Location</th>
                                        <th class="text-end">Timestamp Log Event Details</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(mysqli_num_rows($logs_res) > 0): ?>
                                        <?php while($row = mysqli_fetch_assoc($logs_res)): ?>
                                            <tr>
                                                <td class="font-monospace text-dark fw-semibold">
                                                    <i class="fa-solid fa-network-wired text-muted me-2 small"></i><?php echo sanitize($row['ip_address']); ?>
                                                </td>
                                                <td class="text-end text-muted font-monospace small">
                                                    <?php echo sanitize($row['login_time']); ?>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="2" class="text-center text-muted py-4 small">No historical session login trace values saved.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>