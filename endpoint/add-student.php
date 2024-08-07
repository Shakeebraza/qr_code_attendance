<?php
include("../conn/conn.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['username'], $_POST['email'], $_POST['password'], $_POST['generated_code'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $generatedCode = $_POST['generated_code'];

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        try {
            // Begin transaction
            $conn->beginTransaction();

            // Insert into users table
            $stmt = $conn->prepare("
                INSERT INTO users (username, email, password) 
                VALUES (:username, :email, :password)
            ");
            $stmt->bindParam(":username", $username, PDO::PARAM_STR);
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->bindParam(":password", $hashedPassword, PDO::PARAM_STR);
            $stmt->execute();

            // Get the last inserted user ID
            $lastId = $conn->lastInsertId();

            // Insert into tbl_student table
            $stmt = $conn->prepare("
                INSERT INTO tbl_student (user_id, student_name, generated_code) 
                VALUES (:user_id, :username, :generated_code)
            ");
            $stmt->bindParam(":user_id", $lastId, PDO::PARAM_INT);
            $stmt->bindParam(":username", $username, PDO::PARAM_STR);
            $stmt->bindParam(":generated_code", $generatedCode, PDO::PARAM_STR);
            $stmt->execute();

            // Commit transaction
            $conn->commit();

            // Redirect to the master list page
            header("Location: http://localhost/newatt/masterlist.php");
            exit();
        } catch (PDOException $e) {
            // Rollback transaction in case of error
            $conn->rollBack();
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "
            <script>
                alert('Please fill in all fields!');
                window.location.href = 'http://localhost/newatt/masterlist.php';
            </script>
        ";
    }
} else {
    header("Location: http://localhost/newatt/masterlist.php");
    exit();
}
?>
