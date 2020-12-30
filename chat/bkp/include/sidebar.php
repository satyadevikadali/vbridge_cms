<style>
.mail{float: left;padding-left: 11px;font-size: 13px;width: 111px;}
.mail1{float: left;padding-left: 11px;padding-top: 0px;position: absolute;line-height: 22px;}
.gap{margin-left: -30px;margin-top: 10px;}
.messagecolor{color:#5a5454;}
.mailcolor{}
form.example{margin: 3px 4px;}
form.example input[type=text] {padding: 10px;font-size: 17px;border: 1px solid #d0d0d0;float: left;width: 80%;background: #f1f1f1;border-radius: 1px;}
form.example button {float: left;width: 20%;padding: 5px;background: #5370f7;color: white;font-size: 17px;border: 1px solid #d0d0d0;border-left: none;cursor: pointer;}
form.example button:hover {background: #0b7dda;}
form.example::after {content: "";clear: both;display: table;}
.main-sidebar{top: 15px;padding-top: 0px;background-color: #fff;border: 1px solid #d0d0d0;min-height: 81%;}
.user-panel {position: relative;width: 100%;padding: 10px;overflow: hidden;background-color: #5370f7 !important;color: #fff !important;}
.user-panel > .info > a{color:#fff;}
.user-panel > .image > img{border:1px solid #fff;}
.content {min-height: 250px;padding: 0px;margin-right: auto;
margin-left: auto;padding-left: 0px;padding-right: 0px;border: 1px solid #d0d0d0;border-left: 0px !important;margin-top: 15px;}
.sidebar-menu li.header {padding: 2px 25px 2px 15px;font-size: 12px;color: #fff;background-color: #000;text-align: center;}
.sidebar-menu > li {position: relative;margin: 0;padding: 0;border-bottom: 1px solid #d0d0d09c;}
.box.box-warning {border-top-color: #8b9ffa;}
.sidebar-menu > li > a {padding: 4px 5px 4px 10px;display: block;}
.sidebar-menu > li >a:hover {background-color: #ededed !important;color: #000 !important;}
.sidebar-menu > li >a:active {background-color: #8b9ffa !important;color: #fff !important;}
</style>
<aside class="main-sidebar"> 
  <!-- sidebar: style can be found in sidebar.less --> 
  <section class="sidebar"> 
    <!-- Sidebar user panel --> 
    <div class="user-panel">
      <div class="pull-left image">
        <?php 
			$url ="https://vbridgehub.com/stgportal/login_userdetails_api.php?userid=399";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); 
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
			$profile_pic="https://vbridgehub.com/stgportal/".$userInfoRow['profile_pic']; 
		?>
      <img src="<?= $profile_pic; ?>" class="img-circle profileImgUrl" alt="User Image"> </div>
      <div class="pull-right info">
        <p class="NameEdt">
        <?php echo $userInfoRow['username'];?>
        </p>
        <a href="#"><i class="fa fa-circle text-success"></i> Online</a> </div> 
    </div>  
    <?php //$uri=$this->uri->segment(1).'/'.$this->uri->segment(2)?>
    <ul class="sidebar-menu" data-widget="tree">
      <li class="header">Team Members</li>  
		<form class="example">		
			<input class="form-control" placeholder="Search.." name="search_data" id="search_data" type="text" onkeyup="ajaxSearch();"><button type="submit"><i class="fa fa-search"></i></button>
		</form> 
   <ul class="sidebar-menu search tabs" data-widget="tree" id="search">

	<?php  
		$url_connections ="https://vbridgehub.com/stgportal/user_connections_api.php?userid=399";
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
		foreach($apiVendorList as $v): 
	?> 
		<li class="selectVendor" id="<?php echo $userInfoRow['userid'];?>" title="<?=$v['username'];?>" data-sender="<?=$v['user_id'];?>">
		<a class="users-list-name" href="#id=<?php echo $v['user_id'];?>" id="<?=$v['user_id'];?>"> 
		<img src="https://vbridgehub.com/stgportal/<?= $v['profile_pic']; ?>" alt="<?=$v['username'];?>" title="<?=$v['username'];?>" width="20%" height="20%" style="border-radius:50%;">
		<span class="mail1"><?=$v['username'];?><br><?=$v['company_name'];?> </span>
		<!--span class="mail1"></span-->
		
		
</a> 
</li> 
<?php endforeach;?>   
   </ul> 
   <ul class="sidebar-menu search" data-widget="tree" style="display:none" id="search-result"></ul>
  </section> 
  <!-- /.sidebar --> 
</aside> 
<script src="/stgportal/templates/sj_shoppystore/public/components/jquery/dist/jquery.min.js"></script>
<!--script src="/stgportal/templates/sj_shoppystore/public/chat/chat.js"></script-->
<script>
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
//var file_data = $('#file').prop('files')[0],
//var form_data = new FormData(),
//form_data.append('attachmentfile', file_data),
//form_data.append('type', 'Attachment'),

	);

});


$('.selectVendor').click(function(){
	//alert('hai');
ChatSection(1);	
var receiver_id = $(this).attr('id'); 
var sender_id= $(this).data('sender');


$('#ReciverId_txt').val(receiver_id);
$('#ReciverName_txt').html($(this).attr('title'));	
GetChatHistory(receiver_id,sender_id);
 				
});


$('.upload_attachmentfile').change(function(){

    
	
	DisplayMessage('<div class="spiner"><i class="fa fa-circle-o-notch fa-spin"></i></div>');
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
                url: './submit.php',   
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


$('.ClearChat').click(function(){
var receiver_id = $('#ReciverId_txt').val();
$.ajax({
//dataType : "json",
url: 'chat-clear?receiver_id='+receiver_id,
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
  						  url: 'view-chat-attachment?message_id='+message_id,
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
	var elmnt = document.getElementById("content");
    var h = elmnt.scrollHeight;
   $('#content').animate({scrollTop: h}, 1000);
}
window.onload = ScrollDown();

function DisplayMessage(message){

var Sender_Name = $('#Sender_Name').val();

var Sender_ProfilePic = $('#Sender_ProfilePic').val();

var str = '<div class="direct-chat-msg right">';
str+='<div class="direct-chat-info clearfix">';
str+='<span class="direct-chat-name pull-right">'+Sender_Name ;
str+='</span><span class="direct-chat-timestamp pull-left"></span>'; //23 Jan 2:05 pm
str+='</div><img class="direct-chat-img" src="'+Sender_ProfilePic+'" alt="">';
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

 


$.ajax({
dataType : "json",
type : 'post',
data : {messageTxt : messageTxt, receiver_id : receiver_id, sender_id:sender_id},
url: './submit.php',
success:function(data)
{
GetChatHistory(receiver_id,sender_id)		 
},
error: function (jqXHR, status, err) {
// alert('Local error callback');
}
});

		
		
		ScrollDown();
		$('.message').val('');
		$('.message').focus();
	}else{
		$('.message').focus();
	}
}

function GetChatHistory(receiver_id,sender_id){
$.ajax({
//dataType : "json",
 url: 'get-chat-history.php',
//url: 'https://vbridgehub.com/stgportal/index.php/chat-history?tmpl=component',
type:'GET',
data:{sender_id:sender_id,receiver_id:receiver_id},
success:function(data)
{
$('#dumppy').html(data);
ScrollDown();	 
},
error: function (jqXHR, status, err) {
// alert('Local error callback');
}
});
}

setInterval(function(){ 
	var receiver_id = $('#ReciverId_txt').val();
	if(receiver_id!=''){GetChatHistory(receiver_id);}
}, 2000);

</script>
<script type="text/javascript">
function ajaxSearch()
{
 var input_data = $('#search_data').val();
    if(input_data!='')
    { 
		$.ajax({
            type: "POST",
            url: "",
            data: {input_data:input_data},
            success: function (data) {			
			  $('#search-result').show();
              $('#search-result').html(data);
              $('#search').hide();
            }
         });
    }
    else
    {
        $('#search-result').hide();
        $('#search').show();
    } 
 } 
</script> 

