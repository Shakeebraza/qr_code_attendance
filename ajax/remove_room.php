<?php
include_once('../global.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $attendanceId = $_POST['tbl_attendance_id'] ?? null;

    if ($attendanceId) {
        try {
            $stmt = $conn->prepare("UPDATE tbl_attendance SET room = NULL WHERE tbl_attendance_id = :attendanceId");
            $stmt->bindParam(':attendanceId', $attendanceId, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                echo json_encode(['success' => true, 'message' => 'La habitación se eliminó correctamente.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'No se eliminó ninguna habitación (posiblemente ya sea nula).']);
            }
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Database error occurred.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid attendance ID.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método de solicitud no válido.']);
}
