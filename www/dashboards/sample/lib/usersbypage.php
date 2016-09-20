<?php

require_once('domain.php');

$name = isset($_REQUEST['name']) ? $_REQUEST['name'] : "";
$date = isset($_REQUEST['date']) ? $_REQUEST['date'] : "";

$postData = array(
	'pagename' => $name,
	'domain' => $site,
	'date' => $date
);

try {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://$domain/api/pageload/user_by_pagename/");
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$result = curl_exec($ch);
} catch (Exception $ex) {
    error_log($ex->getMessage);
    echo "<br>ERROR: " . $ex;
}

echo $result

?>