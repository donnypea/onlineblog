<?php
session_start();
include '../includes/db.php';
// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Fetch total users
$totalUsersQuery = "SELECT COUNT(*) AS total FROM users";
$totalUsersResult = mysqli_query($conn, $totalUsersQuery);
$totalUsers = mysqli_fetch_assoc($totalUsersResult)['total'];

// Fetch total posts
$totalPostsQuery = "SELECT COUNT(*) AS total FROM posts";
$totalPostsResult = mysqli_query($conn, $totalPostsQuery);
$totalPosts = mysqli_fetch_assoc($totalPostsResult)['total'];

// Fetch posts created today
$postsTodayQuery = "SELECT COUNT(*) AS total FROM posts WHERE DATE(created_at) = CURDATE()";
$postsTodayResult = mysqli_query($conn, $postsTodayQuery);
$postsToday = mysqli_fetch_assoc($postsTodayResult)['total'];

// Fetch new users created today
$newUsersTodayQuery = "SELECT COUNT(*) AS total FROM users WHERE DATE(created_at) = CURDATE()";
$newUsersTodayResult = mysqli_query($conn, $newUsersTodayQuery);
$newUsersToday = mysqli_fetch_assoc($newUsersTodayResult)['total'];

// Fetch total bloggers
$totalBloggersQuery = "SELECT COUNT(*) AS total FROM users WHERE role = 'blogger'";
$totalBloggersResult = mysqli_query($conn, $totalBloggersQuery);
$totalBloggers = mysqli_fetch_assoc($totalBloggersResult)['total'];

// Fetch total Admins
$totalAdminsQuery = "SELECT COUNT(*) AS total FROM users WHERE role = 'admin'";
$totalAdminsResult = mysqli_query($conn, $totalAdminsQuery);
$totalAdmins = mysqli_fetch_assoc($totalAdminsResult)['total'];


// Fetch total active posts (is_delete = 0)
$activePostsQuery = "SELECT COUNT(*) AS total FROM posts WHERE is_delete = 0";
$activePostsResult = mysqli_query($conn, $activePostsQuery);
$activePosts = mysqli_fetch_assoc($activePostsResult)['total'];

// Fetch total active users (is_delete = 0)
$activeUsersQuery = "SELECT COUNT(*) AS total FROM users WHERE is_delete = 0";
$activeUsersResult = mysqli_query($conn, $activeUsersQuery);
$activeUsers = mysqli_fetch_assoc($activeUsersResult)['total'];

// Fetch the five most recent users
$recentUsersQuery = "SELECT username, email, created_at, p_image FROM users ORDER BY created_at DESC LIMIT 5";
$recentUsersResult = mysqli_query($conn, $recentUsersQuery);

// Fetch recent posts (last 5)
$recentPostsQuery = "SELECT title, created_at FROM posts ORDER BY created_at DESC LIMIT 5";
$recentPostsResult = mysqli_query($conn, $recentPostsQuery);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/admin.css"> <!-- Custom CSS -->
    <style>
        .dashboard-card {
            text-align: center;
            padding: 20px;
            border-radius: 10px;
            transition: 0.3s;
            width: 300px;
            height: 150px;
        }
        .dashboard-card h3 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .dashboard-card p {
            font-size: 18px;
            margin: 0;
        }
        .dashboard-card i {
            font-size: 40px;
            margin-bottom: 10px;
        }
        .dashboard-card:hover {
            transform: scale(1.05);
        }
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

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <h4 class="text-center">Admin Panel</h4>
    <hr>
    <a href="admin_dashboard.php" class="active"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="manage_users.php"><i class="bi bi-people"></i> User Management</a>
    <a href="manage_posts.php"><i class="bi bi-file-earmark-text"></i> Post Management</a>
    <a href="admin_settings.php"><i class="bi bi-gear"></i> Settings</a>
    <a href="logout.php" class="text-danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>

