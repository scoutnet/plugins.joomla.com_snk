<?php
/**
 * Snk View for Snk World Component
 * 
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://docs.joomla.org/Developing_a_Model-View-Controller_Component_-_Part_4
 * @license		GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );

/**
 * Snk View
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class SnksViewSnk extends JView
{
	/**
	 * display method of Snk view
	 * @return void
	 **/
	function display($tpl = null)
	{
		//get the snk
		$snk		=& $this->get('Data');
		$isNew		= ($snk->id < 1);

		$text = $isNew ? JText::_( 'New' ) : JText::_( 'Edit' );
		JToolBarHelper::title(   JText::_( 'Snk' ).': <small><small>[ ' . $text.' ]</small></small>' );
		JToolBarHelper::save();
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
			// for existing items the button is renamed `close`
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}

		$this->assignRef('snk',		$snk);

		parent::display($tpl);
	}
}
