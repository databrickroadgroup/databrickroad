<?php

$postData = array(
	'created' => date('Y-m-d H:i:s'),
	'domain' => 'test.local',
	'user_guid' => 'testguid',
	'page_name' => '/testing.html',
	'page_url' => 'http://testing/testing.html',
	'referrer'=> 'schmoogle.com'
);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://databrickroad.local/api/pageload/user/");
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
$result = curl_exec($ch);
print_r($result);
curl_close($ch);

// $postData = array(
// 	'created' => date('Y-m-d H:i:s'),
// 	'domain' => 'test.local',
// 	'user_guid' => 'testguid',
// 	'page_name' => '/testing.html',
// 	'page_url' => 'http://testing/testing.html',
// 	'referrer'=> 'schmoogle.com',
// 	'page_position_code' => '1/2'
// );
//
// $ch = curl_init();
// curl_setopt($ch, CURLOPT_URL, "http://databrickroad.local/api/pagescroll/user/");
// curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
// $result = curl_exec($ch);
// print_r($result);
// curl_close($ch);

// $postData = array(
// 	'created' => date('Y-m-d H:i:s'),
// 	'user_guid' => '5331dc3e18e53',
// 	'domain' => 'test.local',
// 	'page_name' => '%2Fdatabrickroadtest.html',
// 	'lastvisitedpage' => 'test.local/databrickroadtest.html',
// 	'start' => date('Y-m-d H:i:s'),
// 	'end' => '',
// 	'duration' => 0,
// 	'pageloadcount' => 1,
// 	'uniquepagecount' => 1
// );
//
// $ch = curl_init();
// curl_setopt($ch, CURLOPT_URL, "http://databrickroad.local/api/userbehavior/user");
// curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
// $result = curl_exec($ch);
// print_r($result);
// curl_close($ch);

?>
