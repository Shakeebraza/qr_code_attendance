<?php
include_once('global.php');
$res = $funObject->checksession();
if ($res == 1) {
    header('Location: welcome.php');
    exit(); 
}
$chkIsAdmin = $funObject->isAdmin();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #333;
            color: #fff;
            margin: 0;
        }
        .form-container {
            background-color: #222;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
            width: 300px;
            text-align: center;
        }
        .form-container h2 {
            margin-bottom: 20px;
            color: #fff;
        }
        .form-container input {
            width: calc(100% - 22px);
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #555;
            border-radius: 5px;
            background-color: #444;
            color: #fff;
        }
        .form-container input::placeholder {
            color: #aaa;
        }
        .form-container button {
            width: 100%;
            padding: 12px;
            margin-top: 10px;
            background-color: #007bff;
            border: none;
            color: #fff;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .form-container button:hover {
            background-color: #0056b3;
        }
        .form-container a {
            display: block;
            margin-top: 10px;
            color: #007bff;
            text-decoration: none;
            font-size: 14px;
        }
        .form-container a:hover {
            text-decoration: underline;
        }
        #languageSelect {
    padding: 10px;
    border: 1px solid #555;
    border-radius: 5px;
    background-color: #444;
    color: #fff;
    font-size: 16px;
    cursor: pointer;
    margin-top: 20px; /* Adjust as needed */
    width: 100%; /* Adjust as needed */
    }
    
    #languageSelect option {
        background-color: #333;
        color: #fff;
        padding: 10px;
        border: none;
    }
    
    #languageSelect option:hover {
        background-color: #555;
    }
    </style>
</head>
<body>
    <div class="form-container">
        <h2 id="headerTitle">Register</h2>
        <form action="auth/register.php" method="post" onsubmit="return validateForm()">
            <input type="text" name="username" id="username" placeholder="Your Name at Work" required>
            <input type="text" name="email" id="number" placeholder="Cell Phone Number" required>
            <input type="password" name="password" placeholder="Password" required>
            <a href="<?php echo $urlval ?>login.php" id="loginLink">Already have an account? Login</a>
            <button type="submit" id="registerButton">Register</button>
            <div id="error-message" style="color: red; display: none;">Please enter at least 10 digits.</div>
        </form>
        <select id="languageSelect">
            <option value="en">English</option>
            <option value="es">Spanish</option>
        </select>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Include jQuery -->
    <script>
        $(document).ready(function() {
            const languageSelect = $('#languageSelect');
            const textElements = {
                headerTitle: $('#headerTitle'),
                usernamePlaceholder: $('#username'),
                numberPlaceholder: $('#number'),
                passwordPlaceholder: $('input[name="password"]'),
                loginLink: $('#loginLink'),
                registerButton: $('#registerButton'),
                errorMessage: $('#error-message')
            };

            function loadLanguage(language, page) {
                $.getJSON(`language/${page}_${language}.json`, function(data) {
                    textElements.headerTitle.text(data.headerTitle);
                    textElements.usernamePlaceholder.attr('placeholder', data.usernamePlaceholder);
                    textElements.numberPlaceholder.attr('placeholder', data.numberPlaceholder);
                    textElements.passwordPlaceholder.attr('placeholder', data.passwordPlaceholder);
                    textElements.loginLink.text(data.loginLink);
                    textElements.registerButton.text(data.registerButton);
                    textElements.errorMessage.text(data.errorMessage);
                });
            }

            const currentPage = 'register';


            const storedLang = localStorage.getItem('language') || 'en';
            languageSelect.val(storedLang);
            loadLanguage(storedLang, currentPage);


            languageSelect.change(function() {
                const selectedLang = $(this).val();
                localStorage.setItem('language', selectedLang);
                loadLanguage(selectedLang, currentPage);
            });
        });

        function validateForm() {
            const usernameInput = document.getElementById('username').value;
            const numberInput = document.getElementById('number').value;
            const errorMessage = document.getElementById('error-message');
            

            const usernamePattern = /^[a-zA-Z]+(?: [a-zA-Z]+)?$/;
            const isUsernameValid = usernamePattern.test(usernameInput);
            
            const isNumeric = /^[0-9]+$/.test(numberInput);
            
            if (!isUsernameValid) {
                alert('El nombre de usuario solo puede contener letras y un único espacio entre palabras.');
                return false; 
            }
            
            if (!isNumeric || numberInput.length < 10) {
                errorMessage.style.display = 'block';
                return false; // Prevent form submission
            } else {
                errorMessage.style.display = 'none';
            }

            return true; // Allow form submission
        }
    </script>
</body>

</html>
