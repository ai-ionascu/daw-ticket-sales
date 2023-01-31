<?php

// $url = getenv('JAWSDB_URL');
// $dbparts = parse_url($url);

if ($_SERVER['HTTP_HOST'] == 'localhost') {
  require_once('config.php');
  $servername = $config['SERVER_NAME'];
  $username = $config['DB_USER'];
  $password = $config['DB_PASS'];
  $db_name = $config['DB_NAME'];
}
else{
  $servername = getenv('freedb_host');
  $username = getenv('freedb_user');
  $password = getenv('freedb_pass');
  $db_name = getenv('freedb_db_name');
}

$con = mysqli_connect($servername, $username, $password, $db_name);
if (mysqli_connect_errno()) {
  echo "DB connection failed: " . mysqli_connect_error();
  exit();
}

mysqli_select_db($con, $db_name);

?>