<?php
    require_once 'fun_connect2.php';
    require_once 'JSON.php';
    $t=time();
    $postdata = file_get_contents("php://input");
    print_r($postdata);
    $sqlcheck = "Update `profile` set status ='TermAccepted' status_change_time='".$t."' where p_id= ".$postdata;
	$rescheck = mysqli_query($con, $sqlcheck) or die ("Error : could not check values for $postdata " . mysqli_error($con) );
    echo 'Updated';
?>
