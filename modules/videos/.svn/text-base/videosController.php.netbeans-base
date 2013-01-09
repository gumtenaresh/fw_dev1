<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of articlesController
 *
 * @author naresh
 */
class videosController {

    public function addVedioAction() {
        $object = new Users();
        $errorObject = new Errors(Error_XML);
        $data = array();
        if (isset($_SESSION['name'])) {
            $data['active'] = 'article';
            $userDetails = $object->getUserDetails($_SESSION['id']);
            //print_r($userDetails);
            $data['profile_image'] = SITE_URL . "profile_images/" . $userDetails[0]['profile_image'];
            $data['fanwires'] = $object->getMyFanwires();
            $data['fanwires_for_more'] = $object->getMyFanwiresMore();
            $data['notifications'] = $object->getNotifications($_SESSION['id']);
        } else {
            header("Location:" . SITE_URL);
        }
        $obj = new Fanwires();
        $obj->getHistory();
        $data['history'] = $_SESSION['history'];

        if (isset($_REQUEST['submit']) && $_REQUEST['submit'] == 'next') {
            extract($_REQUEST);
            $videoname = $_FILES['video']['name'];
            $fileUtils = new FileUtils();
            if ($videoname != "") {
                $videoname = $fileUtils->saveUploadedFile("video", DOC_ROOT_PATH . "/videos/");
                $type = "video";
            } else {
                $videoname = $embedcode;
                $var = explode('<', $embedcode);
                $var2 = explode('"', $var['1']);
                $thumbnail = YoutubeUrl::parse_youtube_url($var2['5'], 'hqthumb', '', '', 0, 1);
                $type = "embed";
            }
            //$thumbnail= $fileUtils->saveUploadedFile("thumbnail",DOC_ROOT_PATH."/photos/");
            if (isset($thumbnail)) {
                $filename = strtotime(date("Y-m-d H:i:s")) . "_vdoImg.jpg";
                $img = file_get_contents($item['getreviewImage']);
                if ($img) {
                    $file = DOC_ROOT_PATH . '/photos/' . $filename;
                    file_put_contents($file, $img);
                    $thumbnail = $filename;
                }
            }
            else
                $thumbnail = "default.jpg";
            $obj = new Fanwires();
            $video_id = $obj->uploadVideos($title, $description, $videoname, $thumbnail, $type, $_SESSION['id']);
            $_SESSION['video_id'] = $video_id;
            header("Location:" . SITE_URL . "videos/organize");
        }

        $view = new View();
        $view->loadView($data, 'videos/my_fanwire_add_new_videos');
    }

    public function organizeAction() {
        $object = new Users();
        $errorObject = new Errors(Error_XML);
        $data = array();
        $obj = new Fanwires();
        $objectVideo = new Videos();
        if (isset($_SESSION['name'])) {
            $data['active'] = 'article';
            $userDetails = $object->getUserDetails($_SESSION['id']);
            //print_r($userDetails);
            $data['profile_image'] = SITE_URL . "profile_images/" . $userDetails[0]['profile_image'];
            $data['fanwires'] = $object->getMyFanwires();
            $data['fanwires_for_more'] = $object->getMyFanwiresMore();
            $data['notifications'] = $object->getNotifications($_SESSION['id']);
            if (isset($_REQUEST['submit']) && $_REQUEST['submit'] == 'publish') {
                $details = $_REQUEST;

                $objectVideo->addVideos($details);
                header("Location:" . SITE_URL . 'myVedios');
            }
        } else {
            header("Location:" . SITE_URL);
        }
        $obj->getHistory();
        $data['fanwires'] = $object->getMyFanwires();
        $data['history'] = $_SESSION['history'];
        $view = new View();
        $view->loadView($data, 'myVideos');
    }

