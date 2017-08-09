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
$t=time();
$p_id=$request->user->id;
$status = $request->user->status;
if($p_id == 0){
	$pass=md5($request->user->password);
	$sql = "insert into `profile` values (NULL, '".mysqli_real_escape_string($con, $request->user->email)."', '".$pass."', '', '', '', '', '".$t."', '".$status."', '')";
	$rs = mysqli_query($con, $sql) or die('can\'t add to profile '.mysqli_error($con)) ;
	$p_id = mysqli_insert_id($con);
	if($rs){
        // $to=$request->user->email;
        // $subject = "Secret Room Registeration";
        // $message= 'Dear user, <br> <br>';
        // $message.= 'Thank you for registration. Please endeavour to complete your profile so as to find a suitable match for you.';
        // $headers = "MIME-Version: 1.0" . "\r\n";
        // $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
        // //$headers .= "Bcc:sholadedokun@gmail.com\r\n";
        // $headers .= "From:Secretroom\r\n";
        // $a = mail($to, $subject, $message, $headers);
    }
}
else{
	foreach($request->response as $resp){
		if(!is_array($resp->value)){
			$sqlr="insert into `response_bank` values (NULL, '".$p_id."', '".mysqli_real_escape_string($con, $resp->cat_id)."', '".mysqli_real_escape_string($con, $resp->id)."', '".mysqli_real_escape_string($con, $resp->value)."', '".mysqli_real_escape_string($con, $resp->value)."', '".$t."')";
			$rsr = mysqli_query($con, $sqlr);

		}
		else{
			if (isset($resp->value))
			{
				for($r=0; $r<count($resp->value); $r++){
				 $sqlr="insert into `response_bank` values (NULL, '".$p_id."', '".mysqli_real_escape_string($con, $resp->cat_id)."', '".mysqli_real_escape_string($con, $resp->id)."', '".mysqli_real_escape_string($con, $resp->value[$r]->item)."', '".mysqli_real_escape_string($con, $resp->value[$r]->value)."', '".$t."')";
				 $rsr = mysqli_query($con, $sqlr) ;
			 }
			}

		}
		switch($resp->id){
			case 1: $newupdate['p_gender']=$resp->value[0]->value;break;
			case 49: $newupdate['p_name']=$resp->value[0]->value;break;
			case 11: $newupdate['p_age']=$resp->value[0]->value;break;
			case 14: $newupdate['p_height']=$resp->value[0]->value;break;
		}

	}
	if(count($newupdate)>0){
		$sql ="Update profile set ";
		$counter=0;
		foreach($newupdate as $key => $value){
			if($counter>0){ $sql.= ", `".$key."` ='".$value."' "; }
			else{$sql.= "`".$key."` ='".$value."' "; }
			$counter++;
		}
		$sql.= ", `status` = '".$status."' ";
		$sql.=' where `p_id`='.$p_id;
		$res=mysqli_query($con, $sql);
	}
	// include 'matcher.php';
}
echo json_encode($p_id);
?>
