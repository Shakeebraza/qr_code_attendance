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
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);

            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Habitaciones asignadas correctamente']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No se pudo asignar la(s) habitación(es)']);
            }
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Entradas no válidas']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método de solicitud no válido']);
}
?>

