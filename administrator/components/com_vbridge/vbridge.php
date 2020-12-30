<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Vbridge
 * @author     John Doe <leaders.noreply@gmail.com>
 * @copyright  
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_vbridge'))
{
	throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
}

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('Vbridge', JPATH_COMPONENT_ADMINISTRATOR);
JLoader::register('VbridgeHelper', JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'vbridge.php');

$controller = JControllerLegacy::getInstance('Vbridge');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
?>
