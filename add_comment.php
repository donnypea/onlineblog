<?php
session_start();
require_once "includes/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $post_id = $_POST['post_id'];
    $user_id = $_SESSION['user_id'];
    $content = trim($_POST['comment']);

    if (!empty($content)) {
        $query = "INSERT INTO comments (post_id, user_id, content, created_at, is_delete) 
                  VALUES (?, ?, ?, NOW(), 0)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iis", $post_id, $user_id, $content);

        if ($stmt->execute()) {
            header("Location: view_post.php?id=$post_id");
            exit();
        } else {
            echo "<script>alert('Error adding comment.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Comment cannot be empty.'); window.history.back();</script>";
    }
}
?>
