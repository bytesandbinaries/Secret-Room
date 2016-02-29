<?php 
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	$host = "localhost";
	$username = "wwwbytes_secretr";
	$password = "secretroom1@";
	$database = "wwwbytes_secretroom"; 
	$con=mysqli_connect($host,$username,$password, $database);
	
	if(isset($_GET['AddQ'])){
		$t=time();	
		$sqlu="Insert into question_bank values ( NULL, '".mysqli_real_escape_string($con, $_GET['q_text'])."','".$_GET['q_c']."', '', '".$_GET['q_type']."', '".mysqli_real_escape_string($con, $_GET['q_res_options'])."', '".mysqli_real_escape_string($con, $_GET['q_scale'])."', '".$_GET['min_response']."', '".$_GET['max_response']."', '".$_GET['q_weight']."', '".$t."', '".$t."')";
		$rsu=mysqli_query($con, $sqlu) or die ("Error : could not Add new Question" . mysqli_error());
		$q_id = mysqli_insert_id();
		if($rsu){ header("Location: index.php?action=viewQ&q_id=".$q_id);}	
					
	}
	else if (isset ($_GET['UpdateQ'])){
			$t=time();	$q_id=$_GET['q_id'];
		$sqlu="Update question_bank set `question_text`='".mysqli_real_escape_string($con, $_GET['q_text'])."', `question_category`='".$_GET['q_c']."',  `question_type` ='".$_GET['q_type']."', `question_response_options`='".mysqli_real_escape_string($con, $_GET['q_res_options'])."', `question_scale`='".$_GET['q_scale']."', `question_response_min`='".$_GET['min_response']."', `question_response_max`='".$_GET['max_response']."', `question_weight`='".$_GET['q_weight']."', `question_updated`='".$t."' where `question_id`=".$q_id;
		$rsu=mysqli_query($con, $sqlu) or die ("Error : could not Add new Question" . mysqli_error());
		if($rsu){ header("Location: index.php?action=viewQ&q_id=$q_id");}	
		}
	else if(isset($_GET['AddC'])){
		$t=time();	
		$sqlu="Insert into question_category values ( NULL, '".$_GET['c_title']."','".$_GET['c_desc']."', '', '".$t."', '".$t."')";
		$rsu=mysqli_query($con, $sqlu) or die ("Error : could not Add new Category" . mysqli_error());
		$c_id = mysqli_insert_id();
		if($rsu){ header("Location: index.php?action=viewC&c_id=".$c_id);}
	}
	else if(isset($_GET['UpdateC'])){
		$t=time();	
		$sqlu="Update question_category set category_title, category_description, category_update values ( '".$_GET['c_title']."','".$_GET['c_desc']."', '', '".$t."') where `category_id`=".$_GET['c_id'];
		$rsu=mysqli_query($con, $sqlu) or die ("Error : could not Update Category" . mysqli_error());
		$c_id = mysqli_insert_id();
		if($rsu){ header("Location: index.php?action=viewC&c_id=".$c_id);}
	}
	else if(isset($_GET['action'])&& ($_GET['action'] =='deleteq')){
		$sqlu="Delete from question_bank  where `question_id`=".$_GET['q_id'];
		$rsu=mysqli_query($con, $sqlu) or die ("Error : could not Update Category" . mysqli_error());
		$c_id = mysqli_insert_id();
		if($rsu){ header("Location: index.php?action=viewallq");}
	}
	$sql='Select * from question_bank  order by question_updated Desc';
	$rs=mysqli_query($con, $sql) or die ("Error : could not Fetch Question Bank" . mysqli_error());
	$total_rows= mysqli_num_rows($rs);
	$info=mysqli_fetch_array($rs);	
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Question Update</title>
<script src="jquery.js" type="text/javascript"></script>
<script>
	$(document).ready(function() {
		$('.seo').change( function(){
		$tex=$(this).attr('id')+'t';		
		$tet="#"+"$tex";
		console.log($("#"+$tex).val());
		$z="";
		$x=$("#"+$tex).val();
		$y=$(this).val();
		if($x!=""){
			$z= $x+"| "+$y;
			$("#"+$tex).val($z);
		}
		else{$("#"+$tex).val($y);}
		})
	})
</script>
</head>

