<?php
require_once 'config.php';

// Redirect to dashboard if session exists
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = sanitize($_POST['name']);
    $email = sanitize($_POST['email']);
    $phone = sanitize($_POST['phone']);
    $gender = sanitize($_POST['gender']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Backend Validation
    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "Please fill in all mandatory fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid format for email address.";
    } elseif ($password !== $confirm_password) {
        $error = "Your passwords do not match.";
    } elseif (strlen($password) < 6) {
        $error = "Password must span at least 6 characters.";
    } else {
        // Confirm user account existence
        $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $error = "An account with this email already exists.";
            mysqli_stmt_close($stmt);
        } else {
            mysqli_stmt_close($stmt);

            // Hash password
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // UPDATED: 'is_verified' is now explicitly assigned 1 for instant system access activation
            $insert_query = "INSERT INTO users (name, email, password, phone, gender, is_verified) VALUES (?, ?, ?, ?, ?, 1)";
            $stmt = mysqli_prepare($conn, $insert_query);
            mysqli_stmt_bind_param($stmt, "sssss", $name, $email, $hashed_password, $phone, $gender);

            if (mysqli_stmt_execute($stmt)) {
                // Get the auto-increment ID of the newly registered user
                $new_user_id = mysqli_insert_id($conn);

                // Auto-create user login session parameters immediately
                $_SESSION['user_id'] = $new_user_id;
                $_SESSION['user_name'] = $name;

                // Log execution activity info
                $ip_address = $_SERVER['REMOTE_ADDR'];
                $log_stmt = mysqli_prepare($conn, "INSERT INTO login_history (user_id, ip_address) VALUES (?, ?)");
                mysqli_stmt_bind_param($log_stmt, "is", $new_user_id, $ip_address);
                mysqli_stmt_execute($log_stmt);
                mysqli_stmt_close($log_stmt);

                // Redirect user to workspace dashboard directly without email blocks
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "An error occurred. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Account</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <main class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow p-4">
                    <h2 class="text-center mb-4">Create Account</h2>
                    
                    <?php if(!empty($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <form action="register.php" method="POST" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label class="form-label">Full Name *</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email Address *</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Gender</label>
                            <select name="gender" class="form-select">
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password *</label>
                            <input type="password" name="password" class="form-control" minlength="6" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirm Password *</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Register</button>
                    </form>
                    <p class="text-center mt-3 mb-0">Already registered? <a href="login.php">Login here</a></p>
                </div>
            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>
</body>
</html>