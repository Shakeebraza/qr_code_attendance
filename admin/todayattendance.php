<?php
include_once('header.php');
// $todayDate = date('Y-m-d');
$data = json_decode($funObject->GetTodayAttendance(), true);
<<<<<<< HEAD
if (!isset($_SESSION['type']) || $_SESSION['type'] != ADMIN_USER_ID) {
=======
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != ADMIN_USER_ID) {
>>>>>>> 7ff2ec5aa10efce709929748f8c1223c10428c95
    header('Location: ../login.php');
    exit();
}
?>
<style>
    
     
    
</style>

<div class="container-fluid pt-4 px-4">
    <div class="col-12">
        <div class="bg-secondary rounded h-100 p-4">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Admin</a></li>
                    <li class="breadcrumb-item active" aria-current="page">All Attendance</li>
                </ol>
            </nav>

            <h6 class="mb-4">All Attendance</h6>

            <div class="table-responsive">
                <table id="attendance-table" class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Work Type</th>
                            <th scope="col">Room</th>
                            <th scope="col">Date</th>
                            <th scope="col">Time</th>
                    
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($data && $data['count'] > 0) {
                            foreach ($data['records'] as $key => $val) {
                                // Extract date and time from the time_in field
                                $date = date('d-M-Y', strtotime($val['time_in']));
                                $time = date('h:i A', strtotime($val['time_in']));
                                $room = empty($val['room']) ? "Waiting" : $val['room'];
                               $worktype = empty($val['worktype']) ? "Not Select" : $val['worktype'];
                                echo '
                                <tr>
                                    <th scope="row">'.($key + 1).'</th>
                                    <td>'.$val['username'].'</td>
                                    <td>'.$val['email'].'</td>
                                    <td>'.$worktype.'</td>
                                    <td>'.$room.'</td>
                                    <td>'.$date.'</td>
                                    <td>'.$time.'</td>

                                </tr>';
                            }
                        } else {
                            echo '
                            <tr>
                                <td colspan="7">No records found for today.</td>
                            </tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



 <?php
 include_once('footer.php');
 ?>

</body>

</html>