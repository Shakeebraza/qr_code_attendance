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
            display: flex;
              flex-direction: column;
              align-items: center;
              justify-content: center;
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
            padding-top:5px;
        }
.popup {
    position: fixed;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 999; 
}

.popup-content {
    background-color: #ffffff; 
    padding: 30px; 
    border-radius: 10px; 
    text-align: center;
    width: 400px; 
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    position: relative; 
}

.close {
    cursor: pointer;
    position: absolute;
    top: 10px; 
    right: 10px; 
    font-size: 24px; 
    color: #333; 
}

.close:hover {
    color: #ff0000; 
}
    .changebtn{
        color:white;
        background-color:black;
     padding: 9px 10px;
      margin-top: 15px;
      border: 1px solid blue;
      border-radius: 8px;
        
    }
    .changenum {
      color: black;
      background: white;
      border: 1px solid blue;
      border-radius: 10px;
    }
    .popup-content > h3{
          color: black;
      background: white;
    }
    #changeNumberBtn:hover{
        background-color: #151414a3 ;
    }

    </style>
</head>
<body>
<?php include_once('menu.php'); ?>

<div class="form-container">
        <h2 id="setting">Settings</h2>
        <h5 id="profileimage">Profile Image</h5>

        <!-- Display the current profile image -->
        <?php if(isset($_SESSION['profile']) && !empty($_SESSION['profile'])): ?>
            <img src="<?php echo $_SESSION['profile']; ?>" alt="Profile Image" class="profile-image">
        <?php endif; ?>

        <form action="settings.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($csrfToken); ?>">
            <input type="file" name="profile" placeholder="Profile Picture">
            <h5 id="password">Password</h5>
            <input type="password" id="oldpasswordinput" name="old_password" placeholder="Old Password" >
            <input type="password" id="newpasswordinput" name="new_password" placeholder="New Password" >
            <button type="submit" id="updatepassword">Update Settings</button>
             <button type="button" id="changeNumberBtn" style="background-color:black;">Cambiar número de teléfono</button>
            <?php
            if(!empty($meg)){
                echo $meg;
            }
            ?>
        </form>
    </div>
<div id="changeNumberPopup" class="popup" style="display:none;">
    <div class="popup-content">
        <span class="close" id="closePopup">&times;</span>
        <h3>Cambiar número de teléfono</h3>
        <form id="changeNumberForm" action="<?php echo $urlval?>ajax/change_number.php" method="post">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($csrfToken); ?>">
            <input class="changenum" type="number" id="newPhone" name="new_phone" placeholder="Nuevo número de teléfono" required>
            <button class="changebtn" type="submit">Entregar</button>
        </form>
        <div id="message" style="color: red; margin-top: 10px;"></div>
    </div>
</div>
    <?php include('script.php'); ?>
    <script>
        let currentLanguage = 'es';

function loadLanguage(language) {
    fetch(`language/login/${language}.json`)
        .then(response => response.json())
        .then(data => {
            console.log(data);

            // Update text based on the selected language
            const elementById = {
                welcome: document.getElementById('welcome'),
                listOfPresentEmployees: document.getElementById('listOfPresentEmployees'),
                settings: document.getElementById('setting'),
                profileImage: document.getElementById('profileimage'),
                password: document.getElementById('password'),
                updateSettingsButton: document.getElementById('updatepassword')
            };

            // Update text for elements by ID
            for (const [key, element] of Object.entries(elementById)) {
                if (element) {
                    element.innerText = data[key] || '';
                } else {
                    console.warn(`Element with ID '${key}' not found.`);
                }
            }

            // Update placeholder for input field
            const oldpasswordinput = document.getElementById('oldpasswordinput');
            const newpasswordinput = document.getElementById('newpasswordinput');
            if (oldpasswordinput) {
                oldpasswordinput.placeholder = data.oldpassword || 'Old Password'; 
            }
             if (newpasswordinput) {
                newpasswordinput.placeholder = data.newpassword || 'New Password'; 
            }

            // Update text for elements with class names
            const elementsByClass = {
                home: document.getElementsByClassName('home'),
                qrCode: document.getElementsByClassName('qrCode'),
                adminPanel: document.getElementsByClassName('adminPanel'),
                viewProfile: document.getElementsByClassName('viewProfile'),
                settings: document.getElementsByClassName('settings'),
                logout: document.getElementsByClassName('logout'),
                gallery: document.getElementsByClassName('gallery')
            };

            for (const [key, elements] of Object.entries(elementsByClass)) {
                for (let i = 0; i < elements.length; i++) {
                    elements[i].innerText = data[key] || '';
                }
            }
        })
        .catch(error => console.error('Error loading localization file:', error));
}

