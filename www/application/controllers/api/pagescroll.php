<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';

class Pagescroll extends REST_Controller
{
	public function users_get() {
		$data = array();
		$this->response($data, 200);
	}
	
	public function user_get($dbrid) {
		
		if(!$this->get('dbrid')) {
        	$this->response(array("failed"=>"Need Data Brick User ID"), 400);
        } else {
			$guid = $this->get('dbrid');
		}

		$this->load->model('Pagescroll_model');
		$pagescrolls = $this->Pagescroll_model->getByGuid($guid);
		
		$data = array('pagescroll' => $pagescrolls);
					
		$this->response($data, 200);	
	}
	
	public function user_post() {
		
		$this->load->library('BuildSQLData');
		$this->load->library('AccessControl');
		if (!$this->accesscontrol->validDomain($this->post('domain'))) {
			$message = $this->accesscontrol->errorMessage();
			exit($message);
		}
		
		$this->load->model('Pagescroll_model');
		$postData = $this->post();
		
		$postData['created'] = date('Y-m-d H:i:s');
		$fields = array('created','user_guid','domain','page_name','page_url','referrer','page_position_code');
		$data = $this->buildsqldata->buildData($this->Pagescroll_model, $postData, $fields);		
		$insertID = $this->Pagescroll_model->addNew($data);
		
		if ($insertID > 0) {	
			echo json_encode(array("databrickuser"=>$data['user_guid'],"result"=>"pagescroll:$insertID"));
		} else {
			echo json_encode(array("result"=>"failed"));
		}
	}
}

?>