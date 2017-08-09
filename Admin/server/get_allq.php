<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
$host = "localhost";
// $username = "root";
 //$password = "";
 //$database = "secretroom";
  $username = "wwwbytes_secretr";
  $password = "secretroom";
  $database = "wwwbytes_secretroom";

$con=mysqli_connect($host,$username,$password, $database);
$a_json = array();
if(isset($_GET['data'])){$data=json_decode($_GET['data']);}
if($_GET['action']=='addQ'){
  $t=time();
  $sqlu="Insert into question_bank values ( NULL, '".mysqli_real_escape_string($con, $data->qtext)."','".$data->cat."', '', '".$data->qtype."', '".mysqli_real_escape_string($con, $data->qresponse)."', '".mysqli_real_escape_string($con, $data->qscale)."', '".$data->qminresponse."', '".$data->qmaxreponse."', '".$data->qweight."', '".$t."', '".$t."')";
  $rsu=mysqli_query($con, $sqlu) or die ("Error : could not Add new Question" . mysqli_error($con));
  $q_id = mysqli_insert_id($con);
  echo $_GET['callback'].json_encode($q_id);
  //if($rsu){ header("Location: index.php?action=viewQ&q_id=".$q_id);}

}
else if ($_GET['action']=='editQ'){
    $t=time();print_r($data);
  $sqlu="Update question_bank set `question_text`='".mysqli_real_escape_string($con, $data->{'1'})."', `question_category`='".$data->{'2'}."',  `question_type` ='".$data->{'4'}."', `question_response_options`='".mysqli_real_escape_string($con, $data->{'5'})."', `question_scale`='".$data->{'6'}."', `question_response_min`='".$data->{'7'}."', `question_response_max`='".$data->{'8'}."', `question_weight`='".$data->{'9'}."', `question_updated`='".$t."' where `question_id`=".$data->{'0'};
  $rsu=mysqli_query($con, $sqlu) or die ("Error : could not Add new Question" . mysqli_error($con));
  if($rsu){ echo $_GET['callback'].'Question Updated'; }
  }
