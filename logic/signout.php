<?php

session_start();
unset($_SESSION['username']);
unset($_SESSION['email']);
unset($_SESSION['role']);
$_SESSION['success'] = 'You have logged out successfully.';
header('Location: ../index.php');

?>