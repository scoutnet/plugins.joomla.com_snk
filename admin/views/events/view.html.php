<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');

/**
 * Snk View
 */
class SnkViewEvents extends JView
{
	/**
	 * Snk view display method
	 * @return void
	 */
	function display($tpl = null) 
	{
		// Get data from the model
		$events = $this->get('Events');
 
		// Check for errors.
		if (count($errors = $this->get('Errors'))) 
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		// Assign data to the view
		$this->events = $events;

		// Set the toolbar
		$this->addToolBar();
 
		// Display the template
		parent::display($tpl);
	}
	/**
	 ** Setting the toolbar
	 **/
	protected function addToolBar() 
	{
		$kalender = $this->get('Kalender');
		$user =& JFactory::getUser();
		JToolBarHelper::title(JText::_('COM_SNK_MANAGER_EVENTS').' - '.$kalender->get_Name(),'snk');

		$SN = $this->get('SN');
	
		$rights = $SN->has_write_permission_to_calender($kalender['ID'],$user->getParam('ScoutNetUser'),$user->getParam('ScoutApiKey'));

		if($rights['code'] != 0) {
			JToolBarHelper::custom( 'snk.request', 'move', 'user-add', 'request Rights', false, false );
		} else {
			JToolBarHelper::deleteList('', 'events.delete');
			JToolBarHelper::editList('event.edit');
			JToolBarHelper::addNew('event.add');
		}
		JToolBarHelper::preferences('com_snk');

	}
}
