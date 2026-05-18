<?php
// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Database Connection Settings
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'gym_management');

if (!function_exists('mysqli_connect')) {
    die("ERROR: The PHP mysqli extension is not enabled. Please enable mysqli in your php.ini and restart your server.");
}

// Attempt connection to MySQL database
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if($conn === false){
    die("ERROR: Could not connect to database. " . mysqli_connect_error());
}

// Base URL configuration (Adjust to match your local setup folder name)
define('BASE_URL', 'http://localhost/gymkhana/');

// Safe output utility function (Prevents XSS attacks)
function sanitize($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// Auto load PHPMailer classes only if the library files exist
$phPMailerBase = __DIR__ . '/PHPMailer/src/';
$phPMailerFiles = [
    'Exception' => __DIR__ . '/Exception.php',
    'PHPMailer' => __DIR__ . '/PHPMailer.php',
    'SMTP' => __DIR__ . '/SMTP.php',
];

if (file_exists($phPMailerBase . 'PHPMailer.php')) {
    $phPMailerFiles = [
        'Exception' => $phPMailerBase . 'Exception.php',
        'PHPMailer' => $phPMailerBase . 'PHPMailer.php',
        'SMTP' => $phPMailerBase . 'SMTP.php',
    ];
}

$PHPMailerAvailable = true;
foreach ($phPMailerFiles as $file) {
    if (!file_exists($file)) {
        $PHPMailerAvailable = false;
        break;
    }
}

if ($PHPMailerAvailable) {
    require_once $phPMailerFiles['Exception'];
    require_once $phPMailerFiles['PHPMailer'];
    require_once $phPMailerFiles['SMTP'];
}

// FIXED: Removed the top-level 'use' statements because they break compile-time if files don't exist.
// Instead, we call the classes using their fully qualified names directly inside the function below.

// Helper Function to send system emails
function sendSystemEmail($toEmail, $subject, $body) {
    if (!class_exists('PHPMailer\\PHPMailer\\PHPMailer')) {
        return false;
    }

    // FIXED: Instantiate using the absolute namespace path to avoid fatal exceptions
    $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
    try {
        // SMTP Server Configurations
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; // Adjust configuration to match target provider
        $mail->SMTPAuth   = true;
        $mail->Username   = 'your_gmail_username@gmail.com'; // Use your real SMTP/Gmail address
        $mail->Password   = 'your_app_password_here';       // Use your real app-specific password
        $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('your_gmail_username@gmail.com', 'Gym Management System');
        $mail->addAddress($toEmail);

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
        return true;
    } catch (\PHPMailer\PHPMailer\Exception $e) { // FIXED: Caught namespace-specific exception safely
        return false;
    }
}
?>