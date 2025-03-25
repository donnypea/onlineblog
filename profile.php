<?php
session_start();
require_once "includes/db.php";
include "includes/header.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['user_id']) || empty($_GET['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_GET['user_id'];

// Fetch user details including profile image
$query = "SELECT username, p_image FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "<script>alert('User not found!'); window.location.href='index.php';</script>";
    exit();
}

// Handle Profile Picture Upload
$uploadError = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["profile_image"])) {
    $targetDir = "uploads/";
    $fileName = basename($_FILES["profile_image"]["name"]);
    $targetFilePath = $targetDir . $fileName;
    $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

    // Allowed file types
    $allowedTypes = array("jpg", "jpeg", "png", "webp");

    if (in_array($fileType, $allowedTypes)) {
        if ($_FILES["profile_image"]["size"] <= 2000000) { // Limit: 2MB
            if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $targetFilePath)) {
                // Update database with new profile image
                $updateQuery = "UPDATE users SET p_image = ? WHERE user_id = ?";
                $stmt = $conn->prepare($updateQuery);
                $stmt->bind_param("si", $fileName, $user_id);
                if ($stmt->execute()) {
                    header("Location: profile.php?user_id=$user_id"); // Reload page
                    exit();
                } else {
                    $uploadError = "Database update failed.";
                }
            } else {
                $uploadError = "Error uploading file.";
            }
        } else {
            $uploadError = "File size must be 2MB or less.";
        }
    } else {
        $uploadError = "Only JPG, JPEG, and PNG files are allowed.";
    }
}

// Fetch posts by this user
$post_query = "SELECT * FROM posts WHERE user_id = ? AND is_delete = 0 ORDER BY created_at DESC";
$stmt = $conn->prepare($post_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$posts = $stmt->get_result();
?>

<!DOCTYPE html>x
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($user['username']); ?>'s Profile - Blog</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
    
    <style>
        .profile-container {
            text-align: center;
            margin-bottom: 20px;
        }
        .profile-img {
            width: 220px;
            height: 220px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #007bff;
        }
        .username {
            font-size: 24px;
            font-weight: bold;
            margin-top: 10px;
        }
        .upload-form {
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>


<div class="container mt-4">
<div class="profile-container" data-session-user-id="<?= $_SESSION['user_id']; ?>">
    <a href="uploads/<?= !empty($user['p_image']) ? htmlspecialchars($user['p_image']) : 'default_profile.png'; ?>" target="_blank">
        <img src="uploads/<?= !empty($user['p_image']) ? htmlspecialchars($user['p_image']) : 'default_profile.png'; ?>" 
             alt="Profile Image" class="profile-img">
    </a>
    <div class="username"> <?= htmlspecialchars($user['username']); ?> </div>

    <form class="upload-form" method="POST" enctype="multipart/form-data">
        <input type="file" name="profile_image" class="form-control mb-2" required>
        <button type="submit" class="btn btn-primary btn-sm">Change Profile Picture</button>
    </form>
    
    <?php if (!empty($uploadError)): ?>
        <div class="alert alert-danger mt-2"> <?= htmlspecialchars($uploadError); ?> </div>
    <?php endif; ?>
</div>

    
    <h2><?= htmlspecialchars($user['username']); ?>'s Posts</h2>
    <hr>
    <?php if ($posts->num_rows > 0): ?>
    <?php while ($post = $posts->fetch_assoc()): ?>
        <div class="card mb-3 mx-auto" style="max-width: 700px; margin: 50px auto;">
            <div class="card-body text-center">
                <?php if (!empty($post['image'])): ?>
                    <img src="uploads/<?= htmlspecialchars($post['image']); ?>" 
                         alt="Post Image" 
                         class="img-fluid mb-3 d-block mx-auto" 
                         style="max-height: 400px; width: 500px; margin: 90px auto; object-fit: cover; border-radius: 5px;">
                <?php endif; ?>
                
                <h3>
                    <a href="view_post.php?id=<?= $post['post_id']; ?>" class="text-decoration-none">
                        <?= htmlspecialchars($post['title']); ?>
                    </a>
                </h3>
                <p class="text-muted"> <?= date("F j, Y, g:i a", strtotime($post['created_at'])); ?> </p>
                <p> <?= nl2br(htmlspecialchars(substr($post['content'], 0, 150))) . '...'; ?> </p>
                <a href="view_post.php?id=<?= $post['post_id']; ?>" class="btn btn-primary">Read More</a>
            </div>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <div class="alert alert-secondary text-center">This user has not posted anything yet.</div>
<?php endif; ?>

</div>

<?php include "includes/footer.php"; ?>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        let sessionUserId = document.querySelector(".profile-container").getAttribute("data-session-user-id");
        let profileUserId = new URLSearchParams(window.location.search).get("user_id");
        
        if (sessionUserId !== profileUserId) {
            let uploadForm = document.querySelector(".upload-form");
            if (uploadForm) {
                uploadForm.style.display = "none";
            }
        }
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
x