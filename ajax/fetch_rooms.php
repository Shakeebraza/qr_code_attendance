<?php
include_once('../conn/conn.php');
include_once('../global.php');

// Fetch all rooms from the database
$query = "SELECT * FROM rooms";
$statement = $conn->prepare($query);
$statement->execute();
$rooms = $statement->fetchAll(PDO::FETCH_ASSOC);

// Loop through each room and add action buttons
foreach ($rooms as &$room) {
    $room['action'] = '
        <a href="'.$urlval.'admin/editroom.php?roomid=' . base64_encode(base64_encode($room['id'])) . '" class="btn btn-outline-success m-2" data-id="' . $room['id'] . '">Edit</a>
        <button type="button" class="btn btn-outline-primary m-2 delete-btn" data-id="' . $room['id'] . '">Delete</button>';
}

// Return the results as JSON
echo json_encode([
    'data' => $rooms
]);
?>

