<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 
// import Joomla modelform library
jimport('joomla.application.component.modeladmin');

require_once('lib/class.com_snk_sn.php');

class SnkHelperMyTable {
	public function getKeyName(){
		return 'id';
	}

}

/**
 * Event Model
 */
class SnkModelEvent extends JModelAdmin {
	private $helper;
	private $params;

	private $ssids;

	function __construct() {
		$this->helper = new com_snk_sn();
		$this->params = &JComponentHelper::getParams( 'com_snk' );

		$this->ssid = intval($this->params->get('SSID'));
		parent::__construct();
	}

	public function checkin($pks = array()) {}
	public function checkout($pk = null) {}
 
	public function getTable(){
		return new SnkHelperMyTable();
	}
	/**
	 * Method to get the record form.
	 *
	 * @param	array	$data		Data for the form.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	mixed	A JForm object on success, false on failure
	 * @since	1.6
	 */
	public function getForm($data = array(), $loadData = true) 
	{
		// Get the form.
		/*$form = $this->loadForm('com_snk.event', 'event', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) 
		{
			return false;
		}

		$form->load('<form><fieldset><field name="Keywords" type="checkboxes" label="COM_SNK_EVENT_KEYWORD_ID_LABEL" description="COM_SNK_EVENT_KEYWORD_ID_DESC"/></fieldset></form>',true);
		 
		return $form;
		*/
		return null;
	}
	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 * @since	1.6
	 */
	protected function loadFormData() 
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_snk.edit.event.data', array());
		if (empty($data)) 
		{
			$data = $this->getItem();
		}
		return $data;
	}

	/**
	 * Method to get the script that have to be included on the form
	 *
	 * @return string	Script files
	 */
	public function getScript() 
	{
		return 'administrator/components/com_snk/models/forms/event.js';
	}

	function getKeywords(){
		$id = JRequest::getInt('id',-1);
		$event = ($id < 0)?false:$this->helper->get_events_with_ids(array($this->ssid),array(JRequest::getInt('id',-1)));
		$kalenders = $this->helper->get_kalender_by_global_id(array($ssid));
		$kategories = $kalenders[0]['Used_Kategories'];

		if( $event && !empty($event['Keywords']) ){
			foreach( $event['Keywords'] as $id => $keyword ) {
				$kategories[$id] = $keyword;
			}
			// this should only remove keywords that are set for the event, but belong to forced_keywords
			foreach($kalender[0]['Forced_Kategories'] as $forced_keywords_group) {
				foreach( $forced_keywords_group as $id => $keyword ) {
					if(isset($kategories[$id])) {
						unset($kategories[$id]);
					}
				}
			}
		}
		// "sonstiges"-hack
		if(isset($kategories[1])){
			unset($kategories[1]);
		}


		uasort($kategories,'strcoll');

		return $kategories;
	}
	function getDpsgEdu(){
		$kalenders = $this->helper->get_kalender_by_global_id(array($this->ssid));
		return $kalenders[0]['Forced_Kategories']['DPSG-Ausbildung'];
	}

	function getForcedKategories(){
		$kalenders = $this->helper->get_kalender_by_global_id(array($this->ssid));
		return $kalenders[0]['Forced_Kategories']['sections/leaders'];
	}

	function getItem($pk = null){
		$id = JRequest::getInt('id',-1);
		if ($id < 0) {
			return array('ID' => -1);
		}
		$events = $this->helper->get_events_with_ids(array($this->ssid),array($id));
		$event = $events[0];

		// add Stufen
		foreach ($event['Stufen'] as $stufe) {
			$event['Keywords'][$stufe['Keywords_ID']] = $stufe['bezeichnung'];
		}
		$data = array(
			'ID' => is_numeric($event['ID'])?$event['ID']:-1,
			'Created_by' => $event['Created_By'],
			'Created_at' => gmstrftime('%Y-%m-%d %H:%M:%S',$event['Created_At']),
			'Modified_by' => $event['Last_Modified_By'],
			'Modified_at' => gmstrftime('%Y-%m-%d %H:%M:%S',$event['Last_Modified_At']),
			'SSID' => $this->ssid,
			'Title' => $event['Title'],
			'Organizer' => $event['Organizer'],
			'Target_Group' => $event['TargetGroup'],
			'Start_Date' => gmstrftime('%Y-%m-%d',$event['Start']), 
			'Start_Time' => gmstrftime('%H:%M',$event['Start']), 
			'End_Date' => gmstrftime('%Y-%m-%d',$event['End']), 
			'End_Time' => gmstrftime('%H:%M',$event['End']), 
			'ZIP' => $event['Zip'],
			'Location' => $event['Location'],
			'URL_Text' => $event['LinkText'],
			'URL' => $event['LinkUrl'],
			'Description' => $event['Info'],
			'Keywords' => $event['Keywords'],
		);
		return $data;
	}
}
