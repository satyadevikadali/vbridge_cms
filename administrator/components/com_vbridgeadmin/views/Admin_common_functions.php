<?php

define('JPATH_BASE', realpath(dirname(__FILE__) . '/../../..'));
require_once (JPATH_BASE . '/../mailer/class.phpmailer.php');
require_once (JPATH_BASE . '/../db_config.php');

function mysqli_query_func($conn,$query){
    $result = mysqli_query($conn, $query);
    if(!$result){
        define('_JEXEC', 1);
        require_once ( JPATH_BASE . '/includes/defines.php' );
        require_once ( JPATH_BASE . '/includes/framework.php' );
        //echo(JPATH_BASE . '/db_config.php');
        $session = JFactory::getSession();
        $sessionData = $session->get('userdata');
        $user_id = $sessionData['id'];
          $err = mysqli_error($conn);
          $date = date("Y-m-d h:i:s");
          $err = str_replace("'","",$err);
          $ip_address = $_SERVER['SERVER_ADDR'];
          $query = str_replace("'","",$query); 
         // $user_id = $this->user_sess_id;
          //echo "insert into application_error_logs(query,error,ipaddress,date)values('$query','$err','$ip_address','$date')";
          mysqli_query($conn,"insert into application_error_logs(query,error,ip_address,user_id,date)values('$query','$err','$ip_address','$user_id','$date')");
        return false;
    }else{
        return $result;
    }

}

function sendmail($from_email, $email, $e_subject, $msg, $con) {
    $query = "SELECT * FROM vb_settings WHERE id=1";
    $res = mysqli_query_func($con, $query);
    $service_url = mysqli_fetch_assoc($res);
    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->SMTPDebug = 0;
    $mail->From = $from_email;
    $mail->FromName = "Donot-reply-mail@vbridgehub.com";
    $mail->Host = 'smtp.office365.com';
    $mail->SMTPSecure = 'tls';
    if (getenv('HTTP_HOST') == 'localhost' || getenv('HTTP_HOST') == '127.0.0.1') {
        $mail->Port = 587;
    } else {
        // $mail->Port = 25;
        $mail->Port = 587;
    }
    $mail->SMTPAuth = true;
    $mail->Username = "Donot-reply-mail@vbridgehub.com";
    $mail->Password = "Vbridgehub@123";
    $mail->AddAddress($email);
    $mail->AddReplyTo("donotreply@vbridgehub.com", "Vbridgehub");
    $mail->AddBCC($service_url['bcc'], "Vinayak");
    $mail->WordWrap = 50;
    $mail->IsHTML(true);
    $mail->Subject = $e_subject;
    $mail->Body = $msg;
    if ($mail->Send()) {
        return true;
    } else {
        echo 'Mailer Error: ' . $mail->ErrorInfo;
        return false;
    }
}

function checkhttp($url) {
    if (preg_match("#^(https?://|ftps?://)?(www.)?#", $url)) {
        $url = preg_replace("#^(https?://|ftps?://)?(www.)?#", "", $url);
    } else {
        $url = $url;
    }
    return $url;
}

function get_service_url($con) {
//        $data = array();
    $query = "SELECT * FROM vb_settings WHERE id='1'";
    $res = mysqli_query_func($con, $query);
    if ($res) {
        $row = mysqli_fetch_assoc($res);
        $data[] = $row;
    }
    return $data;
}

function insert_company($company_id, $domain_name, $companyurl, $company_name, $con) {
    $settings = get_service_url($con);
    $service_url = $settings;
    $companyurl = checkhttp($companyurl);
    $companyurl = "www." . $companyurl;
    $params = array(
        "companyId" => $company_id,
        "companyName" => $company_name,
        "website_Url" => $companyurl,
        "company_domain" => $domain_name,
    );
    $create = $service_url[0]['services_url'] . "/api/Company/Company/Create";
    $ch = curl_init();
    $payload = json_encode($params, 1);
    curl_setopt($ch, CURLOPT_URL, $create);
    curl_setopt($ch, CURLOPT_POST, strlen($payload));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $create_company = curl_exec($ch);
    $company_data = json_decode($create_company, 1);
    curl_close($ch);
    return $company_data;
}

//function add_alternate_domain($company_id, $domain_name_url, $con) {
//    $query = "SELECT * FROM vb_settings WHERE id=1";
//    $res = mysqli_query_func($con, $query);
//    $service_url = mysqli_fetch_assoc($res);
//    $params = array(
//        "companyId" => $company_id,
//        "domainName" => $domain_name_url,
//    );
////    echo"<pre>"; print_r($params); exit;
//    $create = $service_url['services_url'] . "/api/Company/Company/AddAltDomain";
//    $payload = json_encode($params, 1);
//    $ch = curl_init();
//    curl_setopt($ch, CURLOPT_URL, $create);
//    curl_setopt($ch, CURLOPT_POST, strlen($payload));
//    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
//    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
//    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
//    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//    $add_alt_company = curl_exec($ch);
//    $add_alt_company_data = json_decode($add_alt_company, 1);
//    curl_close($ch);
//    return $add_alt_company_data;
//}
function add_alternate_domain($company_id, $domain_name_url, $con) {
    $select = "SELECT * FROM company_info WHERE company_id='$company_id'";
    $result = mysqli_query_func($con, $select);
    $row = mysqli_fetch_assoc($result);
    $existing_alternate_domain = $row['alternate_domains'];
    if (isset($existing_alternate_domain) && !empty($existing_alternate_domain)) {
        $update_alternate_domains = $existing_alternate_domain . "," . $domain_name_url;
    } else {
        $update_alternate_domains = $domain_name_url;
    }
//    echo"<pre>"; print_r($update_alternate_domains);  echo"</pre>"; exit;
    $arrayinsert = array(
        "alternate_domains" => "'" . $update_alternate_domains . "'"
    );
    $where = "company_id = '" . $company_id . "'";
    $add_alternate_domains = update_data('company_info', $arrayinsert, $where, $con);
    return $add_alternate_domains;
}

function update_data($table, $data, $where, $con) {
    $return_res = array();
    try {
        $query = "UPDATE $table SET ";
        $i = 0;
        foreach ($data as $k => $datai):
            if ($i == 0) {
                $query = $query . " " . $k . " = " . $datai;
            } else {
                $query = $query . ", " . $k . " = " . $datai;
            }
            $i++;
        endforeach;
        $query = $query . " where " . $where;
//        echo $query . "<br/>";  exit;
        $result = mysqli_query_func($con, $query);
        $response = array(
            "insert_id" => 1,
            "status" => 1
        );
//        echo"<pre>"; print_r($response); echo"</pre>"; exit;
        return $response;
    } catch (Exception $e) {
        $response = array(
            "status" => 0,
            "error" => $e
        );
        return $response;
    }
}

function GetDomainName($url) {
    $host = @parse_url($url, PHP_URL_HOST);
    if (!$host)
        $host = $url;
    if (substr($host, 0, 4) == "www.")
        $host = substr($host, 4);
    if (strlen($host) > 50)
        $host = substr($host, 0, 47) . '...';
    return $host;
}

