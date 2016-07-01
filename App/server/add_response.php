<?php
//header('Access-Control-Allow-Origin: *');
require_once 'fun_connect2.php';
require_once 'JSON.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

$a_json = array();
$a_json_row = array();
$newupdate=array();
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
print_r($request);
$t=time();
$p_id=$request->user->userID;
if($p_id==0){
	$pass=md5($request->user->password);
	$sql = "insert into `profile` values (NULL, '".mysqli_real_escape_string($con, $request->user->email)."', '".$pass."', '".mysqli_real_escape_string($con, $request->user->name)."', '', '', '', '".$t."')";
	$rs = mysqli_query($con, $sql) or die('can\'t add to profile '.mysqli_error($con)) ;
	$p_id = mysqli_insert_id($con);
}

foreach($request->response as $resp){
	if(!is_array($resp->value)){
		$sqlr="insert into `response_bank` values (NULL, '".$p_id."', '".mysqli_real_escape_string($con, $resp->cat_id)."', '".mysqli_real_escape_string($con, $resp->id)."', '".mysqli_real_escape_string($con, $resp->value)."', '".mysqli_real_escape_string($con, $resp->value)."', '".$t."')";
		$rsr = mysqli_query($con, $sqlr);
		switch($resp->id){
			case 5: $newupdate['p_gender']=$resp->value;break;
			case 60: $newupdate['p_name']=$resp->value;break;
			case 14: $newupdate['p_age']=$resp->value;break;
			case 17: $newupdate['p_height']=$resp->value;break;
		}
	}
	else{
		for($r=0; $r<count($resp->value); $r++){
			$sqlr="insert into `response_bank` values (NULL, '".$p_id."', '".mysqli_real_escape_string($con, $resp->cat_id)."', '".mysqli_real_escape_string($con, $resp->id)."', '".mysqli_real_escape_string($con, $resp->value[$r]->item)."', '".mysqli_real_escape_string($con, $resp->value[$r]->value)."', '".$t."')";
			$rsr = mysqli_query($con, $sqlr) ;
		}
	}

}
print_r($newupdate);
if(count($newupdate)>0){
	$sql ="Update profile set ";
	$counter=0;
	foreach($newupdate as $key => $value){
		if($counter>0){ $sql.= ", `".$key."` ='".$value."' "; }
		else{$sql.= "`".$key."` ='".$value."' "; }
		$counter++;
	}
	$sql.=' where `p_id`='.$p_id;
	$res=mysqli_query($con, $sql);
}
include 'matcher.php';
echo $_GET['callback'].json_encode($p_id);
?>
