<?php $session_value=(isset($sessionData['id']))?$sessionData['id']:''; 
//$session_value=(isset($sessionData['id']))?$sessionData['id']:'';
//$title=(isset($_SESSION['username']))?$_SESSION['username']:'';


?>
<style>
.mail{float: left;padding-left: 11px;font-size: 13px;width: 111px;}
.mail1{float: left;padding-left: 11px;padding-top: 0px;position: absolute;font-size: 13px;line-height: 16px;font-weight: 400;}
.gap{margin-left: -30px;margin-top: 10px;}
.messagecolor{color:#5a5454;}
.mailcolor{}
form.example{margin: 3px 4px;}
form.example input[type=text] {padding: 10px;font-size: 12px;border: 1px solid #d0d0d0;float: left;width: 80%;background: #f1f1f1;border-radius: 1px;}
form.example button {float: left;width: 20%;padding: 5px;background: #5370f7;color: white;font-size: 17px;border: 1px solid #d0d0d0;border-left: none;cursor: pointer;}
form.example button:hover {background: #0b7dda;}
form.example::after {content: "";clear: both;display: table;}
.main-sidebar{top: 15px;padding-top: 0px;background-color: #fff;border: 1px solid #d0d0d0;min-height: 81%;}
.user-panel {position: relative;width: 100%;padding: 10px;overflow: hidden;background-color: #5370f7 !important;color: #fff !important;min-height:65px;max-height:80px;}
.user-panel > .info > a{color:#fff;}
.user-panel > .image > img{border:1px solid #fff;width: 40px;height: 40px;margin: 2px 10px;}
.content {min-height: 250px;padding: 0px;margin-right: auto;
margin-left: auto;padding-left: 13px;padding-right: 0px;border: 0px solid #d0d0d0;border-left: 0px !important;margin-top: 15px;}
/* .content {min-height: 250px;padding: 0px;margin-right: auto;
margin-left: auto;padding-left: 0px;padding-right: 0px;border: 1px solid #d0d0d0;border-left: 0px !important;margin-top: 15px;} */
.sidebar-menu li.header {padding: 2px 25px 2px 15px;font-size: 12px;color: #fff;background-color: #000;text-align: center;}
.sidebar-menu > li {position: relative;margin: 0;padding: 0;border-bottom: 1px solid #d0d0d09c;}
.box.box-warning {border-top-color: #8b9ffa;}
.sidebar-menu > li > a {padding: 4px 5px 4px 10px;display: block;}
/* .sidebar-menu > li >a:hover {background-color: #ededed !important;color: #000 !important;} */
.sidebar-menu > li >a.active {color: #000 !important;}
.sidebar-menu >li.active {background-color: #8b9ffa !important;} 
.sidebar-menu >li.active .mail1 {color: #fff !important;}
.sidebar-menu >li.active img {border:1px solid #fff !important;}
.btn-success {background-color: #5370f7;border-color: #5370f7;margin-left: 5px !important;}
.btn-info {background-color: #8b9ffa;border-color: #8b9ffa;}
p {  margin: auto!important; }
.compose{background-color: transparent;color: #fff;border-color: transparent !important;font-size: 18px !important;position: relative !important;top: 5px !important;right: 7px !important;padding:5px 5px 4px 8px !important;border-radius: 50%;}
.compose:hover{background-color: #8b9ffa !important;color: #fff !important;padding:5px 5px 4px 8px !important;border-radius:50% !important;}

@media screen and (min-device-width: 768px) and (max-device-width: 1024px) {
  .main-sidebar{min-height:68% !important;}
}
</style>

<aside class="main-sidebar"> 
<?php 
include('include/index.php');
//session_start();
//echo $sender_id=$_SESSION["sender_id"];
?>
  <!-- sidebar: style can be found in sidebar.less --> 
  <section class="sidebar"> 
    <!-- Sidebar user panel --> 
    <div class="user-panel">
      <div class="pull-left image">
        <?php         	
 $uriSegments = explode("/", parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
  $uriSegments[3]; 
 if($sessionData['user_profile_pic']!='')
 {
 $profile_pic="https://vbridgehub.com/portal/".$sessionData['user_profile_pic'];
 } 
 else{ 
$profile_pic="https://vbridgehub.com/portal/chat/default-logo.png";
 } 
?>
      <img src="<?php echo $profile_pic;?>" class="img-circle profileImgUrl" alt="User Image">
  </div>
      <div class="pull-left info">
        <p class="NameEdt">
        <?php //echo $userProfileName;?>
		  <?php echo $sessionData['username'];?>
        </p>
       <!-- <a href="#"><i class="fa fa-circle text-success"></i> Online</a> --->
		</div>
		
<div class="pull-right topbtn">
<a href="javascript:void(0);" class="compose" alt="New Message" title="Compose New Message"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
</div>
		
    </div> 
    <!-- sidebar menu: : style can be found in sidebar.less --> 
    <?php //$uri=$this->uri->segment(1).'/'.$this->uri->segment(2)?>
    <ul class="sidebar-menu" data-widget="tree">
     <!-- <li class="header">Your Connections</li>  -->
     <!--  <li class="treeview"> <a href="#"> <i class="fa fa-users"></i> <span>Users</span> <span class="pull-right-container"> <i class="fa fa-angle-left pull-right"></i> </span> </a>
        <ul class="treeview-menu">
          <li class=""> <a href=""><i class="fa fa-list"></i>List</a></li>
        </ul>
      </li> -->  
<form class="example">		
<input class="form-control" placeholder="Search.." name="search_data" id="search_data" type="text" onkeyup="ajaxSearch();">
<button type="submit"><i class="fa fa-search"></i></button>
</form> 
<ul class="sidebar-menu search tabs" data-widget="tree" id="search"> 
<?php  
$url_connections ="https://vbridgehub.com/portal/user_connections_api.php?userid=".$sessionData['id'];
$ch_connections = curl_init();
curl_setopt($ch_connections, CURLOPT_URL, $url_connections);
curl_setopt($ch_connections, CURLOPT_HEADER, 0);
curl_setopt($ch_connections, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch_connections, CURLOPT_SSL_VERIFYHOST, false); 
curl_setopt($ch_connections, CURLOPT_SSL_VERIFYPEER, false); 
$result_connections = curl_exec($ch_connections); // TODO - UNCOMMENT IN PRODUCTION 
curl_close ($ch_connections); 
$vendorslist1=json_decode($result_connections,true); 
$apiVendorList=$vendorslist1['data']; 
// echo "<pre>";
// print_r($apiVendorList);exit;
foreach($apiVendorList as $v):
session_start();

//$_SESSION["username"] = $v['username'];
//include_once 'dbConfig.php'; 

$mysqli = new mysqli("localhost", "Vbridge_stg", "Khip559@", "Vbridge_stg");
$sender_id=$sessionData['id'];
//$sql = "SELECT message,sender_id FROM chat WHERE id IN (SELECT MAX(id) FROM chat WHERE receiver_id='".$sender_id."' GROUP BY sender_id)";
$sql="SELECT * FROM chat WHERE (receiver_id='".$sender_id."' OR sender_id='".$sender_id."') AND (receiver_id='".$v['user_id']."' OR sender_id='".$v['user_id']."') AND message!='NULL' ORDER BY id DESC limit 1";

$result = mysqli_query($mysqli,$sql); 
$latest = mysqli_fetch_array($result);

//print_r($latest);exit;

?>

<?php //if($latest['sender_id']== $sessionData['id']){?>
<p id='<?php //echo $sessionData['id'];?>'></p>
<li class="selectVendor<?php if($uriSegments[3]=='messaging'.'#id='.$v['user_id']){ echo 'active';}?>" id="<?php echo $sessionData['id'];?>" title="<?=$v['username'];?>" data-sender="<?=$v['user_id'];?>" company="<?=$v['company_name'];?>">
<a class="users-list-name" href="https://vbridgehub.com/portal/index.php/messaging#id=<?php echo $v['user_id'];?>" id="<?=$v['user_id'];?>">

<img src="https://vbridgehub.com/portal/<?= $v['profile_pic']; ?>" alt="<?=$v['username'];?>" title="<?=$v['username'];?>" width="50px" height="50px" style="border-radius:50%;">
<span class="mail1"><p><?=$v['username']."</p><p style='font-weight:normal;font-size:12px;color:#000;'>".$v['company_name']."</p><p style='font-weight:normal;font-size:12px;color:#808080;'>".$latest['message'];?></p>
</span>

</a>
<!--<span class="users-list-date">Yesterday</span>-->
</li> 
<?php // } ?>

<?php endforeach;?>  
</ul> 


  </section> 
  <!-- /.sidebar --> 
</aside>

<script src="/portal/chat/public/components/jquery/dist/jquery.min.js"></script>
<script type="text/javascript">
$(function() {
$('.message').keypress(function(event){
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if(keycode == '13'){
       sendTxtMessage($(this).val());
    }
});

$('.btnSend').click(function(){

sendTxtMessage( 
	$('.message').val()
		
	); 
});


$('.btnSend1').click(function(){
sendTxtMessage1( 
	$('.message1').val()
		
	); 
});


$('.selectVendor').click(function(){
	ChatSection(1);	
	var receiver_id = $(this).attr('id'); 
	var sender_id= $(this).data('sender');


	$('#ReciverId_txt').val(receiver_id);
	$('#ReciverName_txt').html($(this).attr('title'));	
	$('#CompanyName_txt').html($(this).attr('company'));	
	GetChatHistory(receiver_id,sender_id); 
}); 
$('.upload_attachmentfile').change(function(){
	DisplayMessage('<div class="spiner"><img src="https://vbridgehub.com/portal/chat/loader.gif"></div>');
	//DisplayMessage('<div class="spiner"><i class="fa fa-circle-o-notch fa-spin"></i></div>');
	ScrollDown();
	var file_data = $('.upload_attachmentfile').prop('files')[0];		
	var receiver_id = $('#ReciverId_txt').val();  
	var sender_id = $('#SenderId_txt').val();  
	//alert(receiver_id); 
	//alert(file_data); 
    var form_data = new FormData();
    form_data.append('attachmentfile', file_data);
	form_data.append('type', 'Attachment');
	form_data.append('receiver_id', receiver_id);
	form_data.append('sender_id', sender_id); 
	$.ajax({
			url: 'https://vbridgehub.com/portal/chat/submit.php',   
			cache: false,
			contentType: false,
			processData: false,
			data: form_data,                        
			type: 'post',
			success: function(response){
				
				$('.upload_attachmentfile').val('');
				GetChatHistory(receiver_id,sender_id);
			},
			error: function (jqXHR, status, err) {
		   
			}
	 });
});

$(function() {
setInterval(function(){ 
	 //alert('hi');
	var receiver_id = $('#ReciverId_txt').val();
	var sender_id= $('#SenderId_txt').val();	
	if(receiver_id!='' && sender_id!=''){GetChatHistory5(receiver_id,sender_id);}
}, 5000); 


});

$('.ClearChat').click(function(){
var receiver_id = $('#ReciverId_txt').val();
$.ajax({
//dataType : "json",
url: 'https://vbridgehub.com/portal/chat/chat-clear?receiver_id='+receiver_id,
success:function(data)
{
GetChatHistory(receiver_id);		 
},
error: function (jqXHR, status, err) {
// alert('Local error callback');
}
}); 				

}); 

});	///end of jquery 
function ViewAttachment(message_id){
//alert(message_id);
	$.ajax({
			  //dataType : "json",
			  url: 'https://vbridgehub.com/portal/chat/view-chat-attachment?message_id='+message_id,
			  success:function(data)
			  {
						 
			  },
			  error: function (jqXHR, status, err) {
				 // alert('Local error callback');
			  }
		});
}
function ViewAttachmentImage(image_url,imageTitle){
	$('#modelTitle').html(imageTitle); 
	$('#modalImgs').attr('src',image_url);
	$('#myModalImg').modal('show');
}

function ChatSection(status){
	//chatSection
	if(status==0){
		$('#chatSection :input').attr('disabled', true);
    } else {
        $('#chatSection :input').removeAttr('disabled');
    }   
}
ChatSection(0);


function ScrollDown(){
	var elmnt = document.getElementById("content1");
	var h = elmnt.scrollHeight;
	$('#content1').animate({scrollTop: h}, 15);
}
window.onload = ScrollDown();

function DisplayMessage(message){ 
	var Sender_Name = $('#Sender_Name').val(); 
	var Sender_ProfilePic = $('#Sender_ProfilePic').val(); 
	var str = '<div class="direct-chat-msg left">';
	str+='<div class="direct-chat-info clearfix">';
	str+='<span class="direct-chat-name pull-left">'+Sender_Name ;
	str+='</span><span class="direct-chat-timestamp pull-left"></span>'; //23 Jan 2:05 pm
	str+='</div><img class="direct-chat-img" src="'+Sender_ProfilePic+'" alt="">';
	str+='<div class="direct-chat-text">'+message;
	str+='</div></div>'; 
	$('#dumppy').append(str); 
}
function DisplayMessage1(message){ 
	//alert(message);
	var Sender_Name = $('#Sender_Name').val(); 
	var Sender_ProfilePic = $('#Sender_ProfilePic').val(); 
	var str = '<div class="direct-chat-msg left">';
	str+='<div class="direct-chat-info clearfix">';
	str+='<span class="direct-chat-name pull-left">'+Sender_Name ;
	str+='</span><span class="direct-chat-timestamp pull-left"></span>'; //23 Jan 2:05 pm
	str+='</div><img class="direct-chat-img" src="'+Sender_ProfilePic+'" alt="">';
	str+='<div class="direct-chat-text">'+message;
	str+='</div></div>'; 
	$('#dumppy1').append(str); 
}
function chatLoader(message){ 
	var str = '<div class="direct-chat-msg left">'; 
	str+='<div class="direct-chat-text">'+message;
	str+='</div></div>'; 
	$('#dumppy').append(str); 
}
function sendTxtMessage(message){
	var messageTxt = message.trim();
	if(messageTxt!=''){
		
//console.log(message);

DisplayMessage(messageTxt);
		
var receiver_id = $('#ReciverId_txt').val();	
var sender_id = $('#SenderId_txt').val();

//console.log(sender_id);
$.ajax({
dataType : "json",
type : 'post',
data : {messageTxt : messageTxt, receiver_id : receiver_id, sender_id:sender_id},
url: 'https://vbridgehub.com/portal/chat/submit.php',
success:function(data)
{
ScrollDown();
//GetChatHistory(receiver_id,sender_id);		 
},
error: function (jqXHR, status, err) {
// alert('Local error callback');
}
});
//ScrollDown();
		$('.message').val('');
		$('.message').focus();
	}else{
		$('.message').focus();
	}} 

function sendTxtMessage1(message){
var messageTxt = message.trim();
if(messageTxt!=''){

//console.log(message);

DisplayMessage1(messageTxt);

var receiver_id = $('#ReciverId_txt').val();	
var sender_id1 = $('.js-example-basic-multiple').val();
var sender_id=JSON.parse(sender_id1);
//console.log(sender_id);
//console.log(sender_id);
$.ajax({
dataType : "json",
type : 'post',
data : {messageTxt : messageTxt, receiver_id : receiver_id, sender_id:sender_id},
url: 'https://vbridgehub.com/portal/chat/submit.php',
success:function(data)
{
ScrollDown();
//GetChatHistory1(receiver_id,sender_id);		 
},
error: function (jqXHR, status, err) {
// alert('Local error callback');
}
});
//ScrollDown();
$('.message1').val('');
$('.message1').focus();
}else{
$('.message1').focus();
}} 
function GetChatHistory(receiver_id,sender_id){
	chatLoader('<div class="spiner"><img src="https://vbridgehub.com/portal/chat/loader.gif"></div>');
	//chatLoader('<div class="spiner"><i class="fa fa-circle-o-notch fa-spin"></i></div>');
	$.ajax({
		//dataType : "json",
		url: 'https://vbridgehub.com/portal/chat/get-chat-history.php',
		type:'GET',
		data:{sender_id:sender_id,receiver_id:receiver_id},
		success:function(data)
		{
		$('#dumppy').html(data);
		ScrollDown();	 
		},
		error: function (jqXHR, status, err) {
			console.log("Came to Error");
		// alert('Local error callback');
		}
	});
}

function GetChatHistory5(receiver_id,sender_id){
	
	$.ajax({
		//dataType : "json",
		url: 'https://vbridgehub.com/portal/chat/get-chat-history.php',
		type:'GET',
		data:{sender_id:sender_id,receiver_id:receiver_id},
		success:function(data)
		{
		$('#dumppy').html(data);
		ScrollDown();	 
		},
		error: function (jqXHR, status, err) {
			//console.log("Came to Error");
		// alert('Local error callback');
		}
	});
}

function GetChatHistory1(receiver_id,sender_id){

	//alert('chat Histrory');
	chatLoader('<div class="spiner"><img src="https://vbridgehub.com/portal/chat/loader.gif"></div>');
	var sender_id=JSON.parse(sender_id);
	//chatLoader('<div class="spiner"><i class="fa fa-circle-o-notch fa-spin"></i></div>');
	$.ajax({
		//dataType : "json",
		url: 'https://vbridgehub.com/portal/chat/get-chat-history.php',
		type:'GET',
		data:{sender_id:sender_id,receiver_id:receiver_id},
		success:function(data)
		{
		$('#dumppy1').html(data);
		ScrollDown();	 
		},
		error: function (jqXHR, status, err) {
			console.log("Came to Error");
		// alert('Local error callback');
		}
	});
}

// $(function() {
// setInterval(function(){ 
	 
// 	//alert('hi');

// 	var receiver_id = $('#ReciverId_txt').val();
// 	var sender_id= $('#SenderId_txt').val();
	
// 	if(receiver_id!='' && sender_id!=''){GetChatHistory5(receiver_id,sender_id);}
// }, 3500); 
// });


</script>

<script type="text/javascript">
function ajaxSearch()
{

var input, filter, ul, li, a, i, txtValue;
input = document.getElementById("search_data");
filter = input.value.toUpperCase();
ul = document.getElementById("search");
li = ul.getElementsByTagName("li");
for (i = 0; i < li.length; i++) {
a = li[i].getElementsByTagName("a")[0];
txtValue = a.textContent || a.innerText;
if (txtValue.toUpperCase().indexOf(filter) > -1) {
li[i].style.display = "";
} else {
li[i].style.display = "none";
}
}

}
 
$(document).ready(function(){

var receiver_id='<?php echo $session_value;?>';

$.ajax({
type: "POST",
data: {receiver_id:receiver_id},
url: 'https://vbridgehub.com/portal/chat/recentchat.php',
success: function (data) {
var id=JSON.parse(data);
//window.location.href = "https://vbridgehub.com/portal/index.php/messaging#id="+id;
$('li[data-sender='+id+']').addClass('active');
$('#SenderId_txt').val(id);
$('li[data-sender='+id+']').click();
//GetChatHistory(receiver_id,JSON.parse(data));                      

}
}); 

});

</script> 
<script type="text/javascript">
	
</script>

</html>