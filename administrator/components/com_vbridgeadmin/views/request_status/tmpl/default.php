<?php
//require_once ( JPATH_BASE . DS . 'modules' . DS . 'Common_module_functions.php' );
//$common = new CommonModel();
$rootpath = JURI::root();
define('JPATH_BASE2', realpath(dirname(__FILE__) . '/../../../../..'));
$str = str_replace('\\', '/', JPATH_BASE2);
require_once ($str . '/modules/Admin_common_module_functions.php');
$common = new CommonModel();
if (isset($_REQUEST['search_filter'])) {
    $search_value = $_REQUEST['search_by'];

    if ($search_value == '0') {
        $all_search_key = $_REQUEST['search_key'];
    } else if ($search_value == '1') {
        $from_date_and_time = $_REQUEST['from_date_and_time'];
        $to_date_and_time = $_REQUEST['to_date_and_time'];
        $condition = " WHERE vgar.created_date>='$from_date_and_time' AND vgar.created_date<='$to_date_and_time'";
    } else if ($search_value == '2') {
        $user_search_key = $_REQUEST['search_key'];
        if ($user_search_key == "Startup") {
            $user_type = '1';
        } else if($user_search_key == "ITSP") {
            $user_type = '2';
        } else if($user_search_key == "Enterprise") {
            $user_type = '3';
        }
        $condition = " WHERE jumfrom.group_id='$user_type'";
    }
    $get_user_request_status = $common->get_user_request_status_data($condition);
} else {
    $get_user_request_status = $common->get_user_request_status_data($condition);
}
//echo "<pre>";
//print_r($get_user_request_status);
//echo"</pre>";
?>
<link rel="stylesheet" href="<?php echo JUri::root(); ?>administrator/easyui/themes/default/easyui.css" type="text/css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/css/bootstrap-datetimepicker.min.css">
<link rel="stylesheet" href="<?php echo JUri::root(); ?>administrator/easyui/themes/icon.css" type="text/css">
<script src="<?php echo JUri::root(); ?>administrator/easyui/jquery.easyui.min.js"></script>
<link rel="stylesheet" href="<?php echo $rootpath; ?>components/com_vbridge/css/request.css"> 
<script src="<?php echo $rootpath ?>components/com_vbridge/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $rootpath; ?>components/com_vbridge/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="<?php echo $rootpath; ?>components/com_vbridge/js/dataTables.bootstrap.js"></script>
<script type="text/javascript" src="<?php echo $rootpath; ?>components/com_vbridge/js/dataTables.buttons.js"></script>
<script type="text/javascript" src="<?php echo $rootpath; ?>components/com_vbridge/js/buttons.bootstrap.js"></script>
<script type="text/javascript" src="<?php echo $rootpath; ?>components/com_vbridge/js/jszip.js"></script>
<script type="text/javascript" src="<?php echo $rootpath; ?>components/com_vbridge/js/pdfmake.js"></script>
<script type="text/javascript" src="<?php echo $rootpath; ?>components/com_vbridge/js/vfs_fonts.js"></script>
<script type="text/javascript" src="<?php echo $rootpath; ?>components/com_vbridge/js/buttons.html5.js"></script>
<script type="text/javascript" src="<?php echo $rootpath; ?>components/com_vbridge/js/buttons.colVis.js"></script>

