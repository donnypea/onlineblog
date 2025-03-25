<?php
session_start();
include '../includes/db.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Check if user_id is set in GET request
if (isset($_GET['id'])) {
    $user_id = intval($_GET['id']);

    // Update the is_delete column to 1 (soft delete)
    $query = "UPDATE users SET is_delete = 1 WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "User has been deactivated successfully.";
    } else {
        $_SESSION['error'] = "Failed to deactivate user.";
    }

    $stmt->close();
    header("Location: manage_users.php");
    exit();
} else {
    $_SESSION['error'] = "Invalid request.";
    header("Location: manage_users.php");
    exit();
}
?>
