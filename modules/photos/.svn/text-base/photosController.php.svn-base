<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of itemController
 *
 * @author naresh
 */
class photosController {

    /**
     * Profile screen
     * This will be shown after user logged in

     * @global  $fc
     */
    public function addPhotosAction() {

        $object = new Users();
        $errorObject = new Errors(Error_XML);
        $data = array();
        if (isset($_SESSION['name'])) {
            $userDetails = $object->getUserDetails($_SESSION['id']);
            $data['profile_image'] = SITE_URL . "profile_images/" . $userDetails[0]['profile_image'];
            $data['active'] = 'article';
            $data['fanwires'] = $object->getMyFanwires();
            $data['fanwires_for_more'] = $object->getMyFanwiresMore();
            $data['notifications'] = $object->getNotifications($_SESSION['id']);
        } else {
            header("Location:" . SITE_URL);
        }
        $obj = new Fanwires();
        $obj->getHistory();
        $data['history'] = $_SESSION['history'];
        $view = new View();
        $view->loadView($data, 'photos/addPhotos');
    }

    /**
     * upload photos and display
     */
    public function ajaxImageAction() {
        // $fileUtils = new FileUtils();
        //$photoname = $fileUtils->saveUploadedFile("photoimg", DOC_ROOT_PATH . "/tmp/");
        //  rename("tmp/" . $photoname, "tmp/" . $_SESSION['id'] . "_" . $photoname);

        if ($handle = opendir('tmp/')) {
            $dir_array = array();
            if ($_REQUEST['can'] == '1')
                @unlink("tmp/" . $_REQUEST['flname']);
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != ".." && preg_match("/^" . $_SESSION['id'] . "_/", $file)) {

                    $dir_array[] = $file;
                }
            }
            echo '<ul class="items" style="left:-58px;">';
            foreach ($dir_array as $key => $val) {
                $p = explode('_', $val);

                $q = explode('.', $p[1]);

                echo '<li>
    <img src="' . SITE_URL . 'tmp/' . $val . '" height="127px" width="156px"/>
    <a href = "javascript:void(0)"  onclick = " return caption(\'' . $val . '\');">' . $q[0] . '</a>
    <input type="radio" name="mainimage" id="mainimage" value="' . $val . '"/> Main Image
    <div class="cross_close_btn"><a href="javascript:void(0);"  onclick="return cancelPic(\'' . $val . '\');">
    <img src="' . SITE_URL . 'views/images/upload_image_close_btn.png" /></a></div>
    <div id="' . $val . '" class="edit_photo_popup" style="display: none" ><form><h2>Edit Caption</h2><br />
    <input type="text" name="captionName" id="tf*' . $val . '" value="' . $q[0] . '"/>
    <input type="hidden" name="imageId" value="' . $val . '"/>
    <a href="javascript:void(0);" onclick="addCaptionForImage(\'' . $val . '\',\'' . $q[1] . '\')" >
    <img src="' . SITE_URL . 'views/images/right_mark.png" alt=""/></a>
    <a href = "javascript:void(0)" onclick = "document.getElementById(\'' . $val . '\').style.display=\'none\'">
    <img src="' . SITE_URL . 'views/images/closebtn.gif" alt="close"/></a> </div></li>';
            }
            echo '<div class="clear"></div>
</ul>
 ';

