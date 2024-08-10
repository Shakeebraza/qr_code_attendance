<?php
include_once('global.php');

// Check if the user session is active
$res = $funObject->checksession();
if ($res == 0) {
    header('Location: login.php');
    exit(); 
}

// Check if the user is an admin
$chkIsAdmin = $funObject->isAdmin();
?>



<nav class="bg-black py-3 px-4 text-white">
    <div class="container mx-auto px-4 flex justify-between items-center">
        <a class="text-xl font-bold" href="#">Norajokaraoke QR Code Attendance System</a>
        <button class="text-white focus:outline-none md:hidden" id="drawerButton">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
        <div class="hidden md:flex space-x-4">
            <!-- Navigation links for larger screens -->
            <a href="./welcome.php" class="hover:text-gray-300">Home</a>
            <?php
            if ($chkIsAdmin == 1 || $chkIsAdmin == 2) {
                echo '<a href="./masterlist.php" class="hover:text-gray-300">List of Employees</a>';
            } else {
                echo '<a href="./empmasterlist.php" class="hover:text-gray-300">Employees</a>';
            }
            if ($chkIsAdmin == 1 || $chkIsAdmin == 2) {
                echo '<a href="' . $urlval . 'admin/index.php" class="hover:text-gray-300">Admin Panel</a>';
            }
            ?>
            <div class="relative">
                <button id="dropdownButton" class="flex items-center space-x-2 focus:outline-none">
                    <?php
                    if (isset($_SESSION['profile']) && !empty($_SESSION['profile'])) {
                        echo '<img src="' . $_SESSION['profile'] . '" alt="Profile Image" class="rounded-full w-8 h-8">';
                    } else {
                        echo '<i class="fa fa-user"></i> Profile';
                    }
                    ?>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-48 bg-white text-gray-800 shadow-lg rounded-lg">
                    <a class="block px-4 py-2 hover:bg-gray-100" href="profile.php">View Profile</a>
                    <a class="block px-4 py-2 hover:bg-gray-100" href="settings.php">Settings</a>
                    <div class="border-t my-2"></div>
                    <a class="block px-4 py-2 hover:bg-gray-100" href="auth/logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Drawer menu for mobile -->
    <div id="drawerMenu" class="fixed inset-0 z-50 bg-gray-800 bg-opacity-75 transform translate-x-full transition-transform duration-300 ease-in-out md:hidden">
        <div class="w-64 bg-gray-900 h-full p-4">
            <button id="closeDrawer" class="text-white mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <ul class="space-y-4">
                <li><a href="./welcome.php" class="block text-white hover:text-gray-300">Home</a></li>
                <?php
                if ($chkIsAdmin == 1 || $chkIsAdmin == 2) {
                    echo '<li><a href="./masterlist.php" class="block text-white hover:text-gray-300">List of Employees</a></li>';
                } else {
                    echo '<li><a href="./empmasterlist.php" class="block text-white hover:text-gray-300">Employees</a></li>';
                }
                if ($chkIsAdmin == 1 || $chkIsAdmin == 2) {
                    echo '<li><a href="' . $urlval . 'admin/index.php" class="block text-white hover:text-gray-300">Admin Panel</a></li>';
                }
                ?>
                <li><a href="profile.php" class="block text-white hover:text-gray-300">View Profile</a></li>
                <li><a href="settings.php" class="block text-white hover:text-gray-300">Settings</a></li>
                <li><a href="auth/logout.php" class="block text-white hover:text-gray-300">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>



