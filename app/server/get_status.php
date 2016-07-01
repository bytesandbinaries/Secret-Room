<?php
require_once 'fun_connect2.php';
require_once 'JSON.php';
//error_reporting(E_ALL);
ini_set('display_errors', 0);

$a_json = array();
$a_json_row = array();
//$hotelcode=json_decode($_GET['hotelCodes']);
$sql = "SELECT * FROM `response_bank` where `profile_id`=".$_GET['userid'];
$rs = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($rs)) {
	$a_json_row["category_id"]=$row[2];
	$a_json_row["question_id"]=$row[3];
	$a_json_row["timestamp"]=$row[6];
	array_push($a_json, $a_json_row);
}
echo $_GET['callback'].json_encode($a_json);
?>
