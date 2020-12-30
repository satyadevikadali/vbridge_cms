<?php include('include/header.php'); ?>
<style>
  .fileDiv {
  position: relative;
  overflow: hidden;
}
.upload_attachmentfile {
  position: absolute;
  opacity: 0;
  right: 0;
  top: 0;
}
.btnFileOpen {margin-top: -50px; }

.direct-chat-warning .right>.direct-chat-text {
    background: #FFFFFF !important;
    border-color: #FFFFFF;
    color: #444;
  text-align: right;
}
.direct-chat-primary .right>.direct-chat-text {
    background: #3c8dbc;
    border-color: #3c8dbc;
    color: #fff;
  text-align: right;
  
}
.spiner{}
.spiner .fa-spin { font-size:24px;}
.attachmentImgCls{ width:450px; width: auto; height: 75px;  margin-left: 0px; cursor:pointer; }
.box-body{ min-height:500px;}
.box-header>.box-tools{display:none;}
div#content {min-height: 425px;}
.dropdown1 {
  float: right;
  overflow: hidden;
  padding-right:40px;
}
.dropdown-content {
  display: none;
  position: absolute;
  background-color: #f9f9f9;
  min-width: 68px;
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  z-index: 1;
}

.dropdown-content a {
  float: none;
  color: black;
  padding: 5px 5px;
  text-decoration: none;
  display: block;
  text-align: left;
}

.dropdown-content a:hover {
  background-color: #ddd;
}

.dropdown1:hover .dropdown-content {
  display: block;
}
.box-body {
    min-height: 425px;
}
.box{position: relative;border-radius: 3px;background: #ffffff;border-top: 3px solid #d2d6de;margin-bottom: 0px;width: 100%;box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);}
</style>
 
 

 
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
<?php 
include('include/topbar.php');
?>

<!-- Left side column. contains the logo and sidebar -->

<?php 
include('include/sidebar.php');
?>

<!-- Content Wrapper. Contains page content -->

<!--<div class="content-wrapper" style="min-height:425px !important;">--> 
<div class="content-wrapper" style="min-height:545px !important;"> 
  
  <!-- Content Header (Page header) -->
  
   
  
  <!-- Main content -->
  
  <section class="content">
     <div class="row">
           

            
            <div class="col-md-12" id="chatSection">
              <!-- DIRECT CHAT -->
              <div class="box box-warning direct-chat direct-chat-primary">
                <div class="box-header with-border">
                  <h3 class="box-title" id="ReciverName_txt">Chat with your Connection</h3>

<div class="pull-right">
            <div class="dropdown1">
              <button class="dropbtn" style="background-color: inherit;border: inherit;">
                <i class="fa fa-ellipsis-h"></i>
              </button>
              

              <div class="dropdown-content">
                <a href="javascript:void(0);" id="archieve">Archive</a>
                <a href="javascript:void(0);" id="restore">Restore</a>
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
                <div class="box-body">
                  <!-- Conversations are loaded here -->
                  <div class="direct-chat-messages" id="content">
                     <!-- /.direct-chat-msg -->

                     <div id="dumppy"></div>

                  </div>
                  <!--/.direct-chat-messages-->
 
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
<!-- <form id="fupForm" enctype="multipart/form-data"> -->
<div class="input-group">
						<?php 	
							$url ="https://vbridgehub.com/stgportal/login_userdetails_api.php?userid=399";
							$ch = curl_init();
							curl_setopt($ch, CURLOPT_URL, $url);
							curl_setopt($ch, CURLOPT_HEADER, 0);
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
							$result1 = curl_exec($ch); // TODO - UNCOMMENT IN PRODUCTION 
							curl_close ($ch);
							$chatInfo=json_decode($result1,true);
							$chatInfoRow=$chatInfo['data'];
							$profile_url="https://vbridgehub.com/stgportal/".$chatInfoRow['profile_pic'];  
						?> 
                        <input type="hidden" id="Sender_Name" value="<?=$chatInfoRow['username'];?>">
                        <input type="hidden" id="Sender_ProfilePic" value="<?=$profile_url;?>"> 
						<input type="hidden" id="ReciverId_txt">    
                       <input type="hidden" id="SenderId_txt" class="sender_id" name="sender_id">     
                        <!--input type="text" name="message" placeholder="Type Message ..." class="form-control message"--> 
						<input type="text" name="message" rows=3 placeholder="Type Message ..." class="form-control message">
                          <span class="input-group-btn">
                             <button type="submit" class="btn btn-success btn-flat btnSend" id="nav_down" style="background-color:#4764f4;">Send</button>
							  <div class="fileDiv btn btn-info btn-flat"> <i class="fa fa-upload"></i> 
									 <input type="file" name="file" class="upload_attachmentfile"/>
							  </div>
                          </span>
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
 $(document).ready(function(){
 
 $('.users-list-name').click(function(){ 
  var sender_id=$(this).attr('id');
$(".sender_id").val(sender_id);
 //alert(sender_id);
 
 });

 });
 $('#archieve').click(function(){

var receiver_id=$('#ReciverId_txt').val();
var sender_id=$('#SenderId_txt').val();

//alert(receiver_id);

$.ajax({

  url:"archieve.php",
  type:"POST",
  data:{receiver_id:receiver_id, sender_id:sender_id},
  success:function(response){
   
   //alert(response);

  },
});

 });

 $('#restore').click(function(){

var receiver_id=$('#ReciverId_txt').val();
var sender_id=$('#SenderId_txt').val();
//alert(receiver_id);

$.ajax({

  url:"restore.php",
  type:"POST",
  data:{receiver_id:receiver_id, sender_id:sender_id},
  success:function(response){
   
   //alert(response);

  },
});

 });

</script>


    <script>
      // Google Analytics
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', 'UA-49610253-3', 'auto');
      ga('send', 'pageview');
    </script>

</body>
</html>