<?php
session_start();
require_once "includes/db.php";
include "includes/header.php";
if (!isset($_GET['query']) || empty($_GET['query'])) {
    header("Location: index.php");
    exit();
}

$search_query = "%" . $_GET['query'] . "%";

// Fetch users matching the search query
$query = "SELECT user_id, username FROM users WHERE username LIKE ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $search_query);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - Blog</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <link rel="stylesheet" href="css/style.css">
</head>
<body>



<div class="container mt-4">
    <h2>Search Results for "<?= htmlspecialchars($_GET['query']); ?>"</h2>
    <hr>

    <?php if ($result->num_rows > 0): ?>
        <ul class="list-group">
            <?php while ($user = $result->fetch_assoc()): ?>
                <li class="list-group-item">
                    <a href="profile.php?user_id=<?= $user['user_id']; ?>" class="text-decoration-none">
                        <i class="bi bi-person-circle"></i> <?= htmlspecialchars($user['username']); ?>
                    </a>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <div class="alert alert-warning">No bloggers found matching your search.</div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
