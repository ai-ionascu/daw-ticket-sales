<?php

include_once('db_con.php');

session_start();

if (isset($_POST['reg_btn'])){
    $_SESSION['username'] = $_POST['username'];
    $_SESSION['email'] = $_POST['email'];
    $_SESSION['password'] = $_POST['password'];
}

?>