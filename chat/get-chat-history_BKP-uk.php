<?php

//print_r($sessionData);
//exit;
//session_start(); 
//$session = JFactory::getSession();
//$sessionData = $session->get('userdata');

 
include_once 'dbConfig.php'; 
   $receiver_id = $_GET['receiver_id'];
   $sender_id=$_GET['sender_id'];

$Logged_sender_id = $sender_id;  
  
//$history = $this->ChatModel->GetReciverChatHistory($receiver_id);
//$Logged_sender_id=$sender_id;
 $sql = "SELECT * FROM chat where `sender_id`= '$sender_id' AND `receiver_id` = '$receiver_id' OR `sender_id`= '$receiver_id' AND `receiver_id` = '$sender_id'"; 
 
 //$sql = "SELECT * FROM chat WHERE `sender_id` = '$receiver_id' AND `receiver_id` = '$sender_id' OR `sender_id` = '$sender_id' AND `receiver_id` = '$receiver_id' AND `sender_id`= '$sender_id' AND `receiver_id` = '$receiver_id' OR `sender_id`= '$receiver_id' AND `receiver_id` = '$sender_id'"; 
$history = $db->query($sql);  
$chat = $history->fetch_assoc(); 
$chat_receiver_id = $receiver_id;//$chat['receiver_id']; 
$chat_sender_id = $sender_id;//$chat['sender_id']; 

//echo "session".$chat_receiver_id;
//echo "<br>";
//echo $chat_sender_id;

		$url ="https://vbridgehub.com/stgportal/login_userdetails_api.php?userid=$chat_sender_id";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); 
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		// Execute the cURL request for a maximum of 50 seconds
		curl_setopt($ch, CURLOPT_TIMEOUT, 50);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
		$result1 = curl_exec($ch); // TODO - UNCOMMENT IN PRODUCTION 
		curl_close ($ch);
		$userInfo=json_decode($result1,true);
		$userInfoRow=$userInfo['data']; 
		//print_r($userInfoRow); exit; 
		$profile_pic="https://vbridgehub.com/stgportal/".$userInfoRow['profile_pic'];

		//$profile_pic="https://vbridgehub.com/stgportal/".$sessionData['user_profile_pic'];
		$userName = $userInfoRow['username'];
		$userPic = $profile_pic;

/* Sender Profile info*/
$url2="https://vbridgehub.com/stgportal/login_userdetails_api.php?userid=$chat_receiver_id";
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
$profile_pic2="https://vbridgehub.com/stgportal/".$userInfoRow2['profile_pic'];  
$recvUserName = $userInfoRow2['username'];
$recvUserPic2 = $profile_pic2; 
/* End of Sender Profile inf*/

 while($chat = $history->fetch_assoc()) {

//print_r($chat); exit;     
      $message_id = $chat['id'];
      $sender_id = $chat['sender_id']; 
	  $receiver_id = $chat['receiver_id']; 

// $userName='';
// $userPic='';      
      $message = $chat['message'];	  
	
	
      $messagedatetime = date('d M H:i A',strtotime($chat['message_date_time']));
         // $history = $this->ChatModel->GetReciverChatHistory($receiver_id);
				$messageBody='';
            	if($message=='NULL'){ //fetach media objects like images,pdf,documents etc
					$classBtn = 'right';
					if($Logged_sender_id==$sender_id){$classBtn = 'left';}
					$attachment_name = $chat['attachment_name'];
					$file_ext = $chat['file_ext'];
					$mime_type = explode('/',$chat['mime_type']);
					$document_url = 'https://vbridgehub.com/stgportal/chat/uploads/'.$attachment_name;
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
             <?php if($Logged_sender_id!=$sender_id){?>     
                  <!-- Message. Default to the left -->
                    <div class="direct-chat-msg">
                      <div class="direct-chat-info clearfix">
                        <span class="direct-chat-name pull-left"><?=$recvUserName2;?></span>
                        <span class="direct-chat-timestamp pull-right"><?=$messagedatetime;?></span>
                      </div>
                      <!-- /.direct-chat-info -->
                      <img class="direct-chat-img" src="<?=$recvUserPic2;?>" alt="<?=$recvUserName2;?>">
                      <!-- /.direct-chat-img -->
                      <div class="direct-chat-text">
                         <?=$messageBody;?>
                      </div>
                      <!-- /.direct-chat-text -->
                    </div>
                    <!-- /.direct-chat-msg -->
			<?php }else{?>
                    <!-- Message to the right -->
                    <div class="direct-chat-msg left">
                      <div class="direct-chat-info clearfix">
                        <span class="direct-chat-name pull-left" ><?=$userName;?></span>
                        <span class="direct-chat-timestamp pull-right"><?=$messagedatetime;?></span>
                      </div>
                      <!-- /.direct-chat-info -->
                      <img class="direct-chat-img" src="<?=$userPic;?>" alt="<?=$userName;?>">
                      <!-- /.direct-chat-img -->
                      <div class="direct-chat-text">
                      	<?=$messageBody;?>
                          	<!--<div class="spiner">
                             	<i class="fa fa-circle-o-notch fa-spin"></i>
                            </div>-->
                       </div>
                       <!-- /.direct-chat-text -->
                    </div>
                    <!-- /.direct-chat-msg -->
             <?php }?>
      <?php } ?>