$type = $_REQUEST['type'];
if ($type == 'userdetails') {
    $rootpath = $_REQUEST['rootpath'];
    $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'jm_users.registerDate';
    $order = isset($_POST['order']) ? strval($_POST['order']) : 'DESC';
    $get_user_data = "SELECT  jm_users.id,jm_users.company_id,jm_users.username,jm_users.email,jm_users.activation,ci.company_name as companyName,jm_users.companyURL,jm_users.registerDate,jm_user_usergroup_map.group_id FROM jm_users LEFT JOIN jm_user_usergroup_map ON jm_users.id = jm_user_usergroup_map.user_id LEFT JOIN company_info ci ON ci.company_id=jm_users.company_id where jm_user_usergroup_map.group_id<>8 order by $sort $order";
    // echo $get_user_data; exit;
    $result = mysqli_query_func($con, $get_user_data);
    if (isset($result) && !empty($result)) {
        $array1 = array();
        while ($row = mysqli_fetch_array($result)) {
//            echo "<pre>";print_r($row);
            $encode_id = base64_encode('0');
            $encode_company_id = base64_encode($row['company_id']);
            if ($row['group_id'] == 1) {
                $row1['registered_as'] = 'Startup';
            } elseif ($row['group_id'] == 2) {
                $row1['registered_as'] = 'System Integrator';
            } elseif ($row['group_id'] == 3) {
                $row1['registered_as'] = 'Enterprise';
            } else {
                $row1['registered_as'] = 'Investor';
            }
			
			$row1['username']="NA";
		   if(!empty($row['email'])){ 
				$row1['email']=$row['email']; 
			}else{ 
				$row1['email']="NA";
			}
			
			if(!empty($row['companyName'])){ 
				$row1['companyName']="<a href='" . $rootpath . "index.php/en/companydetails?id=" . $encode_id . "&company_id=" . $encode_company_id . "' target='_blank'>" . $row['companyName'] . "</a>";
			}else{ 
				$row1['companyName']="NA";
			 }
			 
			if(!empty($row['companyURL'])){ 
				$row1['companyURL']=$row['companyURL']; 
			}else{ 
				$row1['companyURL']="NA";
			}
			          
            $row1['registerDate'] = date('Y-M-d', strtotime(str_replace('-', '/', $row['registerDate'])));
			// $row1['registerDate']='NA';
            // $row['registerDate'] = date('d-F-Y',$row['registerDate']);
            if ($row['activation'] == 1 || $row['activation'] == 3) {
                $row1['app_status'] = "System Approved";
            } else if ($row['activation'] == 2) {
                $row1['app_status'] = "Rejected";
            } else {
                $row1['app_status'] = "<a href='javascript:void(0);' id='btn-edit' onclick='approveAction(" . $row['id'] . ")' >
                                  <span class='glyphicon glyphicon-pencil'></span>Approve</a> | 
                                  <a href='javascript:void(0);' onclick='rejectAction(" . $row['id'] . ")' ><span class='glyphicon glyphicon-trash'></span>Reject</a>";
            }

            array_push($array1, $row1);
        }

        $data['data'] = $array1;
        echo json_encode($data);
        exit;
    } else {
        echo "Something Went Wrong";
    }
}
if ($type == 'subscriptions') {
    $rootpath = $_REQUEST['rootpath'];
    $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'jm_users.id';
    $order = isset($_POST['order']) ? strval($_POST['order']) : 'DESC';
    $get_user_data = "SELECT  jm_users.id,jm_users.company_id,jm_users.username,jm_users.email,jm_users.activation,jm_users.companyName,jm_users.companyURL,jm_users.registerDate,jm_user_usergroup_map.group_id,jm_users.phone_no as contact_no,jm_users.registerDate as registerDate FROM jm_users LEFT JOIN jm_user_usergroup_map ON jm_users.id = jm_user_usergroup_map.user_id where jm_user_usergroup_map.group_id<>8 and jm_users.email  NOT LIKE 'demo@%' and jm_users.phone_no!='' order by $sort $order";
    $result = mysqli_query_func($con, $get_user_data);
    if (isset($result) && !empty($result)) {
        $array1 = array();
        while ($row = mysqli_fetch_array($result)) {
//            echo "<pre>";print_r($row);
            $encode_id = base64_encode('0');
            $encode_company_id = base64_encode($row['company_id']);
            if ($row['group_id'] == 1) {
                $row1['registered_as'] = 'Startup';
            } elseif ($row['group_id'] == 2) {
                $row1['registered_as'] = 'System Integrator';
            } elseif ($row['group_id'] == 3) {
                $row1['registered_as'] = 'Enterprise';
            } else {
                $row1['registered_as'] = 'Investor';
            }
			
			$row1['username']="NA";
		   if(!empty($row['email'])){ 
				$row1['email']=$row['email']; 
			}else{ 
				$row1['email']="NA";
			}
			
			if(!empty($row['companyName'])){ 
				$row1['companyName']="<a href='" . $rootpath . "index.php/en/companydetails?id=" . $encode_id . "&company_id=" . $encode_company_id . "' target='_blank'>" . $row['companyName'] . "</a>";
			}else{ 
				$row1['companyName']="NA";
			 }
			 
			if(!empty($row['companyURL'])){ 
				$row1['companyURL']=$row['companyURL']; 
			}else{ 
				$row1['companyURL']="NA";
			}
			          
            //$row1['registerDate'] = date('Y-M-d', strtotime(str_replace('-', '/', $row['registerDate'])));
			//$row1['registerDate']='NA';
           	//$row['registerDate'] = date('d-F-Y',$row['registerDate']);
           
		   if(!empty($row['registerDate'])){ 
				$row1['registerDate']=$row['registerDate'];
			}else{ 
				$row1['registerDate']="NA";
			}
			if(!empty($row['contact_no'])){ 
				$row1['contact_no']=$row['contact_no'];
			}else{ 
				$row1['contact_no']="NA";
			 }

            array_push($array1, $row1);
        }

        $data['data'] = $array1;
        echo json_encode($data);
        exit;
    } else {
        echo "Something Went Wrong";
    }
}
if ($type == 'approve_user') {
    $status = array();
    $id = $_POST["id"];
    $rootpath = $_POST['rootpath'];
    $get_user = "SELECT * FROM jm_users WHERE id='" . $id . "'";
//    echo $get_user;exit;
    $result = mysqli_query_func($con, $get_user);
    if (isset($result) && !empty($result)) {
        $row = mysqli_fetch_array($result);
        $email = $row['email'];
        $username = $row['name'];
        $domain_name = substr(strrchr($email, "@"), 1);
        $companyurl = $row['companyURL'];
        $companyname = $row['companyName'];
        $company_id = $row['company_id'];
        $domain_name_url = checkhttp($companyurl);
        $add_alternate_domain = add_alternate_domain($company_id, $domain_name, $con);

        if ($add_alternate_domain['status'] == '1') {
            $hyperlink = $rootpath . "?reset_id=" . base64_encode($row['email']);
            $length = 20;
            $key = '';
            list($usec, $sec) = explode(' ', microtime());
            mt_srand((float) $sec + ((float) $usec * 100000));
            $inputs = array_merge(range('z', 'a'), range(0, 9), range('A', 'Z'));
            for ($i = 0; $i < $length; $i++) {
                $key .= $inputs{mt_rand(0, 61)};
            }
            $key_type = base64_encode('SETPASSWORD');
            $hyperlink .= "&key=" . base64_encode($key) . "&key_type=" . $key_type ."&company_id=".base64_encode($company_id);
            $sql = "UPDATE jm_users SET reset_key='" . $key . "' ,company_id='" . $company_id . "',sendEmail=1,activation=3 where id= '" . $row['id'] . "'";
            $result = mysqli_query_func($con, $sql);
            if (isset($result)) {
                //Administrator email address
                $comments = "<p>Hi " . ucwords($row['username']) . ",</p>";
                $comments .= "<p>We sincerely thank you for registering on vBridge Hub. </p>";
                $comments .= "<p>We want to ensure that users on vBridge Hub collaborate with the right stakeholders in the eco-system. In order to avoid any misuse of the system, we restrict users with personal email ID's and check any domain mis-match between mail ID and company URL. Your request for registration has been checked and approved.</p>";
                $comments .= "<h2></h2>";
                $comments .= "<p>Once again, we welcome you to vBridge Hub.</p>";
                $comments .= "<h2></h2>";
                $comments .= "<p>We have created a secured account for you.Please click on below link to activate and set password.<br><p>&#10004; Set Password link: (<a href='" . $hyperlink . "'>CLICK HERE </a>)</p>";
                $comments .= "<p>Thanks,</p> ";
                $comments .= "<p>The vBridge Hub Team</p> ";
                $e_subject = 'Set Password ';
                $from_email = "donot-reply-mail@vbridgehub.com";
                $e_content = PHP_EOL . PHP_EOL . $comments . PHP_EOL . PHP_EOL;
                $msg = wordwrap($e_content, 70);
                $to_email = $row['email'];
                $mail_send_status = sendmail($from_email, $to_email, $e_subject, $msg, $con);
                if ($mail_send_status) {
                    $status = 'An Email has been sent to you, Please check your Email to Set Password';
                } else {
                    $status = 'ERROR! email sending fail';
                }
            } else {
                $status = "Something went wrong! Please try again1";
            }
        } else {
            $status = "Something went wrong! Please try again2";
        }
        //}
    } else {
        $status = "Something went wrong! Please try again3";
    }
    echo $status;
}

if ($type == 'reject_user') {
    $status = array();
    $id = $_POST["id"];
    $get_user = "SELECT * FROM jm_users ju LEFT JOIN jm_user_usergroup_map jmap ON jmap.user_id=ju.id  WHERE id='" . $id . "'";
    $result = mysqli_query_func($con, $get_user);
    if (isset($result) && !empty($result)) {
        $row = mysqli_fetch_array($result);
        $email = $row['email'];
        $update = "UPDATE jm_users SET activation=2 where id= '" . $id . "'";
        $result = mysqli_query_func($con, $update);
        if ($result) {
            $comments = "<p>Hi " . ucwords($row['username']) . ",</p>";
            $comments .= "<p>We sincerely thank you for registering on vBridge Hub. </p>";
            $comments .= "<p>We want to ensure that users on vBridge Hub collaborate with the right stakeholders in the eco-system. In order to avoid any misuse of the system, we restrict users with a domain mismatch between mail ID and company URL. Hence, your request for registration has been checked and rejected.</p>";
            $comments .= "<p>Thanks for your interest in vBridge Hub.</p>";
            $comments .= "<p>Thank you,</p> ";
            $comments .= "<p>vBridge Hub Team</p> ";
            $e_subject = 'Rejected from vBridge Hub';
            $from_email = "donot-reply-mail@vbridgehub.com";
            $e_content = PHP_EOL . PHP_EOL . $comments . PHP_EOL . PHP_EOL;
            $msg = wordwrap($e_content, 70);
            $mail_send_status = sendmail($from_email, $email, $e_subject, $msg, $con);
            if ($mail_send_status) {
                $status = 'Rejected';
            } else {
                $status = 'ERROR! email sending fail';
            }
            if($row['group_id'] == '1') {
                $delete_from_startup_comm_info = "DELETE FROM vb_startup_com_info WHERE email='$email'";
                $del_startup_com_result = mysqli_query_func($con, $delete_from_startup_comm_info);
            }
            if($row['group_id'] == '2') {
                $delete_from_gsi_comm_info = "DELETE FROM vb_gsi_com_info WHERE email='$email'";
                $del_gsi_com_result = mysqli_query_func($con, $delete_from_gsi_comm_info);
            }
            if($row['group_id'] == '3') {
                $delete_from_enterprise_comm_info = "DELETE FROM vb_enterprise_com_info WHERE email='$email'";
                $del_enterprise_com_result = mysqli_query_func($con, $delete_from_enterprise_comm_info);
            }

            $delete_group_map_record = "DELETE FROM jm_user_usergroup_map WHERE user_id='$id'";
            $del_result = mysqli_query_func($con, $delete_group_map_record);
            $delete_main_table = "DELETE FROM jm_users WHERE id='$id'";
            $del_main_result = mysqli_query_func($con, $delete_main_table);
        } else {
            $status = "Something went wrong! Please try again";
        }
    } else {
        $status = "Something went wrong! Please try again";
    }
    echo $status;
}

