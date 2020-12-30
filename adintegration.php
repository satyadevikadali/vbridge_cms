<?php
if (isset($_POST)) {
//    echo"<pre>";
//    print_r($_POST);
//    echo"</pre>";
//    exit;
    ini_set('max_execution_time', '0');
    require_once ('db_config.php');
    $register_type = '0';
    $url_array = explode("/", $_SERVER['REQUEST_URI']);
    array_pop($url_array);
    $str = implode("/", $url_array);
    $rootpath = "http://" . $_SERVER['HTTP_HOST'] . ($str) . '/';
//    echo $rootpath; exit;

    $check_user = "SELECT * FROM jm_users ju
                    LEFT JOIN company_info ci ON ci.company_id=ju.company_id
                    WHERE ju.email='" . $_POST['email'] . "' AND ci.type='" . $_POST['type'] . "'";
    $res = mysqli_query($con, $check_user);
    $row = mysqli_fetch_assoc($res);
    if (mysqli_num_rows($res) > 0) {
        // login directly
        $register_type = '1';
        ?>

        <input type="hidden" id="email" name="email" value="<?= $row['email'] ?>">
        <input type="hidden" id="password" name="password" value="<?= $row['password'] ?>">
        <input type="hidden" id="rootpath" name="rootpath" value="<?= $rootpath; ?>">
        <input type="hidden" id="login_id" name="login_id" value="<?= $_POST['type']; ?>">
        <?php
    } else {
        $register_type = '2';
        ?> 
        <form id="registration_form">
            <input type="hidden"  name="optradio"   id="optradio"  value="<?= $_POST['type'] ?>">
            <input type="hidden"  name="user_name" id="user_name" value="<?= $_POST['name'] ?>"  />
            <input type="hidden"  name="reg_email" id="reg_email" value="<?= $_POST['email'] ?>"  />
            <input type="hidden"  name="phone" id="phone" value="<?= $_POST['phone_number'] ?>"/>
            <input type="hidden"  name="isd_code" id="isd-code" value="<?= $_POST['isd_code'] ?>"/>
            <input type="hidden"  name="company_name" id="company_name" value="<?= $_POST['company_name'] ?>" />
            <input type="hidden"  name="companyurl" id="companyurl" value="<?= $_POST['company_url'] ?>"/>
            <input type="hidden" id="rootpath" name="rootpath" value="<?= $rootpath; ?>">
        </form>
        <?php
    }
}
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

<script>
//    $(document).ready(function () {
    var registerorlogin = '<?php echo $register_type; ?>';
//        alert(registerorlogin); 
    if (registerorlogin === '1') {
        var email = $("#email").val();
        var password = $("#password").val();
        var rootpath = $("#rootpath").val();
        var login_id = $("#login_id").val();
        var ad_integration = "1";
        $.ajax({
            type: 'POST',
            url: 'components/com_vbridge/views/Common_functions.php?type=check_email_password',
            data: "email=" + email + "&password=" + password + "&rootpath=" + rootpath + "&login_id=" + login_id + "&ad_integration=" + ad_integration,
            dataType: "json",
            success: function (data)
            {
                console.log(data);
                if (data.status == 'success' && data.group_id != 5) {
                    var company_id = btoa(data.company_id);
                    var product_id = btoa(data.product_id);
                    if (data.status = "success" && data.group_id == 1) {
                        window.location.href = rootpath + 'index.php/en/startup';
                    } else {
                        window.location.href = rootpath + 'index.php/en/gsi';
                    }
                } else if (data.status == 'success' && data.group_id == 5) {
                    window.location.href = rootpath + 'index.php/en/customersupport';
                } else {
                    jQuery('#notify').html(data.status);
                }
            }
        });
    } else {
        var formdata = jQuery("#registration_form").serializeArray();
        $.ajax({
            type: 'POST',
            url: 'components/com_vbridge/views/Common_functions.php?type=registration',
            data: formdata,
            dataType: "json",
            success: function (data)
            {
                if (data.status === 'DOMAIN') {
                    var user_id = data.user_id;
                    var rootpath = $("#rootpath").val();
                    $.ajax({
                        type: 'POST',
                        url: 'components/com_vbridge/views/Common_functions.php?type=adintegrationlogin',
                        data: "user_id=" + user_id + "&rootpath=" + rootpath,
                        dataType: "json",
                        success: function (data)
                        {
                            if (data.status == '1') {
                                var email = data.email;
                                var password = data.password;
                                var rootpath = $("#rootpath").val();
                                var login_id = $("#optradio").val();
                                var ad_integration = "1";
                                $.ajax({
                                    type: 'POST',
                                    url: 'components/com_vbridge/views/Common_functions.php?type=check_email_password',
                                    data: "email=" + email + "&password=" + password + "&rootpath=" + rootpath + "&login_id=" + login_id + "&ad_integration=" + ad_integration,
                                    dataType: "json",
                                    success: function (datavol)
                                    {
                                        console.log(datavol);
                                        if (datavol.status == 'success' && datavol.group_id != 5) {
                                            var company_id = btoa(datavol.company_id);
                                            var product_id = btoa(datavol.product_id);
                                            if (datavol.status = "success" && datavol.group_id == 1) {
                                                window.location.href = rootpath + 'index.php/en/startup';
                                            } else {
                                                window.location.href = rootpath + 'index.php/en/gsi';
                                            }
                                        } else if (datavol.status == 'success' && datavol.group_id == 5) {
                                            window.location.href = rootpath + 'index.php/en/customersupport';
                                        } else {
                                            jQuery('#notify').html(datavol.status);
                                        }
                                    }
                                });
                            }
                        }
                    });
                }
            }
        });
    }

//    });
</script>









