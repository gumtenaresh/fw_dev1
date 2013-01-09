<?php

include(realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config.inc.php'));

$obj = new Photos();
$rese = $obj->getfbaccessToken();
$object = new Users();
$objectArticle = new Articles();
$countPosts = "50"; //$_REQUEST['count'];
$res = $obj->getFanwires();
foreach ($res as $key => $value) {
    error_log("Called facebook crawl for " . $value['name'] . "on " . date("Y-m-d H:i:s"));
    //if facebook get facebook name
    $data['user'] = $value['facebook']; //$_REQUEST['fanwirename']; //"lilwayne";
    $data['fanwireid'] = $value['id'];
    // $graph_url = "https://graph.facebook.com/" . $data['user'] . "/posts?" . "access_token=" . $_COOKIE['accessToken'];
    $graph_url = "https://graph.facebook.com/" . $data['user'] . "/posts?" . "access_token=" . $rese[0]['accesstoken'];
    error_log("this is old generated====>" . $graph_url);
    $response = $objectArticle->curl_get_file_contents($graph_url);
    $decoded_response = json_decode($response);
    //Check for errors
    if ($decoded_response->error) {
        // check to see if this is an oAuth error:
        if ($decoded_response->error->type == "OAuthException") {
            $app_id = FB_APP_ID;
            $app_secret = FB_APP_SECRET;
            $graph_url = "https://graph.facebook.com/oauth/access_token?client_id=$app_id&client_secret=$app_secret&grant_type=client_credentials";
            $response = curl_get_file_contents($graph_url);
            $newaccesstoken = end(explode("=", $response));
            $graph_urlf = "https://graph.facebook.com/" . $data['user'] . "/posts?" . "access_token=" . $newaccesstoken;
            $obj->getfbaccessToken($newaccesstoken, 2);
            error_log("this is new ly generated====>" . $graph_urlf);
            $response = $objectArticle->curl_get_file_contents($graph_urlf);
            $decoded_response = json_decode($response);
        } else {
            echo "other error has happened";
        }
    }
    $data['fbresult'] = $decoded_response->data;
    if ($data['fbresult']) {
        //insert the data in database
        $j = 0;
        $Ctype = 'facebook';
        $resCheck = $objectArticle->checkStatus($data['fanwireid'], $Ctype); //to check crawl status

        if ($resCheck == 0) {
            $objectArticle->insertStatus($data['fanwireid'], $Ctype); //to insert crawl status
        } else {
            $objectArticle->updateStatus($data['fanwireid'], $Ctype); //to update crawl status
        }
        foreach ($data['fbresult'] as $i) {
            if (!isset($i->story)) {
                if ($j < $countPosts) {
                    $list = array();
                    $list['id'] = $data['fanwireid'];
                    $list['name'] = $i->from->name;
                    $list['message'] = $i->message; //tweet
                    $list['time'] = $i->created_time; //creation time of post
                    $list['imageUrl'] = $i->picture; //image url of post image
                    $filename = end(explode("/", $list['imageUrl'])); //strtotime(date("Y-m-d H:i:s")) . $list['name'] . $j . rand(8, 6666) . "_faceImg.jpg"; //$username.'.jpg';
                    //check the post is available or not
                    $checkArticleTitle = $objectArticle->checkFbTweet(mysql_escape_string(trim($list['message'])), 'facebook', $filename);
                    if ($checkArticleTitle == '0') {
                        if ($list['imageUrl'] != "") {
                            // && $i->caption == ''
                            if (!isset($i->source) && $i->caption == '') {

                                $amazonObject = StorageFactory::getObject();
                                //$img = file_get_contents($list['imageUrl']);
                                $img = file_get_contents(str_replace("_s", "_n", $list['imageUrl']));
                                $amazonObject->saveFileFromContents($img, "photos/" . $filename);
                                /*
                                  $img = file_get_contents(str_replace("_s", "_n", $list['imageUrl']));
                                  $file = DOC_ROOT_PATH.'/photos/' . $filename;
                                  file_put_contents($file, $img); */
                            } else {
                                $filename = "";
                            }
                        } else {
                            $filename = "";
                        }
                        $list['imagename'] = $filename;
                        $ret[] = $list;

                        $objectArticle->addPostsFromFaceBook($list);
                        //echo "<pre>";print_r($list);exit;
                    } else {
                        error_log("posts exists already");
                    }
                    $j++;
                }
            }
        }
    }
}
error_log("end crawling of facebook");

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