if ($type == 'companydetails') {
    $rootpath = $_REQUEST['rootpath'];
    $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'cap.id';
    $order = isset($_POST['order']) ? strval($_POST['order']) : 'DESC';
    $check_qry = "select cap.*,ci.company_name from jm_vbridge_admin_company_approval_pending cap LEFT JOIN company_info ci on ci.company_id = cap.company_id";
    $result = mysqli_query_func($con, $check_qry);
    $count = mysqli_num_rows($result);
    if ($count > 0) {
        $array = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $types = explode(",", $row['types']);
            $type = "";
            $url = $rootpath . "index.php/companydetails?id=" . base64_encode('0') . "&company_id=" . base64_encode($row['company_id']) . "&admin_status=1";
            $row['company_name'] = "<a href='$url' target='_blank'>" . $row['company_name'] . "</a>";
            $updations['request_company_table_id'] = $row['id'];
            if (in_array(1, $types)) {
                if ($type == "") {
                    $type .= "Company details";
                } else {
                    $type .= ", Company details";
                }
            }
            if (in_array(2, $types)) {
                if ($type == "") {
                    $type .= "Company profile";
                } else {
                    $type .= ", Company profile";
                }
            }
            if (in_array(3, $types)) {
                if ($type == "") {
                    $type .= "Funding information";
                } else {
                    $type .= ", Funding information";
                }
            }
            if (in_array(4, $types)) {
                if ($type == "") {
                    $type .= "Product Details";
                } else {
                    $type .= ", Product Details";
                }
            }
            if (in_array(5, $types)) {
                if ($type == "") {
                    $type .= "Videos";
                } else {
                    $type .= ", Videos";
                }
            }
            if (in_array(6, $types)) {
                if ($type == "") {
                    $type .= "Briefs and papers";
                } else {
                    $type .= ", Briefs and papers";
                }
            }
            if (in_array(7, $types)) {
                if ($type == "") {
                    $type .= "Case studies";
                } else {
                    $type .= ", Case studies";
                }
            }
            if (in_array(8, $types)) {
                if ($type == "") {
                    $type .= "Testimonials details";
                } else {
                    $type .= ", Testimonials details";
                }
            }
            if (in_array(9, $types)) {
                if ($type == "") {
                    $type .= "Customer details";
                } else {
                    $type .= ", Customer details";
                }
            }
            if (in_array(10, $types)) {
                if ($type == "") {
                    $type .= "Awards and recognition details";
                } else {
                    $type .= ", Awards and recognition details";
                }
            }
            if (in_array(11, $types)) {
                if ($type == "") {
                    $type .= "News feed details";
                } else {
                    $type .= ", News feed details";
                }
            }
            if (in_array(12, $types)) {
                if ($type == "") {
                    $type .= "Usecases Information";
                } else {
                    $type .= ", Usecases Information";
                }
            }
            if (in_array(13, $types)) {
                if ($type == "") {
                    $type .= "Partner details";
                } else {
                    $type .= ", Partner details";
                }
            }
            if (in_array(14, $types)) {
                if ($type == "") {
                    $type .= "Blog details";
                } else {
                    $type .= ", Blog details";
                }
            }
            $type .= " updated";
            $row['changes'] = $type;
            $update_id = json_encode($updations);
            $row['app_status'] = "<a href='javascript:void(0);' id='btn-edit' onclick='approveAction(" . $update_id . ")' >
                <span class='glyphicon glyphicon-pencil'></span>Approve</a> | 
                <a href='javascript:void(0);' onclick='rejectAction(" . $update_id . ")' ><span class='glyphicon glyphicon-trash'></span>Reject</a>";
            array_push($array, $row);
        }
        $data['data'] = $array;
        echo json_encode($data);
        exit;
    } else {
        $data['data'] = array();
        echo json_encode($data);
        exit;
    }
}


