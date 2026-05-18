<?php
require_once 'config.php';

$message = '';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Query tracking metrics validation signatures
    $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE verification_token = ? AND is_verified = 0");
    mysqli_stmt_bind_param($stmt, "s", $token);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) == 1) {
        mysqli_stmt_bind_result($stmt, $user_id);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        // Turn verification state transitions flag indicator updates positive
        $update_stmt = mysqli_prepare($conn, "UPDATE users SET is_verified = 1, verification_token = NULL WHERE id = ?");
        mysqli_stmt_bind_param($update_stmt, "i", $user_id);
        
        if (mysqli_stmt_execute($update_stmt)) {
            $message = "<div class='alert alert-success'><h4>Verification complete!</h4>Your email trace profiles stand valid. You can now log into your account dashboard.</div>";
        } else {
            $message = "<div class='alert alert-danger'>Failed updating state records.</div>";
        }
        mysqli_stmt_close($update_stmt);
    } else {
        mysqli_stmt_close($stmt);
        $message = "<div class='alert alert-warning'><h4>Link Invalid or Expired</h4>This system token entry path signature is missing from storage pipelines.</div>";
    }
} else {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification Handling</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <main class="container my-5 text-center">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow p-5">
                    <?php echo $message; ?>
                    <a href="login.php" class="btn btn-primary mt-3">Proceed to Login</a>
                </div>
            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>