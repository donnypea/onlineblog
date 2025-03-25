<?php
session_start();
require_once "includes/db.php";
include "includes/header.php";
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$post_id = $_GET['id'];

// Fetch post details
$query = "SELECT posts.*, users.username FROM posts 
          JOIN users ON posts.user_id = users.user_id 
          WHERE posts.post_id = ? AND posts.is_delete = 0";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();

if (!$post) {
    echo "<script>alert('Post not found!'); window.location.href='index.php';</script>";
    exit();
}

// Fetch comments for this post
$comment_query = "SELECT comments.*, users.username FROM comments 
                  JOIN users ON comments.user_id = users.user_id 
                  WHERE comments.post_id = ? AND comments.is_delete = 0 
                  ORDER BY comments.created_at DESC";
$stmt = $conn->prepare($comment_query);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$comments = $stmt->get_result();



if (isset($_GET['id']) && isset($_SESSION['user_id'])) {
    $post_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    // Check if the logged-in user is the owner of the post
    $owner_query = "SELECT user_id FROM posts WHERE post_id = ?";
    $stmt = $conn->prepare($owner_query);
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $owner_result = $stmt->get_result();
    $owner = $owner_result->fetch_assoc();

    if ($owner && $owner['user_id'] == $user_id) {
        $update_notif = "UPDATE comments SET is_read = 1 WHERE post_id = ? AND is_read = 0";
        $stmt = $conn->prepare($update_notif);
        $stmt->bind_param("i", $post_id);
        $stmt->execute();
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($post['title']); ?> - Blog</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>



<div class="container mt-4">
    <div class="card shadow">
        <div class="card-body">
            <h2><?= htmlspecialchars($post['title']); ?></h2>
            <p class="text-muted">By <strong><?= htmlspecialchars($post['username']); ?></strong> - <?= date("F j, Y, g:i a", strtotime($post['created_at'])); ?></p>

            <?php if (!empty($post['image'])): ?>
                <img src="uploads/<?= htmlspecialchars($post['image']); ?>" class="img-fluid mb-3" alt="Post Image">
            <?php endif; ?>

            <p class="card-text"><?= nl2br(htmlspecialchars($post['content'])); ?></p>
        </div>
    </div>

    <!-- Comments Section -->
    <div class="mt-4">
        <h4>Comments</h4>
        <hr>

        <!-- Display Comments -->
        <?php if ($comments->num_rows > 0): ?>
            <?php while ($comment = $comments->fetch_assoc()): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <p class="fw-bold"><?= htmlspecialchars($comment['username']); ?></p>
                        <p><?= nl2br(htmlspecialchars($comment['content'])); ?></p>
                        <small class="text-muted"><?= date("F j, Y, g:i a", strtotime($comment['created_at'])); ?></small>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="alert alert-secondary">No comments yet. Be the first to comment!</div>
        <?php endif; ?>

        <!-- Add Comment Form -->
        <?php if (isset($_SESSION['user_id'])): ?>
            <form action="add_comment.php" method="POST" class="mt-3">
                <input type="hidden" name="post_id" value="<?= $post_id; ?>">
                <div class="mb-3">
                    <label for="comment" class="form-label">Leave a comment:</label>
                    <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary"><i class="bi bi-chat-dots"></i> Comment</button>
            </form>
        <?php else: ?>
            <div class="alert alert-warning mt-3">
                <i class="bi bi-exclamation-triangle"></i> You must be <a href="login.php">logged in</a> to comment.
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
