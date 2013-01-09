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
class articlesController {

    public function addArticleAction() {
        $objectUser = new Users();
        $objectArticle = new Articles();
        $errorObject = new Errors(Error_XML);
        $data = array();
        if (isset($_SESSION['name'])) {
            $data['active'] = 'article';
            $userDetails = $objectUser->getUserDetails($_SESSION['id']);
            $data['profile_image'] = SITE_URL . "profile_images/" . $userDetails[0]['profile_image'];
            $data['fanwires'] = $objectUser->getMyFanwires();
            $data['fanwires_for_more'] = $objectUser->getMyFanwiresMore();
            $data['notifications'] = $objectUser->getNotifications($_SESSION['id']); //to get the any notifications

            if (isset($_REQUEST['article']) && $_REQUEST['article'] == 'article') {

                $articleData = $_POST;
                if ($articleid = $objectArticle->addArticle($articleData)) {
                    $_SESSION['article_id'] = $articleid;
                    // header("Location:" . SITE_URL . 'my_fanwire_organise');
                    header("Location:" . SITE_URL . 'myArticles');
                }
            }
        } else {
            header("Location:" . SITE_URL);
        }
        $obj = new Fanwires();
        $obj->getHistory();
        $data['history'] = $_SESSION['history'];
        $view = new View();
        $view->loadView($data, 'articles/my_fanwire_new_article');
    }

