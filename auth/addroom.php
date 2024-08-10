<?php
include_once('../global.php');
include_once('../conn/conn.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['userId'] ?? '';
    $roomName = $_POST['roomName'] ?? '';

    // Validate inputs
    if (!empty($userId) && !empty($roomName)) {
        try {
            $stmt = $conn->prepare("UPDATE tbl_attendance 
                                    SET room = :roomName
                                    WHERE tbl_attendance_id = :userId");

            $stmt->bindParam(':roomName', $roomName, PDO::PARAM_STR);
            // $stmt->bindParam(':qrcode', $qrcode, PDO::PARAM_STR);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);

            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Room assigned successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to assign room']);
            }
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid inputs']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
