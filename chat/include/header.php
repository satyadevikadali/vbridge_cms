<?php
//print_r($sessionData);
//$sessionData['id'];
/*session_start();
$_SESSION['username']=$sessionData['username'];
$_SESSION['user_profile_pic']=$sessionData['user_profile_pic'];*/

$url=$_SERVER['SERVER_NAME'];//JPATH_BASE . DS;
 if($url=='vbridgehub.com'){
  $basePath="https://".$url.'/portal/';
 }else{
  $basePath="https://".$url."/";
 }

 ?> 
 <!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admin | Dashboard</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?php echo $basePath;?>chat/public/components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo $basePath;?>chat/public/components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo $basePath;?>chat/public/components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo $basePath;?>chat/public/dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="<?php echo $basePath;?>chat/public/dist/css/AdminLTE.css">
  
  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  
  <link rel="stylesheet" href="<?php echo $basePath;?>chat/public/dist/css/skins/_all-skins.min.css">
  <link rel="stylesheet" href="<?php echo $basePath;?>chat/public/plugins/pace/pace.min.css">
  
  <!-- Auto Suggest -->
  
  
  <!-- <link href="/portal/chat/public/lib/css/emoji.css" rel="stylesheet"> -->


