<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include(realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config.inc.php'));
error_log("<br>Twitter crawl started at:" . date("m/d/Y H:i:s"));
$objectArticle = new Articles();
$objectFanwire = new Fanwires();
$ctype = "twitter";
$countPosts = 10;
$res = $objectFanwire->getAllFanwireIds();
foreach ($res as $val) {
    $user = $val['twitter'];
    $item['fanwireid'] = $val['id'];
    error_log("<br>Twitter crawl started for " . $val['twitter'] . " at:" . date("m/d/Y H:i:s"));
    if ($val['twitter'] != "") {
        $url = "https://api.twitter.com/1/statuses/user_timeline/" . $user . ".json?count=" . $countPosts;
        $tw_res = $objectArticle->curl_get_file_contents($url);
        $twitterRes = json_decode($tw_res);
        $j = 0;
        $resCheck = $objectArticle->checkStatus($item['fanwireid'], $ctype); //to check crawl status
        if ($resCheck == 0) {
            $objectArticle->insertStatus($item['fanwireid'], $ctype); //to insert crawl status
        } else {
            $objectArticle->updateStatus($item['fanwireid'], $ctype); //to update crawl status
        }

        foreach ($twitterRes as $i) {
            $list[$j] = array();
            $list[$j]['id'] = $item['fanwireid'];
            $list[$j]['tweet'] = $i->text; //tweet
            $list[$j]['time'] = $i->created_at; //creation time of tweet
            $list[$j]['imageUrl'] = str_replace('normal', 'reasonably_small', $i->user->profile_image_url); //image url of the tweeter
            $list[$j]['name'] = $i->user->name; //name of the tweeter
            $retT[] = $list[$j];
            $objectArticle->addTweetsFromTwitter($list);
            $checkArticleTitle = $objectArticle->checkFbTweet(mysql_escape_string(trim($list[$j]['tweet'])), 'twitter');
            if ($checkArticleTitle == '0') {
                $filename = strtotime(date("Y-m-d H:i:s")) . $list[$j]['name'] . $j . rand(1, 98989) . "_twtImg.jpg"; //$username.'.jpg';
                // $img = file_get_contents($list[$j]['imageUrl']);
                //$file = DOC_ROOT_PATH . '/photos/' . $filename;
                //file_put_contents($file, $img);
                $fs = StorageFactory::getObject();
                $img = file_get_contents($list[$j]['imageUrl']);
                $fs->saveFileFromContents($img, "photos/" . $filename);
                $list[$j]['imagename'] = $filename;

                $objectArticle->addTweetsFromTwitter($list[$j]);
            }
            $j++;
        }
    }
}
error_log("<br>Twitter crawl ended at:" . date("m/d/Y H:i:s"));
?>
