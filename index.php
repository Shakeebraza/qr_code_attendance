
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
        <h1>Attendance Management System</h1>
      
        </header>
        <nav>
            <a href="<?php echo $urlval ?>singup.php">Singup</a>
            <a href="<?php echo $urlval ?>login.php">Login</a>
    </nav>
    <div class="container">
        <h2>Welcome to the Attendance Management System</h2>
        <p>Manage your attendance efficiently with our system.</p>
    </div>
    <footer>
        <p>Created by Fission Fox</p>
    </footer>
</body>
</html>
