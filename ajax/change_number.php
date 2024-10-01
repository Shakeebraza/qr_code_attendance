<?php
include_once('../conn/conn.php');
include_once('../global.php'); // Assuming this file contains the session and CSRF token handling

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newPhone = isset($_POST['new_phone']) ? trim($_POST['new_phone']) : '';

    // Server-side validation
    if (!preg_match('/^\d{10}$/', $newPhone)) {
        // Handle the error
        echo json_encode(['error' => 'El número de teléfono debe tener exactamente 10 dígitos.']);
        exit();
    }

    try {
        // Assuming you have the user ID stored in the session
        $userId = $_SESSION['user_id']; // Change this to the correct session variable you are using

        // Check if the new phone number already exists
        $checkStmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
        $checkStmt->bindParam(':email', $newPhone, PDO::PARAM_STR);
        $checkStmt->execute();
        $exists = $checkStmt->fetchColumn();

        if ($exists > 0) {
            // Phone number already exists
            echo json_encode(['error' => 'El número de teléfono ya está en uso.']);
            exit();
        }

        // Update the phone number in the database
        $stmt = $conn->prepare("UPDATE users SET email = :email WHERE id = :user_id");

        // Bind parameters
        $stmt->bindParam(':email', $newPhone, PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);

        // Execute the statement
        if ($stmt->execute()) {
            echo json_encode(['success' => 'Número guardado exitosamente.']);
        } else {
            echo json_encode(['error' => 'Error al actualizar el número de teléfono.']);
        }
    } catch (PDOException $e) {
        // Handle database connection errors
        echo json_encode(['error' => 'Ha ocurrido un error: ' . $e->getMessage()]);
    }
    exit();
}
?>
