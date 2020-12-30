<html>
    <head>
        <link rel="stylesheet" href="<?php echo JUri::root(); ?>administrator/easyui/themes/default/easyui.css" type="text/css">
        <link rel="stylesheet" href="<?php echo JUri::root(); ?>administrator/easyui/themes/icon.css" type="text/css">
        <script src="<?php echo JUri::root(); ?>administrator/easyui/jquery.easyui.min.js"></script>
        <style>
            .btn-success {
               display:none !important;
            }
        </style>
    </head>
    <body>
        <table id="tt" width="100%"  class="easyui-datagrid projects-plans" emptyMsg='No Records Found' url="<?php echo JUri::root() . '/administrator/components/com_vbridgeadmin/views/Admin_common_functions.php?type=userdetails'; ?>" loadMsg='Loading....' rownumbers="true" pagination="true" pageSize="20" nowrap="false" view="scrollview"  autoRowHeight="true"  auto="true" resizable="true"   singleSelect="true" >
            <thead>
                <tr>
                    <th field="registered_as"  align="left" width="14.28571428571429%"><b>Registered As</b></th>
                    <th field="username" sortable="true" align="left" width="14.28571428571429%"><b>User Name</b></th>
                    <th field="email" sortable="true" align="left" width="17%" ><b>User Email ID</b></th>
                    <th field="companyName" sortable="true" align="left" width="14.28571428571429%"><b>Company Name</b></th>
                    <th field="companyURL" sortable="true" align="left" width="14.999%"><b>Company URL</b></th>
                    <th field="registerDate" sortable="true" align="right" width="14.28571428571429%"><b>Registered Date</b></th>
                    <th field="app_status" align="right" width="14.28571428571429%"><b>Approval Status</b></th>
                </tr>
            </thead>
        </table>
        <script>
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
    </body>
</html>