else if($_GET['action'] =='deleteq'){
  $sqlu="Delete from question_bank  where `question_id`=".$data;
  $rsu=mysqli_query($con, $sqlu) or die ("Error : could not Update Category" . mysqli_error($con));
  if($rsu){echo $_GET['callback'].'Deleted';}
  else{echo $_GET['callback'].'Error Deleting question';}
}
else if($_GET['action'] =='addc'){
  $t=time();
  $sqlu="Insert into question_category values ( NULL, '".$data->title."','".$data->desc."', '', '".$t."', '".$t."')";
  $rsu=mysqli_query($con, $sqlu) or die ("Error : could not Add new Category" . mysqli_error($con));
  $c_id = mysqli_insert_id();
  if($rsu){ echo $_GET['callback'].'Category Added';}
}
else if($_GET['action'] =='updatec'){
  $t=time();
  $sqlu="Update `question_category` set `category_title` ='$data->c_name', `category_description`='$data->c_desc', `category_updated`='$t' where `category_id`=".$data->c_id;
  $rsu=mysqli_query($con, $sqlu) or die ("Error : could not Update Category" . mysqli_error($con));
  $c_id = mysqli_insert_id();
  if($rsu){}
}
else if($_GET['action'] =='deletec'){
  $sqlu="Delete from question_category  where `category_id`=".$data;
  $rsu=mysqli_query($con, $sqlu) or die ("Error : could not Update Category" . mysqli_error($con));
  if($rsu){echo $_GET['callback'].'Deleted';}
  else{echo $_GET['callback'].'Error Deleting category';}
}
elseif ($_GET['action']=='getallc') {
  $a_json[0]=array('c_id' => '', 'c_name'=>'Select a category' );
  $sqlcheck = "SELECT category_id, category_title, category_description FROM `question_category` order by category_id ";
  $rescheck=mysqli_query($con, $sqlcheck) or die ("Error : could not Seect category" . mysqli_error($con));;
  while($info= mysqli_fetch_array($rescheck)){
    $qobject = array('c_id' => $info[0], 'c_name'=>$info[1], 'c_desc'=>$info[2] );
    array_push($a_json, $qobject);
  }
  echo $_GET['callback'].json_encode($a_json);
}
else if($_GET['action']=='viewQ'){
  //retrieve all the question's data
  $sqlQ = "SELECT * FROM `question_bank` where question_id='".$data."' ";
  $resQ=mysqli_query($con, $sqlQ) or die ("Error : could not Select category" . mysqli_error($con));;
  $infoQ= mysqli_fetch_array($resQ);

  //select the category name from the database//
  $sqlQc = "SELECT category_title FROM `question_category` where category_id='".$infoQ[2]."' ";
  $resQc=mysqli_query($con, $sqlQc) or die ("Error : could not Select category" . mysqli_error($con));
  $infoQc= mysqli_fetch_array($resQc);
  $a_json['question_data']= $infoQ;
  $a_json['category_name']=$infoQc[0];
  echo $_GET['callback'].json_encode($a_json);
}
else if($_GET['action']=='viewallQ'){
  //retrieve all the Categories
  $sqlCAT = "SELECT * FROM `question_category` order by `category_title` asc";
  $resCAT=mysqli_query($con, $sqlCAT) or die ("Error : could not Select Categories" . mysqli_error($con));
  while($infoCAT= mysqli_fetch_array($resCAT)){
    $a_sub_json['categoryname']= $infoCAT[1];
    $a_sub_json['categoryid']= $infoCAT[0];
    $a_sub_json['questions']=array();
    //select the question with the category_id//
    $sqlAQ = "SELECT * FROM `question_bank` where `question_category` = '$infoCAT[0]' order by `question_order` asc";
    $resAQ=mysqli_query($con, $sqlAQ) or die ("Error : could not Select Questions" . mysqli_error($con));;
    while($infoAQ= mysqli_fetch_array($resAQ)){
      $qobject = array('q_id' => $infoAQ[0], 'q_text'=>$infoAQ[1] );
      array_push($a_sub_json['questions'], $qobject);
    }
    array_push($a_json, $a_sub_json);
  }
  echo $_GET['callback'].json_encode($a_json);
}
elseif ($_GET['action']=='getallqinc') {
  $a_json[0]=array('q_id' => '', 'q_text'=>'Select a Question' );
  $sqlAQ = "SELECT * FROM `question_bank` where `question_category` = '$data' order by `question_order` asc";
  $resAQ=mysqli_query($con, $sqlAQ) or die ("Error : could not Select Questions" . mysqli_error($con));
  while($infoAQ= mysqli_fetch_array($resAQ)){
    $qobject = array('q_id' => $infoAQ[0], 'q_text'=>$infoAQ[1] );
    array_push($a_json, $qobject);
  }
  echo $_GET['callback'].json_encode($a_json);
}
elseif($_GET['action']=='maddQ'){
  $t=time();$srange=0; $scaleqid=0; $scalecat=0;
  if(isset($data->scale_range) && isset($data->scale_id)){$srange=$data->scale_range; $scaleqid=$data->scale_id; $scalecat=$data->scale_cat;}
  $sqlu="Insert into `match` values ( NULL, '".$data->mtype."', $data->fcat , $data->fq_id , $data->scat , $data->sq_id , $scalecat, $scaleqid , $srange, '".$t."', '".$t."')";
  $rsu=mysqli_query($con, $sqlu) or die ("Error : could not Add match" . mysqli_error($con));
  $q_id = mysqli_insert_id($con);
  echo $_GET['callback'].json_encode($q_id);
}
elseif($_GET['action']=='getallm'){
  $t=time();
  $sqlm = "SELECT * FROM `match` order by `match_id` desc";
  $resm=mysqli_query($con, $sqlm) or die ("Error : could not Select Matches" . mysqli_error($con));
  while($infom= mysqli_fetch_array($resm)){
    $aa_json=array();
    $aa_json['match_type']=$infom[1];
    $aa_json['questions']=array();
    for($x=7; $x>1; $x=$x-2){
       $a_sub_json=array();
       $sql="select q.question_text, c.category_title from `question_bank` as q JOIN `question_category` as c on q.question_category=c.category_id where q.question_id=".$infom[$x];
       $res=mysqli_query($con, $sql) or die ("Error : could not Select Matches" . mysqli_error($con));
       while($info=mysqli_fetch_array($res)){
         $a_sub_json['category_title']= $info[1];
         $a_sub_json['question_text']=$info[0];
         array_push($aa_json['questions'], $a_sub_json);
       }
    }
    array_push($a_json, $aa_json);
  }
  echo $_GET['callback'].json_encode($a_json);
}
elseif($_GET['action']=='shift_question'){
    $sql="Select question_order From question_bank where question_id='".$data->question_id."'";
    $res=mysqli_query($con, $sql);
    $info=mysqli_fetch_array($res);
    if($data->func=='move_up'){
        $sql="Update question_bank
            Set question_order=question_order+1 Where question_category='".$data->category_id."' AND question_order=".($info[0]-1);
        $res=mysqli_query($con, $sql) or die ("Error : could not shift question up " . mysqli_error($con));
        if($res){
            $sql="Update question_bank    Set question_order=question_order-1  Where question_id='".$data->question_id."'";
            $res=mysqli_query($con, $sql) or die ("Error : could not update mode question" . mysqli_error($con));;
            if($res){echo $_GET['callback'].'Question reordered';}
        }
    }
    else{
        $sql="Update question_bank
            Set question_order=question_order-1 Where question_category='".$data->category_id."' AND question_order=".($info[0]+1);
        $res=mysqli_query($con, $sql) or die ("Error : could not shift question up " . mysqli_error($con));
        if($res){
            $sql="Update question_bank    Set question_order=question_order+1  Where question_id='".$data->question_id."'";
            $res=mysqli_query($con, $sql) or die ("Error : could not update mode question" . mysqli_error($con));;
            if($res){echo $_GET['callback'].'Question reordered';}
        }
    }
  // $t=time();$srange=0; $scaleqid=0; $scalecat=0;
  // if(isset($data->scale_range) && isset($data->scale_id)){$srange=$data->scale_range; $scaleqid=$data->scale_id; $scalecat=$data->scale_cat;}
  // $sqlu="Insert into `match` values ( NULL, '".$data->mtype."', $data->fcat , $data->fq_id , $data->scat , $data->sq_id , $scalecat, $scaleqid , $srange, '".$t."', '".$t."')";
  // $rsu=mysqli_query($con, $sqlu) or die ("Error : could not Add match" . mysqli_error($con));
  // $q_id = mysqli_insert_id($con);
  // echo $_GET['callback'].json_encode($q_id);
}
else{
  $sql='Select * from question_bank  order by question_updated Desc';
  $rs=mysqli_query($con, $sql) or die ("Error : could not Fetch Question Bank" . mysqli_error($con));
  $total_rows= mysqli_num_rows($rs);
  $info=mysqli_fetch_array($rs);
  $a_json['total_question']= $total_rows;
  $a_json['last_update']=gmdate("F j, Y, g:i a",$info[10]);
  echo $_GET['callback'].json_encode($a_json);
}

 ?>
