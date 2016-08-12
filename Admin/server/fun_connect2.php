<?php
header('Access-Control-Allow-Origin: *');
$host = "localhost";
// $username = "root";
// $password = "root";
// $database = "secretroom";
$username = "wwwbytes_secretr";
$password = "secretroom";
$database = "wwwbytes_secretroom";
$con=mysqli_connect($host,$username,$password, $database);

?>
