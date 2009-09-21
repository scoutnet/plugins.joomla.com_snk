<?php
jimport( 'joomla.application.component.view');

class SnkViewSnk extends JView {
	function display($tpl = null) {
		try {
			$events = $this->get( 'Events' );
			$kalenders = $this->get('Kalenders');

			$this->assignRef( 'events', $events );
			$this->assignRef( 'kalenders', $kalenders );

			parent::display($tpl);
		} catch (Exception $e) {
			echo "<span class='termin'>zZ ist der Scoutnet Kalender down.<br>Bitte versuch es zu einem sp&auml;teren Zeitpunkt noch mal</span>";
		}
	}
}
?>
