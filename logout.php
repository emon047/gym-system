<?php
require_once 'config.php';

// Unset global state tracking matrices and wipe runtime sessions
$_SESSION = array();
session_destroy();

// Redirect back cleanly to index page
header("Location: login.php");
exit();
?>