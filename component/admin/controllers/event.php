<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla controllerform library
jimport('joomla.application.component.controllerform');
require_once('components/com_snk/models/lib/class.com_snk_sn.php');
 
/**
 * SNK Controller
 */
class SnkControllerEvent extends JControllerForm {
	private $helper;
	private $params;

	private $ssids;

	function __construct() {
		$this->helper = new com_snk_sn();
		$this->params = &JComponentHelper::getParams( 'com_snk' );
		$this->ssid = intval($this->params->get('SSID'));

		parent::__construct();
	}

	function save($key=null,$urlVar = null) {
		JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		// Initialise variables.
		$app		= JFactory::getApplication();
		$lang		= JFactory::getLanguage();
		$model		= $this->getModel();
		$task		= $this->getTask();
		$user		= JFactory::getUser();

		$data = JRequest::getVar('jform', array(), 'post', 'array');

		$start_time = split(':',$data['Start_Time']);
		$end_time = split(':',$data['End_Time']);
		$start_date = split('-',$data['Start_Date']);
		$end_date = split('-',$data['End_Date']);

		$start = mktime(intval($start_time[0]),
			intval($start_time[1]),intval(0),
			intval($start_date[1]),
			intval($start_date[2]),
			intval($start_date[0]));

		$end = mktime(intval($end_time[0]),
			intval($end_time[1]), intval(0),
			intval($end_date[1]==""?$start_date[1]:$end_date[1]),
			intval($end_date[2]==""?$start_date[2]:$end_date[2]),
			intval($end_date[0]==""?$start_date[0]:$end_date[0]));

		$event = array(
			'ID' => is_numeric($data['ID'])?$data['ID']:-1,
			'SSID' => $this->ssid,
			'Title' => $data['Title'],
			'Organizer' => $data['Organizer'],
			'Target_Group' => $data['Target_Group'],
			'Start' => $start, 
			'End' => $end,
			'All_Day' => $data['Start_Time']['m'] == "" || $data['Start_Time']['h'] == "",
			'ZIP' => $data['ZIP'],
			'Location' => $data['Location'],
			'URL_Text' => $data['Link_Text'],
			'URL' => $data['Link_URL'],
			'Description' => $data['Description'],
			'Stufen' => array(),
		);   

		$event['Keywords'] = $data['keywords'];

		foreach ($data['custum_keywords'] as $keyword){
			if (strlen(trim($keyword)) > 0) { 
				$customKeywords[] = trim($keyword);
			}    
		}    

		if (count($customKeywords) > 0) 
			$event['Custom_Keywords'] = $customKeywords;


		$this->helper->write_event($event['ID'],$event,$user->getParam('ScoutNetUser'),$user->getParam('ScoutApiKey'));

		$this->setMessage(JText::_($this->text_prefix.'_ITEM_'.($data['ID']<0?'CREATED':'SAVED')));
		// Redirect to the list screen.
		$this->setRedirect(JRoute::_('index.php?option='.$this->option.'&view='.$this->view_list.$this->getRedirectToListAppend(), false));

		return true;
	}
}