<style>
    .dropdown-menu {
        position: absolute;
        top: 100%;
        left: 0!important;
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
    @font-face {
        font-family: 'Glyphicons Halflings';
        src: url(//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/fonts/glyphicons-halflings-regular.eot);
        src: url(//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/fonts/glyphicons-halflings-regular.eot?#iefix) format('embedded-opentype'), url(//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/fonts/glyphicons-halflings-regular.woff) format('woff'), url(//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/fonts/glyphicons-halflings-regular.ttf) format('truetype'), url(//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/fonts/glyphicons-halflings-regular) format('svg')
    }

    .glyphicon {
        position: relative;
        top: 1px;
        display: inline-block;
        font-family: 'Glyphicons Halflings';
        font-style: normal;
        font-weight: 400;
        line-height: 1;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale
    }
    .glyphicon-calendar::before {
        content: "\e109";
    } 
    .glyphicon-chevron-left:before {
        content: "\e079";
    }
    .glyphicon-chevron-right:before {
        content: "\e080";
    }
    .input-group {
        position: relative;
        display: flex;
        border-collapse: separate
    }
    ul.list-unstyled{
        list-style-type: none!important;
    }
    .input-group[class*="col-"] {
        float: none;
        padding-right: 0;
        padding-left: 0
    }

    .input-group .form-control {
        margin-bottom: 0
    }

    .input-group-lg>.form-control,.input-group-lg>.input-group-addon,.input-group-lg>.input-group-btn>.btn {
        height: 46px;
        padding: 10px 16px;
        font-size: 18px;
        line-height: 1.33;
        border-radius: 6px
    }

    select.input-group-lg>.form-control,select.input-group-lg>.input-group-addon,select.input-group-lg>.input-group-btn>.btn {
        height: 46px;
        line-height: 46px
    }

    textarea.input-group-lg>.form-control,textarea.input-group-lg>.input-group-addon,textarea.input-group-lg>.input-group-btn>.btn {
        height: auto
    }

    .input-group-sm>.form-control,.input-group-sm>.input-group-addon,.input-group-sm>.input-group-btn>.btn {
        height: 30px;
        padding: 5px 10px;
        font-size: 12px;
        line-height: 1.5;
        border-radius: 3px
    }

    select.input-group-sm>.form-control,select.input-group-sm>.input-group-addon,select.input-group-sm>.input-group-btn>.btn {
        height: 30px;
        line-height: 30px
    }

    textarea.input-group-sm>.form-control,textarea.input-group-sm>.input-group-addon,textarea.input-group-sm>.input-group-btn>.btn {
        height: auto
    }

    .input-group-addon,.input-group-btn,.input-group .form-control {
        display: table-cell
    }

    .input-group-addon:not(:first-child):not(:last-child),.input-group-btn:not(:first-child):not(:last-child),.input-group .form-control:not(:first-child):not(:last-child) {
        border-radius: 0
    }

    .input-group-addon,.input-group-btn {
        width: 1%;
        white-space: nowrap;
        vertical-align: middle
    }
    .input-group.date .input-group-addon {
        cursor: pointer;
        width: 10px;
    }

    .input-group-addon {
        padding: 6px 12px;
        font-size: 14px;
        font-weight: normal;
        line-height: 1;
        color: #555;
        text-align: center;
        background-color: #eee;
        border: 1px solid #ccc;
        border-radius: 4px
    }

    .input-group-addon.input-sm {
        padding: 5px 10px;
        font-size: 12px;
        border-radius: 3px
    }

    .input-group-addon.input-lg {
        padding: 10px 16px;
        font-size: 18px;
        border-radius: 6px
    }

    .input-group-addon input[type="radio"],.input-group-addon input[type="checkbox"] {
        margin-top: 0
    }

    .input-group .form-control:first-child,.input-group-addon:first-child,.input-group-btn:first-child>.btn,.input-group-btn:first-child>.dropdown-toggle,.input-group-btn:last-child>.btn:not(:last-child):not(.dropdown-toggle) {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0
    }

    .input-group-addon:first-child {
        border-right: 0
    }

    .input-group .form-control:last-child,.input-group-addon:last-child,.input-group-btn:last-child>.btn,.input-group-btn:last-child>.dropdown-toggle,.input-group-btn:first-child>.btn:not(:first-child) {
        border-bottom-left-radius: 0;
        border-top-left-radius: 0
    }

    .input-group-addon:last-child {
        border-left: 0
    }

    .input-group-btn {
        position: relative;
        white-space: nowrap
    }

    .input-group-btn:first-child>.btn {
        margin-right: -1px
    }

    .input-group-btn:last-child>.btn {
        margin-left: -1px
    }

    .input-group-btn>.btn {
        position: relative
    }

    .input-group-btn>.btn+.btn {
        margin-left: -4px
    }

    .input-group-btn>.btn:hover,.input-group-btn>.btn:active {
        z-index: 2
    } 
    .subhead-collapse {
        display:none;
    }
    .dataTables_paginate {
        text-align: inherit ! important;
    }
    .dataTables_paginate {
        text-align: inherit ! important;
    }
    div.dataTables_wrapper div.dataTables_paginate ul.pagination {
        margin: 2px 0 ! important;
        white-space: nowrap ! important;
    }

    .card-default {
        border-color: #ddd;
        background-color: #fff;
        margin: 0px 0px 30px -37px;
        /* width: 333%; */
        margin-left: 0px !important;
        margin-right: 0px !important;
    }
    #example {
        width: 96% !important;
        margin-left: 10px !important;
        margin-top: 27px !important;
    }
    #example_filter {
        float: right !important;
        margin-right: 47px !important;
    }
    .dt-buttons.btn-group {
        margin-bottom: -23px !important;
        margin-left: 11px !important;
        margin-top: 5px;
    }
    .pagination>li {
        display: inline ! important;
    }
    ul li {
        line-height: 24px ! important;
    }

    div.dataTables_wrapper div.dataTables_paginate ul.pagination {
        margin: 2px 0 ! important;
        white-space: nowrap ! important;
    }
    ul {
        list-style-type: disc! important;
    }
    .pagination>li:first-child>a, .pagination>li:first-child>span {
        margin-left: 0 ! important;
        border-top-left-radius: 4px ! important;
        border-bottom-left-radius: 4px ! important;
    }
    .pagination>.disabled>a, .pagination>.disabled>a:focus, .pagination>.disabled>a:hover, .pagination>.disabled>span, .pagination>.disabled>span:focus, .pagination>.disabled>span:hover {
        color: #777;
        cursor: not-allowed ! important;
        background-color: #fff ! important;
        border-color: #ddd ! important;
    }
    .pagination>li>a, .pagination>li>span {
        position: relative ! important;
        float: left ! important;
        padding: 6px 12px ! important;
        margin-left: -1px ! important;
        line-height: 1.42857143 ! important;
        color: #337ab7 ! important;
        text-decoration: none ! important;
        background-color: #fff ! important;
        border: 1px solid #ddd ! important;
    }

    li {
        text-align: -webkit-match-parent;
    }

    .pagination>li {
        display: inline ! important;
    }
    ul li {
        line-height: 24px ! important;
    }

    ul li {
        line-height: 24px! important;
    }
    .dataTables_paginate paging_simple_numbers{
        float: right ! important;
        margin-right: 33px ! important;
        margin-top: -27px ! important;
    }
    .dataTables_info{
        margin-left: 13px !important;
    }
    #example_paginate{
        margin-left: 42% !important;
    }
    .form-control.input-sm {
        width: 189px !important;
        min-height: 29px !important;
    }
    #toolbar .btn-success {
        min-width: 148px;
        display: none !important;
    }
