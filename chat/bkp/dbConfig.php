<?php 
// Database configuration 
$dbHost     = "localhost"; 
$dbUsername = "Vbridge_stg"; 
$dbPassword = "Khip559@"; 
$dbName     = "Vbridge_stg"; 
 
// Create database connection 
$db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName); 
 
// Check connection 
if ($db->connect_error) { 
    die("Connection failed: " . $db->connect_error); 
}