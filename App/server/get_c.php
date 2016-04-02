<?php
require_once 'fun_connect2.php';
require_once 'JSON.php';
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

$a_json = array();
$a_json_row = array();
//$hotelcode=json_decode($_GET['hotelCodes']);
$sql = "SELECT * FROM `question_category`";
$rs = mysqli_query($con,$sql);
while($row = mysqli_fetch_array($rs)) {
	$a_json_row["title"]=$row[1];
	$a_json_row["description"]=$row[2];
	$a_json_row["all_questions"]=array();
	$q=array();

	$sqlq = "SELECT * FROM `question_bank` where `question_category`='".$row[0]."'";
	$rsq = mysqli_query($con, $sqlq);
	while($row = mysqli_fetch_array($rsq)) {
		$q["q_no"]=$row[0];
		$q["q_text"]=$row[1];
		$q["q_cat"]=$row[2];
		$q["q_order"]=$row[3];
		$q["q_type"]=$row[4];
		$q["q_responseOpt"]=$row[5];
		$q["q_scale"]=$row[6];
		$q["q_responseMin"]=$row[7];
		$q["q_responseMax"]=$row[8];
		$q["q_respweight"]=$row[9];
		array_push($a_json_row["all_questions"], $q);
	}
	array_push($a_json, $a_json_row);
}
echo $_GET['callback'].json_encode($a_json);
?>
