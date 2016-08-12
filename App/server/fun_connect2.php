<?php
header('Access-Control-Allow-Origin: *');
	//offline
	// $host = "localhost";
	// $username = "root";
	// $password = "";
	// $database = "secretroom";
	// $con=mysqli_connect($host,$username,$password, $database);

	//online
	$host = "localhost";
	$username = "wwwbytes_secretr";
	$password = "secretroom";
	$database = "wwwbytes_secretroom";
	$con=mysqli_connect($host,$username,$password, $database);

?>
