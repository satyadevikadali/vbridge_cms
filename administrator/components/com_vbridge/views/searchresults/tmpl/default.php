<?php
//require_once ( JPATH_BASE . DS . 'modules' . DS . 'Common_module_functions.php' );
//$common = new CommonModel();
$rootpath = JURI::root();

?>

<link rel="stylesheet" href="<?php echo JUri::root(); ?>administrator/easyui/themes/default/easyui.css" type="text/css">
<link rel="stylesheet" href="<?php echo JUri::root(); ?>administrator/easyui/themes/icon.css" type="text/css">
<script src="<?php echo JUri::root(); ?>administrator/easyui/jquery.easyui.min.js"></script>

<link rel="stylesheet" href="<?php echo $rootpath; ?>components/com_vbridge/css/buttons.bootstrap.min.css">

<link rel="stylesheet" href="<?php echo $rootpath; ?>components/com_vbridge/css/request.css"> 
<script src="<?php echo $rootpath ?>components/com_vbridge/js/jquery.min.js"></script>
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
        margin-left: -41px !important;
        margin-right: -108px !important;
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
    <div class="row">
        <div class="card card-default">
            <div class="card-header">Subscriptions</div>
            <div class="card-body">
                <div class="form-group">
                    <div class="button_group" style="margin-left: 16px;    width: 113%;">
                        <table id="example" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Registered As</th>
                                    <th>User Email ID</th>
                                    <th>Company Name</th>
                                    <th>Company URL</th>
                                    <th>Registered Date</th>
                                    <th>Contact No</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>

        var rootpath="<?php echo $rootpath?>";
    jQuery(document).ready(function () {
        var table = jQuery('#example').DataTable({
       
            dom: 'Bfrtip',
            lengthChange: false,
            buttons: [ 'copy', 'excel', 'pdf', 'colvis' ],
            "order": [[3, "desc"]],
            
            "ajax": {
                url: "<?php echo JUri::root() ?>/administrator/components/com_vbridgeadmin/views/Admin_common_functions.php?type=subscriptions&rootpath="+rootpath,
               // url: "<?php // echo JUri::root() . '/administrator/components/com_vbridgeadmin/views/Admin_common_functions.php?type=userdetails&rootpath='+rootpath; ?>",
                type: 'get',
            },
            "columns": [
                {"data": "registered_as"},
                {"data": "email"},
                {"data": "companyName"},
                {"data": "companyURL"},
                {"data": "registerDate"},
                {"data": "contact_no"}
            ]
        });
//        table.buttons().container()
//        .appendTo( '#example_wrapper .col-sm-6:eq(0)' );
//                var table = jQuery('#example').DataTable({
//                    lengthChange: false,
//                    buttons: ['copy', 'excel', 'pdf', 'colvis'],
//                    "order": [[3, "desc"]]
//                });
//        table.buttons().container()
//                .appendTo('#example_wrapper .col-sm-6:eq(0)');
//
//        var table = jQuery('#example1').DataTable({
//            lengthChange: false,
            //buttons: ['copy', 'excel', 'pdf', 'colvis'],
            // "order": [[3, "desc"]]
//        });

    });
    function approveAction(id) {
        var rootpath = "<?php echo JUri::root(); ?>";
        rootpath = rootpath + "index.php/en/forgotpassword";
        jQuery.ajax({
            type: "POST",
            url: "<?php echo JUri::root() . '/administrator/components/com_vbridgeadmin/views/Admin_common_functions.php'; ?>",
            data: {id: id, type: 'approve_user', rootpath: rootpath},
            success: function (data) {
                if (data.trim() == "An Email has been sent to you, Please check your Email to Set Password") {
                    alert("Approved User");
                    // check_email_value = 1;
                    location.reload();
                } else {
                    check_email_value = 0;
                    alert(data);
                }
            }
        });
    }
    function rejectAction(id) {
        jQuery.ajax({
            type: "POST",
            url: "<?php echo JUri::root() . '/administrator/components/com_vbridgeadmin/views/Admin_common_functions.php'; ?>",
            data: {id: id, type: 'reject_user'},
            success: function (data) {
                if (data.trim() == "Rejected") {
                    alert("Rejected User");
                    location.reload();
                } else {
                    alert(data);
                }
            }
        });
    }


</script>
