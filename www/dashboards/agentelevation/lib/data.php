<?php

require_once('domain.php');

$mostVisitedEndPoint = "$domain/api/pageload/most_visited/domain/$site";

$yesterday = isset($_REQUEST['yesterday']) ? $_REQUEST['yesterday'] : "";
$today = isset($_REQUEST['today']) ? $_REQUEST['today'] : "";
$limit = isset($_REQUEST['limit']) ? $_REQUEST['limit'] : "5";

// build querystring
if ($yesterday) {
	$mostVisitedEndPoint .= "/from/$yesterday";
}

if ($today) {
	$mostVisitedEndPoint .= "/to/$today";
}

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
