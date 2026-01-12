<?php
require_once 'includes/auth.php';

$error = '';

// Process login form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = trim($_POST['id'] ?? '');
    $nom = trim($_POST['nom'] ?? '');

    if ($id === '' || $nom === '') {
        $error = "Please fill in all fields.";
    } elseif (login($id, $nom)) { // login() uses id + nom
        header('Location: index.php');
        exit;
    } else {
        $error = "Invalid ID or Name.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login - Exams Planning</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body {
            background: #f8f9fa;
        }

        .card {
            border-radius: 1rem;
        }

        .form-control:focus {
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        .btn-primary {
            background: linear-gradient(135deg, #0d6efd, #3d8bfd);
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #0b5ed7, #2c7be5);
        }
    </style>
</head>

<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4 shadow-sm" style="width: 350px;">
        <h3 class="text-center mb-3">Login</h3>

        <!-- Error message -->
        <?php if ($error): ?>
            <div class="alert alert-danger text-center">
                <i class="bi bi-exclamation-circle me-2"></i>
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <!-- Login Form -->
        <form method="POST" novalidate>
            <div class="mb-3">
                <label for="id" class="form-label">ID</label>
                <input type="text" name="id" id="id" class="form-control" placeholder="Enter your ID" required>
            </div>

            <div class="mb-3">
                <label for="nom" class="form-label">Nom</label>
                <input type="text" name="nom" id="nom" class="form-control" placeholder="Enter your Name" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-box-arrow-in-right me-1"></i> Login
            </button>
        </form>
    </div>
</body>

</html>