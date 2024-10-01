<?php
include_once('../global.php');
include_once('../conn/conn.php');

$res = $funObject->checksession();
if ($res == 0) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $imageUploadDir = '../uploads/'; 
    $pdfUploadDir = '../uploads/'; 

    $allowedPdfExtensions = ['pdf'];
    $allowedImageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    $pdfUploadedFiles = [];
    $message = ''; 

    
    if (isset($_FILES['profilePic']) && $_FILES['profilePic']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['profilePic']['tmp_name'];
        $fileName = $_FILES['profilePic']['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (in_array($fileExtension, $allowedImageExtensions)) {
            $destPath = $imageUploadDir . basename($fileName);
            $relativeDestPath = str_replace('../', '', $destPath);

            if (move_uploaded_file($fileTmpPath, $destPath)) {
                // Update profile image path in the users table
                try {
                    $stmt = $conn->prepare("UPDATE users SET profile = :profile WHERE id = :id");
                    $stmt->bindParam(':profile', $relativeDestPath);
                    $stmt->bindParam(':id', $_SESSION['user_id']);
                    $stmt->execute();

                    // Update session variable for profile image
                    $_SESSION['profile'] = $destPath;
                    $message .= "<p class='success'>Profile image uploaded successfully.</p>";
                } catch (PDOException $e) {
                    $message .= "<p class='error'>Error updating profile image: " . $e->getMessage() . "</p>";
                }
            } else {
                $message .= "<p class='error'>Error moving the profile image file.</p>";
            }
        } else {
            $message .= "<p class='error'>Invalid file extension for profile image. Allowed types: jpg, jpeg, png, gif.</p>";
        }
    }

    // Handle PDF file uploads
    $pdfFiles = ['pdfUpload' => 'Id card', 'testId' => 'Test'];

    foreach ($pdfFiles as $inputName => $inputType) {
        if (isset($_FILES[$inputName]) && $_FILES[$inputName]['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES[$inputName]['tmp_name'];
            $fileName = $_FILES[$inputName]['name'];
            $fileSize = $_FILES[$inputName]['size'];
            $fileType = $_FILES[$inputName]['type'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            if (in_array($fileExtension, $allowedPdfExtensions)) {
                $newFileName = uniqid() . '.' . $fileExtension;
                $destination = $pdfUploadDir . $newFileName;
                $relativeDestination = str_replace('../', '', $destination);

                if (move_uploaded_file($fileTmpPath, $destination)) {
                    $pdfUploadedFiles[] = [
                        'filename' => $fileName,
                        'file_path' => $relativeDestination,
                        'file_type' => $fileType,
                        'file_size' => $fileSize,
                        'input_type' => $inputType
                    ];
                } else {
                    $message .= "<p class='error'>Error uploading PDF file $inputName.</p>";
                }
            } else {
                $message .= "<p class='error'>Invalid file type for $inputName. Only PDF files are allowed.</p>";
            }
        } else {
            if (isset($_FILES[$inputName])) {
                $message .= "<p class='error'>Error uploading $inputName.</p>";
            }
        }
    }

    // Handle PDF file entries in the database
    try {
        foreach ($pdfUploadedFiles as $file) {
            $stmt = $conn->prepare("SELECT id FROM files WHERE user_id = :user_id AND input_type = :input_type");
            $stmt->bindParam(':user_id', $_SESSION['user_id']);
            $stmt->bindParam(':input_type', $file['input_type']);
            $stmt->execute();
            $existingFile = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($existingFile) {
                // Update existing record
                $stmt = $conn->prepare("
                    UPDATE files 
                    SET filename = :filename, file_path = :file_path, file_type = :file_type, file_size = :file_size 
                    WHERE id = :id
                ");
                $stmt->bindParam(':id', $existingFile['id']);
            } else {
                // Insert new record
                $stmt = $conn->prepare("
                    INSERT INTO files (user_id, filename, file_path, file_type, file_size, input_type) 
                    VALUES (:user_id, :filename, :file_path, :file_type, :file_size, :input_type)
                ");
                $stmt->bindParam(':user_id', $_SESSION['user_id']);
                $stmt->bindParam(':input_type', $file['input_type']);
            }

            $stmt->bindParam(':filename', $file['filename']);
            $stmt->bindParam(':file_path', $file['file_path']);
            $stmt->bindParam(':file_type', $file['file_type']);
            $stmt->bindParam(':file_size', $file['file_size']);
            $stmt->execute();
        }

        $message .= "<p class='success'>PDF files processed successfully.</p>";

    } catch (PDOException $e) {
        $message .= "<p class='error'>Error processing PDF files: " . $e->getMessage() . "</p>";
    }

    echo $message;
}
?>
