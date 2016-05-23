<?php
  require_once("fun_connect2.php");
  $matcher=array();
  $user_match=array();
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  //fetch all the match sequence
  $sql="Select * from `match` order by `match_id` desc";
  $rs=mysqli_query($con, $sql);
  while($info=mysqli_fetch_array($rs)){
    array_push($matcher, $info);//matcher is an array of all the matching sequence
  }
  print_r($matcher);
    //fetch all the match questions that is in the matcher from the response database using the user's profile_id
  $sqlures="select * from `response_bank` where `profile_id`=".$p_id." AND (";
  for($i=0;  $i<count($matcher); $i++){
    //add the matchers in the query
    //constructing the query
    if($i<=(count($matcher)-2)){$sqlures.= " `question_id`=".$matcher[$i][3]." OR ";}
    else{$sqlures.=" `question_id`=".$matcher[$i][3];}
  }
  $sqlures.=')';
  echo $sqlures;
  $resures=mysqli_query($con, $sqlures);
  while($infor=mysqli_fetch_array($resures)){
    array_push($user_match, $infor); //user match are the retrieved questions that correspond to the matcher.
  }
  print_r($user_match);
  //fetch all possible response in the response Bank
  $sqlM="select qr.*, p.p_email from `response_bank` as qr JOIN `profile` as p on qr.profile_id=p.p_id where (";
  for($x=0; $x<count($user_match); $x++){
    $sqlM.= "( qr.question_id=".$matcher[$x][5]; //fetching the matching response from the database [5] corresponds.
    if($matcher[$x][3]==6){$sqlM.= " AND qr.question_response!='".$user_match[$x][4]."')";}
    else{$sqlM.= " AND qr.question_response='".$user_match[$x][4]."')";}
    if($x==0 && (count($user_match)>1)){$sqlM.=" AND ";}
  }
  $sqlM.=")";
  $a_json=array();
  echo $sqlM;
  $res=mysqli_query($con, $sqlM) or die("Error : couldn't find match" . mysqli_error($con));
  while($infoM=mysqli_fetch_assoc($res)){
      array_push($a_json, $infoM);
  }

  print_r($a_json);
?>
