<?php 
session_start();
$uploadDir = 'uploads/'; 
$response = array( 
    'status' => 0, 
    'message' => 'Form submission failed, please try again.' 
); 
 
 $message='';
 if (!empty($_SERVER['HTTP_CLIENT_IP']))   
  {
    $ip_address = $_SERVER['HTTP_CLIENT_IP'];
  }
//whether ip is from proxy
elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))  
  {
    $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
  }
//whether ip is from remote address
else
  {
    $ip_address = $_SERVER['REMOTE_ADDR'];
  }

     $ip_address= $ip_address;
     $message_date_time= date('Y-m-d H:i:s');

$_SESSION['sender_id']=$_POST['sender_id'];
// If form is submitted 
if(isset($_POST['sender_id']) && isset($_POST['receiver_id']) && isset($_POST['messageTxt'])){ 
    // Get the submitted form data 
   


  $sender_id=$_POST['sender_id'];
  $receiver_id=$_POST['receiver_id'];
 $message=$_POST['messageTxt'];
 


    // Check whether submitted data is not empty 
    if(!empty($message)){ 
        // Validate email 
 
            $uploadStatus = 1; 
             
         
            if($uploadStatus == 1){ 
                // Include the database config file 
                include_once 'dbConfig.php'; 
                 
                // Insert form data in the database 
$insert = $db->query("INSERT INTO chat (sender_id,receiver_id,message,attachment_name,file_ext,message_date_time,ip_address) VALUES ('".$sender_id."','".$receiver_id."','".$message."','','','".$message_date_time."','".$ip_address."')"); 

                if($insert){ 
                    $response['status'] = 1; 
                    $response['message'] = 'Form data submitted successfully!'; 
                } 
            } 
          
  
    
    }

}else{ 

        if(isset($_POST['type'])=='Attachment'){
            $message='';
        $sender_id=$_POST['sender_id'];
            $receiver_id=$_POST['receiver_id'];
            $uploadStatus = 1; 
            $fileType='';
            $uploadedFile = ''; 
            if(!empty($_FILES["attachmentfile"]["name"])){ 
                
                 
                // File path config 
                $fileName = basename($_FILES["attachmentfile"]["name"]); 
                $targetFilePath = $uploadDir . $fileName;


                $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 

                   /*$finfo = finfo_open(FILEINFO_MIME_TYPE);
                   $mime = finfo_file($finfo, $_FILES["attachmentfile"]["name"]);*/
                   $fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);
                   $mime= $_FILES["attachmentfile"]["type"];
                  
                 
                // Allow certain file formats 
                 $allowTypes = array('pdf', 'doc', 'docx', 'jpg', 'png', 'jpeg', 'xlsx', 'pptx', 'rtf'); 
                if(in_array($fileType, $allowTypes)){ 
                    // Upload file to the server 
                    if(move_uploaded_file($_FILES["attachmentfile"]["tmp_name"], $targetFilePath)){ 
                        $uploadedFile = $fileName; 
                        $uploadStatus = 1;
                    }else{ 
                        $uploadStatus = 0; 
                        $response['message'] = 'Sorry, there was an error uploading your file.'; 
                    } 
                }else{ 
                    $uploadStatus = 0; 
                    $response['message'] = 'Sorry, only PDF, DOC, JPG, JPEG, & PNG files are allowed to upload.'; 
                } 
            } 



            if($uploadStatus == 1){ 
                // Include the database config file 
                include_once 'dbConfig.php'; 
                 
                // Insert form data in the database 
$insert = $db->query("INSERT INTO chat (sender_id,receiver_id,message,attachment_name,file_ext,mime_type,message_date_time,ip_address) VALUES ('".$sender_id."','".$receiver_id."','".'NULL'."','".$uploadedFile."','".$fileType."','".$mime."','".$message_date_time."','".$ip_address."')"); 



                if($insert){ 
                    
                    $response['status'] = 1; 
                    $response['message'] = 'Form data submitted successfully!'; 
                } 
            } 
    } 



}

 
// Return response 
echo json_encode($response);