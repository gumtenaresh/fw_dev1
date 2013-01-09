<?php
require_once 'Instagram.php';
include(realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config.inc.php')); 
/**
 * Configuration params, make sure to write exactly the ones
 * instagram provide you at http://instagr.am/developer/
 */
$config = array(
    'client_id' => 'a9e01a220a7d4f2bac8776c8f7eadf5f',
    'client_secret' => '9a520dba987e48fa99195a6055c140e4',
    'grant_type' => 'authorization_code',
    'redirect_uri' => 'http://localhost/fanwire/crons/callback.php'
);
// Instantiate the API handler object
error_log("before object");
$instagram = new Instagram($config);
error_log("after object");
$conn = new Photos();
$res = $conn->getaccessToken();
if (!empty($res)) {
    $_SESSION['InstagramAccessToken'] = $res[0]['accesstoken']; // "13271228.a9e01a2.5f1c591fc6eb4611af986b0e7e906a15";   
} else {
    $accessToken = $instagram->getAccessToken();
    $_SESSION['InstagramAccessToken'] = $accessToken;
    $conn->getaccessToken($_SESSION['InstagramAccessToken']);
}
$instagram->setAccessToken($_SESSION['InstagramAccessToken']);
$res = $conn->getFanwiresInstagram();
error_log("stateedasdfsdfasdf");
foreach ($res as $key => $value) {
    error_log("Called instagram crawl for " . $value['name'] . "on " . date("Y-m-d H:i:s"));
    $popular = $instagram->searchUser($value['instagram']);
    // After getting the response, let's iterate the payload
    $response = json_decode($popular, true);
    $profile_gallery_array = json_decode($instagram->getUserRecent($response['data'][0]['id']));
    if (!empty($profile_gallery_array->data)) {
        foreach ($profile_gallery_array->data as $key => $image_info) {
            $imges['fanwireid'] = $value['id']; //$compStr[4];
            $filename = end(explode('/', $image_info->images->standard_resolution->url)); //strtotime(date("Y-m-d H:i:s")) . md5(rand(1,989)) . "_instImg.jpg";  
            $img = file_get_contents($image_info->images->standard_resolution->url);
            $file = DOC_ROOT_PATH . '/photos/' . $filename;
            file_put_contents($file, $img);
            $imges["actual_name"] = $image_info->images->standard_resolution->url;
            $imges["src"] = $filename; //$image_info->images->standard_resolution->url; //low_resolution
            $instName[] = $imges["src"];
            $imges['link'] = $image_info->link;
            $imges['acttime'] = $image_info->caption->created_time;
            if ($imges['acttime'] == '') {
                $imges['acttime'] = strtotime(date('Y-m-d H:i:s'));
            }
            $imges["datetime"] = date("Y-m-d H:i:s", $imges['acttime']);
            $imges["caption"] = $image_info->caption->text; //$imges["caption"]=
            $ret[] = $imges;
        }
        $conn->insertInstaPhotos($ret);
    } else {
        error_log("This error from instagram crawl" . date("Y-m-d H:i:s"));
    }
}
?>
 

