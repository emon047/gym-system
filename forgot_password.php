<?php
require_once 'config.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = sanitize($_POST['email']);

    if (!empty($email)) {
        $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) == 1) {
            // Setup access recovery tokens with localized validity windows
            $token = bin2hex(random_bytes(32));
            $expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));

            $insert_stmt = mysqli_prepare($conn, "INSERT INTO password_resets (email, token, expiry) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($insert_stmt, "sss", $email, $token, $expiry);
            mysqli_stmt_execute($insert_stmt);
            mysqli_stmt_close($insert_stmt);

            // Construct secure transactional target endpoints URI structures
            $reset_link = BASE_URL . "reset_password.php?token=" . $token;
            $subject = "Reset Your Account Password";
            $body = "<h3>Password Reset Request</h3><p>Click the link below to change your account access keys within 1 hour:</p><br><a href='$reset_link'>$reset_link</a>";

            sendSystemEmail($email, $subject, $body);
        }
        
        // Output ambiguous security text wrapper logic context purposefully
        $message = "<div class='alert alert-info'>If the email address matches an active account, reset instructions have been sent.</div>";
        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password Access Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <main class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow p-4">
                    <h4 class="text-center mb-3">Recover Credentials</h4>
                    <p class="text-muted small text-center">Input your target registered registration email below to receive authentication lifecycle reset routing updates.</p>
                    
                    <?php echo $message; ?>

                    <form action="forgot_password.php" method="POST" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Send Recovery Email</button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>