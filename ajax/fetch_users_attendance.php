<?php
include_once('../global.php');


$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$date = $_POST['date'] ?? date("Y-m-d"); 
$time = $_POST['time'] ?? '';


$result = $funObject->GetUserAttendance($date, $name, $email, $time);

$data = array();

foreach ($result['records'] as $row) {
    $dateTime = new DateTime($row['time_in']);
    $row['date'] = $dateTime->format('Y-m-d');
    $row['time'] = $dateTime->format('H:i:s');

    $row['profile'] = $urlval . $row['profile'];

    $row['room'] = $row['room'] == NULL ? "Not set a room" :$row['room'] ;
    $row['action'] = '
    <a href="'.$urlval.'admin/viewuser.php?userid=' . base64_encode(base64_encode($row['id'])). '" class="btn btn-outline-info m-2">View</a>
    <button class="btn btn-outline-warning m-2 open-popup" data-id="' . $row['tbl_attendance_id'] . '">Room</button>';
    $data[] = $row;
}


echo json_encode(array(
    "recordsTotal" => count($data),
    "recordsFiltered" => count($data),
    "data" => $data
));

?>
