<?php
require_once('config.php');

$url = getenv('CLEARDB_DATABASE_URL');
$dbparts = parse_url($url);

if ($_SERVER['HTTP_HOST'] == 'localhost') {
  $servername = $config['SERVER_NAME'];
  $username = $config['DB_USER'];
  $password = $config['DB_PASS'];
  $db_name = $config['DB_NAME'];
}
else{
  $servername = $dbparts['host'];
  $username = $dbparts['user'];
  $password = $dbparts['pass'];
  $db_name = ltrim($dbparts['path'],'/');
}

$con = mysqli_connect($servername, $username, $password, $db_name);
if (mysqli_connect_errno()) {
  echo "DB connection failed: " . mysqli_connect_error();
  exit();
}

mysqli_select_db($con, $db_name);

?>