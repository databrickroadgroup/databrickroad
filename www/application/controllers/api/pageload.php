<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';

class Pageload extends REST_Controller
{
	public function users_get() {
		$this->load->model('Guid_model');
		$guid = $this->Guid_model->generate_Guid();

		$this->load->model('Pageload_model');
		$pageloads = $this->Pageload_model->getAllPageLoads();

		$data = array(
						'databrickid' => $guid,
						'databrick' =>
							array(
								'id' => 1,
								'url' => 'testing.com',
								'user_behavior' => array(
									'ub_id'=>123,
									'ub_score'=>100,
									'ub_blah'=>'high')
								),
						'pageloads' => $pageloads
					);

		$this->response($data, 200);
	}

	public function user_get($dbrid) {

		if(!$this->get('dbrid')) {
        	$this->response(array("failed"=>"Need Data Brick User ID"), 400);
        } else {
			$guid = $this->get('dbrid');
		}

		$this->load->model('Pageload_model');
		$pageloads = $this->Pageload_model->getByGuid($guid);

		$this->response($pageloads, 200);
	}

	public function user_table_get($dbrid) {

		if(!$this->get('dbrid')) {
        	$this->response(array("failed"=>"Need Data Brick User ID"), 400);
        } else {
			$guid = $this->get('dbrid');
		}

		$this->load->model('Pageload_model');
		$pageloads = $this->Pageload_model->getByGuidTable($guid);

		$this->response($pageloads, 200);
	}

	public function most_visited_get() {

		$limit = ($this->get('limit')) ? $this->get('limit') : 100;
		$from = ($this->get('from')) ? $this->get('from') : null;
		$to = ($this->get('to')) ? $this->get('to') : null;

		if(!$this->get('domain')) {
        	$this->response(array("failed"=>"Need domain name"), 400);
        } else {
			$domain = $this->get('domain');
		}

		$this->load->model('Pageload_model');
		$mostvisited = $this->Pageload_model->getTopPageLoadsByDomain($domain, $from, $to, $limit);

		echo json_encode($mostvisited, JSON_NUMERIC_CHECK);
	}

	public function user_by_pagename_post() {
		if(!$this->post('pagename')) {
        	$this->response(array("failed"=>"Need page name"), 400);
        } else {
			$pagename = $this->post('pagename');
		}

		if(!$this->post('domain')) {
        	$this->response(array("failed"=>"Need domain name"), 400);
        } else {
			$domain = $this->post('domain');
		}

		$date = $this->post('date');

		$this->load->model('Pageload_model');
		$users = $this->Pageload_model->getUsersByPageName($domain, $pagename, $date);

		echo @json_encode($users, JSON_NUMERIC_CHECK);
	}

	public function user_post() {

		$this->load->library('BuildSQLData');
		$this->load->library('AccessControl');
		if (!$this->accesscontrol->validDomain($this->post('domain'))) {
			$message = $this->accesscontrol->errorMessage();
			exit($message);
		}

		$this->load->model('Pageload_model');
		$postData = $this->post();
		$postData['created'] = date('Y-m-d H:i:s');
		$fields = array('created','user_guid','domain','page_name','page_url','referrer');
		$data = $this->buildsqldata->buildData($this->Pageload_model, $postData, $fields);

		if (!isset($data['user_guid'])) {
			$this->load->model('Guid_model');
			$data['user_guid'] = $this->Guid_model->generate_Guid();
		}

		$pageloads = $this->Pageload_model->getByGuidDomainAndPage($postData['user_guid'], $postData['domain'], $postData['page_name']);
		$uniquevisit = (count($pageloads) == 0) ? true : false;

		// create a new page load record
		$insertID = $this->Pageload_model->addNew($data);
		$data['uniquevisit'] = $uniquevisit;

		if ($insertID > 0) {
			// handle user behavior
			$userBehaviorResult = $this->postUserBehavior($data);
			$updateResult = $userBehaviorResult['recordnum'];

			echo json_encode(array("databrickuser"=>$data['user_guid'],"result"=>"pageload:$insertID | behaviorupdate:$updateResult"));
		} else {
			echo json_encode(array("result"=>"failed"));
		}
	}

	public function user_options() {
					return $this->response(NULL, 200);
	}

	private function postUserBehavior($postData) {
		// check if the guid already exists in the db
		$this->load->library('BuildSQLData');
		$this->load->model('Userbehavior_model');
		$userbehavior = $this->Userbehavior_model->getByGuid($postData['user_guid']);
		$fields = array();
		$data = array();

		if (count($userbehavior) > 0) {
			// get user behavior data for guid
			$userbehavior = $this->Userbehavior_model->getLatestByGuid($postData['user_guid']);

			// calculate new duration
			$newduration = $this->calculateNewDuration($userbehavior[0]['duration'], $userbehavior[0]['start']);
			$postData['duration'] = $newduration;
			$postData['end'] = date('Y-m-d H:i:s');
			$postData['pageloadcount'] = $userbehavior[0]['pageloadcount'] + 1;
			$postData['uniquepagecount'] = ($postData['uniquevisit'] == true) ? $userbehavior[0]['uniquepagecount'] + 1 : $userbehavior[0]['uniquepagecount'];
			$postData['lastvisitedpage'] = $postData['page_url'];

			$fields = array('end','duration','pageloadcount','uniquepagecount','lastvisitedpage');

			// update
			$data = $this->buildsqldata->buildData($this->Userbehavior_model, $postData, $fields);
			$result = $this->Userbehavior_model->update($userbehavior[0]['id'], $data);

			return array("result"=>$result,"recordnum"=>$userbehavior[0]['id']);

		} else {
			// build data for new user
			// fields to populate and put in the db
			$postData['start'] = $postData['created'];
			$postData['end'] = '';
			$postData['pageloadcount'] = 1;
			$postData['duration'] = 0;
			$postData['uniquepagecount'] = 1;
			$postData['lastvisitedpage'] = $postData['page_url'];
			$fields = array('created','user_guid','start','end','duration','pageloadcount','uniquepagecount','lastvisitedpage');
			$data = $this->buildsqldata->buildData($this->Userbehavior_model, $postData, $fields);

			// create a new record
			$insertID = $this->Userbehavior_model->addNew($data);

			return $insertID;
		}
	}

	// returns seconds
	private function calculateNewDuration($currentduration, $initialcreateddate) {
		$currentdatetime = strtotime(date('Y-m-d H:i:s'));
		$initialstart = strtotime($initialcreateddate);
		$durationtoadd = floor(abs($currentdatetime - $initialstart) / 60*60);
		$newduration = (int)$currentduration + (int)$durationtoadd;
		return $newduration;
	}
}

?>
