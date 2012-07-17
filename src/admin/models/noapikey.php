<?php
defined('_JEXEC') or die();

require_once('lib/class.com_snk_sn.php');

jimport('joomla.application.component.modellist');

class SnkModelNoapikey extends JModelList {
	private $helper;
	private $params;

	private $ssids;

	function __construct() {
		$this->helper = new com_snk_sn();
		$this->params = &JComponentHelper::getParams( 'com_snk' );

/*		$user =& JFactory::getUser();

		// define path to the XML file
		$path = JPATH_COMPONENT_ADMINISTRATOR . DS . 'models';
		// load the XML file and get the JParameter object
		$params =& $user->getParameters(true, $path);

		$user->setParam('ScoutNetUser','foo');
		$user->save();
*/

		$this->ssids = explode(',', $this->params->get('SSID'));
		parent::__construct();
	}
	function getKalender() {
		$kalenders =  $this->helper->get_kalender_by_global_id($this->ssids);
		return $kalenders[0];
	}

	function getSN() {
		return $this->helper;
	}

}
