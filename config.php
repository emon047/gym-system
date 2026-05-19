<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'gym_management');

define('BASE_URL', 'http://localhost/gymkhana/');

if (!function_exists('mysqli_connect')) {
    die("ERROR: The PHP mysqli extension is not enabled. Please enable mysqli in php.ini and restart your server.");
}

$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
if ($conn === false) {
    die("ERROR: Could not connect to database. " . mysqli_connect_error());
}

function sanitize($data)
{
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}