    public function publishAction() {
        $object = new Users();
        $errorObject = new Errors(Error_XML);
        $data = array();
        if (isset($_SESSION['name'])) {
            $data['active'] = 'article';
            $userDetails = $object->getUserDetails($_SESSION['id']);
            //print_r($userDetails);
            $data['profile_image'] = SITE_URL . "profile_images/" . $userDetails[0]['profile_image'];
            $data['fanwires'] = $object->getMyFanwires();
            $data['fanwires_for_more'] = $object->getMyFanwiresMore();
            $data['notifications'] = $object->getNotifications($_SESSION['id']);
        } else {
            header("Location:" . SITE_URL);
        }
        $obj = new Fanwires();
        $video_id = $_SESSION['video_id'];
        $data['list'] = $obj->getVideoDetails($video_id);
        //  print_r($vid_det);
        if (isset($_REQUEST['submit']) && $_REQUEST['submit'] == "publish") {
            $user_id = $_SESSION['id'];
            $myoptions = $_REQUEST['myoptions'];

            // print_r($myoptions);
            foreach ($myoptions as $val) {
                if ($val == 'fan') {
                    $obj->updateFanNetwork($user_id, $video_id, $type = "video");
                }
            }
            header("Location:" . SITE_URL . "myFanwire");
        }

        $obj->getHistory();
        $data['history'] = $_SESSION['history'];
        $view = new View();
        $view->loadView($data, 'videos/my_fanwire_organise_publish');
    }

    public function videosAction() {
        /* if (!isset($_SESSION['name'])) {
          header("Location:" . SITE_URL);
          } */
        $object = new Users(); //Creating the object for the class Users
        $objectVideos = new Videos();
        $errorObject = new Errors(Error_XML); //Creating the object for the class Errors
        $obj = new Fanwires(); //Creating the object for the class Fanwires
        global $fc;
        $data = array();
        $dpObj = new DatePicker(); //creating object for DatePicker
        $data['months'] = $dpObj->getMonths(); //get all months
        $data['days'] = $dpObj->getDays(); //get days
        $data['years'] = $dpObj->getYears(); //get years
        $data['fanwires_for_moreguest'] = $object->getMyFanwiresGuest(); //for guest login

        if (isset($_GET['fwrid'])) {
            if (isset($_GET['fwrid'])) {
                $data['fwrid'] = $fwrid = $_GET['fwrid'];
                $data['ac'] = 1;
            } else {
                $arr = explode("=", $fc->extra);
                $data['fwrid'] = $fwrid = $arr[1];
                $data['ac'] = 1;
            }
            $sta = 2;
            $data['active'] = 'fanwire';
            //n this case data['fanwire'] is consiting of all fanwires details
            $data['fanwires'] = $object->getFanwireDetails($fwrid); //fanwires details
            $data['vedios'] = $objectVideos->getVideos($fwrid, $sta); //first parameter is id to whoome it belongs and second parameter is to whoome it belongs either to user or fanwire
        }
        if (isset($_GET['uid'])) {
            if (isset($_GET['uid'])) {
                $data['uid'] = $uid = $_GET['uid'];
                $data['ac'] = 4;
            } else {
                $arr = explode("=", $fc->extra);
                $data['uid'] = $uid = $arr[1];
                $data['ac'] = 4;
            }
            $sta = 1;
            $data['active'] = 'fanwire';
            //in this case data['fanwires'] is consiting of alll fan details
            $data['userDetails'] = $object->getUserDetails($uid); //fan details
            $data['vedios'] = $objectVideos->getVideos($uid, $sta); //first parameter is id to whoome it belongs and second parameter is to whoome it belongs either to user or fanwire
            //echo "<pre>";print_r($data['userDetails']);
        }
        $obj->getHistory();
        $data['history'] = $_SESSION['history'];
        $data['active'] = 'fanwire';
        $userDetails = $object->getUserDetails($_SESSION['id']);
        if ($userDetails[0]['profile_image'] != "")
            $data['profile_image'] = SITE_URL . "profile_images/" . $userDetails[0]['profile_image'];
        else
            $data['profile_image'] = "";
        $data['fanwires_for_more'] = $object->getMyFanwiresMore();
        $data['notifications'] = $object->getNotifications($_SESSION['id']);
        //get the zoom values
        $data['zoomValues'] = $obj->saveZoomValues('vidos', $_SESSION['id'], '', '', '');
        //echo "<pre>";print_r($data['vedios']);
        $view = new View(); //Creating the object for the class View
        if (isset($_SESSION['name'])) {

            $view->loadView($data, 'videos/thumbVideos');
        } else {
            $view->loadView($data, 'videos/thumbVideosGuest');
        }
    }

