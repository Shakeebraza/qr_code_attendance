<?php
include_once('header.php');
$data=$funObject->GetAttendance();
$users=$funObject->GetAllUser();

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
                            <h6 class="mb-4">Testimonial</h6>
                            <div class="owl-carousel testimonial-carousel owl-loaded owl-drag">
                                
                                
                            <div class="owl-stage-outer"><div class="owl-stage" style="transform: translate3d(-2227px, 0px, 0px); transition: 1s; width: 4455px;"><div class="owl-item cloned" style="width: 742.5px;"><div class="testimonial-item text-center">
                                    <img class="img-fluid rounded-circle mx-auto mb-4" src="img/testimonial-1.jpg" style="width: 100px; height: 100px;">
                                    <h5 class="mb-1">Client Name</h5>
                                    <p>Profession</p>
                                    <p class="mb-0">Dolor et eos labore, stet justo sed est sed. Diam sed sed dolor stet amet eirmod eos labore diam</p>
                                </div></div><div class="owl-item cloned" style="width: 742.5px;"><div class="testimonial-item text-center">
                                    <img class="img-fluid rounded-circle mx-auto mb-4" src="img/testimonial-2.jpg" style="width: 100px; height: 100px;">
                                    <h5 class="mb-1">Client Name</h5>
                                    <p>Profession</p>
                                    <p class="mb-0">Dolor et eos labore, stet justo sed est sed. Diam sed sed dolor stet amet eirmod eos labore diam</p>
                                </div></div><div class="owl-item" style="width: 742.5px;"><div class="testimonial-item text-center">
                                    <img class="img-fluid rounded-circle mx-auto mb-4" src="img/testimonial-1.jpg" style="width: 100px; height: 100px;">
                                    <h5 class="mb-1">Client Name</h5>
                                    <p>Profession</p>
                                    <p class="mb-0">Dolor et eos labore, stet justo sed est sed. Diam sed sed dolor stet amet eirmod eos labore diam</p>
                                </div></div><div class="owl-item active" style="width: 742.5px;"><div class="testimonial-item text-center">
                                    <img class="img-fluid rounded-circle mx-auto mb-4" src="img/testimonial-2.jpg" style="width: 100px; height: 100px;">
                                    <h5 class="mb-1">Client Name</h5>
                                    <p>Profession</p>
                                    <p class="mb-0">Dolor et eos labore, stet justo sed est sed. Diam sed sed dolor stet amet eirmod eos labore diam</p>
                                </div></div><div class="owl-item cloned" style="width: 742.5px;"><div class="testimonial-item text-center">
                                    <img class="img-fluid rounded-circle mx-auto mb-4" src="img/testimonial-1.jpg" style="width: 100px; height: 100px;">
                                    <h5 class="mb-1">Client Name</h5>
                                    <p>Profession</p>
                                    <p class="mb-0">Dolor et eos labore, stet justo sed est sed. Diam sed sed dolor stet amet eirmod eos labore diam</p>
                                </div></div><div class="owl-item cloned" style="width: 742.5px;"><div class="testimonial-item text-center">
                                    <img class="img-fluid rounded-circle mx-auto mb-4" src="img/testimonial-2.jpg" style="width: 100px; height: 100px;">
                                    <h5 class="mb-1">Client Name</h5>
                                    <p>Profession</p>
                                    <p class="mb-0">Dolor et eos labore, stet justo sed est sed. Diam sed sed dolor stet amet eirmod eos labore diam</p>
                                </div></div></div></div><div class="owl-nav disabled"><div class="owl-prev">prev</div><div class="owl-next">next</div></div><div class="owl-dots"><div class="owl-dot"><span></span></div><div class="owl-dot active"><span></span></div></div></div>
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