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
            // echo "Su cuenta no está verificada. Por favor contacte al administrador. <a href='../login.php'>Iniciar sesión en una cuenta diferente</a>";
            // session_destroy();
             $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['actual_name'] = $user['actual_name'];
            $_SESSION['work_name'] = $user['work_name'];
            $_SESSION['type'] = $user['type'];
            $_SESSION['verified'] = $user['verified'];
            $_SESSION['profile'] = $user['profile'];
            echo "<script>
        window.location.href = '../userprofile.php';
        </script>";
        exit(); 
        } else {
            // Verified
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['actual_name'] = $user['actual_name'];
            $_SESSION['work_name'] = $user['work_name'];
            $_SESSION['type'] = $user['type'];
            $_SESSION['verified'] = $user['verified'];
            $_SESSION['profile'] = $user['profile'];
            header('Location: ../empmasterlist.php');
        }
    } else {
        echo "<script>
        alert('Número o contraseña no válidos.');
        window.location.href = '../login.php';
        </script>";
        exit(); 
    }
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$conn = null;
?>
