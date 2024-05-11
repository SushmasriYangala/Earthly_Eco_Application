<?php
session_start();

// Unset all of the session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Expire the session cookie by setting its lifetime to 0
setcookie(session_name(), '', 0, '/');

// Redirect to the login page
header("location: ../Home.html");
exit;
?>
