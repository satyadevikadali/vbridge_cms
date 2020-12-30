<?php 
error_reporting(-1);
// Database configuration 
$dbHost     = "localhost:3306"; 
$dbUsername = "Vbridge_portal"; 
$dbPassword = "Khip559@"; 
$dbName     = "Vbridge_portal"; 
 
 //$con = mysqli_connect("localhost:3306", "Vbridge_portal", "Khip559@", "Vbridge_portal");
// Create database connection 
$db = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName); 
 
// Check connection 
if ($db->connect_error) { 
    die("Connection failed: " . $db->connect_error); 
}

?>
