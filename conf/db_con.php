<?php
require_once('config.php');
$servername = $config["SERVER_NAME"];
$username = $config["DB_USER"];
$password = $config["DB_PASS"];
$db_name = $config["DB_NAME"];
$con = mysqli_connect($servername, $username, $password, $db_name);
if (mysqli_connect_errno()) {
  echo "DB connection failed: " . mysqli_connect_error();
  exit();
}

mysqli_select_db($con, "ticket_sales");
?>