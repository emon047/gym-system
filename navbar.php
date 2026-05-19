<?php require_once 'config.php';
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">🏋️ GymSystem</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item"><a class="nav-link <?php echo ($currentPage === 'index') ? 'active' : ''; ?>" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link <?php echo ($currentPage === 'bmi') ? 'active' : ''; ?>" href="bmi.php">BMI Calculator</a></li>
                
                <?php if(isset($_SESSION['user_id'])): ?>
                    <li class="nav-item"><a class="nav-link <?php echo ($currentPage === 'dashboard') ? 'active' : ''; ?>" href="dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link <?php echo ($currentPage === 'membership') ? 'active' : ''; ?>" href="membership.php">Plans</a></li>
                    <li class="nav-item"><a class="nav-link <?php echo ($currentPage === 'attendance') ? 'active' : ''; ?>" href="attendance.php">Attendance</a></li>
                    <li class="nav-item"><a class="nav-link <?php echo ($currentPage === 'activity_log') ? 'active' : ''; ?>" href="activity_log.php">Logs</a></li>
                    <li class="nav-item"><a class="nav-link <?php echo ($currentPage === 'profile') ? 'active' : ''; ?>" href="profile.php">Profile</a></li>
                    <li class="nav-item">
                        <a class="btn btn-sm btn-danger ms-2" href="logout.php">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link <?php echo ($currentPage === 'login') ? 'active' : ''; ?>" href="login.php">Login</a></li>
                    <li class="nav-item"><a class="btn btn-sm btn-primary ms-2" href="register.php">Register</a></li>
                <?php endif; ?>
                
            
            </ul>
        </div>
    </div>
</nav>