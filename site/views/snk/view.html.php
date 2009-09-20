<?php
jimport( 'joomla.application.component.view');

class SnkViewSnk extends JView
{
	function display($tpl = null)
	{
		$events = $this->get( 'Events' );
		$kalenders = $this->get('Kalenders');
		//$params = &JComponentHelper::getParams( 'com_snk' );
		//$greeting =  $params->get( 'SSID' );


		$this->assignRef( 'events', $events );
		$this->assignRef( 'kalenders', $kalenders );

		parent::display($tpl);
	}
}
?>
