<?php
session_start();
include '../includes/db.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Pagination settings
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Search and filter
$search = isset($_GET['search']) ? $_GET['search'] : '';
$author_filter = isset($_GET['author']) ? $_GET['author'] : '';

$query = "SELECT posts.post_id, posts.title, posts.content, posts.image, posts.created_at, users.username 
          FROM posts 
          JOIN users ON posts.user_id = users.user_id 
          WHERE posts.is_delete = 0";

if (!empty($search)) {
    $query .= " AND (posts.title LIKE '%$search%' OR posts.content LIKE '%$search%')";
}

if (!empty($author_filter)) {
    $query .= " AND users.username = '$author_filter'";
}

$query .= " LIMIT $limit OFFSET $offset";
$result = $conn->query($query);

// Total posts count for pagination
$total_query = "SELECT COUNT(*) as total FROM posts WHERE is_delete = 0";
$total_result = $conn->query($total_query);
$total_posts = $total_result->fetch_assoc()['total'];
$total_pages = ceil($total_posts / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Posts</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/admin.css">
    <style>
        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            background-color: #3B82F6;
            color: white;
            padding-top: 20px;
            transition: all 0.3s;
        }
        .sidebar a {
            color: white;
            padding: 15px;
            display: block;
            text-decoration: none;
        }
        .sidebar a:hover {
            background-color: #2563EB;
        }
        .sidebar .active {
            background-color: #1E40AF;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
            transition: all 0.3s;
        }
        .navbar {
            background-color: #3B82F6;
        }
        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                overflow: hidden;
            }
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
<div class="sidebar">
    <h4 class="text-center">Admin Panel</h4>
    <hr>
    <a href="admin_dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="manage_users.php"><i class="bi bi-people"></i> User Management</a>
    <a href="manage_posts.php" class="active"><i class="bi bi-file-earmark-text"></i> Post Management</a>
    <a href="admin_settings.php"><i class="bi bi-gear"></i> Settings</a>
    <a href="logout.php" class="text-danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>
<div class="main-content">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand text-white" href="#">Manage Posts</a>
            <div class="ms-auto text-white">
                <i class="bi bi-person-circle"></i> <?= $_SESSION['username']; ?> (Admin)
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Manage Posts</h2>

        <!-- Search and Filter -->
        <form method="GET" class="mb-3 d-flex">
            <input type="text" name="search" class="form-control me-2" placeholder="Search posts..." value="<?= htmlspecialchars($search); ?>">
            <input type="text" name="author" class="form-control me-2" placeholder="Filter by author..." value="<?= htmlspecialchars($author_filter); ?>">
            <button type="submit" class="btn btn-secondary">Filter</button>
        </form>

        <table class="table table-bordered table-striped">
        <thead>
    <tr>
        <th>ID</th>
        <th>Post Image</th>
        <th>Title</th>
        <th>Author</th>
        <th>Created At</th>
        <th>Actions</th>
    </tr>
</thead>
<tbody>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td>#<?= $row['post_id']; ?></td>
        <td>
            <?php if (!empty($row['image'])): ?>
                <img src="../uploads/<?= htmlspecialchars($row['image']); ?>" alt="Post Image" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
            <?php else: ?>
                <span class="text-muted">No Image</span>
            <?php endif; ?>
        </td>
        <td><?= htmlspecialchars($row['title']); ?></td>
        <td><?= htmlspecialchars($row['username']); ?></td>
        <td><?= $row['created_at']; ?></td>
        <td>
            <a href="edit_post.php?id=<?= $row['post_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
            <a href="delete_post.php?id=<?= $row['post_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</tbody>

        </table>

        <!-- Pagination -->
        <nav>
            <ul class="pagination">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?= ($i == $page) ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?= $i; ?>&search=<?= htmlspecialchars($search); ?>&author=<?= htmlspecialchars($author_filter); ?>"><?= $i; ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>
</div>
</body>
</html>