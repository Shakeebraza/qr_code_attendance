<?php
include_once('../global.php');
include_once('../conn/conn.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start the session if it's not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check session
$res = $funObject->checksession();
if ($res == 0) {
    header('Location: login.php');
    exit();
}

// Get user ID from the session
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
if (!$user_id) {
    echo json_encode(['error' => '¡Usuario no conectado!']);
    exit();
}

// Check if files and other required POST parameters are set
if (!isset($_FILES['file']) || !isset($_POST['date']) || !isset($_POST['time'])) {
    echo json_encode(['error' => 'Solicitud no válida']);
    exit();
}

$allowedMimeTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/svg+xml'];

// Check how many images the user has already uploaded
$query = "SELECT COUNT(*) FROM gallery WHERE userid = ?";
$stmt = $conn->prepare($query);
$stmt->bindValue(1, $user_id, PDO::PARAM_INT);
$stmt->execute();
$imageCount = $stmt->fetchColumn();

if ($imageCount >= 5) {
    echo json_encode(['error' => 'Solo puedes cargar hasta 5 imágenes']);
    exit();
}

// Prepare to process file uploads
$uploadDirectory = '../uploads/';
$successCount = 0; // To count successfully uploaded images
$errors = []; // To store errors if any

// Check if the uploaded file is an array (multiple files) or a single file
if (is_array($_FILES['file']['name'])) {
    // Multiple files
    foreach ($_FILES['file']['name'] as $key => $name) {
        $file = [
            'name' => $_FILES['file']['name'][$key],
            'type' => $_FILES['file']['type'][$key],
            'tmp_name' => $_FILES['file']['tmp_name'][$key],
            'error' => $_FILES['file']['error'][$key],
            'size' => $_FILES['file']['size'][$key]
        ];

        // Handle file processing (similar to single file handling)
        processFile($file, $user_id, $allowedMimeTypes, $uploadDirectory, $successCount, $errors);
    }
} else {
    // Single file
    $file = $_FILES['file'];
    processFile($file, $user_id, $allowedMimeTypes, $uploadDirectory, $successCount, $errors);
}

// Response after processing all uploads
if ($successCount > 0) {
    echo json_encode(['success' => "$successCount imagen(es) cargadas exitosamente"]);
} else {
    echo json_encode(['error' => implode(", ", $errors)]);
}

/**
 * Function to process individual file upload
 */
function processFile($file, $user_id, $allowedMimeTypes, $uploadDirectory, &$successCount, &$errors) {
    // Check for upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors[] = "File upload error for " . $file['name'] . ": " . $file['error'];
        return; // Skip to the next file
    }

    // Validate the file type
    if (!in_array($file['type'], $allowedMimeTypes) || $file['size'] > 2048 * 1024) {
        $errors[] = "Invalid image file: " . $file['name'];
        return; // Skip to the next file
    }

    // Prepare to save the image
    $imageName = time() . '_' . uniqid() . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
    $targetFilePath = $uploadDirectory . $imageName;

    // Move the uploaded file to the target directory
    if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
        $targetFilePath2="uploads/".$imageName;
        // Save the image information in the database
        global $conn; // To access the $conn variable
        $query = "INSERT INTO gallery (userid, filepath, filename) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bindValue(1, $user_id, PDO::PARAM_INT);
        $stmt->bindValue(2, $targetFilePath2, PDO::PARAM_STR);
        $stmt->bindValue(3, $imageName, PDO::PARAM_STR);

        if ($stmt->execute()) {
            $successCount++;
        } else {
            $errors[] = "Failed to save image information for " . $file['name'] . " in the database";
        }
    } else {
        $errors[] = "Failed to upload image: " . $file['name'];
    }
}
?>
