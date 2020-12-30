<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Message_from_vbridgehub
 * @author     Manikanta <mketha@ameripro-solutions.com>
 * @copyright  2019 Manikanta
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

use \Joomla\CMS\MVC\Controller\BaseController;
use \Joomla\CMS\Factory;
use \Joomla\CMS\Language\Text;

// Access check.
if (!Factory::getUser()->authorise('core.manage', 'com_message_from_vbridgehub'))
{
	throw new Exception(Text::_('JERROR_ALERTNOAUTHOR'));
}

// Include dependancies
jimport('joomla.application.component.controller');

JLoader::registerPrefix('Message_from_vbridgehub', JPATH_COMPONENT_ADMINISTRATOR);
JLoader::register('Message_from_vbridgehubHelper', JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'message_from_vbridgehub.php');

$controller = BaseController::getInstance('Message_from_vbridgehub');
$controller->execute(Factory::getApplication()->input->get('task'));
$controller->redirect();
