<?php
include_once('global.php');
$res = $funObject->checksession();
if ($res == 0) {
    header('Location: login.php');
    exit(); 
}
$chkIsAdmin = $funObject->isAdmin();
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand ml-4" href="#">QR Code Attendance System</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="./welcome.php">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="./masterlist.php">List of Employees</a>
            </li>
            <?php
            if($chkIsAdmin == 1 || $chkIsAdmin == 2){
            ?>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $urlval?>admin/index.html">Go to Admin Panel</a>
            </li>
            <?php
            }
            ?>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <?php
                if(isset($_SESSION['profile']) && !empty($_SESSION['profile'])){
                    echo '<img src="'.$_SESSION['profile'].'" alt="Profile Image" class="rounded-circle" width="30" height="30"> Profile';
                }else{
                    echo '<i class="fa fa-user"></i> Profile';

                }
                ?>
                
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="profile.php">View Profile</a>
                    <a class="dropdown-item" href="settings.php">Settings</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="auth/logout.php">Logout</a>
                </div>
            </li>
        </ul>
    </div>
</nav>
