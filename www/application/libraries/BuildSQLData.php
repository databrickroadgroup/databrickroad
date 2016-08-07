<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class BuildSQLData {
	
	public function buildData($model, $postData, $fields) {
		$ubFields = $model->_fields;
		
		foreach($fields as $key) {
			if ($postData[$key] != "") {
				$ubFields[$key] = $postData[$key];
			}
		}
		
		return array_filter($ubFields);
	}
}

?>