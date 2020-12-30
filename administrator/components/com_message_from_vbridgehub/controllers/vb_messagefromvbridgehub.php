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

jimport('joomla.application.component.controllerform');

/**
 * Vb_messagefromvbridgehub controller class.
 *
 * @since  1.6
 */
class Message_from_vbridgehubControllerVb_messagefromvbridgehub extends \Joomla\CMS\MVC\Controller\FormController
{
	/**
	 * Constructor
	 *
	 * @throws Exception
	 */
	public function __construct()
	{
		$this->view_list = 'vb_messagefromvbridgehubs';
		parent::__construct();
	}
}
