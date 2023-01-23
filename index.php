<?php
session_start();
$page_title = "Home Page";
include('include/head.php');
include('include/header.php') ;
include(dirname(__FILE__).'/conf/db_con.php');
?>

<h1>Home Page</h1>

<?php
include('include/alerts.php');
include('include/tail.php') 
?>