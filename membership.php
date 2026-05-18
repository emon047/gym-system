<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Extract tier matrix records structures directly
$query = "SELECT id, plan_name, price, duration_months FROM membership_plans";
$plans = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membership Tiers</title>
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

        .plan-card {
            border: 1px solid #e0e0e0 !important;
            border-top: 4px solid #0d6efd !important;
            border-radius: 12px !important;
            background-color: #ffffff;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .plan-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 0.4rem 1rem rgba(0, 0, 0, 0.06) !important;
        }
        
        .feature-list li {
            font-size: 0.88rem;
            color: #495057;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        .price-display {
            color: #212529;
            font-weight: 700;
            font-size: 2rem;
        }
    </style>
</head>
<body class="bg-light">
    <?php include 'navbar.php'; ?>

    <main class="container">
        <!-- Compact Section Header -->
        <div class="text-center mb-4">
            <h3 class="fw-bold text-dark mb-1">Select a Membership Plan</h3>
            <p class="text-muted small mb-2">Choose the ideal tier tailored for your lifestyle and fitness objectives.</p>
            <div class="mx-auto bg-primary rounded" style="width: 40px; height: 3px;"></div>
        </div>

        <!-- Single Screen Structured Pricing Grid -->
        <div class="row row-cols-1 row-cols-md-3 g-3 justify-content-center align-items-stretch">
            <?php while($plan = mysqli_fetch_assoc($plans)): ?>
                <div class="col" style="max-width: 360px;">
                    <div class="card h-100 shadow-sm border-0 plan-card p-2">
                        <div class="card-body d-flex flex-column text-center p-3">
                            
                            <!-- Plan Name -->
                            <h5 class="fw-bold text-dark mb-1"><?php echo sanitize($plan['plan_name']); ?></h5>
                            
                            <!-- Compact Pricing Display -->
                            <div class="my-2">
                                <h2 class="price-display mb-0">
                                    $<?php echo number_format(sanitize($plan['price']), 2); ?>
                                </h2>
                                <small class="text-muted small tracking-wider fw-semibold text-uppercase" style="font-size: 0.7rem;">Full Term Access</small>
                            </div>
                            
                            <hr class="my-2 opacity-10">

                            <!-- Feature List -->
                            <ul class="list-unstyled feature-list my-2 flex-grow-1">
                                <li>
                                    <i class="fa-solid fa-clock text-primary"></i>
                                    <span>Duration: <strong><?php echo sanitize($plan['duration_months']); ?> Mos</strong></span>
                                </li>
                                <li>
                                    <i class="fa-solid fa-dumbbell text-muted"></i>
                                    <span>Full weight-lifting access</span>
                                </li>
                                <li>
                                    <i class="fa-solid fa-shower text-muted"></i>
                                    <span>Locker room & showers</span>
                                </li>
                                <li>
                                    <i class="fa-solid fa-circle-check text-muted"></i>
                                    <span>Free intro orientation</span>
                                </li>
                            </ul>
                            
                            <!-- Action Button -->
                            <a href="payment.php?plan_id=<?php echo $plan['id']; ?>" class="w-100 btn btn-primary py-2 btn-sm fw-semibold rounded-3 mt-auto">
                                Purchase Tier <i class="fa-solid fa-arrow-right small ms-1"></i>
                            </a>
                            
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>