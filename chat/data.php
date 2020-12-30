<?php  

$url=$_SERVER['SERVER_NAME'];//JPATH_BASE . DS;
 if($url=='vbridgehub.com'){
	$basePath="https://".$url.'/portal/';
 }else{
	$basePath="https://".$url."/";
 }

$receiver_id=$_GET['receiver_id'];
$url_connections =$basePath."user_connections_api.php?userid=".$receiver_id;
$ch_connections = curl_init();
curl_setopt($ch_connections, CURLOPT_URL, $url_connections);
curl_setopt($ch_connections, CURLOPT_HEADER, 0);
curl_setopt($ch_connections, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch_connections, CURLOPT_SSL_VERIFYHOST, false); 
curl_setopt($ch_connections, CURLOPT_SSL_VERIFYPEER, false); 
$result_connections = curl_exec($ch_connections); // TODO - UNCOMMENT IN PRODUCTION 
curl_close ($ch_connections); 
$vendorslist1=json_decode($result_connections,true); 
$apiVendorList=$vendorslist1['data']; 
echo json_encode($apiVendorList);

exit;

?>