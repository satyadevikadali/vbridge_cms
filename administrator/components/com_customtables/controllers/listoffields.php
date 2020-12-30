<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				JoomlaBoat.com 
/-------------------------------------------------------------------------------------------------------/

	@version		1.0.76
	@build			1st July, 2018
	@created		24th May, 2018
	@package		Custom Tables
	@subpackage		listoffields.php
	@author			Ivan Komlev <https://joomlaboat.com>	
	@copyright		Copyright (C) 2018. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
/------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');

/**
 * Listoffields Controller
 */
class CustomtablesControllerListoffields extends JControllerAdmin
{
	protected $text_prefix = 'COM_CUSTOMTABLES_LISTOFFIELDS';
	/**
	 * Proxy for getModel.
	 * @since	2.5
	 */
	public function getModel($name = 'Fields', $prefix = 'CustomtablesModel', $config = array())
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		
		return $model;
	}  
}
