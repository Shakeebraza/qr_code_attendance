<?php
include_once('header.php');
$data=$funObject->GetAttendance();
$users=$funObject->GetAllUser();
$usersdata=$funObject->GetUserAttendance(date('y-m-d'));
?>




        <!-- Content Start -->
        



            <!-- Sale & Revenue Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="row g-4">
                    <div class="col-sm-6 col-xl-6">
                        <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa fa-chart-line fa-3x text-primary"></i>
                            <div class="ms-3">
                                <p class="mb-2">All User</p>
                                <h6 class="mb-0"><?php echo $users['count'] ?></h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-xl-6   ">
                        <div class="bg-secondary rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa fa-chart-bar fa-3x text-primary"></i>
                            <div class="ms-3">
                                <p class="mb-2">Today Attendance</p>
                                <h6 class="mb-0"><?php
                                echo $data['count'];
                                ?></h6>
                            </div>
                        </div>
                    </div>
                   
                </div>
            </div>
            <!-- Sale & Revenue End -->





            <!-- Recent Sales Start -->
            <div class="container-fluid pt-4 px-4">
            <div class="col-sm-12 col-xl-12">
                        <div class="bg-secondary rounded h-100 p-4">
                            <h6 class="mb-4">Present Employe</h6>
                            <div class="owl-carousel testimonial-carousel">
                            <?php
                            if(isset($usersdata['records'])){
                                foreach($usersdata['records'] as $val){
                                    if(!empty($val["profile"])){
                                        $imgurl=$urlval.$val["profile"];
                                        
                                    }else{
                                        $imgurl=$urlval."admin/img/user.jpg";

                                    }
                                    echo '
                                <div class="testimonial-item text-center">
                                    <img class="img-fluid rounded-circle mx-auto mb-4" src="'.$imgurl.'" style="width: 100px; height: 100px;">
                                    <h5 class="mb-1">'.$val["username"].'</h5>
                                    <p>User</p>
                                </div>
                                
                                ';
                                
                            }
                        }
                        ?>
                        </div>  

                        </div>
                    </div>
            </div>
            <!-- Widgets End -->

<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>

 <?php
 include_once('footer.php');
 ?>



</body>

</html>