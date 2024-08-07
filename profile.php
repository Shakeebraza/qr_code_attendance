<?php
include_once('global.php');
include_once('conn/conn.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars(trim($_POST['username']));

 
    $uploadedFiles = [];
    $allowedExtensions = ['pdf'];
    $uploadDir = 'uploads/';


    $uploadedFilePaths = [];

    for ($i = 1; $i <= 2; $i++) {
        if (isset($_FILES["file$i"]) && $_FILES["file$i"]['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES["file$i"]['tmp_name'];
            $fileName = $_FILES["file$i"]['name'];
            $fileSize = $_FILES["file$i"]['size'];
            $fileType = $_FILES["file$i"]['type'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            if (in_array($fileExtension, $allowedExtensions)) {
                $newFileName = uniqid() . '.' . $fileExtension;
                $destination = $uploadDir . $newFileName;

                if (move_uploaded_file($fileTmpPath, $destination)) {
                    $inputType = ($i == 1) ? 'Test' : 'Id card';
                    $uploadedFiles[] = [
                        'filename' => $fileName,
                        'file_path' => $destination,
                        'file_type' => $fileType,
                        'file_size' => $fileSize,
                        'input_type' => $inputType
                    ];
                    $uploadedFilePaths[] = $destination;
                } else {
                    $meg= "<p class='error'>Error uploading file $i.</p>";
                }
            } else {
                $meg= "<p class='error'>Invalid file type for file $i. Only PDF files are allowed.</p>";
            }
        }
    }

    try {
        // Update username
        $stmt = $conn->prepare("UPDATE users SET username = :username WHERE id = :id");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':id', $_SESSION['user_id']);
        $stmt->execute();

        $files = $funObject->updateSessionUsername();

        foreach ($uploadedFiles as $file) {
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

        $meg= "<p class='success'>Profile updated successfully.</p>";

        // Display uploaded files
        foreach ($uploadedFiles as $file) {
            $meg= "<p class='success'>Uploaded file: {$file['filename']}.</p>";
        }
    } catch (PDOException $e) {
        $meg= "<p class='error'>Error: " . $e->getMessage() . "</p>";
    }
}

$conn = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <!-- Data Table -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap');

        * {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: #121212;
            color: #e0e0e0;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .maincondev {
           
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .form-container {
            background-color: #1e1e1e;
            padding: 40px;
            border-radius: 12px;
            box-shadow: rgba(0, 0, 0, 0.3) 0px 4px 8px;
            max-width: 500px;
            margin: 20px auto;
            text-align: center;
        }

        .form-container h2 {
            margin-bottom: 20px;
            color: #e0e0e0;
        }

        .form-container input[type="text"], 
        .form-container input[type="password"], 
        .form-container input[type="file"], 
        .form-container button {
            width: calc(100% - 20px);
            padding: 15px;
            margin: 10px 0;
            border: 1px solid #333;
            border-radius: 8px;
            background-color: #333;
            color: #e0e0e0;
            font-size: 16px;
        }

        .form-container input::placeholder {
            color: #888;
        }

        .form-container button {
            background-color: #007bff;
            border: none;
            color: #fff;
            cursor: pointer;
            font-size: 18px;
            transition: background-color 0.3s;
        }

        .form-container button:hover {
            background-color: #0056b3;
        }

        .form-container a {
            display: block;
            margin-top: 15px;
            color: #007bff;
            text-decoration: none;
            font-size: 14px;
        }

        .form-container a:hover {
            text-decoration: underline;
        }

        .success {
            color: #4caf50;
            margin: 10px 0;
        }

        .error {
            color: #f44336;
            margin: 10px 0;
        }

        header {
            background-color: #333;
            padding: 10px;
            color: #fff;
            text-align: center;
        }

        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        nav ul li {
            display: inline;
            margin: 0 15px;
        }

        nav ul li a {
            color: #fff;
            text-decoration: none;
        }

        nav ul li a:hover {
            text-decoration: underline;
        }
        .container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 800px;
            width: 100%;
            max-height: 90vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }

        .pdf-container {
            width: 100%;
            height: 80vh; /* Adjust height as needed */
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }

        .pdf-container iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        .header {
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }
    </style>
</head>
<body>
    <?php include_once('menu.php'); ?>

    <div class="form-container">
        <h2>Update Profile</h2>
        <form action="profile.php" method="post" enctype="multipart/form-data">
            <p>Username</p>
            <input type="text" name="username" placeholder="New Username" required 
            value="<?php echo isset($_SESSION['username']) ? $_SESSION['username'] : ''; ?>">
            <p>Test</p>
            <input type="file" name="file1" accept=".pdf">
            <p>Id card</p>
            <input type="file" name="file2" accept=".pdf">
            <button type="submit">Update Profile</button>
            <?php 
            if(isset($meg) && !empty($meg)){

                echo $meg;
            }
            ?>
        </form>
    </div>


<div class="maincondev"> 
<?php


$files = $funObject->findfiles($_SESSION['user_id']);

if(!empty($files)){
foreach ($files as $file) {
    echo '
        <div class="container">
            <div class="header">
                <h1>'.$file['input_type'].'</h1>
            </div>
            <div class="pdf-container">
                <iframe src="' . $urlval . $file['file_path'] . '" type="application/pdf" width="100%" height="600px"></iframe>
            </div>
        </div>
    ';
}
}
?>
 


    </div>

    <?php include('script.php'); ?>
</body>
</html>
