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
<<<<<<< HEAD
=======
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $userExists = $stmt->fetchColumn();

    if ($userExists) {
        // User already exists
        echo "<script>
        alert('User already exists. Please use a different username or Number.');
        window.location.href = '../singup.php'; // Redirect to registration page
        </script>";
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and bind for insertion
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
>>>>>>> 7ff2ec5aa10efce709929748f8c1223c10428c95
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

?>
