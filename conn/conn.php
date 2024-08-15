<?php
$servername = "localhost";
$username = "u227020428_karaokesystem";
$password = "Karaokesystem*009";
$dbname = "u227020428_karaokesystem";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

?>