if ($type == 'approve_company_data') {
//    echo"<pre>";
//    print_r($_POST);
//    echo"</pre>";
//    exit;
    $status = "success";
    if (isset($_POST['request_company_table_id']) && !empty($_POST['request_company_table_id']) && $_POST['request_company_table_id'] > 0) {
        $query = "SELECT * FROM jm_vbridge_admin_company_approval_pending WHERE id='" . $_POST['request_company_table_id'] . "'";
        $result = mysqli_query_func($con, $query);
        if (isset($result) && !empty($result)) {
            $row = mysqli_fetch_assoc($result);
            $company_id_main = $row['company_id'];
            $types = explode(",", $row['types']);
            if (in_array(1, $types) || in_array(2, $types)) {
                $id = $row['company_id'];
                $query = "SELECT cit.*,acap.types FROM company_info_temp cit LEFT JOIN jm_vbridge_admin_company_approval_pending acap ON cit.company_id=acap.company_id WHERE cit.company_id='" . $id . "'";
                $result = mysqli_query_func($con, $query);
                $update_fileds = "";
                $uploadpath = "";
                $status = "success";
                if (isset($result) && !empty($result)) {
                    $row = mysqli_fetch_assoc($result);
                    $types = explode(",", $row['types']);
                    $check_data = "SELECT * FROM company_info WHERE company_id='" . $row['company_id'] . "'";
                    $some_data = mysqli_query_func($con, $check_data);
                    if (mysqli_num_rows($some_data) > 0) {
                        $inser_or_update_status = 1; // Update
                    } else {
                        $inser_or_update_status = 0; // Insert
                    }
                    if (in_array(1, $types)) {
                        if ($row['company_logo_path'] != "") {
                            $uploadpath = $row['company_logo_path'];
                        }
                        if ($uploadpath != '') {
                            $update_fileds = "company_logo_path='$uploadpath',company_name='" . $row['company_name'] . "',short_description='" . $row['short_description'] . "',website_Url='" . $row['website_Url'] . "',linked_in_url='" . $row['linked_in_url'] . "',twitter_url='" . $row['twitter_url'] . "',facebook_url='" . $row['facebook_url'] . "',modified_by='" . $row['modified_by'] . "',modified_date=NOW()";
                            $insert_fields = "INSERT INTO company_info(company_logo_path,company_name,short_description,linked_in_url,twitter_url,facebook_url,created_by,created_date) VALUES('$uploadpath','" . $row['company_name'] . "','" . $row['short_description'] . "','" . $row['linked_in_url'] . "','" . $row['twitter_url'] . "','" . $row['facebook_url'] . "','" . $row['created_by'] . "',NOW())";
                        } else {
                            $update_fileds = "company_name='" . $row['company_name'] . "',short_description='" . $row['short_description'] . "',website_Url='" . $row['website_Url'] . "',linked_in_url='" . $row['linked_in_url'] . "',twitter_url='" . $row['twitter_url'] . "',facebook_url='" . $row['facebook_url'] . "',modified_by='" . $row['modified_by'] . "',modified_date=NOW()";
                            $insert_fields = "INSERT INTO company_info(company_name,short_description,website_Url,linked_in_url,twitter_url,facebook_url,created_by,created_date) VALUES('" . $row['company_name'] . "','" . $row['short_description'] . "','" . $row['website_Url'] . "','" . $row['linked_in_url'] . "','" . $row['twitter_url'] . "','" . $row['facebook_url'] . "','" . $row['created_by'] . "',NOW())";
                        }

                        if ($inser_or_update_status == 1) {
                            $update = "UPDATE company_info SET $update_fileds WHERE company_id='" . $row['company_id'] . "'";
                            $data = mysqli_query_func($con, $update);
                        } else {
                            $insert = $insert_fields;
                            $data = mysqli_query_func($con, $insert);
                            $inser_or_update_status = 1;
                        }
                    }

                    if (in_array(2, $types)) {
//                        echo "<pre>"; print_r($row); exit;
                        $data = "";
                        if ($inser_or_update_status == 1) {
                            $update_fields = " founder_name='" . $row['founder_name'] . "',total_annual_revenue='" . $row['total_annual_revenue'] . "',founded='" . $row['founded'] . "',country='" . $row['country'] . "',state='" . $row['state'] . "',city='" . $row['city'] . "',total_funding='" . $row['total_funding'] . "',description='" . str_replace("'", "&#39;", $row['description']) . "',modified_by='" . $row['modified_by'] . "',modified_date=NOW()";
                            $update = "UPDATE company_info SET  " . $update_fields . " WHERE company_id='" . $row['company_id'] . "'";
//                            echo $update; exit;
                            $data = mysqli_query_func($con, $update);
                        } else {
                            $insert_fields = "INSERT INTO company_info(company_id,company_name,description,founder_name,total_annual_revenue,founded,country,state,city,total_funding,created_by,created_date) VALUES('" . $row['company_id'] . "','" . $row['company_name'] . "','" . str_replace("'", "&#39;", $row['description']) . "','" . $row['founder_name'] . "','" . $row['total_annual_revenue'] . "','" . $row['founded'] . "','" . $row['country'] . "','" . $row['state'] . "','" . $row['city'] . "','" . $row['total_funding'] . "','" . $row['created_by'] . "',NOW())";
                            $insert = $insert_fields;
                            $data = mysqli_query_func($con, $insert);
                            $inser_or_update_status = 1;
                        }
                    }
                    if ($data) {
                        $delete = "DELETE FROM company_info_temp WHERE company_id='" . $row['company_id'] . "'";
                        $res = mysqli_query_func($con, $delete);
                    }
                }
            }
            if (in_array(3, $types)) {
                $query = "SELECT * FROM funding_information_temp WHERE company_id = '" . $row['company_id'] . "'";
                $retval = mysqli_query_func($con, $query);
                if (mysqli_num_rows($retval) > 0) {
                    $query = "SELECT * FROM funding_information WHERE company_id='" . $row['company_id'] . "'";
                    $res = mysqli_query_func($con, $query);
                    if (mysqli_num_rows($res) > 0) {
                        $delete = "DELETE FROM funding_information WHERE company_id='" . $row['company_id'] . "'";
                        $res = mysqli_query_func($con, $delete);
                    }
                    while ($get_res = mysqli_fetch_array($retval)) {
                        $insert = "INSERT INTO funding_information(funding_round, funding_date, funding_amount, investor, company_id, created_by, created_date) VALUES ('" . $get_res['funding_round'] . "','" . $get_res['funding_date'] . "','" . $get_res['funding_amount'] . "','" . $get_res['investor'] . "','" . $get_res['company_id'] . "','" . $get_res['created_by'] . "',NOW())";
                        $data = mysqli_query_func($con, $insert);
                    }
                    if ($data) {
                        $delete = "DELETE FROM funding_information_temp WHERE company_id = '" . $row['company_id'] . "'";
                        $data = mysqli_query_func($con, $delete);
                    }
                }
            }
            if (in_array(4, $types)) {
//                echo "<pre>";
                $product_id = 0;
                $company_id_pro = $row['company_id'];
                $query = "SELECT * FROM jm_vbridge_admin_products_approval_pending WHERE company_id='" . $company_id_pro . "'";
                $result_pro = mysqli_query_func($con, $query);
                if (mysqli_num_rows($result_pro) > 0) {
                    while ($row_pro = mysqli_fetch_array($result_pro)) {
                        $query = "SELECT * FROM products_temp WHERE product_id='" . $row_pro['product_id'] . "'";
                        $data_products = mysqli_query_func($con, $query);
                        $row_pro = mysqli_fetch_assoc($data_products);
                        if (mysqli_num_rows($data_products) > 0) {
                            $update = "UPDATE products SET industry_vertical='" . $row_pro['industry_vertical'] . "',name='" . mysqli_real_escape_string($con, $row_pro['name']) . "',Short_Description='" . mysqli_real_escape_string($con, $row_pro['Short_Description']) . "',Long_Description='" . mysqli_real_escape_string($con, $row_pro['Long_Description']) . "',Company_Id='" . $row_pro['Company_Id'] . "',last_modified_timestamp=NOW() WHERE product_id='" . $row_pro['product_id'] . "'";
                            $data = mysqli_query_func($con, $update);
                            $product_id = $row_pro['product_id'];
                        } else {
                            $insert = "INSERT INTO products(industry_vertical,name, Short_Description, Long_Description, Company_Id, Created_timestamp) VALUES ('" . $row_pro['industry_vertical'] . "','" . mysqli_real_escape_string($con, $row_pro['name']) . "','" . mysqli_real_escape_string($con, $row_pro['Short_Description']) . "','" . mysqli_real_escape_string($con, $row_pro['Long_Description']) . "','" . $row_pro['Company_Id'] . "',NOW())";
                            $data = mysqli_query_func($con, $insert);
                            $product_id = mysqli_insert_id($con);
                        }
                        $delete_prod_cat = "DELETE FROM product_categories WHERE Product_Id='" . $row_pro['product_id'] . "'";
                        $data = mysqli_query_func($con, $delete_prod_cat);
                        $query_prod_cat = "SELECT * FROM product_categories_temp WHERE Product_Id = " . $row_pro['product_id'] . "";
                        $data_prod_cat = mysqli_query_func($con, $query_prod_cat);
                        $insert_data = array();
                        $group_catgeroy_ids = array();
                        if ($data_prod_cat) {
                            while ($row_cat = mysqli_fetch_array($data_prod_cat)) {
                                $group_cat_id = $row_cat['Group_Category_Id'];
                                $group_cat_query = "SELECT * FROM group_categories WHERE Group_Category_Id = $group_cat_id";
                                $group_cat_res = mysqli_query_func($con, $group_cat_query);
                                $group_cat = mysqli_fetch_assoc($group_cat_res);
                                if (isset($group_cat) && !empty($group_cat)) {
                                    $catname = $group_cat['Category_Name'];
                                    $subcatname = $group_cat['SubCategory_Name'];
                                    if (isset($subcatname) && !empty($subcatname)) {
                                        $group_cat_query_real = "SELECT * FROM group_categories WHERE Category_Name = '$catname' and SubCategory_Name = '$subcatname'";
                                        $group_cat_res_real = mysqli_query_func($con, $group_cat_query_real);
                                        $group_cat_real = mysqli_fetch_assoc($group_cat_res_real);
                                        if (isset($group_cat_real) && !empty($group_cat_real)) {
                                            $group_catgeroy_ids[] = $group_cat_real['Group_Category_Id'];
                                        } else {
                                            // insert group_categories
                                            $insert = "INSERT INTO group_categories(Category_Name, SubCategory_Name, Created_timestamp) VALUES ('" . $group_cat['Category_Name'] . "','" . $group_cat['SubCategory_Name'] . "',NOW())";
                                            $group_cat_res = mysqli_query_func($con, $insert);
                                            $group_catgeroy_ids[] = mysqli_insert_id($con);
                                        }
                                        // $group_cat_id group_categories_temp
                                        $delete = "DELETE FROM group_categories_temp WHERE Group_Category_Id='$group_cat_id'";
                                        $res = mysqli_query_func($con, $delete);
                                    } else {
                                        $group_cat_query_real = "SELECT * FROM group_categories WHERE Category_Name = '$catname'";
                                        $group_cat_res_real = mysqli_query_func($con, $group_cat_query_real);
                                        $group_cat_real = mysqli_fetch_assoc($group_cat_res_real);
                                        if (isset($group_cat_real) && !empty($group_cat_real)) {
                                            $group_catgeroy_ids[] = $group_cat_real['Group_Category_Id'];
                                        } else {
                                            // insert group_categories
                                            $insert = "INSERT INTO group_categories(Category_Name, SubCategory_Name, Created_timestamp) VALUES ('" . $group_cat['Category_Name'] . "','" . $group_cat['SubCategory_Name'] . "',NOW())";
                                            $group_cat_res = mysqli_query_func($con, $insert);
                                            $group_catgeroy_ids[] = mysqli_insert_id($con);
                                        }
                                        // $group_cat_id group_categories_temp 
                                        $delete = "DELETE FROM group_categories_temp WHERE Group_Category_Id='$group_cat_id'";
                                        $res = mysqli_query_func($con, $delete);
                                    }
                                } else {
                                    // next
                                }
                            }
                        }

                        // product_id $product_id
                        $delete = "DELETE FROM products_temp WHERE product_id='$product_id'";
                        $res = mysqli_query_func($con, $delete);
                        // product_cat $product_id
                        $delete = "DELETE FROM product_categories_temp WHERE product_id='$product_id'";
                        $res = mysqli_query_func($con, $delete);

                        // jm_vbridge_admin_products_approval_pending delete
                        $delete = "DELETE FROM jm_vbridge_admin_products_approval_pending WHERE product_id='$product_id'";
                        $res = mysqli_query_func($con, $delete);

                        foreach ($group_catgeroy_ids as $group_cat) {
                            $insert = "INSERT INTO product_categories(Product_Id, Group_Category_Id) VALUES ('$product_id','$group_cat')";
                            $prod_cat_res = mysqli_query_func($con, $insert);
                        }
                    }
                }
            }
            if (in_array(5, $types)) {
                $query = "SELECT * FROM jm_vbridge_product_videos_temp where company_id='" . $row['company_id'] . "'";
                $data = mysqli_query_func($con, $query);
                if ($data) {
                    while ($row1 = mysqli_fetch_assoc($data)) {
//                        print_r($row1);
                        $insert = "INSERT INTO jm_vbridge_product_videos(company_id, title, video_type, video_path, original_filename, renamed_filename, created_by, created_date) VALUES ('" . $row1['company_id'] . "','" . $row1['title'] . "','" . $row1['video_type'] . "','" . $row1['video_path'] . "','" . $row1['original_filename'] . "','" . $row1['renamed_filename'] . "','" . $row1['created_by'] . "',NOW())";
//                        echo $insert; exit;
                        $vbridge_product_videos_insert_status = mysqli_query_func($con, $insert);
                        if ($vbridge_product_videos_insert_status) {
                            $query = "DELETE FROM jm_vbridge_product_videos_temp WHERE company_id='" . $row['company_id'] . "'";
                            $vbridge_product_videos_temp_delete_status = mysqli_query_func($con, $query);
                        }
                    }
                }
            }
            if (in_array(6, $types)) {
                $query = "SELECT * FROM jm_vbridge_product_briefs_paper_temp WHERE company_id=" . $row['company_id'] . "";
                $data = mysqli_query_func($con, $query);
                if ($data) {
                    while ($row1 = mysqli_fetch_assoc($data)) {
                        if ($row1['edit_main_briefs_paper_id'] > 0) {
                            $edit_main_briefs_paper_id = $row1['edit_main_briefs_paper_id'];
                            $sql = "UPDATE jm_vbridge_product_briefs_paper SET company_id='" . $row1['company_id'] . "',title='" . mysqli_real_escape_string($con, $row1['title']) . "',description='" . mysqli_real_escape_string($con, $row1['description']) . "',path='" . $row1['path'] . "',original_filename='" . $row1['original_filename'] . "',renamed_filename='" . $row1['renamed_filename'] . "',modified_by='1',modifiled_date=NOW() WHERE id = '" . $edit_main_briefs_paper_id . "'";
                            $vbridge_product_case_studies_insert_status = mysqli_query_func($con, $sql);
                            if ($vbridge_product_case_studies_insert_status) {
                                $query = "DELETE FROM jm_vbridge_product_briefs_paper_temp WHERE company_id=" . $row['company_id'] . " ";
                                $vbridge_product_case_studies_temp_delete_status = mysqli_query_func($con, $query);
                            }
                        } else {
                            $insert = "INSERT INTO jm_vbridge_product_briefs_paper(company_id, title,description, path, original_filename, renamed_filename, created_by, created_date) VALUES ('" . $row1['company_id'] . "','" . mysqli_real_escape_string($con, $row1['title']) . "','" . mysqli_real_escape_string($con, $row1['description']) . "','" . $row1['path'] . "','" . $row1['original_filename'] . "','" . $row1['renamed_filename'] . "','" . $row1['created_by'] . "',NOW())";
                            $vbridge_product_briefs_paper_insert_status = mysqli_query_func($con, $insert);
                            if ($vbridge_product_briefs_paper_insert_status) {
                                $query = "DELETE FROM jm_vbridge_product_briefs_paper_temp WHERE company_id='" . $row['company_id'] . "'";
                                $vbridge_product_briefs_paper_temp_delete_status = mysqli_query_func($con, $query);
                            }
                        }
                    }
                }
            }
            if (in_array(7, $types)) {
                $query = "SELECT * FROM jm_vbridge_product_case_studies_temp WHERE company_id=" . $row['company_id'] . "";
                $data = mysqli_query_func($con, $query);
                if ($data) {
                    while ($row1 = mysqli_fetch_assoc($data)) {
//                        print_r($row1);
                        if ($row1['edit_main_casestudy_id'] > 0) {
                            $usecase_id = $row1['edit_main_casestudy_id'];
                            $sql = "UPDATE jm_vbridge_product_case_studies SET company_id='" . $row1['company_id'] . "',title='" . mysqli_real_escape_string($con, $row1['title']) . "',description='" . mysqli_real_escape_string($con, $row1['description']) . "',path='" . $row1['path'] . "',original_filename='" . $row1['original_filename'] . "',renamed_filename='" . $row1['renamed_filename'] . "',modified_by='" . $row1['modified_by'] . "',modifiled_date=NOW() WHERE id = '" . $usecase_id . "'";
                            $vbridge_product_case_studies_insert_status = mysqli_query_func($con, $sql);
                            if ($vbridge_product_case_studies_insert_status) {
                                $query = "DELETE FROM jm_vbridge_product_case_studies_temp WHERE company_id=" . $row['company_id'] . " ";
                                $vbridge_product_case_studies_temp_delete_status = mysqli_query_func($con, $query);
                            }
                        } else {
                            $insert = "INSERT INTO jm_vbridge_product_case_studies(company_id, title,description, path, original_filename, renamed_filename, created_by, created_date) VALUES ('" . $row1['company_id'] . "','" . mysqli_real_escape_string($con, $row1['title']) . "','" . mysqli_real_escape_string($con, $row1['description']) . "','" . $row1['path'] . "','" . $row1['original_filename'] . "','" . $row1['renamed_filename'] . "','" . $row1['created_by'] . "',NOW())";
                            $vbridge_product_case_studies_insert_status = mysqli_query_func($con, $insert);
                            if ($vbridge_product_case_studies_insert_status) {
                                $query = "DELETE FROM jm_vbridge_product_case_studies_temp WHERE company_id=" . $row['company_id'] . " ";
                                $vbridge_product_case_studies_temp_delete_status = mysqli_query_func($con, $query);
                            }
                        }
                    }
                }
            }
            if (in_array(8, $types)) {
                $query = "SELECT * FROM jm_vbridge_product_testimonials_temp WHERE company_id = '" . $row['company_id'] . "'";
                $retval = mysqli_query_func($con, $query);
                if (mysqli_num_rows($retval) > 0) {
                    $query = "SELECT * FROM jm_vbridge_product_testimonials WHERE company_id='" . $row['company_id'] . "'";
                    $res = mysqli_query_func($con, $query);
                    if (mysqli_num_rows($res) > 0) {
                        $delete = "DELETE FROM jm_vbridge_product_testimonials WHERE company_id='" . $row['company_id'] . "'";
                        $res = mysqli_query_func($con, $delete);
                    }
                    while ($get_res = mysqli_fetch_array($retval)) {
                        $insert = "INSERT INTO jm_vbridge_product_testimonials(company_id, description,organization_name, author_name,testimonial_logo_path,created_by,created_date) VALUES ('" . $get_res['company_id'] . "','" . mysqli_real_escape_string($con, $get_res['description']) . "','" . $get_res['organization_name'] . "','" . $get_res['author_name'] . "','" . $get_res['testimonial_logo_path'] . "','" . $get_res['created_by'] . "',NOW())";
//                        echo $insert; exit;
                        $customer_insert_status = mysqli_query_func($con, $insert);
                    }
                    if ($customer_insert_status) {
                        $delete = "DELETE FROM jm_vbridge_product_testimonials_temp WHERE company_id = '" . $row['company_id'] . "'";
                        $data = mysqli_query_func($con, $delete);
                    }
                }
            }
            if (in_array(9, $types)) {
                $query = "SELECT * FROM jm_vbridge_product_customers_temp WHERE company_id='" . $row['company_id'] . "'";
                $retval = mysqli_query_func($con, $query);
                if (mysqli_num_rows($retval) > 0) {
                    $query = "SELECT * FROM jm_vbridge_product_customers WHERE company_id='" . $row['company_id'] . "'";
                    $res = mysqli_query_func($con, $query);
                    if (mysqli_num_rows($res) > 0) {
                        $delete = "DELETE FROM jm_vbridge_product_customers WHERE company_id='" . $row['company_id'] . "'";
                        $res = mysqli_query_func($con, $delete);
                    }
                    while ($get_res = mysqli_fetch_array($retval)) {
                        $insert = "INSERT INTO jm_vbridge_product_customers(company_id, description, customer_name,customer_logo_path,created_date,created_by) VALUES ('" . $get_res['company_id'] . "','" . mysqli_real_escape_string($con, $get_res['description']) . "','" . mysqli_real_escape_string($con, $get_res['customer_name']) . "','" . $get_res['customer_logo_path'] . "',NOW(),'" . $get_res['created_by'] . "')";
                        $customer_insert_status = mysqli_query_func($con, $insert);
                    }
                    if ($customer_insert_status) {
                        $delete = "DELETE FROM jm_vbridge_product_customers_temp WHERE company_id = '" . $row['company_id'] . "'";
                        $data = mysqli_query_func($con, $delete);
                    }
                }
            }
            if (in_array(10, $types)) {
                $query = "SELECT * FROM jm_vbridge_product_awards_temp WHERE company_id='" . $row['company_id'] . "'";
                $retval = mysqli_query_func($con, $query);
                if (mysqli_num_rows($retval) > 0) {
                    $query = "SELECT * FROM jm_vbridge_product_awards WHERE company_id='" . $row['company_id'] . "'";
                    $res = mysqli_query_func($con, $query);
                    if (mysqli_num_rows($res) > 0) {
                        $delete = "DELETE FROM jm_vbridge_product_awards WHERE company_id='" . $row['company_id'] . "'";
                        $res = mysqli_query_func($con, $delete);
                    }
                    while ($get_res = mysqli_fetch_array($retval)) {
                        $insert = "INSERT INTO jm_vbridge_product_awards(company_id, description, award_name,award_logo_path,created_by,created_date) VALUES ('" . $get_res['company_id'] . "','" . mysqli_real_escape_string($con, $get_res['description']) . "','" . $get_res['award_name'] . "','" . $get_res['award_logo_path'] . "','" . $get_res['created_by'] . "',NOW())";
                        $customer_insert_status = mysqli_query_func($con, $insert);
                    }
                    if ($customer_insert_status) {
                        $delete = "DELETE FROM jm_vbridge_product_awards_temp WHERE company_id = '" . $row['company_id'] . "'";
                        $data = mysqli_query_func($con, $delete);
                    }
                }
            }
            if (in_array(12, $types)) {
                /* Note: Here product_id acts as company_id as per service issues we have to rename company_id as product_id */
                $query = "SELECT product_id,GROUP_CONCAT(DISTINCT(UseCase_Id)) as usecase_id FROM product_usecases_temp where  product_id='" . $row['company_id'] . "'";
//            echo $query; exit;
                $data = mysqli_query_func($con, $query);
                if ($data) {
                    $row = mysqli_fetch_assoc($data);
//                    print_r($row);exit;
                    $query = "SELECT *,title as usecase_name FROM usecases_temp WHERE usecase_Id IN(" . $row['usecase_id'] . ")";
//                    echo $query;exit;
                    $usecases_temp_result_set = mysqli_query_func($con, $query);
                    if ($usecases_temp_result_set) {
                        while ($row1 = mysqli_fetch_assoc($usecases_temp_result_set)) {
//                            print_r($row1);
                            if ($row1['edit_main_usecase_id'] > 0) {
                                $update = "UPDATE usecases SET title='" . mysqli_real_escape_string($con, $row1['title']) . "',industry_vertical='" . mysqli_real_escape_string($con, $row1['industry_vertical']) . "',other_vertical='" . mysqli_real_escape_string($con, $row1['other_vertical']) . "',problem_statement='" . mysqli_real_escape_string($con, $row1['problem_statement']) . "',description='" . mysqli_real_escape_string($con, $row1['description']) . "',last_modified_timestamp=NOW() WHERE usecase_Id='" . $row1['edit_main_usecase_id'] . "'";
                                $usecases_update_status = mysqli_query_func($con, $update);
                            } else {
                                $insert = "INSERT INTO usecases(title, industry_vertical, other_vertical, problem_statement,description,Created_timestamp) VALUES ('" . mysqli_real_escape_string($con, $row1['usecase_name']) . "','" . mysqli_real_escape_string($con, $row1['industry_vertical']) . "','" . mysqli_real_escape_string($con, $row1['other_vertical']) . "','" . mysqli_real_escape_string($con, $row1['problem_statement']) . "','" . mysqli_real_escape_string($con, $row1['description']) . "',NOW())";
                                $usecases_insert_status = mysqli_query_func($con, $insert);
                                if ($usecases_insert_status) {
                                    $last_insert_id = mysqli_insert_id($con);
                                    $insert = "INSERT INTO product_usecases(product_id, UseCase_Id) VALUES ('" . $row['product_id'] . "','" . $last_insert_id . "')";
                                    $product_usecases_insert_status = mysqli_query_func($con, $insert);
                                }
                            }
                            $delete1 = "DELETE FROM usecases_temp WHERE usecase_Id='" . $row1['usecase_Id'] . "'";
                            $usecases_temp_delete_status = mysqli_query_func($con, $delete1);
                            $delete2 = "DELETE FROM product_usecases_temp WHERE UseCase_Id='" . $row1['usecase_Id'] . "' and product_id='" . $row['product_id'] . "'";
                            $product_usecases_temp_delete_status = mysqli_query_func($con, $delete2);
                        }
                    }
                }
            }
            if (in_array(13, $types)) {
                $query = "SELECT * FROM vb_company_partners_temp WHERE company_id = '" . $row['company_id'] . "'";
                $retval = mysqli_query_func($con, $query);
                if (mysqli_num_rows($retval) > 0) {
                    $query = "SELECT * FROM vb_company_partners WHERE company_id='" . $row['company_id'] . "'";
                    $res = mysqli_query_func($con, $query);
                    if (mysqli_num_rows($res) > 0) {
                        $delete = "DELETE FROM vb_company_partners WHERE company_id='" . $row['company_id'] . "'";
                        $res = mysqli_query_func($con, $delete);
                    }
                    while ($get_res = mysqli_fetch_array($retval)) {
                        $insert = "INSERT INTO vb_company_partners(company_id, partner_name, partner_logo_path, website_url,created_by, created_date) VALUES ('" . $get_res['company_id'] . "','" . $get_res['partner_name'] . "','" . $get_res['partner_logo_path'] . "','" . $get_res['website_url'] . "','" . $get_res['created_by'] . "',NOW())";
//                        echo $insert."<br>";
                        $data = mysqli_query_func($con, $insert);
                    }
//                    exit;
                    if ($data) {
                        $delete = "DELETE FROM vb_company_partners_temp WHERE company_id = '" . $row['company_id'] . "'";
//                        echo $delete; exit;
                        $data = mysqli_query_func($con, $delete);
                    }
                }
            }
            if (in_array(14, $types)) {
                $query = "SELECT * FROM vb_blog_temp WHERE company_id = '" . $row['company_id'] . "'";
                $retval = mysqli_query_func($con, $query);
                if (mysqli_num_rows($retval) > 0) {
                    $query = "SELECT * FROM vb_blog WHERE company_id='" . $row['company_id'] . "'";
                    $res = mysqli_query_func($con, $query);
                    if (mysqli_num_rows($res) > 0) {
                        $delete = "DELETE FROM vb_blog WHERE company_id='" . $row['company_id'] . "'";
                        $res = mysqli_query_func($con, $delete);
                    }
                    while ($get_res = mysqli_fetch_array($retval)) {
                        $insert = "INSERT INTO vb_blog(company_id, blog_name, blog_description,tags,status,created_by, created_date) VALUES ('" . $get_res['company_id'] . "','" . $get_res['blog_name'] . "','" . mysqli_real_escape_string($con, $get_res['blog_description']) . "','" . $get_res['tags'] . "','" . $get_res['status'] . "','" . $get_res['created_by'] . "',NOW())";
                        $data = mysqli_query_func($con, $insert);
                    }
                    if ($data) {
                        $delete = "DELETE FROM vb_blog_temp WHERE company_id = '" . $row['company_id'] . "'";
                        $data = mysqli_query_func($con, $delete);
                    }
                }
            }

            if (isset($_POST['newd_feed_temp_table_id']) && !empty($_POST['newd_feed_temp_table_id']) && $_POST['newd_feed_temp_table_id'] > 0) {
                $id = $_POST["newd_feed_temp_table_id"];
                $query = "SELECT pnt.*,acap.types 
                    FROM jm_vbridge_product_newsfeed_temp pnt
                    LEFT JOIN jm_vbridge_admin_company_approval_pending acap 
                    ON pnt.company_id=acap.company_id 
                    WHERE pnt.id='$id'";
                $result = mysqli_query_func($con, $query);
                $status = "success";
                if (isset($result) && !empty($result)) {
                    $row = mysqli_fetch_assoc($result);
                    $types = explode(",", $row['types']);

                    if (in_array(11, $types)) {
                        $query = "SELECT * FROM jm_vbridge_product_newsfeed_temp WHERE id='$id'";
                        $data = mysqli_query_func($con, $query);
                        if ($data) {
                            $row = mysqli_fetch_assoc($data);
                            $check_data = "SELECT * FROM jm_vbridge_product_newsfeed WHERE company_id=" . $row['company_id'] . "";
                            $res = mysqli_query_func($con, $check_data);
                            if (mysqli_num_rows($res) > 0) {
                                $update_fields = " description='" . str_replace("'", "&#39;", $row['description']) . "',modified_date=NOW()";
                                $update = "UPDATE jm_vbridge_product_newsfeed SET  " . $update_fields . " WHERE company_id='" . $row['company_id'] . "'";
                                $data = mysqli_query_func($con, $update);
                            } else {
                                $insert = "INSERT INTO jm_vbridge_product_newsfeed(company_id, description, created_by, created_date) VALUES (" . $row['company_id'] . ",'" . str_replace("'", "&#39;", $row['description']) . "',1,NOW())";
                                $data = mysqli_query_func($con, $insert);
                            }
                            if ($data) {
                                $query = "DELETE FROM jm_vbridge_product_newsfeed_temp WHERE id='$id'";
                                $data = mysqli_query_func($con, $query);
                            }
                        }
                    }
                }
            }

            $remove_approval = "DELETE FROM jm_vbridge_admin_company_approval_pending WHERE  id=" . $_POST['request_company_table_id'];
            $result = mysqli_query_func($con, $remove_approval);
            $status = "success";


            $remove_approval = "DELETE FROM jm_vbridge_admin_products_approval_pending WHERE  company_id ='" . $company_id_main . "'";
            $result = mysqli_query_func($con, $remove_approval);
            $status = "success";
        }
    }
    echo $status;
}