// Load initial language
loadLanguage(currentLanguage);

// Handle language selection change
document.getElementById('languageSelector').addEventListener('change', function() {
    const selectedLanguage = this.value;
    if (selectedLanguage !== currentLanguage) {
        currentLanguage = selectedLanguage;
        loadLanguage(selectedLanguage);
    }
});
document.getElementById('mobilelanguageSelector').addEventListener('change', function() {
    const selectedLanguage = this.value;
    if (selectedLanguage !== currentLanguage) {
        currentLanguage = selectedLanguage;
        loadLanguage(selectedLanguage);
    }
});


 var changeNumberBtn = document.getElementById("changeNumberBtn");
    
    // Get the popup
    var popup = document.getElementById("changeNumberPopup");
    
    // Get the <span> element that closes the popup
    var closePopup = document.getElementById("closePopup");
    
    // When the user clicks the button, open the popup
    changeNumberBtn.onclick = function() {
        popup.style.display = "flex"; // Change to "flex" to center content
    }
    
    // When the user clicks on <span> (x), close the popup
    closePopup.onclick = function() {
        popup.style.display = "none";
    }
    
    // When the user clicks anywhere outside of the popup, close it
    window.onclick = function(event) {
        if (event.target == popup) {
            popup.style.display = "none";
        }
    }
    
document.getElementById('changeNumberForm').addEventListener('submit', function(e) {
    e.preventDefault(); // Prevent default form submission

    const phoneInput = document.getElementById('newPhone');
    const messageDiv = document.getElementById('message');
    const phoneNumber = phoneInput.value.trim();

    // Clear previous messages
    messageDiv.textContent = '';

    // Check if the number is exactly 10 digits
    if (!/^\d{10}$/.test(phoneNumber)) {
        messageDiv.textContent = 'El número de teléfono debe tener exactamente 10 dígitos.';
        messageDiv.style.color = 'red'; // Set message color to red
        return; // Stop further processing
    }

    // Prepare the AJAX request
    const formData = new FormData(this); // Automatically includes all form fields
    const xhr = new XMLHttpRequest();
    xhr.open('POST', this.action, true); // Send to the form's action URL

    xhr.onload = function() {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.error) {
                messageDiv.textContent = response.error; // Show error message
                messageDiv.style.color = 'red'; // Set message color to red
            } else {
                messageDiv.textContent = response.success; // Show success message
                messageDiv.style.color = 'green'; // Set message color to green
                phoneInput.value = ''; // Clear the input field after successful submission

                // Automatically close the popup after 15 seconds
                setTimeout(() => {
                    document.getElementById('changeNumberPopup').style.display = 'none';
                }, 5000); // 15000 milliseconds = 15 seconds
            }
        } else {
            messageDiv.textContent = 'Ha ocurrido un error al enviar el número de teléfono.'; // Show general error message
            messageDiv.style.color = 'red'; // Set message color to red
        }
    };

    xhr.onerror = function() {
        messageDiv.textContent = 'Ha ocurrido un error al enviar la solicitud.';
        messageDiv.style.color = 'red'; // Set message color to red
    };

    // Send the form data
    xhr.send(formData);
});
    </script>
</body>
</html>
