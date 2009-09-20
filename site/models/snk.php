<?php
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

class SnkModelSnk extends JModel {
	function getKalender() {
		$db =& JFactory::getDBO();

		$query = 'SELECT greeting FROM #__snk';
		$db->setQuery( $query );
		$greeting = $db->loadResult();

		$greeting = "foo";

		return $greeting;
	}
}
