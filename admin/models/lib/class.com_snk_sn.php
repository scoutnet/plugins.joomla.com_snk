<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009 Stefan Horst <s.horst@dpsg-koeln.de>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 * Hint: use extdeveval to insert/update function index above.
 */

require_once('class.com_snk_jsonRPCClient.php');
require_once('class.com_snk_AES.php');

require_once('models/SN_Model_Stufe.php');
require_once('models/SN_Model_Kalender.php');
require_once('models/SN_Model_User.php');
require_once('models/SN_Model_Event.php');

DEFINE("SCOUTNET_LOGIN_PAGE","https://www.scoutnet.de/community/scoutnetConnect.html");


/**
 * Service "SN" for the "com_snk" extension.
 *
 * @author	Stefan Horst <s.horst@dpsg-koeln.de>
 */
class com_snk_sn {
	var $iv = '1234567890123456';

	var $SN = null;

	var $user_cache = array();
	var $stufen_cache = array();
	var $kalender_cache = array();

	var $snData;

	private $params;
	
	/**
	 * [Put your description here]
	 *
	 * @return	[type]		...
	 */
	public function __construct()	{
		ini_set('default_socket_timeout',1);
		$this->SN = new com_snk_jsonRPCClient("https://www.scoutnet.de/jsonrpc/server.php");
		$this->params = &JComponentHelper::getParams( 'com_snk' );
	}

	protected function load_data_from_scoutnet($ids,$query){
		$res = $this->SN->get_data_by_global_id($ids,$query);

		return $res;
	}

	public function get_events_for_global_id_with_filter($ids,$filter){
		$events = array();
		foreach ($this->load_data_from_scoutnet($ids,array('events'=>$filter)) as $record) {

			if ($record['type'] === 'user'){
				$user = new SN_Model_User($record['content']);
				$this->user_cache[$user['userid']] = $user;
			} elseif ($record['type'] === 'stufe'){
				$stufe = new SN_Model_Stufe($record['content']);
				$this->stufen_cache[$stufe['Keywords_ID']] = $stufe;
			} elseif ($record['type'] === 'kalender'){
				$kalender = new SN_Model_Kalender($record['content']);
				$this->kalender_cache[$kalender['ID']] = $kalender;
			} elseif ($record['type'] === 'event') {
				$event = new SN_Model_Event($record['content']);

				$author = $this->get_user_by_id($event['Last_Modified_By']);
				if ($author == null) {
					$author = $this->get_user_by_id($event['Created_By']);
				}

				if ($author != null) {
					$event['Author'] = $author;
				}	

				$stufen = Array();


				if (isset($event['Stufen'])){
					foreach ($event['Stufen'] as $stufenId) {
						$stufe = $this->get_stufe_by_id($stufenId);
						if ($stufe != null) {
							$stufen[] = $stufe;
						}
					}
				}

				$event['Stufen'] = $stufen;
					
				$event['Kalender'] = $this->get_kalender_by_id($event['Kalender']);


				$events[] = $event;
			}
		}
		return $events;
	}

	public function get_events_with_ids($ids,$event_ids){
		return $this->get_events_for_global_id_with_filter($ids,array('event_ids'=>$event_ids));
	}

	public function get_kalender_by_global_id($ids) {
		$kalenders = array();
		foreach ($this->load_data_from_scoutnet($ids,array('kalenders'=>array())) as $record) {
			if ($record['type'] === 'kalender'){
				$kalender = new SN_Model_Kalender($record['content']);
				$this->kalender_cache[$kalender['ID']] = $kalender;
				$kalenders[] = $kalender;
			}
		}

		return $kalenders;
	}

	private function get_stufe_by_id($id) {
		return $this->stufen_cache[$id];
	}

	private function get_kalender_by_id($id) {
		return $this->kalender_cache[$id];
	}

	private function get_user_by_id($id) {
		return $this->user_cache[$id];
	}

	public function write_event($id,$data,$user,$api_key) {
		$type = 'event';
		$auth = $this->_generate_auth($api_key,$type.$id.serialize($data).$user);

		return $this->SN->setData($type,$id,$data,$user,$auth);
	}

	public function delete_event($ssid,$id,$user,$api_key) {
		$type = 'event';
		$auth = $this->_generate_auth($api_key,$type.$ssid.$id.$user);

		return $this->SN->deleteObject($type,$ssid,$id,$user,$auth);
	}

