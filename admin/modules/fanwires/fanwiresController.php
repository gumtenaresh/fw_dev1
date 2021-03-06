<?php

/**
 * Description of fanwires  *
 * @author gangaraju
 */
class fanwiresController {

    public function indexAction() {
        // authentication
        if (empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE and $_SESSION['admintype'] != SUBADMIN_TYPE)) {
            header("Location:" . ADMIN_URL);
            exit;
        }
    }

    /**
     * fanwires list
     * @global type $fc 
     */
    public function fanwiresListAction() {
        global $fc;
        $search = "";
        $data = array();
        // authentication
        if (empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE and $_SESSION['admintype'] != SUBADMIN_TYPE)) {
            header("Location:" . ADMIN_URL);
            exit;
        }
        $qObj = new Fanwires();
        $sort = $_REQUEST['sort'];
        if ($sort == "desc")
            $data['sort'] = "asc";
        else
            $data['sort'] = "desc";
        if (isset($_REQUEST['column']))
            $data['column'] = $_REQUEST['column'];
        else
            $data['column'] = "name";

        if (isset($_REQUEST['submit'])) {
            $cdate = $qObj->dateformat($_REQUEST['cdate']);
            $qObj->updateDate($_REQUEST, $cdate);
            header("Location:" . ADMIN_URL . "fanwires/fanwiresList");
        }

        if (isset($_REQUEST['release'])) {
            $dt_release = $qObj->dateformat($_REQUEST['released_date']);
            $qObj->updateReleasedDate($_REQUEST, $dt_release);
            header("Location:" . ADMIN_URL . "fanwires/fanwiresList");
        }

        if (isset($_REQUEST['search'])) {
            $search = $_REQUEST['search'];
        }
        if (isset($_REQUEST['delete']) && $_REQUEST['delete'] == 'Delete Selected') {
            extract($_REQUEST);
            $arr = explode(",", $fan_ids);
            foreach ($arr as $id)
                $qObj->deleteFanwire($id, '2');
        }

        $data['date'] = date("Y-m-d H:i:s");
        $list = $qObj->getFanwiresList($sort, $data['column'], $search);
        $data['list'] = $list['list'];
        $data['navigation'] = $list['navigation'];
        $data['ADMIN'] = ADMIN;
        $data['IMAGE_URL'] = IMAGE_URL;
        $data['class'] = "fanwires";
        $view = new View();
        $view->loadView($data, ADMIN . "fanwires/fanwireslist");
    }

    /**
     * add fanwires
     * @global type $fc 
     */
    public function addFanwiresAction() {
        global $fc;
        $object = new Users();
        $data = array();
        // authontication
        if (empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE and $_SESSION['admintype'] != SUBADMIN_TYPE)) {
            header("Location:" . ADMIN_URL);
            exit();
        }
        $data['msg'] = "";
        $Obj = new Fanwires();
        if (isset($_REQUEST['button']) && $_REQUEST['button'] == 'Save') {
            $Obj->addFanwires($_REQUEST);
            header("Location:" . ADMIN_URL . "fanwires/fanwiresList/");
        }
        $data['fanwire_id'] = $Obj->getFanwireId();
        $cat = $Obj->getCategories();
        $data['cat'] = $cat;
        $data['ADMIN'] = ADMIN;
        $data['class'] = "fanwires";
        $data['menu'] = "addFanwire";
        $view = new View();
        $view->loadView($data, ADMIN . "fanwires/addFanwires");
    }

    public function uploadImageAction() {
        $object = new Users();
        $fileUtils = new FileUtils();
        $photoname = $fileUtils->saveUploadedFile("photo", DOC_ROOT_PATH . "/photos");
        $object->createThumbs($photoname, DOC_ROOT_PATH . "/photos/", DOC_ROOT_PATH . "/photos/thumbs/", '250');
        echo $photoname;
    }

    /**
     * displaying albums list
     * @global type $fc 
     */
    public function albumsAction() {
        global $fc;
        $data = array();
        // authentication
        if (empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE and $_SESSION['admintype'] != SUBADMIN_TYPE)) {
            header("Location:" . ADMIN_URL);
            exit;
        }
        $qObj = new Fanwires();
        $list = $qObj->getAlbumsList();
        $data['list'] = $list['list'];
        $data['navigation'] = $list['navigation'];
        $data['ADMIN'] = ADMIN;
        $view = new View();
        $view->loadView($data, ADMIN . "fanwires/albums");
    }

    /**
     * displaying photos list
     * @global type $fc 
     */
    public function photosListAction() {
        global $fc;
        $data = array();
        // authentication
        if (empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE and $_SESSION['admintype'] != SUBADMIN_TYPE)) {
            header("Location:" . ADMIN_URL);
            exit;
        }
        $qObj = new Fanwires();
        $albumid = $_REQUEST['id'];
        $list = $qObj->getAlbumDetails($albumid);
        $data['album_name'] = $qObj->getAlbumName($albumid);
        $data['list'] = $list;
        $data['ADMIN'] = ADMIN;
        $data['IMAGE_URL'] = IMAGE_URL;
        $view = new View();
        $view->loadView($data, ADMIN . "fanwires/photoslist");
    }

