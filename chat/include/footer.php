<!-- <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 2.4.0
    </div>
 
    
  </footer> -->
  
  
    <!-- Control Sidebar -->
   <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->
  
  
  
  
  
  <!-- jQuery 3 -->
<script src="<?php echo $basePath;?>chat/public/components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo $basePath;?>chat/public/components/bootstrap/dist/js/bootstrap.min.js"></script>
 <?php //if($this->uri->segment(1) != 'chat'){?>
<script src="<?php echo $basePath;?>chat/public/components/PACE/pace.min.js"></script>
 <?php //}?>
<!-- SlimScroll -->
<script src="<?php echo $basePath;?>chat/public/components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="<?php echo $basePath;?>chat/public/components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo $basePath;?>chat/public/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo $basePath;?>chat/public/dist/js/demo.js"></script>

     <script src="<?php echo $basePath;?>chat/public/lib/js/config.js"></script>
    <script src="<?php echo $basePath;?>chat/public/lib/js/util.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
    <!--script src="<?php echo $basePath;?>chat/public/lib/js/jquery.emojiarea.js"></script-->
    <!--script src="/portal/chat/public/lib/js/emoji-picker.js"></script-->

<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.lazyload/1.9.1/jquery.lazyload.js"></script> -->

		
<script>
  $(document).ready(function () {
    $('.sidebar-menu').tree()
  })
  <?php //if($this->uri->segment(1) != 'chat'){?>
  $(document).ajaxStart(function () {
    Pace.restart();
  });
  <?php //}?>
</script>