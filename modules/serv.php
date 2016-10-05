<?php

// SERVER CONNECTION

$server = '';
$user = '';
$pass = '';
$dbname = '';

$conn = mysqli_connect($server, $user, $pass);
mysqli_select_db($conn, $dbname);
mysqli_query($conn, "SET NAMES UTF8");

?>