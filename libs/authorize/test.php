<?php
require_once 'anet_php_sdk/AuthorizeNet.php'; // The SDK
$url = "http://202.65.136.24/direct_post.php";
$api_login_id = 'YOUR_API_LOGIN_ID';
$transaction_key = 'YOUR_TRANSACTION_KEY';
$md5_setting = 'YOUR_API_LOGIN_ID'; // Your MD5 Setting
$amount = "5.99";
AuthorizeNetDPM::directPostDemo($url, $api_login_id, $transaction_key, $amount, $md5_setting);
?>