<?php
require_once 'fun_connect2.php';
require_once 'JSON.php';
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

$a_json = array();
$a_json_row = array();
//$hotelcode=json_decode($_GET['hotelCodes']);
$sql = "SELECT * FROM `question_bank` where `question_category`='".$_GET['category']."'";
$rs = mysqli_query($con, $sql);
while($row = mysqli_fetch_array($rs)) {
	$a_json_row["q_no"]=$row[0];
	$a_json_row["q_text"]=$row[1];
	$a_json_row["q_cat"]=$row[2];
	$a_json_row["q_order"]=$row[3];
	$a_json_row["q_type"]=$row[4];	
	$a_json_row["q_responseOpt"]=$row[5];
	$a_json_row["q_scale"]=$row[6];
	$a_json_row["q_responseMin"]=$row[7];
	$a_json_row["q_responseMax"]=$row[8];
	$a_json_row["q_respweight"]=$row[9];
	array_push($a_json, $a_json_row);
}
echo $_GET['callback'].json_encode($a_json);
?>