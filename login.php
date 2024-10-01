<?php
include_once('global.php');
$res = $funObject->checksession();
if ($res == 1) {
    header('Location: welcome.php');
    exit(); 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
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
            margin-top: 15px;
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
        <h2 id="headerTitle">Login</h2>
        <form action="auth/login.php" method="post" onsubmit="return validateLoginForm()">
            <input type="number" name="email" id="loginNumber" placeholder="Cell Phone Number" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" id="loginButton">Login</button>
            <a href="singup.php" id="signupLink">Signup</a>
            <div id="login-error-message" style="color: red; display: none;">Please enter at least 10 digits.</div>
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
                title: $('#pageTitle'),
                headerTitle: $('#headerTitle'),
                signupLink: $('#signupLink'),
                loginButton: $('#loginButton'),
                loginErrorMessage: $('#login-error-message'),
                cellPhonePlaceholder: $('#loginNumber'),
                passwordPlaceholder: $('input[name="password"]')
            };

            function loadLanguage(language, page) {
                $.getJSON(`language/${page}_${language}.json`, function(data) {
                    textElements.title.text(data.title);
                    textElements.headerTitle.text(data.headerTitle);
                    textElements.signupLink.text(data.signup);
                    textElements.loginButton.text(data.loginButton);
                    textElements.loginErrorMessage.text(data.loginErrorMessage);
                    textElements.cellPhonePlaceholder.attr('placeholder', data.cellPhonePlaceholder);
                    textElements.passwordPlaceholder.attr('placeholder', data.passwordPlaceholder);
                });
            }

            // Determine the current page
            const currentPage = 'login'; // Change this for different pages (home, login, etc.)

            // Load the selected language or default to English
            const storedLang = localStorage.getItem('language') || 'en';
            languageSelect.val(storedLang);
            loadLanguage(storedLang, currentPage);

            // Change language when dropdown value changes
            languageSelect.change(function() {
                const selectedLang = $(this).val();
                localStorage.setItem('language', selectedLang);
                loadLanguage(selectedLang, currentPage);
            });
        });

        function validateLoginForm() {
            const numberInput = document.getElementById('loginNumber').value;
            const errorMessage = document.getElementById('login-error-message');
            
            if (numberInput.length < 10) {
                errorMessage.style.display = 'block';
                return false; // Prevent form submission
            } else {
                errorMessage.style.display = 'none';
                return true; // Allow form submission
            }
        }
    </script>
</html>
