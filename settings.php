<?php
include_once('global.php'); 

$res = $funObject->checksession();
if ($res == 0) {
    header('Location: login.php');
    exit();
}

// Generate CSRF token
$csrfToken = $funObject->generateCsrfToken();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!isset($_POST['token']) || $_POST['token'] !== $csrfToken) {
        die('Invalid CSRF token');
    }

    $oldPassword = htmlspecialchars(trim($_POST['old_password']));
    $newPassword = htmlspecialchars(trim($_POST['new_password']));


    // Password change logic
    if (!empty($oldPassword) && !empty($newPassword)) {
        try {
            // Fetch current user data
            $stmt = $conn->prepare("SELECT password FROM users WHERE id = :id");
            $stmt->bindParam(':id', $_SESSION['user_id']);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($oldPassword, $user['password'])) {
                $hashedNewPassword = password_hash($newPassword, PASSWORD_BCRYPT);
                
                // Update new password
                $stmt = $conn->prepare("UPDATE users SET password = :new_password WHERE id = :id");
                $stmt->bindParam(':new_password', $hashedNewPassword);
                $stmt->bindParam(':id', $_SESSION['user_id']);
                $stmt->execute();
                
                $meg= "<p class='success'>Password updated successfully.</p>";
            } else {
                $meg= "<p class='error'>Old password is incorrect.</p>";
            }
        } catch (PDOException $e) {
            $meg= "<p class='error'>Error: " . $e->getMessage() . "</p>";
        }
    }

 

    // Profile image upload logic
    if (isset($_FILES['profile']) && $_FILES['profile']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['profile']['tmp_name'];
        $fileName = $_FILES['profile']['name'];
        $fileSize = $_FILES['profile']['size'];
        $fileType = $_FILES['profile']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        
        // Check if file is an image and has allowed extension
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($fileExtension, $allowedExtensions)) {
            // Directory where the file will be uploaded
            $uploadFileDir = 'uploads/';
            $dest_path = $uploadFileDir . basename($fileName);
            
            // Move the file from the temp directory to the destination directory
            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                try {
                    // Update profile picture path in the database
                    $stmt = $conn->prepare("UPDATE users SET profile = :profile WHERE id = :id");
                    $stmt->bindParam(':profile', $dest_path);
                    $stmt->bindParam(':id', $_SESSION['user_id']);
                    $stmt->execute();

                    // Update session with new profile image path
                    $_SESSION['profile'] = $dest_path;

                    $meg= "<p class='success'>Profile image updated successfully.</p>";
                } catch (PDOException $e) {
                    $meg= "<p class='error'>Error: " . $e->getMessage() . "</p>";
                }
            } else {
                $meg= "<p class='error'>There was an error uploading the file.</p>";
            }
        } else {
            $meg= "<p class='error'>Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.</p>";
        }
    }

    $conn = null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio,line-clamp,container-queries"></script>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

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
        .profile-image {
            border-radius: 50%;
            width: 150px;
            height: 150px;
            object-fit: cover;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<?php include_once('menu.php'); ?>

<div class="form-container">
        <h2>Settings</h2>
        <h5>Profile Image</h5>

        <!-- Display the current profile image -->
        <?php if(isset($_SESSION['profile']) && !empty($_SESSION['profile'])): ?>
            <img src="<?php echo $_SESSION['profile']; ?>" alt="Profile Image" class="profile-image">
        <?php endif; ?>

        <form action="settings.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($csrfToken); ?>">
            <input type="file" name="profile" placeholder="Profile Picture">
            <h5>Password</h5>
            <input type="password" name="old_password" placeholder="Old Password" >
            <input type="password" name="new_password" placeholder="New Password" >
            <button type="submit">Update Settings</button>
            <?php
            if(!empty($meg)){
                echo $meg;
            }
            ?>
        </form>
    </div>

    <?php include('script.php'); ?>
</body>
</html>
