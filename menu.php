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
<style>
    #drawerMenu {
    transform: translateX(100%); /* Drawer is hidden by default */
    transition: transform 0.3s ease-in-out;
}

#drawerMenu.open {
    transform: translateX(0); /* Drawer is visible when 'open' class is added */
}

.goog-te-gadget-simple img{
    display: none;
}
.goog-te-gadget-simple span{
    color: white;
    padding: 5px;
    background: black;
    border: none;
    
} 
.goog-te-gadget-simple span:nth-child(3){
    display: none;
}


</style>
<nav class="bg-black py-3 px-4 text-white">
    <div class="container mx-auto px-4 flex justify-between items-center">
        <a class="text-xl font-bold" href="#" id="welcome">Norajokaraoke QR Code Attendance System</a>
        <button class="text-white focus:outline-none md:hidden" id="drawerButton">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
        <div class="hidden md:flex space-x-4">
            <?php if($_SESSION['verified'] != 0): ?>
            <a href="./welcome.php" class="hover:text-gray-300 home">Home</a>
            <?php endif;?>
            <?php
            if ($chkIsAdmin == 1 || $chkIsAdmin == 2) {
                // echo '<a href="./masterlist.php" class="hover:text-gray-300">List of Employees</a>';
                echo '<a href="./empmasterlist.php" class="hover:text-gray-300 qrCode">QR Code</a>';
            } else {
                echo '<a href="./empmasterlist.php" class="hover:text-gray-300 qrCode">QR Code</a>';
            }
            if ($chkIsAdmin == 1 || $chkIsAdmin == 2) {
                echo '<a href="' . $urlval . 'admin/index.php" class="hover:text-gray-300 adminPanel">Admin Panel</a>';
            }
            ?>
                    <select id="languageSelector" class="bg-gray-800 text-white border border-gray-700 rounded p-1">
                    <option value="es">Spanish</option>
                      <option value="en">English</option>
                </select>
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
                    <a class="block px-4 py-2 hover:bg-gray-100 viewProfile" href="profile.php">View Profile</a>
                    <a class="block px-4 py-2 hover:bg-gray-100 settings" href="settings.php">Settings</a>
                    <a class="block px-4 py-2 hover:bg-gray-100 gallery" href="gallery.php">Gellery</a>
                    <div class="border-t my-2"></div>
                    
                    <a class="block px-4 py-2 hover:bg-gray-100 logout" href="auth/logout.php">Logout</a>
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
                   <select id="mobilelanguageSelector" class="bg-gray-800 text-white border border-gray-700 rounded p-1">
                    <option value="es">Español</option>
                      <option value="en">English</option>
                </select>
                <li><a href="./welcome.php" class="block text-white hover:text-gray-300 home">Home</a></li>
                <?php
                if ($chkIsAdmin == 1 || $chkIsAdmin == 2) {
                    //echo '<li><a href="./masterlist.php" class="block text-white hover:text-gray-300">List of Employees</a></li>';
                     echo '<li><a href="./empmasterlist.php" class="block text-white hover:text-gray-300 qrCode">QR Code</a></li>';
                } else {
                    echo '<li><a href="./empmasterlist.php" class="block text-white hover:text-gray-300 qrCode">QR Code</a></li>';
                }
                if ($chkIsAdmin == 1 || $chkIsAdmin == 2) {
                    echo '<li><a href="' . $urlval . 'admin/index.php" class="block text-white hover:text-gray-300 adminPanel">Admin Panel</a></li>';
                }
                ?>
             
                <li><a href="profile.php" class="block text-white hover:text-gray-300 viewProfile">View Profile</a></li>
                <li><a href="settings.php" class="block text-white hover:text-gray-300 settings">Settings</a></li>
                <li><a href="gallery.php" class="block text-white hover:text-gray-300 gallery">Gallery</a></li>
                <li><a href="auth/logout.php" class="block text-white hover:text-gray-300 logout">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- JavaScript for drawer and dropdown -->
<script>
// Drawer menu script
document.addEventListener('DOMContentLoaded', function() {
    const drawerButton = document.getElementById('drawerButton');
    const drawerMenu = document.getElementById('drawerMenu');
    const closeDrawer = document.getElementById('closeDrawer');

    // Toggle the drawer menu
    drawerButton.addEventListener('click', () => {
        drawerMenu.classList.toggle('open');
    });

    // Close the drawer menu
    closeDrawer.addEventListener('click', () => {
        drawerMenu.classList.remove('open');
    });

    // Close drawer if clicked outside
    document.addEventListener('click', function(event) {
        if (!drawerMenu.contains(event.target) && !drawerButton.contains(event.target)) {
            drawerMenu.classList.remove('open');
        }
    });
});

// Dropdown menu script
document.addEventListener('DOMContentLoaded', function() {
    const dropdownButton = document.getElementById('dropdownButton');
    const dropdownMenu = document.getElementById('dropdownMenu');

    dropdownButton.addEventListener('click', function() {
        dropdownMenu.classList.toggle('hidden');
    });

    document.addEventListener('click', function(event) {
        if (!dropdownButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
            dropdownMenu.classList.add('hidden');
        }
    });
});
</script>
