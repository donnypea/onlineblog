<?php
session_start();
include '../includes/db.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Check if post ID is provided
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $post_id = $_GET['id'];

    // Soft delete the post by updating is_delete = 1
    $stmt = $conn->prepare("UPDATE posts SET is_delete = 1 WHERE post_id = ?");
    $stmt->bind_param("i", $post_id);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Post deleted successfully.";
    } else {
        $_SESSION['error_message'] = "Failed to delete post.";
    }

    $stmt->close();
} else {
    $_SESSION['error_message'] = "Invalid request.";
}

// Redirect back to manage posts
header("Location: manage_posts.php");
exit();
?>
