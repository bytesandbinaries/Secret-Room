<?php
//header('Access-Control-Allow-Origin: *');
require_once 'fun_connect2.php';
require_once 'JSON.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

$a_json = array();
$a_json_row = array();
$newupdate = array();
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$t = time();
$p_id = $request->user->id;

if( !$con ){
	die ('can\'t connect to database '.mysqli_connect_error());
}

if( $p_id == 0 ){

	$pass = md5( $request->user->password );
	// $pass = 'Fools Day';

	$sql = "INSERT INTO profile VALUES (NULL, '".mysqli_real_escape_string($con, $request->user->email)."', '".$pass."', '".mysqli_real_escape_string($con, $request->user->name)."', '', '', '', '.$t.', '', '')";
	$rescheck = mysqli_query( $con, $sqlcheck );

	$p_id = mysqli_insert_id( $con );

	if( $rescheck ){
		echo json_encode($p_id);
		// try{
		// 	$to = $request->user->email;
		// 	$subject = "The Exclusives Registration";
		// 	$message= 'Dear user, <br> <br>';
		// 	$message.= 'Welcome to the exclusives!!! We are glad you found your way here and we won\’t ask how! \r\n We believe there are over 1billion people in the world, and your decision to desire someone just like you is valid! \r\n This time around we really want you to get it right! so please give us the opportunity to get to know you a little more and understand your requirements for a relationship. Take a few minutes to complete the questionnaire, to enable us understand your wants and needs, so we only identify members that closely suit your preference. Once you have completed the questionnaire, we will let you know if you have an immediate match. If you do have a match, we will contact you immediately.\r\n Otherwise, not to worry, we will put you in our database and let you know as soon as we have some.\r\n If you have questions, please do not hesitate to send us an email immediately to support@the-exclusives.com \r\n The exclusives team';
		// 	$headers = "MIME-Version: 1.0" . "\r\n";
		// 	$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
		// 	//$headers .= "Bcc:hello@the-exclusives.com\r\n";
		// 	$headers .= "From:theexclusives\r\n";
		// 	$a = mail($to, $subject, $message, $headers);
		// } catch (Exception $e){
		// 	error_log( $e->errorMessage());
		// }
	} else {
		echo 'unable to save response data'. mysqli_error($con);
	}

} else {

	foreach( $request->response as $resp ){

		if( !is_array( $resp->value ) ){
			$sqlcheckR = "INSERT INTO response_bank VALUES ( NULL, '.$p_id.', ".mysqli_real_escape_string($con, $resp->cat_id).", ".mysqli_real_escape_string($con, $resp->id).", ".mysqli_real_escape_string($con, $resp->value).", ".mysqli_real_escape_string($con, $resp->value).", '.$t.')";
			$rescheckR = mysqli_query($con, $sqlcheck);

		}
		else{
			for( $r=0; $r<count($resp->value); $r++ ){
				$sqlcheckR = "INSERT INTO response_bank VALUES (NULL, '.$p_id.', ".mysqli_real_escape_string($con, $resp->cat_id).", ".mysqli_real_escape_string($con, $resp->id).", ".mysqli_real_escape_string($con, $resp->value[$r]->item).", ".mysqli_real_escape_string($con, $resp->value[$r]->value).", '.$t.')";
				$rescheckR = mysqli_query($con, $sqlcheck) ;
			}
		}

		switch( $resp->id ){
			case 5: $newupdate['p_gender'] = $resp->value;break;
			case 77: $newupdate['p_name'] = $resp->value[0]->value;break;
			case 14: $newupdate['p_age'] = $resp->value[0]->value;break;
			case 17: $newupdate['p_height'] = $resp->value[0]->value;break;
		}

	}

	if( count($newupdate) > 0 ){
		$sqlcheck = "UPDATE profile SET ";
		$counter = 0;
		foreach( $newupdate as $key => $value){
			if( $counter > 0 ){
				$sqlcheck.= " '.$key.' = '.$value.', ";
			}	else {
				$sqlcheck.= " '.$key.' = '.$value.' ";
			}
			$counter++;
		}
		$sqlcheck.= " WHERE p_id = '.$p_id.' " ;
		$rescheck = mysqli_query( $con, $sqlcheck );
	}
	include 'matcher.php';
	if ($rescheck && $rescheckR){
		echo json_encode($p_id);
	} else {
		echo 'Update Response failed'. mysqli_error($con);
	}
}

?>
