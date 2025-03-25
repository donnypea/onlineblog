<?php
include 'includes/db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Check if fields are empty
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $message = "<div class='alert alert-danger'>All fields are required!</div>";
    } elseif ($password !== $confirm_password) {
        $message = "<div class='alert alert-danger'>Passwords do not match!</div>";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";

        if ($conn->query($query)) {
            $message = "<div class='alert alert-success'>Registration successful! <a href='login.php'>Login here</a></div>";
        } else {
            $message = "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
   <!-- Add these inside <head> -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card p-4 shadow-lg" style="width: 400px;">
        <h3 class="text-center">Register</h3>
        <?= $message; ?>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" name="password" id="password" class="form-control" required>
                   
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" required>
                
            </div>
            <button type="submit" class="btn btn-primary w-100">Register</button>
            <p class="text-center mt-3">Already have an account? <a href="login.php">Login</a></p>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"></script>
<script src="js/script.js"></script>
</body>
</html>
