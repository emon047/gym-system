<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = mysqli_prepare($conn, "SELECT name, email, phone, gender, created_at FROM users WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        /* Force single screen view matching Home, Dashboard, and BMI layout structures */
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
            border-top: 4px solid #6f42c1 !important; /* Premium Purple Accent Line */
            border-radius: 12px !important;
            background-color: #ffffff;
        }

        .profile-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #f1f3f5;
        }
        .profile-row:last-of-type {
            border-bottom: none;
        }
        .profile-label {
            font-size: 0.88rem;
            color: #6c757d;
            font-weight: 500;
        }
        .profile-value {
            font-size: 0.92rem;
            color: #212529;
            font-weight: 600;
        }
    </style>
</head>
<body class="bg-light">
    <?php include 'navbar.php'; ?>

    <main class="container">
        <!-- Compact Section Header (Matches elite typographic standard) -->
        <div class="text-center mb-3">
            <span class="badge bg-purple px-3 py-1.5 rounded-pill mb-2 fw-semibold tracking-wide" style="font-size: 0.75rem; background-color: #6f42c1;">IDENTITY CONTEXT</span>
            <h3 class="fw-bold text-dark mb-1">Account Overview</h3>
            <p class="text-muted small mb-2">Manage your authentication details and identity access configuration parameters.</p>
            <div class="mx-auto rounded" style="width: 40px; height: 3px; background-color: #6f42c1;"></div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5 col-xl-4">
                <div class="card p-3 shadow-sm border-0 custom-card">
                    
                    <!-- Avatar Context Area -->
                    <div class="text-center mb-2">
                        <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle shadow-sm" style="width: 55px; height: 55px; color: #6f42c1;">
                            <i class="fa-solid fa-user-gear fs-4"></i>
                        </div>
                        <h6 class="fw-bold text-dark mt-2 mb-1">My Account Profile</h6>
                    </div>

                    <!-- Clean Profile Data List -->
                    <div class="px-1 mb-3">
                        <div class="profile-row">
                            <span class="profile-label"><i class="fa-solid fa-id-card me-2 opacity-50"></i>Full Name</span>
                            <span class="profile-value"><?php echo sanitize($user['name']); ?></span>
                        </div>
                        <div class="profile-row">
                            <span class="profile-label"><i class="fa-solid fa-envelope me-2 opacity-50"></i>Email Address</span>
                            <span class="profile-value text-muted font-monospace small"><?php echo sanitize($user['email']); ?></span>
                        </div>
                        <div class="profile-row">
                            <span class="profile-label"><i class="fa-solid fa-phone me-2 opacity-50"></i>Phone Number</span>
                            <span class="profile-value"><?php echo sanitize($user['phone']) ?: '<em class="text-muted fw-normal small">Not Provided</em>'; ?></span>
                        </div>
                        <div class="profile-row">
                            <span class="profile-label"><i class="fa-solid fa-venus-mars me-2 opacity-50"></i>Gender Category</span>
                            <span class="profile-value"><?php echo sanitize($user['gender']) ?: '<em class="text-muted fw-normal small">Not Provided</em>'; ?></span>
                        </div>
                        <div class="profile-row">
                            <span class="profile-label"><i class="fa-solid fa-calendar-day me-2 opacity-50"></i>Joined System</span>
                            <span class="profile-value font-monospace text-muted small"><?php echo date("Y-m-d", strtotime($user['created_at'])); ?></span>
                        </div>
                    </div>
                    
                    <!-- Interactive System Actions -->
                    <div class="d-flex gap-2">
                        <a href="edit_profile.php" class="btn btn-outline-warning btn-sm py-2 fw-semibold w-50 rounded-3">
                            <i class="fa-solid fa-user-pen me-1"></i>Edit Settings
                        </a>
                        <a href="delete_profile.php" class="btn btn-outline-danger btn-sm py-2 fw-semibold w-50 rounded-3" 
                           onclick="return confirm('WARNING: Are you absolutely certain you want to wipe your profile data permanently? This action cannot be reversed.');">
                            <i class="fa-solid fa-trash-can me-1"></i>Delete Account
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>