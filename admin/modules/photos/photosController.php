<?php

class photosController {

    public function addPhotosAction() {

        if (empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE && $_SESSION['admintype'] != SUBADMIN_TYPE)) {
            header("Location:" . ADMIN_URL);
            exit;
        }


        $ua = $_SERVER["HTTP_USER_AGENT"];

        $safari = strpos($ua, 'Safari') ? true : false; // All Safari
        if ($safari)
            $data['browser'] = 'Safari';
        else
            $data['browser'] = "";

        $obj = new Photos();
        $object = new Users();
        $data['fanwires'] = $obj->getFanwires();

        if (isset($_REQUEST['add'])) { // echo "<pre>";       print_r($_REQUEST);exit();           
            if ($handle = opendir('../tmp/')) {
                $dir_array = array();
                while (false !== ($file = readdir($handle))) {
                    if ($file != "." && $file != ".." && preg_match("/^" . $_SESSION['adminid'] . "_/", $file)) { {
                            $dir_array[] = $file;
                            copy("../tmp/" . $file, "../photos/" . $file);
                            @unlink("../tmp/" . $file);
                            $object->createThumbs($file, DOC_ROOT_PATH . "../photos/", DOC_ROOT_PATH . "../photos/thumbs/", '250');
                        }
                    }
                }
                $obj->insertPhotos($_REQUEST, $dir_array);
            }
        }
        $view = new View();
        $data['class'] = "fanwires";
        $data['menu'] = "media";
        $data['item'] = "photos";
        $data['ADMIN'] = ADMIN;
        $view->loadView($data, ADMIN . 'photos/addPhotosAdmin');
    }

    /**
     * upload photos and display
     */
    public function ajaxImageAction() {


        if ($handle = opendir('../tmp/')) {
            $dir_array = array();
            if ($_REQUEST['can'] == '1')
                @unlink("../tmp/" . $_REQUEST['flname']);
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != ".." && preg_match("/^" . $_SESSION['adminid'] . "_/", $file)) {

                    $dir_array[] = $file;
                }
            }
            echo '<ul class="items" style="left:-58px;">';
            foreach ($dir_array as $key => $val) {
                $p = explode('_', $val);

                $q = explode('.', $p[1]);

                echo '<li>
<img src="' . SITE_URL . '/tmp/' . $val . '" height="127px" width="156px"/><br/>
<a href = "javascript:void(0)"  onclick = " return caption(\'' . $val . '\');">' . $q[0] . '</a>
<input type="radio" name="mainimage" id="mainimage" value="' . $val . '"/> Main Image
<div class="cross_close_btn"><a href="javascript:void(0);"  onclick="return cancelPic(\'' . $val . '\');"><img src="' . SITE_URL . 'views/images/upload_image_close_btn.png" /></a></div>
 <div id="' . $val . '" class="edit_photo_popup" style="display: none" ><form><h2>Edit Caption</h2><br /><input type="text" name="captionName" id="tf*' . $val . '" value="' . $q[0] . '"/><input type="hidden" name="imageId" value="' . $val . '"/><a href="javascript:void(0);" onclick="addCaptionForImage(\'' . $val . '\',\'' . $q[1] . '\')" ><img src="' . SITE_URL . 'views/images/right_mark.png" alt=""/><a href = "javascript:void(0)" onclick = "document.getElementById(\'' . $val . '\').style.display=\'none\'"><img src="' . SITE_URL . 'views/images/closebtn.gif" alt="close"/></a> </div></li>';
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
        if ($_REQUEST['privacy'] == 'private')
            $sta = 0;
        else
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
        /* if (!isset($_SESSION['name'])) {
          header("Location:" . SITE_URL);
          } */
        $object = new Users(); //Creating the object for the class Users
        $objectPhotos = new Photos();
        $errorObject = new Errors(Error_XML); //Creating the object for the class Errors
        $obj = new Fanwires(); //Creating the object for the class Fanwires
        global $fc;
        $data = array();
        $userDetails = $object->getUserDetails($_SESSION['id']); //current user details
        if ($userDetails[0]['profile_image'] != "")
            $data['profile_image'] = SITE_URL . "profile_images/" . $userDetails[0]['profile_image'];
        else
            $data['profile_image'] = "";

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
            $data['albums'] = $objectPhotos->getAlbums($fwrid, $sta); //first parameter is id to whoome it belongs and second parameter is to whoome it belongs either to user or fanwire
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
            $data['albums'] = $objectPhotos->getAlbums($uid, $sta); //first parameter is id to whoome it belongs and second parameter is to whoome it belongs either to user or fanwire
            // echo "<pre>";print_r($data['fanwires']);
        }
        $obj->getHistory();
        $data['history'] = $_SESSION['history'];
        $data['fanwires_for_more'] = $object->getMyFanwiresMore();
        $data['notifications'] = $object->getNotifications($_SESSION['id']);
        $view = new View(); //Creating the object for the class View
        if (isset($_SESSION['name'])) {

            $view->loadView($data, 'photos/thumbPhotos');
        } else {
            $view->loadView($data, 'photos/thumbPhotosGuest'); //for guest user
        }
    }

    public function viewAlbumAction() {
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
            $view->loadView($data, 'photos/albumDetails');
        }
    }

    /**
     * upload photos and display
     */
    public function ajaxImageDragAction() {
        error_log("ajax image drag");
        if ($handle = opendir('../tmp/')) {
            $dir_array = array();
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != ".." && preg_match("/^" . $_SESSION['adminid'] . "_/", $file)) {
                    $dir_array[] = $file;
                }
            }


            echo '<ul class="items" style="left:-58px;">';
            foreach ($dir_array as $key => $val) {
                $p = explode('_', $val);

                $q = explode('.', $p[1]);

                echo '<li>
<img src="' . SITE_URL . '/tmp/' . $val . '" height="127px" width="156px"/><br/>
<a href = "javascript:void(0)"  onclick = " return caption(\'' . $val . '\');">' . $q[0] . '</a>
<input type="radio" name="mainimage" id="mainimage" value="' . $val . '"/> Main Image
<div class="cross_close_btn"><a href="javascript:void(0);"  onclick="return cancelPic(\'' . $val . '\');"><img src="' . SITE_URL . 'views/images/upload_image_close_btn.png" /></a></div>
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

        rename("../tmp/" . $oldname, "../tmp/" . $_SESSION['adminid'] . "_" . trim($newname) . '.' . $ext);

        if ($handle = opendir('../tmp/')) {
            $dir_array = array();
            if ($_REQUEST['can'] == '1')
                @unlink("../tmp/" . $_REQUEST['flname']);
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != ".." && preg_match("/^" . $_SESSION['adminid'] . "_/", $file)) {

                    $dir_array[] = $file;
                }
            }
            echo '<ul class="items" style="left:-58px;">';
            foreach ($dir_array as $key => $val) {
                $p = explode('_', $val);

                $q = explode('.', $p[1]);

                echo '<li>
<img src="' . SITE_URL . '/tmp/' . $val . '" height="127px" width="156px"/><br/>
<a href = "javascript:void(0)"  onclick = " return caption(\'' . $val . '\');">' . $q[0] . '</a>
<input type="radio" name="mainimage" id="mainimage" value="' . $val . '"/> Main Image
<div class="cross_close_btn"><a href="javascript:void(0);"  onclick="return cancelPic(\'' . $val . '\');"><img src="' . SITE_URL . 'views/images/upload_image_close_btn.png" /></a></div>
 <div id="' . $val . '" class="edit_photo_popup" style="display: none" ><form><h2>Edit Caption</h2><br /><input type="text" name="captionName" id="tf*' . $val . '" value="' . $q[0] . '"/><input type="hidden" name="imageId" value="' . $val . '"/><a href="javascript:void(0);" onclick="addCaptionForImage(\'' . $val . '\',\'' . $q[1] . '\')" ><img src="' . SITE_URL . 'views/images/right_mark.png" alt=""/><a href = "javascript:void(0)" onclick = "document.getElementById(\'' . $val . '\').style.display=\'none\'"><img src="' . SITE_URL . 'views/images/closebtn.gif" alt="close"/></a> </div></li>';
            }
            echo '<div class="clear"></div>
