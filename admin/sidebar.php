        <!-- Sidebar Start -->
        <div class="sidebar pe-4 pb-3">
            <nav class="navbar bg-secondary navbar-dark">
                <a href="index.php" class="navbar-brand mx-4 mb-3">
                    <h3 class="text-primary"></i>Norajokaraoke</h3>
                </a>
                <div class="d-flex align-items-center ms-4 mb-4">
                    <div class="position-relative">
                        <img class="rounded-circle" src="<?php echo $urlval.$_SESSION['profile']?>" alt="" style="width: 40px; height: 40px;">
                        <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0"><?php echo $_SESSION['username']?></h6>
                        <span id="useradmin">Admin</span>
                    </div>
                </div>
                <div class="navbar-nav w-100">
                    <a href="index.php" class="nav-item nav-link active"><i class="fa fa-tachometer-alt me-2"></i><span id="dashboard">Dashboard</span></a>
                    <a href="attendance.php" class="nav-item nav-link"><i class="fa fa fa-chart-bar me-2"></i><span id="todayattendance">Today Attendance</span></a>
                    <a href="disponible.php" class="nav-item nav-link"><i class="fas fa-yin-yang"></i><span id="disponible">Disponible</span></a>
                    <?php
                    if($_SESSION['type'] == ADMIN_USER_ID){
                       echo '<a href="filterattendance.php" class="nav-item nav-link"><i class="fa fa fa-chart-bar me-2"></i><span id="filterattendance">Filter Attendance</span></a>'; 
                    }
                    ?>
                     
                    <a href="qrtoday.php" class="nav-item nav-link"><i class="fa fa-qrcode me-2"></i><span id="qrtoday">QR Today</span></a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-users me-2"></i><span id="Users">Users</span></a>
                        <div class="dropdown-menu bg-transparent border-0">
                            <a href="user.php" class="dropdown-item"><span id="alluser">All Users</span></a>
                            <a href="useradd.php" class="dropdown-item"><span id="adduser">Add user</span></a>
                        </div>
                    </div>
                    <!--<div class="nav-item dropdown">-->
                    <!--    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa fa-chart-bar me-2"></i>Attendance</a>-->
                    <!--    <div class="dropdown-menu bg-transparent border-0">-->
                                    
                                    
                                    <?php
                                    // if (!isset($_SESSION['type']) || $_SESSION['type'] == ADMIN_USER_ID) {
                                    //     echo '
                                    //     <a href="attendance.php" class="dropdown-item">Today Attendance</a>
                                    //     <a href="todayattendance.php" class="dropdown-item">Attendance</a>
                                    //     <a href="filterattendance.php" class="dropdown-item">Filter Attendance</a>
                                    //     ';
                                    // }else{
                                    //     echo '
                                    //     <a href="attendance.php" class="dropdown-item">Today Attendance</a>
                                    //     ';
                                    // }
                                    ?>
                                    
                    <!--    </div>-->
                    <!--</div>-->
<?php if($_SESSION['type'] == ADMIN_USER_ID):?>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-object-ungroup me-2"></i><span id="room">Room</span></a>
                        <div class="dropdown-menu bg-transparent border-0">
                            <a href="room.php" class="dropdown-item"><span id="addroom">All Room</span></a>
                            <a href="roomadd.php" class="dropdown-item"><span id="allroom">Add Room</span></a>
                        </div>
                    </div>
<?php endif;?>
              
                </div>
            </nav>
        </div>
 