<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Userbehavior_model extends CI_Model
{
	protected $_table = "userbehavior";
	
	public $_fields = array(
		'created' => '',
		'user_guid' => '',
		'start' => null,
		'end' => null,
		'duration' => '',
		'pageloadcount' => '',
		'uniquepagecount' => '',
		'lastvisitedpage' => ''
	);
	
	function __construct() {
		$this->load->database();
		parent::__construct();
	}
	
	public function getByGuid($guid) {
		$this->db->where('user_guid', $guid);
		$query = $this->db->get($this->_table);
		
		$result = array();
		
		foreach ($query->result() as $row) {
			$result[$row->id] = array(
				'created' => $row->created,
				'start' => $row->start,
				'end' => $row->end,
				'duration' => $row->duration,
				'pageloadcount'=> $row->pageloadcount,
				'uniquepagecount' => $row->uniquepagecount,
				'lastvisitedpage' => $row->lastvisitedpage
			);
		}
		
		return $result;
	}
	
	public function getLatestByGuid($guid) {
		$this->db->where('user_guid', $guid);
		$this->db->order_by('created','desc');
		$this->db->limit(1);
		$query = $this->db->get($this->_table);
		
		$result = array();
		
		foreach ($query->result() as $row) {
			$result[] = array(
				'id' => $row->id,
				'created' => $row->created,
				'start' => $row->start,
				'end' => $row->end,
				'duration' => $row->duration,
				'pageloadcount'=> $row->pageloadcount,
				'uniquepagecount' => $row->uniquepagecount,
				'lastvisitedpage' => $row->lastvisitedpage
			);
		}
		
		return $result;
	}
	
	public function addNew($data) {		
		$this->db->insert($this->_table, $data);
		$insertID = $this->db->insert_id();
		return $insertID;
	}
	
	public function update($id, $data) {
		$query = $this->db->update_string($this->_table, $data, "id = $id");
		$result = $this->db->query($query);
		return $result;
	}
}