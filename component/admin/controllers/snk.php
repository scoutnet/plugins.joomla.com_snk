<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
require_once('components/com_snk/models/lib/class.com_snk_sn.php');
 
// import Joomla controllerform library
jimport('joomla.application.component.controllerform');

 
/**
 * SNK Controller
 */
class SnkControllerSnk extends JControllerForm {
	function display($cacheable=false){

			$this->params = &JComponentHelper::getParams( 'com_snk' );

			$user =& JFactory::getUser();
			
			$SN = new com_snk_sn();

			$ssid = $this->params->get('SSID');

			try {
				$SN->request_write_permissions_for_calender($ssid,$user->getParam('ScoutNetUser'),$user->getParam('ScoutApiKey'));

				$info[] = 'rightRequested';
				JRequest::setVar('view', JRequest::getCmd('view', 'requested'));
			} catch (Exception $e) {
				echo "error";
				$info[] = sprintf('errorRequstRight',$e->getMessage());
			}


		// set default view if not set
		//JRequest::setVar('view', JRequest::getCmd('view', 'events'));
 
		// call parent behavior
		parent::display($cachable);
	}

}
