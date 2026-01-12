<?php
session_start();
require_once __DIR__ . '/../config/database.php';

/**
 * Ensure the user is logged in.
 * Redirects to login page if not.
 */
function requireLogin(): void
{
    if (!isset($_SESSION['user'])) {
        header('Location: /exams-planning/login.php');
        exit;
    }
}

/**
 * Login function
 * @param string $id User ID
 * @param string|null $name User name (for students/professors)
 * @return bool True if login successful
 */
function login(string $id, ?string $name = null): bool
{
    global $pdo;

    // --- Admin login ---
    if ($id === 'admin' && $name === 'admin') {
        $_SESSION['user'] = [
            'username' => 'admin',
            'role' => 'admin',
            'name' => 'Administrator'
        ];
        return true;
    }

    // --- Professor login ---
    $stmt = $pdo->prepare("SELECT * FROM professeurs WHERE id = ? AND nom = ?");
    $stmt->execute([$id, $name]);
    $prof = $stmt->fetch();
    if ($prof) {
        $_SESSION['user'] = [
            'id' => $prof['id'],
            'name' => $prof['nom'] . ' ' . $prof['prenom'],
            'role' => 'prof'
        ];
        return true;
    }

    // --- Student login ---
    $stmt = $pdo->prepare("SELECT * FROM etudiants WHERE id = ? AND nom = ?");
    $stmt->execute([$id, $name]);
    $student = $stmt->fetch();
    if ($student) {
        $_SESSION['user'] = [
            'id' => $student['id'],
            'name' => $student['nom'] . ' ' . $student['prenom'],
            'role' => 'student'
        ];
        return true;
    }

    return false;
}

/**
 * Logout user
 */
function logout(): void
{
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }
    session_destroy();
    header('Location: /exams-planning/login.php');
    exit;
}

/**
 * Check user roles
 */
function isAdmin(): bool
{
    return isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin';
}

function isProf(): bool
{
    return isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'prof';
}

function isStudent(): bool
{
    return isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'student';
}
