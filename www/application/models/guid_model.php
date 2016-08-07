<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Guid_model extends CI_Model
{
	function __construct() {
		parent::__construct();
	}
	
	public function generate_Guid() {
		return uniqid();
	}
}