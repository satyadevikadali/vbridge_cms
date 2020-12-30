<?php $session_value=(isset($sessionData['id']))?$sessionData['id']:''; 
//$session_value=(isset($sessionData['id']))?$sessionData['id']:'';
//$title=(isset($_SESSION['username']))?$_SESSION['username']:'';

$url=$_SERVER['SERVER_NAME'];//JPATH_BASE . DS;
 if($url=='stgportal.vbridgehub.com'){
	$basePath="https://".$url.'/';
 }else{
	$basePath="https://".$url."/";
 }

?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css">
<?php 
$session = JFactory::getSession();
$sessionData = $session->get('userdata');
//print_r($sessionData);
//exit;

//$userProfileName=$sessionData['username'];
//$profile_url="https://vbridgehub.com/stgportal/".$sessionData['user_profile_pic']; 
include('include/header.php'); ?>
<style>
.fileDiv {position: relative;overflow: hidden;}
.box.box-warning {border: 1px solid #d0d0d0 !important ;}
.upload_attachmentfile {position: absolute;opacity: 0;right: 0;top: 0;}
.btnFileOpen {margin-top: -50px; }
.direct-chat-warning .right>.direct-chat-text {background: #FFFFFF !important;border-color: #FFFFFF;color: #444;text-align: right;}
.direct-chat-primary .right>.direct-chat-text {background: #FFFFFF;border-color: #FFFFFF;color: #000000;text-align: right;}
.direct-chat-text1{border-radius: 0px;position: relative;padding: 0px 0px;background: #fff !important;border: 1px solid #d0d0d0 !important;margin: 5px 0 0 50px;color: #444444;}
.spiner{background:#fff;border:1px solid #fff; background-color:none !important;}
.spiner .fa-spin { font-size:24px;}
.attachmentImgCls{ width:450px; width: auto; height: 75px;  margin-left: 0px; cursor:pointer; }

.box-body{ min-height:500px;}
.box-header>.box-tools{display:none;}
div#content {min-height: 425px;}
.dropdown1 {float: right;overflow: hidden;padding-right:40px;margin-top:-40px;}
.dropdown-content {display: none;position: absolute;background-color: #f9f9f9;width: 121px;box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);z-index: 1;padding: 1px 0px 5px 1px;}
.dropdown-content a {
  float: none;
  color: black;
  padding: 5px 5px;
  text-decoration: none;
  display: block;
  text-align: left;
  /* border-bottom:1px solid #000; */
}

.dropdown-content a:hover {
  background-color: #ddd;
}

.dropdown1:hover .dropdown-content {
  display: block;
}
.box-body {
    min-height: 270px;
}
.box{position: relative;border-radius: 3px;background: #ffffff;border-top: 3px solid #d2d6de;margin-bottom: 0px;width: 100%;box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);}

@media (min-width: 992px){
	.modal-lg {
		min-width: auto !important;
		text-align: center !important;
	}
}
.direct-chat-messages {
	    height: 270px;
}
.message{height: 147px !important;}
.attachment {
    float: left;
    width: 100%!important;
}
.filename{
	font-size:13px !important;
}
.direct-chat-msg, .direct-chat-text {
   font-size:13px !important;
   font-weight:normal !important;
}
.direct-chat-text {background: #fff !important;border: 1px solid #fff !important;width: 430px;word-break: break-all;}
.direct-chat-text:after {display:none !important;}
.direct-chat-text:before {display:none !important;}
.box-header{padding:12px !important;}
.box-header.with-border {border-bottom: 2px solid #d6d2d2 !important;}
.box-header .box-title{padding-top:0px !important;}
.col-sm-8{padding-right:0px !important;padding-left:0px !important;}
.btn-success:hover, .btn-success:active, .btn-success.hover{background-color:#5370f7 !important;border-color:#5370f7 !important;}
.btn-info:active:hover{background-color:#8b9ffa !important;border-color:#8b9ffa !important;}
/* textarea {
    overflow-y: scroll !important;
} */
textarea {
  border: 0 none;
  overflow-y: scroll !important; /*overflow is set to auto at max height using JS */
  background:#fff;
  font-family:sans-serif;
  outline: none;
 /*  min-height:90px;
  max-height:160px; */
  width:100%;
  padding:5px;
}
.input-group {
    position: relative;
    display: unset;
    border-collapse: separate;
}
.box-footer{padding:0px !important;}
.input-group-btn {
    position: relative;
    font-size: 0;
    right: 90px;
    top: 6px;
    float: right;
}
/* .textareaElement {
  width: 100%;
  min-height: 90px;
  border: 1px solid #fff;
  max-height: 160px;
  overflow-x: hidden;
} */
.textareaElement:empty:not(:focus):before {
  content: attr(data-placeholder)
}
@media screen and (min-device-width: 481px) and (max-device-width: 768px) {
.direct-chat-messages {height: 375px !important;}
.box-body {
    min-height: 270px !important; 
} 
}
#id1{display:none;}
.select2-container{width:100% !important;}

/* popup css*/

/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 9999; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 0;
  /*width: 100%;*/ /* Full width */
/*  height: 100%;*/ /* Full height */
  /*overflow: auto;*/ /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
  background-color: #fefefe;
  margin: auto;
  border-radius: 5px;
  border: 1px solid #888;
  width: 60%;
}

/* The Close Button */
.close {
  color: #5370f7 !important;
  float: right;
  font-size: 28px;
  font-weight: bold;
  opacity:1;
  margin-right: 10px;
  margin-top: 7px;
}

.close:hover,
.close:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}
.text{border-bottom:1px solid #ededed;padding:10px;margin-bottom:5px;}
.submitbutton{height:40px;}
.button{margin-right: 10px;padding: 6px;border-radius: 5px;}
/* popup css*/
</style> 
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper" style="height:560px !important;">
<?php 
include('include/topbar.php');
?>

<!-- Left side column. contains the logo and sidebar -->

<?php 
include('include/sidebar.php');
//session_start();
//echo $sender_id=$_SESSION["sender_id"];
?>

<!-- Content Wrapper. Contains page content -->

<div id="id2" class="content-wrapper" style="min-height:560px !important;background-color: #f5f5f5;"> 
  <!-- Content Header (Page header) -->
  <!-- Main content -->
  <section class="content">
     <div class="row">
            <div class="col-md-12" id="chatSection">
              <!-- DIRECT CHAT -->
              <div class="box box-warning direct-chat direct-chat-primary">
                <div class="box-header with-border">
                  <h3 class="box-title" id="ReciverName_txt">Chat with your Connection <?php //print_r($sessionData['id']);?></h3>
                  <p id="CompanyName_txt"></p>
					<div class="pull-right">
						<div class="dropdown1">
						  <button class="dropbtn" style="background-color: inherit;border: inherit;">
							<i class="fa fa-ellipsis-h"></i>
						  </button> 
						  <div class="dropdown-content">
							<!--<a href="javascript:void(0);" id="archieve">Archive</a>
							<a href="javascript:void(0);" id="restore">Restore</a>-->
							<a href="javascript:void(0);" id="myBtn">Report Abuse</a>
						  </div>
						  </div> 
					  </div> 
                  <div class="box-tools pull-right">
                    <span data-toggle="tooltip" title="Clear Chat" class="ClearChat"><i class="fa fa-comments"></i></span>
                    <!--<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>-->
                   <!-- <button type="button" class="btn btn-box-tool" data-toggle="tooltip" title="Clear Chat"
                            data-widget="chat-pane-toggle">
                      <i class="fa fa-comments"></i></button>-->
                   <!-- <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                    </button>-->
                  </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body" id="box-body">
                  <!-- Conversations are loaded here -->
                  <div class="direct-chat-messages" id="content1">
                     <!-- /.direct-chat-msg -->

                     <div id="dumppy"></div>
                      <div class="ajax-load text-center" style="display:none">
                          <p><img src="https://demo.itsolutionstuff.com/plugin/loader.gif">Loading More post</p>
                      </div>
                  </div>
                  <!--/.direct-chat-messages-->
 
                </div>
                <!-- /.box-body -->
                <!-- popupcode Starts here -->
				
				<div id="myModal" class="modal">
						<!-- Modal content -->
						  <div class="modal-content">
							<span class="close">&times;</span>

       
							<div class="text">
                  <p class="statusMsg"></p>
                   <form role="form">
								<h3>Report Abuse</h3>
             
                <input type="hidden" class="box-title" id="ReciverName_txt">
               <!--  <input type="hidden" name="" value=""> -->
                <input type="hidden" id="ReciverId_txt" name="ReciverId_txt">
								
                <textarea class="form-control" id="abuse_message" placeholder="Type Message ..." rows="6" maxlength ="1000" style="overflow-y: auto"></textarea>  
  </form>
							</div>
							<div class="submitbutton">
								<button type="button" class="button pull-right submitBtn" onClick="submitContactForm()">Send Message</button> 
							</div>

</div>
</div>
				
                <!-- popupcode ends here -->
                <div class="box-footer">
<!-- <form id="fupForm" enctype="multipart/form-data"> -->
				<div class="input-group">
					<?php 
					$url =$basePath."login_userdetails_api.php?userid=".$sessionData['id'];
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $url);
					curl_setopt($ch, CURLOPT_HEADER, 0);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					$result1 = curl_exec($ch); // TODO - UNCOMMENT IN PRODUCTION 
					curl_close ($ch);
					 $chatInfo=json_decode($result1,true);
					 $chatInfoRow=$chatInfo['data'];
					$profile_url=$basePath.$chatInfoRow['profile_pic']; 
					?>
                        <input type="hidden" id="Sender_Name" value="<?=$chatInfoRow['username'];?>">
                        <input type="hidden" id="Sender_ProfilePic" value="<?=$profile_url;?>"> 
                        <input type="hidden" id="ReciverId_txt">
                        <input type="hidden" id="SenderId_txt" class="sender_id" name="sender_id">   
					
						<div class="col-md-12" id="contact-form" style="padding:5px;height: 158px;overflow-y: auto;border-bottom: 1px solid #f1f1f1;position:sticky;">
							<textarea class="form-control message" name="message" placeholder="Type Message ..." rows="1" id="message" maxlength = "600" onKeyPress="return disableEnterKey(event)"></textarea>
						</div>
					
						 <div class="col-md-12 right"style="height: 47px;">
							<span class="input-group-btn">
								<div class="fileDiv btn btn-info btn-flat"> <i class="fa fa-upload"></i> 
									<input type="file" name="file" class="upload_attachmentfile"/>
								</div>  
								<button type="submit" class="btn btn-success btn-flat btnSend" id="nav_down">Send</button> 
							</span>
						 </div>
                    </div>
                  <!-- </form> -->
                </div>
                <!-- /.box-footer-->
              </div>
              <!--/.direct-chat -->
            </div> 
            <div class="col-md-4">
              <!-- USERS LIST -->

              <!--/.box -->
            </div>
            <!-- /.col -->            
          </div>
    
    <!-- /.row --> 
    
    
    
  </section>
  
  <!-- /.content --> 
  
</div>

<!-- /.content-wrapper --> 


<!-- Compose Message Starts --> 

<div id="id1" class="content-wrapper" style="min-height:560px !important;background-color: #f5f5f5;"> 
<section class="content">
<div class="row">
<div class="col-md-12">
<div class="box box-warning direct-chat direct-chat-primary">
<div class="box-header with-border">
<h3 class="box-title">Compose New Message</h3>
<div class="box-tools pull-right">
<span data-toggle="tooltip" title="Clear Chat" class="ClearChat"><i class="fa fa-comments"></i></span>
</div>
</div>

<div align="center">
<input type="text" name="search1" id="search1" placeholder="Search User Details" class="form-control" />
<input type="hidden" name="search2" id="search2" placeholder="Search User Details" class="form-control" />
</div>

<ul class="list-group search" id="result"></ul>
<!-- <div>

<?php

if(isset($apiVendorList) && !empty($apiVendorList))
{?>
<select class="js-example-basic-multiple form-control" name="list_connections" id="list_connections"  width="100%">
<?php
foreach ($apiVendorList as $key => $value) {?>
<option value="<?php echo $value['user_id'] ?>"><?php echo $value['username']; ?></option>

<?php }?>
</select>
<?php } ?>


</div> -->
<div class="box-body">
<!-- <div class="direct-chat-messages"> -->
 <div class="direct-chat-messages" id="content2">
<div id="dumppy1"></div>

</div>

<div class="box-footer">
<div class="input-group">

<div class="col-md-12" style="padding:5px;height: 147px;overflow-y: auto;border-bottom: 1px solid #f1f1f1;">

<textarea class="form-control message1" name="message1" id="message1" placeholder="Type Message ..." rows="1" maxlength = "500" style="height:136px;"></textarea>

</div>
			<div class="col-md-12 right" style="height: 47px;">
				<span class="input-group-btn">
					<div class="fileDiv btn btn-info btn-flat"> <i class="fa fa-upload"></i> 
					<input type="file" class="upload_attachmentfile"/>
					</div>  
					<button type="submit" class="btn btn-success btn-flat btnSend1">Send</button> 
				</span>
			</div> 
</div>
</div>
</div>

</div> 
<div class="col-md-4"></div>
</div>
</section>
</div>

<!-- Compose Message End --> 

<!-- Modal -->
<div class="modal fade" id="myModalImg">
<div class="modal-dialog modal-lg">
<div class="modal-content">

<!-- Modal Header -->
<div class="modal-header">
<h4 class="modal-title" id="modelTitle">Modal Heading</h4>
<button type="button" class="close" data-dismiss="modal">&times;</button>
</div>

<!-- Modal body -->
<div class="modal-body">
<img id="modalImgs" src="uploads/attachment/21_preview.png" class="img-thumbnail" alt="Cinque Terre">
</div>

<!-- Modal footer -->


</div>
</div>
</div>
<!-- Modal -->
  
<?php 
include('include/footer.php');
?>

<script> 
$("#body").keypress(function(e) {
  if (e.which == 13 && !$(e.target).is("textarea")) {
    return false;
  }
});
$(document).ready(function() {
$('.js-example-basic-multiple').select2();


 $("#list_connections").change(function(){

 var sender_id = $(this).val();
 var receiver_id = $('#ReciverId_txt').val(); 
 //alert(sender_id);
// alert(receiver_id);
if(receiver_id!='' && sender_id!='')
{

  GetChatHistory1(receiver_id,sender_id); 
}
else
{
$('#dumppy1').empty();
}



});



});
$(document).ready(function(){

$('.users-list-name').click(function(){ 
var id=$(this).attr('id');
//alert(id);
//$('li[data-sender='+id+']').addClass('active');
$(".sender_id").val(id);

var selector = '#search li';

$(selector).on('click', function(){
$(selector).removeClass('active');
$(this).addClass('active');
});

$("#id2").show();
$("#id1").hide();


});

});
$('#archieve').click(function(){

//chatLoader('<div class="spiner"><i class="fa fa-circle-o-notch fa-spin"></i></div>');
var receiver_id=$('#ReciverId_txt').val();
var sender_id=$('#SenderId_txt').val();

//alert(receiver_id);

$.ajax({

url:"<?php echo $basePath;?>chat/archieve.php",
type:"POST",
data:{receiver_id:receiver_id,sender_id:sender_id},
success:function(response){
//GetChatHistory(receiver_id,sender_id);
},
});
});
$('#restore').click(function(){
//chatLoader('<div class="spiner"><i class="fa fa-circle-o-notch fa-spin"></i></div>');
var receiver_id=$('#ReciverId_txt').val();
var sender_id=$('#SenderId_txt').val();
//alert(receiver_id);
$.ajax({

url:"<?php echo $basePath;?>chat/restore.php",
type:"POST",
data:{receiver_id:receiver_id, sender_id:sender_id},
success:function(response){

GetChatHistory(receiver_id,sender_id);

},
});

});
//show and hide starts here

//show and hide ends here

//text area height script
//$('#contact-form').on( 'change keydown keyup paste cut', 'textarea', function () {  
//  $(this).height(0).height(this.scrollHeight-1);
// if ($(this).height() >= 160) {
// $('textarea#message').css("overflow", "auto");
// }
//else {
//  $('textarea#message').css("overflow", "hidden");
//}
//}).find('textarea#message').change();
</script>
<script type="text/javascript">
$(".compose").click(function(){
$("#id1").show();
$('#search1').val('');
$('#result').html('');
$('#dumppy1').empty();
$("#id2").hide();
$('#search li.active').removeClass('active');
//$('#result').empty();


// var receiver_id = $('#ReciverId_txt').val();  
// var sender_id1 = $('.js-example-basic-multiple').val();
// var sender_id=JSON.parse(sender_id1);

// alert(receiver_id);

// GetChatHistory1(receiver_id,sender_id); 

});
</script>
<script>
//disable Enter 
/*$(document).on('keypress', ':input:not(textarea):not([type=submit])', function (e) {
    if (e.which == 13) e.preventDefault();
});*/
</script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.lazyload/1.9.1/jquery.lazyload.js"></script>
<script type="text/javascript">
$(function() {

$('.direct-chat-messages').lazyload({
//threshold:200,
effect: 'fadeIn'
});

});
</script> -->


<script>
// Get the modal
var modal = document.getElementById("myModal");

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal 
btn.onclick = function() {
  modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}

function submitContactForm(){
var name =$('#ReciverName_txt').html();
//alert(aname);
var sender_id = $('#SenderId_txt').val(); 
var abuse_message = $('#abuse_message').val();

$.ajax({

url:"https://vbridgehub.com/stgportal/chat/abuse.php",
type:"POST",
data:{sender_id:sender_id, abuse_message:abuse_message, name:name},
beforeSend: function () {
$('.submitBtn').attr("disabled","disabled");
// $('.modal-body').css('opacity', '.5');
},
success:function(msg){
if(msg){
$('#abuse_message').val('');
$('.statusMsg').html('<span style="color:green;">Thanks for contacting us, we\'ll get back to you soon.</p>');
}
else
{
$('#abuse_message').val('');
$('.statusMsg').html('<span style="color:red;">Some problem occurred, please try again.</span>');
}
//alert(response);

},
});

}
</script>

<script type="text/javascript"> 
 
//create a function that accepts an input variable (which key was key pressed) 
/*function disableEnterKey(e){ 
 
//create a variable to hold the number of the key that was pressed      
var key; 
 
    //if the users browser is internet explorer 
    if(window.event){ 
      
    //store the key code (Key number) of the pressed key 
    key = window.event.keyCode; 
      
    //otherwise, it is firefox 
    } else { 
      
    //store the key code (Key number) of the pressed key 
    key = e.which;      
    } 
      
    //if key 13 is pressed (the enter key) 
    if(key == 13){ 
      
    //do nothing 
    return false; 
      
    //otherwise 
    } else { 
      
    //continue as normal (allow the key press for keys other than "enter") 
    return true; 
    } 
      
//and don't forget to close the function     
} */
</script>

<script>
$(document).ready(function(){
$.ajaxSetup({ cache: false });
$('#search1').keyup(function(){
$('#result').html('');
$('#state').val('');
var searchField = $('#search1').val();
var receiver_id='<?php echo $session_value;?>';
//alert(receiver_id);
var expression = new RegExp(searchField, "i");
$.getJSON('<?php echo $basePath;?>chat/data.php?receiver_id='+receiver_id, function(data) {
$.each(data, function(key, value){
if (value.username.search(expression) != "-1")
{

$('#result').html('');

$('#result').append('<li class="list-group-item link-class" onclick="getNewUser('+value.user_id+')"><span class="text-muted">'+value.username+'</span> </li>');

var searchField = $('#search1').val();
if(searchField=='')
{
$('#result').html('');
$('#dumppy1').empty();
}
}



});   
});

});



$('#result').on('click', 'li', function() {
var click_text = $(this).text().split('|');
$('#search1').val($.trim(click_text[0]));
$("#result").html('');

var sender_id = $('#search2').val();
var receiver_id = $('#ReciverId_txt').val(); 

if(receiver_id!='' && sender_id!='')
{  
GetChatHistory1(receiver_id,sender_id);
}
else
{
$('#dumppy1').html('');
}
//$("#result").empty();
});
});
function getNewUser(user)
{
  $('#search2').val(user); 
}
	


    $(window).keydown(function(event){

        if((event.keyCode == 13) && ($("textarea")[0])) {

            event.preventDefault();

            return false;

        }

    });

/*$(document).on('keypress', ':input:not(textarea):not([type=submit])', function (e) {
    if (e.which == 13) e.preventDefault();
});*/
	
</script>
 
</body>
</html>