<?php
session_start();

// Destroy admin session
session_destroy();

// Redirect to home
header('Location: login.php');
exit();
?>
