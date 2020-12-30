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

    
	
	DisplayMessage('<div class="spiner" style="background:#fff;"><i class="fa fa-circle-o-notch fa-spin"></i></div>');
	//ScrollDown();
	
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
                url: 'https://vbridgehub.com/stgportal/chat/submit.php',   
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
url: 'https://vbridgehub.com/stgportal/chat/chat-clear?receiver_id='+receiver_id,
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
  						  url: 'https://vbridgehub.com/stgportal/chat/view-chat-attachment?message_id='+message_id,
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


/*function ScrollDown(){
	var elmnt = document.getElementById("content");
    var h = elmnt.scrollHeight;
   $('#content').animate({scrollTop: h}, 1000);
}
window.onload = ScrollDown();*/

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
url: 'https://vbridgehub.com/stgportal/chat/submit.php',
success:function(data)
{
GetChatHistory(receiver_id,sender_id)		 
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





function GetChatHistory(receiver_id,sender_id){
$.ajax({
//dataType : "json",
url: 'https://vbridgehub.com/stgportal/chat/get-chat-history.php',
type:'GET',
data:{sender_id:sender_id,receiver_id:receiver_id},
success:function(data)
{
$('#dumppy').html(data);
//ScrollDown();	 
},
error: function (jqXHR, status, err) {
// alert('Local error callback');
}
});
}
$(function() {
var interval=setInterval(function(){ 
	//alert('hi');
	var receiver_id = $('#ReciverId_txt').val();
	var sender_id= $('#SenderId_txt').val();
	if(receiver_id!='' && sender_id!=''){GetChatHistory(receiver_id,sender_id);}
}, 2000);
});