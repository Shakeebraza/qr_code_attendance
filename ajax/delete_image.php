<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once('../conn/conn.php'); // Make sure the connection is using PDO

if (isset($_POST['imid'])) {
    $imid = intval($_POST['imid']);

    // Prepare and execute the query to delete the image using PDO
    $sql = "DELETE FROM gallery WHERE imid = :imid";
    $stmt = $conn->prepare($sql);

    // Check if the statement was prepared correctly
    if (!$stmt) {
        echo json_encode(['success' => false, 'error' => 'SQL Prepare Error: ' . $conn->errorInfo()[2]]);
        exit();
    }

    // Bind the parameter with PDO (using bindParam or bindValue)
    $stmt->bindParam(':imid', $imid, PDO::PARAM_INT);

    // Execute the statement and check if it succeeds
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->errorInfo()[2]]);
    }

    // Close the connection
    $stmt = null;
    $conn = null;
}
?>
