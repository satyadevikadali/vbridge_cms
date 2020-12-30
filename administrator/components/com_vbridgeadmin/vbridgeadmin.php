<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Vbridgeadmin
 * @author     VINYAK ACHALKAR <vinayakachalkar007@gmail.com>
 * @copyright  2019 VINYAK ACHALKAR
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_vbridgeadmin'))
{
	throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
}

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('Vbridgeadmin', JPATH_COMPONENT_ADMINISTRATOR);
JLoader::register('VbridgeadminHelper', JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'vbridgeadmin.php');

$controller = JControllerLegacy::getInstance('Vbridgeadmin');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
