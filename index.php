
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
    <title>Attendance Management System</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }
        header {
            background-color: #333;
            color: white;
            padding: 15px;
            text-align: center;
        }
        nav {
            display: flex;
            justify-content: center;
            background-color: #444;
        }
        nav a {
            color: white;
            padding: 14px 20px;
            text-decoration: none;
            text-align: center;
        }
        nav a:hover {
            background-color: #555;
        }
        .login {
            float: right;
            margin-right: 20px;
        }
        .container {
            padding: 20px;
            text-align: center;
            background-color: white;
        }
        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px;
            position: fixed;
            width: 100%;
            bottom: 0;
        }
    </style>
</head>
<body>
    <header>
        <h1 id="headerTitle">Attendance Management System</h1>
    </header>
    <nav>
        <select id="languageSelect">
            <option value="en">English</option>
            <option value="es" selected>Spanish</option> <!-- Default to Spanish -->
        </select>
        <a id="signupLink" href="singup.php">Signup</a>
        <a id="loginLink" href="login.php">Login</a>
    </nav>
    <div class="container">
        <h2 id="welcomeMessage">Welcome to Norajokaraoke Attendance Management</h2>
        <p id="description">Manage your attendance efficiently with our system.</p>
    </div>
    <footer>
        <p id="footerText">Created by Fission Fox</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            const languageSelect = $('#languageSelect');
            const textElements = {
                title: $('#pageTitle'),
                headerTitle: $('#headerTitle'),
                signupLink: $('#signupLink'),
                loginLink: $('#loginLink'),
                welcomeMessage: $('#welcomeMessage'),
                description: $('#description'),
                footerText: $('#footerText')
            };
        
            function loadLanguage(language) {
                $.getJSON(`language/${language}.json`, function(data) {
                    textElements.title.text(data.title);
                    textElements.headerTitle.text(data.title);
                    textElements.signupLink.text(data.signup);
                    textElements.loginLink.text(data.login);
                    textElements.welcomeMessage.text(data.welcome_message);
                    textElements.description.text(data.description);
                    textElements.footerText.text(data.footer);
                });
            }
        
            // Load the selected language or default to Spanish
            const storedLang = localStorage.getItem('language') || 'es'; // Default to Spanish
            languageSelect.val(storedLang);
            loadLanguage(storedLang);
        
            // Change language when dropdown value changes
            languageSelect.change(function() {
                const selectedLang = $(this).val();
                localStorage.setItem('language', selectedLang);
                loadLanguage(selectedLang);
            });
        });

    </script>
</body>
</html>
