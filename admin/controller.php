<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controller library
jimport('joomla.application.component.controller');

require_once('models/lib/class.com_snk_sn.php');
 
/**
 * General Controller of HelloWorld component
 */
class SnkController extends JController
{

	private $helper;
	private $params;

	/**
	 * display task
	 *
	 * @return void
	 */
	function display($cachable = false,$urlparams = false) 
	{
		$this->helper = new com_snk_sn();
		$this->params = &JComponentHelper::getParams( 'com_snk' );

		$user =& JFactory::getUser();

		/*
		// define path to the XML file
		$path = JPATH_COMPONENT_ADMINISTRATOR . DS . 'models';
		// load the XML file and get the JParameter object
		$params =& $user->getParameters(true, $path);
		 */

		if ($return_data = $this->helper->getApiKeyFromData()) {
			$user->setParam('ScoutNetUser', $return_data['user']);
			$user->setParam('ScoutApiKey',  $return_data['api_key']);
			$user->save();
		}

		if (trim($user->getParam('ScoutNetUser')) == '' || trim($user->getParam('ScoutApiKey')) == '') {
			// set default view if not set
			JRequest::setVar('view', JRequest::getCmd('view', 'noapikey'));
		} else {
			// set default view if not set
			JRequest::setVar('view', JRequest::getCmd('view', 'events'));
		}
 
		// call parent behavior
		parent::display($cachable,$urlparams);
	}

}