if ($type == 'reject_company_data') {
    $status = "success";
    if (isset($_POST['request_company_table_id']) && !empty($_POST['request_company_table_id'])) {
        $query = "SELECT * FROM jm_vbridge_admin_company_approval_pending WHERE id='" . $_POST['request_company_table_id'] . "'";
        $result = mysqli_query_func($con, $query);
        if (isset($result) && !empty($result)) {
            $row = mysqli_fetch_assoc($result);
            $types = explode(",", $row['types']);
            if (in_array(1, $types) || in_array(2, $types)) {
                $remove_company = "DELETE FROM company_info_temp WHERE temp_id=" . $row['company_id'];
                $result = mysqli_query_func($con, $remove_company);
                if ($result) {
                    $status = "success";
                } else {
                    $status = "fail";
                }
            }
            if (in_array(3, $types)) {
                $query = "DELETE FROM funding_information_temp WHERE company_id = '" . $row['company_id'] . "'";
                $retval = mysqli_query_func($con, $query);
                if ($retval) {
                    $status = "success";
                } else {
                    $status = "fail";
                }
            }
            if (in_array(4, $types)) {
                $company_id_pro = $row['company_id'];
                $query = "SELECT * FROM jm_vbridge_admin_products_approval_pending WHERE company_id='" . $row['company_id'] . "'";
                $result_pro = mysqli_query_func($con, $query);
                if (mysqli_num_rows($result_pro) > 0) {
                    while ($row_pro = mysqli_fetch_array($result_pro)) {
                        $query = "DELETE FROM products_temp WHERE product_id='" . $row_pro['product_id'] . "'";
                        $data = mysqli_query_func($con, $query);
                        $query = "DELETE FROM group_categories_temp WHERE product_id='" . $row_pro['product_id'] . "'";
                        $data = mysqli_query_func($con, $query);
                        $query = "DELETE FROM product_categories_temp WHERE Product_Id='" . $row_pro['product_id'] . "'";
                        $data = mysqli_query_func($con, $query);
                        $query = "DELETE FROM jm_vbridge_admin_products_approval_pending WHERE product_id='" . $row_pro['product_id'] . "'";
                        $data = mysqli_query_func($con, $query);
                    }
                }
            }
            if (in_array(5, $types)) {
                $query = "DELETE FROM jm_vbridge_product_videos_temp WHERE company_id='" . $row['company_id'] . "'";
                $data = mysqli_query_func($con, $query);
            }
            if (in_array(6, $types)) {
                $query = "DELETE FROM jm_vbridge_product_briefs_paper_temp WHERE company_id='" . $row['company_id'] . "'";
                $data = mysqli_query_func($con, $query);
            }
            if (in_array(7, $types)) {
                $query = "DELETE FROM jm_vbridge_product_case_studies_temp WHERE company_id='" . $row['company_id'] . "'";
                $data = mysqli_query_func($con, $query);
            }
            if (in_array(8, $types)) {
                $query = "DELETE FROM jm_vbridge_product_testimonials_temp WHERE company_id = '" . $row['company_id'] . "'";
                $retval = mysqli_query_func($con, $query);
                if ($retval) {
                    $status = "success";
                } else {
                    $status = "fail";
                }
            }
            if (in_array(9, $types)) {
                $query = "DELETE FROM jm_vbridge_product_customers_temp WHERE company_id = '" . $row['company_id'] . "'";
                $retval = mysqli_query_func($con, $query);
                if ($retval) {
                    $status = "success";
                } else {
                    $status = "fail";
                }
            }
            if (in_array(10, $types)) {
                $query = "DELETE FROM jm_vbridge_product_awards_temp WHERE company_id = '" . $row['company_id'] . "'";
                $retval = mysqli_query_func($con, $query);
                if ($retval) {
                    $status = "success";
                } else {
                    $status = "fail";
                }
            }
            if (in_array(12, $types)) {
                $query = "SELECT product_id,GROUP_CONCAT(DISTINCT(UseCase_Id)) as usecase_id FROM product_usecases_temp where product_id='" . $_POST['temp_table_product_id'] . "' group by Product_Id";
                $data = mysqli_query_func($con, $query);
                if ($data) {
                    $row = mysqli_fetch_assoc($data);
                    $query = "DELETE FROM usecases_temp WHERE usecase_Id in(" . $row['usecase_id'] . ")";
                    $data = mysqli_query_func($con, $query);
                    $query = "DELETE FROM product_usecases_temp WHERE product_id='" . $_POST['temp_table_product_id'] . "'";
                    $data = mysqli_query_func($con, $query);
                }
            }
            if (in_array(13, $types)) {
                $query = "DELETE FROM patent_information_temp WHERE company_id = '" . $row['company_id'] . "'";
                $retval = mysqli_query_func($con, $query);
                if ($retval) {
                    $status = "success";
                } else {
                    $status = "fail";
                }
            }

            if (isset($_POST['awards_temp_table_id']) && !empty($_POST['awards_temp_table_id']) && $_POST['awards_temp_table_id'] > 0) {
                $remove_company = "DELETE FROM jm_vbridge_product_awards_temp WHERE id=" . $_POST['awards_temp_table_id'];
                $result = mysqli_query_func($con, $remove_company);
                if ($result) {
                    $status = "success";
                } else {
                    $status = "fail";
                }
            }
            if (isset($_POST['newd_feed_temp_table_id']) && !empty($_POST['newd_feed_temp_table_id']) && $_POST['newd_feed_temp_table_id'] > 0) {
                $remove_company = "DELETE FROM jm_vbridge_product_newsfeed_temp WHERE id=" . $_POST['newd_feed_temp_table_id'];
                $result = mysqli_query_func($con, $remove_company);
                if ($result) {
                    $status = "success";
                } else {
                    $status = "fail";
                }
            }

            $remove_approval = "DELETE FROM jm_vbridge_admin_company_approval_pending WHERE id=" . $_POST['request_company_table_id'];
            $result = mysqli_query_func($con, $remove_approval);

            $remove_approval = "DELETE FROM jm_vbridge_admin_products_approval_pending WHERE company_id ='" . $row['company_id'] . "'";
            $result = mysqli_query_func($con, $remove_approval);
            $status = "success";
        }
    }
    echo $status;
}
//manikanta Code starts

