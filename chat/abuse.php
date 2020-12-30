<?php
if(isset($_POST['sender_id']) && !empty($_POST['abuse_message']) && !empty($_POST['name'])){
    
    // Submitted form data
$name = $_POST['name'];
$sender_id   = $_POST['sender_id'];
$sender_id   = $_POST['sender_id'];
$email  = 'haribabuyeturi@gmail.com';    
$message= $_POST['abuse_message'];

   //exit;
    
    /*
     * Send email to admin
     */
include "classes/class.phpmailer.php";
$mail = new PHPMailer(); // create a new object


//Enable SMTP debugging. 
$mail->SMTPDebug = 1;                               
//Set PHPMailer to use SMTP.
$mail->isSMTP();            
//Set SMTP host name                          
$mail->Host = "smtp.gmail.com";
//Set this to true if SMTP host requires authentication to send email
$mail->SMTPAuth = true;                          
//Provide username and password     
$mail->Username = "haribabuyeturi@gmail.com";                 
$mail->Password = "hari@#1215";                           
//If SMTP requires TLS encryption then set it
$mail->SMTPSecure = "tls";                           
//Set TCP port to connect to 
$mail->Port = 587;                                   

$mail->From = "helpdesk@vbridgehub.com";
$mail->FromName = "V-Bridge Hub";

$mail->addAddress("helpdesk@vbridgehub.com", "Admin");

$mail->isHTML(true);

$mail->Subject = "Abuse Request Submitted";
$mail->Body = '<h4>Abuse request has submitted at Chat, details are given below.</h4>
<table cellspacing="0" style="width: 300px; height: 200px;">
 <tr>
<th>Name:</th><td>'.$name.'</td>
</tr>
 <tr>
<th>Sender ID:</th><td>'.$sender_id.'</td>
</tr>
<tr style="background-color: #e0e0e0;">
 <th>Email:</th><td>'.$email.'</td>
 </tr>
 <tr>
 <th>Message:</th><td>'.$message.'</td>
</tr>
 </table>';
$mail->AltBody = "This is the plain text version of the email content";

if($mail->send()) 
{
   
     echo "Message has been sent successfully";
} 
else 
{
    echo "Mailer Error: " . $mail->ErrorInfo;
}


}

