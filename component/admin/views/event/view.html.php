<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla view library
jimport('joomla.application.component.view');
 
/**
 * Snk View
 */
class SnkViewEvent extends JView
{
	/**
	 * display method of Snk
	 * @return void
	 */
	public function display($tpl = null) 
	{
		// get the Data
		$form = $this->get('Form');
		$item = $this->get('Item');
		$script = $this->get('Script');
		$keywords = $this->get('Keywords');
		$forced_kategories = $this->get('ForcedKategories');
		$dpsg_edu = $this->get('DpsgEdu');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) 
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		// Assign the Data
		$this->form = $form;
		$this->item = $item;
		$this->script = $script;
		$this->keywords = $keywords;
		$this->forced_kategories = $forced_kategories;
		$this->dpsg_edu = $dpsg_edu;

		// Set the toolbar
		$this->addToolBar();
 
		// Display the template
		parent::display($tpl);

		// Set the document
		$this->setDocument();
	}
 
	/**
	 * Setting the toolbar
	 */
	protected function addToolBar() {
		JRequest::setVar('hidemainmenu', true);
		$isNew = ($this->item['ID'] < 0);
		JToolBarHelper::title($isNew ? JText::_('COM_SNK_MANAGER_EVENT_NEW')
		                             : JText::_('COM_SNK_MANAGER_EVENT_EDIT'),'snk');
		JToolBarHelper::save('event.save');
		JToolBarHelper::cancel('event.cancel', $isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE');
	}

	/**
	 * Method to set up the document properties
	 *
	 * @return void
	 */
	protected function setDocument() 
	{
		$isNew = ($this->item['ID'] < 0);
		$document = JFactory::getDocument();
		$document->setTitle($isNew ? JText::_('COM_SNK_EVENT_CREATING')
		                           : JText::_('COM_SNK_EVENT_EDITING'));
		$document->addScript(JURI::root() . $this->script);
		$document->addScript(JURI::root() . "/administrator/components/com_snk/views/event/submitbutton.js");
		JText::script('COM_SNK_EVENT_ERROR_UNACCEPTABLE');
	}
}
