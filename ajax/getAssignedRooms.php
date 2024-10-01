<?php
include_once('../conn/conn.php');
include_once('../global.php');


$data = json_decode(file_get_contents('php://input'), true);
$attendanceId = $data['userId']; 


$sql = "SELECT room FROM tbl_attendance WHERE tbl_attendance_id = :attendanceId";
$stmt = $conn->prepare($sql);
$stmt->bindValue(':attendanceId', $attendanceId, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

$assignedRooms = [];
foreach ($result as $row) {

    $rooms = explode(',', $row['room']);
    $assignedRooms = array_merge($assignedRooms, $rooms);
}


echo json_encode(['assignedRooms' => $assignedRooms]);
?>