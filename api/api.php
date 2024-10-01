<?php
header("Content-Type: application/json");

// Database configuration
$host = 'localhost'; // Change this to your database host
$dbname = 'u227020428_karaokesystem'; // Change this to your database name
$user = 'u227020428_karaokesystem'; // Change this to your database username
$pass = 'Karaokesystem*009'; // Change this to your database password




try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare and execute the query
    $stmt = $pdo->query("SELECT project_name, expiration_date FROM projects");

    // Fetch all results
    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return JSON response
    echo json_encode($projects);

} catch (PDOException $e) {
    // Handle database connection errors
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
}
?>