            closedir($handle);
        }
        // echo "<div style='float:left'><img src='tmp/".$photoname."' height='144' width='175' /></div>";
    }

    public function organizeAction() {
        $object = new Users();
        $obj = new Fanwires();
        $errorObject = new Errors(Error_XML);
        $data = array();
        if (isset($_SESSION['name'])) {
            $userDetails = $object->getUserDetails($_SESSION['id']);
            //print_r($userDetails);
            $data['profile_image'] = SITE_URL . "profile_images/" . $userDetails[0]['profile_image'];
            $data['fanwires'] = $object->getMyFanwires();
            $data['fanwires_for_more'] = $object->getMyFanwiresMore();
        } else {
            header("Location:" . SITE_URL);
        }

        $obj->getHistory();
        $data['history'] = $_SESSION['history'];
        $data['notifications'] = $object->getNotifications($_SESSION['id']);
        //  if (isset($_REQUEST['album'])) {

        $album = $_REQUEST['coverImage'];
        $id = $_SESSION['id'];
        // if ($_REQUEST['privacy'] == 'private')
        //   $sta = 0;
        // else
        $sta = 1;
        $cover_image = $_REQUEST['coverImage'];

        $fanwireName = $_REQUEST['fanwireName']; //need to work on this this is the personal or fanwires pics
        $personal = $_REQUEST['personal']; //need to work on this this is the personal or fanwires pics
        if ($handle = opendir('tmp/')) {
            $dir_array = array();
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != ".." && preg_match("/^" . $_SESSION['id'] . "_/", $file)) { {
                        $dir_array[] = $file;
                        copy("tmp/" . $file, "photos/" . $file);
                        @unlink("tmp/" . $file);

                        //if($file==$cover_image)
                        //  {

                        $object->createThumbs($file, DOC_ROOT_PATH . "/photos/", DOC_ROOT_PATH . "/photos/thumbs/", '250');
                        // }
                    }
                }
            }
            // }
            //save album and photos...
            $album_id = $obj->savePhotos($id, $album, $dir_array, $cover_image, $sta, $caption);
            $_SESSION['album_id'] = $album_id;

            if ($album_id) {
                echo 1;
            } else {
                echo 0;
            }
        }

        //        $view = new View();
        //        $view->loadView($data, 'photos/my_photos_organise');
    }

    public function publishAction() {
        $object = new Users();
        $errorObject = new Errors(Error_XML);
        $data = array();
        if (isset($_SESSION['name'])) {
            $userDetails = $object->getUserDetails($_SESSION['id']);
            //print_r($userDetails);
            $data['profile_image'] = SITE_URL . "profile_images/" . $userDetails[0]['profile_image'];
            $data['fanwires'] = $object->getMyFanwires();
            $data['fanwires_for_more'] = $object->getMyFanwiresMore();
        } else {
            header("Location:" . SITE_URL);
        }

        $obj = new Fanwires();
        $obj->getHistory();
        $data['history'] = $_SESSION['history'];
        $list = $obj->getAlbumDetails($_SESSION['album_id']);
        $data['album_name'] = $list[0]['name'];
        $data['list'] = $list;
        $data['notifications'] = $object->getNotifications($_SESSION['id']);
        if (isset($_REQUEST['submit']) && $_REQUEST['submit'] == "publish") {
            $user_id = $_SESSION['id'];
            $myoptions = $_REQUEST['myoptions'];

            //print_r($myoptions);
            foreach ($myoptions as $val) {
                if ($val == 'fan') {
                    $obj->updateFanNetwork($user_id, $_SESSION['album_id'], $type = "photo");
                }
            }
            header("Location:" . SITE_URL . "myFanwire");
        }

        $view = new View();
        $view->loadView($data, 'photos/my_photos_publish');
    }

    public function photosAction() {
        global $fc;
        $object = new Users(); //Creating the object for the class Users
        $objectPhotos = new Photos();
        $errorObject = new Errors(Error_XML); //Creating the object for the class Errors
        $obj = new Fanwires(); //Creating the object for the class Fanwires

        $data = array();
        $dpObj = new DatePicker(); //creating object for DatePicker
        $data['months'] = $dpObj->getMonths(); //get all months
        $data['days'] = $dpObj->getDays(); //get days
        $data['years'] = $dpObj->getYears(); //get years
        $data['fanwires_for_moreguest'] = $object->getMyFanwiresGuest(); //for guest login
        $userDetails = $object->getUserDetails($_SESSION['id']); //current user details
        //    print_r($userDetails);
        if ($userDetails[0]['profile_image'] != "")
            $data['profile_image'] = SITE_URL . "profile_images/" . $userDetails[0]['profile_image'];
        else
            $data['profile_image'] = SITE_URL . "views/images/logodef.png";


        if (isset($_GET['fwrid'])) {
            $data['fwrid'] = $fwrid = $_GET['fwrid'];
            $data['ac'] = 1;
            $sta = 2;
        } else if (isset($_GET['uid'])) {
            $data['uid'] = $uid = $_GET['uid'];
            $data['ac'] = 4;
            $sta = 1;
        } else {

            $arr = explode("&", $fc->extra);
            $arr = explode("=", $arr[1]);
            if ($arr[0] == 'fwrid') {
                $data['fwrid'] = $fwrid = $arr[1];
                $data['ac'] = 1;
                $sta = 2;
            } else {
                $data['uid'] = $uid = $arr[1];
                $data['ac'] = 4;
                $sta = 1;
            }
        }
        $data['sta'] = $sta;
        $data['path'] = $_REQUEST['path'];
        //n this case data['fanwire'] is consiting of all fanwires details
        $data['fanwires'] = $object->getFanwireDetails($data['fwrid']); //fanwires details               
        $uid = (isset($data['uid'])) ? $data['uid'] : $data['fwrid'];
        $data['profileid'] = $uid;
        $data['active'] = 'fanwire';
        //in this case data['fanwires'] is consiting of alll fan details
        $data['userDetails'] = $object->getUserDetails($data['uid']); //fan details
        //$data['albums'] = $objectPhotos->getMedia($uid, $sta); //first parameter is id to whoome it belongs and second parameter is to whoome it belongs either to user or fanwire            
        $data['albums'] = $obj->getProfileDetails($uid, 'media', $sta); //first parameter is id to whoome it belongs and second parameter is to whoome it belongs either to user or fanwire            
        //}

        $obj->getHistory();
        $data['history'] = $_SESSION['history'];
        //$data['fanwires_for_more'] = $object->getMyFanwiresMore();
        $data['fanwires_for_more'] = $obj->getMasterDetailsMore();
        $data['active'] = 'fanwire';
        $data['fanwires']['name'] = (isset($data['uid'])) ? $object->getUserName($uid) : $obj->getFanwireName($uid); //fanwires details
        //get the zoom values
        if ($_SESSION['id'])
            $session = $_SESSION['id'];
        else
            $session = session_id();

        $data['zoomValues'] = $obj->saveZoomValues($_REQUEST['path'], $session, '', '', '');

        if ($data['zoomValues'] == 1)
            $data['zoomValues'] = array(array('value' => 68, 'width' => 246, 'height' => 186));

        $data['notifications'] = $object->getNotifications($_SESSION['id']);
        $cat = new Categories();
        $metatitle = $cat->getMetaTitle($data['fanwires']['categoryid']);
        $view = new View(); //Creating the object for the class View
        $data['pagetitle'] = $data['fanwires']['name'] . ' News | ' . $metatitle . ' | Photos | Facebook | Twitter';
        $data['metadescription'] = $data['fanwires']['description'];
        // echo "<pre>";print_r($data['albums']);
        $view->loadView($data, 'photos/thumbPhotos');
    }

    public function viewAlbumAction() {

        $data = array();
        global $fc;
        if (isset($_GET['aid'])) {
            $aid = $_GET['aid']; //get article id
        } else {
            $arr = explode("=", $fc->extra);
            $aid = $arr[1];
        }
        $data['aid'] = $aid;
        $object = new Users(); //Creating the object for the class Users
        $dpObj = new DatePicker(); //creating object for DatePicker
        $objectPhotos = new Photos();
        $errorObject = new Errors(Error_XML); //Creating the object for the class Errors
        $obj = new Fanwires(); //Creating the object for the class Fanwires
        $artObject = new Articles();
        $testObj = new CombineFeatures();
        $data['months'] = $dpObj->getMonths(); //get all months
        $data['days'] = $dpObj->getDays(); //get days
        $data['years'] = $dpObj->getYears(); //get years
        $data['active'] = 'fanwire';
        $data['history'] = $_SESSION['history']; //to store the history
        $userDetails = $object->getUserDetails($_SESSION['id']);
        if ($userDetails[0]['profile_image'] != "")
            $data['profile_image'] = SITE_URL . "profile_images/" . $userDetails[0]['profile_image'];
        else
            $data['profile_image'] = SITE_URL . "views/images/logodef.png";
        $data['fanwires_for_more'] = $obj->getMasterDetailsMore();
        $data['albumDetail'] = $objectPhotos->getAlbumDetails($aid); //get the album details
        $data['thisAlubumDetails'] = $objectPhotos->getThisAlbumDetails($aid); //get the album details   
        $data['fanwires'] = $object->getFanwireDetails($data['thisAlubumDetails']['fanwire_id']); //fanwires details
        $data['similarphotos'] = $objectPhotos->getSimilarPhotos($data['thisAlubumDetails']['fanwire_id'], $aid); //get the album details
        //echo "<pre>";print_r($data['similarphotos']);
        $data['popularImages'] = $testObj->getImagess($data['thisAlubumDetails']['fanwire_id']);
        $data['twitterPosts'] = $artObject->getTwitterList($data['thisAlubumDetails']['fanwire_id']); //get twitter posts related to that particular celebrity
        $data['otherNewsAboutFanwire'] = $obj->getOtherNewsAboutFanwire($data['thisAlubumDetails']['fanwire_id']); //other news about this fanwire        
        $data['fanwires_for_moreguest'] = $object->getMyFanwiresGuest();
        $data['moreFanwires'] = $object->getMyFanwiresMore(); //already user fan of these fanwires
        $data['username'] = $_SESSION['name'];
        $data['boigraphy'] = $testObj->getBio();
        $data['relatedAlbumNames'] = $objectPhotos->getRelatedAlbumsNames(mysql_escape_string($data['thisAlubumDetails']['title'])); //getting related articles names
        $arr = array();
        foreach ($data['moreFanwires'] as $key => $value) {
            $arr[] = $value['id'];
        }
        if ($data['thisAlubumDetails']['source'] == 'Instagram') {
            $data['pagetitle'] = $data['fanwires']['name'] . ' Instagram | Latest Photos For ' . $data['fanwires']['name'] . ' on ' . $data['thisAlubumDetails']['metadate'];
            $data['metadescription'] = $data['fanwires']['name'] . ' Instagram photo on ' . $data['thisAlubumDetails']['metadate'];
        } else {
            $data['pagetitle'] = $data['fanwires']['name'] . ' | Latest Photos For ' . $data['fanwires']['name'] . ' on ' . $data['thisAlubumDetails']['metadate'];
            $data['metadescription'] = $data['fanwires']['name'] . ' photo on ' . $data['thisAlubumDetails']['metadate'];
        }
        $ids = implode(',', $arr);
        $view = new View(); //Creating the object for the class View
        if (isset($_SESSION['name'])) {
            $data['fanwiresMore'] = $object->getFanwiresNotIn($ids); //these fanwires which user not fan
            $view->loadView($data, 'photos/albumDetails');
        } else {
            $data['fanwiresMore'] = $object->getMyFanwiresGuest(); //these fanwires which user not fan
            $view->loadView($data, 'photos/albumDetailsGuest');
        }
    }

    /**
     * upload photos and display
     */
    public function ajaxImageDragAction() {

        if ($handle = opendir('tmp/')) {
            $dir_array = array();
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != ".." && preg_match("/^" . $_SESSION['id'] . "_/", $file)) {
                    $dir_array[] = $file;
                }
            }


            echo '<ul class="items" style="left:-58px;">';
            foreach ($dir_array as $key => $val) {
                $p = explode('_', $val);

                $q = explode('.', $p[1]);

                echo '<li>
    <img src="' . SITE_URL . 'tmp/' . $val . '" height="127px" width="156px"/>
    <a href = "javascript:void(0)"  onclick = " return caption(\'' . $val . '\');">' . $q[0] . '</a>
    <input type="radio" name="mainimage" id="mainimage" value="' . $val . '"/> Main Image
    <div class="cross_close_btn"><a href="javascript:void(0);"  onclick="return cancelPic(\'' . $val . '\');">
    <img src="' . SITE_URL . 'views/images/upload_image_close_btn.png" /></a></div>
    <div id="' . $val . '" class="edit_photo_popup" style="display: none" ><form><h2>Edit Caption</h2><br />
    <input type="text" name="captionName" id="tf*' . $val . '" value="' . $q[0] . '"/>
    <input type="hidden" name="imageId" value="' . $val . '"/>
    <a href="javascript:void(0);" onclick="addCaptionForImage(\'' . $val . '\',\'' . $q[1] . '\')" >
    <img src="' . SITE_URL . 'views/images/right_mark.png" alt=""/></a>
    <a href = "javascript:void(0)" onclick = "document.getElementById(\'' . $val . '\').style.display=\'none\'">
    <img src="' . SITE_URL . 'views/images/closebtn.gif" alt="close"/></a> </div></li>';
            }
            echo '<div class="clear"></div>