</style>
<div class="container">
    <div class="row" style="margin-top: 18px;">
    <a href="<?php echo $rootpath; ?>administrator/index.php?option=com_vbridgeadmin&view=registration_status&rootpath=<?php echo $rootpath; ?>" class="btn btn-info col-md-6" role="button">Registration Status Dashboard</a>
        <div class="card card-default">
            <div class="card-header" style="color:#ffffff">Request Status Dashboard</div>
            <div class="card-body">

                <form class="form-inline" role ="form" method="GET" id="filter_search_form">
                    <input type="hidden" id="rootpath" name="option" value="com_vbridgeadmin">
                    <input type="hidden" id="rootpath" name="view" value="request_status">
                    
                    <label for = "name">Search By</label>
                    <select class="form-control search_by" id="search_by" name="search_by" style="width:10%" >
                        <option value="0" <?php if (isset($search_value) && $search_value == '0'): ?> selected="selected"<?php endif; ?>>All</option>
                        <option value="1" <?php if (isset($search_value) && $search_value == '1'): ?> selected="selected"<?php endif; ?>>Date</option>
                        <option value="2" <?php if (isset($search_value) && $search_value == '2'): ?> selected="selected"<?php endif; ?>>Entity</option>
                    </select>
                    <span id="search_by_error" class="error"></span>
                    <!--</div>-->

                    <div class = "form-group" id="search_value" <?php if ((isset($search_value) && $search_value == '2') || (isset($search_value) && $search_value == '3')) { ?> style="display:inline;" <?php } else { ?> style="display:none;" <?php } ?>>
                        <label  for = "inputfile">Value:</label>
                        <input type="text" name="search_key" id="search_key" style="margin-top: -4px;" value="<?php
                        if (isset($_REQUEST['search_key']) && !empty($_REQUEST['search_key'])) {
                            echo $_REQUEST['search_key'];
                        }
                        ?>" class="form-control" >
                    </div>

                    <div class="form-group"  id="from_date" <?php if (isset($search_value) && $search_value == '3') { ?> style="display:inline-flex;" <?php } else { ?> style="display:none;" <?php } ?>>
                        <label for="from" class="col-xs-4" style="margin-top: 5px;">From Date&nbsp;&nbsp;</label>  
                        <div class="col-xs-3">
                            <div class="input-group date" id="from_date_time">
                                <input type="text" id="from_date_and_time" name="from_date_and_time"  value="<?php
                                if (isset($_REQUEST['from_date_and_time']) && !empty($_REQUEST['from_date_and_time'])) {
                                    echo $_REQUEST['from_date_and_time'];
                                }
                                ?>" style="min-height: 24px;"  readonly/>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span> 
                                </span>
                            </div>
                            <span id="date_and_time_error" class="error"></span>
                        </div>
                    </div>


                    <div class = "form-group"  id="to_date" <?php if (isset($search_value) && $search_value == '3') { ?> style="display:inline-flex;" <?php } else { ?> style="display:none;" <?php } ?>>
                        <label for="to" class="col-xs-4" style="margin-top: 5px;">To Date&nbsp;&nbsp;</label>  
                        <div class="col-xs-3">
                            <div class="input-group date" id="to_date_time">
                                <input type="text" id="to_date_and_time" name="to_date_and_time" value="<?php
                                if (isset($_REQUEST['to_date_and_time'])) {
                                    echo $_REQUEST['to_date_and_time'];
                                }
                                ?>" style="min-height: 24px;" readonly />
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span> 
                                </span>
                            </div>
                            <span id="date_and_time_error" class="error"></span>
                        </div>
                    </div>
                    <div class="form-group" style="display:contents;" style="margin-left: 13px;">
                        <button type="submit" name="search_filter" value="search" class="btn btn" id="search_buttons" style="margin-left: 22px;background-color: #4764f4;color: #ffffff;">Search</button>
                        <button type ="reset" class = "btn btn-danger" style="background-color:red;" id="reset_button">Reset</button>
                    </div>
                </form>
                <div class="form-group">
                    <div class="button_group" style="margin-left: 16px;width: 113%;">
                        <table id="example" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Request Date</th>
                                    <th>From Entity</th>
                                    <th>From Organization</th>
                                    <th>From User Name</th>
                                    <th>To Entity</th>
                                    <th>Registration Status</th>
                                    <th>To Organization</th>
                                    <th>To User Name</th>
                                    <th>Request Raised</th>
                                    <th>Response Received</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($get_user_request_status) && !empty($get_user_request_status)) {
                                    foreach ($get_user_request_status as $req_status) {
                                        ?>
                                        <tr>
                                            <td><?= (isset($req_status['request_created_date']) && !empty($req_status['request_created_date']) ? date('Y-M-d', strtotime($req_status['request_created_date'])) : "NA") ?></td>
                                            <td><?= (isset($req_status['from_entity']) && !empty($req_status['from_entity']) ? $req_status['from_entity'] : "NA") ?></td>
                                            <td><?= (isset($req_status['from_organization']) && !empty($req_status['from_organization']) ? ucwords($req_status['from_organization']) : "NA") ?></td>
                                            <td><?= (isset($req_status['from_username']) && !empty($req_status['from_username']) ? ucwords($req_status['from_username']) : "NA") ?></td>
                                            <td><?= (isset($req_status['to_entity']) && !empty($req_status['to_entity']) ? $req_status['to_entity'] : "NA") ?></td>
                                            <td>
                                                <?php
                                                if ($req_status['login_count'] > 0) {
                                                    echo 'Logged In';
                                                } else if ($req_status['login_count'] == 0) {
                                                    echo 'Not Registered';
                                                } else {
                                                    echo 'Registered';
                                                }
                                                ?>
                                            </td>
                                            <td><?= (isset($req_status['to_organization']) && !empty($req_status['to_organization']) ? ucwords($req_status['to_organization']) : "NA") ?></td>
                                            <td><?= (isset($req_status['to_username']) && !empty($req_status['to_username']) ? ucwords($req_status['to_username']) : "NA") ?></td>
                                            <td><?= (isset($req_status['request_raised']) && !empty($req_status['request_raised']) ? $req_status['request_raised'] : "NA") ?></td>
                                            <td><?= (isset($req_status['responsereceived']) && !empty($req_status['responsereceived']) ? $req_status['responsereceived'] : "NA") ?></td>
                                        </tr>

                                        <?php
                                    }
                                }
                                ?>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.1/moment.min.js"></script>    
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/js/bootstrap-datetimepicker.min.js"></script>    
<script>

    jQuery(document).ready(function () {
        var table = jQuery('#example').DataTable({
            dom: 'Bfrtip',
            lengthChange: false,
            buttons: ['copy', 'excel', 'pdf', 'colvis'],
            "order": [[1, "desc"]],
            "dateFormat": "YYYY-MM-DD",
            "aoColumns": [
                null,
                {"sType": "date-in"},
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null
            ]
        });
        jQuery("#from_date").hide();
        jQuery("#to_date").hide();
    });
    jQuery("select.search_by").change(function () {
        var selectedValue = jQuery(this).children("option:selected").val();
//        alert(selectedValue);
        if (selectedValue == '0') {
            jQuery("#search_key").val('');
            jQuery("#search_buttons").show();
            jQuery("#search_value").hide();
            jQuery("#from_date").hide();
            jQuery("#to_date").hide();
        }
        if (selectedValue == '1') {
            jQuery("#search_key").val('');
            jQuery("#search_value").hide();
            jQuery("#from_date").show();
            jQuery("#from_date").css("display", "inline-flex");
            jQuery("#to_date").show();
            jQuery("#to_date").css("display", "inline-flex");
            jQuery("#search_buttons").show();
        }
        if (selectedValue == '2') {
            jQuery("#search_key").val('');
            jQuery("#search_value").show();
            jQuery("#search_value").css("display", "inline-flex");
            jQuery("#from_date").hide();
            jQuery("#to_date").hide();
            jQuery("#search_buttons").show();
        }
    });

    jQuery('#from_date_time').datetimepicker({
        ignoreReadonly: true,
        format: 'YYYY-MM-DD',
        useCurrent: false
    });

    jQuery('#to_date_time').datetimepicker({
        ignoreReadonly: true,
        format: 'YYYY-MM-DD',
        useCurrent: false,
        minDate: moment()
    });



    jQuery('#from_date_time').datetimepicker().on('dp.change', function (e) {
        var incrementDay = moment(new Date(e.date));
        incrementDay.add(1, 'days');
        jQuery('#to_date_time').data('DateTimePicker').minDate(incrementDay);
        jQuery(this).data("DateTimePicker").hide();
    });
    jQuery('#to_date_time').datetimepicker().on('dp.change', function (e) {
        var decrementDay = moment(new Date(e.date));
        decrementDay.subtract(1, 'days');
        jQuery('#from_date_time').data('DateTimePicker').maxDate(decrementDay);
        jQuery(this).data("DateTimePicker").hide();
    });


</script>