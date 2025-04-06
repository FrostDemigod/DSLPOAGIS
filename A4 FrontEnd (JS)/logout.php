<?php
session_start();
session_unset(); // Clear all session variables
session_destroy(); // Destroy the session
header("Location: ../main.html"); // Redirect to the main page or any other page
exit;
?>