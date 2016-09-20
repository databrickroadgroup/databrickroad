<?php

require_once('domain.php');

$userEndPoint = "http://$domain/api/pageload/user_table/dbrid/";
$user = isset($_REQUEST['user']) ? $_REQUEST['user'] : "";

if($user != ""){
	try {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $userEndPoint . $user);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
	} catch (Exception $ex) {
	    error_log($ex->getMessage);
	    echo "<br>ERROR: " . $ex;
	}	
}

echo $result;

?>
