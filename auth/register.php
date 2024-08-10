<?php
require '../conn/conn.php'; // Database connection

// Sanitize and validate input
$username = htmlspecialchars(trim($_POST['username']));
$email = htmlspecialchars(trim($_POST['email']));
$password = htmlspecialchars(trim($_POST['password']));

// Basic validation
if (empty($username) || empty($email) || empty($password)) {
    echo "<script>
    alert('Please fill in all fields.');
    window.location.href = '../singup.php'; // Redirect to registration page
    </script>";
    exit();
}


// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

try {
    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashed_password);

    // Execute statement
    $stmt->execute();
    echo "<script>
    alert('Registration successful!');
    window.location.href = '../login.php'; // Redirect to login page
    </script>";
    exit(); 
} catch(PDOException $e) {
    echo "<script>
    alert('Error: " . addslashes($e->getMessage()) . "');
    window.location.href = '../singup.php'; // Redirect to registration page
    </script>";
    exit(); // Ensure no further code is executed
}

// Close connection
$conn = null;
?>
