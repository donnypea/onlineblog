<?php

include("connect_db.php");

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("SELECT * FROM users");
    $stmt->execute();
    $result = $stmt->fetchAll();



?>