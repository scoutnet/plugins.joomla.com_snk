<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');
require_once('components/com_snk/models/lib/class.com_snk_sn.php');
 
/**
 * SNKs Controller
 */
class SnkControllerEvents extends JControllerAdmin
{
	private $helper;
	private $params;

	private $ssids;

	function __construct() {
		$this->helper = new com_snk_sn();
		$this->params = &JComponentHelper::getParams( 'com_snk' );
		$this->ssid = intval($this->params->get('SSID'));

		parent::__construct();
	}
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function getModel($name = 'Snk', $prefix = 'SnkModel') 
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}

	public function getKalender() {
		return array();
	}

	public function delete(){
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$user		= JFactory::getUser();
		// Get items to remove from the request.
		$cid	= JRequest::getVar('cid', array(), '', 'array');

		if (!is_array($cid) || count($cid) < 1) {
			JError::raiseWarning(500, JText::_($this->text_prefix.'_NO_ITEM_SELECTED'));
		} else {
			// Make sure the item ids are integers
			jimport('joomla.utilities.arrayhelper');
			JArrayHelper::toInteger($cid);
		
			try {
				foreach ($cid as $id) {
					$this->helper->delete_event($this->ssid,$id,$user->getParam('ScoutNetUser'),$user->getParam('ScoutApiKey'));
				}
				$this->setMessage(JText::plural($this->text_prefix.'_N_ITEMS_DELETED', count($cid)));
			} catch (Exception $e) {
				$this->setMessage($e->getMessage());
			}
		}

		$this->setRedirect(JRoute::_('index.php?option='.$this->option.'&view='.$this->view_list, false));


		return true;
	}
}
