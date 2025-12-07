<?php

session_start();
require_once '../configs/connect.php';

if (isset($_POST['submitmajor'])) {
    $major = $_POST['major'];

    $sql = "INSERT INTO majors (major) VALUES (:major)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':major', $major);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Major added successfully.";
    } else {
        $_SESSION['error'] = "Major added fail!!!";
    }
    
    header("location: major.php");
}

?>