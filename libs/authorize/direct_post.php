<?php
require_once 'anet_php_sdk/AuthorizeNet.php'; // The SDK
$url = "http://202.65.136.24/socash/authorize/direct_post.php";
$api_login_id = '4j9PW2h6';
$transaction_key = '6hF5225kMgR2Tcgq';
$md5_setting = '4j9PW2h6'; // Your MD5 Setting
$amount = "5.99";
AuthorizeNetDPM::directPostDemo($url, $api_login_id, $transaction_key, $amount, $md5_setting);
?>