<?php
    session_start();
    session_unset(); // Unsets session varibles
    session_destroy(); // Terminates Session
    header("Location: index.php"); // Redirects user to login screen
?>