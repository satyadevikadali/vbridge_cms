<?php
define('JPATH_BASE', realpath(dirname(__FILE__) . '/../../../..'));
require_once (JPATH_BASE . '/components/com_vbridgeadmin/views/Admin_common_functions.php');
//$common = new CommonModel();
//$get_all_company_data = get_all_companys($con);

use Joomla\CMS\Factory;

$user = Factory::getUser(220);
$user_id = $user->id;
$rootpath = JURI::root();
$id = $_REQUEST['id'];
$get_all_company_edit_data = get_edit_companys_data($con, $id);
$vb_mssage_id=$get_all_company_edit_data['id'];
//echo "<pre>";
//print_r($get_all_company_edit_data);exit;
$un_selected_company_id = $get_all_company_edit_data['company_id'];
if($get_all_company_edit_data['display_id']=='2'){   
$get_unselected_companies = get_unselected_companies_data($con, $un_selected_company_id);
//echo "<pre>";
//print_r($get_unselected_companies);exit;
}else{
    $get_all_company_data = get_all_companys($con);
//   print_r($get_all_company_data);exit;
}
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/css/bootstrap-datetimepicker.css"> 
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/css/bootstrap-datetimepicker.min.css"> 
        <link rel="stylesheet" type="text/css" href="<?php echo $rootpath; ?>administrator/components/com_vbridgeadmin/assets/css/site.css"> 
        <link rel="stylesheet" type="text/css" href="<?php echo $rootpath; ?>administrator/components/com_vbridgeadmin/assets/css/toastr.css"> 
        <link rel="stylesheet" type="text/css" href="<?php echo $rootpath; ?>administrator/components/com_vbridgeadmin/assets/css/editmessagefromvbridgehub.css"> 
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
           <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote-lite.css" rel="stylesheet">
         <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote-lite.js"></script>
        <script type="text/javascript" src="<?php echo $rootpath; ?>administrator/components/com_vbridgeadmin/assets/js/jquery.selectlistactions.js"></script>
        <script type="text/javascript" src="<?php echo $rootpath; ?>administrator/components/com_vbridgeadmin/assets/js/toastr.js"></script>

       

    </head>
    <style>
        .glyphicon-chevron-left:before {
                content: "\e079";
            }
            .glyphicon-chevron-right:before {
                content: "\e080";
            }
    </style>

    <body>
        <div class="container">
            <div class="main-center main-message-hub ">

                <div class="messagevbridgefrom">
                    <form class="form-horizontal" id="registration_form" method="post" action="#" name="registration"> 
                        <input type="hidden" id="user_id" name="user_id" value="<?= $user_id; ?>">
                        <input type="hidden" id="vb_mssage_id" name="vb_mssage_id" value="<?= $vb_mssage_id; ?>">
                        
                        <div class="row">
                            <div class="col-25">
                                <label for="fname">Display To</label>
                            </div>
                            <div class="col-75">
                                <label class="radio-inline">

                                    <input type="radio" name="display_id" id="gsi" value="1" onclick="check_value('all');" style="margin-top: -2px;" <?php
                                    if ($get_all_company_edit_data['display_id'] == '1') {
                                        $checked = 'checked';
                                        ?> <?= $checked; ?><?php } ?>> All

                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="display_id" id="selected" value="2"  onclick="check_value('selected');" style="margin-top: -2px;"  <?php
                                    if ($get_all_company_edit_data['display_id'] == '2') {
                                        $checked = 'checked';
                                        ?> <?= $checked; ?><?php } ?>> Seleted
                                </label> 

                                <div class="errormessage">
                                    <span id="gsi_all_selected_error" class="error" ></span></div>
                            </div>
                        </div>

                        <?php
                       //if ($get_all_company_edit_data['display_id'] == '2') {
                            $companys = explode(',', $get_all_company_edit_data['company_name']);
                            $company_ids = explode(',', $get_all_company_edit_data['company_id']);
