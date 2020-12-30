<?php
define('JPATH_BASE', realpath(dirname(__FILE__) . '/../../../..'));
require_once (JPATH_BASE . '/components/com_vbridgeadmin/views/Admin_common_functions.php');
$get_all_company_data = get_all_companys($con);

use Joomla\CMS\Factory;

$user = Factory::getUser(220);
$user_id = $user->id;
$rootpath = JURI::root();
$get_all_company_data = get_all_companys($con);
$get_industry_vertical = get_data_industry_verticals($con);
$category_data = get_date_categories($con);
$get_countries = get_data_countries($con);
//echo "<pre>";print_r($get_countries);echo "</pre>";exit;
$get_checked_categories = array();
$temp[] = array();
if (isset($category_data['categories']) && !empty($category_data['categories'])) {
    if ($user_status == "0") {
        $get_checked_categories = explode(",", $category_data['categories']);
    } else {
        $get_checked_categories = explode(",", $category_data['categories']);
    }
} else {
    $get_checked_categories = array();
}
//echo"<pre>"; print_r($category_data); echo"</pre>"; exit;
?>
<!DOCTYPE html>
<html>
    <head>


        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/css/bootstrap-datetimepicker.min.css"> 
        <link rel="stylesheet" type="text/css" href="<?php echo $rootpath; ?>administrator/components/com_vbridgeadmin/assets/css/site.css"> 
        <link rel="stylesheet" type="text/css" href="<?php echo $rootpath; ?>administrator/components/com_vbridgeadmin/assets/css/toastr.css"> 
        <link rel="stylesheet" type="text/css" href="<?php echo $rootpath; ?>administrator/components/com_vbridgeadmin/assets/css/addmessagefromvbridgehub.css"> 

        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote-lite.css" rel="stylesheet">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.12/summernote-lite.js"></script>
        <script type="text/javascript" src="<?php echo $rootpath; ?>administrator/components/com_vbridgeadmin/assets/js/jquery.selectlistactions.js"></script>
        <script type="text/javascript" src="<?php echo $rootpath; ?>administrator/components/com_vbridgeadmin/assets/js/toastr.js"></script>


    </head>
    <style>

        .error {
            color:red;
        }
        .vb_set_label {
            margin-top:8px;
        }
        .comdet {
            position: absolute;
            will-change: transform;
            overflow-y: scroll !important;
            top: 0px;
            left: 0px;
            transform: translate3d(231px, 33px, 6px) !important;
            padding: 0px 0px 0px 20px !important;
        }
        .subcategory_acordian{
            display:none;
            padding: 0px !important;
        }
        .category_acordian{
            display:none;
        }
        .icon-accordian {
            float: right;
            margin-right: 13px;
            cursor: pointer;
            margin-top: 6px;
        }

        .category-block{
            margin-left: -11px !important;
            margin-top: -2px;
        }

        ul, ol {
            padding: 0;
            margin: 0 0 0px 15px;
        }
        .dropdown-menu {
            position: absolute;
            top: 100%;
            left: -219px !important;
            z-index: 1000;
            display: none;
            float: left;
            min-width: 287px !important;
            padding: 5px 0;
            margin: 2px 0 0;
            font-size: 14px;
            text-align: left;
            list-style: none;
            background-color: #fff;
            -webkit-background-clip: padding-box;
            background-clip: padding-box;
            border: 1px solid #ccc;
            border: 1px solid rgba(0,0,0,.15);
            border-radius: 4px;
            -webkit-box-shadow: 0 6px 12px rgba(0,0,0,.175);
            box-shadow: 0 6px 12px rgba(0,0,0,.175);
            max-height: 200px !important;
        }

        /*  .category_acc_main:first-child {
         padding-top: 10px;    
         }*/
        .category_acc_main:last-child {
            padding-bottom: 10px;    
        } 
        #category_dropdown {
            margin-left: -17px !important;
            min-width: 340px !important;
        }
        .checkbox input[type="checkbox"] {
            float: unset !important;

        }
        label.move_right {
            padding: 0px 12px 12px 0;}

    </style>

    <body>
        <div class="container">
            <div class="main-center main-message-hub ">
                <div class="messagevbridgefrom">
                    <form class="form-horizontal" id="registration_form" method="post" action="#" name="registration"> 
                        <input type="hidden" id="user_id" name="user_id" value="<?= $user_id; ?>">
                        <div class="row">
                            <div class="col-25">
                                <label for="fname">Display To</label>
                            </div>
                            <div class="col-75">
                                <label class="radio-inline">
                                    <input type="radio" name="demo" id="gsi" value="1" onclick="check_value('all');" style="margin-top: -2px;"> All
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="demo" id="selected" value="2"  onclick="check_value('selected');" style="margin-top: -2px;"> Seleted
                                </label> 

                                <div class="errormessage">
                                    <span id="demo_error" class="error" ></span></div>
                            </div>
                        </div>

                        <div class="row" id="selected_companys" style="display:none">
                            <!-- vertical and category -->

                            <div class="form-group">
                                <!--<form id="check_company_name">-->
                                    <label for="title" class="col-25" style="margin-top:5px;margin-left:-3px;">Category </label>
                                    <div class="row">
                                        <div class="col-75">

                                            <div class="dropdown"> 
                                                <input type="hidden" id="table_type" name="table_type" value=""/>
                                                <input type="hidden" id="product_id_popup" name="product_id_popup" value=""/>

                                                <button class="btn btn-primary dropdown-toggle" id="select_category" type="button" data-toggle="dropdown" style="background-color: #4764f4;width:287px;">Select Category
                                                    <span class="caret"></span>
                                                </button>
                                                <?php
                                                if (isset($category_data) && !empty($category_data)) {
                                                    $k = 0;
                                                    ?>
                                                    <ul class="dropdown-menu comdet category-block" id="category_dropdown" style="margin-left: 59px;top: -5px;">
                                                        <?php
                                                        foreach ($category_data as $k1 => $category_app) :

                                                            $k++;

                                                            $resappcount = count($category_app);
                                                            ?>
                                                            <li class="category_acc_main">
                                                                <div class="checkbox checkbox-primary" >
                                                                    <input type="checkbox" class="cat level1 <?php echo "value_of_current_" . str_replace(" ", "", $k1); ?>" id="<?= $k ?><?= $k1 ?>" data-parent="0" data-level ="1" data-childcount="<?= $resappcount ?>" value="<?= $k1 ?>" name="chk_box[]" >                                                                   
                                                                    <label for="<?= $k ?><?= $k1 ?>"class="move_right ellipsis" style="padding-left: 5px;width: 141px;margin-bottom: 0px;" title="<?php echo $k1; ?>"><?php echo $k1; ?></label>
                                                                    <span class="icon-accordian" onclick="opencategories('<?= $k ?>')"><i class="fa fa-plus" id="main_icon_acc_<?= $k ?>"></i></span>
                                                                    <ul id="category_acc_<?= $k ?>" class="category_acordian">
                                                                        <?php if (isset($category_app) && !empty($category_app)) { ?>
                                                                            <li style="list-style: none;padding-bottom: 7px;">
                                                                                <ul style="margin:0; padding: 0;">
                                                                                    <?php
                                                                                    $other_cat_names = array();
                                                                                    foreach ($category_app as $k2 => $category) :
                                                                                        $other_cat_names[] = $category['category'];
                                                                                        $subCategories = $category['subCategory'];
                                                                                        $res = $category['subCategory'];
                                                                                        $rescount = count($res);
                                                                                        $catlength = count($get_checked_categories);
                                                                                        if (isset($subCategories[0]) && !empty($subCategories[0])) {
                                                                                            $subcount = count($res);
                                                                                        } else {
                                                                                            $subcount = 0;
                                                                                        }
                                                                                        ?>

                                                                                        <li style="list-style: none;" class="category_acc_main">
                                                                                            <div class="checkbox checkbox-primary" >
                                                                                                <input type="checkbox" class="cat <?php echo str_replace(" ", "", $k1); ?> <?php echo "value_of_current_" . str_replace(" ", "", $category['category']); ?> level2" data-parent="<?php echo str_replace(" ", "", $k1); ?>" data-level ="2" data-childcount="<?= $rescount ?>" id="<?= $k2 ?><?= $category['category'] ?>" value="<?= $category['category'] ?>" name="chk_box[]"  >                                                                                                
                                                                                                <label for="<?= $k2 ?><?= $category['category'] ?>"class="move_right ellipsis" style="padding-left: 5px;margin-bottom: 0px;" title="<?php echo $category['category']; ?>"><?php echo $category['category']; ?></label>
                                                                                                <span class="icon-accordian" onclick="openSubcategories('<?= $k2 ?>')"><i class="fa fa-plus" id="icon_acc_<?= $k2 ?>"></i></span>
                                                                                                <ul id="sub_category_acc_<?= $k2 ?>" class="subcategory_acordian">                                                                                                
                                                                                                    <?php if (isset($res) && !empty($res)) { ?>
                                                                                                        <?php foreach ($res as $key => $rec): ?>
                                                                                                            <?php if (isset($rec) && !empty($rec)) { ?>  
                                                                                                                <li style="list-style: none;padding-left: 9px;" >
                                                                                                                    <div class="checkbox checkbox-primary" >                                                                                                                    
                                                                                                                        <input type="checkbox" data-parent="<?= str_replace(" ", "", $category['category']) ?>" data-level ="3"  data-childcount="0" class="cat <?php echo str_replace(" ", "", $k1); ?> <?= str_replace(" ", "", $category['category']) ?> level3" name="chk_box[]" id="<?= $key ?>_<?= $rec ?>" value="<?= $category['category'] . "-" . $rec ?>" />
                                                                                                                        <label for="<?= $key ?>_<?= $rec ?>" class="move_right" style="padding-left: 5px;margin-bottom: 0px;" title="<?= $rec ?>"><?= $rec ?></label>
                                                                                                                    </div>                                                                                                    </li>
                                                                                                            <?php } ?>
                                                                                                        <?php endforeach; ?>

                                                                                                    <?php } ?>
                                                                                                </ul>
                                                                                            </div>
                                                                                        </li>

                                                                                        <?php
                                                                                    endforeach;
                                                                                    ?>
                                                                                </ul>
                                                                            </li>
                                                                        <?php } ?>
                                                                    </ul>
                                                                </div>
                                                            </li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                <?php } ?>
                                                <span id="category_check_error" class="error"></span>
                                                 
                                            </div>
                                            <div class="col-md-3">
                                                <?php
                                                if (isset($product_info_data['other_category_name']) && !empty($product_info_data['other_category_name'])) {
                                                    $othercatname = $product_info_data['other_category_name'];
                                                } else if (isset($product_info_data_real['other_category_name']) && !empty($product_info_data_real['other_category_name'])) {
                                                    $othercatname = $product_info_data_real['other_category_name'];
                                                } else {
                                                    $othercatname = "";
                                                }
                                                ?>

                                                <span id="other_category_error" class="error"></span>
                                            </div>

                                        </div>
                                    </div>

                                     <!--Industry Vertical Code Starts--> 

                                    <div class="form-group">
                                        <label for="title" class="col-25" style="margin-top:5px;margin-left:-3px;">Industry Vertical </label>
                                        <div class="row">
                                            <div class="col-75">
                                                <div class="dropdown">
                                                    <button class="btn btn-primary dropdown-toggle1" id="select_vertical" type="button" data-toggle="dropdown" style="background-color: #4764f4;width: 286px;">Select Industry Vertical<span class="caret"></span>
                                                    </button>

                                                    <ul class="dropdown-menu comdet" style="position: absolute;will-change: transform;overflow-y: scroll !important;top: -4px;left: 0px;transform: translate3d(217px, 33px, 6px) !important;padding: 0px 0px 0px 20px !important;">
                                                        <div class="checkbox checkbox-primary" >
                                                            <input type="checkbox" id="chckBox" class="parent" onchange="all_ckeck_industry_veriticals(this);" style="opacity: 1;z-index: 1;"/><label for="chckBox" class="move_right" style="padding-left: 4px;margin-top: 5px;">All</label></div>
                                                        <?php
                                                        if (isset($get_industry_vertical) && !empty($get_industry_vertical)) {
                                                            foreach ($get_industry_vertical as $k => $industry) {
                                                                ?>
                                                                <li style="list-style: none;padding-bottom: 7px;">
                                                                    <div class="checkbox checkbox-primary" >
                                                                        <input type="checkbox" class="ind_vertical" id="chk_box_<?= $industry['id'] ?>" name="industry_vertical[]" value="<?= $industry['id'] ?>"/>
                                                                        <label for="chk_box_<?= $industry['id'] ?>" class="move_right"><?= $industry['vertical_name'] ?></label>
                                                                    </div>
                                                                </li> 

                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                    </ul>
                                                    <span id="vertical_check_error" class="error"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="title" class="col-25" style="margin-top:5px;margin-left:-3px;">Countries</label>
                                        <div class="row">
                                            <div class="col-75">
                                                <select class="form-control" id="county_id" name="county_id">
                                                    <option value="">Choose a country</option>
                                                    <?php foreach ($get_countries as $key => $details_countries) { ?>
                                                        <option value="<?php echo $details_countries['id']; ?>"><?php echo $details_countries['name']; ?></option>
                                                    <?php } ?>     
                                                </select>
                                                <span id="name_error" class="error"></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="title" class="col-25" style="margin-top:5px;margin-left:-3px;"></label>
                                        <div class="row">
                                            <div class="col-75">
                                                <a type="button" id="login_button_data" class="btn btn-primary btn-md" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Wait..." onclick="check_companies()">Search</a>
                                            </div>
                                        </div>
                                <!--</form>-->
                            </div>
                        </div>


                        <!--vertical Ends-->

                        <div class="subject-info-box-1" id="init_available_companies" style="margin-left: 208px;margin-right: -9px;">
                            <label>Available Companies</label>
                            <select multiple class="form-control" id="lstBox1" ondblclick="avalible_companies(event)">
                                <?php
                                foreach ($get_all_company_data as $key => $get_company_name) {
                                    ?>
                                    <option  value="<?php echo $get_company_name['company_id']; ?>"><?php echo $get_company_name['company_name']; ?></option>
                                <?php } ?>
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
                            </select>
                        </div>
                </div>

                <div class="row">
                    <div class="col-25">
                        <label for="subject">Message</label>
                    </div>
                    <div class="col-75">
                        <div id="company_editor_div">
                            <textarea name="message" id="message" class="form-control" rows="5" cols="4"></textarea>
                        </div>
                        <div class="errormessage">
                            <span id="message_error" class="error"></span></div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-25" style="margin-top: 1px;">
                        <label for="lname" >From Date</label>
                    </div>

                    <div class="col-75" >
                        <div class="input-group date" id="from_and_time">
                            <input type="text"  id="from_date_and_time" name="from_date_and_time" class="form-control"/>
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
                        <label for="lname" >To Date</label>
                    </div>

                    <div class="col-75">
                        <div class="input-group date" id="to_and_time">
                            <input type="text"  id="to_date_time" name="to_date_time" class="form-control"/>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span> 
                            </span>
                        </div>


                        <div class="errormessage">
                            <span id="to_date_time_error" class="error"></span></div>
                    </div>
                </div></br>
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
                                        function check_companies() {
                                            var categories = [];
                                            var industry_verticals = [];
                                            jQuery.each(jQuery("input[name='chk_box[]']:checked"), function () {
                                                categories.push(jQuery(this).val());
                                            });
                                            var catdata = categories.join(", ");

                                            jQuery.each(jQuery("input[name='industry_vertical[]']:checked"), function () {
                                                industry_verticals.push(jQuery(this).val());
                                            });
                                            var indvertdata = industry_verticals.join(", ");

                                            var countries = jQuery("#county_id").val();
                                            var rootpath = "<?php echo $rootpath; ?>";
                                            jQuery.ajax({
                                                type: "POST",
                                                url: rootpath + '/administrator/components/com_vbridgeadmin/views/Admin_common_functions.php?type=check_company_name',
                                                data: {catdata: catdata, indvertdata: indvertdata, countries: countries},
                                                dataType: "json",
                                                success: function (data) {


                                                    var div = "";
                                                    div += "<label>Available Companies</label>";
                                                    div += "<select multiple class='form-control' id='lstBox1' ondblclick='avalible_companies(event)'>";
                                                    for (var i = 0; i < data.length; i++) {
//                                                        alert(data[i]['company_name']);
                                                        div += "<option  value='" + data[i]['company_id'] + "'>" + data[i]['company_name'] + "</option>";
                                                    }
                                                    div += "</select>";
                                                    //div += "</div>";
//                                                jQuery("#init_available_companies").html('');

                                                    jQuery("#init_available_companies").html(div);

                                                }
                                            });

                                        }
                                        function all_ckeck_industry_veriticals(check) {
                                            $(check).closest("ul").find(":checkbox").prop("checked", check.checked);
                                        }
                                        function selected_companies(e) {
                                            $('select').moveToListAndDelete('#lstBox2', '#lstBox1');
                                            e.preventDefault();
                                        }
                                        function avalible_companies(e) {
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
//                                        $('#btnRight').on('click', function (e) {
//                                            $('select').moveToListAndDelete('#lstBox1', '#lstBox2');
//                                            e.preventDefault();
//                                        });
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
//          alert('hi')
                                            format: 'YYYY-MM-DD',
                                            useCurrent: false
                                        });
                                        jQuery('#to_and_time').datetimepicker({
                                            format: 'YYYY-MM-DD ',
                                            useCurrent: false,
                                            minDate: moment()
                                        });
                                        jQuery('#from_and_time').datetimepicker().on('dp.change', function (e) {
                                            var incrementDay = moment(new Date(e.date));
                                            incrementDay.add(1, 'days');
                                            jQuery('#to_and_time').data('DateTimePicker').minDate(incrementDay);
                                            jQuery(this).data("DateTimePicker").hide();
                                        });
                                        jQuery('#to_and_time').datetimepicker().on('dp.change', function (e) {
                                            var decrementDay = moment(new Date(e.date));
                                            decrementDay.subtract(1, 'days');
                                            // jQuery('#from_date_and_time').data('DateTimePicker').maxDate(decrementDay);
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


                                            if (!checkradio('demo', 'demo', 'any one')) {
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
                                                 jQuery("#login_button").attr("disabled", true);
                                                var form = jQuery("#registration_form").get(0);
                                                var formdata = new FormData(form);
                                                var rootpath = "<?php echo $rootpath; ?>";
                                                formdata.append("selectedvalues", selectedvalues)
//                                                 //alert(rootpath);exit;
//                                                 var isd_code = jQuery('.selected-dial-code').text();
//                                                 formdata.append("isd_code", isd_code);
                                                jQuery.ajax({
                                                    type: "POST",
                                                    url: rootpath + '/administrator/components/com_vbridgeadmin/views/Admin_common_functions.php?type=add_message_vbridge_hub',
                                                    data: formdata,
                                                    cache: false,
                                                    processData: false,
                                                    contentType: false,
                                                    dataType: "json",
                                                    success: function (data) {
                                                        if (data.status == 'success') {
                                                            show_toastr(1, 'Added successfully');
                                                            setTimeout(function () {
                                                                parent.location.reload();
                                                                window.location.href = rootpath + "administrator/index.php?option=com_message_from_vbridgehub";
                                                                //                                window.parent.jQuery(".fancybox-overlay-fixed").prop("style", "display: none;");
                                                            }, 4000);

                                                        } else {
                                                            show_toastr(0, 'Oops!not added successfully');
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

<script>
    function openSubcategories(idk) {
        //                                        jQuery(".subcategory_acordian").fadeOut(2);
        jQuery("#sub_category_acc_" + idk).slideToggle();
        jQuery("#icon_acc_" + idk).toggleClass('fa fa-minus');
        jQuery("#icon_acc_" + idk).toggleClass('fa fa-plus');
    }
    function opencategories(idk) {
        jQuery("#category_acc_" + idk).slideToggle();
        jQuery("#main_icon_acc_" + idk).toggleClass('fa fa-minus');
        jQuery("#main_icon_acc_" + idk).toggleClass('fa fa-plus');
    }
    var allcheckbox = jQuery('.cat').length;
    var allcheckboxchecked = jQuery('.cat:checked').length;
    if (allcheckbox == allcheckboxchecked) {
        jQuery("#chk_box_01").prop("checked", true);
    }

    /* Dropdown Code */
    jQuery('.dropdown-menu').on('click', function (e) {
        e.stopPropagation();
    });
    function setdefault() {

    }
    jQuery('li :checkbox').on('click', function () {
        var idofselected = jQuery(this).attr('id');
        var $chk = jQuery(this),
                $li = $chk.closest('li'),
                $ul, $parent;
        //                            alert(idofselected);
        if (idofselected == 'chk_box_other') {
            var check = jQuery('#chk_box_other').prop('checked');
            if (check) {
                //                                    jQuery('input[type=checkbox]').prop('checked', false);
                jQuery("input[name='industry_vertical[]']:checkbox").prop('checked', false);
                jQuery("#chk_box_other").prop("checked", true);
                jQuery("#other_vertical_div").show();
            } else {
                jQuery("#chk_box_other").prop("checked", false);
                jQuery("#other_vertical_div").hide();
            }
        } else {
            jQuery("#chk_box_other").prop("checked", false);
            jQuery("#other_vertical_div").hide();
            jQuery('#other_vertical').val("");
        }


        if (idofselected == 'chk_box_02o') {
            var check = jQuery('#chk_box_02o').prop('checked');
            if (check) {
                jQuery('input[type=checkbox]').prop('checked', false);
                jQuery("#chk_box_02o").prop("checked", true);
            } else {
                jQuery("#chk_box_02o").prop("checked", false);
                jQuery('input[name="other_category"]').hide();
                jQuery('#other_category').val("");
            }
        } else {
            jQuery("#chk_box_02o").prop("checked", false);
            jQuery('input[name="other_category"]').hide();
            jQuery('#other_category').val("");

            if ($li.has('ul')) {
                $li.find(':checkbox').not(this).prop('checked', this.checked);
            }
            do {
                $ul = $li.parent();
                $parent = $ul.siblings(':checkbox');
                //                        console.log($parent);
                if ($chk.is(':checked')) {
                    $parent.prop('checked', $ul.has(':checkbox:not(:checked)').length == 0)
                } else {
                    $parent.prop('checked', false)
                }
                $chk = $parent;
                $li = $chk.closest('li');
            } while ($ul.is(':not(.someclass)'));
        }

    });


    /* Drop Down End */
    jQuery(document).ready(function () {
        var prod_add_status = "<?php echo $prod_add_status; ?>";
        var prod_id = "<?php echo $prod_id; ?>";
        if (prod_add_status > 0) {
            jQuery('html, body').animate({
                scrollTop: jQuery('#product_display1').offset().top
            }, 'slow');
            //                    jQuery('#new_product_info').slideToggle("slow");
            jQuery("#tooglecls_" + prod_id).trigger("click");
        }

        var other_checked = jQuery("#chk_box_02o").prop("checked");
        if (!other_checked) {
            jQuery("#chk_box_02o").prop("checked", false);
            jQuery('input[name="other_category"]').hide();
        }
        jQuery('.scroll').click(function () {
            jQuery('html, body').animate({
                scrollTop: eval(jQuery('#' + jQuery(this).attr('target')).offset().top - 200)
            }, 1000);
        });

        var maxLength = 300;
        jQuery("#show_categories_more").each(function () {
            var myStr = jQuery(this).html();
            var myStrArr = myStr.split("<br>");
            myStrArr = myStrArr.filter(function (e) {
                return String(e).trim();
            });
            //                                                                                                                                            console.log(myStrArr);
            if (myStrArr.length > 3) {
                var newStr = myStrArr[0] + "<br>" + myStrArr[1] + "<br>" + myStrArr[2];
                var removedStr = "";
                for (var i = 3; i < myStrArr.length; i++) {
                    removedStr += "<br>" + myStrArr[i];
                }
                jQuery(this).html('');
                jQuery(this).html(newStr);
                jQuery(this).append(' <a href="javascript:void(0);" class="read-more-categories" style="font-size:18px;">(...)</a>');
                jQuery(this).append('<span class="more-text">' + removedStr + '</span>');
            }
        });
        jQuery(".read-more-categories").click(function () {
            jQuery(this).siblings(".more-text").contents().unwrap();
            jQuery(this).remove();
        });
        jQuery("#show_verticles_more").each(function () {
            var myVertStr = jQuery(this).html();
            var myVertStrArr = myVertStr.split("<br>");
            myVertStrArr = myVertStrArr.filter(function (e) {
                return String(e).trim();
            });
            //                    console.log(myVertStrArr);
            if (myVertStrArr.length > 3) {
                var newStr = myVertStrArr[0] + "<br>" + myVertStrArr[1] + "<br>" + myVertStrArr[2];
                var removedStr = "";
                for (var i = 3; i < myVertStrArr.length; i++) {
                    removedStr += "<br>" + myVertStrArr[i];
                }
                jQuery(this).html('');
                jQuery(this).html(newStr);
                jQuery(this).append(' <a href="javascript:void(0);" class="read-more" style="font-size:18px;">(...)</a>');
                jQuery(this).append('<span class="more-text-verticle">' + removedStr + '</span>');
            }
        });
        jQuery(".read-more").click(function () {
            jQuery(this).siblings(".more-text-verticle").contents().unwrap();
            jQuery(this).remove();
        });

        jQuery(".usecase-slider").owlCarousel({
            items: 1,
            itemsDesktop: [1000, 1],
            itemsDesktopSmall: [979, 1],
            itemsTablet: [768, 1],
            pagination: false,
            navigation: true,
            navigationText: ["", ""],
            autoPlay: true
        });

        jQuery(".customer-slider").owlCarousel({
            items: 1,
            itemsDesktop: [1000, 1],
            itemsDesktopSmall: [979, 1],
            itemsTablet: [768, 1],
            pagination: false,
            navigation: true,
            navigationText: ["", ""],
            autoPlay: true
        });
        jQuery(".award-slider").owlCarousel({
            items: 1,
            itemsDesktop: [1000, 1],
            itemsDesktopSmall: [979, 1],
            itemsTablet: [768, 1],
            pagination: false,
            navigation: true,
            navigationText: ["", ""],
            autoPlay: true
        });
        jQuery(".testimonial-slider").owlCarousel({
            items: 1,
            itemsDesktop: [1000, 1],
            itemsDesktopSmall: [979, 1],
            itemsTablet: [768, 1],
            pagination: false,
            navigation: true,
            navigationText: ["", ""],
            autoPlay: true
        });
        jQuery(".video-slider").owlCarousel({
            items: 2,
            itemsDesktop: [1000, 1],
            itemsDesktopSmall: [979, 1],
            itemsTablet: [768, 1],
            pagination: false,
            navigation: true,
            navigationText: ["", ""],
            autoPlay: true
        });
        jQuery(".brief-slider").owlCarousel({
            items: 2,
            itemsDesktop: [1000, 1],
            itemsDesktopSmall: [979, 1],
            itemsTablet: [768, 1],
            pagination: false,
            navigation: true,
            navigationText: ["", ""],
            autoPlay: true
        });
        jQuery(".casestudy-slider").owlCarousel({
            items: 2,
            itemsDesktop: [1000, 1],
            itemsDesktopSmall: [979, 1],
            itemsTablet: [768, 1],
            pagination: false,
            navigation: true,
            navigationText: ["", ""],
            autoPlay: true
        });

        var maxLength = 300;
        jQuery(".award_description").each(function () {
            var myStr = jQuery(this).text();
            if (jQuery.trim(myStr).length > maxLength) {
                var newStr = myStr.substring(0, maxLength);
                var removedStr = myStr.substring(maxLength, jQuery.trim(myStr).length);
                jQuery(this).empty().html(newStr);
                jQuery(this).append(' <a href="javascript:void(0);" class="award-read-more">read more...</a>');
                jQuery(this).append('<span class="more-awards-text">' + removedStr + '</span>');
            }
        });
        jQuery(".award-read-more").click(function () {
            jQuery(this).siblings(".more-awards-text").contents().unwrap();
            jQuery(this).remove();
        });
        jQuery(".customer_description").each(function () {
            var myStr = jQuery(this).text();
            if (jQuery.trim(myStr).length > maxLength) {
                var newStr = myStr.substring(0, maxLength);
                var removedStr = myStr.substring(maxLength, jQuery.trim(myStr).length);
                jQuery(this).empty().html(newStr);
                jQuery(this).append(' <a href="javascript:void(0);" class="customers-read-more">read more...</a>');
                jQuery(this).append('<span class="more-customers-text">' + removedStr + '</span>');
            }
        });
        jQuery(".customers-read-more").click(function () {
            jQuery(this).siblings(".more-customers-text").contents().unwrap();
            jQuery(this).remove();
        });

        var totalRowCount = jQuery("#dynamic_patent_table tr").length;
        var rowCount = jQuery("#dynamic_patent_table td").closest("tr").length;
        if (rowCount > 5) {
            jQuery("#show-all-patents").show();
            jQuery("#dynamic_patent_table > tbody > tr").hide().slice(0, 5).show();
        }

        var top_product_id = jQuery("#product > div:first-child").attr('id');
        top_product_id = top_product_id.split("_");
        console.log(top_product_id);
        toggleProduct(top_product_id[1]);
    });
    jQuery(".show-all").on("click", function () {
        jQuery("table tr").show();
        jQuery("#show-all-patents").hide();
        //                                                                                                                                        jQuery("tbody > tr", jQuery("#dynamic_patent_table").prev()).show();
    });

    var statusofprofile = "<?= $get_profile_status; ?>";
    //                                                                                                                                                var statusofprofile = "9";
    //            alert(statusofprofile);
    jQuery(function ($) {
        jQuery('.progress').asProgress({
            'namespace': 'progress'
        });
        jQuery('.progress').asProgress('go', statusofprofile + '%');
    });

    if (statusofprofile <= 50) {
        jQuery('.progress > div').css({

            "display": "block",
            "height": "100%",
            "padding-left": "26px",
            "background-color": "rgb(247, 4, 29)",
            "background-image": "-webkit-gradient( linear, left bottom, left top, color-stop(0, rgb(194, 91, 43)), color-stop(1, rgb(194, 91, 43)) )",
            "background-image": "-moz-linear-gradient(center bottom, rgb(194, 91, 43) 37%, rgb(240, 84, 84) 69%)",
            "-webkit-box-shadow": "inset 0 2px 9px rgba(255,255,255,0.3), inset 0 -2px 6px rgba(0,0,0,0.4)",
            "-moz-box-shadow": "inset 0 2px 9px rgba(255,255,255,0.3), inset 0 -2px 6px rgba(0,0,0,0.4)",
            "box-shadow": "inset 0 2px 9px rgba(255,255,255,0.3), inset 0 -2px 6px rgba(0,0,0,0.4)",
            "position": "relative!important",
            "overflow": "hidden!important"
        });
        jQuery('.progress > div ::after, .animate > div').css({
            "content": "",
            "position": "absolute",
            "top": 0,
            "left": 0,
            "bottom": 0,
            "right": 0,
            //                    "background-image": "-moz-linear-gradient( -45deg, rgba(255, 255, 255, .2) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, .2) 50%, rgba(255, 255, 255, .2) 75%, transparent 75%, transparent )",
            "z-index": "1",
            "-webkit-background-size": "50px 50px",
            "-moz-background-size": "50px 50px",
            "background-size": "50px 50px",
            "-webkit-animation": "move 2s linear infinite",
            "-moz-animation": "move 2s linear infinite",
            "overflow": "hidden"
        });
    } else {
        jQuery('.progress > div').css({
            "display": "block",
            "height": "100%",
            "padding-left": "26px",
            "background-color": "rgb(43,84,194)",
            //                 "background-image": "-webkit-gradient( linear, left bottom, left top, color-stop(0, rgb(43,66,194)), color-stop(1, rgb(84,96,240)) )",
            "background-image": "-moz-linear-gradient( center bottom, rgb(71, 100, 244) 37%, rgb(71, 100, 244) 69% )",
            "-webkit-box-shadow": "inset 0 2px 9px rgba(255,255,255,0.3), inset 0 -2px 6px rgba(0,0,0,0.4)",
            "-moz-box-shadow": "inset 0 2px 9px rgba(255,255,255,0.3), inset 0 -2px 6px rgba(0,0,0,0.4)",
            "-webkit-box-shadow": "inset 0 2px 9px rgba(255,255,255,0.3), inset 0 -2px 6px rgba(0,0,0,0.4)",
            "-moz-box-shadow": "inset 0 2px 9px rgba(255,255,255,0.3), inset 0 -2px 6px rgba(0,0,0,0.4)",
            "box-shadow": "inset 0 2px 9px rgba(255,255,255,0.3), inset 0 -2px 6px rgba(0,0,0,0.4)",
            "position": "relative!important",
            "overflow": "hidden!important"
        });
        jQuery('.progress > div ::after, .animate > div').css({
            "content": "",
            "position": "absolute",
            "top": 0,
            "left": 0,
            "bottom": 0,
            "right": 0,
            //                    "background-image": "-moz-linear-gradient( -45deg, rgba(255, 255, 255, .2) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, .2) 50%, rgba(255, 255, 255, .2) 75%, transparent 75%, transparent )",
            "z-index": "1",
            "-webkit-background-size": "50px 50px",
            "-moz-background-size": "50px 50px",
            "background-size": "50px 50px",
            "-webkit-animation": "move 2s linear infinite",
            "-moz-animation": "move 2s linear infinite",
            "overflow": "hidden"
        });
    }




    jQuery('input[name="other_cat"]').on('click', function () {
        if (jQuery(this).prop('checked')) {
            jQuery('input[name="other_category"]').fadeIn();
        } else {
            jQuery('input[name="other_category"]').hide();
        }
    });
</script> 