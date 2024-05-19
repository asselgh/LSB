<?php
// Start the session
session_start();

// Unset all session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Redirect the user or perform any other action after destroying the session
// For example:
header("Location: http://lsbsolutions.org");
exit();
?>