//                            echo "<pre>";print_r($companys);
//                            echo "<pre>";print_r($company_ids);
                            ?>
                        <?php  if ($get_all_company_edit_data['display_id'] == '1') {?>
                        <div class="row" id="selected_companys" style="display:none">
                        <?php } else {?>
                            <div class="row" id="selected_companys" >
                        <?php }?>
                                <div class="subject-info-box-1" style="margin-left: 208px;margin-right: -9px;">

                                    <label>Available Companies</label>
                                    <select multiple class="form-control" id="lstBox1" name="company_selected[]" ondblclick="avalible_companies(event)">
                                        <?php
                                        if (isset($get_unselected_companies) && !empty($get_unselected_companies)) {
                                            foreach ($get_unselected_companies as $unselected) {
                                                ?>
                                                <option value="<?php echo $unselected['company_id']; ?>"><?php echo $unselected['company_name']; ?></option>
                                                <?php
                                            }
                                        } else {
                                            foreach ($get_all_company_data as $get_all_company) {
                                                ?>
                                                <option value="<?php echo $get_all_company['company_id']; ?>"><?php echo $get_all_company['company_name']; ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="subject-info-arrows text-center">
                                    <br /><br />
                                    <input type='button' id='btnAllRight' value='>>' class="btn btn-default" /><br />
                                    <input type='button' id='btnRight' value='>' class="btn btn-default" /><br />
                                    <input type='button' id='btnLeft' value='<' class="btn btn-default" /><br />
                                    <input type='button' id='btnAllLeft' value='<<' class="btn btn-default" />
                                </div>
                                <div class="subject-info-box-2">
                                    <label> Selected Companies</label>
                                    <select multiple class="form-control" name="company_selected[]" id="lstBox2" style="margin-left: -13px;" ondblclick="selected_companies(event)">
                                        <?php 
                                        foreach ($companys as $key => $company) : 
                                            if($company_ids[$key] > 0){
                                            ?>
                                            <option value="<?php echo $company_ids[$key]; ?>"><?php echo $company; ?></option>
                                            <?php } endforeach;  ?>
                                    </select>
                                </div>
                            </div>
                        <?php //} ?>



                        <div class="row">
                            <div class="col-25">
                                <label for="message">Message</label>
                            </div>
                            <div class="col-75">
<!--                                <textarea cols="4" rows="5" id="message" name="message" class="form-control"><?php
//                                    if (isset($get_all_company_edit_data['message']) && !empty($get_all_company_edit_data['message'])) {
//                                        echo $get_all_company_edit_data['message'];
//                                    }
                                    ?></textarea>-->
                                    <textarea name="message" id="message" class="form-control" rows="5" cols="4"><?php
                                    if (isset($get_all_company_edit_data['message']) && !empty($get_all_company_edit_data['message'])) {
                                        echo $get_all_company_edit_data['message'];
                                    }
                                    ?></textarea>
                                <div class="errormessage">
                                    <span id="message_error" class="error"></span></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-25" style="margin-top: 1px;">
                                <label for="from_date_and_time" >From Date</label>
                            </div>

                            <div class="col-75" >
                                <div class="input-group date" id="from_and_time">
                                    <input type="text"  id="from_date_and_time" name="from_date_and_time" class="form-control" value="<?php
                                    if (isset($get_all_company_edit_data['fromdate']) && !empty($get_all_company_edit_data['fromdate'])) {
                                        echo $get_all_company_edit_data['fromdate'];
                                    }
                                    ?>"/>
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span> 
                                    </span>
                                </div>


                                <div class="errormessage">
                                    <span id="from_date_and_time_error" class="error"></span></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-25" style="margin-top: 1px;">
                                <label for="to_date_time" >To Date</label>
                            </div>

                            <div class="col-75" >
                                <div class="input-group date" id="to_date_and_time">
                                    <input type="text"  id="to_date_time" name="to_date_time" class="form-control" value="<?php
                                    if (isset($get_all_company_edit_data['todate']) && !empty($get_all_company_edit_data['todate'])) {
                                        echo $get_all_company_edit_data['todate'];
                                    }
                                    ?>"/>
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span> 
                                    </span>
                                </div>


                                <div class="errormessage">
                                    <span id="to_date_time_error" class="error"></span></div>
                            </div>
                        </div>
                        </br>
                        <div class="row" >
                            <div class="form-group" style="margin-left: -303px;"> 
                                <div class="cols-sm-10" style="text-align:center;">
                                    <button type="button" id="login_button" class="btn btn-primary btn-md" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Wait..." onclick="return validate_login()">Submit</button>
                                    <a href="<?php echo JUri::root(); ?>administrator/index.php?option=com_message_from_vbridgehub" class="btn btn-primary btn-md">Cancel</a>

                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.1/moment.min.js"></script>    
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/js/bootstrap-datetimepicker.min.js"></script>    
<script>
     function selected_companies(e){
      $('select').moveToListAndDelete('#lstBox2', '#lstBox1');
      e.preventDefault();
    }
  function avalible_companies(e){
       $('select').moveToListAndDelete('#lstBox1', '#lstBox2');
           e.preventDefault();
    }
     function initeditor() {
        jQuery("#message").summernote({
            focus: true,
            height: 200,
             toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview']],
            ],
        });

    }
    initeditor();
//    jQuery(document).ready(function () {
////       console.log( "ready!" );
//                $.ajax({
//                url: "<?php echo JUri::root(); ?>/administrator/components/com_vbridgeadmin/views/Admin_common_functions.php?type=companynames",
//                type: 'get'
//            });
//    });
                                        $('#btnRight').on('click', function (e) {
                                            $('select').moveToListAndDelete('#lstBox1', '#lstBox2');
                                            e.preventDefault();
                                        });

                                        $('#btnAllRight').on('click', function (e) {
                                            $('select').moveAllToListAndDelete('#lstBox1', '#lstBox2');
                                            e.preventDefault();
                                        });

                                        $('#btnLeft').on('click', function (e) {
                                            $('select').moveToListAndDelete('#lstBox2', '#lstBox1');
                                            e.preventDefault();
                                        });

                                        $('#btnAllLeft').on('click', function (e) {
                                            $('select').moveAllToListAndDelete('#lstBox2', '#lstBox1');
                                            e.preventDefault();
                                        });



                                        jQuery('#from_and_time').datetimepicker({
                                            format: 'YYYY-MM-DD',
                                            useCurrent: false
                                        });
                                        jQuery('#to_date_and_time').datetimepicker({
                                            format: 'YYYY-MM-DD ',
                                            useCurrent: false
//                                            minDate: moment()
                                        });
                                        jQuery('#from_and_time').datetimepicker().on('dp.change', function (e) {
                                            var incrementDay = moment(new Date(e.date));
                                            incrementDay.add(1, 'days');
                                            jQuery('#to_date_and_time').data('DateTimePicker').minDate(incrementDay);
                                            jQuery(this).data("DateTimePicker").hide();
                                        });
                                        jQuery('#to_date_and_time').datetimepicker().on('dp.change', function (e) {
                                            var decrementDay = moment(new Date(e.date));
                                            decrementDay.subtract(1, 'days');
//                                             jQuery('#from_and_time').data('DateTimePicker').maxDate(decrementDay);
                                            jQuery(this).data("DateTimePicker").hide();
                                        });

                                        function check_value(value) {
                                            if (value == 'all') {
                                                $("#gsi_all_company").show();
                                                $("#selected_companys").hide();

                                            } else if (value == "selected") {
//                                                alert();
                                                $("#gsi_all_company").hide();
                                                $("#selected_companys").show();
//                                                $('.multiselect-all').css({'display': 'none'})
//             $(".multiselect-all").css("background-color:red !important");

                                            } else {
                                                $("#gsi_all_company").hide();
                                                $("#selected_companys").hide();
                                            }
                                            $("#gsi_all_company").val(value);
                                        }

                                        function validate_login() {
                                            var error = 0;


                                            if (!checkradio('display_id', 'display_id', 'any one')) {
                                                error++;
                                            }
                                            if (!required_field(jQuery('#message').val(), 'message', 'message', 'enter')) {
                                                error++;
                                            }
                                            if (!required_field(jQuery('#from_date_and_time').val(), 'from_date_and_time', 'date and time', 'select')) {
                                                error++;
                                            }
                                            if (!required_field(jQuery('#to_date_time').val(), 'to_date_time', 'date and time', 'select')) {
                                                error++;
                                            }
                                            if (!required_field(jQuery('#company_description').val(), 'company_description', 'company description', 'enter')) {
                                                error++;
                                            }

                                            if (error == 0) {
                                                var selectedvalues = [];
                                                jQuery("#lstBox2 > option").each(function (index, row) {
                                                    selectedvalues.push(jQuery(row).val())
                                                });
                                                var unselectedvalues = [];
                                                jQuery("#lstBox1 > option").each(function (index, row) {
                                                    unselectedvalues.push(jQuery(row).val())
                                                });
//                                                console.log(selectedvalues);
                                                 jQuery("#login_button").attr("disabled", true);
                                                var form = jQuery("#registration_form").get(0);
                                                var formdata = new FormData(form);
                                                var rootpath = "<?php echo $rootpath; ?>";
                                                formdata.append("selectedvalues", selectedvalues);
                                                formdata.append("unselectedvalues", unselectedvalues);
                                                
//                                                 //alert(rootpath);exit;
//                                                 var isd_code = jQuery('.selected-dial-code').text();
//                                                 formdata.append("isd_code", isd_code);
                                                jQuery.ajax({
                                                    type: "POST",
                                                    url: rootpath + '/administrator/components/com_vbridgeadmin/views/Admin_common_functions.php?type=edit_message_vbridge_hub',
                                                    data: formdata,
                                                    cache: false,
                                                    processData: false,
                                                    contentType: false,
                                                    dataType: "json",
                                                    success: function (data) {
                                                        if (data.status == 'success') {
                                                            show_toastr(1, 'Updated successfully');
                                                            setTimeout(function () {
                                                                parent.location.reload();
                                                                window.location.href = rootpath + "administrator/index.php?option=com_message_from_vbridgehub";
                                                                //                                window.parent.jQuery(".fancybox-overlay-fixed").prop("style", "display: none;");
                                                            }, 4000);

                                                        } else {
                                                            show_toastr(0, 'Not updated successfully');
                                                            setTimeout(function () {
                                                                parent.location.reload();
                                                                //                                window.parent.jQuery(".fancybox-overlay-fixed").prop("style", "display: none;");
                                                            }, 4000);
                                                        }
                                                    }
                                                });
                                            } else {
                                                return false;
                                            }
                                        }
                                        function show_toastr(type, message) {
                                            toastr.options = {
                                                "closeButton": true,
                                                "debug": false,
                                                "positionClass": "toast-bottom-right",
                                                "onclick": null,
                                                "showDuration": "300",
                                                "hideDuration": "1000",
                                                "timeOut": "300000",
                                                "extendedTimeOut": "1000",
                                                "showEasing": "swing",
                                                "hideEasing": "linear",
                                                "showMethod": "fadeIn",
                                                "hideMethod": "fadeOut"
                                            };
                                            if (type == '1') {
                                                toastr.success(message);
                                            } else {
                                                toastr.error(message);
                                            }

                                        }
                                        function required_field(field_value, field_id, error_msg, field_type) {
                                            jQuery('#' + field_id + "_error").html('');
                                            if (field_value == '') {
                                                jQuery('#' + field_id + "_error").html('Please ' + field_type + ' ' + error_msg);
                                                return false;
                                            } else {
                                                jQuery('#' + field_id + "_error").html('');
                                                return true;
                                            }
                                        }

                                        function checkradio(name, field_id, error_msg) {
                                            var check = true;
                                            jQuery("input:radio").each(function () {
                                                if (jQuery("input:radio[name=" + name + "]:checked").length == 0) {
                                                    check = false;
                                                }
                                            });
                                            if (!check) {
                                                jQuery('#' + field_id + "_error").html('Please select ' + error_msg);
                                                return false;
                                            } else {
                                                jQuery('#' + field_id + "_error").html('');
                                                return true;
                                            }
                                        }
                                        function image_required_field(field_value, field_id, error_msg) {
                                            $('#' + field_id + "_error").html('');
                                            if (field_value == '') {
                                                $('#' + field_id + "_error").html('Please select ' + error_msg);
                                                return false;
                                            } else {
                                                $('#' + field_id + "_error").html('');
                                                return true;
                                            }
                                        }


</script>