<?php
/**
 * ExtraSearch Joomla! 3.x Native Component
 * @version 2.5.7
 * @author JoomlaBoat.com <support@joomlaboat.com>
 * @link http://www.joomlaboat.com
 * @license GNU/GPL
 **/


// no direct access
defined('_JEXEC') or die('Restricted access');

// Import Joomla! libraries
jimport( 'joomla.application.component.view');

class CustomTablesViewImportTables extends JViewLegacy
{
    var $catalogview;
    
    function display($tpl = null)
    {
		
		JToolBarHelper::title(   JText::_( 'Custom Tables - Import Tables', 'generic.png' ));//
		
		parent::display($tpl);
		
	}
	
	function generateRandomString($length = 32)
	{
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++)
		{
		    $randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
}
		
?>
