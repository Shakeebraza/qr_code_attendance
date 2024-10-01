<?php
session_start(); // Start the session

$host = 'localhost'; // Database host
$dbname = 'u227020428_karaokesystem'; // Database name
$user = 'u227020428_karaokesystem'; // Database username
$pass = 'Karaokesystem*009'; // Database password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}

$action = $_POST['action'] ?? null;
$userId = $_POST['userId'] ?? null;

if ($action == 'load' && $userId) {
    // Fetch notes from the database
    $query = "SELECT * FROM user_notes WHERE user_id = :userId ORDER BY created_at DESC";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['userId' => $userId]);
    $notes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($notes); // Return notes as JSON
}

if ($action == 'add' && $userId) {
    $noteText = $_POST['noteText'] ?? '';
    $createdBy = $_SESSION['username'] ?? 'Anonymous';

    if ($noteText) {
        $query = "INSERT INTO user_notes (user_id, note_text, created_at, created_by) VALUES (:userId, :noteText, NOW(), :createdBy)";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['userId' => $userId, 'noteText' => $noteText, 'createdBy' => $createdBy]);

        echo json_encode(['message' => 'Note added successfully']);
    } else {
        echo json_encode(['error' => 'Note text cannot be empty']);
    }
}

if ($action == 'edit' && $userId) {
    $noteId = $_POST['noteId'] ?? null;
    $noteText = $_POST['noteText'] ?? '';

    if ($noteId && $noteText) {
        $query = "UPDATE user_notes SET note_text = :noteText WHERE note_id = :noteId";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['noteText' => $noteText, 'noteId' => $noteId]);

        echo json_encode(['message' => 'Note updated successfully']);
    } else {
        echo json_encode(['error' => 'Note ID and text cannot be empty']);
    }
}



  
?>
