<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = sanitize($_POST['name']);
    $phone = sanitize($_POST['phone']);
    $gender = sanitize($_POST['gender']);

    if (empty($name)) {
        $error = "Name cannot be left empty.";
    } else {
        $stmt = mysqli_prepare($conn, "UPDATE users SET name = ?, phone = ?, gender = ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "sssi", $name, $phone, $gender, $user_id);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['user_name'] = $name; // Update active session context data
            $success = "Profile records updated successfully!";
        } else {
            $error = "Failed to rewrite records in system database storage.";
        }
        mysqli_stmt_close($stmt);
    }
}

// Fetch up to date layout markers
$stmt = mysqli_prepare($conn, "SELECT name, phone, gender FROM users WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile settings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <main class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow p-4">
                    <h3 class="mb-4">Modify Details</h3>
                    
                    <?php if(!empty($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    <?php if(!empty($success)): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php endif; ?>

                    <form action="edit_profile.php" method="POST" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" value="<?php echo sanitize($user['name']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control" value="<?php echo sanitize($user['phone']); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Gender</label>
                            <select name="gender" class="form-select">
                                <option value="Male" <?php if($user['gender'] == 'Male') echo 'selected'; ?>>Male</option>
                                <option value="Female" <?php if($user['gender'] == 'Female') echo 'selected'; ?>>Female</option>
                                <option value="Other" <?php if($user['gender'] == 'Other') echo 'selected'; ?>>Other</option>
                            </select>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">Save Profile Info</button>
                            <a href="profile.php" class="btn btn-secondary">Back to Profile</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>