    public function viewVideosAction() {
        global $fc;
        if (isset($_GET['vid'])) {
            $aid = $_GET['vid']; //get article id
        } else {
            $arr = explode("=", $fc->extra);
            $aid = $arr[1];
        }

        $data = array();
        $object = new Users(); //Creating the object for the class Users
        $objectVideos = new Videos();
        $errorObject = new Errors(Error_XML); //Creating the object for the class Errors
        $obj = new Fanwires(); //Creating the object for the class Fanwires

        $objectArticles = new Articles();
        $objectPhotos = new Photos();
        $dpObj = new DatePicker(); //creating object for DatePicker
        $testObj = new CombineFeatures();
        $data['months'] = $dpObj->getMonths(); //get all months
        $data['days'] = $dpObj->getDays(); //get days
        $data['years'] = $dpObj->getYears(); //get years
        $obj->getHistory();
        $data['active'] = 'fanwire';
        $data['history'] = $_SESSION['history']; //to store the history
        $userDetails = $object->getUserDetails($_SESSION['id']);
        if ($userDetails[0]['profile_image'] != "")
            $data['profile_image'] = SITE_URL . "profile_images/" . $userDetails[0]['profile_image'];
        else
            $data['profile_image'] = SITE_URL . "views/images/logodef.png";
        //  $data['fanwires_for_more'] = $object->getMyFanwiresMore();
        $data['fanwires_for_more'] = $obj->getMasterDetailsMore();

        $data['videoDetail'] = $objectVideos->getVideoDetails($aid); //get the video details
        //echo "<pre>";print_r($data['videoDetail']);
        $var = explode('<', $data['videoDetail'][0]['video']);
        $var2 = explode('"', $var['1']);

        $data['aid'] = $aid;
        $data['videoNew'] = $var2['5'];
        $data['fanwires'] = $object->getFanwireDetails($data['videoDetail'][0]['whomItBelongsTo']); //fanwires details
        $data['twitterPosts'] = $objectArticles->getTwitterList($data['videoDetail'][0]['whomItBelongsTo']); //get twitter posts related to that particular celebrity
        $data['otherNewsAboutFanwire'] = $obj->getOtherNewsAboutFanwire($data['videoDetail'][0]['whomItBelongsTo']); //other news about this fanwire        
        $data['popularVideos'] = $testObj->getPopularVideos($data['videoDetail'][0]['whomItBelongsTo']);
        $data['similarphotos'] = $objectPhotos->getSimilarPhotos($data['videoDetail'][0]['whomItBelongsTo'], $aid); //get the album details

        $data['notifications'] = $object->getNotifications($_SESSION['id']); //to get the any notifications
        $data['moreFanwires'] = $object->getMyFanwiresMore(); //already user fan of these fanwires
        $data['fanwires_for_moreguest'] = $object->getMyFanwiresGuest();

        $artObject = new Articles();
        $data['list'] = $artObject->getComments($aid, VDO_TYPE);
        $data['username'] = $_SESSION['name'];

        $data['boigraphy'] = $testObj->getBio();
        $data['relatedVediosNames'] = $artObject->getRelatedVideosNames(mysql_escape_string($data['videoDetail']['0']['title'])); //getting related articles names        
        $arr = array();
        foreach ($data['moreFanwires'] as $key => $value) {
            $arr[] = $value['id'];
        }
        $ids = implode(',', $arr);
        $data['pagetitle'] = $data['fanwires']['name'] . ' | ' . $data['videoDetail'][0]['title'];
        $data['metadescription'] = $data['fanwires']['name'] . ' video titled ' . str_replace('"', '', $data['videoDetail'][0]['title']) . ' Find all of the latest ' . $data['fanwires']['name'] . ' videos, photos, and news on FanWire';
        $view = new View(); //Creating the object for the class View
        if (isset($_SESSION['name'])) {
            $data['fanwiresMore'] = $object->getFanwiresNotIn($ids); //these fanwires which user not fan
            $view->loadView($data, 'videos/videoDetails');
        } else {
            $data['fanwiresMore'] = $object->getMyFanwiresGuest(); //these fanwires which user not fan
            $view->loadView($data, 'videos/videoDetailsGuest');
        }
    }

