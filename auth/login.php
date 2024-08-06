<?php
session_start();
require '../conn/conn.php'; 

// Sanitize input
$email = htmlspecialchars(trim($_POST['email']));
$password = htmlspecialchars(trim($_POST['password']));

try {
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Check if the account is verified
        if ($user['verified'] == 0) {
            echo "Your account is not verified. Please contact admin. <a href='../login.php'>Login Different Account</a>";
            session_destroy();
        } else {
            // Verified
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['type'] = $user['type'];
            $_SESSION['verified'] = $user['verified'];
            $_SESSION['profile'] = $user['profile'];
            header('Location: ../welcome.php');
        }
    } else {
        echo "<script>
        alert('Invalid email or password.');
        window.location.href = '../login.php';
        </script>";
        exit(); 
    }
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$conn = null;
?>
