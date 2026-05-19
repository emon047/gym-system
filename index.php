<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gym Membership Management System</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome for clean iconography -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">

    <style>
        html, body {
            height: 100%;
        }
        body {
            display: flex;
            flex-direction: column;
        }
        .hero-section {
            background: linear-gradient(135deg, rgba(13, 110, 253, 0.06) 0%, rgba(25, 135, 84, 0.04) 100%);
            border-radius: 1rem;
        }
        .feature-icon {
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.75rem;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <main class="container my-auto py-4">
        <div class="row align-items-center g-4 hero-section p-4 p-md-5 shadow-sm border m-1">
            
            <!-- Left Side -->
            <div class="col-lg-6 text-center text-lg-start">
                <span class="badge bg-primary px-3 py-2 rounded-pill mb-3 fw-semibold">FITNESS MANAGEMENT ELITE</span>
                <h1 class="display-5 fw-extrabold text-dark mb-3 lh-sm">
                    Build Your Elite <br class="d-none d-lg-inline">Fitness Journey
                </h1>
                <p class="fs-5 text-muted mb-4 col-lg-11">
                    Track workouts, structure goals, manage subscriptions, map your physical metrics, and maintain attendance records seamlessly.
                </p>
                <div>
                    <?php if(!isset($_SESSION['user_id'])): ?>
                        <a class="btn btn-primary btn-lg px-4 shadow-sm fw-bold" href="register.php" role="button">
                            <i class="fa-solid fa-user-plus me-2"></i>Get Started Today
                        </a>
                        <a class="btn btn-outline-secondary btn-lg px-4 fw-bold" href="login.php" role="button">
                            Sign In
                        </a>
                    <?php else: ?>
                        <a class="btn btn-success btn-lg px-5 shadow-sm fw-bold" href="dashboard.php" role="button">
                            <i class="fa-solid fa-gauge me-2"></i>Go to Dashboard
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Right Side -->
            <div class="col-lg-6">
                <div class="d-flex flex-column gap-3">
                    
                    <!-- Feature 1 -->
                    <div class="card shadow-sm p-3 bg-white rounded-3">
                        <div class="d-flex align-items-center">
                            <div class="feature-icon bg-primary-subtle text-primary me-3 fs-5">
                                <i class="fa-solid fa-dumbbell"></i>
                            </div>
                            <div>
                                <h5 class="mb-1 fw-bold text-dark">Flexible Membership Plans</h5>
                                <p class="mb-0 text-muted small">Choose personalized tiers fitting your active routine and schedules.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Feature 2 -->
                    <div class="card border-0 shadow-sm p-3 bg-white rounded-3">
                        <div class="d-flex align-items-center">
                            <div class="feature-icon bg-success-subtle text-success me-3 fs-5">
                                <i class="fa-solid fa-calculator"></i>
                            </div>
                            <div>
                                <h5 class="mb-1 fw-bold text-dark">Interactive Metric Calculation</h5>
                                <p class="mb-0 text-muted small">Leverage instant calculations for Body Mass Index (BMI) profiles dynamically.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Feature 3 -->
                    <div class="card border-0 shadow-sm p-3 bg-white rounded-3">
                        <div class="d-flex align-items-center">
                            <div class="feature-icon bg-info-subtle text-info me-3 fs-5">
                                <i class="fa-solid fa-clock-rotate-left"></i>
                            </div>
                            <div>
                                <h5 class="mb-1 fw-bold text-dark">Precise Activity Logging</h5>
                                <p class="mb-0 text-muted small">Keep transparent historical data checking daily check-ins and payments securely.</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </main>

    <?php include 'footer.php'; ?>