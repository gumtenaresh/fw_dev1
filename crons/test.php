<?php

include(realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config.inc.php'));

$app_id = FB_APP_ID;
$app_secret = FB_APP_SECRET;
 
$graph_url = "https://graph.facebook.com/oauth/access_token?client_id=$app_id&client_secret=$app_secret&grant_type=client_credentials";
$response = curl_get_file_contents($graph_url);
echo end(explode("=",$response));
print_r($response);
function curl_get_file_contents($URL) {
    $c = curl_init();
    curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($c, CURLOPT_URL, $URL);
    $contents = curl_exec($c);
    $err = curl_getinfo($c, CURLINFO_HTTP_CODE);
    curl_close($c);
    if ($contents)
        return $contents;
    else
        return FALSE;
}
?>
 
