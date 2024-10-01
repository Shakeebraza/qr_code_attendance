<?php

include_once("conn/conn.php");
require 'fun/fun.php';
$funObject = new Fun($conn);

$urlval="http://localhost/citaa/";


define('ADMIN_USER_ID', 2);
<<<<<<< HEAD
date_default_timezone_set('America/Monterrey');
// date_default_timezone_set('Asia/Karachi');
$todayDate=date('Y-m-d');
$todayDateTime=date('Y-m-d H:i:s');
$todaytime = date('H:i:s');
$cureenttime = date('H:i:');
=======
date_default_timezone_set('Asia/Karachi');
>>>>>>> 7ff2ec5aa10efce709929748f8c1223c10428c95
?>