if ($type == 'add_message_vbridge_hub') {
// echo "<pre>";print_r($_POST);exit;
    $status = array();
    $user_id = $_POST['user_id'];
    $all_or_selected = $_POST["demo"];
    $message = mysqli_real_escape_string($con, $_POST["message"]);
    $from_date_and_time = $_POST["from_date_and_time"];
    $to_date_time = $_POST["to_date_time"];
    $selectedvalues = $_POST["selectedvalues"];
    $selected = explode(",", $selectedvalues);
//    echo "<pre>";print_r($selected);exit;
//    $all_or_selected = $_POST["demo"];
    $query = "INSERT INTO vb_message(display_to, message, from_date, to_date, status, creadted_by, created_date)"
            . "VALUES ('$all_or_selected','$message','$from_date_and_time','$to_date_time','1','$user_id',NOW())";
    $result = mysqli_query_func($con, $query);
    $last_insert_id = mysqli_insert_id($con);
    if ($result) {
        $status['status'] = 'success';
    } else {
        $status['status'] = 'fail';
    }
    if ($all_or_selected == '2') {
        foreach ($selected as $k => $data) {
//                echo "<pre>";print_r($data);exit;
            $company_id = $data;
            $query1 = "INSERT INTO vb_message_display_to( vb_message_id, dsiplay_to_company_id, creadted_by, created_date) "
                    . "VALUES ('$last_insert_id','$company_id','$user_id',NOW())";
            $result = mysqli_query_func($con, $query1);
            if ($result) {
                $status['status'] = 'success';
            } else {
                $status['status'] = 'fail';
            }
        }
    }
    echo json_encode($status, 1);
}
if ($type == 'edit_message_vbridge_hub') {
//    echo "<pre>";
//    print_r($_POST);
//    exit;

    $status = array();
    $user_id = $_POST['user_id'];
    $vb_mssage_id = $_POST['vb_mssage_id'];
    $display_id = $_POST['display_id'];
//    $all_or_selected = $_POST["demo"];
    $message = mysqli_real_escape_string($con, $_POST["message"]);
    $from_date_and_time = $_POST["from_date_and_time"];
    $to_date_time = $_POST["to_date_time"];
    $selectedvalues = $_POST["selectedvalues"];
    $selected = explode(",", $selectedvalues);
    $unselectedvalues = $_POST["unselectedvalues"];
    $unselectedvalues = explode(",", $unselectedvalues);
//     echo "<pre>";print_r($selected);
    if ($display_id == '1') {
        if (isset($vb_mssage_id) && !empty($vb_mssage_id)) {
            $delete = "DELETE FROM vb_message_display_to where vb_message_id='$vb_mssage_id'";
            $deleteresult = mysqli_query_func($con, $delete);
            $updatemessage = "UPDATE vb_message SET display_to='1',message='$message',from_date='$from_date_and_time',to_date='$to_date_time' WHERE id='$vb_mssage_id'";
            $result = mysqli_query_func($con, $updatemessage);
            if ($result) {
                $status['status'] = 'success';
            } else {
                $status['status'] = 'fail';
            }
        } else {
            $update = "UPDATE vb_message SET message='$message',from_date='$from_date_and_time',to_date='$to_date_time',modified_date='NOW()' WHERE id='$vb_mssage_id'";

            $result = mysqli_query_func($con, $update);
            $last_insert_id = mysqli_insert_id($con);
            if ($result) {
                $status['status'] = 'success';
            } else {
                $status['status'] = 'fail';
            }
        }
    }
    if ($display_id == '2') {
        if (isset($vb_mssage_id) && !empty($vb_mssage_id)) {
            $updatemessage = "UPDATE vb_message SET display_to='2',message='$message'  WHERE id='$vb_mssage_id'";
            $result = mysqli_query_func($con, $updatemessage);
            if (isset($vb_mssage_id) && !empty($vb_mssage_id)) {

//             echo "<pre>";print_r($selected);
                foreach ($selected as $k => $data) {
                    $company_id = $data;
//                echo $data;exit;
                    $delete = "DELETE FROM vb_message_display_to WHERE dsiplay_to_company_id='" . $company_id . "' AND vb_message_id='" . $vb_mssage_id . "'";
                    $result5 = mysqli_query_func($con, $delete);
                    $query1 = "INSERT INTO vb_message_display_to( vb_message_id, dsiplay_to_company_id, creadted_by, created_date) "
                            . "VALUES ('$vb_mssage_id','$company_id','$user_id',NOW())";
                    $result = mysqli_query_func($con, $query1);
                    if ($result) {
                        $status['status'] = 'success';
                    } else {
                        $status['status'] = 'fail';
                    }
                }
            }
        }

//        foreach ($selected as $k => $data) {
//            $company_id = $data;
//            //echo $company_id;
//           $select = "SELECT * FROM vb_message_display_to where dsiplay_to_company_id='$company_id' AND vb_message_id='".$vb_mssage_id."'";
//            $result1 = mysqli_query_func($con, $select);
//            if (mysqli_num_rows($result1) > 0) {
//                $update = "UPDATE vb_message_display_to SET dsiplay_to_company_id='$company_id',modified_date=NOW() WHERE dsiplay_to_company_id='$company_id'";
//                $result2 = mysqli_query_func($con, $update);
//                
//                if ($result2) {
//                    $update1 = "UPDATE vb_message SET message='$message',from_date='$from_date_and_time',to_time='$to_date_time',modified_date=NOW() WHERE id='$vb_mssage_id'";
//                    $result3 = mysqli_query_func($con, $update1);
//                }
//            } else {
//                $query1 = "INSERT INTO vb_message_display_to( vb_message_id, dsiplay_to_company_id, creadted_by, created_date) VALUES ('$vb_mssage_id','$company_id','$user_id',NOW())";
//                $result3 = mysqli_query_func($con, $query1);
//            }
//        }
        foreach ($unselectedvalues as $k => $data1) {
            $unselected_company_id = $data1;
            $select = "SELECT * FROM vb_message_display_to where dsiplay_to_company_id='$company_id' AND vb_message_id='" . $vb_mssage_id . "'";
            $result4 = mysqli_query_func($con, $select);
            if (mysqli_num_rows($result4) > 0) {
                $delete = "DELETE FROM vb_message_display_to WHERE dsiplay_to_company_id='" . $unselected_company_id . "'";
                $result5 = mysqli_query_func($con, $delete);
            }
        }

        if ($result5) {
            $status['status'] = 'success';
        } else {
            $status['status'] = 'fail';
        }
    }
    echo json_encode($status, 1);
}
if ($type == 'activation_status') {
//       echo "<pre>";
//      print_r($_POST);
//      exit;
    $status = array();
    $action_id = $_POST['action_id'];
    $vid = $_POST['vid'];
    if ($action_id == '1') {
        $update = "UPDATE vb_message SET status='0' WHERE id='$vid'";
    } else {
        $update = "UPDATE vb_message SET status='1' WHERE id='$vid'";
    }
    $result = mysqli_query_func($con, $update);
    if ($result) {
        $status['status'] = 'success';
    } else {
        $status['status'] = 'fail';
    }
    echo json_encode($status, 1);
}
if ($type == 'check_company_name') {
//      echo "<pre>";print_r($_POST);echo "</pre>";exit;
    $catdata = explode(',', $_POST['catdata']);
    $indvertdata = $_POST['indvertdata'];
    $countries = $_POST['countries'];
//       echo "<pre>";print_r($indvertdata);echo "</pre>";exit;

    foreach ($catdata as $category_str) {

        // foreach ($category as $category_str){
        if (strpos($category_str, '-')) {
            $catararray = explode('-', $category_str);
//                $catararray=trim($catararray);
            //echo "<pre>";print_r($catararray);echo "</pre>";
            $query = "SELECT * FROM group_categories where Category_Name='" . trim($catararray[0]) . "' AND SubCategory_Name='" . $catararray[1] . "'";
            $retval = mysqli_query_func($con, $query);
            if (mysqli_num_rows($retval) > 0) {
                $row = mysqli_fetch_assoc($retval);
                $group_category_id[] = $row['Group_Category_Id'];
            }
        }
    }
    if (isset($group_category_id) && !empty($group_category_id)) {
        $gcat = implode(",", $group_category_id);
    }
//    echo "<pre>";
//    print_r($gcat);
//    echo "</pre>";exit;
//    $select = "SELECT ci.company_name FROM `company_info` ci 
//            LEFT JOIN products p ON p.Company_Id=ci.company_id 
//            LEFT JOIN product_categories pc on pc.Product_Id = p.product_id 
//            LEFT JOIN group_categories gc on gc.Group_Category_Id = pc.Group_Category_Id 
//            LEFT JOIN jm_vbridge_user_industry_vertical jiv ON FIND_IN_SET(jiv.industry_verticals_id,$indvertdata)
//            LEFT JOIN vb_countries vb on vb.id=ci.country WHERE";
//    if(isset($group_category_id)){
//        $select .=" gc.Group_Category_Id = '" . $group_category_id . "'";
//    }else if(isset($indvertdata)){
//       $select .=" p.industry_vertical='" . $indvertdata . "'";
//    }else if(isset($countries)){
//        $select .=" vb.id='".$countries."'";
    $select = "SELECT ci.company_id,ci.company_name FROM `company_info` ci 
                LEFT JOIN products p ON p.Company_Id=ci.company_id 
                LEFT JOIN product_categories pc on pc.Product_Id = p.product_id
                LEFT JOIN group_categories gc on gc.Group_Category_Id = pc.Group_Category_Id
                LEFT JOIN jm_vbridge_user_industry_vertical jiv on jiv.industry_verticals_id=p.industry_vertical
                LEFT JOIN vb_countries vb on vb.id=ci.country where ci.company_id>'0'";
    if (isset($group_category_id) && !empty($group_category_id)) {
        $select .= " and gc.Group_Category_Id IN ($gcat)";
    }
    if (isset($indvertdata) && !empty($indvertdata)) {
        $select .= " and jiv.industry_verticals_id IN ($indvertdata)";
    }if (isset($countries) && !empty($countries)) {
        $select .= " and vb.id='" . $countries . "'";
    }
    $select .= " group by ci.company_name";
    $res = mysqli_query_func($con, $select);
    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $data[] = $row;
        }
    } else {
        $data = array();
    }
