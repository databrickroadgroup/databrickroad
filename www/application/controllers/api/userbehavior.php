<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';

class Userbehavior extends REST_Controller
{
	public function user_get() {
		
		if(!$this->get('dbrid')) {
        	$this->response(array("failed"=>"Need Data Brick Road User ID"), 400);
        } else {
			$guid = $this->get('dbrid');
		}
		
		$this->load->model('Userbehavior_model');
		$userbehavior = $this->Userbehavior_model->getByGuid($guid);
					
		$this->response($userbehavior, 200);
	}
	
	public function exists_get($dbrid) {
		
		if(!$this->get('dbrid')) {
        	$this->response(array("failed"=>"Need Data Brick Road User ID"), 400);
        } else {
			$guid = $this->get('dbrid');
		}
		
		$this->load->model('Userbehavior_model');
		$userbehavior = $this->Userbehavior_model->getByGuid($guid);
		
		if (count($userbehavior) > 0) {
			$this->response(array("result"=>"1"), 200);
		} else {
			$this->response(array("result"=>"0"), 200);
		}
	}
	
	public function user_post() {

		$this->load->library('AccessControl');
		if (!$this->accesscontrol->validDomain($this->post('domain'))) {
			$message = $this->accesscontrol->errorMessage();
			exit($message);
		}
		
		// check if the guid already exists in the db
		$postData = $this->post();
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
			
			// determine if the page visit is unique
			$this->load->model('Pageload_model');
			$pageloads = $this->Pageload_model->getByGuidDomainAndPage($postData['user_guid'], $postData['domain'], $postData['page_name']);
			
			if (count($pageloads) == 0) {
				// user has not been on this domain and page before
				$postData['uniquepagecount'] = $postData['uniquepagecount'] + 1;
			}
			
			$fields = array('end','duration','pageloadcount','uniquepagecount','lastvisitedpage');
			$data = $this->buildData($this->Userbehavior_model, $postData, $fields);
			$result = $this->Userbehavior_model->update($userbehavior[0]['id'], $data);

			if ($result > 0) {
				echo json_encode(array("result"=>"success:$result"));
			} else {
				echo json_encode(array("result"=>"failed"));
			}
			
		} else {
			// build data for new user
			// fields to populate and put in the db
			$fields = array('created','user_guid','start','end','duration','pageloadcount','uniquepagecount','lastvisitedpage');
			$data = $this->buildData($this->Userbehavior_model, $postData, $fields);
			
			// create a new record
			$insertID = $this->Userbehavior_model->addNew($data);

			if ($insertID > 0) {
				echo json_encode(array("result"=>"success:$insertID"));
			} else {
				echo json_encode(array("result"=>"failed"));
			}
		}
	}
	
	private function calculateNewDuration($currentduration, $initialcreateddate) {
		$currentdatetime = strtotime(date('Y-m-d H:i:s'));
		$initialstart = strtotime($initialcreateddate);		
		$durationtoadd = round(abs($currentdatetime - $initialstart) / 60);
		$newduration = (int)$currentduration + (int)$durationtoadd;
		return $newduration;
	}
	
	private function buildData($model, $postData, $fields) {
		$ubFields = $model->_fields;
		
		foreach($fields as $key) {
			if ($postData[$key] != "") {
				$ubFields[$key] = $postData[$key];
			}
		}
		
		return array_filter($ubFields);
	}
}