<body>
	
	<div class="" style="margin:10% 0 0 30%; width:600px; height:auto; border:2px solid #aaa">
    		<div style="padding:10px; background:#999; color:#fff; text-align:right"><a href="index.php">Home</a></div>
    		<?php if (isset($updated)){?><div style="padding:10px"> <?php echo $updated; ?> </div> <?php } ?>
        	
        	<?php if(!isset($_GET['action'])){ ?>
            
    	   	<div class="" style="padding:5px 15px; background:#ccc; color:#555"><h2>Update Question Bank</h2></div>
        	<div class="" style="padding:10px; background:#eee; color:#555">
            	<div><a href="index.php?action=addnewq">Add New Question</a></div>
            </div>
            <div class="" style="padding:10px; background:#bbb; color:#555">
            	<div><a href="index.php?action=addnewc">Add New Category</a></div>
            </div>
            <div class="" style="padding:10px; background:#eee; color:#555">
            	<div><a href="index.php?action=viewallq">View All Question</a></div>
            </div>
            <div class="" style="padding:10px; background:#eee; color:#555">
            	<div><a href="index.php?action=viewallc">View All Categories</a></div>
            </div>
           
        <div  style="padding:10px;"><b>Last Updated :</b>  <?php echo gmdate("F j, Y, g:i a", $info[10]); ?></div>
        <div  style="padding:10px;"><b>Total Questions :</b> <?php echo $total_rows;  ?></div>
         <?php } 
		 else if(($_GET['action']=='addnewq')|| ($_GET['action']=='editq'))
		 {$a=$_GET['action']; $infoU=array('','','','','','','','','','','','');?>
         	<div class="" style="padding:5px 15px; background:#ccc; color:#555">
            	<?php if($a=='addnewq'){?>	<h2>Add New Question</h2><?php } else{?><h2>Update Question</h2> <?php 
					$sqlU = "SELECT * FROM `question_bank` where `question_id`=".$_GET['q_id'];
					$resU=mysqli_query($con, $sqlU) or die ("Error : could not Seect category" . mysqli_error());
					$infoU= mysqli_fetch_array($resU);				
				} ?>
            </div>
            <form action="" method="get" >
            	<input type="hidden" name="q_id" value="<?php echo $infoU[0]  ?>" />
        	<div class="" style="padding:10px; background:#eee; color:#555">
            	<div><b>Select Question Category</b></div>
                <span><select name="q_c" id="q_c" class="seo">
                  	<option  value="">Select A Category</option>
                    <?php
						$sqlcheck = "SELECT category_id, category_title FROM `question_category` order by category_id ";
						$rescheck=mysqli_query($con, $sqlcheck) or die ("Error : could not Seect category" . mysqli_error());;
						while($info= mysqli_fetch_array($rescheck)){
					?>
        	        <option  value="<?php echo $info[0]?>" <?php if ($infoU[2]==$info[0]) {echo "selected";} ?>><?php echo ($info[1])?></option>
                    <?php }?>
                    </select>
      	        </span>
            </div>
            <div class="" style="padding:10px; background:#bbb; color:#555">
            	<div><b>Select Question Type</b></div>
                <div><select name="q_type" id="p_trip" class="seo">
                  	<option  value="">Select A Type</option>
                    <option  value="tb" <?php if ($infoU[4]=="tb") {echo "selected";}?>>TextBox</option>
                    <option  value="mp" <?php if ($infoU[4]=="mp") {echo "selected";}?>>Multiple Response</option>
                    <option  value="s" <?php if ($infoU[4]=="s") {echo "selected";}?>>Scale</option>
                    <option  value="sm" <?php if ($infoU[4]=="sm") {echo "selected";}?>>Scale And Multiple Response</option>
                    </select>
      	        </div>
            </div>
            <div class="" style="padding:10px; background:#eee; color:#555">
            	<div><b>Question Text</b></div>
                <div>
                	<textarea name="q_text"  cols="40" rows="5"><?php echo $infoU[1] ?></textarea>
      	        </div>
            </div>
            <div class="" style="padding:10px; background:#bbb; color:#555">
            	<div><b>Response Options</b></div>
                <div>
                	<textarea name="q_res_options"  cols="50" rows="8"><?php echo $infoU[5] ?></textarea>
      	        </div>
            </div>
            <div class="" style="padding:10px; background:#eee; color:#555">
            	<div><b>Measuring Scale</b></div>
                <div><select name="q_scale_opt" id="q_scale" class="seo">
                  	<option  value="">Popular Scales</option>                    
                    <option  value="Not Important">Not Important</option>
                    <option  value="Important">Important</option>
                    <option  value="Very Important">Very Important</option>
                    <option  value="Not me at all">Not me at all</option>
                    <option  value="Slightly me">Slightly me</option>
                    <option  value="That’s exactly me">That’s exactly me</option>
                    <option  value="Almost"> Almost</option>
                    <option  value="Never">Never</option>
                    <option  value="Sometimes">Sometimes</option>
                    <option  value="Most times">Most times</option>
                    <option  value="Not interested">Not interested</option>
                    <option  value="Somewhat interested">Somewhat interested</option>
                    <option  value="Very interested">Very interested</option>
                    </select>
      	        </div>
                <div>
                	<textarea id="q_scalet" name="q_scale" cols="50" rows="8"><?php echo $infoU[6] ?></textarea>
      	        </div>
            </div>
            <div class="" style="padding:10px; background:#bbb; color:#555">
            	<div><b>Min Response</b></div>
                <div><input type="text" name="min_response" value="<?php echo $infoU[7] ?>"/></div>
            </div>
            <div class="" style="padding:10px; background:#eee; color:#555">
            	<div><b>Max Response</b></div>
                <div><input type="text" name="max_response" value="<?php echo $infoU[8] ?>"></div>
            </div>
            <div class="" style="padding:10px; background:#eee; color:#555">
            	<div><b>Question Weight</b></div>
                <div><input type="text" name="q_weight" value="<?php echo $infoU[9] ?>"></div>
            </div>
            <?php if($a=='addnewq'){?><div  style="padding:10px; " ><input type="submit" value="Add Question" name="AddQ" /></div><?php } else{ ?>
            	<div  style="padding:10px; " ><input type="submit" value="Update Question" name="UpdateQ" /></div>
			<?php }?>
           	</form>
          <?php } 
		 else if($_GET['action']=='addnewc')
		 {?>
         	<div class="" style="padding:5px 15px; background:#ccc; color:#555"><h2>Add New Category</h2></div>
            <form action="index.php?action=updateC" method="get" >
                <div class="" style="padding:10px; background:#eee; color:#555">
                    <div><b>Category Title</b></div>
                    <div>
                        <textarea name="c_title"></textarea>
                    </div>
                </div>
                <div class="" style="padding:10px; background:#eee; color:#555">
                    <div><b>Category Description</b></div>
                    <div>
                        <textarea name="c_desc"></textarea>
                    </div>
                </div>
                <div  style="padding:10px;"><input type="submit" value="Add Category" name="AddC" /></div>
           	</form>
         <?php  } 
		 else if($_GET['action']=='viewQ')
		 { 
						$sqlQ = "SELECT * FROM `question_bank` where question_id='".$_GET['q_id']."' ";
						$resQ=mysqli_query($con, $sqlQ) or die ("Error : could not Select category" . mysqli_error());;
						$infoQ= mysqli_fetch_array($resQ);
					?>
         	<div class="" style="padding:5px 15px; background:#ccc; color:#555"><h2>Question View</h2></div>
                <div class="" style="padding:10px; background:#eee; color:#555">
                    <div><b>Select Question Category</b></div>
                    <div><?php $sqlQc = "SELECT category_title FROM `question_category` where category_id='".$infoQ[2]."' ";
						$resQc=mysqli_query($con, $sqlQc) or die ("Error : could not Select category" . mysqli_error());;
						$infoQc= mysqli_fetch_array($resQc); echo $infoQc[0] ?></div>
                </div>
                <div class="" style="padding:10px; background:#bbb; color:#555">
                    <div><b>Select Question Type</b></div>
                    <div><?php if( $infoQ[4] == 'tb'){echo 'Textbox Fill-in';} else if( $infoQ[4] == 's'){echo 'Scale';} else if( $infoQ[4] == 'mp'){echo 'Multiple Response';} else if( $infoQ[4] == 'sm'){echo 'Scale and Multiple Response';} ?></div>
                </div>
                <div class="" style="padding:10px; background:#eee; color:#555">
                    <div><b>Question Text</b></div>
                    <span> <?php echo $infoQ[1] ?>     </span>
                </div>
                <?php if(  $infoQ[5]!=''){?>
                <div class="" style="padding:10px; background:#bbb; color:#555">
                    <div><b>Response Options</b></div>
                    <div>     <?php echo $infoQ[5];?>   </div>
                </div>
                <?php } if(  $infoQ[6]!=''){?>
                <div class="" style="padding:10px; background:#eee; color:#555">
                    <div><b>Measuring Scale</b></div>
                    <div>   <?php echo $infoQ[6];?>  </div>
                </div>
                <?php } ?>
                <div class="" style="padding:10px; background:#bbb; color:#555">
                    <div><b>Min Response</b></div>
                    <div> <?php echo $infoQ[7];?> </div>
                </div>
                <div class="" style="padding:10px; background:#eee; color:#555">
                    <div><b>Max Response</b></div>
                    <div> <?php echo $infoQ[8];?></div>
                </div>
                <div class="" style="padding:10px; background:#bbb; color:#555">
                    <div><b>Question Weight</b></div>
                    <div><?php echo $infoQ[9];?></div>
                </div>
                <span  style="padding:10px; " ><b>Last Updated:</b> <?php echo gmdate("F j, Y, g:i a", $infoQ[10]) ?></span><br/>
                <span  style="padding:10px; " ><b>Date Created:</b> <?php echo gmdate("F j, Y, g:i a", $infoQ[11]) ?></span><br/>
                <span  style="padding:10px; " ><a href="index.php?action=addnewq">Add New Question</a></span>
                <span  style="padding:10px; " ><a href="index.php?action=editq&q_id=<?php echo $_GET['q_id'] ?>">Edit this Question</a></span>
                <span  style="padding:10px; " ><a href="index.php?action=deleteq&q_id=<?php echo $_GET['q_id'] ?>">Delete Question</a></span>
                <span  style="padding:10px; " ><a href="index.php?action=viewallq">View all Questions</a></span>
                
           	
         <?php } 
		 else if($_GET['action']=='viewC'){
		 
         	
						$sqlC = "SELECT * FROM `question_category` where category_id='".$_GET['c_id']."' ";
						$resC=mysqli_query($con, $sqlC) or die ("Error : could not Select category" . mysqli_error());;
						$infoC= mysqli_fetch_array($resC);
					?>
         	<div class="" style="padding:5px 15px; background:#ccc; color:#555"><h2>Category View</h2></div>
                <div class="" style="padding:10px; background:#eee; color:#555">
                    <div><b>Category Title</b></div>
                    <div><?php  echo $infoC[1] ?></div>
                </div>
                <div class="" style="padding:10px; background:#bbb; color:#555">
                    <div><b>Category Description</b></div>
                    <div><?php echo $infoC[2]?></div>
                </div>
                <span  style="padding:10px; " ><b>Last Updated:</b> <?php echo gmdate("F j, Y, g:i a", $infoC[4]) ?></span><br/>
                <span  style="padding:10px; " ><b>Date Created:</b> <?php echo gmdate("F j, Y, g:i a", $infoC[5]) ?></span><br/>
                <span  style="padding:10px; " ><a href="index.php?action=addnewc">Add New Category</a></span>
                <span  style="padding:10px; " ><a href="index.php?action=editC&c_id=<?php echo $_GET['c_id'] ?>">Edit this category</a></span>
                <span  style="padding:10px; " ><a href="index.php?action=viewallc">View all Categories</a></span>
                
           	
         <?php }else if($_GET['action']=='viewallq'){  ?>
         	<div style="padding:5px 15px; background:#ccc; color:#555"><h2>View All Questions</h2></div>
			<?php
				$sqlCAT = "SELECT * FROM `question_category` order by `category_title` asc";
				$resCAT=mysqli_query($con, $sqlCAT) or die ("Error : could not Select Categories" . mysqli_error());
				while($infoCAT= mysqli_fetch_array($resCAT)){
			?>
                    <div class="" style="padding:10px; background:#eee; color:#555; border-bottom:2px solid #555">
                    <div><b> <?php echo $infoCAT[1] ?></b></div>
                    <?php 
						$sqlAQ = "SELECT * FROM `question_bank` where `question_category` = '$infoCAT[0]' order by `question_order` asc";
						$resAQ=mysqli_query($con, $sqlAQ) or die ("Error : could not Select Questions" . mysqli_error());;
						while($infoAQ= mysqli_fetch_array($resAQ)){
					?>
                    <div>
                    	<a href="index.php?action=viewQ&q_id=<?php echo $infoAQ[0] ?>"><?php  echo $infoAQ[1] ?></a></div>
                    <?php } ?>
                </div>
             <?php } 
			 
			 }
			 else if($_GET['action']=='viewallc'){ ?>
             <div style="padding:5px 15px; background:#ccc; color:#555"><h2>View All Categories</h2></div>
             <?php 
			 $sqlCAT = "SELECT * FROM `question_category` order by `category_title` asc";
				$resCAT=mysqli_query($con, $sqlCAT) or die ("Error : could not Select Categories" . mysqli_error());
				while($infoCAT= mysqli_fetch_array($resCAT)){
			  ?>
               <div class="" style="padding:10px; background:#eee; color:#555; border-bottom:2px solid #555">
                   <div><b> <?php echo $infoCAT[1] ?></b></div>
                   <div><?php echo $infoCAT[2] ?></div>
                   <div>Total Questions: <?php
				   		$sqlCATq = "SELECT * FROM `question_bank` where `question_category`='".$infoCAT[0]."'";
						$resCATq=mysqli_query($con, $sqlCATq) or die ("Error : could not Select Categories" . mysqli_error());
				   		$total_row= mysqli_num_rows($resCATq);
				    echo $total_row ?></div>
                   <div>Date Created: <?php echo gmdate("F j, Y, g:i a",  $infoCAT[5]) ?></div>
                   <div>Last Updated:  <?php echo gmdate("F j, Y, g:i a",  $infoCAT[4]) ?></div>
               </div>    
             <?php } 
			 }?>
    </div>
    
    	
    
</body>
</html>