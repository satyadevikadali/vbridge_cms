<?php
	include_once 'dbConfig.php'; 

	if(isset($_GET['user_id']) && $_GET['user_id']!=''){ 
	//$user=mysql_real_escape_string($_GET['user_id']);  
	$user_id=$_GET['user_id'];	
	$qry=mysqli_query($db,"select COUNT(id) as msg_cnt from chat WHERE sender_id = '$user_id' AND msg_status=1");

	$cnt=mysqli_num_rows($qry);
	if($cnt>0)
	{
		$rows = array();
		 //$r = mysql_fetch_assoc($qry);
		$r= $qry -> fetch_assoc();
			$rows['cnt']= $r['msg_cnt'];
			$rows['message']=  "success";  
	}else{ 
			$rows['cnt']= "0";
			$rows['message']=  "No New Messages"; 
		 
	}
	echo json_encode($rows); 
	}
?>