    public function stepTwoAction() {
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
            $data['notifications'] = $object->getNotifications($_SESSION['id']); //to get the any notifications
        } else {
            header("Location:" . SITE_URL);
        }
        $obj = new Fanwires();
        $obj->getHistory();
        $data['history'] = $_SESSION['history'];
        $view = new View();
        $view->loadView($data, 'articles/my_fanwire_new_article_more');
    }

    public function stepThreeAction() {
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
            $data['notifications'] = $object->getNotifications($_SESSION['id']); //to get the any notifications
        } else {
            header("Location:" . SITE_URL);
        }
        $obj = new Fanwires();
        $obj->getHistory();
        $data['history'] = $_SESSION['history'];
        $view = new View();
        $view->loadView($data, 'articles/my_fanwire_add_new_wire');
    }

    public function organizeAction() {
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
            $data['notifications'] = $object->getNotifications($_SESSION['id']); //to get the any notifications
        } else {
            header("Location:" . SITE_URL);
        }
        $obj = new Fanwires();

        $art = new Articles();
        $obj->getHistory();
        $data['history'] = $_SESSION['history'];
        $data['notifications'] = $object->getNotifications($_SESSION['id']);
        //  if (isset($_REQUEST['album'])) {

        $album = $_REQUEST['coverImage'];
        $id = $_SESSION['id'];
        if ($_REQUEST['privacy'] == 'private')
            $sta = "0";
        else
            $sta = "1";
        $title = $_REQUEST['title'];
        $cover_image = $_REQUEST['coverImage'];
        $description = $_REQUEST['textAreaContent'];
        $fanwireName = $_REQUEST['fanwireName']; //need to work on this this is the personal or fanwires pics
        $personal = $_REQUEST['personal']; //need to work on this this is the personal or fanwires pics
        if ($handle = opendir('tmp/')) {
            $dir_array = array();
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != ".." && preg_match("/^" . $_SESSION['id'] . "_/", $file)) { {
                        $dir_array[] = $file;
                        copy("tmp/" . $file, "photos/" . $file);
                        @unlink("tmp/" . $file);
                        $object->createThumbs($file, DOC_ROOT_PATH . "/photos/", DOC_ROOT_PATH . "/photos/thumbs/", '250');
                    }
                }
            }
            //save album and photos...
            $album_id = $art->addArticlesUser($id, $title, $album, $dir_array, $cover_image, $sta, addslashes($description));
            $_SESSION['album_id'] = $album_id;

            if ($album_id) {
                echo 1;
            } else {
                echo 0;
            }
        }
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
            $data['notifications'] = $object->getNotifications($_SESSION['id']); //to get the any notifications
        } else {
            header("Location:" . SITE_URL);
        }
        $art = new Articles();
        $data['list'] = $art->getArticleDetails($_SESSION['article_id']);
        $obj = new Fanwires();
        if (isset($_REQUEST['submit']) && $_REQUEST['submit'] == "publish") {
            $user_id = $_SESSION['id'];
            $myoptions = $_REQUEST['myoptions'];

            //print_r($myoptions);
            foreach ($myoptions as $val) {
                if ($val == 'fan') {
                    $obj->updateFanNetwork($user_id, $_SESSION['article_id'], $type = "article");
                }
            }
            header("Location:" . SITE_URL . "myFanwire");
        }

        $obj->getHistory();
        $data['history'] = $_SESSION['history'];
        $view = new View();
        $view->loadView($data, 'articles/my_fanwire_organise_publish');
    }

    public function articlesAction() {


        global $fc;
        $data = array();
        $dpObj = new DatePicker(); //creating object for DatePicker
        $data['months'] = $dpObj->getMonths(); //get all months
        $data['days'] = $dpObj->getDays(); //get days
        $data['years'] = $dpObj->getYears(); //get years
        $object = new Users(); //Creating the object for the class Users
        $data['fanwires_for_moreguest'] = $object->getMyFanwiresGuest(); //for guest login
        $objectArticles = new Articles();
        $errorObject = new Errors(Error_XML); //Creating the object for the class Errors
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
            $data['articles'] = $objectArticles->getArticles($fwrid, $sta); //first parameter is id to whoome it belongs and second parameter is to whoome it belongs either to user or fanwire
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
            $data['articles'] = $objectArticles->getArticles($uid, $sta); //first parameter is id to whoome it belongs and second parameter is to whoome it belongs either to user or fanwire
            //echo "<pre>";print_r($data['userDetails']);
        }

        $obj = new Fanwires(); //Creating the object for the class Fanwires
        $obj->getHistory();
        $data['history'] = $_SESSION['history'];

        $userDetails = $object->getUserDetails($_SESSION['id']);
        if ($userDetails[0]['profile_image'] != "")
            $data['profile_image'] = SITE_URL . "profile_images/" . $userDetails[0]['profile_image'];
        else
            $data['profile_image'] = "";
        $data['fanwires'] = $object->getFanwireDetails($fwrid); //fanwires details
        $data['fanwires_for_more'] = $object->getMyFanwiresMore();
        $data['notifications'] = $object->getNotifications($_SESSION['id']); //to get the any notifications
        $data['connects'] = $object->getConnects($_SESSION['id'], $uid);
        //get the zoom values
        $data['zoomValues'] = $obj->saveZoomValues('articles', $_SESSION['id'], '', '', '');
        $view = new View(); //Creating the object for the class View
        // echo '<pre>';print_r($data['articles']);
        if (isset($_SESSION['name'])) {

            $view->loadView($data, 'articles/thumbArticles');
        } else {
            $view->loadView($data, 'articles/thumbArticlesGuest');
        }
    }

    //to view single article
    public function viewArticleAction() {
        global $fc;
        if (isset($_GET['aid'])) {
            $aid = $_GET['aid']; //get article id
        } else {
            $arr = explode("=", $fc->extra);
            $aid = $arr[1];
        }

        $object = new Users(); //Creating the object for the class Users
        $objectArticles = new Articles();
        $errorObject = new Errors(Error_XML); //Creating the object for the class Errors
        $obj = new Fanwires(); //Creating the object for the class Fanwires
        $testObj = new CombineFeatures();
        $data = array();
        $dpObj = new DatePicker(); //creating object for DatePicker
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
        $data['articleDetail'] = $objectArticles->getArticleDetails($aid); //get the article details
        $data['fanwires'] = $object->getFanwireDetails($data['articleDetail'][0]['user_id']); //fanwires details
        $data['twitterPosts'] = $objectArticles->getTwitterList($data['articleDetail'][0]['user_id']); //get twitter posts related to that particular celebrity
        $data['otherNewsAboutFanwire'] = $obj->getOtherNewsAboutFanwire($data['articleDetail'][0]['user_id']); //other news about this fanwire        
       
        $data['popularImages'] = $testObj->getImagess($data['articleDetail'][0]['user_id']);
        $data['username'] = $_SESSION['name'];
        $data['aid'] = $aid;
        $data['boigraphy'] = $testObj->getBio();
        $data['articles'] = $testObj->getArticles($data['articleDetail'][0]['user_id']);
        $arr = array();
        foreach ($data['moreFanwires'] as $key => $value) {
            $arr[] = $value['id'];
        }
        $ids = implode(',', $arr);

        if ($data['articleDetail'][0]['source'] == '1') {
            $data['pagetitle'] = $data['fanwires']['name'] . ' | Facebook | Latest Updates For ' . $data['fanwires']['name'] . ' on ' . $data['articleDetail'][0]['date'];
            $data['metadescription'] = $data['fanwires']['name'] . ' Facebook post on ' . $data['articleDetail'][0]['date'];
        } else {
            $data['pagetitle'] = $data['fanwires']['name'] . ' | ' . substr($data['articleDetail'][0]['title'], 0, 65);
            $data['metadescription'] = 'News about ' . $data['fanwires']['name'] . ', in the latest article titled ' . $data['articleDetail'][0]['title'] . ' on FanWire';
        }
        $view = new View(); //Creating the object for the class View
        if (isset($_SESSION['name'])) {
            $data['fanwiresMore'] = $object->getFanwiresNotIn($ids); //these fanwires which user not fan
            $view->loadView($data, 'articles/articleDetails');
        } else {
            $data['fanwiresMore'] = $object->getMyFanwiresGuest(); //these fanwires which user not fan
            $view->loadView($data, 'articles/articleDetailsGuest');
        }
    }

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
<img src="tmp/' . $val . '" height="127px" width="156px"/>

