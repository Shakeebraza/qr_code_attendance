<?php
include_once('../conn/conn.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $userId = $_POST['userId'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = isset($_POST['password']) && !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : null;
    $type = $_POST['status'];
    $verified = $_POST['usertype'];
    $profile = '';

    // Handle file upload if a new file is provided
    if (isset($_FILES['files']) && $_FILES['files']['error'][0] == UPLOAD_ERR_OK) {
        $target_dir = "../uploads/";
        $pro = "uploads/";
        $profile = $target_dir . basename($_FILES["files"]["name"][0]);
        $profilee = $pro . basename($_FILES["files"]["name"][0]);
        move_uploaded_file($_FILES["files"]["tmp_name"][0], $profile);
    } else {
        $profilee = ''; 
    }

    // Prepare the SQL statement for updating
    $sql = "UPDATE users SET username = :username, email = :email, type = :type, verified = :verified, profile = :profile";
    if ($password) {
        $sql .= ", password = :password";
    }
    $sql .= " WHERE id = :id";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':type', $verified);
    $stmt->bindParam(':verified', $type);
    $stmt->bindParam(':profile', $profilee);
    $stmt->bindParam(':id', $userId);

    if ($password) {
        $stmt->bindParam(':password', $password);
    }

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'Error: ' . $stmt->errorInfo()[2];
    }
}
?>