</ul>
 ';



            closedir($handle);
        }
    }

    //adding caption to the every image
    public function addCaptionAjaxAction() {
        // $fileUtils = new FileUtils();
        //$photoname = $fileUtils->saveUploadedFile("photoimg", DOC_ROOT_PATH . "/tmp/");

        $oldname = $_REQUEST['imageId'];
        $newname = $_REQUEST['captionName'];
        $ext = $_REQUEST['ext'];


        //$myFile = DOC_ROOT_PATH . "/tmp/captions.txt";
        // $fh = fopen($myFile, 'a') or die("can't open file");
        // fwrite($fh, $oldname . "*" . $newname . '\n');
        // fclose($fh);

        rename("tmp/" . $oldname, "tmp/" . $_SESSION['id'] . "_" . trim($newname) . '.' . $ext);

        if ($handle = opendir('tmp/')) {
            $dir_array = array();
            if ($_REQUEST['can'] == '1')
                @unlink("tmp/" . $_REQUEST['flname']);
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != ".." && preg_match("/^" . $_SESSION['id'] . "_/", $file)) {

                    $dir_array[] = $file;
                }
            }
            echo '<ul class="items" style="left:-58px;">';
            foreach ($dir_array as $key => $val) {
                $p = explode('_', $val);

                $q = explode('.', $p[1]);

                echo '<li>
    <img src="' . SITE_URL . 'tmp/' . $val . '" height="127px" width="156px"/>
    <a href = "javascript:void(0)"  onclick = " return caption(\'' . $val . '\');">' . $q[0] . '</a>
    <input type="radio" name="mainimage" id="mainimage" value="' . $val . '"/> Main Image
    <div class="cross_close_btn"><a href="javascript:void(0);"  onclick="return cancelPic(\'' . $val . '\');">
    <img src="' . SITE_URL . 'views/images/upload_image_close_btn.png" /></a></div>
    <div id="' . $val . '" class="edit_photo_popup" style="display: none" ><form><h2>Edit Caption</h2><br />
    <input type="text" name="captionName" id="tf*' . $val . '" value="' . $q[0] . '"/>
    <input type="hidden" name="imageId" value="' . $val . '"/>
    <a href="javascript:void(0);" onclick="addCaptionForImage(\'' . $val . '\',\'' . $q[1] . '\')" >
    <img src="' . SITE_URL . 'views/images/right_mark.png" alt=""/></a>
    <a href = "javascript:void(0)" onclick = "document.getElementById(\'' . $val . '\').style.display=\'none\'">
    <img src="' . SITE_URL . 'views/images/closebtn.gif" alt="close"/></a> </div></li>';
            }
            echo '<div class="clear"></div>
