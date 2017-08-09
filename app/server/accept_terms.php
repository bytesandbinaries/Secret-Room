<?php
    require_once 'fun_connect2.php';
    $t = time();
    $user = json_decode( $_GET["user"] );

    if (!$con){
      die( 'Can\'t connect to the database'.mysqli_connect_error() );
    }
    //print_r($user);
    $sqlcheck = "UPDATE profile SET status = 'TermAccepted', status_change_time = '.$t.' WHERE p_id = '.$user->id.'";

	  $rescheck = mysqli_query( $con, $sqlcheck );
    if( $rescheck ){
      echo 'Updated';
      // try{
      //   $to=$user->email;
      //   $subject = "Your Match is a step closer";
      //   $message= 'Dear '.$user->name.' <br> <br>';
      //   $message.= 'Thank you for your registration. Please endeavour to complete your profile so we can find a suitable match for you.';
      //   $headers = "MIME-Version: 1.0" . "\r\n";
      //   $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
      //   //$headers .= "Bcc:sholadedokun@gmail.com\r\n";
      //   $headers .= "From:The Exclusives\r\n";
      //   $a = mail($to, $subject, $message, $headers);
      //
      //   $to='sholadedokun@yahoo.com';
      //   $subject = "New Completed Profile";
      //   $message= 'Dear Admin, <br> <br>';
      //   $message.= 'A new user with profile ID :'.$user->id.', has just completed their profile.';
      //   $headers = "MIME-Version: 1.0" . "\r\n";
      //   $headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
      //   $headers .= "Bcc:buchanora@gmail.com\r\n";
      //   $headers .= "From:The Exclusives\r\n";
      //   $a = mail($to, $subject, $message, $headers);
      // } catch (Exception $e){
      //   error_log($e->getMessage());
      // }

    } else {
      echo 'Error updating Record:' . mysqli_error($con);
    }

?>
