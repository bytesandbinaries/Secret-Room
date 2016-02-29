<?php
require_once 'fun_connect2.php';
require_once 'JSON.php';
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

$a_json = array();
$a_json_row = array();
//$hotelcode=json_decode($_GET['hotelCodes']);
$sql = "SELECT * FROM `question_category` where `category_id`='".$_GET['category']."'";
$rs = mysql_query($sql);
while($row = mysql_fetch_array($rs)) {
	$a_json_row["title"]=$row[1];
	$a_json_row["description"]=$row[2];
	array_push($a_json, $a_json_row);
}
echo $_GET['callback'].json_encode($a_json);
?>
