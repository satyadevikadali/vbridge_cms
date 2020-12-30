<?php
define('JPATH_BASE2', realpath(dirname(__FILE__) . '/../../../../..'));
$str = str_replace('\\', '/', JPATH_BASE2);
require_once ($str . '/modules/Admin_common_module_functions.php');
$common = new CommonModel();
$rootpath = $_REQUEST['rootpath'];
if (isset($_REQUEST['search_filter'])) {
    $search_value = $_REQUEST['search_by'];

    if ($search_value == '0') {
        $all_search_key = $_REQUEST['search_key'];
    } else if ($search_value == '1') {
        $from_date_and_time = $_REQUEST['from_date_and_time'];
        $to_date_and_time = $_REQUEST['to_date_and_time'];
        $condition = "WHERE ju.registerDate>='$from_date_and_time' AND ju.registerDate<='$to_date_and_time'";
    } else if ($search_value == '2') {
        $user_search_key = $_REQUEST['search_key'];
        if ($user_search_key == "Startup") {
            $user_type = '1';
        } else {
            $user_type = '2';
        }
        $condition = "WHERE jum.group_id='$user_type'";
    } else if ($search_value == '3') {
        $user_search_key = $_REQUEST['search_key'];
        if ($user_search_key == "Logged In") {
            
        } else if ($user_search_key == "Not Registered") {
            
        } else {
            
        }
    }
    $get_user_registration_status = $common->get_user_registration_status_data($condition);
} else {
    $get_user_registration_status = $common->get_user_registration_status_data();
}
?>
<!DOCTYPE html>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<link rel="stylesheet" href="<?php echo $rootpath ?>administrator/easyui/themes/default/easyui.css" type="text/css">
<link rel="stylesheet" href="<?php echo $rootpath ?>administrator/easyui/themes/icon.css" type="text/css">
<link rel="stylesheet" href="<?php echo $rootpath; ?>components/com_vbridge/css/buttons.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo $rootpath; ?>components/com_vbridge/css/bootstrap-datetimepicker.min.css"> 
<link rel="stylesheet" type="text/css" href="<?php echo $rootpath; ?>components/com_vbridge/css//bootstrap-datetimepicker-standalone.css">
<link rel="stylesheet" href="<?php echo $rootpath; ?>components/com_vbridge/css/request.css"> 
<script type="text/javascript" src="<?php echo $rootpath ?>components/com_vbridge/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $rootpath ?>administrator/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="<?php echo $rootpath ?>components/com_vbridge/js/moment.min.js"></script>
<script type="text/javascript" src="<?php echo $rootpath; ?>components/com_vbridge/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo $rootpath; ?>components/com_vbridge/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="<?php echo $rootpath; ?>components/com_vbridge/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="<?php echo $rootpath; ?>components/com_vbridge/js/dataTables.bootstrap.js"></script>
<script type="text/javascript" src="<?php echo $rootpath; ?>components/com_vbridge/js/dataTables.buttons.js"></script>
<script type="text/javascript" src="<?php echo $rootpath; ?>components/com_vbridge/js/buttons.bootstrap.js"></script>
<script type="text/javascript" src="<?php echo $rootpath; ?>components/com_vbridge/js/jszip.js"></script>
<script type="text/javascript" src="<?php echo $rootpath; ?>components/com_vbridge/js/pdfmake.js"></script>
<script type="text/javascript" src="<?php echo $rootpath; ?>components/com_vbridge/js/vfs_fonts.js"></script>
<script type="text/javascript" src="<?php echo $rootpath; ?>components/com_vbridge/js/buttons.html5.js"></script>
<script type="text/javascript" src="<?php echo $rootpath; ?>components/com_vbridge/js/buttons.print.min.js"></script>
<script type="text/javascript" src="<?php echo $rootpath; ?>components/com_vbridge/js/buttons.colVis.js"></script>


