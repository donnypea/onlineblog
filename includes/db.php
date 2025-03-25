<?php
$host = "localhost";
$user = "root"; // Default XAMPP user
$pass = ""; // Leave empty for XAMPP
$db = "blogdb";

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
