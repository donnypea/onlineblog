<?php
session_start();
include '../includes/db.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Handle password update
$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    $admin_id = $_SESSION['user_id'];

    // Fetch current password from the database
    $query = "SELECT password FROM users WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $admin_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $admin = mysqli_fetch_assoc($result);

    if ($admin && $current_password == $admin['password']) { // No hashing as per your preference
        if ($new_password == $confirm_password) {
            // Update password
            $update_query = "UPDATE users SET password = ? WHERE user_id = ?";
            $update_stmt = mysqli_prepare($conn, $update_query);
            mysqli_stmt_bind_param($update_stmt, "si", $new_password, $admin_id);
            if (mysqli_stmt_execute($update_stmt)) {
                $message = "<div class='alert alert-success'>Password updated successfully!</div>";
            } else {
                $message = "<div class='alert alert-danger'>Error updating password.</div>";
            }
        } else {
            $message = "<div class='alert alert-warning'>New passwords do not match.</div>";
        }
    } else {
        $message = "<div class='alert alert-danger'>Incorrect current password.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Settings</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/admin.css">
    <style>
        .dashboard-card {
            text-align: center;
            padding: 20px;
            border-radius: 10px;
            transition: 0.3s;
            width: 300px;
            height: 150px;
        }
        .dashboard-card h3 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .dashboard-card p {
            font-size: 18px;
            margin: 0;
        }
        .dashboard-card i {
            font-size: 40px;
            margin-bottom: 10px;
        }
        .dashboard-card:hover {
            transform: scale(1.05);
        }
        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            background-color: #3B82F6;
            color: white;
            padding-top: 20px;
            transition: all 0.3s;
        }
        .sidebar a {
            color: white;
            padding: 15px;
            display: block;
            text-decoration: none;
        }
        .sidebar a:hover {
            background-color: #2563EB;
        }
        .sidebar .active {
            background-color: #1E40AF;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
            transition: all 0.3s;
        }
        .navbar {
            background-color: #3B82F6;
        }
        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                overflow: hidden;
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
<div class="sidebar">
    <h4 class="text-center">Admin Panel</h4>
    <hr>
    <a href="admin_dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="manage_users.php"><i class="bi bi-people"></i> User Management</a>
    <a href="manage_posts.php"><i class="bi bi-file-earmark-text"></i> Post Management</a>
    <a href="admin_settings.php" class="active"><i class="bi bi-gear"></i> Settings</a>
    <a href="logout.php" class="text-danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>
<div class="main-content">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand text-white" href="#">Admin Settings</a>
            <div class="ms-auto text-white">
                <i class="bi bi-person-circle"></i> <?= $_SESSION['username']; ?> (Admin)
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Change Password</h2>

        <?= $message; ?>

        <form method="POST" class="mt-3">
            <div class="mb-3">
                <label class="form-label">Current Password:</label>
                <input type="password" name="current_password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">New Password:</label>
                <input type="password" name="new_password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Confirm New Password:</label>
                <input type="password" name="confirm_password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Password</button>
        </form>
    </div>
</div>
</body>
</html>
