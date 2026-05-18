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

    // Validate inputs are greater than zero before performing division
    if ($weight > 0 && $height > 0) {
        // Core BMI Formula: weight (kg) / height^2 (m)
        $bmi = round(($weight / ($height * $height)), 2);

        // Determine health category based on standard BMI thresholds
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
</head>
<body>
    <?php include 'navbar.php'; ?>

    <main class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow border-0 rounded-3 overflow-hidden">
                    
                    <!-- Elegant Header Area -->
                    <div class="bg-dark text-white text-center py-4">
                        <h3 class="mb-1"><i class="fa-solid fa-heart-pulse text-primary me-2"></i>BMI Calculator</h3>
                        <p class="text-muted small mb-0 px-3">Track your health metrics instantly using simple server-side calculation.</p>
                    </div>

                    <div class="card-body p-4">
                        <!-- Pure PHP Form Submission -->
                        <form method="POST" action="bmi.php" class="needs-validation" novalidate id="bmiForm">
                            <div class="mb-3">
                                <label class="form-label fw-semibold text-secondary">Weight (Kilograms) *</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-weight-scale"></i></span>
                                    <input type="number" step="0.1" name="weight" class="form-control" value="<?php echo $weightInput; ?>" placeholder="e.g. 70" required>
                                    <div class="invalid-feedback">Please enter a valid weight in kg.</div>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label fw-semibold text-secondary">Height (Centimeters) *</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-ruler-vertical"></i></span>
                                    <input type="number" step="0.1" name="height" class="form-control" value="<?php echo $heightInput; ?>" placeholder="e.g. 175" required>
                                    <div class="invalid-feedback">Please enter a valid height in cm.</div>
                                </div>
                            </div>
                            
                            <!-- Single standard submission button -->
                            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">
                                <i class="fa-solid fa-calculator me-2"></i>Calculate BMI
                            </button>
                        </form>

                        <!-- Dynamic PHP Result Box Display -->
                        <?php if($bmi !== ''): ?>
                            <div class="alert <?php echo $alertClass; ?> mt-4 border-0 shadow-sm animate-fade-in">
                                <h5 class="alert-heading fw-bold mb-3">
                                    <i class="fa-solid fa-square-poll-vertical me-2"></i>Your Results:
                                </h5>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-secondary fw-semibold">Your BMI Score:</span>
                                    <span class="fs-4 fw-extrabold text-dark"><?php echo $bmi; ?></span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="text-secondary fw-semibold">Classification:</span>
                                    <span class="badge <?php 
                                        echo ($category === 'Normal Weight') ? 'bg-success' : (($category === 'Obese') ? 'bg-danger' : 'bg-warning text-dark'); 
                                    ?> px-3 py-2 rounded-pill fw-bold fs-7"><?php echo $category; ?></span>
                                </div>
                                <hr class="my-2 opacity-10">
                                <p class="mb-0 text-dark small"><i class="fa-solid fa-circle-info me-1 text-primary"></i> <?php echo $message; ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Back Link Utility Footer -->
                    <div class="card-footer bg-light text-center py-3 border-0">
                        <a href="dashboard.php" class="text-decoration-none text-muted small fw-semibold">
                            <i class="fa-solid fa-arrow-left me-1"></i> Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>