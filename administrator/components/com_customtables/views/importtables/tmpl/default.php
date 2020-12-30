<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				JoomlaBoat.com
/-------------------------------------------------------------------------------------------------------/

	@version		1.0.76
	@build			19th July, 2018
	@created		24th May, 2018
	@package		Custom Tables
	@subpackage		default.php
	@author			Ivan Komlev <https://joomlaboat.com>
	@copyright		Copyright (C) 2018. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
/------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');

// load tooltip behavior
JHtml::_('behavior.tooltip');

$document = JFactory::getDocument();

$document->addCustomTag('<link href="'.JURI::root(true).'/components/com_customtables/css/uploadfile.css" rel="stylesheet">');
$document->addCustomTag('<script src="'.JURI::root(true).'/components/com_customtables/js/jquery.uploadfile.min.js"></script>');
$document->addCustomTag('<script src="'.JURI::root(true).'/components/com_customtables/js/uploader.js"></script>');


    $fileid=$this->generateRandomString();
	//echo $fileid;

	echo '<form method="post" action="" id="esFileUploaderForm_Tables">';
	echo '<h2>Import Tables</h2>';

	echo '<p>This may import Table Structure from .txt (json encoded) file.</p>';

	echo '

	<div id="fileuploader"></div>
	<div id="eventsmessage"></div>

	<script>
		var urlstr="'.JURI::root(true).'/administrator/index.php?option=com_customtables&view=fileuploader&tmpl=component&fileid='.$fileid.'";
		ct_getUploader(0,urlstr,1000000,"txt html","esFileUploaderForm_Tables",true,"fileuploader","eventsmessage","'.$fileid.'","filetosubmit",null);

	</script>
    <ul style="list-style: none;">
        <li><input type="checkbox" name="importfields" value="1" checked="checked" /> Import Table Fields</li>
        <li><input type="checkbox" name="importlayouts" value="1" checked="checked" /> Import Layouts</li>
        <li><input type="checkbox" name="importmenu" value="1" checked="checked" /> Import Menu</li>

    </ul>

    <input type="hidden" id="filetosubmit" name="filetosubmit" value="" checked="checked" />
	<input type="hidden" name="fileid" value="'.$fileid.'" />
	<input type="hidden" name="option" value="com_customtables" />
	<!--<input type="hidden" name="controller" value="importtables" />-->
	<input type="hidden" name="task" value="importtables.importtables" />
    '.JHtml::_('form.token').'
	</form>
	';

?>
