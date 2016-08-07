<?php defined('BASEPATH') OR exit('No direct script access allowed');

class AccessControl {
	
	private $_domainList = array();
	
	public function __construct() {
	    $ci = &get_instance();
		$ci->config->load('config', TRUE);
		$values = $ci->config->item('config');
		$this->_domainList = $values['domain_list'];
	}
	
	public function getDomainList() {
		return $this->_domainList;
	}
	
	public function validDomain($domain) {
		return (in_array($domain, $this->_domainList)) ? true : false;
	}
	
	public function errorMessage() {
		return "Access Control Violation: Domain Not Permitted";
	}
}

?>