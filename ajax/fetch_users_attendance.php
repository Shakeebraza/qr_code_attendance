<?php
include_once('../global.php');

// Get parameters from POST request
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$date = $_POST['date']==NULL?date("Y/m/d"):$_POST['date'];
$time = $_POST['time'] ?? '';

$result = $funObject->GetUserAttendance($date, $name, $email, $time);

$data = array();
foreach ($result['records'] as $row) {
    $dateTime = new DateTime($row['time_in']);
    $row['date'] = $dateTime->format('Y-m-d');
    $row['time'] = $dateTime->format('H:i:s');

    $row['profile'] = $urlval . $row['profile'];

    $row['action'] = '
        <a href="'.$urlval.'admin/viewuser.php?userid=' . base64_encode(base64_encode($row['id'])). '" class="btn btn-outline-info m-2">View User</a>';

    $data[] = $row;
}

// Return JSON data in the format required by DataTables
echo json_encode(array(
    "recordsTotal" => count($data),
    "recordsFiltered" => count($data),
    "data" => $data
));
?>
