<?php

session_start();
unset($_SESSION['username']);
$_SESSION['success'] = 'You have logged out successfully.';
header('Location: ../index.php');

?>