//end function questions

    /**
     * displaying videos list
     * @global type $fc 
     */
    public function videosListAction() {
        global $fc;
        $data = array();
        // authentication
        if (empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE and $_SESSION['admintype'] != SUBADMIN_TYPE)) {
            header("Location:" . ADMIN_URL);
            exit;
        }
        $qObj = new Fanwires();
        $list = $qObj->getVideosList();
        $data['list'] = $list['list'];
        $data['navigation'] = $list['navigation'];
        $data['ADMIN'] = ADMIN;
        $data['IMAGE_URL'] = IMAGE_URL;
        $view = new View();
        $view->loadView($data, ADMIN . "fanwires/videoslist");
    }

//end function questions

    /**
     * delete photos 
     */
    public function deletePhotoAction() {
        // authentication
        if (empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE and $_SESSION['admintype'] != SUBADMIN_TYPE)) {
            header("Location:" . ADMIN_URL);
            exit;
        }
        $id = $_REQUEST['id'];
        $album_id = $_REQUEST['album_id'];
        $qObj = new Fanwires();
        $qObj->deletePhoto($id);
        header("Location:" . ADMIN_URL . "fanwires/photosList?id=" . $album_id);
    }

    /**
     * delete videos 
     */
    public function deleteVideoAction() {
        // authentication
        if (empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE and $_SESSION['admintype'] != SUBADMIN_TYPE)) {
            header("Location:" . ADMIN_URL);
            exit;
        }
        $id = $_REQUEST['id'];
        $qObj = new Fanwires();
        $qObj->deleteVideo($id);
        header("Location:" . ADMIN_URL . "fanwires/videosList");
    }

    /**
     * delete fanwires 
     */
    public function deleteFanwireAction() {
        // authentication
        if (empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE and $_SESSION['admintype'] != SUBADMIN_TYPE)) {
            header("Location:" . ADMIN_URL);
            exit;
        }
        $id = $_REQUEST['id'];
        $status = $_REQUEST['status'];
        $qObj = new Fanwires();
        $qObj->deleteFanwire($id, $status);
        header("Location:" . ADMIN_URL . "fanwires/fanwiresList");
    }

    /**
     * changing the fanwire status to public or private
     */
    public function viewFanwireAction() {
        // authentication
        if (empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE and $_SESSION['admintype'] != SUBADMIN_TYPE)) {
            header("Location:" . ADMIN_URL);
            exit;
        }
        $id = $_REQUEST['id'];
        $view_status = $_REQUEST['view_status'];
        $qObj = new Fanwires();
        $qObj->viewFanwire($id, $view_status);
        header("Location:" . ADMIN_URL . "fanwires/fanwiresList");
    }

    public function deleteAlbumAction() {
        // authentication
        if (empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE and $_SESSION['admintype'] != SUBADMIN_TYPE)) {
            header("Location:" . ADMIN_URL);
            exit;
        }
        $id = $_REQUEST['id'];
        $qObj = new Fanwires();
        $qObj->deleteAlbum($id);
        header("Location:" . ADMIN_URL . "fanwires/albums/");
    }

    public function subCategoriesAction() {
        // authentication
        if (empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE and $_SESSION['admintype'] != SUBADMIN_TYPE)) {
            header("Location:" . ADMIN_URL);
            exit;
        }

        $parentid = $_REQUEST['parentid'];
        $qObj = new Fanwires();
        $categories = $qObj->getCategories($parentid);

        $data = "<select id='cat2' name='cat2' onchange=\"getSubCategories(this.value,'#categories3','" . ADMIN_URL . "fanwires/subCategories2')\"> <option value=''>Select</option>";
        foreach ($categories as $key => $val) {
            $id = $val['id'];
            $name = $val['name'];
            $data.="<option value=" . $id . ">" . $name . "</option>";
        }
        $data .= "</select>";
        echo $data;
    }

    public function subCategories2Action() {
        // authentication
        if (empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE and $_SESSION['admintype'] != SUBADMIN_TYPE)) {
            header("Location:" . ADMIN_URL);
            exit;
        }

        $parentid = $_REQUEST['parentid'];
        $qObj = new Fanwires();
        $categories = $qObj->getCategories($parentid);

        $data = "<select id='cat3' name='cat3'> <option value=''>Select</option>";
        foreach ($categories as $key => $val) {
            $id = $val['id'];
            $name = $val['name'];
            $data.="<option value=" . $id . ">" . $name . "</option>";
        }
        $data .= "</select>";
        echo $data;
    }

    public function searchAction() {

        $keyword = $_REQUEST['part'];
        $conn = new Dao();
        $sql = "SELECT name from tbl_fanwires where name like '%$keyword%'";
        $rs = $conn->selectSql($sql, array());
        $i = 0;
        foreach ($rs as $key => $value) {
            $data[$i]['name'] = $value['name'];
            $i++;
        }
        error_log($rs);
        echo json_encode($data);
    }

    public function ajaxImageDragAction() {
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

    public function uploadFanwireImageAction() {

        $object = new Users();
        $fileUtils = new FileUtils();
        $photoname = $fileUtils->saveUploadedFile("photo", DOC_ROOT_PATH . "/photos");
        $object->createThumbs($photoname, DOC_ROOT_PATH . "/photos/", DOC_ROOT_PATH . "/photos/thumbs/", '250');
    }

    public function ajaxUploadImageAction() {


        $upload_dir = DOC_ROOT_PATH . "/tmp/";
        $preview_url = SITE_URL . '/tmp/';
        $image = $_FILES["picture"]["name"];
        $uploadedfile = $_FILES['picture']['tmp_name'];

        //error_log($uploadedfile."--". $upload_dir);

        if (!move_uploaded_file($uploadedfile, $upload_dir))
            error_log("error");
        $errors = 0;
        if ($image) {
            $filename = stripslashes($_FILES['picture']['name']);
            //get image extension
            $extension = fanwiresController::getExtensionAction($filename);
            $extension = strtolower($extension);
            // echo $extension;
            $imgEncrypt = md5(time() . $_FILES['picture']['name']) . "." . $extension;
            if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif") && ($extension != "pjpeg")) {

                $change = '<div class="msgdiv">Unknown Image extension </div> ';
                $errors = 1;
            } else {
                $size = filesize($_FILES['picture']['tmp_name']);
                if ($extension == "jpg" || $extension == "jpeg" || $extension == "pjpeg") {
                    //create jpg image
                    $uploadedfile = $_FILES['picture']['tmp_name'];
                    $src = imagecreatefromjpeg($uploadedfile);
                } else if ($extension == "png") {
                    //create png image
                    $uploadedfile = $_FILES['picture']['tmp_name'];
                    $src = imagecreatefrompng($uploadedfile);
                } else {
                    //create gif image
                    $src = imagecreatefromgif($uploadedfile);
                }

                list($width, $height) = getimagesize($uploadedfile);
                $max_width = 800;
                if ($width > $max_width) {
                    $percent = $max_width / $width;
                    $newwidth = $max_width;
                    $newheight = round($height * $percent);
                } else {
                    $newwidth = $width;
                    $newheight = $height;
                }
                //create image of specified width and height
                $tmp = imagecreatetruecolor($newwidth, $newheight);
                imagecopyresampled($tmp, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
                $filename = $upload_dir . $imgEncrypt;
                imagejpeg($tmp, $filename, 100);
                //imagedestroy($src);
                imagedestroy($tmp);

                $max_width = 246;
                if ($width > $max_width) {
                    $percent = $max_width / $width;
                    $newwidth = $max_width;
                    $newheight = round($height * $percent);
                } else {
                    $newwidth = $width;
                    $newheight = $height;
                }

                $tmp = imagecreatetruecolor($newwidth, $newheight);
                imagecopyresampled($tmp, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
                $filename = $upload_dir . "thumb_" . $imgEncrypt;
                imagejpeg($tmp, $filename, 100);
                imagedestroy($src);
                imagedestroy($tmp);
            }
        }

        // This is a PHP code outputing Javascript code.
        echo '<script language="JavaScript" type="text/javascript">' . "\n";
        echo 'var parDoc = window.parent.document;';
        if ($errors == '' || $errors == '0') {
            echo 'parDoc.getElementById("picture_error").innerHTML =  "";';
        } else {
            echo "parDoc.getElementById('picture_error').innerHTML = '" . $change . "';";
        }

        if ($filename != '') {
            echo "parDoc.getElementById('picture_preview').innerHTML = '<input type=\"hidden\" name=\"avatar\" id=\"avatar\" /><input type=\"hidden\" name=\"photo\" id=\"photo\" value=\"$imgEncrypt\"/> <img src=\'$preview_url$imgEncrypt\' id=\'preview_picture_tag\' name=\'preview_picture_tag\' width=\'$newwidth\' height=\'$newheight\' />';";
        }

        echo "\n" . '</script>';
        exit(); // do not go futher
    }

    public function getExtensionAction($str) {
        $i = strrpos($str, ".");
        if (!$i) {
            return "";
        }
        $l = strlen($str) - $i;
        $ext = substr($str, $i + 1, $l);
        return $ext;
    }

    /**
     * edit fanwires
     * @global type $fc
     */
    public function editFanwiresAction() {
        global $fc;
        $object = new Users();
        $Obj = new Fanwires();
        $data = array();
        // authentication
        if (empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE and $_SESSION['admintype'] != SUBADMIN_TYPE)) {
            header("Location:" . ADMIN_URL);
            exit();
        }
        $data['msg'] = "";

        if (isset($_REQUEST['button'])) {
            $Obj->editFanwires($_REQUEST);
        }
        $data['fanwires_data'] = $Obj->getFanwires($_REQUEST['id']);
        //list($width, $height) = getimagesize(DOC_ROOT_PATH . "/photos/" . $data['fanwires_data']['photos']['url']);
        list($width, $height) = getimagesize(IMAGE_URL . $data['fanwires_data']['photos']['url']);
        $data['width'] = $width;
        $data['height'] = $height;
        $photosObj = new Photos();
        $data['photos'] = $photosObj->getLibrary($_REQUEST['id']);
        $data['fanwireId'] = $_REQUEST['id'];
        $cat = $Obj->getCategories();
        $data['cat'] = $cat;
        $data['ADMIN'] = ADMIN;
        $data['class'] = "fanwires";
        $data['menu'] = "addFanwire";
        $view = new View();
        $view->loadView($data, ADMIN . "fanwires/editFanwires");
    }

    public function profileAction() {
        global $fc;
        $object = new Users();
        $s3object = StorageFactory::getObject();
        $Obj = new Fanwires();
        //echo "submit";
        $image = new SimpleImage();
        $data = array();
        // authontication
        if (empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE and $_SESSION['admintype'] != SUBADMIN_TYPE)) {
            header("Location:" . ADMIN_URL);
            exit();
        }
        $fan_id = $_REQUEST['id'];


        if (isset($_REQUEST['avatar'])) {
            $image_name = $_FILES['avatar_img']['name'];
            list($txt, $ext) = explode(".", $image_name);
            $name = strtotime(date("Y-m-d H:i:s")) . "." . $ext;
            $tmp = $_FILES['avatar_img']['tmp_name'];
            $s3object->saveFile($tmp, "photos/" . $name);
            /* $image->load($tmp);
              $image->resizeToWidth(225);
              $image->save(DOC_ROOT_PATH . '/photos/' . $name);
              $image->save(DOC_ROOT_PATH . '/photos/thumbs/' . $name); */
            $Obj->updateAvatarPhoto($name, $fan_id);
        }

        if (isset($_REQUEST['timeline'])) {
            $image_name = $_FILES['timeline_img']['name'];
            list($txt, $ext) = explode(".", $image_name);
            $name = strtotime(date("Y-m-d H:i:s")) . "." . $ext;
            $tmp = $_FILES['timeline_img']['tmp_name'];
            $s3object->saveFile($tmp, "photos/" . $name);
            /*
              $image->load($tmp);
              //$image->resizeToWidth(1024);
              $image->save(DOC_ROOT_PATH . '/photos/' . $name); */
            $Obj->updateTimelinePhoto($name, $fan_id);
        }

        if (isset($_REQUEST['submit']) && $_REQUEST['submit'] == "Next") {
            extract($_REQUEST);
            $Obj->updateProfile($id, $biography);
        }
        $photosObj = new Photos();
        $data['photos'] = $photosObj->getLibrary($_REQUEST['id']);
        $data['cover'] = $photosObj->getCoverImages($_REQUEST['id']);
        $list = $Obj->getBiography($fan_id);
        //print_r($list);
        $data['name'] = $list[0]['name'];
        $data['description'] = $list[0]['description'];
        $data['avatar_photo'] = $list[0]['avatar_photo'];
        $data['width'] = $list[0]['width'];
        $data['height'] = $list[0]['height'];
        $data['timeline_photo'] = $list[0]['timeline_photo'];
        $data['category'] = $list[0]['category'];
        $data['ADMIN'] = ADMIN;
        $data['class'] = "fanwires";
        $data['menu'] = "profile";
        $data['fan_id'] = $fan_id;
        $view = new View();
        $view->loadView($data, ADMIN . "fanwires/profile");
    }

    public function fbProfileImagesAction() {

        // authontication
        if (empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE and $_SESSION['admintype'] != SUBADMIN_TYPE)) {
            header("Location:" . ADMIN_URL);
            exit();
        }
        $Obj = new Fanwires();
        $username = $_REQUEST['fburl'];
        $fanwire_id = $_REQUEST['fanwire_id'];
        $url = 'http://graph.facebook.com/' . $username;
        $data = json_decode($Obj->get_data($url));
        $fid = $data->id;
        // store the images in S3
        $amazonObject = StorageFactory::getObject();
        $filename = $fid . "_avator.jpg"; //$username.'.jpg';
        $img = file_get_contents('https://graph.facebook.com/' . $fid . '/picture?type=large');
        $amazonObject->saveFileFromContents($img, "photos/" . $filename);
        list($width, $height) = getimagesize(IMAGE_URL . $filename);
        /* //creating thumbnails
          $file = DOC_ROOT_PATH.'/photos/thumbs/'.$filename;
          file_put_contents($file, $img);
         */

        $url = 'http://graph.facebook.com/' . $username . "?fields=cover";
        $data = json_decode($Obj->get_data($url));
        $cover_filename = $fid . "_timeline.jpg"; //$username.'_cover.jpg';
        $coverimage = file_get_contents($data->cover->source);
        $amazonObject->saveFileFromContents($coverimage, "photos/" . $cover_filename);
        $Obj->InsertFbPhotos($fanwire_id, $filename, $cover_filename);
        error_log($filename . "----------" . $cover_filename);
        echo $filename . "##" . $width . "##" . $height;
    }

    public function twitterProfileImagesAction() {
        $amazonObject = StorageFactory::getObject();
        // authontication
        /*  if(empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE and $_SESSION['admintype'] != SUBADMIN_TYPE))
          {
          header("Location:".ADMIN_URL);
          exit();
          } */
        $Obj = new Fanwires();
        $username = $_REQUEST['twitterurl'];
        $fanwire_id = $_REQUEST['fanwire_id'];

        $url = 'https://api.twitter.com/1/users/profile_image?screen_name=' . $username . '&size=bigger';
        $filename = strtotime(date("Y-m-d H:i:s")) . "_avator.jpg"; //$username.'.jpg';
        $img = file_get_contents($url);
        $amazonObject->saveFileFromContents($img, "photos/" . $filename);
        /*
          $file = DOC_ROOT_PATH . '/photos/' . $filename;
          file_put_contents($file, $img);

          $file = DOC_ROOT_PATH . '/photos/thumbs/' . $filename;
          file_put_contents($file, $img);

         */
        list($width, $height) = getimagesize(IMAGE_URL . $filename);
        $Obj->InsertTwitterPhotos($fanwire_id, $filename);
        echo $filename . "##" . $width . "##" . $height;
    }

    public function getFanwiresAction() {
        $q = strtolower($_GET["q"]);
        $obj = new Fanwires();
        echo $obj->getFanwireNames($q);
    }

    public function deleteKeywordAction() {
        // authentication
        if (empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE and $_SESSION['admintype'] != SUBADMIN_TYPE)) {
            header("Location:" . ADMIN_URL);
            exit;
        }
        $id = $_REQUEST['id'];
        $qObj = new Fanwires();
        $qObj->deleteKeyword($id);
    }

    public function deleteAdditionalFansAction() {
        // authentication
        if (empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE and $_SESSION['admintype'] != SUBADMIN_TYPE)) {
            header("Location:" . ADMIN_URL);
            exit;
        }
        $id = $_REQUEST['id'];
        $qObj = new Fanwires();
        $qObj->deleteAdditionalFans($id);
    }

    public function commentsListAction() {
        if (empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE and $_SESSION['admintype'] != SUBADMIN_TYPE)) {
            header("Location:" . ADMIN_URL);
            exit;
        }
        $id = $_REQUEST['id'];
        $type = $_REQUEST['type'];
        $obj = new Videos();
        $list = $obj->getComments($id, $type);
        $data['list'] = $list['list'];
        $data['navigation'] = $list['navigation'];
        $view = new View();
        $data['ADMIN'] = ADMIN;
        $data['referer_id'] = $id;
        $data['type'] = $type;
        $view->loadView($data, ADMIN . "fanwires/commentsList");
    }

    public function deleteCommentAction() {
        if (empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE and $_SESSION['admintype'] != SUBADMIN_TYPE)) {
            header("Location:" . ADMIN_URL);
            exit;
        }
        $id = $_REQUEST['id'];
        $type = $_REQUEST['type'];
        $referer_id = $_REQUEST['referer_id'];
        $obj = new Videos();
        $obj->deleteComment($id);
        header("Location:" . ADMIN_URL . "fanwires/commentsList?id=" . $referer_id . "&type=" . $type);
    }

    public function deleteFanwireCategoryAction() {
        // authentication
        if (empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE and $_SESSION['admintype'] != SUBADMIN_TYPE)) {
            header("Location:" . ADMIN_URL);
            exit;
        }
        $id = $_REQUEST['id'];
        $qObj = new Fanwires();
        $qObj->deleteFanwireCategory($id);
    }

    public function facebookListAction() {
        if (empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE and $_SESSION['admintype'] != SUBADMIN_TYPE)) {
            header("Location:" . ADMIN_URL);
            exit;
        }
        $fanwire_id = $_REQUEST['id'];
        $data['fanwire_id'] = $fanwire_id;
        $obj = new Articles();
        $fan = new Fanwires();
        if (isset($_REQUEST['release'])) {
            $dt_release = $fan->dateformat($_REQUEST['released_date']);
            $obj->updateReleasedDate($_REQUEST, $dt_release);
            header("Location:" . ADMIN_URL . "fanwires/facebookList?id=" . $fanwire_id);
        }


        $list = $obj->getFacebookList($fanwire_id);
        $data['list'] = $list['list'];
        $data['navigation'] = $list['navigation'];
        $view = new View();
        $data['date'] = date("Y-m-d H:i:s");
        $data['ADMIN'] = ADMIN;
        $view->loadView($data, ADMIN . "articles/facebookList");
    }

    public function twitterListAction() {
        if (empty($_SESSION['adminid']) or ($_SESSION['admintype'] != ADMIN_TYPE and $_SESSION['admintype'] != SUBADMIN_TYPE)) {
            header("Location:" . ADMIN_URL);
            exit;
        }
        $fanwire_id = $_REQUEST['id'];
        $data['fanwire_id'] = $fanwire_id;
        $obj = new Articles();
        $fan = new Fanwires();
        if (isset($_REQUEST['release'])) {
            $dt_release = $fan->dateformat($_REQUEST['released_date']);
            $obj->updateReleasedDate($_REQUEST, $dt_release);
            header("Location:" . ADMIN_URL . "fanwires/twitterList?id=" . $fanwire_id);
        }
        $list = $obj->getTwitterList($fanwire_id);
        $data['list'] = $list['list'];
        $data['navigation'] = $list['navigation'];
        $view = new View();
        $data['date'] = date("Y-m-d H:i:s");
        $data['ADMIN'] = ADMIN;
        $view->loadView($data, ADMIN . "articles/twitterList");
    }

}

//end class
?>
