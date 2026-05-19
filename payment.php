<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$plan_id = filter_input(INPUT_GET, 'plan_id', FILTER_VALIDATE_INT);
$error = '';
$success = '';

if (!$plan_id) {
    header("Location: membership.php");
    exit();
}

$stmt = mysqli_prepare($conn, "SELECT plan_name, price FROM membership_plans WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $plan_id);
mysqli_stmt_execute($stmt);
$plan = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $card_num = $_POST['card_number'];

    if (strlen($card_num) < 16) {
        $error = "transaction rejected. Card details are invalid.";
    } else {
        // Construct dummy mock success reference sequence token
        $tx_id = "TXN-" ;
        $status = "Success";
        $amount = $plan['price'];

        $stmt = mysqli_prepare($conn, "INSERT INTO payments (user_id, plan_id, amount, payment_status, transaction_id) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "iidss", $user_id, $plan_id, $amount, $status, $tx_id);

        if (mysqli_stmt_execute($stmt)) {
            $success = "Transaction simulation successful! Your Plan is activated. Reference ID: " . $tx_id;
        } else {
            $error = "Failed to record payment in system history logs.";
        }
        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Sandbox Payment Gateway</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php include 'navbar.php'; ?>

    <main class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow p-4">
                    <h3 class="mb-3 text-center">Secure Payment Sandbox</h3>
                    <p class="text-center text-muted">Plan Choice:
                        <strong><?php echo sanitize($plan['plan_name']); ?></strong>
                        ($<?php echo sanitize($plan['price']); ?>)</p>
                    <hr>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                        <a href="dashboard.php" class="btn btn-primary d-block mt-2">Return to Dashboard</a>
                    <?php else: ?>

                        <form action="payment.php?plan_id=<?php echo $plan_id; ?>" method="POST" class="needs-validation"
                            novalidate>
                            <div class="mb-3">
                                <label class="form-label">Dummy Card Number</label>
                                <input type="text" name="card_number" class="form-control" placeholder="1234 5678 1234 5678"
                                    minlength="16" maxlength="16" required>
                                <small class="text-muted text-info">Enter any 16-digit dummy value to simulate
                                    processing.</small>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" class="form-control" placeholder="example@gmail.com" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Security Code</label>
                                    <input type="password" class="form-control" placeholder="999" maxlength="3" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success w-100 py-2">Process Simulated Payment</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>