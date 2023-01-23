<?php
session_start();
$page_title = "Home Page";
include('include/head.php');
include('include/header.php') ;
include(dirname(__FILE__).'/conf/db_con.php');
?>

<h1>Home Page</h1>

<?php
$users_query = 'SELECT * FROM users';
$users_query_run = mysqli_query($con, $users_query);
if ($users_query_run){
    foreach($users_query_run as $user){
        print_r($user);
    }
}
else{
    echo "Query error.";
}
include('include/alerts.php');
include('include/tail.php') 
?>