<a href = "javascript:void(0)"  onclick = " return caption(\'' . $val . '\');">' . $q[0] . '</a>
<input type="radio" name="mainimage" id="mainimage" value="' . $val . '"/> Main Image
<div class="cross_close_btn"><a href="javascript:void(0);"  onclick="return cancelPic(\'' . $val . '\');"><img src="views/images/upload_image_close_btn.png" /></a></div>
 <div id="' . $val . '" class="edit_photo_popup" style="display: none" ><form><h2>Edit Caption</h2><br /><input type="text" name="captionName" id="tf*' . $val . '" value="' . $q[0] . '"/><input type="hidden" name="imageId" value="' . $val . '"/><a href="javascript:void(0);" onclick="addCaptionForImage(\'' . $val . '\',\'' . $q[1] . '\')" ><img src="' . SITE_URL . 'views/images/right_mark.png" alt=""/><a href = "javascript:void(0)" onclick = "document.getElementById(\'' . $val . '\').style.display=\'none\'"><img src="' . SITE_URL . 'views/images/closebtn.gif" alt="close"/></a> </div></li>';
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
<img src="tmp/' . $val . '" height="127px" width="156px"/>

<a href = "javascript:void(0)"  onclick = " return caption(\'' . $val . '\');">' . $q[0] . '</a>
<input type="radio" name="mainimage" id="mainimage" value="' . $val . '"/> Main Image
<div class="cross_close_btn"><a href="javascript:void(0);"  onclick="return cancelPic(\'' . $val . '\');"><img src="views/images/upload_image_close_btn.png" /></a></div>
 <div id="' . $val . '" class="edit_photo_popup" style="display: none" ><form><h2>Edit Caption</h2><br /><input type="text" name="captionName" id="tf*' . $val . '" value="' . $q[0] . '"/><input type="hidden" name="imageId" value="' . $val . '"/><a href="javascript:void(0);" onclick="addCaptionForImage(\'' . $val . '\',\'' . $q[1] . '\')" ><img src="' . SITE_URL . 'views/images/right_mark.png" alt=""/><a href = "javascript:void(0)" onclick = "document.getElementById(\'' . $val . '\').style.display=\'none\'"><img src="' . SITE_URL . 'views/images/closebtn.gif" alt="close"/></a> </div></li>';
            }
            echo '<div class="clear"></div>