</ul>  
 ';

            closedir($handle);
        }
        // echo "<div style='float:left'><img src='tmp/".$photoname."' height='144' width='175' /></div>";
    }

    public function removePhotosAction() {

        if (empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE && $_SESSION['admintype'] != SUBADMIN_TYPE)) {
            header("Location:" . ADMIN_URL);
            exit;
        }
        $obj = new Photos();
        $data = $obj->removePhotos($_REQUEST['imgId'], $_REQUEST['fanwireId']);
        echo $data;
    }

    public function removeCoverPhotosAction() {

        if (empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE && $_SESSION['admintype'] != SUBADMIN_TYPE)) {
            header("Location:" . ADMIN_URL);
            exit;
        }
        $obj = new Photos();
        $data = $obj->removeCoverPhotos($_REQUEST['imgId'], $_REQUEST['fanwireId']);
        echo $data;
    }

    public function setPrimaryImageAction() {

        if (empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE && $_SESSION['admintype'] != SUBADMIN_TYPE)) {
            header("Location:" . ADMIN_URL);
            exit;
        }
        $obj = new Photos();
        $data = $obj->setPhotos($_REQUEST['imgId'], $_REQUEST['fanwireId']);
        echo $data;
    }

    public function setPrimaryCoverImageAction() {

        if (empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE && $_SESSION['admintype'] != SUBADMIN_TYPE)) {
            header("Location:" . ADMIN_URL);
            exit;
        }
        $obj = new Photos();
        $data = $obj->setCoverPhotos($_REQUEST['imgId'], $_REQUEST['fanwireId']);
        echo $data;
    }

    public function ajaxUploadImageAction() {
        error_log("testing ajax");
        $preview_url = SITE_URL . '/tmp/';
        $image = $_FILES["fileselect"]["name"];
        $uploadedfile = $_FILES['fileselect']['tmp_name'];
        $upload_dir = DOC_ROOT_PATH . "/tmp/" . $_SESSION['adminid'] . "_" . $image;
        if (!move_uploaded_file($uploadedfile, $upload_dir))
            error_log("error");

        if ($handle = opendir('../tmp/')) {
            $dir_array = array();
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != ".." && preg_match("/^" . $_SESSION['adminid'] . "_/", $file)) {
                    $dir_array[] = $file;
                }
            }
            // This is a PHP code outputing Javascript code.
            echo '<script language="JavaScript" type="text/javascript">' . "\n";
            echo 'var parDoc = window.parent.document;';
            $data = '<ul class="items" style="left:-58px;">';
            foreach ($dir_array as $key => $val) {
                $p = explode('_', $val);
                $q = explode('.', $p[1]);
                $data .= '<li>
<img src="' . SITE_URL . '/tmp/' . $val . '" height="127px" width="156px"/><br/>
<a href = "javascript:void(0)"  onclick = " return caption(\'' . $val . '\');">' . $q[0] . '</a>
<input type="radio" name="mainimage" id="mainimage" value="' . $val . '"/> Main Image
<div class="cross_close_btn"><a href="javascript:void(0);"  onclick="return cancelPic(\'' . $val . '\');"><img src="' . SITE_URL . 'views/images/upload_image_close_btn.png" /></a></div>
 <div id="' . $val . '" class="edit_photo_popup" style="display: none" ><form><h2>Edit Caption</h2><br /><input type="text" name="captionName" id="tf*' . $val . '" value="' . $q[0] . '"/><input type="hidden" name="imageId" value="' . $val . '"/><a href="javascript:void(0);" onclick="addCaptionForImage(\'' . $val . '\',\'' . $q[1] . '\')" ><img src="' . SITE_URL . 'views/images/right_mark.png" alt=""/><a href = "javascript:void(0)" onclick = "document.getElementById(\'' . $val . '\').style.display=\'none\'"><img src="' . SITE_URL . 'views/images/closebtn.gif" alt="close"/></a> </div></li>';
            }
            $data .= '<div class="clear"></div>
