<?php

//ini_set('max_execution_time', '0');
//require_once ('db_config.php');
//require_once('components/com_vbridge/views/Common_functions.php');
//require_once('modules/Common_module_functions.php');
$type='1';
$email='xyz@abc.solutions';
$name='ABCStartup';
$company_name='abc.solutions';
$path='adintegration.php';
$phone_number='885797401';
$company_url='www.abc.solutions';
$isd_code='+91';
?>
 <style>

      

    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
      <body>
        <div class="container">
            <div class="main-center main-message-hub ">
                <div class="messagevbridgefrom">
                    <form class="form-horizontal" action="<?php echo $path;?>" method="post"> 
                        <input type="hidden" name="type" name="type" value="<?php echo $type?>">
                        <input type="hidden" name="email" name="email" value="<?php echo $email?>">
                        <input type="hidden" name="name" name="name" value="<?php echo $name?>">
                        <input type="hidden" name="company_name" name="company_name" value="<?php echo $company_name?>">
                        <input type="hidden" name="company_url" name="company_url" value="<?php echo $company_url?>">
                        <input type="hidden" name="phone_number" name="phone_number" value="<?php echo $phone_number?>">
                        <input type="hidden" name="isd_code" name="isd_code" value="<?php echo $isd_code?>">
                   <div class="row" >
                    <div class="form-group" style="margin-left: -303px;margin-top: 100px;"> 
                        <div class="cols-sm-10" style="text-align:center;">
                           <button type="submit" id="id_submit"  class="btn btn-info pull-right">go to Vridge</button>
<!--                            <a href="<?php //echo JUri::root(); ?>administrator/index.php?option=com_message_from_vbridgehub" class="btn btn-primary btn-md">Cancel</a>-->

                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</body>
