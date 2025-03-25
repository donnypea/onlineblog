<?php
session_start();
require_once "includes/db.php"; 
include "includes/header.php";

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$post_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Fetch post data
$query = "SELECT * FROM posts WHERE post_id = ? AND user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $post_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: index.php");
    exit();
}

$post = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $content = $_POST['content'];

    $update_query = "UPDATE posts SET title = ?, content = ? WHERE post_id = ? AND user_id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssii", $title, $content, $post_id, $user_id);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        $error = "Failed to update the post.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>



<div class="container mt-4">
    <h2>Edit Post</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error; ?></div>
    <?php endif; ?>
    <form action="" method="POST">
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($post['title']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Content</label>
            <textarea name="content" class="form-control" rows="5" required><?= htmlspecialchars($post['content']); ?></textarea>
        </div>
        <button type="submit" class="btn btn-success">Update Post</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

</body>
</html>