//    print_r($data);
    echo json_encode($data);
}

function get_all_companys($con) {
    $data = array();
    $query = "SELECT company_info.company_name as company_name,company_info.company_id as company_id FROM company_info";
    $res = mysqli_query_func($con, $query);
    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $data[] = $row;
        }
    } else {
        $data = array();
    }
    return $data;
}

function get_unselected_companies_data($con, $ids) {
//   print_r($ids);exit;
    $data = array();
    $query = "SELECT company_name,company_id FROM company_info WHERE company_id NOT IN($ids)";
    $res = mysqli_query_func($con, $query);
    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $data[] = $row;
        }
    } else {
        $data = array();
    }
    return $data;
}

function get_all_message_data($con, $user_id) {
    $data = array();
    $query = "SELECT vm.message,vm.from_date as fromdate,vm.to_date as todate,vm.status as status,vm.display_to as display_id,vm.id as id
                FROM vb_message vm group by vm.id";

    $query2 = "SELECT dto.vb_message_id,GROUP_CONCAT(ci.company_name SEPARATOR ',') as company_name FROM vb_message_display_to dto
                LEFT JOIN vb_message vm2 ON vm2.id=dto.vb_message_id 
                LEFT JOIN company_info ci ON ci.company_id=dto.dsiplay_to_company_id group by dto.vb_message_id";

    $query3 = "SELECT q1.*,(CASE 
                WHEN q1.display_id = '1' 
                THEN 'All' 
                ELSE q2.company_name END) as company_name FROM ($query) q1 LEFT JOIN ($query2) q2 ON q2.vb_message_id=q1.id group by q1.id";
    //echo $query3;
    $res = mysqli_query_func($con, $query3);
    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $data[] = $row;
        }
    } else {
        $data = array();
    }
