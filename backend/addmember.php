<?php

require_once '../configs/connect.php';
require_once '../configs/requireLogin.php';


if (isset($_POST['submitmember'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $years = $_POST['years'];
    $major = $_POST['major'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format!";
        header("Location: member.php");
        exit();
    }

    $sql = "INSERT INTO members (firstName, lastName, email, years, major) VALUES (:firstname, :lastname, :email, :years, :major)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':firstname', $firstname);
    $stmt->bindParam(':lastname', $lastname);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':years', $years);
    $stmt->bindParam(':major', $major);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Member added successfully.";
    } else {
        $_SESSION['error'] = "Member added fail!!!";
    }
    header("location: member.php");
}

?>