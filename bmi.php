<?php
require_once 'config.php';

$bmi = '';
$category = '';
$message = '';
$alertClass = 'alert-info'; // Default fallback theme

// Retain form inputs for better user experience after page reload
$weightInput = '';
$heightInput = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $weightInput = sanitize($_POST['weight']);
    $heightInput = sanitize($_POST['height']);
    
    // Convert string inputs to floating point numbers
    $weight = floatval($weightInput);
    $height = floatval($heightInput) / 100; // Convert centimeters to meters

    if ($weight > 0 && $height > 0) {
        $bmi = round(($weight / ($height * $height)), 2);

        //determining health category 
        if ($bmi < 18.5) {
            $category = "Underweight";
            $message = "Consider building mass with a customized nutrition strategy.";
            $alertClass = "alert-warning";
        } elseif ($bmi < 24.9) {
            $category = "Normal Weight";
            $message = "Excellent baseline health index metrics! Maintain your training structure.";
            $alertClass = "alert-success";
        } elseif ($bmi < 29.9) {
            $category = "Overweight";
            $message = "Incorporate focused metabolic conditioning or functional training models.";
            $alertClass = "alert-warning";
        } else {
            $category = "Obese";
            $message = "Consult structural training teams to establish cardiorespiratory targets safely.";
            $alertClass = "alert-danger";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BMI Metric Calculator Tool</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
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

        .form-label {
            font-size: 0.88rem;
            margin-bottom: 0.25rem;
        }

        .input-group-text {
            background-color: #f8f9fa;
            border-color: #ced4da;
            color: #6c757d;
        }
    </style>
</head>
<body class="bg-light">
    <?php include 'navbar.php'; ?>

    <main class="container">
        <div class="text-center mb-3">
            <span class="badge bg-primary px-3 py-1.5 rounded-pill mb-2 fw-semibold tracking-wide" style="font-size: 0.75rem;">HEALTH LOGISTICS</span>
            <h3 class="fw-bold text-dark mb-1">Body Mass Index Calculator</h3>
            <p class="text-muted small mb-2">Track your health metrics instantly using simple server-side calculation layouts.</p>
            <div class="mx-auto bg-primary rounded" style="width: 40px; height: 3px;"></div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5 col-xl-4">
                <div class="card p-3 shadow-sm border-0 custom-card">
                    <div class="card-body p-2">
                        
                        <!-- Pure PHP Form Submission -->
                        <form method="POST" action="bmi.php" class="needs-validation" novalidate id="bmiForm">
                            <div class="mb-2.5">
                                <label class="form-label fw-bold text-secondary">Weight (Kilograms) *</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text"><i class="fa-solid fa-weight-scale"></i></span>
                                    <input type="number" step="0.1" name="weight" class="form-control form-control-sm" value="<?php echo $weightInput; ?>" placeholder="e.g. 70" required>
                                    <div class="invalid-feedback">Please enter a valid weight in kg.</div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold text-secondary">Height (Centimeters) *</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text"><i class="fa-solid fa-ruler-vertical"></i></span>
                                    <input type="number" step="0.1" name="height" class="form-control form-control-sm" value="<?php echo $heightInput; ?>" placeholder="e.g. 175" required>
                                    <div class="invalid-feedback">Please enter a valid height in cm.</div>
                                </div>
                            </div>
                            
                            <!-- Single standard submission button -->
                            <button type="submit" class="btn btn-primary btn-sm w-100 py-2 fw-semibold rounded-3">
                                <i class="fa-solid fa-calculator me-1.5"></i>Calculate BMI
                            </button>
                        </form>

                        <!-- Dynamic PHP Result Box Display -->
                        <?php if($bmi !== ''): ?>
                            <div class="alert <?php echo $alertClass; ?> mt-3 p-2.5 border-0 shadow-sm rounded-3 mb-0">
                                <h6 class="alert-heading fw-bold mb-2 small">
                                    <i class="fa-solid fa-square-poll-vertical me-1.5"></i>Calculated Profile Results:
                                </h6>
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="text-secondary fw-semibold small">Your BMI Score:</span>
                                    <span class="fw-extrabold text-dark" style="font-size: 1.15rem;"><?php echo $bmi; ?></span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-secondary fw-semibold small">Classification:</span>
                                    <span class="badge <?php 
                                        echo ($category === 'Normal Weight') ? 'bg-success' : (($category === 'Obese') ? 'bg-danger' : 'bg-warning text-dark'); 
                                    ?> px-2.5 py-1 rounded-pill fw-bold" style="font-size: 0.72rem;"><?php echo $category; ?></span>
                                </div>
                                <p class="mb-0 text-dark opacity-85" style="font-size: 0.78rem; line-height: 1.2;"><i class="fa-solid fa-circle-info me-1 text-primary"></i> <?php echo $message; ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Back Link Utility Footer -->
                    <div class="text-center pt-2 mt-2 border-top">
                        <a href="dashboard.php" class="text-decoration-none text-muted small fw-semibold">
                            <i class="fa-solid fa-arrow-left me-1"></i> Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>