//    echo"<pre>"; print_r($data); echo"</pre>"; exit;
    return $data;
}

function get_edit_companys_data($con, $id) {
    $data = array();
    $query = "SELECT vm.id,vm.message,vm.from_date as fromdate,vm.to_date as todate,vm.display_to as display_id,vm.status as status,vm.id as id,IFNULL(GROUP_CONCAT(dto.dsiplay_to_company_id),'') as company_id, IFNULL(GROUP_CONCAT(ci.company_name),'') as company_name
                FROM vb_message vm
                LEFT JOIN vb_message_display_to dto ON dto.vb_message_id=vm.id
                LEFT JOIN company_info ci ON ci.company_id=dto.dsiplay_to_company_id
                where vm.id='$id'";
//       echo $query; exit;
    $res = mysqli_query_func($con, $query);
//         echo $res; exit;
    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $data = $row;
        }
    } else {
        $data = array();
    }
    return $data;
}

//industry veriticals for message from vbridge hub
function get_data_industry_verticals($con) {
    $data = array();
    $select = "SELECT * FROM vb_industry_verticals";
    $res = mysqli_query_func($con, $select);
    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            $data[] = $row;
        }
    }
    return $data;
}

function get_date_categories($con) {
//        $service_url = $this->settings;
//        $url = $service_url[0]['services_url'] . '/api/Query/listCategories';
////        echo $url; exit;
//        //return array();
//        $ch = curl_init($url);
//
//        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
//        curl_setopt($ch, CURLOPT_POSTREDIR, 3);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // follow http 3xx redirects
//        $resp_orders = curl_exec($ch); // execute
//        $category_data = json_decode($resp_orders, 1);
//        echo"<pre>"; print_r($category_data);exit;
    $category_data = get_data_all_categories($con);
    $return = array();
    $temp = array();
    /*
     * New Code
     */
    $i = 0;
    if (isset($category_data) && !empty($category_data)) {
        foreach ($category_data as $key => $cat) {
            if (in_array($cat['category'], $temp)) {
                $return[$cat['domain']][$i]['category'] = $cat['category'];
                $return[$cat['domain']][$i]['subCategory'][] = $cat['subCategory'];
            } else {
                $temp[] = $cat['category'];
                $i++;
                $return[$cat['domain']][$i]['category'] = $cat['category'];
                $return[$cat['domain']][$i]['subCategory'][] = $cat['subCategory'];
            }
        }
    }
    ksort($return);
    /*
     * New Code
     */
    //echo"<pre>"; print_r($return);exit;
    return $return;
}

function get_data_all_categories($con) {
    $data = array();
    $sql_category = "SELECT Group_Category_Id as groupCategoryId, Category_Name as category, SubCategory_Name as subCategory, domain as domain, Segment as Segment  FROM group_categories";
//        echo $sql_category; exit;
    $res = mysqli_query_func($con, $sql_category);
    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            $data[] = $row;
        }
    }
    return $data;
}

function get_data_countries($con) {
    $data = array();
    $query = "select vb.name,vb.id from company_info ci
                  LEFT JOIN vb_countries vb on ci.country=vb.id where ci.country !='' group by ci.country order by vb.name ASC";
    $res = mysqli_query_func($con, $query);
    if (mysqli_num_rows($res) > 0) {
        while ($row = mysqli_fetch_assoc($res)) {
            $data[] = $row;
        }
    }
    return $data;
}

if ($type == 'company_users_registered') {
    $rootpath = $_REQUEST['rootpath'];
    $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'jm_users.id';
    $order = isset($_POST['order']) ? strval($_POST['order']) : 'DESC';
    $get_user_data = "SELECT  jm_users.id,jm_users.company_id,jm_users.username,jm_users.email,CONCAT(jm_users.isd_code,' ',jm_users.phone_no) as contactno,jm_users.activation,jm_users.companyName,jm_users.companyURL,jm_users.registerDate,jm_user_usergroup_map.group_id FROM jm_users LEFT JOIN jm_user_usergroup_map ON jm_users.id = jm_user_usergroup_map.user_id where jm_user_usergroup_map.group_id<>8 and jm_users.email  NOT LIKE 'demo@%' order by $sort $order";
    $result = mysqli_query_func($con, $get_user_data);
    if (isset($result) && !empty($result)) {
        $array = array();
        while ($row = mysqli_fetch_array($result)) {
//            echo "<pre>";print_r($row);
            $encode_id = base64_encode('0');
            $encode_company_id = base64_encode($row['company_id']);
            if ($row['group_id'] == 1) {
                $row['registered_as'] = 'Startup';
            } elseif ($row['group_id'] == 2) {
                $row['registered_as'] = 'System Integrator';
            } elseif ($row['group_id'] == 3) {
                $row['registered_as'] = 'Enterprise';
            } else {
                $row['registered_as'] = 'Investor';
            }
            $row['username'] = $row['username'];
            $row['email'] = $row['email'];
            $row['companyName'] = "<a href='" . $rootpath . "index.php/en/companydetails?id=" . $encode_id . "&company_id=" . $encode_company_id . "' target='_blank'>" . $row['companyName'] . "</a>";
            $row['companyURL'] = $row['companyURL'];
            $row['registerDate'] = date('Y-M-d', strtotime(str_replace('-', '/', $row['registerDate'])));
            // $row['registerDate'] = date('d-F-Y',$row['registerDate']);
            if ($row['activation'] == 1 || $row['activation'] == 3) {
                $row['app_status'] = "System Approved";
            } else if ($row['activation'] == 2) {
                $row['app_status'] = "Rejected";
            } else {
                $row['app_status'] = "<a href='javascript:void(0);' id='btn-edit' onclick='approveAction(" . $row['id'] . ")' >
                                  <span class='glyphicon glyphicon-pencil'></span>Approve</a> | 
                                  <a href='javascript:void(0);' onclick='rejectAction(" . $row['id'] . ")' ><span class='glyphicon glyphicon-trash'></span>Reject</a>";
            }

            array_push($array, $row);
        }

        $data['data'] = $array;
        echo json_encode($data);
        exit;
    } else {
        echo "Something Went Wrong";
    }
}

//End
//manikanta Code Ends
?>
    