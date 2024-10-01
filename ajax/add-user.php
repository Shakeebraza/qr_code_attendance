<?php
include_once('../conn/conn.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $type = $_POST['type'];
    $verified = $_POST['verified'];
    $profile = '';

    if (isset($_FILES['profile'])) {
        $target_dir = "../uploads/";
        $pro = "uploads/";
        $profile = $target_dir . basename($_FILES["profile"]["name"]);
        $profilee = $pro . basename($_FILES["profile"]["name"]);
        move_uploaded_file($_FILES["profile"]["tmp_name"], $profile);
    }

    $stmt = $conn->prepare("INSERT INTO users (username,work_name, profile, email, password, type, verified, date) VALUES (:username,:work_name,:profile, :email, :password, :type, :verified, CURDATE())");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':work_name', $username);
    $stmt->bindParam(':profile', $profilee);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':type', $type);
    $stmt->bindParam(':verified', $verified);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'Error: ' . $stmt->errorInfo()[2];
    }
}
?>
