<?php
require_once 'fun_connect2.php';
require_once 'JSON.php';
//error_reporting(E_ALL);
ini_set('display_errors', 1);

$a_json = array();
$a_json_row = array();
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$t=time();
$p_id=$request->user->id;
if($p_id==0){
	$pass=md5($request->user->password);
	$sql = "insert into `profile` values (NULL, '".mysqli_real_escape_string($con, $request->user->email)."', '".$pass."', '".mysqli_real_escape_string($con, $request->user->name)."', '".mysqli_real_escape_string($con, $request->user->gender)."', '".mysqli_real_escape_string($con, $request->user->age)."', '".mysqli_real_escape_string($con, $request->user->height)."', '".$t."')";
	$rs = mysqli_query($con, $sql) ;
	$p_id = mysqli_insert_id($con);
}

foreach($request->response as $resp){
	if(!is_array($resp->value)){
		$sqlr="insert into `response_bank` values (NULL, '".$p_id."', '".mysqli_real_escape_string($con, $resp->cat_id)."', '".mysqli_real_escape_string($con, $resp->id)."', '".mysqli_real_escape_string($con, $resp->value)."', '".mysqli_real_escape_string($con, $resp->value)."', '".$t."')";
	$rsr = mysqli_query($con, $sqlr) ;
	}
	else{
		for($r=0; $r<count($resp->value); $r++){
			$sqlr="insert into `response_bank` values (NULL, '".$p_id."', '".mysqli_real_escape_string($con, $resp->cat_id)."', '".mysqli_real_escape_string($con, $resp->id)."', '".mysqli_real_escape_string($con, $resp->value[$r]->item)."', '".mysqli_real_escape_string($con, $resp->value[$r]->value)."', '".$t."')";
			$rsr = mysqli_query($con, $sqlr) ;
		}
	}
}

echo $_GET['callback'].json_encode($p_id);
?>