<!-- Main Content -->
<div class="main-content">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <button class="btn btn-light me-2 d-lg-none" onclick="toggleSidebar()">
                <i class="bi bi-list"></i>
            </button>
            <a class="navbar-brand text-white" href="#">Admin Dashboard</a>
            <div class="ms-auto text-white">
            <a href="../index.php" class="btn btn-primary my-3">
    <i class="bi bi-pencil"></i> Login as Blogger
    </a>
                <i class="bi bi-person-circle"></i> <?= $_SESSION['username']; ?> (Admin)
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Welcome, <?= $_SESSION['username']; ?>!</h2>
        <p>This is the admin dashboard where you can monitor statistics.</p>
        
        


        <div class="row g-3">
            <div class="col-md-4">
                <div class="dashboard-card bg-info text-white">
                    <i class="bi bi-people"></i>
                    <h3><?= $totalUsers ?></h3>
                    <p>Total Users</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="dashboard-card bg-success text-white">
                    <i class="bi bi-person-plus"></i>
                    <h3><?= $newUsersToday ?></h3>
                    <p>New Users Today</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="dashboard-card bg-warning text-dark">
                    <i class="bi bi-file-earmark-text"></i>
                    <h3><?= $totalPosts ?></h3>
                    <p>Total Posts</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="dashboard-card bg-danger text-white">
                    <i class="bi bi-pencil-square"></i>
                    <h3><?= $postsToday ?></h3>
                    <p>Posts Today</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="dashboard-card bg-secondary text-white">
                    <i class="bi bi-person-badge"></i>
                    <h3><?= $totalBloggers ?></h3>
                    <p>Total Bloggers</p>
                </div>
            </div>
            <div class="col-md-4">
            <div class="dashboard-card bg-secondary text-white">
                <i class="bi bi-shield-lock"></i>
                <h3><?= $totalAdmins ?></h3>
                <p>Total Admins</p>
            </div>
        </div>

            <div class="col-md-4">
                <div class="dashboard-card bg-primary text-white">
                    <i class="bi bi-file-earmark-check"></i>
                    <h3><?= $activePosts ?></h3>
                    <p>Active Posts</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="dashboard-card bg-dark text-white">
                    <i class="bi bi-person-check"></i>
                    <h3><?= $activeUsers ?></h3>
                    <p>Active Users</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
    <h3>Recent Users</h3>
    <div class="row">
        <?php while ($user = mysqli_fetch_assoc($recentUsersResult)): ?>
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-body text-center">
                    <img src="<?= !empty($row['p_image']) ? '../uploads/' . htmlspecialchars($row['p_image']) : '../uploads/default_profile.png'; ?>" 
                     alt="User Image" 
                     class="img-thumbnail" 
                     style="width: 50px; height: 50px;">
                    <h5 class="card-title"><?= htmlspecialchars($user['username']); ?></h5>
                        <p class="card-text"><?= htmlspecialchars($user['email']); ?></p>
                        <small class="text-muted">Joined: <?= date('M d, Y', strtotime($user['created_at'])); ?></small>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

     <!-- Recent Posts -->
     <div class="col-md-6">
                <div class="recent-card p-3">
                    <h5><i class="bi bi-file-earmark-text"></i> Recent Posts</h5>
                    <ul class="list-group">
                        <?php while ($post = mysqli_fetch_assoc($recentPostsResult)): ?>
                            <li class="list-group-item">
                                <?= htmlspecialchars($post['title']); ?> 
                                <small class="text-muted">(<?= date("M d, Y", strtotime($post['created_at'])); ?>)</small>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                </div>
            </div>
</div>

</div>



<script>
    function toggleSidebar() {
        var sidebar = document.getElementById("sidebar");
        if (sidebar.style.width === "250px" || sidebar.style.width === "") {
            sidebar.style.width = "0";
        } else {
            sidebar.style.width = "250px";
        }
    }
</script>

</body>
</html>
