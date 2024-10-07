<?php
session_start(); // Start or resume the session

if (isset($_POST['logout'])) {
    // Clear all session data
    session_unset();
    // Destroy the session
    session_destroy();
    
    // Redirect to a login page or another page after logout
    header("Location: Login.php");
    exit();
}
?>
