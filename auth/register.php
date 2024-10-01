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
    window.location.href = '../signup.php'; // Redirect to registration page
    </script>";
    exit();
}

try {
    // Check if user already exists
    $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE username = :username OR email = :email");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $userExists = $stmt->fetchColumn();

    if ($userExists) {
        // User already exists
        echo "<script>
        alert('El usuario ya existe. Utilice un nombre de usuario o número diferente.');
        window.location.href = '../singup.php'; // Redirect to registration page
        </script>";
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and bind for insertion
    $stmt = $conn->prepare("INSERT INTO users (username, work_name, email, password) VALUES (:username, :work_name, :email, :password)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':work_name', $username); // Assuming work_name is same as username
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashed_password);

    // Execute statement
    $stmt->execute();
    echo "<script>
    alert('¡Registro exitoso!');
    window.location.href = '../login.php'; // Redirect to login page
    </script>";
    exit(); 
} catch(PDOException $e) {
    echo "<script>
    alert('Error: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "');
    window.location.href = '../singup.php'; // Redirect to registration page
    </script>";
    exit(); // Ensure no further code is executed
}

// Close connection
$conn = null;
?>
