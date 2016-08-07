<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Pagescroll_model extends CI_Model
{
	protected $_table = "pagescroll";
	
	public $_fields = array(
		'created' => '',
		'user_guid' => '',
		'domain' => '',
		'page_name' => null,
		'page_url' => '',
		'referrer' => '',
		'page_position_code' => ''
	);
	
	function __construct() {
		$this->load->database();
		parent::__construct();
	}
	
	public function addNew($data) {		
		$this->db->insert($this->_table, $data);
		$insertID = $this->db->insert_id();
		return $insertID;
	}
	
	public function getByGuid($guid) {
		$this->db->where('user_guid', $guid);
		$query = $this->db->get($this->_table);
		
		$result = array();
		
		foreach ($query->result() as $row) {
			$result[$row->id] = array(
				'created' => $row->created,
				'user_guid' => $row->user_guid,
				'domain' => $row->domain,
				'page_name' => $row->page_name,
				'page_url' => $row->page_url,
				'referrer'=> $row->referrer,
				'page_position_code' => $row->page_position_code
			);
		}
		
		return $result;
	}
}