	public function has_write_permission_to_calender($ssid,$user,$api_key) {
		$type = 'event';
		$auth = $this->_generate_auth($api_key,$type.$ssid.$user);

		return $this->SN->checkPermission($type,$ssid,$user,$auth);
	}

	public function request_write_permissions_for_calender($ssid,$user,$api_key) {
		$type = 'event';
		$auth = $this->_generate_auth($api_key,$type.$ssid.$user);

		return $this->SN->requestPermission($type,$ssid,$user,$auth);
	}

	public function get_scoutnetConnectLoginButton($returnUrl = '',$requestApiKey = false){
		$this->_check_for_all_configValues();
		$button = '<form action="'.SCOUTNET_LOGIN_PAGE.'" id="scoutnetLogin" method="post" target="_self">';

		$button .= $returnUrl == ''?'':'<input type="hidden" name="redirect_url" value="'.$returnUrl.'" />';
		$button .= '<input type="hidden" name="provider" value="'.$this->params->get('ScoutnetProviderName').'" />';
		$button .= $requestApiKey?'<input type="hidden" name="createApiKey" value="1" />':'';
		
		$button .= '<a href="#" onclick="document.getElementById(\'scoutnetLogin\').submit(); return false;">';
		$button .= '<img src="components/com_snk/res/scoutnetConnect.png" title="scoutnet" alt="scoutnet"/>';
		$button .= '</a>';
		
		$button .= '</form>';

		return $button;
	}


	public function getApiKeyFromData(){
		if (isset($this->snData)) {
			return $this->snData;
		}    

		$uri = JFactory::getURI();

		if (!$uri->hasVar('auth') || trim($uri->getVar('auth')) == '') {
			return false;
		}

		$this->_check_for_all_configValues();
		$z = $this->params->get('AES_key');
		$iv = $this->params->get('AES_iv');

		$aes = new com_snk_AES($z,"CBC",$iv);

		$base64 = base64_decode(strtr($uri->getVar('auth'), '-_~','+/='));

		if (trim($base64) == "")  
			throw new Exception('the auth is empty');

		$data = unserialize(substr($aes->decrypt($base64),strlen($iv)));


		$md5 = $data['md5']; unset($data['md5']);
		$sha1 = $data['sha1']; unset($data['sha1']);

		if (md5(serialize($data)) != $md5) {
			throw new Exception('the auth is broken');
		}    

		if (sha1(serialize($data)) != $sha1) {
			throw new Exception('the auth is broken');
		}    


		if (time() - $data['time'] > 3600) {
			throw new Exception('the auth is too old. Try again');
		}    

		$your_domain = $this->params->get('ScoutnetProviderName');

		if ($data['your_domain'] != $your_domain)
			throw new Exception('the auth is for the wrong site!. Try again');

		$this->snData = $data;

		return $data;
	}

	private function _check_for_all_configValues(){

		$configVars = array('AES_key','AES_iv','ScoutnetProviderName');

		foreach ($configVars as $configVar) {
			if (trim($this->params->get($configVar)) == '')
				throw new com_snk_sn_Exception_MissingConfVar($configVar);
		}
	}


	private function _generate_auth($api_key,$checkValue){
		if ($api_key == '')
			throw new Exception('your Api Key is empty');

		$aes = new com_snk_AES($api_key,"CBC",$this->iv);

		$auth = array(
			'sha1' => sha1($checkValue),
			'md5' => md5($checkValue),
			'time' => time(),
		);
		$auth = serialize($auth);

		// this is done since we use the same iv all the time
		$first_block = '';
		for ($i=0;$i<16;$i++) {
			$first_block .= chr(rand(0,255));
		}

		$auth = strtr(base64_encode($aes->encrypt($first_block.$auth)), '+/=', '-_~');
		return $auth;
	}

}

class com_snk_sn_Exception extends Exception{}

class com_snk_sn_Exception_MissingConfVar extends com_snk_sn_Exception{
	public function __construct( $var ){
		parent::__construct( "Missing '$var'. Please Contact your Joomla Admin to enter a valid '$var'. You can request it via <a href=\"mailto:scoutnetconnect@scoutnet.de\">scoutnetConnect@ScoutNet.de</a>." );
	}
}

?>
