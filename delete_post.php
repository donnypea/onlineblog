<?php
session_start();
require_once "includes/db.php"; 

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$post_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Update query to mark the post as deleted
$query = "UPDATE posts SET is_delete = 1 WHERE post_id = ? AND user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $post_id, $user_id);

if ($stmt->execute()) {
    echo "<script>alert('Post deleted successfully!'); window.location.href='index.php';</script>";
} else {
    echo "<script>alert('Failed to delete post.'); window.location.href='index.php';</script>";
}
?>
