<div class="content">

            <nav class="navbar navbar-expand bg-secondary navbar-dark sticky-top px-4 py-0">
                <a href="index.html" class="navbar-brand d-flex d-lg-none me-4">
                    <h2 class="text-primary mb-0"><i class="fa fa-user-edit"></i></h2>
                </a>
                <a href="#" class="sidebar-toggler flex-shrink-0">
                    <i class="fa fa-bars"></i>
                </a>
                <div class="navbar-nav align-items-center ms-auto">
               <select id="languageSelector" class="bg-gray-800 text-white border border-gray-700 rounded p-1">
                                <option value="es">Spanish</option>
                                  <option value="en">English</option>
                            </select>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <img class="rounded-circle me-lg-2" src="<?php echo $urlval.$_SESSION['profile']?>" alt="" style="width: 40px; height: 40px;">
                            <span class="d-none d-lg-inline-flex"><?php echo $_SESSION['username']?></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-secondary border-0 rounded-0 rounded-bottom m-0">
                            <a href="<?php echo $urlval?>welcome.php" id="gowebsite" class="dropdown-item"></a>
                            <a href="<?php echo $urlval?>profile.php" id="myprofile" class="dropdown-item"></a>
                            <a href="<?php echo $urlval?>setting.php" id="settings" class="dropdown-item"></a>
                            <a href="<?php echo $urlval?>auth/logout.php" id="logout" class="dropdown-item"></a>
                        </div>
                    </div>
                </div>
            </nav>
            <!-- Navbar End -->