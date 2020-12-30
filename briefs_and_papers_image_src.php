<?php
define('_JEXEC', 1);

define('JPATH_BASE', realpath(dirname(__FILE__)));
// echo JPATH_BASE; exit;
require_once ( JPATH_BASE . '/includes/defines.php' );
require_once ( JPATH_BASE . '/includes/framework.php' );
$mainframe = JFactory::getApplication('site');
$mainframe->initialise();
$session = JFactory::getSession(); 
$rootpath = JUri::root(); 
$sessionData = $session->get('userdata');   
if(isset($sessionData) && !empty ($sessionData)) {  
    $rootpath = $sessionData['rootpath'];   
}
if(isset($sessionData) && !empty ($sessionData)) {  
    $path=$_REQUEST['path'];
    $full_path="briefs_and_papers/".$path;
    $fp = fopen($full_path, 'r');
    // Set mime type to header
    header('Content-type: '.mime_content_type($full_path));
    // Send the contents of the file the browser
    fpassthru($fp);
} else {
	echo "Dont' have permissions to access this document";	
}
?> 