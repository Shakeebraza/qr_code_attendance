<?php

session_start();

class Fun {

    // Function to check if the 'username' session variable is set
    public function checksession() {
        return isset($_SESSION['username']) ? 1 : 0;
    }

    // Function to check user type and return appropriate value
    public function isAdmin() {
        // Check if session is set
        if ($this->checksession() == 1) {
            // Check the type of user
            if (isset($_SESSION['type'])) {
                if ($_SESSION['type'] == 1) {
                    return 1; // Admin type 1
                } elseif ($_SESSION['type'] == 2) {
                    return 2; // Admin type 2
                }
            }
        }
        // Return 0 if session is not set or type is not valid
        return 0;
    }
}
?>
