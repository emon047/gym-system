<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = mysqli_prepare($conn, "DELETE FROM users WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $user_id);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    $_SESSION = array();
    session_destroy();
    
    echo "<script>alert('Your profile account records have been dropped permanently.'); window.location.href='index.php';</script>";
    exit();
} else {
    echo "<script>alert('Wipe action execution block failed.'); window.location.href='profile.php';</script>";
}
?>