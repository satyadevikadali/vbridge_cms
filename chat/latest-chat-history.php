<?php
include_once 'dbConfig.php'; 
$url=$_SERVER['SERVER_NAME'];//JPATH_BASE . DS;
 if($url=='stgportal.vbridgehub.com'){
	$basePath="https://".$url.'/';
 }else{
	$basePath="https://".$url."/";
 }
   $receiver_id = $_GET['receiver_id'];
   $sender_id=$_GET['sender_id'];
   $last_id=$_GET['last_id'];
   if($last_id!=''){ $wh=" AND id > '$last_id'";}else{ $wh="";}
	$Logged_sender_id = $sender_id;
$var1 = $db->query("SELECT COUNT(id) as cnt FROM chat where ((`sender_id`= '$sender_id' AND `receiver_id` = '$receiver_id') OR (`sender_id`= '$receiver_id' AND `receiver_id` = '$sender_id')) $wh")->fetch_assoc();

// $totCnt = $var1['cnt']-10;

// if($totCnt >= 0)
// {
// $totCnt = $var1['cnt']-10;
// }
// else
// {
// $totCnt = abs($totCnt);
// }

 $sql = "SELECT *,DATE_FORMAT(message_date_time, '%Y-%m-%d') as dOfChat FROM chat where ((`sender_id`= '$sender_id' AND `receiver_id` = '$receiver_id') OR (`sender_id`= '$receiver_id' AND `receiver_id` = '$sender_id')) $wh ORDER BY id ASC LIMIT 0,10";
// echo $var1['cnt'];
$update=$db->query("UPDATE chat SET msg_status = '0' WHERE sender_id = '$receiver_id' AND msg_status=1 $wh ORDER BY id ASC");
$history = $db->query($sql);  
// $chat = $history->fetch_assoc();
	if($var1['cnt']>0)
	{
	$dateArray = array();
	$key = 0;
// echo "<pre>";
 while($chat = $history->fetch_assoc())
 {

// print_r($chat);exit;
      $message_id = $chat['id'];
      $sender_id = $chat['sender_id']; 
	  $receiver_id = $chat['receiver_id']; 
$url=$_SERVER['SERVER_NAME'];//JPATH_BASE . DS;
 if($url=='vbridgehub.com'){
	$basePath="https://".$url.'/portal/';
 }else{
	$basePath="https://".$url."/";
 }	  
/* Sender Profile info*/
$url2=$basePath."login_userdetails_api.php?userid=$receiver_id";
$ch1 = curl_init();
curl_setopt($ch1, CURLOPT_URL, $url2);
//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); 
curl_setopt($ch1, CURLOPT_HEADER, 0);
curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch1, CURLOPT_CONNECTTIMEOUT, 10);
// Execute the cURL request for a maximum of 50 seconds
curl_setopt($ch1, CURLOPT_TIMEOUT, 50);
curl_setopt($ch1, CURLOPT_SSL_VERIFYHOST, false); 
curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false); 
$result2 = curl_exec($ch1); // TODO - UNCOMMENT IN PRODUCTION 
curl_close ($ch1);
$userInfo2=json_decode($result2,true);
$userInfoRow2=$userInfo2['data'];  
$profile_pic2=$basePath.$userInfoRow2['profile_pic'];  
$userName2 = $userInfoRow2['username'];
$userPic2 = $profile_pic2; 
/* End of Sender Profile inf*/

// $userName='';
// $userPic='';      
      $message = $chat['message'];	  
		
		$dateArray[$key]=$chat['dOfChat'];
		if($key!=0)
		{
			$dateArray = array_unique($dateArray);
			$aCount = count($dateArray);
			if($aCount>1)
			{
				$d1 = end($dateArray);
				$d2 = array_shift($dateArray);
			}
			else
			{
				$d1 = '';
			}
		}
		else
		{
			$d1 = $chat['dOfChat'];
		}
		$key++;
      $messagedatetime = date('H:i A',strtotime($chat['message_date_time']));
         // $history = $this->ChatModel->GetReciverChatHistory($receiver_id);
				$messageBody='';
            	if($message=='NULL'){ //fetach media objects like images,pdf,documents etc
					$classBtn = 'right';
					if($Logged_sender_id==$sender_id){$classBtn = 'left';}
					$attachment_name = $chat['attachment_name'];
					$file_ext = $chat['file_ext'];
					$mime_type = explode('/',$chat['mime_type']);
					$document_url =  $basePath.'chat/uploads/'.$attachment_name;
				  if($mime_type[0]=='image'){
 					$messageBody.='<img src="'.$document_url.'" onClick="ViewAttachmentImage('."'".$document_url."'".','."'".$attachment_name."'".');" class="attachmentImgCls">';	
				  }else{
					$messageBody='';
					 $messageBody.='<div class="attachment">';
                          $messageBody.='<h5>Attachments:</h5>';
                           $messageBody.='<p class="filename">';
                            $messageBody.= $attachment_name;
                          $messageBody.='</p>'; 
                          //$messageBody.='<div class="pull-'.$classBtn.'">';
						  $messageBody.='<div class="pull-right">';
                            $messageBody.='<a download href="'.$document_url.'"><button type="button" id="'.$message_id.'" class="btn btn-primary btn-sm btn-flat btnFileOpen">Open</button></a>';
                          $messageBody.='</div>';
                        $messageBody.='</div>';
					}					
				}else{
					$messageBody = $message;
				}
			?>
                    <div class="direct-chat-msg left post-id" id="<?php echo $chat['id']; ?>">
                      <div class="direct-chat-info clearfix">
						<?php if($d1!=''){?>
						<div class="line">
							<h5><?php echo date('j F Y', strtotime($d1));?></h5>
						</div>
						<?php } ?>
                        <span class="direct-chat-name pull-left" ><?=$userName2;?></span>
                        <span class="direct-chat-timestamp pull-right"><?=$messagedatetime;?></span>
                      </div>

                      <img class="direct-chat-img" src="<?=$userPic2;?>" alt="<?=$userName2;?>">
                      <!-- /.direct-chat-img -->
                      <div class="direct-chat-text">
                      	<?=$messageBody;?>
                       </div>
                       <!-- /.direct-chat-text -->
                    </div>
                    <!-- /.direct-chat-msg -->
      <?php } 
	}
?>