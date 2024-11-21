<?php

ob_start();
session_start();
require 'CLASS/admin.php';
$obj_admin = new appControl();

if (isset($_GET['logout'])) {
    $obj_admin->admin_logout();
}

// Check if 'OTP' session variable is not set
if (!isset($_SESSION['OTP'])) {
    // Output JavaScript code to redirect back
    echo '<script>
    // JavaScript code to redirect back
    function redirectBack() {
        history.back();
    }
    // Call the function to redirect back
    redirectBack();
    </script>';
}