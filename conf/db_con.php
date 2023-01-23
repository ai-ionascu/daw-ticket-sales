<?php
require_once('config.php');

$url = getenv('JAWSDB_URL');
$dbparts = parse_url($url);

$servername = $dbparts['host'];
$username = $dbparts['user'];
$password = $dbparts['pass'];
$db_name = ltrim($dbparts['path'],'/');

// $servername = $config["SERVER_NAME"];
// $username = $config["DB_USER"];
// $password = $config["DB_PASS"];
// $db_name = $config["DB_NAME"];

$con = mysqli_connect($servername, $username, $password, $db_name);
if (mysqli_connect_errno()) {
  echo "DB connection failed: " . mysqli_connect_error();
  exit();
}

// mysqli_select_db($con, "ticket_sales");
mysqli_select_db($con, "t83g94b0z25s43zs");

?>