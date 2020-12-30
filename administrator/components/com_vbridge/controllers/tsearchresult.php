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

jimport('joomla.application.component.controllerform');

/**
 * Tsearchresult controller class.
 *
 * @since  1.6
 */
class VbridgeControllerTsearchresult extends JControllerForm
{
	/**
	 * Constructor
	 *
	 * @throws Exception
	 */
	public function __construct()
	{
		$this->view_list = 'tsearchresults';
		parent::__construct();
	}
}
