<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Vbridgeadminapprovals
 * @author     VINYAK ACHALKAR <vinayakachalkar007@gmail.com>
 * @copyright  2019 VINYAK ACHALKAR
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_vbridgeadminapprovals'))
{
	throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
}

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('Vbridgeadminapprovals', JPATH_COMPONENT_ADMINISTRATOR);
JLoader::register('VbridgeadminapprovalsHelper', JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'vbridgeadminapprovals.php');

$controller = JControllerLegacy::getInstance('Vbridgeadminapprovals');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
