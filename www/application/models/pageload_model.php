<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Pageload_model extends CI_Model
{
	protected $_table = "pageload";
	
	public $_fields = array(
		'created' => '',
		'user_guid' => '',
		'domain' => '',
		'page_name' => null,
		'page_url' => '',
		'referrer' => ''
	);
	
	function __construct() {
		$this->load->database();
		parent::__construct();
	}
	
	public function getAllPageLoads() {
		
		$query = $this->db->query("SELECT * FROM " . $this->_table);

		$result = array();

		foreach ($query->result() as $row) {
			$result[$row->id] = array(
				'created' => $row->created,
				'user_guid' => $row->user_guid,
				'domain' => $row->domain,
				'page_name' => $row->page_name,
				'page_url' => $row->page_url,
				'referrer'=> $row->referrer
			);
		}
		
		return $result;
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
				'referrer'=> $row->referrer
			);
		}
		
		return $result;
	}
	
	public function getByGuidTable($guid) {
		$this->db->where('user_guid', $guid);
		$query = $this->db->get($this->_table);
		
		$result = array();
		
		foreach ($query->result() as $row) {
			$result[] = array(
				$row->created,
				$row->user_guid,
				$row->domain,
				$row->page_name,
				$row->page_url,
				$row->referrer
			);
		}
		
		return $result;
	}
	
	public function getByGuidDomainAndPage($guid, $domain, $page_name) {
		$this->db->where('user_guid', $guid);
		$this->db->where('domain', $domain);
		$this->db->where('page_name', $page_name);
		$this->db->limit(1);
		$query = $this->db->get($this->_table);
		
		$result = array();
		
		foreach ($query->result() as $row) {
			$result[$row->id] = array(
				'created' => $row->created,
				'user_guid' => $row->user_guid,
				'domain' => $row->domain,
				'page_name' => $row->page_name,
				'page_url' => $row->page_url,
				'referrer'=> $row->referrer
			);
		}
		
		return $result;
	}
	
	public function addNew($data) {
		$this->db->insert($this->_table, $data);
		$insertID = $this->db->insert_id();
		return $insertID;
	}
	
	public function getTopPageLoadsByDomain($domain, $fromdate=null, $todate=null, $limit=10) {
		$table = $this->_table;
		$dateclause = "";
		
		if ($fromdate) {
			$dateclause .= "and created > '$fromdate'";
			
			if ($todate) {
				$dateclause .= " and created < '$todate'";
			}
		}
		
		$query = $this->db->query("
		select page_name, count(*) as page_name_count
			from $table
			where domain = '$domain'
			$dateclause
			group by page_name
			order by page_name_count desc
			limit $limit
		");
		
		//echo $this->db->last_query();

		$result = array();
		
		foreach ($query->result() as $row) {
			$result[] = array($row->page_name, $row->page_name_count);
		}
		
		return $result;
	}
	
	public function getUsersByPageName($domain, $page_name, $date) {		
		$this->db->where('domain', $domain);
		$this->db->where('page_name', $page_name);
		
		if ($date != '' || $date != null) {
			$this->db->like('created', $date);
		}
		
		$query = $this->db->get($this->_table);
		
		//echo $this->db->last_query();
		
		$result = array();
		
		foreach ($query->result() as $row) {
			$referrer = ($row->referrer == null) ? "" : $row->referrer;
			$result[] = array($row->created, $row->user_guid, $referrer);
		}
		
		return $result;
	}
}