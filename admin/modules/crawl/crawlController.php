<?php

/**
 * Description of crawling
 * @author gangaraju
 */
class crawlController {

    public function indexAction() {
        global $fc;
        $data = array();
        // authentication
        if (empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE and $_SESSION['admintype'] != SUBADMIN_TYPE)) {
            header("Location:" . ADMIN_URL);
            exit;
        }

        $obj = new Photos();
        $object = new Users();
        $objectArticle = new Articles();
        $data['Ctype'] = $_REQUEST['Crawltype'];
        $data['Wsourceid'] = $_REQUEST['Crawltype'];
        $data['user'] = strtolower($_REQUEST['fanwirename']); //"lilwayne";
        //$data['fanwireid'] = $_REQUEST['websiteCrawlid'];
        $countPosts = $_REQUEST['count'];
        $data['countPost'] = $countPosts;
        $data['fanwires_sites'] = $obj->getFanSites("");
        $explodeUer = explode('*', $data['user']);
        $data['selectedfanwire'] = $explodeUer[0];
        if ($data['Ctype'] == "facebook") {
            foreach ($_REQUEST['fanwirenamef'] as $key => $value) {
                //if facebook get facebook name
                $data['user'] = $value; //$_REQUEST['fanwirename']; //"lilwayne";
                $data['user'] = explode('*', $data['user']);
                $data['fanwireid'] = $data['user'][4];
                $data['user'] = end(explode('/', $data['user'][1]));
                $data['profie_image'] = "https://graph.facebook.com/" . strtolower($data['user']) . "/picture";
                $graph_url = "https://graph.facebook.com/" . $data['user'] . "/posts?" . "access_token=" . $_COOKIE['accessToken'];
                $response = crawlController::curl_get_file_contents($graph_url);
                $decoded_response = json_decode($response);
                //echo "<pre>";print_r($decoded_response);exit;
                //Check for errors
                if ($decoded_response->error) {
                    // check to see if this is an oAuth error:
                    if ($decoded_response->error->type == "OAuthException") {
                        // Retrieving a valid access token.
                        $dialog_url = "https://www.facebook.com/dialog/oauth?" . "client_id=" . FB_APP_ID . "&redirect_uri=" . urlencode(ADMIN_URL . $_REQUEST['path']);
                        echo("<script> top.location.href='" . $dialog_url . "'</script>");
                    } else {
                        echo "other error has happened";
                    }
                } else {
                    echo $access_token;
                }
                $data['fbresult'] = $decoded_response->data;


                if ($data['fbresult']) {
                    //insert the data in database
                    $j = 0;
                    $resCheck = $objectArticle->checkStatus($data['fanwireid'], $data['Ctype']); //to check crawl status

                    if ($resCheck == 0) {
                        $objectArticle->insertStatus($data['fanwireid'], $data['Ctype']); //to insert crawl status
                    } else {
                        $objectArticle->updateStatus($data['fanwireid'], $data['Ctype']); //to update crawl status
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
                                            $img = file_get_contents(str_replace("_s", "_n", $list['imageUrl']));
                                            $file = DOC_ROOT_PATH . '/photos/' . $filename;
                                            file_put_contents($file, $img);
                                        } else {
                                            $filename = "";
                                        }
                                    } else {
                                        $filename = "";
                                    }
                                    $list['imagename'] = $filename;
                                    $ret[] = $list;
                                    $objectArticle->addPostsFromFaceBook($list);
                                } else {
                                    error_log("posts exists already");
                                }
                                $j++;
                            }
                        }
                    }
                }
            }

            $data['fbresult_face'] = $ret;
            // echo "<pre>";print_r($data['fbresult']);exit;
        } elseif ($data['Ctype'] == "twitter") {
            $p = 0;
            foreach ($_REQUEST['fanwirenamet'] as $key => $value) {
                //if twitter get twitter username
                $data['user'] = $value; //$_REQUEST['fanwirename']; //"lilwayne";
                $data['user'] = explode('*', $data['user']);
                $item['fanwireid'] = $data['user'][4];  //$_REQUEST['websiteCrawlid'];
                $data['user'] = end(explode('/', $data['user'][2]));
                $url = "https://api.twitter.com/1/statuses/user_timeline/" . $data['user'] . ".json?count=" . $countPosts;
                //  echo "https://twitter.com/statuses/user_timeline/" . $data['user'] . ".json?count=" . $countPosts;
                //exit;
                if ($_REQUEST['fanwirenamet']) {
                    $tw_res = crawlController::curl_get_file_contents($url);
                }

                $data['twitterRes'] = json_decode($tw_res);


                //insert the data in database
                //$checkTwitterTweet = $objectArticle->checkTweet('passthe string her');
                $j = 0;

                $resCheck = $objectArticle->checkStatus($item['fanwireid'], $data['Ctype']); //to check crawl status

                if ($resCheck == 0) {
                    $objectArticle->insertStatus($item['fanwireid'], $data['Ctype']); //to insert crawl status
                } else {
                    $objectArticle->updateStatus($item['fanwireid'], $data['Ctype']); //to update crawl status
                }

                foreach ($data['twitterRes'] as $i) {

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
                        $img = file_get_contents($list[$j]['imageUrl']);
                        $file = DOC_ROOT_PATH . '/photos/' . $filename;
                        file_put_contents($file, $img);
                        $list[$j]['imagename'] = $filename;

                        $objectArticle->addTweetsFromTwitter($list[$j]);
                    }
                    $j++;
                }
            }
            $data['twitterRes_twitt'] = $retT;
        } else if ($data['Wsourceid'] == "instagram") {
            if ($_REQUEST['count'] != "") {
                $count = $_REQUEST['count'];
            } else {
                $count = 5;
            }

            $data['user'] = $_REQUEST['fanwirenameI']; //strtolower($_REQUEST['fanwirenameI']); //"lilwayne";
            $auth_config = array(
                'client_id' => INST_CLIENT_ID,
                'client_secret' => INST_CLIENT_SECRET,
                'redirect_uri' => INST_REDIRECT_URI,
                'scope' => array('likes', 'comments', 'relationships')
            );
            $_SESSION['instagram_access_token'] = "13271228.a9e01a2.5f1c591fc6eb4611af986b0e7e906a15";
            // print_r($_COOKIE);exit;
            if ((!isset($_SESSION['instagram_access_token']))) { //echo EXAMPLES_DIR . '/call/_auth.php';
                require( INSTAGRAM_DIR . '/Examples/_auth.php' );
                exit;
            } else {


                if (isset($_COOKIE['inst_session'])) {
                    
                } else {
                    setcookie("inst_session", $_SESSION['instagram_access_token']);
                }
                //start loop for multiple fanwires
                foreach ($data['user'] as $key => $value) {
                    $compStr = explode('*', $value);
                    //echo "<pre>";print_r($compStr);exit;
                    //for checking the status that when the last carawl happend    starts
                    $resCheck = $objectArticle->checkStatus($compStr[4], $data['Ctype']); //to check crawl status
                    if ($resCheck == 0) {
                        $objectArticle->insertStatus($compStr[4], $data['Ctype']); //to insert crawl status
                    } else {
                        $objectArticle->updateStatus($compStr[4], $data['Ctype']); //to update crawl status
                    }
                    //for checking the status that when the last carawl happend    end
                    $profile_id_url = "https://api.instagram.com/v1/users/search?access_token=" . $_SESSION['instagram_access_token'] . "&count=1&q=" . $compStr[6];
                    $profile_id_content_array = json_decode(crawlController::curl_get_file_contents($profile_id_url));
                    $profiel_id = $profile_id_content_array->data[0]->id;
                    $profile_gallery_url = "https://api.instagram.com/v1/users/" . $profiel_id . "/media/recent?access_token=" . $_SESSION['instagram_access_token'] . "&count=" . $count;
                    $profile_gallery_array = json_decode(crawlController::curl_get_file_contents($profile_gallery_url));
                    if (!empty($profile_gallery_array->data)) {
                        foreach ($profile_gallery_array->data as $key => $image_info) {
                            $imges['fanwireid'] = $compStr[4];
                            $filename = end(explode('/', $image_info->images->standard_resolution->url)); //strtotime(date("Y-m-d H:i:s")) . md5(rand(1,989)) . "_instImg.jpg";
                            $img = file_get_contents($image_info->images->standard_resolution->url);
                            $file = DOC_ROOT_PATH . '/photos/' . $filename;
                            file_put_contents($file, $img);
                            $imges["actual_name"] = $image_info->images->standard_resolution->url;
                            $imges["src"] = $filename; //$image_info->images->standard_resolution->url; //low_resolution
                            $instName[] = $imges["src"];
                            $imges["albumname"] = $compStr[5];
                            $imges['link'] = $image_info->link;
                            $imges['acttime'] = $image_info->caption->created_time;
                            if ($imges['acttime'] == '') {
                                $imges['acttime'] = strtotime(date('Y-m-d H:i:s'));
                            }
                            $imges["datetime"] = date("Y-m-d H:i:s", $imges['acttime']);
                            $imges["caption"] = $image_info->caption->text; //$imges["caption"]=
                            $ret[] = $imges;
                        }
                        $obj->insertInstaPhotos($ret);
                        /* $res = $obj->checkInstaPhotos($instName);
                          if (empty($res)) {
                          $obj->insertInstaPhotos($ret);
                          } else {
                          $data['galError'] = "The Photo albums of this celebrity already exists";
                          } */
                    } else {
                        $data['galError'] = "No Images to Available for \"" . $compStr[0] . "\"";
                    }
                    $data['instaresults'] = $ret;
                    $data['Ctype'] = 'instagram';
                }
                //end loop here for multiple fanwires
            }
        } elseif ($data['Ctype'] == "youtube") {
            $data['countPost'] = "";
            $objectVedios = new Videos();
            //if youttube get youtube name
            $data['user'] = $_REQUEST['fanwirename']; //"lilwayne";
            $data['user'] = explode('*', $data['user']);
            $item['fanwireid'] = $data['user'][4]; //$_REQUEST['websiteCrawlid'];
            $data['user'] = end(explode('/', $data['user'][3]));
            $item['websiteCrawlname'] = $data['user'];

            // set URL for XML feed containing category list
            $catURL = 'http://gdata.youtube.com/schemas/2007/categories.cat';

            // retrieve category list using atom: namespace
            // note: you can cache this list to improve performance,
            // as it doesn't change very often!
            $cxml = simplexml_load_file($catURL);
            $cxml->registerXPathNamespace('atom', 'http://www.w3.org/2005/Atom');
            $categories = $cxml->xpath('//atom:category');

            // iterate over category list
            // foreach ($categories as $c) {
            // for each category
            // set feed URL
            if (isset($_REQUEST['crawlOnlyOne']) && $_REQUEST['crawlOnlyOne'] == 'on') {

                $i = 0;
                $channelname = $_REQUEST['videourl'];
                $pos = strpos($channelname, '?v=');

                if ($pos === false) {
                    $idURL = end(explode('/', $channelname));
                } else {
                    $vl = explode('&', end(explode('v=', $channelname)));
                    $idURL = $vl[0];
                }
                $feedURL = 'http://gdata.youtube.com/feeds/api/videos/' . $idURL;
                $entry = simplexml_load_file($feedURL);
                // iterate over entries in category
                // get nodes in media: namespace for media information

                $media = $entry->children('http://search.yahoo.com/mrss/');
                $attrs = $media->group->thumbnail[0]->attributes();
                $items[$i]['youtubeid'] = end(explode('/', $entry->id));
                //$items[$i]['video'] = substr($media->group->title, 0, 300);
                $items[$i]['video'] = str_replace('&', '--', $media->group->title); //substr($media->group->title, 0, 300);
                //  if (preg_match("/" . $item['websiteCrawlname'] . "/i", $items[$i]['video'])) {

                $items[$i]['description'] = substr($media->group->description, 0, 300);
                $items[$i]['keywords'] = substr($media->group->keywords, 0, 300);
                $items[$i]['released'] = substr($entry->published, 0, 30);
                $items[$i]['embedcode'] = "http://youtu.be/" . end(explode(':', $entry->id));

                $items[$i]['fanwireName'] = $item['fanwireid'];
                $items[$i]['iframe'] = $embedcode = YoutubeUrl::parse_youtube_url($items[$i]['embedcode'], 'embed', '', '', 0, 1);
                $items[$i]['thumbnail'] = trim($attrs['url']);
                $items[$i]['source'] = VDO_TYPE;
            } else {

                if (isset($_REQUEST['channelname_check']) && $_REQUEST['channelname_check'] == 'on') {
                    if ($_REQUEST['channelname'] == '') {
                        $data['error'] = "enter channel name.";
                        $data['Ctype'] = "youtube";
                    } else {
                        $channelname = $_REQUEST['channelname'];
                    }
                } else {
                    if (empty($item['websiteCrawlname']) || $item['websiteCrawlname'] == '') {
                        $data['error'] = "Fanwire Does'nt have youtube URL pleasse update.";
                        $data['Ctype'] = "youtube";
                    } else {
                        $channelname = $item['websiteCrawlname'];
                    }
                }
                $feedURL1 = 'http://gdata.youtube.com/feeds/api/videos?author=' . $channelname;
                //$feedURL1 = 'http://gdata.youtube.com/feeds/api/users/' . $channelname . '/uploads?orderby=updated';
                // read feed into SimpleXML object
                $sxml1 = simplexml_load_file($feedURL1);
                $counts = $sxml1->children('http://a9.com/-/spec/opensearchrss/1.0/');
                $total_records = $counts->totalResults;
                $start_index = $counts->startIndex;
                $max_results = 25;
                $a = 0;
                $p = 1; //$total_records / 25;
                $remaining = $total_records;
                $i = 0;
                for ($k = 0; $k <= $p; $k++) {
                    $feedURL = 'http://gdata.youtube.com/feeds/api/users/' . $channelname . '/uploads?orderby=updated&v=2&start-index=' . $start_index . '&max_results=' . $max_results;
                    $sxml = simplexml_load_file($feedURL);
                    // iterate over entries in category
                    // print each entry's details
                    foreach ($sxml->entry as $entry) {
                        if (empty($entry)) {
                            echo "No results found...";
                            exit;
                        }
                        $feedURL = 'http://gdata.youtube.com/feeds/api/videos/' . end(explode(':', $entry->id));
                        $items[$i]['youtubeid'] = end(explode(':', $entry->id));
                        $entry = simplexml_load_file($feedURL);
                        // get nodes in media: namespace for media information
                        $media = $entry->children('http://search.yahoo.com/mrss/');
                        $attrs = $media->group->thumbnail[0]->attributes();
                        //$items[$i]['video'] = substr($media->group->title, 0, 300);
                        $items[$i]['video'] = str_replace('&', '--', $media->group->title); //substr($media->group->title, 0, 300);
                        //  if (preg_match("/" . $item['websiteCrawlname'] . "/i", $items[$i]['video'])) {

                        $items[$i]['description'] = substr($media->group->description, 0, 300);
                        $items[$i]['keywords'] = substr($media->group->keywords, 0, 300);
                        $items[$i]['released'] = substr($entry->published, 0, 30);
                        $items[$i]['embedcode'] = "http://youtu.be/" . end(explode(':', $entry->id));

                        $items[$i]['fanwireName'] = $item['fanwireid'];
                        $items[$i]['iframe'] = $embedcode = YoutubeUrl::parse_youtube_url($items[$i]['embedcode'], 'embed', '', '', 0, 1);
                        $items[$i]['thumbnail'] = trim($attrs['url']);
                        $items[$i]['source'] = VDO_TYPE;
                        $i++;
                    }
                    $start_index += $max_results;
                }
            }
            $result = $objectVedios->insertVideosCrawl($items);

            $data['releaseVdoId'] = $result;


            $data['item'] = $items;
        } else if ($data['Wsourceid'] != "") {
            $data['countPost'] = "";
            $websitedet = $obj->getFanSites($data['Wsourceid']);
            $src = explode("*", $_REQUEST['fanwirename']);
            $data['request_url'] = $websitedet['source'];
            $data['keyword'] = $src[5];
            $data['fanwireid'] = $src[4];
            // error_log($fanwireid.'----------'.$data['keyword']);
            $item['searchkeyword'] = $data['keyword'];
            $item['source'] = $request_url;
            $data['source'] = $request_url;
            //            error_log(str_replace("###", str_replace(" ", $websitedet['search_seperator'], $data['keyword']), trim($websitedet['search_url']))); // exit;
            //            $html = crawlController::file_get_html(str_replace("###", str_replace(" ", $websitedet['search_seperator'], $data['keyword']), trim($websitedet['search_url'])));
            $data['classes'] = $obj->getSourceClasselements($data['Wsourceid']);
            $ret = crawlController::CrawlMainAction($data, "Crawl");
            $data['websiteresults'] = $ret;
            $data['Ctype'] = 'website';
        }
        $data['fanwires'] = $obj->getFanwires();
        $data['fanwires_twitter'] = $obj->getFanwires_network('twitter');
        $data['fanwires_facebook'] = $obj->getFanwires_network('facebook');
        $data['fanwires_instagram'] = $obj->getFanwiresInstagram('instagram');
        // echo' heolo<pre>';print_r($data['fanwires_instagram']);echo'</pre>';exit;
        $view = new View(); //Creating the object for the class View
        $view->loadView($data, ADMIN . 'crawl/Crawl'); //load view
    }

    /**
     * crawl data list
     * @global type $fc
     */
    public function crawlListAction() {
        global $fc;
        $data = array();
        // authentication
        if (empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE and $_SESSION['admintype'] != SUBADMIN_TYPE)) {
            header("Location:" . ADMIN_URL);
            exit;
        }
        $id = $_REQUEST['id'];
        $qObj = new Crawl();
        $list = $qObj->getCrawlList($id);
        //echo "<pre>";
        //print_r($list);exit();
        $data['list'] = $list['list'];
        $data['navigation'] = $list['navigation'];
        $data['ADMIN'] = ADMIN;
        $data['class'] = "pipe";
        $view = new View();
        $view->loadView($data, ADMIN . "crawl/crawllist");
    }

    /**
     * deleteArticle
     * @global object $fc
     *
     */
    public function deleteArticleAction() {
        global $fc;
        $data = array();
        // authentication
        if (empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE and $_SESSION['admintype'] != SUBADMIN_TYPE)) {
            header("Location:" . ADMIN_URL);
            exit;
        }
        $id = $_REQUEST['id'];
        $qObj = new Crawl();
        $qObj->deleteArticle($id);
        header("Location:" . ADMIN_URL . "crawl/crawllist");
    }

    /**
     * changeStatus
     * @global object $fc
     */
    public function changeStatusAction() {
        global $fc;
        $data = array();
        // authentication
        if (empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE and $_SESSION['admintype'] != SUBADMIN_TYPE)) {
            header("Location:" . ADMIN_URL);
            exit;
        }
        $id = $_REQUEST['id'];
        $refid = $_REQUEST['refid'];
        $fanwire_id = $_REQUEST['fanwire_id'];
        $status = $_REQUEST['status'];
        if ($status == '1') {
            $status = '0';
            $date = '0000-00-00 00:00:00';
        } else {
            $status = '1';
            $date = date("Y-m-d H:i");
        }
        $qObj = new Crawl();
        $qObj->changeStatus($id, $status, $date);
        header("Location:" . ADMIN_URL . "articles/articlesList?id=" . $fanwire_id . "#" . $refid);
    }

    /**
     * curl_get_file_contents
     * @param string $URL Url
     */
    public function curl_get_file_contents($URL) {
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

    public function file_get_html($url, $use_include_path = false, $context = null, $offset = -1, $maxLen = -1, $lowercase = true, $forceTagsClosed = true, $target_charset = DEFAULT_TARGET_CHARSET, $stripRN = true, $defaultBRText = DEFAULT_BR_TEXT) {
        // We DO force the tags to be terminated.
        $dom = new simple_html_dom(null, $lowercase, $forceTagsClosed, $target_charset, $defaultBRText);
        // For sourceforge users: uncomment the next line and comment the retreive_url_contents line 2 lines down if it is not already done.
        $contents = crawlController::curl_get_file_contents($url);
        // Paperg - use our own mechanism for getting the contents as we want to control the timeout.
        if (empty($contents)) {
            return false;
        }
        // The second parameter can force the selectors to all be lowercase.
        $dom->load($contents, $lowercase, $stripRN);
        return $dom;
    }

    /**
     * setCookie
     */
    public function setCookieAction() {
        setcookie($_REQUEST['ckname'], $_REQUEST['ckvalue']);
    }

    public function ManageCrawlAction() {
        //echo' heolo<pre>';print_r($data);echo'</pre>';exit;
        //echo ADMIN . 'index/AddCrawl'; exit;
        $obj = new Photos();


        //exit;
        if ($_POST['submit'] == "Save") {
            $ins = $obj->insertCrawlArticle($_POST);
            if ($ins) {
                $data['StatusMsg'] = "Inserted record Successfully";
            } else {
                $data['StatusMsg'] = "Insertion Faileed! Problem in inserting";
            }
        } else if ($_POST['submit'] == "Edit") {

            $Edit_rec = $obj->GetArticle($_POST['id']);
            //  echo"<pre>"; print_r($Edit_rec); echo'</pre>'; exit;
            $data['editResult'] = $Edit_rec;
            $data['act'] = "edit";
            $data['WId'] = $_POST['id'];
        } else if ($_POST['submit'] == "Update") {
            $Edit_rec = $obj->UpdateArticles($_POST);
            $data['act'] = "edit";
            $data['WId'] = $_POST['Wid'];
            $Edit_rec = $obj->GetArticle($_POST['Wid']);
            $data['editResult'] = $Edit_rec;
            $data['StatusMsg'] = "Updated Successfully";
        } else if ($_POST['submit'] == "Delete") {
            $del_rec = $obj->DeleteCrawlArticle($_POST['id']);
            $data['StatusMsg'] = "Deleted Successfully";
        } else if ($_POST['submit'] == "Test") {
            //echo"<pre>"; print_r($_REQUEST); echo'</pre>'; //exit;
            $data['request_url'] = $_REQUEST['websitename'];
            $data['keyword'] = $_REQUEST['fanwirename'];
            $item['searchkeyword'] = $data['keyword'];
            $data['classes'] = $_REQUEST;
            $data['act'] = "";
            //echo'<pre>'; print_r($data); exit;
            $key = 1;
            $item['source'] = $request_url;
            $ret = crawlController::CrawlMainAction($data, "test");
            // echo '<br/>'.$_REQUEST['search_seperator'];
            //   echo '<br/>'.$_REQUEST['search_url'];
            // echo str_replace("###", str_replace(" ", $_REQUEST['search_seperator'], $data['keyword']), trim($_REQUEST['search_url'])).'<br/>'; //exit;

            $view = new View();
            $data['websiteresults'] = $ret;
            ob_start();
            $view->loadView($data, ADMIN . 'crawl/TestCrawl'); //load view
            $var = ob_get_contents();
            ob_end_clean();
            echo $var;
            exit;
        }
        $data['fanwires'] = $obj->getFanwires();
        $data['loadclasses'] = $obj->getclassesInfo();
        $view = new View(); //Creating the object for the class View
        $view->loadView($data, ADMIN . 'index/AddCrawl'); //load view
    }

    public function CrawlMainAction($data, $type) {
        $obj = new Photos();
        $object = new Users();
        $objectArticle = new Articles();
        // echo str_replace("###", str_replace(" ", $data['classes']['search_seperator'], $data['keyword']), trim($data['classes']['search_url'])) . '<br/>'; //exit;
        //    echo"<pre>"; print_r($data['classes']); echo'</pre>'; exit;
        $html = crawlController::file_get_html(str_replace("###", str_replace(" ", $data['classes']['search_seperator'], $data['keyword']), trim($data['classes']['search_url'])));

        //  $classElements = $obj->getSourceClasselements($data['Wsourceid']);
        // echo $_REQUEST['search_list_repeat_element'] . '.' . $_REQUEST['search_list_repeat_class']; exit;

        if (($data['classes']['websitename'] != "")) {
            // echo $_REQUEST['search_list_repeat_element'] . '.' . $_REQUEST['search_list_repeat_class'];

            if ($data['classes']['search_list_repeat_element'] != "" && $data['classes']['searchlist_repeat_element_class'] == "") {
                $cls = $data['classes']['search_list_repeat_element'];
            } else {
                $cls = $data['classes']['search_list_repeat_element'] . '.' . $data['classes']['searchlist_repeat_element_class'];
            }
            //echo '<br/>' . $cls . '<br/>';
            //echo count($html->find($cls)); //exit;
            if (count($html->find($cls)) > 0) {
                foreach ($html->find($cls) as $key => $article) {
                    $item = array();
                    if ($key <= 10) {
                        $item['url'] = trim($article->find($data['classes']['searchlist_title_class'], 0)->href);
                        $item['tit'] = trim($article->find($data['classes']['searchlist_title_class'], 0)->plaintext);
                        $item['author'] = trim($article->find($data['classes']['searchlist_author_class'], 0)->plaintext);
                        $item['desc'] = trim($article->find('p', 0)->plaintext);
                        $item['date'] = trim($article->find($data['classes']['search_list_date_element'], 0)->plaintext);
                        $item['fanwireid'] = $data['fanwireid'];
                        if ($item['date'] == "") {
                            $item['date'] = trim($article->find('.' . $data['classes']['search_list_date_class'], 0)->plaintext);
                        }
                        preg_match('@^(?:http://)?([^/]+)@i', $data['request_url'], $matches);

                        $item['articleFrom'] = str_replace('www.', '', $matches[1]);
                        $item['source'] = $matches[0];
                        if ($data['classes']['searchlist_image_class'] != "") {
                            if ($data['classes']['searchlist_image_class_type'] == 0)
                                $sep_item = '.';
                            else
                                $sep_item = '#';
                            $cls = $data['classes']['searchlist_image_class_element'] . $sep_item . $data['classes']['searchlist_image_class'];
                            $item['image'] = trim($article->find($cls . ' img', 0)->src);
                            if ($data['Wsourceid'] == 6) {
                                $item['image'] = str_replace("width=70&height=53", "width=281&height=211", $item['image']);
                            }
                        }
                        //echo $data['request_url'];//exit;
                        // get last two segments of host name
                        $pos = strpos($item['url'], "ew.com");
                        if ($pos !== false) {
                            
                        } else {
                            preg_match('@^(?:http://)?([^/]+)@i', $data['request_url'], $matches);
                            $pos = strpos($item['url'], $matches[1]);
                            // print_r($matches);
                            if ($pos !== false) {
                                
                            } else {
                                $item['url'] = $data['request_url'] . ltrim($item['url'], '/');
                            }
                        }
                        //echo "<br/><br/>Item url  " . $item['url'];
                        //exit;
                        $html_sub = crawlController::file_get_html(trim($item['url']));
                        // echo"<pre>"; print_r($html_sub); echo'</pre>'; exit;
                        if (!empty($html_sub)) {

                            if ($data['classes']['description_content_class_type'] == 0)
                                $sep_item = '.';
                            else
                                $sep_item = '#';
                            // echo 'div' . $sep_item . $_REQUEST['description_div_class']; exit;
                            if ($data['classes']['description_div_class_element'] != "") {
                                $divelem = $data['classes']['description_div_class_element'];
                            } else {
                                $divelem = 'div';
                            }
                            $findelem = $divelem . $sep_item . $data['classes']['description_div_class']; //exit;
                            $res_sub = $html_sub->find($findelem);
                            //echo"<pre>"; print_r($res_sub); echo'</pre>'; exit;
                            if (empty($res_sub)) {
                                // echo ' <br/> Not Found Item Url not exists<br/>'; //exit;
                            } else {
                                foreach ($html_sub->find($findelem) as $key => $detail_artic) {

                                    $ite = "";
                                    if ($key == 0) {
                                        if ($data['classes']['description_image_class_type'] == 0)
                                            $sep_item = '.';
                                        else
                                            $sep_item = '#';

                                        // echo "<br/>".$item['getreviewImage'] = trim($detail_artic->find('div.' . $sep_item . $_REQUEST['description_image_class'] . ' img', 0)->src);exit;
                                        if ($item['image'] == '') {
                                            $item['getreviewImage'] = trim($detail_artic->find('div.' . $sep_item . $data['classes']['description_image_class'] . ' img', 0)->src);
                                        } else {
                                            $item['getreviewImage'] = $item['image'];
                                        }
                                        if ($item['author'] == '') {
                                            if ($item['author'] == "") {
                                                $item['getAuthor'] = trim($html_sub->find('div.' . $data['classes']['description_author_class'], 0)->plaintext);
                                            }
                                            if ($data['Wsourceid'] == 6) {
                                                $item['getAuthor'] = trim($html_sub->find('.' . $data['classes']['description_author_class'], 0)->plaintext);
                                            }
                                        } else {
                                            $item['getAuthor'] = $item['author'];
                                        }
                                        //echo $item['date'];exit;
                                        if ($item['date'] == "") {
                                            $item['getDate'] = trim($detail_artic->find('.' . $data['classes']['description_date_class'], 0)->plaintext);
                                        } else if ($item['date'] == "") {
                                            $item['getDate'] = trim($detail_artic->find('div.' . $data['classes']['description_date_class'], 0)->plaintext);
                                        } else {
                                            $item['getDate'] = trim($item['date']);
                                        }
                                        $ite = "";
                                        if (count($detail_artic->find('p')) > 0) {
                                            foreach ($detail_artic->find('p') as $pr) {
                                                $ite = $ite . $pr;
                                            }
                                        }
                                        $item['ItemDesc'] .= $ite; //strip_tags($ite, '<p><a><b><strong>');
                                    }
                                }
                                if ($type == "Crawl") {
                                    $checkArticleTitle = $objectArticle->checkArticle($item['tit'], 'website');
                                    if ($checkArticleTitle == 0) {
                                        $compStr = explode('*', $data['user']);
                                        $pos = strpos(strtolower($item['ItemDesc']), strtolower($compStr[5]));
                                        // var_dump($pos);
                                        if ($pos === false) {
                                            error_log('descriptuion not matched with the fanwire name');
                                            //   exit;
                                        } else {
                                            $img = file_get_contents($item['getreviewImage']);
                                            $filename = strtotime(date("Y-m-d H:i:s")) . rand(2, 900) . "_artImg.jpg"; //$username.'.jpg';
                                            if ($img) {
                                                $file = DOC_ROOT_PATH . '/photos/' . $filename;
                                                file_put_contents($file, $img);
                                                $item['getreviewImageL'] = $filename;
                                            } else {
                                                $item['getreviewImageL'] = "";
                                            }
                                            if ($item['ItemDesc'] != '') {
                                                $objectArticle->addArticleFromWebsite($item, 'website');
                                            }
                                        }
                                    } else {
                                        error_log("already this article exist");
                                    }
                                }
                            }
                        } else {
                            //echo'<br/>Emty found<br/>';
                            //  exit;
                        }

                        $ret[] = $item;
                    }
                }  // echo"<pre>"; print_r($ret); echo'</pre>'; exit;
            } else {
                //echo "<div class='errormsg'>Error in crawling</div>";
                //exit;
            }
        } else {
            //echo "<div class='errormsg'>website name Required.</div>";
            //exit;
        }

        return $ret;
    }

    public function releaseThisNowAction() {
        $id = $_REQUEST['id'];
        $vdoObject = new Videos();
        $vdoObject->releaseVideos($id);
    }

    public function biographyAction() {
        $obj = new CombineFeatures();
        if (isset($_REQUEST['submit']) && $_REQUEST['submit'] == "submit") {
            $det = explode("~", $_REQUEST['fanwire']);
            $result = $obj->chechBio($det[0]);
            if (!empty($result)) {
                $data['error'] = "Biography for this fanwire  is already exisist with our data base.";
            } else {
                $html = self::file_get_html('http://en.wikipedia.org/wiki/' . $det[1]);
                foreach ($html->find('div.mw-content-ltr') as $element) {
                    $data['id'] = $det[0];
                    $data['name'] = $det[1];
                    $boo = substr($element->find('img', 0)->src, $det[1]);

                    if ($boo) {
                        $image = $element->find('img', 0)->src;
                    } else {
                        $image = $element->find('img', 1)->src;
                    }
                    $data['image'] = $det[1] . md5(rand(1, 787)) . ".jpg"; //end(explode("/", trim($image, "//"))); //trim($image, "//");//

                    $test = StorageFactory::getObject();
                    $img = file_get_contents("http:" . $image);
                    $test->saveFileFromContents($img, 'photos/' . $data['image']);

                    // $filename = end(explode("/", trim($image, "//")));
                    /* $img = file_get_contents("http:" . $image); //get te image
                      $file = DOC_ROOT_PATH . '/photos/' . $filename;
                      file_put_contents($file, $img); //stroing an image
                     */

                    $biography = "";
                    $biography.="description**" . $element->find('p', 0)->plaintext . ":::";
                    $i = 0;
                    foreach ($html->find('tr') as $row) {
                        // if ($i <= 11) {
                        if ($row->find('td', 0)->plaintext != "" && $row->find('th', 0)->plaintext != "") {
                            $biography.=$row->find('th', 0)->plaintext . "**" . $row->find('td', 0)->plaintext . ":::";
                        }
                        if ($row->find('th', 0)->plaintext == 'Website') {
                            break;
                        }
                        //$i++;
                        // }
                    }
                    $data['bio'] = trim($biography, ':::');
                    $bo = explode(":::", $data['bio']);
                    foreach ($bo as $value) {
                        $rr = explode("**", $value);
                        $data[$i][$rr[0]] = $rr[1];
                    }
                }
            }
        }
        $data['list'] = $data;
        $result = $obj->insertBiography($data);
        $data['fanwires'] = $obj->getFanwires();
        $view = new View(); //Creating the object for the class View
        $view->loadView($data, ADMIN . 'crawl/biography'); //load view
    }

}

//end class
?>
