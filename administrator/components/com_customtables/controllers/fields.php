<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				JoomlaBoat.com
/-------------------------------------------------------------------------------------------------------/

	@version		1.0.76
	@build			1st July, 2018
	@created		24th May, 2018
	@package		Custom Tables
	@subpackage		fields.php
	@author			Ivan Komlev <https://joomlaboat.com>
	@copyright		Copyright (C) 2018. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html

/------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controllerform library
jimport('joomla.application.component.controllerform');

/**
 * Fields Controller
 */
class CustomtablesControllerFields extends JControllerForm
{
	/**
	 * Current or most recently performed task.
	 *
	 * @var    string
	 * @since  12.2
	 * @note   Replaces _task.
	 */
	protected $task;

	public function __construct($config = array())
	{
		//$jinput = JFactory::getApplication()->input;
		//$tableid=JFactory::getApplication()->input->getint('tableid',0);

		$this->view_list = 'listoffields'; // safeguard for setting the return view listing to the main view.
		parent::__construct($config);
	}

        /**
	 * Method override to check if you can add a new record.
	 *
	 * @param   array  $data  An array of input data.
	 *
	 * @return  boolean
	 *
	 * @since   1.6
	 */
	protected function allowAdd($data = array())
	{		// In the absense of better information, revert to the component permissions.
		return parent::allowAdd($data);
	}

	/**
	 * Method override to check if you can edit an existing record.
	 *
	 * @param   array   $data  An array of input data.
	 * @param   string  $key   The name of the key for the primary key.
	 *
	 * @return  boolean
	 *
	 * @since   1.6
	 */
	protected function allowEdit($data = array(), $key = 'id')
	{
		// get user object.
		$user = JFactory::getUser();
		// get record id.
		$recordId = (int) isset($data[$key]) ? $data[$key] : 0;


		if ($recordId)
		{
			// The record has been set. Check the record permissions.
			$permission = $user->authorise('core.edit', 'com_customtables.fields.' . (int) $recordId);
			if (!$permission)
			{
				if ($user->authorise('core.edit.own', 'com_customtables.fields.' . $recordId))
				{
					// Now test the owner is the user.
					$ownerId = (int) isset($data['created_by']) ? $data['created_by'] : 0;
					if (empty($ownerId))
					{
						// Need to do a lookup from the model.
						$record = $this->getModel()->getItem($recordId);

						if (empty($record))
						{
							return false;
						}
						$ownerId = $record->created_by;
					}

					// If the owner matches 'me' then allow.
					if ($ownerId == $user->id)
					{
						if ($user->authorise('core.edit.own', 'com_customtables'))
						{
							return true;
						}
					}
				}
				return false;
			}
		}
		// Since there is no permission, revert to the component permissions.
		return parent::allowEdit($data, $key);
	}

	/**
	 * Gets the URL arguments to append to an item redirect.
	 *
	 * @param   integer  $recordId  The primary key id for the item.
	 * @param   string   $urlVar    The name of the URL variable for the id.
	 *
	 * @return  string  The arguments to append to the redirect URL.
	 *
	 * @since   12.2
	 */
	protected function getRedirectToItemAppend($recordId = null, $urlVar = 'id')
	{
		$tmpl   = $this->input->get('tmpl');
		$layout = $this->input->get('layout', 'edit', 'string');

		$ref 	= $this->input->get('ref', 0, 'string');
		$refid 	= $this->input->get('refid', 0, 'int');

		$tableid= $this->input->getint('tableid',0);
		// Setup redirect info.

		$append = '';

		if ($refid)
                {
			$append .= '&ref='.(string)$ref.'&refid='.(int)$refid;
		}
		elseif ($ref)
		{
			$append .= '&ref='.(string)$ref;
		}

		if ($tmpl)
		{
			$append .= '&tmpl=' . $tmpl;
		}

		if ($layout)
		{
			$append .= '&layout=' . $layout;
		}

		if ($recordId)
		{
			$append .= '&' . $urlVar . '=' . $recordId;
		}

		$append .= '&tableid=' . $tableid;

		return $append;
	}

