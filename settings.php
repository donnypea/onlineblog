<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once "includes/db.php";
include "includes/header.php";
// Fetch current user data
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];

    // Verify current password
    if ($current_password !== $user['password']) { // Change this to password_verify() if using hashed passwords
        $error = "Current password is incorrect.";
    } else {
        if (!empty($new_password)) {
            // Update email and password
            $query = "UPDATE users SET email = ?, password = ? WHERE user_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssi", $email, $new_password, $user_id);
        } else {
            // Update only email
            $query = "UPDATE users SET email = ? WHERE user_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("si", $email, $user_id);
        }

        if ($stmt->execute()) {
            $success = "Settings updated successfully!";
        } else {
            $error = "Error updating settings.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

</head>
<body>



<div class="container mt-4">
    <h2>Settings</h2>
    <hr>

    <?php if (isset($success)): ?>
        <div class="alert alert-success"><?= $success; ?></div>
    <?php elseif (isset($error)): ?>
        <div class="alert alert-danger"><?= $error; ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="current_password" class="form-label">Current Password</label>
            <input type="password" name="current_password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="new_password" class="form-label">New Password (Leave blank to keep current)</label>
            <input type="password" name="new_password" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Save Changes</button>
    </form>
</div>
<?php include "includes/footer.php"; ?>
</body>
</html>
