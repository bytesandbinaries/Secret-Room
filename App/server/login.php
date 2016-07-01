<?php
	require_once 'fun_connect2.php';
	require_once 'JSON.php';
	// prevent direct access

	$passer=md5(mysqli_real_escape_string($con, $_GET['pass']));
	$a_json = array();
	$a_json_row = array();
	$sqlcheck = "SELECT * FROM `profile` WHERE p_email='".mysqli_real_escape_string($con, $_GET['email'])."' AND p_pass='$passer'";
	$rescheck=mysqli_query($con, $sqlcheck) or die ("Error : could not check values for $email " . mysqli_error($con) );
	$count = mysqli_num_rows($rescheck);
	if($count > 0){
		$row= mysqli_fetch_array($rescheck);
		  $a_json_row["info"] = 'Authenticated';
		  $a_json_row["userId"] = $row[0];
		  $a_json_row["email"] = $row[1];
		  $a_json_row["fname"] = $row[3];
		  $a_json_row["gender"] = $row[4];
		  $a_json_row["age"] = $row[5];
		  $a_json_row["height"] = $row[6];
		  $a_json_row["joined"] = $row[7];
		  array_push($a_json, $a_json_row);
		  //$re=turntojson($a_json);
		  //echo($re);
			$serverToken=$row[2].$row[7].'.myUniqueTQ';
			$ucal['token']=$serverToken;
			$ucal['details']=$a_json_row;
		  echo json_encode($ucal);
	}
	else{
		$ucal['errorM']='Wrong Username or Password, Plese verify your details.';
		echo json_encode($ucal);
	}

?>
