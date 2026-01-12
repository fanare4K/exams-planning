<?php
session_start();
require_once __DIR__ . '/../config/database.php';

/**
 * Ensure user is logged in. Redirect to login page if not.
 */
function requireLogin()
{
    if (!isset($_SESSION['user'])) {
        header('Location: /exams-planning/login.php');
        exit;
    }
}

/**
 * Get current logged-in user
 */
function getUser()
{
    return $_SESSION['user'] ?? null;
}

/**
 * Login function for admin, professors, and students
 */
function login($username, $password)
{
    global $pdo;

    // --- Admin login (temporary) ---
    // You can later create an 'admin' table instead of hardcoding
    if ($username === 'admin' && $password === 'admin') {
        $_SESSION['user'] = [
            'id' => 0,
            'username' => 'admin',
            'name' => 'Administrator',
            'role' => 'admin'
        ];
        session_regenerate_id(true);
        return true;
    }

    // --- Professor login ---
    $stmt = $pdo->prepare("SELECT * FROM professeurs WHERE nom = ?");
    $stmt->execute([$username]);
    $prof = $stmt->fetch();

    if ($prof && isset($prof['password']) && password_verify($password, $prof['password'])) {
        $_SESSION['user'] = [
            'id' => $prof['id'],
            'name' => $prof['nom'] . ' ' . $prof['prenom'],
            'role' => 'prof'
        ];
        session_regenerate_id(true);
        return true;
    }

    // --- Student login ---
    $stmt = $pdo->prepare("SELECT * FROM etudiants WHERE nom = ?");
    $stmt->execute([$username]);
    $student = $stmt->fetch();

    if ($student && isset($student['password']) && password_verify($password, $student['password'])) {
        $_SESSION['user'] = [
            'id' => $student['id'],
            'name' => $student['nom'] . ' ' . $student['prenom'],
            'role' => 'student'
        ];
        session_regenerate_id(true);
        return true;
    }

    return false; // login failed
}

/**
 * Logout function
 */
function logout()
{
    $_SESSION = [];
    session_destroy();
    header('Location: /exams-planning/login.php');
    exit;
}

/**
 * Role checking helpers
 */
function isAdmin()
{
    return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
}

function isProf()
{
    return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'prof';
}

function isStudent()
{
    return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'student';
}

/**
 * Utility function to create a hashed password (for setup or user creation)
 */
function hashPassword($password)
{
    return password_hash($password, PASSWORD_DEFAULT);
}