    //code by rak for edit photo on 08/06/12

    public function editVideosAction() {
        if (!isset($_SESSION['name'])) {
            header("Location:" . SITE_URL);
        }

        $aid = $_GET['aid']; //get article id
        $object = new Users(); //Creating the object for the class Users
        $objectPhotos = new Photos();
        $errorObject = new Errors(Error_XML); //Creating the object for the class Errors
        $obj = new Fanwires(); //Creating the object for the class Fanwires
        $data = array();
        $obj->getHistory();
        $data['active'] = 'fanwire';
        $data['history'] = $_SESSION['history']; //to store the history
        $userDetails = $object->getUserDetails($_SESSION['id']);
        if ($userDetails[0]['profile_image'] != "")
            $data['profile_image'] = SITE_URL . "profile_images/" . $userDetails[0]['profile_image'];
        else
            $data['profile_image'] = "";
        $data['fanwires'] = $object->getFanwireDetails($fwrid); //fanwires details
        $data['fanwires_for_more'] = $object->getMyFanwiresMore();
        $data['albumDetail'] = $objectPhotos->getAlbumDetails($aid); //get the article details
        //echo "<pre>";print_r($data['albumDetail']);
        $data['notifications'] = $object->getNotifications($_SESSION['id']); //to get the any notifications
        $data['moreFanwires'] = $object->getMyFanwiresMore(); //already user fan of these fanwires
        $arr = array();
        foreach ($data['moreFanwires'] as $key => $value) {
            $arr[] = $value['id'];
        }
        $ids = implode(',', $arr);
        $data['fanwiresMore'] = $object->getFanwiresNotIn($ids); //these fanwires which user not fan
        if (isset($_SESSION['name'])) {
            $view = new View(); //Creating the object for the class View
            $view->loadView($data, 'videos/edit_video');
        }
    }

    public function ajaxVideosAction() {
        $obj = new CombineFeatures();
        $result = $obj->getPopularVideos($_GET['fanwireid']);
        $str = "<a href='" . $result['list'][0]['title_link'] . "'><img src =\" " . IMAGE_URL . $result['list'][0]['photo'] . "\" height = \"79\" width = \"120\" /></a>
                <a href='" . $result['list'][1]['title_link'] . "'><img src =\"" . IMAGE_URL . $result['list'][1]['photo'] . "\" height = \"79\" width = \"120\" /></a>
                <a href='" . $result['list'][2]['title_link'] . "'><img src = \"" . IMAGE_URL . $result['list'][2]['photo'] . "\" height = \"79\" width = \"120\" /></a>
                <a href='" . $result['list'][3]['title_link'] . "'><img src = \"" . IMAGE_URL . $result['list'][3]['photo'] . "\" height = \"79\" width = \"120\" /></a>
                <a href='" . $result['list'][4]['title_link'] . "'><img src = \"" . IMAGE_URL . $result['list'][4]['photo'] . "\" height = \"79\" width = \"120\" /></a>
                <a href='" . $result['list'][5]['title_link'] . "'><img src = \"" . IMAGE_URL . $result['list'][5]['photo'] . "\" height = \"79\" width = \"120\" /></a>";
        $str.= ":::" . $result['navigation'];
        echo $str;
    }

}

?>
