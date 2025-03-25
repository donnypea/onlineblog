<?php
include 'includes/db.php';
session_start();

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $message = "<div class='alert alert-danger'>All fields are required!</div>";
    } else {
        $query = "SELECT * FROM users WHERE email='$email'";
        $result = $conn->query($query);

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();

            // Check if the user is deleted
            if ($row['is_delete'] == 1) {
                $message = "<div class='alert alert-danger'>This account has been deleted and cannot be accessed.</div>";
            } elseif (password_verify($password, $row['password'])) {
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['role'] = $row['role']; // Store the role in session

                // Redirect based on role
                if ($row['role'] == 'admin') {
                    header("Location: admin/admin_dashboard.php");
                } else {
                    header("Location: index.php");
                }
                exit();
            } else {
                $message = "<div class='alert alert-danger'>Invalid credentials!</div>";
            }
        } else {
            $message = "<div class='alert alert-danger'>User not found!</div>";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card p-4 shadow-lg" style="width: 400px;">
        <h3 class="text-center">Login</h3>
        <?= $message; ?>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" name="password" id="password_login" class="form-control" required>
                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_login')">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
            <p class="text-center mt-3">
                Don't have an account? <a href="register.php">Register here</a>
            </p>
        </form>
    </div>
</div>
<script src="js/script.js"></script>
</body>
</html>
