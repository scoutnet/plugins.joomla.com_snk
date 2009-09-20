<?php
jimport( 'joomla.application.component.view');

class SnkViewSnk extends JView
{
	function display($tpl = null)
	{
		$greeting = $this->get( 'Kalender' );
		//$params = &JComponentHelper::getParams( 'com_snk' );
		//$greeting =  $params->get( 'SSID' );

		$this->assignRef( 'greeting',	$greeting );

		parent::display($tpl);
	}
}
?>
