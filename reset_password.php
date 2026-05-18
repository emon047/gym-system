<?php
require_once 'config.php';

$error = '';
$success = '';
$token = filter_input(INPUT_GET, 'token', FILTER_DEFAULT);

if (empty($token) && isset($_POST['token'])) {
    $token = $_POST['token'];
}

if (empty($token)) {
    die("Error: Request token is missing.");
}

// Verify dynamic verification timeline conditions matching parameters
$stmt = mysqli_prepare($conn, "SELECT email, expiry FROM password_resets WHERE token = ? ORDER BY id DESC LIMIT 1");
mysqli_stmt_bind_param($stmt, "s", $token);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$reset_request = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$reset_request || strtotime($reset_request['expiry']) < time()) {
    $error = "Token signature is invalid or has expired.";
} else {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        $email = $reset_request['email'];

        if (strlen($password) < 6) {
            $error = "Password structural depth metrics must hit at least 6 characters.";
        } elseif ($password !== $confirm_password) {
            $error = "Password matching constraints validation mismatch.";
        } else {
            $new_hash = password_hash($password, PASSWORD_BCRYPT);
            
            // Rewrite configuration matrix profiles
            $update_stmt = mysqli_prepare($conn, "UPDATE users SET password = ? WHERE email = ?");
            mysqli_stmt_bind_param($update_stmt, "ss", $new_hash, $email);
            
            if (mysqli_stmt_execute($update_stmt)) {
                // Drop used single use tokens cleanly out of relational table structures
                $delete_stmt = mysqli_prepare($conn, "DELETE FROM password_resets WHERE email = ?");
                mysqli_stmt_bind_param($delete_stmt, "s", $email);
                mysqli_stmt_execute($delete_stmt);

                $success = "Password updated successfully! You can now log in.";
            } else {
                $error = "Database structural change update command failure occurred.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set New Access Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <main class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow p-4">
                    <h4 class="mb-3 text-center">Reset Account Password</h4>

                    <?php if(!empty($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    <?php if(!empty($success)): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                        <a href="login.php" class="btn btn-success d-block text-center mt-2">Go to Login Page</a>
                    <?php endif; ?>

                    <?php if(empty($success) && empty($error)): ?>
                    <form action="reset_password.php" method="POST" class="needs-validation" novalidate>
                        <input type="hidden" name="token" value="<?php echo sanitize($token); ?>">
                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" name="password" class="form-control" minlength="6" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-warning w-100">Update Secure Access Keys</button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>