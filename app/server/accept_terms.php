<?php
    require_once 'fun_connect2.php';
    $t=time();
    $user=json_decode($_GET["user"]);
    //print_r($user);
    $sqlcheck = "Update `profile` set status ='TermAccepted', status_change_time ='".$t."' where p_id= ".$user->id;
	$rescheck=mysqli_query($con, $sqlcheck) or die (" Error : could not check values for " . mysqli_error($con) );
    if($rescheck){
        $to=$user->email;
        $subject = "Your Match is a step closer";
        $message= 'Dear '.$user->name.' <br> <br>';
        $message.= 'Thank you for registration. Please endeavour to complete your profile so as to find a suitable match for you.';
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
        //$headers .= "Bcc:sholadedokun@gmail.com\r\n";
        $headers .= "From:Secretroom\r\n";
        $a = mail($to, $subject, $message, $headers);

        $to='sholadedokun@yahoo.com';
        $subject = "New Completed Profile";
        $message= 'Dear Admin, <br> <br>';
        $message.= 'A new user with profile ID :'.$user->id.', has just completed their profile.';
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
        $headers .= "Bcc:buchanora@gmail.com\r\n";
        $headers .= "From:Secretroom\r\n";
        $a = mail($to, $subject, $message, $headers);
    }
    echo 'Updated';

?>
