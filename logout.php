<?php
// Start session
session_start();

require_once 'includes/auth.php';

// If logout() function exists in auth.php, call it
if (function_exists('logout')) {
    logout();
} else {
    // If logout() not defined, destroy session manually
    $_SESSION = [];
    session_destroy();
}

// Redirect to login page after logout
header('Location: login.php');
exit;