</ul>
 ';

            closedir($handle);
        }
        // echo "<div style='float:left'><img src='tmp/".$photoname."' height='144' width='175' /></div>";
    }

    public function editAlbumAction() {
        if (!isset($_SESSION['name'])) {
            header("Location:" . SITE_URL);
        }

        $aid = $_GET['aid']; //get article id
        $object = new Users(); //Creating the object for the class Users
        $objectPhotos = new Photos();
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
        $albumDetail = $objectPhotos->getAlbumDetails($aid); //get the article details
        foreach ($albumDetail as $val) {
            copy(DOC_ROOT_PATH . "/photos/" . $val['image_url'], DOC_ROOT_PATH . "/tmp/" . $val['image_url']);
        }
        //echo "<pre>";print_r($albumDetail);echo "</pre>";
        $data['albumDetail'] = $albumDetail;
        $data['notifications'] = $object->getNotifications($_SESSION['id']); //to get the any notifications
        $data['album_id'] = $aid;
        $view = new View(); //Creating the object for the class View
        $view->loadView($data, 'photos/edit_photo');
    }

    public function updateAlbumAction() {
        $object = new Users();
        $obj = new Fanwires();
        $album = $_REQUEST['coverImage'];
        $id = $_SESSION['id'];
        $sta = 1;
        $cover_image = $_REQUEST['coverImage'];
        $fanwireName = $_REQUEST['fanwireName']; //need to work on this this is the personal or fanwires pics
        $personal = $_REQUEST['personal']; //need to work on this this is the personal or fanwires pics
        $album_id = $_REQUEST['album_id'];
        if ($handle = opendir('tmp/')) {
            $dir_array = array();
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != ".." && preg_match("/^" . $_SESSION['id'] . "_/", $file)) {
                    $dir_array[] = $file;
                    copy("tmp/" . $file, "photos/" . $file);
                    @unlink("tmp/" . $file);
                    $object->createThumbs($file, DOC_ROOT_PATH . "/photos/", DOC_ROOT_PATH . "/photos/thumbs/", '250');
                }
            }
            $obj->updatePhotos($id, $album, $dir_array, $cover_image, $sta, $caption, $album_id);
        }
    }

    /*
     * ajaxMore 
     */

    public function ajaxMoreProfilesAction() {
        global $fc;
        $actual_image_name = "";
        $object = new Users(); //Creating the object for the class Users
        $objectFanwires = new Fanwires();
        $errorObject = new Errors(Error_XML); //Creating the object for the class Errors
        $data = array();
        $userDetails = $object->getUserDetails($_SESSION['id']);
        if ($userDetails[0]['profile_image'] != "")
            $data['profile_image'] = SITE_URL . "profile_images/" . $userDetails[0]['profile_image'];
        else
            $data['profile_image'] = SITE_URL . "views/images/logodef.png";
        if ($_SESSION['id'])
            $session = $_SESSION['id'];else
            $session = session_id();
        $zoom = $data['zoomValues'] = $objectFanwires->saveZoomValues($_POST['path'], $session, '', '', '');
        $start = $_POST['strlimit'];
        $lastmsg = $start + 10;
        $result = $objectFanwires->getProfileDetails($_POST['profileid'], $_POST['page'], $_POST['belongsTo'], $start);
        $html = '';
        // echo "<pre>";print_r($zoom);exit;
        foreach ($result as $key => $value) {
            $nu = '';
            $style1 = '';
            $comment = '';
            $style2 = '';
            $style3 = '';
            $style4 = '';
            $share_n_collect = '';
            $description2 = '';
            $description = '';
            $description1 = '';
            $comments = '';
            $share_popup = '';
            $show = '';

            if ($value['source'] != 2 && $value['source'] != 1) {
                if ($value['type'] != 4) {
                    $show .="<div style=' display: block; clear: both;float:right;'><span ><img src='" . SITE_URL . "views/images/display" . $value['type'] . ".png' width='19' height='19' align='left' /></span></div>";
                }
            }
            if ($zoom[0]['width'] == 0 || $zoom[0]['width'] == '')
                $zoom[0]['width'] = 246;
            if ($zoom[0]['width']) {
                $style = "style='width:" . $zoom[0]['width'] . "px;'";
            }
            if ($value['source'] == 2 || $value['photo'] == '') {
                $style1 .= "style=float:left;width:79px;";
            } else {
                $style1 .= "";
            }
            if ($value['source'] == 2) {
                $style2 .= '<img src="' . IMAGE_URL . $value['photo'] . '"  width="75" height="75"/>';
            } else if ($value['photo'] == '') {
                $style2 .= '<img src="' . IMAGE_URL . $value['fanwire_profile_img'] . '" width="75" height="75"/>';
            } else {
                if ($value['type'] == 0) {
                    $style3 .= 'href="' . $value['link'] . '"';
                    if ($zoom[0]['width'] > 0) {
                        $widthImageShare = $zoom[0]['width'];
                        $heightImageShare = $zoom[0]['width'] * ($value['height'] / $value['width']);
                    } else {
                        $widthImageShare = 246;
                        $heightImageShare = 246 * ($value['height'] / $value['width']);
                    }
                } else {
                    $style3 .= 'href="' . $value['title_link'] . '"';
                    if ($zoom[0]['width'] > 0) {
                        $widthImageShare = $zoom[0]['width'];
                        $heightImageShare = $zoom[0]['width'] * ($value['height'] / $value['width']);
                    } else {
                        $widthImageShare = 246;
                        $heightImageShare = 246 * ($value['height'] / $value['width']);
                    }
                }
                $style2 .= '<input type="hidden" name="image_width" id="image_width"  value="' . $value['width'] . '" /><input type="hidden" name="image_height" id="image_height" value="' . $value['height'] . '" /><a ' . $style3 . 'class="userProf" ><img id="image" src="' . IMAGE_URL . $value['photo'] . '" width="' . $widthImageShare . '" height="' . $heightImageShare . '"/></a>';
            }
            if ($value['source'] == 2 || $value['photo'] == '') {

                if ($zoom[0]['width'] < 215)
                    $style4 .= "style='float: left;width:51%'";
                else
                    $style4 .= "style='float: left;width:66%'";
            }
            $share = ''; // '<div id="fanid'.$value['id'].'" class="share_n_colletc"> <a class="share_img_btn" name =\'share\' onclick = "getFanwireFans(\''.$value['id'].'\'); shareTogg(\''.$value['id'].'\');displayBlock(\'black\'); " href="javascript:void(0);"> <span class="icon"><img src="'.SITE_URL.'views/images/share_icon.png"/></span><span>Share</span> </a>  </div>'



            if ($value['title_link'] != '' && $value['source'] != 1 && $value['source'] != 2) {
                $description.='<a href="' . $value['title_link'] . '" style="color:#20A0BF;text-transform: none;text-decoration: none;font-size: 12px;">' . $value['title'] . '</a><br />';
            } else if ($value['source'] != 1 && $value['source'] != 2) {
                $description .=' <span style="color:#20A0BF;text-transform: none;text-decoration: none;font-size: 12px;">' . $value['title'] . '</span>';
            }
            if ($value['source'] == 2)
                $description .='<div style=" display: block; clear: both;"><span style="float:left;padding: 5px 5px 0 0px;">via</span><span style="float:left"> <img src="' . SITE_URL . 'views/images/twt.png" width="25" height="21" align="left" /></span><span style="float:left;padding: 5px 0 0 0px;"><b>Twitter</b></span></div> ';
            if ($value['source'] == 1 && $value['photo'] == '')
                $description .='<div style=" display: block; clear: both;"><span style="float:left;padding: 5px 3px 0 0;">via</span><span style="float:left;padding: 5px"> <img src="' . SITE_URL . 'views/images/facebook.png" align="left" /></span><span style="float:left;padding: 5px 0 0 3px;"><b>Facebook</b></span></div>';

            $textDescription = YoutubeUrl::truncate_chars($value['description'], 300);
            if ($value['title_link'] != '' && $value['source'] != 1 && $value['source'] != 2) {
                $description1 .= '<a onclick="return javascript:void(0);" href="' . $value['title_link'] . '" style="text-decoration: none;color:#424140;">' . $textDescription . '</a>';
            } else {
                $description1 .= $textDescription;
            }

            if ($value['source'] == 1 && $value['photo'] != '')
                $description2 .= '<div style=" display: block; clear: both;"><span style="float:left;padding: 5px 3px 0 0;">via</span><span style="float:left;padding: 5px"> <img src="' . SITE_URL . 'views/images/facebook.png" align="left" /></span><span style="float:left;padding: 5px 0 0 3px;"><b>Facebook</b></span></div>';
            if (ACTIVATE == 1)
                $share_popup .='<a href="javascript:void(0)" class="close"  onClick="Popup.show(\'sub' . $value['id'] . $value['type'] . '\');" ></a>';
            if ($value['article_from'] == 'twitter' || $value['article_from'] == 'facebook')
                $text = 'posts';
            $share_popup .='<div id = "sub' . $value['id'] . $value['type'] . '" class = "sub3" >
                <ul class = "facebook_dd">
                    <li><a href="' . SITE_URL . 'remove?id=' . $value['id'] . '&type=' . $value['type'] . '">Hide Element</a></li>
                    <li><a href = "#" onclick = "return tog(\'tog' . $value['id'] . $value['type'] . '\');">Report element or spam</a>
                        <div id = "tog' . $value['id'] . $value['type'] . '" style = "display:none;">
                            <span><a class = "selected" href = "#">Inappropriate content</a></span>
                            <span><a href = "#">Sapm</a></span>
                            <span><a href = "#">Miscategorized</a></span>
                            <span><a href = "#">Other</a></span>
                        </div>
                     </li>
                     <li><a href="#">Remove ' . $value['article_from'] . $text . ' from my profile</li>
                     <li><a href="' . SITE_URL . 'remove?id={$fan.id}&type={$fan.type}">Remove {$fan.name} permanently</a></li>
                </ul>
             </div>';

//            if ($value['status'] == 1)
//                $share_n_collect .='<span class="icon" ><img src="' . SITE_URL . 'views/images/collected_icon.png"/></span><span style="background: none repeat scroll 0 0 green;" >collected</span>'; else
//                $share_n_collect .='<span class="icon"><img src="' . SITE_URL . 'views/images/collect_icon.png"/></span> <span>collect</span>';
            if ($value['commentcnt'] > 0)
                $comment .= '(' . $value['commentcnt'] . ')';
            if ($value['dislike'] > 0)
                $dislike = '(' . $value['dislike'] . ')'; else
                $dislike = '&nbsp;';
            if ($value['like'] > 0)
                $like = '(' . $value['like'] . ')'; else
                $like = '&nbsp;';
            if ($value['commentcnt'] > 5)
                $comm = '<a id="view_all_link' . $value['id'] . $value['type'] . '" href="javascript:void(0);" onclick="viewAllComments(\'' . $value['id'] . '\',\'' . $value['type'] . '\', \'' . SITE_URL . 'fan/viewAllComments\')" >view all comments</a>'; else
                $comm = '<a href="javascript:void(0);" >&nbsp;</a>';

            if ($value['type'] == 4) {
                $inst = '<div style=" display: block; clear: both;"><span style="float:left;padding: 5px 5px 0 0px;">via</span><span style="float:left"> <img src="' . SITE_URL . 'views/images/display' . $value['type'] . '.png" width="19" height="19" align="left" /></span><span style="float:left;padding: 5px 0 0 0px;"><b> &nbsp;Instagram</b></span></div>';
            } else {
                $inst = '';
            }
            if ($value['commentcnt'] > 5)
                $height = 'class="staticUL"';
            if ($value['commentcnt'] < 5)
                $height = ' style="max-height:' . ($value['commentcnt'] * 42) . 'px;"';
            foreach ($value['comments_for_this_post'] as $keys => $values) {
                $comments.='<li ><img src = "' . $values['profile_image'] . '"><span style = "float: left;"><strong>' . $values['name'] . '</strong> <h5 style = "float: right; text-decoration: none;" >' . $values['stamp'] . '</h5>&nbsp;</span><span>' . $values['comment'] . '</span><div class = "clear"></div></li >';
            }
            $html .= '<div  id="mydiv" class="collect_photo item block"' . $style . '>
                    <div class="image_share_popup" id="image_share_popup" ' . $style1 . '>' . $style2 . '</div>
                    <div class="red_links" >' . $share . '
                    <div id="collect' . $value['id'] . $value['type'] . '" class="share_n_colletc">
                    <a class="collect_img_btn" href="javascript:void(0);" onclick="collect(\'' . $value['id'] . '\',\'' . $value['type'] . '\',\'' . SITE_URL . 'collect\');">' . $share_n_collect . '</a>
                    </div>' . $share_popup . '</div> 
                    <div class="data">
                    <a href="' . $value['link'] . '" style="color: #F04F2C;font-size: 14px;font-family: OswaldBook;text-decoration: float:left; none;text-transform: uppercase;">' . $value['name'] . '</a>
                    ' . $show . '<span style="float:right;padding:0 3px 0 ;">' . $value['time'] . '</span>
                    <div style="clear:both;"></div> ' . $description . $inst . '
                        
                    <div class="clear"></div>
                    </div>
                    <div class="data" style="clear:both;padding: 8px 0 8px 0;"><span style="clear:both;"  class=\'more1\'>' . $description1 . '</span>
                       <br /><span style="line-height:25px;margin-bottom: 5px; ">' . $description2 . '</span></div>
                    <div class="photo_post">
                     <div class="message" id="showcomment' . $value['id'] . $value['type'] . '" onClick="ShowDialog(\'true\',\'' . $value['id'] . '\',\'' . $value['type'] . '\');">
                        ' . $comment . '
                     </div>
                     <script type="text/javascript"> window.onload=onloadcall(\'' . $value['id'] . '\');</script>
                     <div id="showdislike' . $value['id'] . $value['type'] . '" class="red" onclick="dislikefanwire(\'' . $value['id'] . '\',\'' . $value['type'] . '\',\'' . SITE_URL . 'fan/fanwirelikes\');">' . $dislike . '</div>
                     <div id="showlike' . $value['id'] . $value['type'] . '" class="blue" onclick="likefanwire(\'' . $value['id'] . '\',\'' . $value['type'] . '\',\'' . SITE_URL . 'fan/fanwirelikes\');">' . $like . '</div>
                     <div id="dialog' . $value['id'] . $value['type'] . '" class="web_dialog" >
                     <div class="comments" >
                    <div>
                        <ul >  
                            <li class="view_comments">
                               ' . $comm . ' 
                               <a href="javascript:void(0);" class="cross_btn" onclick="HideDialog(\'' . $value['id'] . '\',\'' . $value['type'] . '\');" >X</a>
                            </li>
                        </ul>
                    </div>     
                             <div ' . $height . '>
                                 <ul id="all_comments' . $value['id'] . $value['type'] . '"  >

                                    ' . $comments . '

                                               </ul>

                                           </div> 
                                           <div id=\'comm' . $value['id'] . $value['type'] . '\'></div>
                    <div>
                                               <ul>
                                                   <li >
                                                       <img src="' . $data['profile_image'] . '" style="float: left;">
                                                       <textarea  id="msg' . $value['id'] . $value['type'] . '"  style="float: left;" name="msg' . $value['id'] . $value['type'] . '"  onkeyup=\'expandtext(this)\' onclick="return showSend(\'' . $value['id'] . '\');"onfocus=" return textlimits(\'#remainingCharacters' . $value['id'] . $value['type'] . '\',\'#msg' . $value['id'] . $value['type'] . '\');" onKeyPress="Javascript:if(event.keyCode==13) submitForm(\'' . $value['id'] . '\',\'' . $value['type'] . '\',\'' . SITE_URL . 'fan/sendComment\',\'' . SITE_URL . 'fan/commentCount\');" placeholder="say something..." title="say something..."   autocomplete="off" rows="1" ></textarea>
                    <div id = "commentsToShow' . $value['id'] . $value['type'] . '" style = "display: none;">
                    <button type = "button" class = "sendComment" onclick = "submitForm(\'' . $value['id'] . '\',\'' . $value['type'] . '\',\'' . SITE_URL . 'fan/sendComment\',\'' . SITE_URL . 'fan/commentCount\');">send</button>
                    <span class = "characters" id = "remainingCharacters' . $value['id'] . $value['type'] . '">140 Characters</span>
                    </div>
                    <div class = "clear"></div>
                    </li>
                    <input type = "hidden" id = "id" name = "id" value = "' . $value['id'] . '" />
                    <input type = "hidden" name = "type" id = "type" value = "' . $value['type'] . '" />
                    </ul>
                    </div>
                    </div>
                    </div>
                    </div>
                    <div class = "clr"></div>
                    </div>
                    <div id = "shareLoader' . $value['id'] . '" style = " display: none; top: 38%;position: absolute;left: 45%;z-index: 9999;"><img src = "' . SITE_URL . 'views/images/loaderBlack.gif"></div>
                    <div id = "light1' . $value['id'] . '" class = "share_fanwire" style = "display: none;">
                    <div style = "padding:0 0 0 15px;"><img src = "' . SITE_URL . 'views/images/1.png" height = "13" width = "22" alt = ""/></div>
                    <div class = "share_fanwire_content">
                    <a href = "javascript:void(0);" style = "position: absolute;bottom: 0px;right: 0px;" onclick = "shareTogg(\'light1' . $value['id'] . '\');">
                    <img src = "' . SITE_URL . 'views/images/closebtn.gif">
                    </a>
                    <div class = "share_fanwire_content1">
                    <form action = "' . SITE_URL . 'shareContent" name = \'share\' method = "GET">
                    <div style = "float:left;">
                    <p>SHARE THIS FANWIRE<span id = "errorShare' . $value['id'] . '" style = "color: orangered;padding: 0 0 0 10px;font-size: 14px; font-family: OpenSansRegular;"></span></p></div>
                    <div class = "connect10"><a href = "#" onclick = "Share(\'' . $value['id'] . '\');shareTheContentToRespectNetworks(\'' . $value['id'] . '\');">send message</a></div>
                    <div class = "clear"></div>
                    </div>
                    <div class = "share_fanwire_left">
                    <input type = "hidden" name = \'fanid\' value = \'' . $value['link'] . '\'/>
                    <p><input type = "checkbox" name = \'email_share\' id = "email_share' . $value['id'] . '" /><label>Email</label></p>
                    <p style = "padding:18px 0 3px 18px;">To (Email Address)</p>
                    <div class = "clear"></div>
                    <input type = "text" name = \'email_addresses\' id = "email_addresses' . $value['id'] . '" class = "textfieldshare"/>
                    <div class = "share_fanwire_lefta">
                    <p><a href = "javascript:void(0);" onclick = "addPerMes(\'' . $value['id'] . '\',\'mail\')">+ Add Personal message</a></p>
                    <div id = "addPmesg' . $value['id'] . '" style = "display: none;">
                    <textarea name = "personalmessage" id = "personalmessage' . $value['id'] . '">hello</textarea>
                    </div>
                    <p><a href = "#">+Preview email</a></p>
                    </div>
                    <p style = "padding:10px 0 0 0"><input type = "checkbox" name = \'facebook_share\' id = \'facebook' . $value['id'] . '\' value = ""/><label>Facebook</label></p>
                    <div class = "share_fanwire_lefta">
                    <p><a href = "javascript:void(0);" onclick = "addPerMes(\'' . $value['id'] . '\',\'face\')">+ Add Personal message</a></p>
                    <div id = "addPmesgFace' . $value['id'] . '" style = "display: none;">
                    <textarea name = "personalmessageface" id = "personalmessageface' . $value['id'] . '">hello</textarea></div>
                    </div>
                    <p style = "padding:24px 0 0 0"><input type = "checkbox" name = \'twitter_share\'id = \'twitter' . $value['id'] . '\'value = "" />
                    <label style = "color:#a8a8a8">Twitter<a href = "#">&nbsp;
                    connect</a></label></p>
                    </div>
                    <div class = "share_fanwire_right">
                    <p>
                    <input type = "checkbox" />
                    <label>Fanwire Fans</label></p>
                    <p style = "padding:18px 0 3px 18px;">Share This to:</p>
                    <div class = "clear"></div>
                    <div class = "share_fanwire_checkbox" >
                    <input type = \'checkbox\' value = \'on\' name = \'allbox\' id = \'allbox' . $value['id'] . '\' class = "allbox" onclick = \'checkAll();\' >Select/Deselect All<br>
                    <div id = \'fanwire_fans' . $value['id'] . '\'></div>
                    </div>
                    </div>
                    </form>
                    <div class = "clear">&nbsp;
                    </div>
                    </div>
                    </div>
                    <div class = "clr"></div>';
        }

        echo $html . "##" . $lastmsg;
    }

    //end update album function

    public function ajaxImagesAction() {
        $obj = new CombineFeatures();

        $result = $obj->getImagess($_GET['fanwireid']);
        $str = "<a href='" . $result['list'][0]['title_link'] . "'><img src =\" " . IMAGE_URL . $result['list'][0]['photo'] . "\" height = \"79\" width = \"120\" /></a>
                <a href='" . $result['list'][1]['title_link'] . "'><img src =\"" . IMAGE_URL . $result['list'][1]['photo'] . "\" height = \"79\" width = \"120\" /></a>
                <a href='" . $result['list'][2]['title_link'] . "'><img src = \"" . IMAGE_URL . $result['list'][2]['photo'] . "\" height = \"79\" width = \"120\" /></a>
                <a href='" . $result['list'][3]['title_link'] . "'><img src = \"" . IMAGE_URL . $result['list'][3]['photo'] . "\" height = \"79\" width = \"120\" /></a>
                <a href='" . $result['list'][4]['title_link'] . "'><img src = \"" . IMAGE_URL . $result['list'][4]['photo'] . "\" height = \"79\" width = \"120\" /></a>
                <a href='" . $result['list'][5]['title_link'] . "'><img src = \"" . IMAGE_URL . $result['list'][5]['photo'] . "\" height = \"79\" width = \"120\" /></a>";
        $str.= ":::" . $result['navigation'];
        echo $str;
    }

    /*
     * display the next album
     */

    public function ajaxNextAlbumAction() {
        $obj = new CombineFeatures();
        $result = $obj->getNextAlbums($_GET['fanwireid']);
        $str = "<img src='" . IMAGE_URL . $result[0]['photo'] . "' " . $result[0]['heightwidth'] . " alt=\"\" />";
        $str.=":::<a href='".$result[0]['link']."' target=\"_blank\" style=\"text-decoration: none;\">" . ucfirst($result[0]['source']) . "</a>:::" . str_replace("By", "", $result[0]['article_source']) . ":::" . $result[0]['date'] . ":::" . $result[0]['title'];
        $str.=":::" . $result['navigation'];
        echo $str;
    }

}

//end class..
?>