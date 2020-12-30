<?php

class CommonModel {

    var $con;
    var $settings;

    public function __construct() {
        defined('JPATH_BASE') OR define('JPATH_BASE', realpath(dirname(__FILE__) . '/../../..'));
        $str = str_replace('\\', '/', JPATH_ROOT);
        require ($str . '/db_config.php');
        // defined('JPATH_BASE') OR define('JPATH_BASE', realpath(dirname(__FILE__) . '/..'));
        // $path = explode('\\', JPATH_BASE);
        // $removed = array_pop($path);
        // $abspath = $path[0] . '/' . $path[1] . '/' . $path[2] . '/' . $path[3];
        // require ($abspath . '/db_config.php');
        $this->con = $con;
    }

    public function get_user_registration_status_data($condition = "") {
        $data = array();
        $get_user_data = "SELECT DISTINCT(ci.company_id),ci.company_name,jum.group_id,ju.username,ju.id,ju.name,ju.email,ju.registerDate,ju.activation,
                        ju.isd_code,ju.phone_no,ju.created_by,
                        (SELECT COUNT(vlhis.user_id) FROM vb_login_history vlhis WHERE vlhis.user_id = ju.id) as login_count
                        FROM company_info ci
                        LEFT JOIN jm_users ju ON ju.company_id=ci.company_id
                        LEFT JOIN jm_user_usergroup_map jum ON ju.id = jum.user_id
                        LEFT JOIN vb_login_history vlh ON vlh.id=ju.id ";
                        // echo $get_user_data; exit;

        if ($condition == "") {
            $get_user_data .= " order by ju.registerDate DESC";
        } else {
            $get_user_data .= $condition;
        }
//        echo $get_user_data; exit;
        $res = mysqli_query($this->con, $get_user_data);
        if ($res) {
            while ($row = mysqli_fetch_assoc($res)) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function get_user_request_status_data($condition = "") {
        $data = array();
        $get_request_data = "SELECT vgar.created_date as request_created_date,
                             (case when jumfrom.group_id='1' then 'Startup' when jumfrom.group_id='2' then 'ITSP' when jumfrom.group_id='3' then 'Enterprise' else 'Enterprise' end) as from_entity,
                             cifrom.company_name as from_organization,jufrom.username as from_username,
                             (case when jumto.group_id='1' then 'Startup' when jumto.group_id='2' then 'ITSP' when jumto.group_id='3' then 'Enterprise' else 'Enterprise' end) as to_entity,
                             cito.company_name as to_organization,juto.username as to_username,vgat.type as request_raised,IF(vgar.latest_action_status='0','No','Yes') as responsereceived,
                        (SELECT COUNT(vlhis.user_id) FROM vb_login_history vlhis WHERE vlhis.user_id = juto.id) as login_count
                        FROM vb_gsi_requests_participents vgrp
                        LEFT JOIN vb_gsi_action_requests vgar ON vgar.id=vgrp.vb_gsi_action_requests_id
                        LEFT JOIN jm_users jufrom ON jufrom.id=vgrp.from_id
                        LEFT JOIN jm_user_usergroup_map jumfrom ON jumfrom.user_id=jufrom.id
                        LEFT JOIN company_info cifrom ON cifrom.company_id=vgrp.from_company_id
                        LEFT JOIN jm_users juto ON juto.id=vgrp.to_id
                        LEFT JOIN jm_user_usergroup_map jumto ON jumto.user_id=juto.id
                        LEFT JOIN company_info cito ON cito.company_id=vgrp.to_company_id
                        LEFT JOIN vb_gsi_action_types vgat ON vgat.id=vgrp.gsi_action_type";
        if ($condition == "") {
            $get_request_data .= " order by vgar.created_date DESC";
        } else {
            $get_request_data .= $condition;
        }
    //    echo $get_request_data; exit;
        $res = mysqli_query($this->con, $get_request_data);
        if ($res) {
            while ($row = mysqli_fetch_assoc($res)) {
                $data[] = $row;
            }
        }
        return $data;
    }

}

?>

