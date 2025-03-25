<?php
session_start();
include '../includes/db.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Check if post ID is set
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid post ID.");
}

$post_id = $_GET['id'];

// Fetch post details
$query = "SELECT title, content, image FROM posts WHERE post_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();
$stmt->close();

if (!$post) {
    die("Post not found.");
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $image = $post['image']; // Keep old image by default

    // Check if a new image is uploaded
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../uploads/";
        $image = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image;
        
        // Move the uploaded file
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // Optional: Delete old image (if it exists)
            if (!empty($post['image']) && file_exists("../uploads/" . $post['image'])) {
                unlink("../uploads/" . $post['image']);
            }
        } else {
            die("Error uploading image.");
        }
    }

    // Update post in the database
    $update_query = "UPDATE posts SET title = ?, content = ?, image = ? WHERE post_id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("sssi", $title, $content, $image, $post_id);
    
    if ($update_stmt->execute()) {
        echo "<script>alert('Post updated successfully!'); window.location.href='manage_posts.php';</script>";
    } else {
        echo "<script>alert('Error updating post.');</script>";
    }
    $update_stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
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
    <a href="manage_posts.php" class="active"><i class="bi bi-file-earmark-text"></i> Post Management</a>
    <a href="admin_settings.php"><i class="bi bi-gear"></i> Settings</a>
    <a href="logout.php" class="text-danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>
<div class="main-content">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand text-white" href="#">Manage Posts</a>
            <div class="ms-auto text-white">
                <i class="bi bi-person-circle"></i> <?= $_SESSION['username']; ?> (Admin)
            </div>
        </div>
    </nav>

<div class="container mt-4">
<a href="manage_posts.php" class="btn btn-secondary mb-3"><i class="bi bi-arrow-left"></i> Back</a>
    <h2>Edit Post</h2>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="title" class="form-label">Title:</label>
            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($post['title']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="content" class="form-label">Content:</label>
            <textarea name="content" class="form-control" rows="5" required><?= htmlspecialchars($post['content']); ?></textarea>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Post Image:</label>
            <?php if (!empty($post['image']) && file_exists("../uploads/" . $post['image'])): ?>
                <img src="../uploads/<?= htmlspecialchars($post['image']); ?>" class="img-fluid mb-2" width="200">
            <?php endif; ?>
            <input type="file" name="image" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Update Post</button>
        <a href="manage_posts.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
