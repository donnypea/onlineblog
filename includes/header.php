<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$current_page = basename($_SERVER['PHP_SELF']);
$username = $_SESSION['username'];
$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['role'];

include 'db.php'; // Database connection

// Fetch unread comment notifications for the current user's posts
$sql = "SELECT c.comment_id, c.content, c.created_at, p.title, p.post_id 
        FROM comments c
        JOIN posts p ON c.post_id = p.post_id
        WHERE p.user_id = ? AND c.is_read = 0 AND c.is_delete = 0
        ORDER BY c.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$notifications = [];
while ($row = $result->fetch_assoc()) {
    $notifications[] = $row;
}

$unread_count = count($notifications);
?>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg fixed-top" style="background-color: #3B82F6;">
    <div class="container">
        <?php if ($current_page !== 'index.php'): ?>
            <button class="btn btn-light me-2" onclick="goBack()" id="backBtn">
                <i class="bi bi-arrow-left"></i> Back
            </button>
        <?php endif; ?>

        <a class="navbar-brand text-white" href="index.php">
            <i class="bi bi-pencil-square"></i> My Blog
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link text-white" href="index.php">
                        <i class="bi bi-house-door"></i> Home
                    </a>
                </li>

                <!-- Notification Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link text-white position-relative" href="#" id="notifDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-bell"></i>Notifications
                        <?php if ($unread_count > 0): ?>
                            <span class="badge bg-danger position-absolute top-0 start-100 translate-middle rounded-pill"><?= $unread_count ?></span>
                        <?php endif; ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notifDropdown">
                        <li><h6 class="dropdown-header">Notifications</h6></li>
                        <?php if (empty($notifications)): ?>
                            <li><a class="dropdown-item text-muted">No new notifications</a></li>
                        <?php else: ?>
                            <?php foreach ($notifications as $notif): ?>
                                <li>
                                    <a class="dropdown-item" href="view_post.php?id=<?= $notif['post_id'] ?>">
                                        <strong>New comment</strong> on <em><?= htmlspecialchars($notif['title']) ?></em>
                                        <br>
                                        <small class="text-muted"><?= htmlspecialchars($notif['content']) ?></small>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-white" href="profile.php?user_id=<?= $user_id; ?>">
                        <i class="bi bi-person"></i> <?= htmlspecialchars($username); ?>
                        <?php if ($user_role === 'admin'): ?>
                            <span class="badge bg-danger">(Admin)</span>
                        <?php endif; ?>
                    </a>
                </li>

                <?php if ($user_role === 'admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link text-white btn btn-warning ms-2" href="admin/admin_dashboard.php">
                            <i class="bi bi-shield-lock"></i> Admin Panel
                        </a>
                    </li>
                <?php endif; ?>

                <li class="nav-item">
                    <a class="nav-link text-white" href="settings.php">
                        <i class="bi bi-gear"></i> Settings
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white btn btn-danger ms-2" href="logout.php">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<script>
    function goBack() {
        window.history.back();
    }
</script>

<style>
    body {
        padding-top: 70px;
    }
</style>
