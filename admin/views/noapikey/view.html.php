<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');

/**
 * Snk View
 */
class SnkViewNoapikey extends JView
{
	/**
	 * Snk view display method
	 * @return void
	 */
	function display($tpl = null) 
	{
 		$sn = $this->get('SN');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) 
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		// Assign data to the view
		$this->sn = $sn;

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
		JToolBarHelper::title(JText::_('COM_SNK_MANAGER_EVENTS').' - '.$kalender->get_Name());
		JToolBarHelper::preferences('com_snk');

	}
}
