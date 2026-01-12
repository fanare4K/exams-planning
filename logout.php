<?php
// Start session (ensures $_SESSION is available)
session_start();

require_once 'includes/auth.php';

// Call logout function to destroy session and redirect
logout();
