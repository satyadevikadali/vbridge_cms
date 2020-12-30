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

jimport('joomla.application.component.controllerform');

/**
 * Approval controller class.
 *
 * @since  1.6
 */
class VbridgeadminapprovalsControllerApproval extends JControllerForm
{
	/**
	 * Constructor
	 *
	 * @throws Exception
	 */
	public function __construct()
	{
		$this->view_list = 'approvals';
		parent::__construct();
	}
}
