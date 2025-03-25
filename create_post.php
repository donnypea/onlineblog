<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once "includes/db.php";
include "includes/header.php";
$title = $content = "";
$title_err = $content_err = $image_err = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate title
    if (empty(trim($_POST["title"]))) {
        $title_err = "Title is required.";
    } else {
        $title = htmlspecialchars($_POST["title"]);
    }

    // Validate content
    if (empty(trim($_POST["content"]))) {
        $content_err = "Content is required.";
    } else {
        $content = htmlspecialchars($_POST["content"]);
    }

    // Handle image upload
    $image_name = "";
    if (!empty($_FILES["image"]["name"])) {
        $target_dir = "uploads/";
        $image_name = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_name;
        $image_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Allow only JPG, PNG, GIF
        if (!in_array($image_type, ["jpg", "jpeg", "png", "gif","jfif","webp"])) {
            $image_err = "Only JPG, JPEG, PNG, and GIF files are allowed.";
        } else {
            move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
        }
    }

    // If no errors, insert post
    if (empty($title_err) && empty($content_err) && empty($image_err)) {
        $stmt = $conn->prepare("INSERT INTO posts (user_id, title, content, image, created_at, is_delete) 
                                VALUES (?, ?, ?, ?, NOW(), 0)");
        $stmt->bind_param("isss", $_SESSION['user_id'], $title, $content, $image_name);
        
        if ($stmt->execute()) {
            $_SESSION['success_msg'] = "Post created successfully!";
            header("Location: index.php");
            exit();
        } else {
            echo "<div class='alert alert-danger'>Error: Could not create post.</div>";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<body>



<div class="container mt-4">
    <h2>Create a New Post</h2>
    <hr>

    <form action="create_post.php" method="POST" enctype="multipart/form-data">
        <!-- Title -->
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control <?= !empty($title_err) ? 'is-invalid' : ''; ?>" value="<?= $title; ?>">
            <div class="invalid-feedback"><?= $title_err; ?></div>
        </div>

        <!-- Content -->
        <div class="mb-3">
            <label class="form-label">Content</label>
            <textarea name="content" class="form-control <?= !empty($content_err) ? 'is-invalid' : ''; ?>" rows="5"><?= $content; ?></textarea>
            <div class="invalid-feedback"><?= $content_err; ?></div>
        </div>

        <!-- Image Upload -->
        <div class="mb-3">
            <label class="form-label">Upload Image</label>
            <input type="file" name="image" class="form-control <?= !empty($image_err) ? 'is-invalid' : ''; ?>">
            <div class="invalid-feedback"><?= $image_err; ?></div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-cloud-upload"></i> Publish Post
        </button>
        <a href="index.php" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
