<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = '';
$today = date('Y-m-d');

if (isset($_POST['mark_attendance'])) {
    $stmt = mysqli_prepare($conn, "INSERT INTO attendance (user_id, attendance_date, status) VALUES (?, ?, 'Present')");
    mysqli_stmt_bind_param($stmt, "is", $user_id, $today);

    if (mysqli_stmt_execute($stmt)) {
        $message = "<div class='alert alert-success py-2 small mb-3'>Attendance log saved successfully!</div>";
    } else {
        $message = "<div class='alert alert-warning py-2 small mb-3'>You have already checked in today.</div>";
    }
    mysqli_stmt_close($stmt);
}

// Extract historical list patterns
$history_query = "SELECT attendance_date, status FROM attendance WHERE user_id = ? ORDER BY attendance_date DESC";
$stmt = mysqli_prepare($conn, $history_query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$history_res = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Manager</title>
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
            border-top: 4px solid #0d6efd !important;
            border-radius: 12px !important;
            background-color: #ffffff;
        }

        .history-card {
            border-top-color: #198754 !important; /* Green Accent for history table */
        }

        /* Prevent page scrolling by capping the table element */
        .table-scroll-wrapper {
            max-height: 240px;
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
        <!-- Compact Section Header -->
        <div class="text-center mb-4">
            <h3 class="fw-bold text-dark mb-1">Attendance Tracker</h3>
            <p class="text-muted small mb-2">Log your active physical terminal presence entries and view your tracking calendar ledger.</p>
            <div class="mx-auto bg-primary rounded" style="width: 40px; height: 3px;"></div>
        </div>

        <div class="row g-3 justify-content-center align-items-stretch">
            <!-- Daily Check In Module -->
            <div class="col-md-4">
                <div class="card p-4 shadow-sm border-0 custom-card text-center h-100 d-flex flex-column justify-content-center">
                    <div class="mb-3 text-primary">
                        <i class="fa-solid fa-calendar-check display-6"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Daily Sign-In</h5>
                    <p class="text-muted small mb-3">Current System Date:<br><span class="font-monospace fw-bold text-dark" style="font-size: 1.1rem;"><?php echo $today; ?></span></p>
                    
                    <?php echo $message; ?>
                    
                    <form action="attendance.php" method="POST" class="mt-auto">
                        <button type="submit" name="mark_attendance" class="btn btn-primary py-2 fw-semibold w-100 rounded-3">
                            <i class="fa-solid fa-right-to-bracket me-2"></i>Check In Today
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- History Logs Module -->
            <div class="col-md-8">
                <div class="card p-4 shadow-sm border-0 custom-card history-card h-100 d-flex flex-column">
                    <h5 class="fw-bold text-dark mb-3"><i class="fa-solid fa-clock-rotate-left text-success me-2"></i>My Attendance History Logs</h5>
                    
                    <div class="table-scroll-wrapper flex-grow-1">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="position-sticky top-0 z-1">
                                    <tr>
                                        <th>Logged Date</th>
                                        <th class="text-end">Status Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(mysqli_num_rows($history_res) > 0): ?>
                                        <?php while($row = mysqli_fetch_assoc($history_res)): ?>
                                            <tr>
                                                <td class="font-monospace text-dark fw-semibold"><?php echo sanitize($row['attendance_date']); ?></td>
                                                <td class="text-end">
                                                    <span class="badge bg-success-subtle text-success px-2.5 py-1.5 rounded-pill fw-semibold small">
                                                        <i class="fa-solid fa-check-circle me-1 small"></i><?php echo sanitize($row['status']); ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="2" class="text-center text-muted py-4 small">No attendance logs found on file.</td>
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