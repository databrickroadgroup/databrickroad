<?php

require_once('domain.php');

$mostVisitedEndPoint = "http://$domain/api/pageload/most_recent_conversions/domain/$site";
$limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : "100";

// add limit
$mostVisitedEndPoint .= "/limit/$limit";

try {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $mostVisitedEndPoint);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$result = curl_exec($ch);
} catch (Exception $ex) {
    error_log($ex->getMessage);
    echo "<br>ERROR: " . $ex;
}

echo $result;

?>