</ul>
 ';

            closedir($handle);
        }
        // echo "<div style='float:left'><img src='tmp/".$photoname."' height='144' width='175' /></div>";
    }

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
<img src="tmp/' . $val . '" height="127px" width="156px"/>

<a href = "javascript:void(0)"  onclick = " return caption(\'' . $val . '\');">' . $q[0] . '</a>
<input type="radio" name="mainimage" id="mainimage" value="' . $val . '"/> Main Image
<div class="cross_close_btn"><a href="javascript:void(0);"  onclick="return cancelPic(\'' . $val . '\');"><img src="views/images/upload_image_close_btn.png" /></a></div>
 <div id="' . $val . '" class="edit_photo_popup" style="display: none" ><form><h2>Edit Caption</h2><br /><input type="text" name="captionName" id="tf*' . $val . '" value="' . $q[0] . '"/><input type="hidden" name="imageId" value="' . $val . '"/><a href="javascript:void(0);" onclick="addCaptionForImage(\'' . $val . '\',\'' . $q[1] . '\')" ><img src="' . SITE_URL . 'views/images/right_mark.png" alt=""/><a href = "javascript:void(0)" onclick = "document.getElementById(\'' . $val . '\').style.display=\'none\'"><img src="' . SITE_URL . 'views/images/closebtn.gif" alt="close"/></a> </div></li>';
            }
            echo '<div class="clear"></div>
