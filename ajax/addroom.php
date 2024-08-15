<?php
include_once('../conn/conn.php');

// Get POST data
$name = $_POST['name'] ?? '';
$enable = isset($_POST['enable']) ? 1 : 0;

// Validate input
if (empty($name)) {
    echo json_encode(['status' => 'error', 'message' => 'Room name is required']);
    exit;
}

// Prepare the SQL query
$query = "INSERT INTO rooms (name, enabled) VALUES (:name, :enable)";
$statement = $conn->prepare($query);

// Bind parameters
$statement->bindParam(':name', $name);
$statement->bindParam(':enable', $enable, PDO::PARAM_INT);

try {
    // Execute the prepared statement
    $statement->execute();
    echo json_encode(['status' => 'success']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to add room: ' . $e->getMessage()]);
}
?>