</ul>   ';
            echo "parDoc.getElementById('messages').innerHTML =" . $data;
            echo "\n" . '</script>';


            closedir($handle);
        }
    }

    public function photosListAction() {
        global $fc;
        $data = array();
        // authontication
        if (empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE && $_SESSION['admintype'] != SUBADMIN_TYPE)) {
            header("Location:" . ADMIN_URL);
            exit();
        }
        $fan = new Fanwires();
        $fanwire_id = $_REQUEST['id'];
        $Obj = new Photos();
        if (isset($_REQUEST['submit'])) {
            $cdate = $fan->dateformat($_REQUEST['cdate']);
            $Obj->updateDate($_REQUEST, $cdate);
            header("Location:" . ADMIN_URL . "photos/photosList?id=" . $fanwire_id);
        }

        if (isset($_REQUEST['release'])) {
            $dt_release = $fan->dateformat($_REQUEST['released_date']);
            $Obj->updateReleasedDate($_REQUEST, $dt_release);
            header("Location:" . ADMIN_URL . "photos/photosList?id=" . $fanwire_id);
        }
        $data['date'] = date("Y-m-d H:i:s");
        $cat = $Obj->getPhotosList($fanwire_id);
        $data['list'] = $cat['list'];
        $data['navigation'] = $cat['navigation'];
        $data['ADMIN'] = ADMIN;
        $data['fanwire_id'] = $fanwire_id;
        $view = new View();
        $view->loadView($data, ADMIN . "photos/photosList");
    }

    /**
     * delete photos
     * @global type $fc 
     */
    public function deletePhotoAction() {
        global $fc;
        // authontication
        if (empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE && $_SESSION['admintype'] != SUBADMIN_TYPE)) {
            header("Location:" . ADMIN_URL);
            exit();
        }
        //print_r($_REQUEST);exit();
        $id = $_REQUEST['photo_id'];
        $fanwire_id = $_REQUEST['id'];
        $Obj = new Photos();
        $Obj->deletePhotos($id);
        header("Location:" . ADMIN_URL . "photos/photosList?id=" . $fanwire_id);
    }

    public function deleteImageAction() {
        global $fc;
        // authontication
        if (empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE && $_SESSION['admintype'] != SUBADMIN_TYPE)) {
            header("Location:" . ADMIN_URL);
            exit();
        }
        $id = $_REQUEST['id'];
        $filename = $_REQUEST['filename'];
        @unlink(SITE_URL . "photos/" . $filename);
        @unlink(SITE_URL . "photos/thumbs/" . $filename);
        $Obj = new Photos();
        $Obj->deleteImage($id);
    }

    public function editPhotosAction() {
        if (empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE && $_SESSION['admintype'] != SUBADMIN_TYPE)) {
            header("Location:" . ADMIN_URL);
            exit;
        }

        $id = $_REQUEST['id'];
        $photo_id = $_REQUEST['photo_id'];


        $obj = new Photos();
        $object = new Users();

        if (isset($_REQUEST['add'])) { // echo "<pre>";       print_r($_REQUEST);exit();           
            if ($handle = opendir('../tmp/')) {
                $dir_array = array();
                while (false !== ($file = readdir($handle))) {
                    if ($file != "." && $file != ".." && preg_match("/^" . $_SESSION['adminid'] . "_/", $file)) { {
                            $dir_array[] = $file;
                            copy("../tmp/" . $file, "../photos/" . $file);
                            @unlink("../tmp/" . $file);
                            $object->createThumbs($file, DOC_ROOT_PATH . "../photos/", DOC_ROOT_PATH . "../photos/thumbs/", '250');
                        }
                    }
                }
                $obj->editPhotos($_REQUEST, $dir_array);
            }
        }
        $data['album'] = $obj->getAlbum($photo_id);
        $data['photos'] = $obj->getAlbumDetails($photo_id);
        $data['keywords'] = $data['album'][0]['keywords'];
        $data['additional_fans'] = $data['album'][0]['additional_fans'];
        $view = new View();
        $data['class'] = "fanwires";
        $data['menu'] = "media";
        $data['item'] = "photos";
        $data['ADMIN'] = ADMIN;
        $data['fanwire_id'] = $id;
        $view->loadView($data, ADMIN . 'photos/editPhotos');
    }

}

?>
