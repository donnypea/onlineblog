<?php
require_once("connect_db.php");

$query = "INSERT INTO users (firstname, lastname, email)
VALUES (:firstname, :lastname, :email)";

$stmt = $conn->prepare($query);

$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$email = $_POST['email'];

$stmt->bindParam(":firstname", $firstname);
$stmt->bindParam(":lastname", $lastname);
$stmt->bindParam(":email", $email);

$stmt->execute();

$conn = null;
$stmt = null;

header("Location: ../Prelim/users.php");
die();
