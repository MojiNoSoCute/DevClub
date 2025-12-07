<?php
session_start();
require_once 'configs/connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(":username", $username);
    $stmt->execute();
    $user = $stmt->fetch();

    if ($username != $user['username']) {
        $_SESSION['error'] = "Invalid username or password.";
        header("Location: index.php");
        exit;
    }

    if ($password != $user['password']) {
        $_SESSION['error'] = "Invalid username or password.";
        header("Location: index.php");
        exit;
    }

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];

    header("Location: backend/member.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DevClub</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css">
</head>

<body>

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow p-4" style="width: 380px;">

        <h3 class="text-center mb-3">
            <i class="bi bi-box-arrow-in-right"></i> Login
        </h3>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger text-center">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                    <input type="text" class="form-control" name="username" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                    <input type="password" class="form-control" name="password" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-box-arrow-in-right"></i> Login
            </button>
        </form>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
