<?php
session_start(); // Start the session

// Unset all of the session variables
$_SESSION = array();

// Destroy the session
session_destroy();

if (isset($_GET['auto'])) {
    header("Location: ../../pages/LoginPage.php?msg=For security reasons, you have been logged out. Please log in again to continue.");
} else {

    header("Location: ../../pages/LoginPage.php");
}
// Redirect to the login page or any other page after logout
exit; // Stop further execution
