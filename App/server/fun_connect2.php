<?php
header('Access-Control-Allow-Origin: *');
	$host = "localhost";
	$username = "root";
	$password = "root";
	$database = "secretroom";
	$con=mysqli_connect($host,$username,$password, $database);
	//@ mysql_pconnect($host,$username,$password);
	//$connection=mysql_select_db($database);
	//$dbh = new PDO('mysql:host=173.254.2.163:3306;dbname=getcentr_getcentre', $username, $password);

?>