<style>
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
        <div class="card card-default">
            <div class="card-header" style="color:#ffffff">Registration Status Dashboard</div>
            <div class="card-body">
                <form class = "form-inline" role = "form" method="GET" id="filter_search_form">
                    <input type="hidden" id="rootpath" name="rootpath" value="<?= $rootpath; ?>">
                    <div class = "form-group" style="margin-left: 17px;">
                        <label for = "name">Search By</label>
                        <select class="form-control search_by" id="search_by" name="search_by" >
                            <option value="0" <?php if (isset($search_value) && $search_value == '0'): ?> selected="selected"<?php endif; ?>>All</option>
                            <option value="1" <?php if (isset($search_value) && $search_value == '1'): ?> selected="selected"<?php endif; ?>>Date</option>
                            <option value="2" <?php if (isset($search_value) && $search_value == '2'): ?> selected="selected"<?php endif; ?>>Entity</option>
                            <!--<option value="3" <?php if (isset($search_value) && $search_value == '3'): ?> selected="selected"<?php endif; ?>>Status</option>-->
                        </select>
                        <span id="search_by_error" class="error"></span>
                    </div>

                    <div class = "form-group" id="search_value" <?php if ((isset($search_value) && $search_value == '2') || (isset($search_value) && $search_value == '3')) { ?> style="display:none;" <?php } else { ?> style="display:inline;" <?php } ?>>
                        <label  for = "inputfile">Value:</label>
                        <input type="text" name="search_key" id="search_key" value="<?php
                        if (isset($_REQUEST['search_key']) && !empty($_REQUEST['search_key'])) {
                            echo $_REQUEST['search_key'];
                        }
                        ?>" class="form-control" >
                    </div>

                    <div class = "form-group" id="from_date" <?php if (isset($search_value) && $search_value == '3') { ?> style="" <?php } else { ?> style="display:none;" <?php } ?>>
                        <label for="from" class="col-xs-4" style="margin-top: 8px;">From Date</label>  
                        <div class="col-xs-3">
                            <div class="input-group date" id="from_date_time">
                                <input type="text" id="from_date_and_time" name="from_date_and_time"  value="<?php
                                if (isset($_REQUEST['from_date_and_time']) && !empty($_REQUEST['from_date_and_time'])) {
                                    echo $_REQUEST['from_date_and_time'];
                                }
                                ?>" style="min-height: 37px;"  readonly/>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span> 
                                </span>
                            </div>
                            <span id="date_and_time_error" class="error"></span>
                        </div>
                    </div>


                    <div class = "form-group" id="to_date" <?php if (isset($search_value) && $search_value == '3') { ?> style="" <?php } else { ?> style="display:none;" <?php } ?>>
                        <label for="to" class="col-xs-4" style="margin-top: 8px;">To Date</label>  
                        <div class="col-xs-3">
                            <div class="input-group date" id="to_date_time">
                                <input type="text" id="to_date_and_time" name="to_date_and_time" value="<?php
                                if (isset($_REQUEST['to_date_and_time'])) {
                                    echo $_REQUEST['to_date_and_time'];
                                }
                                ?>" style="min-height: 37px;" readonly />
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span> 
                                </span>
                            </div>
                            <span id="date_and_time_error" class="error"></span>
                        </div>
                    </div>
                    <div class="form-group" style="margin-left: 13px;">
                        <button type="submit" name="search_filter" value="search" class="btn btn" id="search_buttons" style="margin-left: 22px;background-color: #4764f4;color: #ffffff;">Search</button>
                        <button type ="reset" class = "btn btn-danger" style="background-color:red;" id="reset_button">Reset</button>
                    </div>
                </form>
                <div class="form-group">
                    <div class="button_group" style="margin-left: 16px;    width: 113%;">
                        <table id="example" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Registered Date</th>
                                    <th>Entity</th>
                                    <th>Organization Name</th>
                                    <th>User Name</th>
                                    <th>Email ID</th>
                                    <th>Contact Number</th>
                                    <th>Registered Type</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($get_user_registration_status) && !empty($get_user_registration_status)) {
                                    foreach ($get_user_registration_status as $reg_status) {
                                        ?>
                                        <tr>
                                            <td><?= (isset($reg_status['registerDate']) && !empty($reg_status['registerDate']) ? date('Y-M-d', strtotime($reg_status['registerDate'])) : "NA") ?></td>
                                            <td>
                                                <?php
                                                if ($reg_status['group_id'] == '1') {
                                                    echo 'Startup';
                                                } else {
                                                    echo 'ITSP';
                                                }
                                                ?>
                                            </td>
                                            <td><?= (isset($reg_status['company_name']) && !empty($reg_status['company_name']) ? $reg_status['company_name'] : "NA") ?></td>
                                            <td><?= (isset($reg_status['username']) && !empty($reg_status['username']) ? $reg_status['username'] : "NA") ?></td>
                                            <td><?= (isset($reg_status['email']) && !empty($reg_status['email']) ? $reg_status['email'] : "NA") ?></td>
                                            <td><?= (isset($reg_status['phone_no']) && !empty($reg_status['phone_no']) ? $reg_status['isd_code'] . "-" . $reg_status['phone_no'] : "NA") ?></td>
                                            <td>
                                                <?php
                                                if ($reg_status['created_by'] != '0') {
                                                    echo 'Created by Ops Team';
                                                } else {
                                                    echo 'Self Registered';
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                if ($reg_status['id'] != '' && $reg_status['login_count'] > 0) {
                                                    echo 'Logged In';
                                                } else if ($reg_status['id'] == '' && $reg_status['login_count'] == 0) {
                                                    echo 'Not Registered';
                                                } else {
                                                    echo 'Registered';
                                                }
                                                ?>
                                            </td>
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
<script>

    jQuery(document).ready(function () {
        var table = jQuery('#example').DataTable({
            dom: 'Bfrtip',
            lengthChange: false,
            buttons: ['copy', 'excel', 'pdf', 'colvis'],
            "order": [[3, "desc"]],
            "dateFormat": "YYYY-MM-DD",
            "aoColumns": [
                null,
                {"sType": "date-in"},
                null,
                null,
                null,
                null,
                null,
                null
            ]
        });
    });
    jQuery("select.search_by").change(function () {
        var selectedValue = $(this).children("option:selected").val();
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
            jQuery("#to_date").show();
            jQuery("#search_buttons").show();
        }
        if (selectedValue == '2' || selectedValue == '3') {
            jQuery("#search_key").val('');
            jQuery("#search_value").show();
            jQuery("#from_date").hide();
            jQuery("#to_date").hide();
            jQuery("#search_buttons").show();
        }
//        if (selectedValue == '4' || selectedValue == '5') {
//            jQuery("#search_buttons").show();
//            jQuery("#search_value").hide();
//            jQuery("#from_date").hide();
//            jQuery("#to_date").hide();
//        }
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