	/**
	 * Method to run batch operations.
	 *
	 * @param   object  $model  The model.
	 *
	 * @return  boolean   True if successful, false otherwise and internal error is set.
	 *
	 * @since   2.5
	 */
	public function batch($model = null)
	{
		$tableid 	= $this->input->get('tableid', 0, 'int');
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Set the model
		$model = $this->getModel('Fields', '', array());

		// Preset the redirect
		//&tableid='.(int)$tableid
		$this->setRedirect(JRoute::_('index.php?option=com_customtables&view=listoffields'. $this->getRedirectToListAppend(), false));

		return parent::batch($model);
	}

	/**
	 * Method to cancel an edit.
	 *
	 * @param   string  $key  The name of the primary key of the URL variable.
	 *
	 * @return  boolean  True if access level checks pass, false otherwise.
	 *
	 * @since   12.2
	 */
	public function cancel($key = null)
	{
		// get the referal details
		$this->ref 		= $this->input->get('ref', 0, 'word');
		$this->refid 	= $this->input->get('refid', 0, 'int');
		$tableid 	= $this->input->get('tableid', 0, 'int');

		$cancel = parent::cancel($key);

		if ($cancel)
		{
			if ($this->refid)
			{
				$redirect = '&view='.(string)$this->ref.'&layout=edit&tableid='.(int)$tableid.'&id='.(int)$this->refid;

				// Redirect to the item screen.
				$this->setRedirect(
					JRoute::_(
						'index.php?option=' . $this->option . $redirect, false
					)
				);
			}
			elseif ($this->ref)
			{
				$redirect = '&view='.(string)$this->ref.'&tableid='.(int)$tableid;

				// Redirect to the list screen.
				$this->setRedirect(
					JRoute::_(
						'index.php?option=' . $this->option . $redirect, false
					)
				);
			}
		}
		else
		{
			// Redirect to the items screen.
			$this->setRedirect(
				JRoute::_(
					'index.php?option=' . $this->option . '&view=' . $this->view_list.'&tableid='.(int)$tableid, false
				)
			);
		}
		return $cancel;
	}

	/**
	 * Method to save a record.
	 *
	 * @param   string  $key     The name of the primary key of the URL variable.
	 * @param   string  $urlVar  The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
	 *
	 * @return  boolean  True if successful, false otherwise.
	 *
	 * @since   12.2
	 */
	public function save($key = null, $urlVar = null)
	{
		$tableid 	= $this->input->get('tableid', 0, 'int');

		// get the referal details
		$this->ref 		= $this->input->get('ref', 0, 'word');
		$this->refid 	= $this->input->get('refid', 0, 'int');

		if ($this->ref || $this->refid)
		{
			// to make sure the item is checkedin on redirect
			$this->task = 'save';
		}

		$saved = parent::save($key, $urlVar);

		if ($this->refid && $saved)
		{
			$redirect = '&view='.(string)$this->ref.'&layout=edit&tableid='.(int)$tableid.'&id='.(int)$this->refid;

			// Redirect to the item screen.
			$this->setRedirect(
				JRoute::_(
					'index.php?option=' . $this->option . $redirect, false
				)
			);
		}
		elseif ($this->ref && $saved)
		{
			$redirect = '&view='.(string)$this->ref.'&tableid='.(int)$tableid;

			// Redirect to the list screen.
			$this->setRedirect(
				JRoute::_(
					'index.php?option=' . $this->option . $redirect, false
				)
			);
		}
		return $saved;
	}

	/**
	 * Function that allows child controller access to model data
	 * after the data has been saved.
	 *
	 * @param   JModel  &$model     The data model object.
	 * @param   array   $validData  The validated data.
	 *
	 * @return  void
	 *
	 * @since   11.1
	 */
	protected function postSaveHook(JModelLegacy $model, $validData = array())
	{
		return;
	}

}