</ul>
 ';

            closedir($handle);
        }
        // echo "<div style='float:left'><img src='tmp/".$photoname."' height='144' width='175' /></div>";
    }

    //code by Rak for edit articles on 08/06/2012


    public function editArticlesAction() {
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
        $artobj = new Articles();
        $data['articledetails'] = $artobj->editArticles($_SESSION['id'], $aid); //Fetch the article and send to view for edit
        // echo "<pre>";
        //     print_r($data['articledetails']);
        $arr = array(); //re's to another year of friendship.
        foreach ($data['moreFanwires'] as $key => $value) {
            $arr[] = $value['id'];
        }
        $ids = implode(',', $arr);
        $data['fanwiresMore'] = $object->getFanwiresNotIn($ids); //these fanwires which user not fan
        if (isset($_SESSION['name'])) {
            $view = new View(); //Creating the object for the class View
            $view->loadView($data, 'articles/editarticle_');
        }
    }

    //code by Rak to edit article on 08/07/2012
    public function editorganizeAction() {
        $aid = $_GET['aid'];

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
            $data['notifications'] = $object->getNotifications($_SESSION['id']); //to get the any notifications
        } else {
            //header("Location:" . SITE_URL);
        }
        $obj = new Fanwires();

        $art = new Articles();
        $obj->getHistory();
        $data['history'] = $_SESSION['history'];
        $data['notifications'] = $object->getNotifications($_SESSION['id']);
        //  if (isset($_REQUEST['album'])) {

        $album = $_REQUEST['coverImage'];
        $id = $_SESSION['id'];
        if ($_REQUEST['privacy'] == 'private')
            $sta = "0";
        else
            $sta = "1";
        $title = $_REQUEST['title'];
        $cover_image = $_REQUEST['coverImage'];
        $description = $_REQUEST['textAreaContent'];
        $fanwireName = $_REQUEST['fanwireName']; //need to work on this this is the personal or fanwires pics
        $personal = $_REQUEST['personal']; //need to work on this this is the personal or fanwires pics
        if ($handle = opendir('tmp/')) {
            $dir_array = array();
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != ".." && preg_match("/^" . $_SESSION['id'] . "_/", $file)) { {
                        $dir_array[] = $file;
                        copy("tmp/" . $file, "photos/" . $file);
                        @unlink("tmp/" . $file);
                        $object->createThumbs($file, DOC_ROOT_PATH . "/photos/", DOC_ROOT_PATH . "/photos/thumbs/", '250');
                    }
                }
            }
            //save album and photos...
            echo $album_id = $art->editArticle($aid, $title, $album, $dir_array, $cover_image, $sta, addslashes($description), $_SESSION['id']);
            $_SESSION['album_id'] = $album_id;

            if ($album_id) {
                echo 1;
            } else {
                echo 0;
            }
        }
    }

    //code by Rak on 08102012
    public function viewAllCommentsAction() {
        $id = $_REQUEST['id'];
        //echo "<script>alert('$id');</script>";
        $obj = new Users();
        $all_comments = $obj->viewAllComments($id, $_SESSION['id'], $_REQUEST['type']);

        $totc = '';
        //echo 'rakesh';
        foreach ($all_comments as $comm) {


            $totc.= '<div class = "comment"><h5>' . $comm['comment_time'] . '</h5>
<div class = "comment_img"><img height = "27" width = "27" src = " ' . $comm['profile_image'] . '" />
</div><div class = "comment_text"><h4>' . $comm['name'] . '</h4><p>' . $comm['comment'] . '</p></div><div class = "comments_icons">
<div id = "showdislike' . $comm['cmtid'] . CMT_TYPE . '" class = "red" onclick = "dislikefanwire(\'' . $comm['cmtid'] . '\',\'' . CMT_TYPE . '\');">';
            if ($comm['dislike'] == '')
                $next = '0';
            else
                $next = $comm['dislike'];
            $totc.='(' . $next . ')</div><div id = "showlike' . $comm['cmtid'] . CMT_TYPE . '" class = "blue" onclick = "likefanwire(\'' . $comm['cmtid'] . ' \',\'' . CMT_TYPE . '\');">(';

            if ($comm['like'] == '')
                $prev = '0';
            else
                $prev = $comm['like'];
            $totc.=$prev . ')</div></div><div class = "clear"></div></div>';
            //$totc.="<h5></h5><div class='comment_img'><img height='27' width='27' src='". $comm['profile_image'] . "'></div><div class='comment_text'><h4>" . $comm['name'] . "</h4><p>" . $comm['comment'] . "</p></div><div class=\"clear\"></div><br/><hr/>";
        }
        echo $totc;
    }

    //code by Rak for likes and dislikes and comments
    public function fanwirelikesAction() {
        $fanwireid = $_REQUEST['fanwireid'];
        $like = $_REQUEST['like'];
        $dislike = $_REQUEST['dislike'];
        $type = $_REQUEST['type'];
        $obj = new Users();

        echo $obj->fanwireLikes($fanwireid, $_SESSION['id'], $like, $dislike, $type);
    }

    /**
     * This function is for ajax article display
     * 
     */
    public function ajaxArticleAction() {
        $obj = new CombineFeatures();
        $result = $obj->getArticles($_GET['fanwireid']);
        //  echo "<pre>";print_r($_GET);
        $str = "<img src='" . IMAGE_URL . $result[0]['photo'] . "' " . $result[0]['heigtwidth'] . " alt=\"\" /><p>" . $result[0]['description'] . "</p>";
        $str.=":::" . ucfirst($result[0]['article_from']) . ":::" . str_replace("By", "", $result[0]['article_source']) . ":::" . $result[0]['date'] . ":::" . $result[0]['title'];
        $str.=":::" . $result['navigation'];
        echo $str;
    }

}

?>
