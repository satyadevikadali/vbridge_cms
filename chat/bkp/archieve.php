<?php

include_once 'dbConfig.php'; 
 $receiver_id = $_POST['receiver_id'];
 $sender_id=$_POST['sender_id'];

 $from_date=date('Y-m-d H:i');
 $to_date=date('Y-m-d H:i', strtotime("-30 days"));

$sql = "SELECT id,sender_id,receiver_id,message,attachment_name,file_ext,mime_type,message_date_time,ip_address FROM `chat` WHERE `sender_id` = '$receiver_id' AND `receiver_id` = '$sender_id' OR `sender_id` = '$sender_id' AND `receiver_id` = '$receiver_id' AND `sender_id`= '$sender_id' AND `receiver_id` = '$receiver_id' OR `sender_id`= '$receiver_id' AND `receiver_id` = '$sender_id' AND `message_date_time` >= '$from_date' AND `message_date_time` <= '$to_date'";

$archievemessages = $db->query($sql);
while($archieve = $archievemessages->fetch_assoc()) {
unset($archieve['id']);

extract($archieve);

 $sql1 = "INSERT INTO chat_archive (sender_id, receiver_id, message,attachment_name,file_ext,mime_type,message_date_time,ip_address) VALUES ('$sender_id',
  '$receiver_id', '$message','$attachment_name','$file_ext','$mime_type','$message_date_time','$p_address')";

	
	$sql2 = " DELETE FROM chat WHERE `sender_id` = '$receiver_id' AND `receiver_id` = '$sender_id' OR `sender_id` = '$sender_id' AND `receiver_id` = '$receiver_id' AND `sender_id`= '$sender_id' AND `receiver_id` = '$receiver_id' OR `sender_id`= '$receiver_id' AND `receiver_id` = '$sender_id'";

if(($db->query($sql1)===TRUE) &&  ($db->query($sql2)===TRUE)
{

echo 'sucess';exit;
//return true;

}
else
{
return false;
}

}

?>