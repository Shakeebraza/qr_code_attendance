<?php
include_once('../global.php');


$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$time = $_POST['time'] ?? '';

$room = $_POST['room'] ?? NULL;
$worktype = $_POST['worktype'] ?? NULL;
$startTime = '00:00'; 
$endTime = new DateTime('16:00');   


if ($_POST['date'] == "") {
    if ($cureenttime  >= $startTime && $cureenttime  <= $endTime) {
      
        $date = (new DateTime())->modify('-1 day')->format('Y-m-d');
    } else {
       
        $date = $todayDate;
    }
} else {
   
    $date = $_POST['date'];
}

$order_column = $_POST['order_column'] ?? 'username'; 
$order_dir = $_POST['order_dir'] ?? 'asc'; 

$result = $funObject->GetUserAttendance($date, $name, $email, $time, $order_column, $order_dir,$room,$worktype,$isSetrRoom="setdata");

// $result = $funObject->GetUserAttendance($date, $name, $email, $time);

$data = array();

foreach ($result['records'] as $row) {
<<<<<<< HEAD
    $datetime = $row['time_in'];
    $correctedDateTimeString = $funObject->getCorrectedDateTime($datetime);
    
    
    $correctedDateTime = new DateTime($correctedDateTimeString);

    $row['date'] = $correctedDateTime->format('d-M-Y'); 
    $row['time'] = $correctedDateTime->format('h:i A');

  
    $row['action'] = "";
=======
    $dateTime = new DateTime($row['time_in']);
    $row['date'] = $dateTime->format('d-M-Y'); 
    $row['time'] = $dateTime->format('h:i A'); 
    $row['action']="";
>>>>>>> 7ff2ec5aa10efce709929748f8c1223c10428c95

    $row['profile'] = $urlval . $row['profile'];
    if($_SESSION['type'] == 2){
    $row['email'];
    }else{
        $row['email']="Not Permission Yet";  
    }

<<<<<<< HEAD
    if ($_SESSION['type'] == 2) {
        $row['email'] = $row['email']; 
    } else {
        $row['email'] = "Not Permission Yet";  
    }
=======
    if($_SESSION['type'] == 2){
        $row['username'];
        }else{
            $row['username']= $row['work_name'] == NULL? "Work name not set":$row['work_name'] ;  
        }
>>>>>>> 7ff2ec5aa10efce709929748f8c1223c10428c95

    if ($_SESSION['type'] == 2) {
        $row['username'] = $row['username']; 
    } else {
        $row['username'] = $row['work_name'] === NULL ? "Work name not set" : $row['work_name'];  
    }

<<<<<<< HEAD
    $row['room'] = $row['room'] === NULL ? "No establecer una habitación" : $row['room'];
    $row['worktype'] = $row['worktype'] === NULL ? "Tipo de trabajo no seleccionado" : $row['worktype'];

    if ($_SESSION['type'] == 2) {
        $row['action'] .= '
            <a href="' . $urlval . 'admin/viewuser.php?userid=' . base64_encode(base64_encode($row['id'])) . '" class="btn btn-outline-info m-2">View</a>';
=======
    if($_SESSION['type'] == 2){
    $row['action'] .= '
        <a href="'.$urlval.'admin/viewuser.php?userid=' . base64_encode(base64_encode($row['id'])) . '" class="btn btn-outline-info m-2">View</a>';
>>>>>>> 7ff2ec5aa10efce709929748f8c1223c10428c95
    }
    if ($row['worktype'] !== "no_work") {
        $row['action'] .= '<button class="btn btn-outline-warning m-2 open-popup" data-id="' . $row['tbl_attendance_id'] . '">Room</button>';
    }

    $data[] = $row; 
}

echo json_encode(array(
    "recordsTotal" => count($data),
    "recordsFiltered" => count($data),
    "data" => $data
));
?>




