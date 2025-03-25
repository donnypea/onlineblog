<?php
session_start();
include '../includes/db.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Check if user ID is provided
if (!isset($_GET['id'])) {
    header("Location: manage_users.php");
    exit();
}

$user_id = $_GET['id'];

// Fetch user details
$query = "SELECT username, email, role FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    header("Location: manage_users.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $update_query = "UPDATE users SET username = ?, email = ?, role = ? WHERE user_id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("sssi", $username, $email, $role, $user_id);
    
    if ($update_stmt->execute()) {
        header("Location: manage_users.php");
        exit();
    } else {
        $error = "Failed to update user.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/admin.css">
    <style>
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

<div class="sidebar" id="sidebar">
    <h4 class="text-center">Admin Panel</h4>
    <hr>
    <a href="admin_dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="manage_users.php" class="active"><i class="bi bi-people"></i> User Management</a>
    <a href="manage_posts.php"><i class="bi bi-file-earmark-text"></i> Post Management</a>
    <a href="admin_settings.php"><i class="bi bi-gear"></i> Settings</a>
    <a href="logout.php" class="text-danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>

<div class="main-content">
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <button class="btn btn-light me-2 d-lg-none" onclick="toggleSidebar()">
                <i class="bi bi-list"></i>
            </button>
            <a class="navbar-brand text-white" href="#">Edit User</a>
            <div class="ms-auto text-white">
                <i class="bi bi-person-circle"></i> <?= $_SESSION['username']; ?> (Admin)
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <a href="manage_users.php" class="btn btn-secondary mb-3"><i class="bi bi-arrow-left"></i> Back</a>
        <h2>Edit User</h2>
        <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($user['username']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Role</label>
                <select name="role" class="form-control" required>
                    <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : ''; ?>>admin</option>
                    <option value="blogger" <?= $user['role'] === 'blogger' ? 'selected' : ''; ?>>blogger</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update User</button>
        </form>
    </div>
</div>

<script>
    function toggleSidebar() {
        var sidebar = document.getElementById("sidebar");
        if (sidebar.style.width === "250px" || sidebar.style.width === "") {
            sidebar.style.width = "0";
        } else {
            sidebar.style.width = "250px";
        }
    }
</script>

</body>
</html>
