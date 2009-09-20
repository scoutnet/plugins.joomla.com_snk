<?php
defined('_JEXEC') or die();

require_once("jsonRPCClient.php");
require_once('stufe.php');
require_once('kalender.php');
require_once('user.php');
require_once('event.php');

jimport( 'joomla.application.component.model' );

class SnkModelSnk extends JModel {
	var $user_cache = array();
	var $stufen_cache = array();
	var $kalender_cache = array();

	function getKalenders($params = null){
		if ($params == null)
			$params = &JComponentHelper::getParams( 'com_snk' );

		$ssids =  split(",",$params->get('SSIDs',4));

		$out = array();
		foreach ($ssids as $ssid){
			$out[] = $this->get_kalender_by_id($ssid);
		}
		return $out;
	}

	function getEvents($params = null) {
		ini_set('default_socket_timeout',1);
		$SN = new com_snk_jsonRPCClient("https://www.scoutnet.de/jsonrpc/server.php");
		//$SN = new com_snk_jsonRPCClient("http://localhost/www.scoutnet.de/public_html/jsonrpc/server.php");
		$default_limit = 4;
		if ($params == null) {
			$params = &JComponentHelper::getParams( 'com_snk' );
			$default_limit = 20;
		}

		$ids =  split(",",$params->get('SSIDs',4));

		$limit = $params->get('limit',$default_limit);

		$filter = array(
			'limit'=>$limit,
			'after'=>'now()',
		);

		$kategories = $params->get('Kategories');
		if (isset($kategories) && trim($kategories)) {
			$filter['kategories'] = split(",",$kategories);
		}

		$stufen = $params->get('Stufen');
		if (isset($stufen) && trim($stufen)) {
			$filter['stufen'] = split(",",$stufen);
		}


		$results = $SN->get_data_by_global_id($ids,array('events'=>$filter));

		$events = array();
		foreach ($results as $record) {
			if ($record['type'] === 'user'){
				$user = new user($record['content']);
				$this->user_cache[$user['userid']] = $user;
			} elseif ($record['type'] === 'stufe'){
				$stufe = new stufe($record['content']);
				$this->stufen_cache[$stufe['Keywords_ID']] = $stufe;
			} elseif ($record['type'] === 'kalender'){
				$kalender = new kalender($record['content']);
				$this->kalender_cache[$kalender['ID']] = $kalender;
			} elseif ($record['type'] === 'event') {
				$event = new event($record['content']);

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

	private function get_stufe_by_id($id) {
		return $this->stufen_cache[$id];
	}

	private function get_kalender_by_id($id) {
		return $this->kalender_cache[$id];
	}

	private function get_user_by_id($id) {
		return $this->user_cache[$id];
	}

}
