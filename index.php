<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include "includes/db.php"; // Database connection
include "includes/header.php";
// Fetch all posts along with user roles and comment counts
$query = "SELECT posts.*, users.username, users.role, users.p_image, 
                 (SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.post_id AND comments.is_delete = 0) AS comment_count
          FROM posts 
          JOIN users ON posts.user_id = users.user_id
          WHERE posts.is_delete = 0 
          ORDER BY posts.created_at DESC";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Blog</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .card {
            max-width: 900px;
            margin: 90px auto;
        }
        .card-body img {
            display: block;
            max-width: 100%;
            height: auto;
            max-height: 400px;
            object-fit: contain;
            margin: 0 auto;
        }
    </style>
</head>
<body>



<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center">
        <h2>Latest Posts</h2>
        <a href="create_post.php" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Create Post
        </a>
    </div>
    <hr>

    <form action="search.php" method="GET" class="d-flex mb-4">
        <input type="text" name="query" class="form-control me-2" placeholder="Search for a blogger..." required>
        <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Search</button>
    </form>

    <?php if ($result->num_rows > 0): ?>
        <?php while ($post = $result->fetch_assoc()): ?>
            <div class="card mb-4 shadow">
                <div class="card-body">
                    <h4 class="card-title"><?= htmlspecialchars($post['title']); ?></h4>
                    <p class="text-muted">
                        By 
                        <a href="profile.php?user_id=<?= $post['user_id']; ?>" class="fw-bold text-decoration-none">
                            <?= htmlspecialchars($post['username']); ?>
                            <?php if ($post['role'] === 'admin'): ?>
                                <span class="text-danger">(admin)</span>
                            <?php endif; ?>
                        </a> 
                        - <?= date("F j, Y, g:i a", strtotime($post['created_at'])); ?>
                    </p>

                    <p class="card-text"><?= nl2br(htmlspecialchars(substr($post['content'], 0, 200))); ?>...</p>

                    <?php if (!empty($post['image'])): ?>
                        <img src="uploads/<?= htmlspecialchars($post['image']); ?>" class="img-fluid mb-3" alt="Post Image">
                    <?php endif; ?>
                    
                   <center>
                    <a href="view_post.php?id=<?= $post['post_id']; ?>" class="btn btn-sm btn-secondary">
                        <i class="bi bi-eye"></i> Read More (<?= $post['comment_count']; ?> comments)
                    </a>
                    
                    <?php if ($_SESSION['user_id'] == $post['user_id']): ?>
                        <a href="edit_post.php?id=<?= $post['post_id']; ?>" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <a href="delete_post.php?id=<?= $post['post_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this post?');">
                            <i class="bi bi-trash"></i> Delete
                        </a>

                        </center>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="alert alert-warning text-center">No posts yet. Be the first to create one!</div>
    <?php endif; ?>
</div>

<?php include "includes/footer.php"; ?>
<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>