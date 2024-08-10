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
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Register</h2>
        <form action="auth/register.php" method="post" onsubmit="return validateForm()">
    <input type="text" name="username" placeholder="Username" required>
    <input type="number" name="email" id="number" placeholder="Number" required>
    <input type="password" name="password" placeholder="Password" required>
    <a href="<?php echo $urlval?>login.php">Already have an account? Login</a>
    <button type="submit">Register</button>
    <div id="error-message" style="color: red; display: none;">Please enter at least 10 digits.</div>
</form>
    </div>

    <script>
    function validateForm() {
        const numberInput = document.getElementById('number').value;
        const errorMessage = document.getElementById('error-message');
        
        if (numberInput.length < 10) {
            errorMessage.style.display = 'block';
            return false; // Prevent form submission
        } else {
            errorMessage.style.display = 'none';
            return true; // Allow form submission
        }
    }
</script>
</body>
</html>
