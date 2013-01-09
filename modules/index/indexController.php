
<?php

/**
 * Description of indexController
 *
 * @author naresh
 * */
class indexController {
    /* login
     * september 20
     */

    public function loginAction() {

        global $fc;
        global $facebook;
        $data = array();
        $object = new Users(); //Creating Users class object
        $errorObject = new Errors(Error_XML); //creating Error class object
        $obj = new Fanwires();
        $data['pagetitle'] = 'FanWire | Login';
        $data['metadescription'] = 'Fan Wire is a social media news platform that gives you news and information on music, celebrities, sports athletes, and sports teams for all social news websites.';
        //echo "<pre>";print_r($_REQUEST);
        if (isset($_REQUEST['submit']) && $_REQUEST['submit'] == 'Login') {
            //check the login credential weather they are correct or not
            $rs = $object->isRegisteredUser($_POST['fan_email'], md5($_POST['fan_password']));
            if ($rs) {
                if ($rs[0]['status'] != 2) {
                    if ($rs) {
                        $_SESSION['name'] = $rs[0]['fname'];
                        $_SESSION['id'] = $rs[0]['id'];
                        $res = $object->updateLogin($rs[0]['id']);
                        if ($res == '0' || $res == 0) {
                            header("Location:" . SITE_URL . 'addFanwires');
                        } else {
                            header("Location:" . SITE_URL . 'myFanwire');
                        }
                    } else {
                        $data['ermsg'] = $errorObject->getErrorMessage('usernotavail');
                    }
                } else {
                    $data['ermsg'] = $errorObject->getErrorMessage('notactive');
                }
            } else {
                $data['ermsg'] = $errorObject->getErrorMessage('usernotavail');
            }
        }

        $view = new View();
        $view->loadView($data);
    }

    public function signupAction() {

        global $fc;
        global $facebook;
        $data = array();
        $object = new Users(); //Creating Users class object
        $errorObject = new Errors(Error_XML); //creating Error class object
        $dpObj = new DatePicker(); //creating object for DatePicker
        $obj = new Fanwires();
        $data['months'] = $dpObj->getMonths(); //get all months
        $data['days'] = $dpObj->getDays(); //get days
        $data['years'] = $dpObj->getYears(); //get years
        $data['path'] = session_id(); //need in guest page to fetch zoom values
        $data['active'] = 'connect';

        $data['pagetitle'] = 'FanWire | Signup';
        $data['metadescription'] = 'Fan Wire is a social media news platform that gives you news and information on music, celebrities, sports athletes, and sports teams for all social news websites.';

        if (isset($_REQUEST['submit']) && $_REQUEST['submit'] == 'Connect') {
            // echo "<pre>";print_r($_REQUEST);exit;
            if ($object->isDuplicateEmail($_POST['email']) > 0) {
                $data['ermsg'] = $errorObject->getErrorMessage('emailEx');
                $data['values'] = $_POST;
            } else {
                //insert the  profile data in  DB
                $id = $object->insertNewFanwire();
                //strore the users id and name in session for the next registration process
                $_SESSION['id'] = $id;
                $_SESSION['name'] = $_POST['firstname'];
                if ($id) {
                    $to = $_POST['email'];
                    $ip = SITE_URL; //$_SERVER['HTTP_HOST']; //get the ip address
                    $subject = 'Welcome To Fanwire';
                    $body['html'] = '<html><body><div style="background:#01738a; width:800px; height:1070px; margin:0 auto; padding-top:81px;"><div style="width:650px; height:995px; position:relative; background:#FFF; margin:0 auto;"><div style="background:url(http://www.fanwire.com/views/images/0_text.png) no-repeat 0 0; width:110px; height:122px; position:absolute; top:-67px; left:55px;"> </div><div style="color:#02a0bf; font-size:14px; padding-top:37px; text-align:right;width:548px; margin:0 auto;"><a href="' . SITE_URL . 'footer/contact"> Contact Us</a></div><div style="width:534px; margin:0 auto;"><p style="color:#686868; font-size:14px; padding-top:30px;">Dear Fellow Fan,</p><p style="color:#686868; font-size:14px; padding-top:5px;">I want to personally thank you for being a part of FanWire.Welcome!</p><p style="color:#686868; font-size:14px; padding-top:5px;">I started FanWire because there are many websites out there to bookmark, too many videos on YouTube to filter through, Tweets to scroll, or Fan Pages to click to, and we are busy people! This site is dedicated to the fans who want it all in one place, an easy and fast method of getting articles, photos, and videos for everything you are a fan of, without having you do the work. We do the work for you, just sit back, connect, and collect.</p><p style="color:#686868; font-size:14px; padding-top:5px;">When you log into FanWire, select some FanWires that interest you. Are we missing something? Please tell us! We have a special form so you can let us know who we need to add to the site, and we&rsquoll do our best to make it happen</p><p style="color:#686868; font-size:14px; padding-top:5px;">There are two features I want to introduce you to when you log back in: The Slider and the Collector. The Slider is the little bar at the top of the page, and when you click and drag it in either direction, it instantly resizes your FanWire blocks into a smaller or larger size. So if you prefer to have much smaller previews and want to skim through them faster, you can do that. If you prefer to have the blocks large and taking up your 40 inch screen like a boss, go for it!</p><p style="color:#686868; font-size:14px; padding-top:5px;">The other feature (and most important in my opinion) is the Collector. When you roll over a media block you can &quot;Collect&quot; an item, which allows you to save it for later in your My Collections section on the side. If you have music videos, photo galleries, or special articles you want to save, go for it. You can always uncollect them later.</p><p style="color:#686868; font-size:14px; padding-top:5px;">I hope this was a helpful introduction email. I want to make sure my fellow fans are happy and I&rsquo;ll continue to build the best site out there. You have my personal email, so if there&rsquo;s anything I can do for you, please let me know.!</p><p style="color:#686868; font-size:14px; padding-top:5px;">Best regards,</p><span style="float:left; padding-right:12px; padding-top:5px;"><img src="' . SITE_URL . 'views/images/0_kory.png" height="156" width="118" alt=""/></span><p>Kory (Founder / CEO)</p></div></div><div style="color:#FFF; font-size:12px;text-align:center; padding:5px 0 0 0;">&copy;Copyright 2012, FanWire</div></div></body> </html>';
                    if (Mailer::sendMail($to, $subject, $body, SUPPORT_EMAIL)) {
                        header("Location:" . SITE_URL . 'addFanwires');
                    } else {
                        $data['ermsg'] = $error = $errorObject->getErrorMessage("InvalidAddress");
                    }
                } else {
                    echo "something going wrong";
                }
            }
        }

        $view = new View();
        $view->loadView($data);
    }

    //Method :indexAction
    public function indexAction() {
        global $fc;
        global $facebook;
        $data = array();
        $object = new Users(); //Creating Users class object
        $errorObject = new Errors(Error_XML); //creating Error class object
        $dpObj = new DatePicker(); //creating object for DatePicker
        $obj = new Fanwires(); //create object for the Fanwire class
        $data['months'] = $dpObj->getMonths(); //get all months
        $data['days'] = $dpObj->getDays(); //get days
        $data['years'] = $dpObj->getYears(); //get years
        $data['active'] = "fanwire";
        $data['path'] = session_id(); //need in guest page to fetch zoom values
        $data['pagetitle'] = 'FanWire | Music | Celebrity | Entertainment News and Social Media';
        $data['metadescription'] = 'Fan Wire is a social media news platform that gives you news and information on music, celebrities, sports athletes, and sports teams for all social news websites.';
        if (isset($_GET['cnfrm']) && $_GET['cnfrm'] == 'fNwraCt') {
            $data['activatedmsg'] = $errorObject->getErrorMessage('activated');
        } elseif (isset($_GET['cnfrm']) && $_GET['cnfrm'] == 'fNwr') {
            $data['activatedmsg'] = $errorObject->getErrorMessage('activate');
        }
        if ($_SESSION['id'])
            $session = $_SESSION['id'];
        else
            $session = session_id();
        $_REQUEST['path'] = $session;
        $filters = $object->filterValues($session, $_REQUEST); //get the selected twitter values
        $data['filters'] = $filters;
        if (empty($filters)) {
            $list = $obj->getHomeDetails();
        } else {
            if ($filters['article'] == 0 && $filters['facebook'] == 0 && $filters['twitter'] == 0 && $filters['video'] == 0 && $filters['photo'] == 0 && $filters['instagram'] == 0) {
                $list = $obj->getFiltersPostsOnly(0, 30, ''); //""; //$obj->getHomeDetails();
            } else {

                $in = '';
                $photo = '';
                if ($filters['twitter'] == 1)
                    $in .=",'2'"; //',' . TWT_TYPE;
                if ($filters['facebook'] == 1)
                    $in .=",'1'"; //',' . FACE_TYPE;
                if ($filters['article'] == 1)
                    $in .=",'3'"; //',' . OTHER_TYPE;
                if ($filters['video'] == 1)
                    $video = "4"; //',' . OTHER_TYPE;
                if ($filters['photo'] == 1)
                    $photo .= ",'fanwire.com'"; //',' . OTHER_TYPE;
                if ($filters['instagram'] == 1)
                    $photo .= ",'Instagram'"; //',' . OTHER_TYPE;      
                /*                 * search operation */
                if (isset($_POST['searchterm']) && $_POST['searchterm'] != '') {
                    $data = array();
                    //labels
                    $data['resultsfor'] = "Results for : ";
                    $data['searchedfor'] = $_POST['searchterm'];
                    $searchterm = $_POST['searchterm'];
                    $searchcriteria = $_POST["searchcriteria"]; //all,profiles,media,social,my collection
                    $profiles_lbl_active = '';
                    $media_lbl_active = '';
                    $social_lbl_active = '';
                    $mycollection_lbl_active = '';
                    $all_lbl_active = '';
                    if ($searchcriteria == 'profiles') {
                        $searchcriteria = 'profiles';
                        $profiles_lbl_active = ' class="active"';
                    } else if ($searchcriteria == 'media') {
                        $searchcriteria = 'media';
                        $media_lbl_active = ' class="active"';
                    } else if ($searchcriteria == 'social') {
                        $searchcriteria = 'social';
                        $social_lbl_active = ' class="active"';
                    } else if ($searchcriteria == 'mycollection') {
                        $searchcriteria = 'mycollection';
                        $mycollection_lbl_active = ' class="active"';
                    } else {
                        $searchcriteria = 'all';
                        $all_lbl_active = ' class="active"';
                    }
                    $list = $obj->getSearchedPosts(0, 30, trim($in, ','), $video, $photo, $searchterm, $searchcriteria);
                    $searchcounts = explode(',', $_SESSION['searchcounts']);
                    $data['all_filter_lbl'] = '<li' . $all_lbl_active . '><a href="#" id="all_c"><span><strong> ALL (' . $searchcounts[0] . ')</strong></span></a></li>';
                    $data['profiles_filter_lbl'] = '<li' . $profiles_lbl_active . '><a href="#" id="profiles_c"><span><strong> PROFILES (' . $searchcounts[1] . ')</strong></span></a></li>';
                    $data['media_filter_lbl'] = '<li' . $media_lbl_active . '><a href="#" id="media_c"><span><strong> MEDIA (' . $searchcounts[2] . ')</strong></span></a></li>';
                    $data['social_filter_lbl'] = '<li' . $social_lbl_active . '><a href="#" id="social_c"><span><strong> SOCIAL (' . $searchcounts[3] . ')</strong></span></a></li>';
                    if (count($list) == 0) {
                        $returndata = file_get_contents(SITE_URL . 'didyoumean.php?phrase=' . $_POST['searchterm']);
                        if ($returndata != '') {
                            $list = $obj->getSearchedPosts(0, 30, trim($in, ','), $video, $photo, $returndata, $searchcriteria);
                        }
                        if (count($list) > 0) {
                            $data['resultsfor'] = "Sorry, no results for ";
                            $data['searchedfor'] = $_POST['searchterm'];
                            $data['didyoumean_lbl'] = "But we found some results for ";
                            $data['didyoumean'] = $returndata;
                            $searchcounts = explode(',', $_SESSION['searchcounts']);
                            $data['all_filter_lbl'] = '<li' . $all_lbl_active . '><a href="#" id="all_c"><span><strong> ALL (' . $searchcounts[0] . ')</strong></span></a></li>';
                            $data['profiles_filter_lbl'] = '<li' . $profiles_lbl_active . '><a href="#" id="profiles_c"><span><strong> PROFILES (' . $searchcounts[1] . ')</strong></span></a></li>';
                            $data['media_filter_lbl'] = '<li' . $media_lbl_active . '><a href="#" id="media_c"><span><strong> MEDIA (' . $searchcounts[2] . ')</strong></span></a></li>';
                            $data['social_filter_lbl'] = '<li' . $social_lbl_active . '><a href="#" id="social_c"><span><strong> SOCIAL (' . $searchcounts[3] . ')</strong></span></a></li>';
                            $data['mycollection_filter_lbl'] = '<li' . $mycollection_lbl_active . '><a href="#" id="mycollection_c"><span><strong> MY COLLECTION (' . $searchcounts[4] . ')</strong></span></a></li>';
                        }
                    }
                    /*                     * search end */
                } else {
                    $list = $obj->getFiltersPostsOnly(0, 30, trim($in, ','), $video, trim($photo, ','));
                }
            }
        }
        // echo "<pre>";print_r($data);
        if (isset($_REQUEST['submit']) && $_REQUEST['submit'] == 'Login') {
            //check the login credential weather they are correct or not
            $rs = $object->isRegisteredUser($_POST['fan_email'], md5($_POST['fan_password']));
            if ($rs) {
                if ($rs[0]['status'] != 2) {
                    if ($rs) {
                        $_SESSION['name'] = $rs[0]['fname'];
                        $_SESSION['id'] = $rs[0]['id'];
                        $res = $object->updateLogin($rs[0]['id']);
                        if ($res == '0' || $res == 0) {
                            header("Location:" . SITE_URL . 'addFanwires');
                        } else {
                            header("Location:" . SITE_URL . 'myFanwire');
                        }
                    } else {
                        $data['ermsg'] = $errorObject->getErrorMessage('usernotavail');
                    }
                } else {
                    $data['ermsg'] = $errorObject->getErrorMessage('notactive');
                }
            } else {
                $data['ermsg'] = $errorObject->getErrorMessage('usernotavail');
            }
        }
        $data['fanwiresguest'] = $list; //$object->getMyFanwiresGuest();
        $data['default_width'] = '246';
        //$data['fanwires_for_moreguest'] = $object->getMyFanwiresGuest();
        if (isset($_SESSION['name'])) {
            header("Location:" . SITE_URL . 'myFanwire');
        }
        $obj->getHistory(); //get the history which is recorded
        $data['history'] = $_SESSION['history'];
        $data['zoomValues'] = $obj->saveZoomValues($data['path'], $session, '', '', '');
        if ($data['zoomValues'] == 1)
            $data['zoomValues'] = array(array('value' => 68, 'width' => 246, 'height' => 186));
        $view = new View();
        $view->loadView($data);
    }

    public function StepFurtherAction() {

        global $fc;
        global $facebook;
        $data = array();
        $object = new Users(); //Creating Users class object
        $dpObj = new DatePicker(); //creating object for DatePicker
        $data['months'] = $dpObj->getMonths(); //get all months
        $data['days'] = $dpObj->getDays(); //get days
        $data['years'] = $dpObj->getYears(); //get years
        $errorObject = new Errors(Error_XML); //creating Error class object
        $data['fanwiresguest'] = $object->getMyFanwiresGuest();
        $data['fanwires_for_moreguest'] = $object->getMyFanwiresGuest();
        //$ar = $object->getCategory('celebrity');
        //$data['fanwiresCelebrity'] = $object->getAllFanwiresCatPop($ar[0]['id']);
        $data['fanwiresCelebrity'] = $object->getAllFanwiresWithOutCat2();
        //for facebook lofin
        # Let's see if we have an active session
        $session = $facebook->getSession();
        if (!empty($session)) {
            # Active session, let's try getting the user id (getUser()) and user info (api->('/me'))
            try {
                $uid = $facebook->getUser();
                $user = $facebook->api('/me');
            } catch (Exception $e) {
                echo $e;
            }
        } else {
            # There's no active session, let's generate one
            $data['login_fb_url'] = $login_url = $facebook->getLoginUrl();
            if (isset($_SESSION['logout_fb_url'])) {
                $_SESSION['logout_fb_url'] = $logout_url = $facebook->getLogoutUrl();
            } else {
                $data['login_fb_url'] = $login_url = $facebook->getLoginUrl();
            }
        }
        //facebook login end
        $path = DOC_ROOT_PATH . "/profile_images/";
        $pat = SITE_URL . "profile_images/";
        $valid_formats = array("jpg", "png", "gif", "bmp", "jpeg", "JPG", "PNG", "GIF", "BMP", "JPEG");
        $userDetails = $object->getUserDetails($_SESSION['id']);
        $data['profile_image'] = $userDetails[0]['profile_image'];
        if (!empty($data['profile_image'])) {
            $data['image'] = "<img id='photo' name='photo' src='profile_images/" . $data['profile_image'] . "'   >";
        }


        if (isset($_POST['upload']) and $_POST['upload'] == 'upload') {
            $name = $_FILES['photoimg']['name'];
            $size = $_FILES['photoimg']['size'];
            $imgsize = getimagesize($_FILES['photoimg']['tmp_name']);

            if ($imgsize[0] < 200 || $imgsize[1] < 200) {
                $data['error'] = " Your image is too small. Please upload a larger image";
            } else if (strlen($name)) {
                list($txt, $ext) = explode(".", $name);
                $exxt = trim(strrchr($name, "."), '.');
                if (in_array($exxt, $valid_formats)) {
                    $actual_image_name = time() . substr(str_replace(" ", "_", $txt), 5) . "." . $ext;
                    $tmp = $_FILES['photoimg']['tmp_name'];
                    $image = new SimpleImage();
                    $image->load($tmp);
                    $image->resizeToWidth(225);
                    $image->save('tmp/' . $name);
                    $data['image'] = "<img id='photo' name='photo' src='tmp/" . $name . "'   >";
                }
                else
                    echo "Invalid file format..";
            }
            else
                echo "Please select image..!";
        }

        if (isset($_REQUEST['x1'])) {
            $arr = explode("/", $_REQUEST['actual_image_name']);
            $image_name = $arr[1]; // $_REQUEST['actual_image_name']; //$arr[2];
            if (!isset($image_name)) {
                $image_name = $arr[0];
            }
            list($txt, $ext) = explode(".", $image_name);
            $actual_image_name = time() . substr(str_replace(" ", "_", $txt), 5) . "." . $ext;
            @copy("tmp/" . $image_name, "profile_images/" . $actual_image_name) or die('image copy failed');
            $object->updateProfile($actual_image_name);
            @unlink("tmp/" . $image_name);
            header('Location:' . SITE_URL . 'addFanwires');
            exit;
        }
        $data['actual_image_name'] = $actual_image_name;

        $obj = new Fanwires(); //create object for the Fanwire class
        $obj->getHistory(); //get the history which is recorded
        $data['history'] = $_SESSION['history'];
        $view = new View();
        $view->loadView($data);
    }

    /*
     * Finding the fans using the other social networks
      creation date:23-04-2012
     */

    public function findFansAction() {
        global $fc;
        $object = new Users(); //Create Users class object
        $errorObject = new Errors(Error_XML); //create the Error class object
        $data = array();
        //to find the gmail contacts we need to configure
        if ($_SESSION['name']) {
            $data['userdetails'] = $userDetails = $object->getUserDetails($_SESSION['id']);
            $data['profile_image'] = SITE_URL . "profile_images/" . $userDetails[0]['profile_image'];
            $oauth = new GmailOath(GOOGLE_CONSUMER_KEY, GOOGLE_CONSUMER_SECRET, $argarray, $debug, GOOGLE_CALL_BACK_URL);
            $getcontact = new GmailGetContacts();
            $access_token = $getcontact->get_request_token($oauth, false, true, true);
            $_SESSION['oauth_token'] = $access_token['oauth_token'];
            $_SESSION['oauth_token_secret'] = $access_token['oauth_token_secret'];
            $data['link'] = $oauth->rfc3986_decode($access_token['oauth_token']);
        } else {
            header("Location:" . SITE_URL);
        }
        $obj = new Fanwires(); //create the Fanwire class object
        $obj->getHistory(); //get the history
        $data['history'] = $_SESSION['history'];
        $view = new View();
        $view->loadView($data, 'index/find_your_fans');
    }

    /*
     * Uploads a profile pic
      creation date:24-04-2012
     */

    public function uploadProfilePicAction() {
        global $fc;
        $actual_image_name = "";
        $object = new Users(); //Creating the object for the class Users
        $errorObject = new Errors(Error_XML); //Creating the object for the class Errors
        $data = array();
        $data['preview_title'] = "Preview";
        $data['active'] = 'pic';
        $path = DOC_ROOT_PATH . "/profile_images/";
        $pat = SITE_URL . "profile_images/";
        if (!isset($_SESSION['name'])) {
            header("Location:" . SITE_URL);
        }
        $valid_formats = array("jpg", "png", "gif", "bmp", "jpeg", "JPG", "PNG", "GIF", "BMP", "JPEG");
        $userDetails = $object->getUserDetails($_SESSION['id']);
//SITE_URL .
        if ($userDetails[0]['profile_image'] != "")
            $data['profile_image'] = "profile_images/" . $userDetails[0]['profile_image'];
        else
            $data['profile_image'] = "";

        if (isset($_POST['upload']) and $_POST['upload'] == 'upload') {
            #print_r($_FILES);
            $name = $_FILES['photoimg']['name'];
            $size = $_FILES['photoimg']['size'];
            $imgsize = getimagesize($_FILES['photoimg']['tmp_name']);

            if ($imgsize[0] < 200 || $imgsize[1] < 200) {
                $data['error'] = " Your image is too small. Please upload a larger image";
            } else if (strlen($name)) {

                list($txt, $ext) = explode(".", $name);
                $exxt = trim(strrchr($name, "."), '.');
                #print_r(explode(".", $name));
                if (in_array($exxt, $valid_formats)) {
                    if ($size < (1024 * 1024)) {
                        $actual_image_name = time() . substr(str_replace(" ", "_", $txt), 5) . "." . $ext;
                        $tmp = $_FILES['photoimg']['tmp_name'];
                        $image = new SimpleImage();
                        $image->load($tmp);
                        $image->resizeToWidth(225);
                        $image->save('tmp/' . $name);
                        $data['preview_title'] = "Preview";
                        //                        if (move_uploaded_file($tmp, "tmp/" . $name)) {
                        $data['image'] = "<img id='photo' name='photo' src='tmp/" . $name . "'   >";
                        //                        }
                        //                        else
                        //                            echo "failed";
                    }
                    else
                        echo "Image file size max 1 MB";
                }
                else
                    echo "Invalid file format..";
            }
            else
                echo "Please select image..!";

            //exit;
        }

        if (isset($_REQUEST['x1'])) {
            //echo'<pre>'; print_r($_REQUEST); echo'</pre>'; exit;
            $arr = explode("/", $_REQUEST['actual_image_name']);
            $image_name = $arr[count($arr) - 1]; // $_REQUEST['actual_image_name']; //$arr[2];

            if (!isset($image_name)) {
                $image_name = $arr[0];
            }
            list($txt, $ext) = explode(".", $image_name);

            $actual_image_name = time() . substr(str_replace(" ", "_", $txt), 5) . "." . $ext;
            $po = strpos($_REQUEST['actual_image_name'], "tmp");
            if ($po === false) {
                @copy("profile_images/" . $image_name, "profile_images/" . $actual_image_name) or die('image copy failed');
                $object->updateProfile($actual_image_name);
                @unlink("profile_images/" . $image_name);
            } else {
                @copy("tmp/" . $image_name, "profile_images/" . $actual_image_name) or die('image copy failed');
                $object->updateProfile($actual_image_name);
                @unlink("tmp/" . $image_name);
            }


            header('Location:' . SITE_URL . 'myFanwire');
        }
        $data['actual_image_name'] = $actual_image_name;
        $obj = new Fanwires(); //create the object for the fanwire class
        $obj->getHistory(); //get history
        $data['history'] = $_SESSION['history'];
        $view = new View();
        $view->loadView($data);
    }

    /*
     * Adding addFanwires
      creation date:24-04-2012
     */

    public function addFanWiresAction() {
        global $fc;
        $object = new Users(); //Creating the object for the class Users class
        $errorObject = new Errors(Error_XML); //Creating the object for the class Errors
        $objectFanwires = new Fanwires(); //create the object for fanwires class

        $data = array();
        $data['active'] = 'addFanWires';
        //get the  category id
        //check weather user already a fan of the list or not
        if (isset($_SESSION['name'])) {
            $data['userdetails'] = $userDetails = $object->getUserDetails($_SESSION['id']);
            $data['profile_image'] = SITE_URL . "profile_images/" . $userDetails[0]['profile_image'];

            $cat = new Categories();
            $data['categories'] = $cat->getCategories();
            $data['selected_cat'] = $data['categories'][0]['id'];
            //get all fanwires by category
            //$ar = $object->getCategory('celebrity');
            $data['fanwiresCelebrity'] = $object->getAllFanwiresCatPop($data['selected_cat']);
            $data['zoomValues'] = $objectFanwires->saveZoomValues('addFanwires', $_SESSION['id'], '', '', '');
        } else {
            header("Location:" . SITE_URL);
        }
        $obj = new Fanwires(); //Creating the object for the class Fanwire class
        $obj->getHistory();
        $data['history'] = $_SESSION['history'];
        $view = new View();
        $view->loadView($data, 'index/addFanWires');
    }

    /*
     *  Connecting to social media
      creation date:24-04-2012
     */

    public function ConnectToMediaAction() {

        global $fc;
        global $facebook;
        global $twitterObj;
        // See if there is a user from a cookie
        $object = new Users(); //Creating the object for the class Users
        $errorObject = new Errors(Error_XML); //Creating the object for the class Errors
        $data = array();
        $obj = new Fanwires(); //Creating the object for the class Fanwires

        $data['userdetails'] = $userDetails = $object->getUserDetails($_SESSION['id']);
        $data['profile_image'] = SITE_URL . "profile_images/" . $userDetails[0]['profile_image'];
        $obj->getHistory();
        $data['history'] = $_SESSION['history'];
        $view = new View(); //Creating the object for the class view
        $view->loadView($data); //load view
    }

    /**
     * Logout the application
     * created on :24-04-2012
     */
    public function logoutAction() {

        if (isset($_GET['dAx']) && $_GET['dAx'] == "KJBH123") {
            session_destroy(); //destroy the session
            header("Location:" . SITE_URL . '?dAx=KJBH123');
            exit; //redirecting to the main siteexit;
        }
        //       session_destroy(); //destroy the session
        unset($_SESSION['id']);
        unset($_SESSION['name']);
        header("Location:" . SITE_URL); //redirecting to the main site
    }

    /**

     * @CREATION DATE 24-04-2012
     * face book login
     * This function saves user name and id in data base who logins from facebook login
     */
    public function facebookLoginAction() {
        global $fc;
        $object = new Users(); //Creating the object for the class users
        $errorObject = new Errors(Error_XML); //Creating the object for the class Errors
        $data = array();
        if (!empty($_SESSION)) {
            //header("Location:".SITE_URL);
        }
        # Creating the facebook object
        $facebook = new Facebook(array(
                    'appId' => FB_APP_ID,
                    'secret' => FB_APP_SECRET,
                    'cookie' => true
                ));
        # Let's see if we have an active session
        $session = $facebook->getSession();
        if (!empty($session)) {
            # Active session, let's try getting the user id (getUser()) and user info (api->('/me'))
            try {
                $uid = $facebook->getUser();
                $user = $facebook->api('/me');
                echo"<pre>";
                print_r($user);
            } catch (Exception $e) {
                echo $e;
            }
            if (!empty($user)) {
                # We have an active session, let's check if we have already registered the user
                $result = $object->isAlreadyRegisteredUserUsingFb($user['id']);
                # If not, let's add it to the database
                if (empty($result)) {
                    //print_r($result);exit;
                    $result = $object->RegisteredUserUsingFb($user['id'], $user['name'], $user['first_name'], $user['last_name'], $user['gender']);
                }

                // this sets variables in the session
                $_SESSION['id'] = $result[0]['id'];
                $_SESSION['oauth_uid'] = $result[0]['oauth_uid'];
                $_SESSION['oauth_provider'] = $result[0]['oauth_provider'];
                $_SESSION['name'] = $result[0]['username'];


                // header("Location:" . SITE_URL . 'findFans');
                header("Location:" . SITE_URL . 'getEmailPassword');
            } else {
                # For testing purposes, if there was an error, let's kill the script
                die("There was an error.");
            }
        } else {
            # There's no active session, let's generate one
            $login_url = $facebook->getLoginUrl();
            header("Location: " . $login_url);
        }
        $obj = new Fanwires(); //Creating the object for the class Fanwires
        $obj->getHistory();
        $data['history'] = $_SESSION['history'];
        $view = new View(); //Creating the object for the class View
        $view->loadView($data, 'index/index'); //load view
    }

    /**
     * @CREATION DATE 24-04-2012
     * twitter login
     * This function saves user name and id in data base who logins from facebook login
     */
    public function twitterLoginAction() {
        global $fc;
        $object = new Users(); //Creating the object for the class Users
        $errorObject = new Errors(Error_XML); //Creating the object for the class Errors
        $data = array();
        $twitterObj = new EpiTwitter(YOUR_CONSUMER_KEY, YOUR_CONSUMER_SECRET); //Creating the object for the class EPI TWITTER class
        $oauth_token = $_GET['oauth_token'];
        $data['active'] = 'connect';
        if ($oauth_token == '') {
            $url = $twitterObj->getAuthorizationUrl();
            header("Location:" . $url);
            //echo "<a href='$url'>Sign In with Twitter</a>";
        } else {
            $twitterObj->setToken($_GET['oauth_token']);
            $token = $twitterObj->getAccessToken();
            $twitterObj->setToken($token->oauth_token, $token->oauth_token_secret);
            //check weather already registered with using twitter r not
            $result = $object->isAlreadyRegisteredUserUsingTwitter($token->screen_name);
            //echo "<pre>";print_r($result);exit();
            # If not, let's add it to the database
            if (empty($result)) {
                $url = 'https://api.twitter.com/1/users/profile_image?screen_name=' . $token->screen_name . '&size=original';
                $filename = strtotime(date("Y-m-d H:i:s")) . ".jpg"; //$username.'.jpg';
                $img = file_get_contents($url);
                $file = DOC_ROOT_PATH . '/profile_images/' . $filename;
                file_put_contents($file, $img);
                $result = $object->RegisteredUserUsingTwitter($token->user_id, $token->oauth_token, $token->oauth_token_secret, $token->screen_name, $filename);
            }
            // this sets variables in the session
            $_SESSION['ot'] = $result[0]['twitter_oauth_token'];
            $_SESSION['ots'] = $result[0]['twitter_oauth_token_secret'];
            $_SESSION['name'] = $result[0]['username'];
            $_SESSION['twitter_id'] = $result[0]['oauth_uid'];
            $_SESSION['id'] = $result[0]['id'];
            $_SESSION['image'] = $result[0]['profile_image'];

            if ($result[0]['email'] != '')
                header("Location:" . SITE_URL . 'addFanwires');
            else
                header("Location:" . SITE_URL . 'getEmailPassword');
        }
        $obj = new Fanwires(); //Creating the object for the class Fanwires
        $obj->getHistory();
        $data['history'] = $_SESSION['history'];
        $view = new View(); //Creating the object for the class View
        $view->loadView($data, 'index/index'); //load view
    }

    //testing


    public function testContact2Action() {
        global $fc;
        $object = new Users(); //Creating the object for the class Users
        $errorObject = new Errors(Error_XML); //Creating the object for the class Errors
        $data = array();
        $consumer_key = 'test.mobiledevelopersdirectory.com';
        $consumer_secret = 'COdnKAPm394GXadw6OEKqvJE';
        $callback = 'http://test.mobiledevelopersdirectory.com/fanwire/testContact2';
        $emails_count = '500';
        $oauth = new GmailOath($consumer_key, $consumer_secret, $argarray, $debug, $callback);
        //$oauth =new GmailOath(GOOGLE_CONSUMER_KEY, GOOGLE_CONSUMER_SECRET, $argarray, $debug, GOOGLE_CALL_BACK_URL);
        $getcontact_access = new GmailGetContacts();

        $request_token = $oauth->rfc3986_decode($_GET['oauth_token']);
        $request_token_secret = $oauth->rfc3986_decode($_SESSION['oauth_token_secret']);
        $oauth_verifier = $oauth->rfc3986_decode($_GET['oauth_verifier']);

        $contact_access = $getcontact_access->get_access_token($oauth, $request_token, $request_token_secret, $oauth_verifier, false, true, true);

        $access_token = $oauth->rfc3986_decode($contact_access['oauth_token']);
        $access_token_secret = $oauth->rfc3986_decode($contact_access['oauth_token_secret']);
        $contacts = $getcontact_access->GetContacts($oauth, $access_token, $access_token_secret, false, true, $emails_count);

        //echo "<pre>";print_r($contacts);exit;
        $i = 0;
        foreach ($contacts as $k => $a) {

            $final = end($contacts[$k]);

            foreach ($final as $email) {

                $data['add'][$i] = $email["address"];
                //echo $email["address"] ."<br />";
            }
            //$data['contac'][$i]=$final;
            $i++;
        }

        $obj = new Fanwires(); //Creating the object for the class Fanwires
        $obj->getHistory();
        $data['history'] = $_SESSION['history'];
        $view = new View(); //Creating the object for the class View
        $view->loadView($data, 'index/testContact2'); //load view
    }

    //inviting the people
    public function inviteAction() {

        $errorObject = new Errors(Error_XML); //Creating the object for the class Error
        $data = array();

        $det = $_POST['det'];

        //sending invitation
        $to = 'nareshgumte412@gmail.com';
        $cc = trim($det, ',');
        $subject = 'Fanwire Invitation';
        $body['html'] = <<<EOT
Hello  ,<br />
You are invited to join the <a href='http://202.65.136.24/fanwire'>fanwire</a> <br />
Thanks,<br />
Fanwire Inc.
EOT;


        if (Mailer::sendMail($to, $subject, $body, SUPPORT_EMAIL, '', '', $cc, '')) {
            echo 'true';
            // header("Location:" . SITE_URL . '/uploadProfilePic');
        } else {
            echo 'false';
            // $error = $errorObject->getErrorMessage("InvalidAddress");
        }
    }

    /*
     * Forgot password
     */

    public function forgotpasswordAction() {
        global $fc;
        $object = new Users(); //Creating the object for the class Users
        $errorObject = new Errors(Error_XML); //Creating the object for the class Errors
        $data = array();
        $dpObj = new DatePicker(); //creating object for DatePicker
        $data['months'] = $dpObj->getMonths(); //get all months
        $data['days'] = $dpObj->getDays(); //get days
        $data['years'] = $dpObj->getYears(); //get years

        if (isset($_REQUEST['fgt'])) {//check weather form has submited or not
            if ($_POST['fan_email'] == '') {//check wwheather email is empty or not
                $error = "Please Enter the email address";
            } else {

                $rs = $object->isDuplicateEmail($_POST['fan_email']); //checking the email which is available as per the records(login tabe)
                //print_r($rs);
                // validating user
                if ($rs <= 0) {
                    echo $error = "This Email address is not matched with our records";
                } else { // sending mail
                    $to = $_POST['fan_email'];
                    $subject = "your password";
                    $rand = rand(000000, 999999);
                    $body = <<<EOT
Hello,
Your new password is $rand.
Thanks,
Fanwire Team

EOT;
                    if ($object->forgotPass(md5($rand), $_POST['fan_email'])) {
                        if (Mailer::sendMail($to, $subject, $body, SUPPORT_EMAIL)) {
                            $error = "Password has been sent to provided email address";
                        } else {
                            $error = "email failed";
                        }
                    } else {
                        $error = 'Ooops something went wrong';
                    }
                }
            }
        }

        $data['error'] = $error; //if there any errors store in this data object
        $obj = new Fanwires(); //Creating the object for the class Fanwires
        $obj->getHistory();
        $data['pagetitle'] = 'FanWire | Forgot Password';
        $data['metadescription'] = 'Fan Wire is a social media news platform that gives you news and information on music, celebrities, sports athletes, and sports teams for all social news websites.';
        $data['history'] = $_SESSION['history'];
        $view = new View(); //Creating the object for the class View
        $view->loadView($data, 'index/forgot_password'); //load view
    }

    /*
      this function is used to get diffrent mail contacts
     */

    public function multiMailContactsAction() {
        global $fc;
        $object = new Users(); //Creating the object for the class Users
        $errorObject = new Errors(Error_XML); //Creating the object for the class Errors
        $data = array();
        $inviter = new openinviter(); //Creating the object for the class OpenInviter class
        $oi_services = $inviter->getPlugins();
        if (!empty($_POST['step']))
            $step = $_POST['step'];
        else
            $step = 'get_contacts';

        $ers = array();
        $oks = array();
        $import_ok = false;
        $done = false;
        $_SERVER['REQUEST_METHOD'];
        // if ($_SESSION['name']) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            if ($step == 'get_contacts') {
                if (empty($_POST['email_box']))
                    echo "Email missing !";
                else {
                    $provider = $inviter->getPluginByDomain($_POST['email_box']);
                    if (!empty($provider)) {
                        if (isset($oi_services['email'][$provider]))
                            $plugType = 'email';
                        elseif (isset($oi_services['social'][$provider]))
                            $plugType = 'social';
                        else
                            $plugType = '';
                    }
                    else
                        echo "Email missing !";
                }

                if (empty($_POST['password_box']))
                    echo "Password missing !";
                if (count($ers) == 0) {
                    $inviter->startPlugin($provider);
                    $internal = $inviter->getInternalError();

                    $contacts = $inviter->getMyContacts();
                    if ($internal)
                        echo $internal;
                    elseif (!$inviter->login($_POST['email_box'], $_POST['password_box'])) {
                        $internal = $inviter->getInternalError();
                        //echo ($internal ? $internal : "Login failed. Please check the email and password you have provided and try again later !");
                        ($internal ? $internal : $data['error'] = "failed");
                    } elseif (false === $contacts = $inviter->getMyContacts())
                        echo "Unable to get contacts !";
                    else {
                        $import_ok = true;
                        $step = 'send_invites';
                        $_POST['oi_session_id'] = $inviter->plugin->getSessionID();
                    }
                }
            } elseif ($step == 'send_invites') {
                if (!empty($_POST['email_box']))
                    $provider = $inviter->getPluginByDomain($_POST['email_box']);
                if (empty($provider))
                    echo 'Provider missing !';
                else {
                    $inviter->startPlugin($provider);
                    $internal = $inviter->getInternalError();
                    if ($internal)
                        echo $internal;
                    else {
                        if (empty($_POST['email_box']))
                            echo 'Inviter information missing !';
                        if (empty($_POST['oi_session_id']))
                            echo 'No active session !';

                        $selected_contacts = array();
                        $contacts = array();
                        $message = array('subject' => $inviter->settings['message_subject'], 'body' => $inviter->settings['message_body'], 'attachment' => "\n\rAttached message: \n\r" . $_POST['message_box']);
                        if ($inviter->showContacts()) {
                            foreach ($_POST as $key => $val)
                                if (strpos($key, 'check_') !== false)
                                    $selected_contacts[$_POST['email_' . $val]] = $_POST['name_' . $val];
                                elseif (strpos($key, 'email_') !== false) {
                                    $temp = explode('_', $key);
                                    $counter = $temp[1];
                                    if (is_numeric($temp[1]))
                                        $contacts[$val] = $_POST['name_' . $temp[1]];
                                }
                            if (count($selected_contacts) == 0)
                                echo "You haven't selected any contacts to invite !";
                        }
                    }
                }
                if (count($ers) == 0) {
                    $sendMessage = $inviter->sendMessage($_POST['oi_session_id'], $message, $selected_contacts);
                    $inviter->logout();
                    if ($sendMessage === -1) {
                        $message_footer = "\r\n\r\n Fanwire.";
                        $message_subject = $_POST['email_box'] . $message['subject'];
                        $message_body = $message['body'] . $message['attachment'] . $message_footer;
                        $headers = "From: {$_POST['email_box']}";
                        foreach ($selected_contacts as $email => $name)
                            mail($email, $message_subject, $message_body, $headers);
                        echo "Mails sent successfully";
                    } elseif ($sendMessage === false) {
                        $internal = $inviter->getInternalError();
                        echo ($internal ? $internal : "There were errors while sending your invites.<br>Please try again later!");
                    }
                    else
                        echo "Invites sent successfully!";
                    $done = true;
                }
            }
        }
        else {
            $_POST['email_box'] = '';
            $_POST['password_box'] = '';
        }

        if (!$done) {
            //  $se="<input type='checkbox' value='on' name='allbox' id='allbox' onclick='checkAll();'/> </td><td width='20%'>&nbsp;Select all </td>";
            $data['add'] = $contacts;
            $content = "<h2>Connect with fans you already known<br />";
            $content.=" <span>We call all of our friends ‘fans’ in these community. Everyone is a fan of something, so why not connect with them? We made this step easy for you.</span></h2><div class='connect_fans_contact'>";
            $content.="<form name='contacts' id='contacts' method='post' action='" . SITE_URL . "invite'><table><tr> <td width='10%'>  <input type='checkbox' value='on' name='allbox' id='allbox' onclick='checkAll();'  /> </td><td width='20%'>&nbsp;Select all </td><td width='60%'></td></tr>";
            foreach ($contacts as $k => $v) {
                if (!empty($v)) {
                    $content.="<tr><td><input type='checkbox' class='case' name='contacts[]' value='" . $k . "'></td><td>" . $v . "</td><td>" . $k . "</td></tr>";
                }
            }
            $content.="</table></div><div class='clear'></div><hr /><br /><div class='skip'><input name='submit' type='submit' class='continue_btn' id='get_id' value='Invite' onclick='inviteFriends();' /><br /><br /><br /><br /></form><a href='javascript:void(0);' onclick='document.getElementById('step1').style.display='none';document.getElementById('fade').style.display='none';document.getElementById('step2').style.display='block';document.getElementById('fade2').style.display='block''><i>Skip this step</i></a></div><div class='clear'></div>";
            echo $content;
        }
    }

    //this is for getting the user email id and password for the user fanwire
    public function getEmailPasswordAction() {
        global $fc;
        $object = new Users(); //Creating the object for the class Users
        $errorObject = new Errors(Error_XML); //Creating the object for the class Errors
        $obj = new Fanwires(); //Creating the object for the class Fanwire
        $data = array();
        $data['active'] = 'connect';
        if (isset($_SESSION['name'])) {
            if (isset($_REQUEST['import'])) {
                if (empty($_POST['email']) || empty($_POST['email'])) {
                    $data['msg'] = "Please Provide Email Id or Passwprd.";
                } else {
                    if ($object->isDuplicateEmail($_POST['email']) > 0) {
                        $data['msg'] = $errorObject->getErrorMessage("emailEx");
                    } else {
                        $res = $object->updateEmailPass($_POST['email'], md5($_POST['password']));
                        if ($res) {
                            header("Location:" . SITE_URL . 'addFanwires');
                        } else {
                            header("Location:" . SITE_URL . 'getEmailPassword');
                        }
                    }
                }
            }
        } else {
            header("Location:" . SITE_URL);
        }
        $data['path'] = $_REQUEST['path'];
        $obj->getHistory();
        $data['history'] = $_SESSION['history'];
        $data['terms'] = $obj->getTerms();
        $data['privacy'] = $obj->getPrivacy();
        $view = new View(); //Creating the object for the class View
        $view->loadView($data, 'index/getEmailPassword'); //load view
    }

    //adding fanwire to user means user is fan of selected on
    public function addFanwireAction() {

        global $fc;
        $objectUser = new Users(); //Creating the object for the class Users
        $errorObject = new Errors(Error_XML); //Creating the object for the class Error
        $data = array();
        if (isset($_SESSION['name'])) {
            //take data and add as fan

            $id_fanwire = $_POST['id']; //is fanwires id (celebritys id)
            echo $objectUser->addFanwire($id_fanwire);
        } else {
            //echo "session timed out";
            header("Location:" . SITE_URL);
        }
    }

    //unfanwire fanwire to user means user is fan of selected on
    public function unFanwireAction() {
        global $fc;
        $objectUser = new Users(); //Creating the object for the class Users
        $errorObject = new Errors(Error_XML); //Creating the object for the class Errors
        $data = array();

        if (isset($_SESSION['name'])) {
            //take data and add as fan
            $id_fanwire = $_POST['id']; //is fanwires id (celebritys id)
            echo $objectUser->unFanwire($id_fanwire);
        } else {
            header("Location:" . SITE_URL);
        }
    }

    //check weather user selected minimum threee fans or not
    public function checkMinThreeFanAction() {
        global $fc;
        $objectUser = new Users(); //Creating the object for the class Users
        $errorObject = new Errors(Error_XML); //Creating the object for the class Errors
        $data = array();

        if (isset($_SESSION['name'])) {
            echo $objectUser->checkMinThreeFan();
        } else {
            header("Location:" . SITE_URL);
        }
    }

    public function registerAjaxAction() {

        $data = array();
        $object = new Users(); //Creating Users class object
        $errorObject = new Errors(Error_XML); //creating Error class object
        //check weather email is exits or not
        if ($object->isDuplicateEmail($_POST['email']) > 0) {
            $data['msg'] = $errorObject->getErrorMessage('emailEx');
            echo 1;
            exit;
        } else {
            //insert the  profile data in  DB

            $id = $object->insertNewFanwire();
            //strore the users id and name in session for the next registration process
            $_SESSION['id'] = $id;
            $_SESSION['name'] = $_POST['firstname'];
            if ($id) {
                $to = $_POST['email'];
                $ip = SITE_URL; //$_SERVER['HTTP_HOST']; //get the ip address
                $subject = 'Fanwire Invitation';
                $body['html'] = " Hi " . $_POST['firstname'] . ",<br><br>
     Welcome to Fanwire !<br>  
 <p>Finally, a website where you get information on EVERYTHING you are a fan of. Never worry about missing the latest news on your favorite Athlete, Celebrity, Music Artist, Sports Team, and much more.</p>
 
 <p>FanWire allows you to connect your Facebook and Twitter accounts, customize your profile, and even contribute articles, photos, or videos to the rest of the world.</p>
 
 <p>So what are you waiting for? Click this link <a href='" . $ip . "emailConfirming?id=" . $id . "'>HERE</a> or copy and paste the confirmation link below.</p>

 

 <p>" . $ip . "emailConfirming?id=" . $id . "</p><br>

 Thanks<br><br>
 Team Fanwire<br><br>
<p>
 Note : This is NOT Spam . You have received this mail as a person named " . $_POST['firstname'] . " has registered your name to be a member of Fanwire.
 If you are not " . $_POST['firstname'] . " and this mail has reached you in error,please delete this mail and accept our apologies for the same. "
                ;
                if (Mailer::sendMail($to, $subject, $body, SUPPORT_EMAIL)) {
                    //header("Location:" . SITE_URL.'?cnf=1');

                    $data['msgq'] = $error = $errorObject->getErrorMessage("confirmsent");
                    echo 2;
                    exit;
                } else {
                    $data['ermsg'] = $error = $errorObject->getErrorMessage("InvalidAddress");
                    echo 3;
                }
            } else {
                echo "something going wrong";
                //   header("Location:" . SITE_URL);
            }
        }
    }

    /*
      Ajax image action for the croping the image
     */

    public function ajaxImageCropAction() {

        $object = new Users(); //Creating Users class object
        if (isset($_GET['t']) and $_GET['t'] == "ajax") {
            extract($_GET);
            $src_image = imagecreatefromjpeg($img);
            $dst_image = imagecreatetruecolor($w, $h);
            imagecopy($dst_image, $src_image, 0, 0, $x, $y, $w, $h);
            $ext = substr($img, -3, 3);
            if ($ext == "png")
                imagepng($dst_image, NULL, 90);
            else
                imagejpeg($dst_image, $img, 90);
            $arr = explode("/", $_REQUEST['actual_image_name']);
            $image_name = $arr[1]; // $_REQUEST['actual_image_name']; //$arr[2];
            if (!isset($image_name)) {
                $image_name = $arr[0];
            }
            list($txt, $ext) = explode(".", $image_name);
            $actual_image_name = time() . substr(str_replace(" ", "_", $txt), 5) . "." . $ext;
            //echo "name  = ".$image_name."   --  actual image".$actual_image_name;
            //echo "tmp/".$image_name,"profile_images/".$actual_image_name;
            @copy("tmp/" . $image_name, "profile_images/" . $actual_image_name) or die('image copy failed');
            // $pic_path = $pat . $actual_image_name;
            $object->updateProfile($actual_image_name);
            @unlink("tmp/" . $image_name);
            // header('Location:' . SITE_URL . 'addFanwires');

            exit();
        }
    }

    //this is for the popup fashin category fanwires list
    public function ajaxFashionCategoryFanwiresAction() {

        $object = new Users();
        $objectFanwires = new Fanwires();
        $cat = new Categories();
        $val = $_POST['val'];
        $fashions = $object->getAllFanwiresCatPop($val);
        $categories = $cat->getCategories();


        $dataFashion = "<div class=\"browser\"><p>Add 3 Profiles You Are Interested In </p><div class=\"menu\"><ul>";

        foreach ($categories as $value) {
            if ($val == $value['id'])
                $selected = "active";
            else
                $selected = "";
            $dataFashion.='<li  class="' . $selected . '"><a href = "javascript:void(0); " onclick = "nextPage(' . $value['id'] . ')" id = "celebrityPopup"><span><strong>' . $value['name'] . '</strong></span></a></li>';
        }

        $dataFashion.="</ul></div></div><div class = \"browser_comment\" id = \"mainBlock\">";

        foreach ($fashions as $key => $value) {
            if ($value['fan_status'] == 1) {
                $class = " class=\"comment_follow_img_active\" ";
            } else {
                $class = "class=\"comment_follow_img\" ";
            }
            $followers = ""; //"<span style = \"clear:right\">1, 234 Followed</span>";
            $dataFashion.="<div class = \"comment item block\"><div class = \"comment_img\"><img src = \"" . IMAGE_URL . $value['photo'] . "\" width = \"180\" height = \"" . (180 * ($value['height'] / $value['width'])) . "\" ></div><div class = \"comment_text\"><p>
            " . $value['name'] . "</p><span class=\"more\">" . $value['description'] . "</span> </div>
                <div class = \"comment_bottom\">
                <div class = \"comment_follow\">
                <a href = \"javascript:void(0);\" rel=\"" . $value['fan_status'] . "\" id=\"folunfol" . $value['id'] . "\" title=\"" . $value['id'] . "\"" . $class . "alt=\"" . $value['fan_status'] . "\"></a>" . $followers . " </div></div></div>";
        }

        $dataFashion.="</div>";



        echo $dataFashion;
    }

    public function loginAjaxAction() {


        $object = new Users();
        $errorObject = new Errors(Error_XML);
        $rs = $object->isRegisteredUser($_POST['fan_email'], md5($_POST['fan_password']));
        if ($rs) {

            if ($rs[0]['status'] != 2) {
                if ($rs) {
                    $_SESSION['name'] = $rs[0]['fname'];
                    $_SESSION['id'] = $rs[0]['id'];
                    $res = $object->updateLogin($rs[0]['id']);
                    $object->activateAccount($rs[0]['id']);
                    if ($res == '0' || $res == 0) {
                        echo 0;
                        exit;
                        //header("Location:" . SITE_URL . 'StepFurther?Axt=Itt');
                        //header("Location:" . SITE_URL . 'findFans');
                    } else {
                        echo 1;
                        exit;
                        //  header("Location:" . SITE_URL . 'myFanwire');
                    }
                } else {
                    echo $data['ermsg'] = $errorObject->getErrorMessage('usernotavail');
                    exit;
                }
            } else {
                echo $data['ermsg'] = $errorObject->getErrorMessage('notactive');
                exit;
            }
        } else {

            echo $data['ermsg'] = $errorObject->getErrorMessage('usernotavail');
            exit;
        }
    }

    public function manageSessionAction() {
        if (isset($_POST['destroy']) && $_POST['destroy'] == 'yes') {
            unset($_SESSION['fbid']);
            unset($_SESSION['fbusername']);
            exit;
        }
        $_SESSION['fbid'] = $_POST['fbid'];

        $link = explode('/', $_POST['fb_url_link']);
        $_SESSION['fbusername'] = $link['3'];
    }

    /**
     * @CREATION DATE 24-04-2012
     * face book login
     * This function saves user name and id in data base who logins from facebook login
     */
    public function CrawlAction() {
        $obj = new Photos();
        $object = new Users();
        $data['fanwires'] = $obj->getFanwires();
        $objectArticle = new Articles();
        $data['Ctype'] = $_REQUEST['Crawltype'];
        $data['Wsourceid'] = $_REQUEST['Crawltype'];
        $data['user'] = strtolower($_REQUEST['fanwirename']); //"lilwayne";
        //$data['fanwireid'] = $_REQUEST['websiteCrawlid'];
        $countPosts = $_REQUEST['count'];
        $data['countPost'] = $countPosts;
        // echo "<pre>";print_r($_REQUEST); exit; 
        $data['fanwires_sites'] = $obj->getFanSites("");
        if ($data['Ctype'] == "facebook") {

            $facebook = new Facebook(array(
                        'appId' => FB_APP_ID,
                        'secret' => FB_APP_SECRET,
                        'cookie' => true
                    ));

            $app_id = FB_APP_ID;
            $app_secret = FB_APP_SECRET;
            $my_url = SITE_URL . "facebookCrawl";
            $session = $facebook->getSession();
            if (empty($session)) {
                echo("<script> top.location.href='" . $facebook->getLoginUrl() . "'</script>");
            }
            if (isset($code)) {
                $token_url = "https://graph.facebook.com/oauth/access_token?client_id="
                        . $app_id . "&redirect_uri=" . urlencode($my_url)
                        . "&client_secret=" . $app_secret
                        . "&code=" . $code . "&display=popup"; //exit;
                $response = file_get_contents($token_url);
                $params = null;
                parse_str($response, $params);
                $access_token = $params['access_token'];
            }
            $k = 0;
            foreach ($_REQUEST['fanwirenameft'] as $key => $value) {
                //if facebook get facebook name
                $data['user'] = $value; //$_REQUEST['fanwirename']; //"lilwayne";
                $data['user'] = explode('*', $data['user']);
                $data['fanwireid'] = $data['user'][4];
                $data['user'] = end(explode('/', $data['user'][1]));


                $data['profie_image'] = "https://graph.facebook.com/" . strtolower($data['user']) . "/picture";

                $graph_url = "https://graph.facebook.com/" . $data['user'] . "/posts?"
                        . "access_token=" . $session['access_token'];
                $response = indexController::curl_get_file_contents($graph_url);
                $decoded_response = json_decode($response);
                //Check for errors
                if ($decoded_response->error) {
                    // check to see if this is an oAuth error:
                    if ($decoded_response->error->type == "OAuthException") {
                        // Retrieving a valid access token.
                        $dialog_url = "https://www.facebook.com/dialog/oauth?"
                                . "client_id=" . $app_id
                                . "&redirect_uri=" . urlencode($my_url);
                        echo("<script> top.location.href='" . $dialog_url
                        . "'</script>");
                    } else {
                        echo "other error has happened";
                    }
                } else {
                    echo $access_token;
                }
                $data['fbresult'] = $decoded_response->data;
                //$data[$k]['fbresults'] = $data['fbresult'];
                // error_log($k);
                //$k++;
                // echo'<pre>'; print_r( $data['fbresults']);  
                if ($data['fbresult']) {
                    //insert the data in database
                    $j = 0;
                    foreach ($data['fbresult'] as $i) {
                        if ($j < $countPosts) {
                            $list = array();
                            $list['id'] = $data['fanwireid'];
                            $list['name'] = $i->from->name;
                            $list['message'] = $i->message; //tweet
                            $list['time'] = $i->created_time; //creation time of post
                            $list['imageUrl'] = $i->picture; //image url of post image
                            //check the post is available or not
                            $checkArticleTitle = $objectArticle->checkArticle(mysql_escape_string(trim($list['message'])), 'facebook');
                            if ($checkArticleTitle == '0') {
                                if ($list['imageUrl'] != "") {
                                    if (!isset($i->source) && $i->caption == '') {
                                        $img = file_get_contents(str_replace("_s", "_n", $list['imageUrl']));
                                        $filename = strtotime(date("Y-m-d H:i:s")) . $list['name'] . $j . rand(8, 6666) . "_faceImg.jpg"; //$username.'.jpg';
                                        $file = DOC_ROOT_PATH . '/photos/' . $filename;
                                        file_put_contents($file, $img);
                                    } else {
                                        $filename = "";
                                    }
                                } else {
                                    $filename = "";
                                }
                                $list['imagename'] = $filename;

                                $objectArticle->addPostsFromFaceBook($list);
                            } else {
                                error_log("posts exists already");
                            }
                            $j++;
                        }
                    }
                }
            }
        } elseif ($data['Ctype'] == "twitter") {
            //echo "<pre>";print_r($_REQUEST);exit;
            foreach ($_REQUEST['fanwirenameft'] as $key => $value) {
                //if twitter get twitter username
                $data['user'] = $value; //$_REQUEST['fanwirename']; //"lilwayne";
                $data['user'] = explode('*', $data['user']);
                $item['fanwireid'] = $data['user'][4];  //$_REQUEST['websiteCrawlid'];
                $data['user'] = end(explode('/', $data['user'][2]));
                $url = "https://api.twitter.com/1/statuses/user_timeline/" . $data['user'] . ".json?count=" . $countPosts;
                //  echo "https://twitter.com/statuses/user_timeline/" . $data['user'] . ".json?count=" . $countPosts;
                //exit;
                if ($_REQUEST['fanwirenameft']) {
                    $tw_res = indexController::curl_get_file_contents($url);
                }

                $data['twitterRes'] = json_decode($tw_res);

                //insert the data in database
                //$checkTwitterTweet = $objectArticle->checkTweet('passthe string her');
                $j = 0;
                foreach ($data['twitterRes'] as $i) {

                    $list[$j] = array();
                    $list[$j]['id'] = $item['fanwireid'];
                    $list[$j]['tweet'] = $i->text; //tweet
                    $list[$j]['time'] = $i->created_at; //creation time of tweet
                    $list[$j]['imageUrl'] = str_replace('normal', 'reasonably_small', $i->user->profile_image_url); //image url of the tweeter
                    $list[$j]['name'] = $i->user->name; //name of the tweeter
                    //$objectArticle->addTweetsFromTwitter($list);


                    $checkArticleTitle = $objectArticle->checkArticle(mysql_escape_string(trim($list[$j]['tweet'])), 'twitter');
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
        } elseif ($data['Ctype'] == "youtube") {

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
            //echo $_REQUEST['videourl'];exit;
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
                // echo "<pre>";print_r($entry);exit;
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
            } else {

                if (isset($_REQUEST['channelname_check']) && $_REQUEST['channelname_check'] == 'on') {
                    if ($_REQUEST['channelname'] == '') {
                        echo "enter channel name.";
                        exit;
                    } else {
                        $channelname = $_REQUEST['channelname'];
                    }
                } else {
                    if (empty($item['websiteCrawlname']) || $item['websiteCrawlname'] == '') {
                        echo "Fanwire Does'nt have youtube URL pleasse update.";
                        exit;
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
                        //  echo "<pre>";print_r($entry); 
                        // get nodes in media: namespace for media information

                        /*  $media = $entry->children('http://search.yahoo.com/mrss/');
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
                          $items[$i]['source'] = VDO_TYPE; */

                        // }
                        $feedURL = 'http://gdata.youtube.com/feeds/api/videos/' . end(explode(':', $entry->id));
                        error_log($feedURL);
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
            //echo "<pre>";print_r($items);exit;
            $objectVedios->insertVideosCrawl($items);
            $data['item'] = $items;
        } else if ($data['Wsourceid'] != "") {
            $data['countPost'] = "";
            $websitedet = $obj->getFanSites($data['Wsourceid']);
            $src = explode("*", $_REQUEST['fanwirename']);
            $request_url = $websitedet['source'];
            $data['keyword'] = $src[5];
            $fanwireid = $src[4];
            // error_log($fanwireid.'----------'.$data['keyword']);
            $item['searchkeyword'] = $data['keyword'];
            $item['source'] = $request_url;
            error_log(str_replace("###", str_replace(" ", $websitedet['search_seperator'], $data['keyword']), trim($websitedet['search_url']))); // exit;
            $html = indexController::file_get_html(str_replace("###", str_replace(" ", $websitedet['search_seperator'], $data['keyword']), trim($websitedet['search_url'])));
            $classElements = $obj->getSourceClasselements($data['Wsourceid']);
            foreach ($html->find($classElements['search_list_repeat_element'] . '.' . $classElements['searchlist_repeat_element_class']) as $key => $article) {
                $item = array();
                if ($key <= 10) {
                    $item['url'] = trim($article->find($classElements['searchlist_title_class'], 0)->href);
                    $item['tit'] = trim($article->find($classElements['searchlist_title_class'], 0)->plaintext);
                    $item['author'] = trim($article->find($classElements['searchlist_author_class'], 0)->plaintext);
                    $item['date'] = trim($article->find($classElements['search_list_date_element'], 0)->plaintext);
                    $item['fanwireid'] = $fanwireid;
                    if ($item['date'] == "") {
                        $item['date'] = trim($article->find('.' . $classElements['search_list_date_class'], 0)->plaintext);
                    }
                    preg_match('@^(?:http://)?([^/]+)@i', $request_url, $matches);
                    $item['articleFrom'] = str_replace('www.', '', $matches[1]);
                    $item['source'] = $matches[0];
                    if ($classElements['searchlist_image_class'] != "") {
                        if ($classElements['searchlist_image_class_type'] == 0)
                            $sep_item = '.';
                        else
                            $sep_item = '#';
                        $cls = $classElements['searchlist_image_class_element'] . $sep_item . $classElements['searchlist_image_class'];
                        $item['image'] = trim($article->find($cls . ' img', 0)->src);
                        if ($data['Wsourceid'] == 6) {
                            $item['image'] = str_replace("width=70&height=53", "width=281&height=211", $item['image']);
                        }
                    }
                    // get last two segments of host name
                    preg_match('@^(?:http://)?([^/]+)@i', $request_url, $matches);
                    $pos = strpos($item['url'], $matches[1]);
                    // The !== operator can also be used.  Using != would not work as expected
                    // because the position of 'a' is 0. The statement (0 != false) evaluates
                    // to false.
                    if ($pos !== false) {
                        
                    } else {
                        $item['url'] = $request_url . ltrim($item['url'], '/');
                    }
                    $html_sub = indexController::file_get_html(trim($item['url']));
                    if ($html_sub != "") {
                        if ($classElements['description_content_class_type'] == 0)
                            $sep_item = '.';
                        else
                            $sep_item = '#';
                        $res_sub = $html_sub->find('div' . $sep_item . $classElements['description_div_class']);
                        if (empty($res_sub)) {
                            
                        } else {
                            foreach ($html_sub->find('div' . $sep_item . $classElements['description_div_class']) as $key => $detail_artic) {
                                $ite = "";
                                if ($key == 0) {
                                    if ($classElements['description_image_class_type'] == 0)
                                        $sep_item = '.';
                                    else
                                        $sep_item = '#';
                                    if ($item['image'] == '') {
                                        $item['getreviewImage'] = trim($detail_artic->find('div.' . $sep_item . $classElements['description_image_class'] . ' img', 0)->src);
                                    } else {
                                        $item['getreviewImage'] = $item['image'];
                                    }
                                    if ($item['author'] == '') {
                                        if ($item['author'] == "") {
                                            $item['getAuthor'] = trim($html_sub->find('div.' . $classElements['description_author_class'], 0)->plaintext);
                                        }
                                        if ($data['Wsourceid'] == 6) {
                                            $item['getAuthor'] = trim($html_sub->find('.' . $classElements['description_author_class'], 0)->plaintext);
                                        }
                                    } else {
                                        $item['getAuthor'] = $item['author'];
                                    }
                                    //echo $item['date'];exit;
                                    if ($item['date'] == "") {
                                        $item['getDate'] = trim($detail_artic->find('.' . $classElements['description_date_class'], 0)->plaintext);
                                    } else if ($item['date'] == "") {
                                        $item['getDate'] = trim($detail_artic->find('div.' . $classElements['description_date_class'], 0)->plaintext);
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
                    $ret[] = $item;
                }
            }
            $data['websiteresults'] = $ret;
            $data['Ctype'] = 'website';
        }
        //echo' heolo<pre>';print_r($data);echo'</pre>';exit;
        $view = new View(); //Creating the object for the class View
        $view->loadView($data, 'index/Crawl'); //load view
    }

    public function file_get_html($url, $use_include_path = false, $context = null, $offset = -1, $maxLen = -1, $lowercase = true, $forceTagsClosed = true, $target_charset = DEFAULT_TARGET_CHARSET, $stripRN = true, $defaultBRText = DEFAULT_BR_TEXT) {
        // We DO force the tags to be terminated.
        $dom = new simple_html_dom(null, $lowercase, $forceTagsClosed, $target_charset, $defaultBRText);
        // For sourceforge users: uncomment the next line and comment the retreive_url_contents line 2 lines down if it is not already done.
        //$contents = file_get_contents($url, $use_include_path, $context, $offset);
        $contents = indexController::curl_get_file_contents($url);
        // Paperg - use our own mechanism for getting the contents as we want to control the timeout.
        //    $contents = retrieve_url_contents($url);
        if (empty($contents)) {
            return false;
        }
        // The second parameter can force the selectors to all be lowercase.
        $dom->load($contents, $lowercase, $stripRN);
        return $dom;
    }

    // get html dom from string
    public function str_get_html($str, $lowercase = true, $forceTagsClosed = true, $target_charset = DEFAULT_TARGET_CHARSET, $stripRN = true, $defaultBRText = DEFAULT_BR_TEXT) {
        $dom = new simple_html_dom(null, $lowercase, $forceTagsClosed, $target_charset, $defaultBRText);
        if (empty($str)) {
            $dom->clear();
            return false;
        }
        $dom->load($str, $lowercase, $stripRN);
        return $dom;
    }

    // dump html dom tree
    public function dump_html_tree($node, $show_attr = true, $deep = 0) {
        $object = new simple_html_dom_node();
        $object->dump($node);
    }

    public function websiteCrawlAction() {
        //$object = new Users();
        if ($_REQUEST) {
            $request_url = "http://www.rollingstone.com";
            $data['user'] = str_replace(" ", "+", $_REQUEST['searchkeyword']);
            $html = indexController::file_get_html($request_url . "/search?q=" . $data['user']);
            //echo'<pre>';        print_r($html); exit;
            //$i = 1;

            foreach ($html->find('div.copy') as $article) {

                //echo '<br/><br/> href = ' .
                $item['url'] = trim($article->find('h2 a', 0)->href);
                //echo '<br/>tilte of url = ' .
                $item['tit'] = trim($article->find('h2 a', 0)->plaintext);
                $item['desc'] = trim($article->find('p', 0)->plaintext);

                //echo "<br/>http://www.rollingstone.com" . $item['url'];
                $html_sub = indexController::file_get_html($request_url . $item['url']);
                if ($html_sub != "") {
                    if ($html_sub->find('div#storyTextContainer') != "") {
                        foreach ($html_sub->find('div#storyTextContainer') as $detail_artic) {
                            $ite = "";
                            if (count($html_sub->find('div#storyTextContainer')) > 1) {
                                $item['getreviewImage'] = trim($detail_artic->find('div.reviewSection-Image img', 0)->src);
                                if (trim($detail_artic->find('div.date', 0)->plaintext) != "") {
                                    $item['getDate'] = trim($detail_artic->find('div.date', 0)->plaintext);
                                }
                                if (trim($detail_artic->find('div.author a', 0)->plaintext) != "") {
                                    $item['getAuthor'] = trim($detail_artic->find('div.author a', 0)->plaintext);
                                }
                            } else {
                                $item['getDate'] = trim($detail_artic->find('div#contentInfo .date', 0)->plaintext);
                                $item['getAuthor'] = trim($detail_artic->find('div#contentInfo .author a', 0)->plaintext);
                                $item['getreviewImage'] = trim($detail_artic->find('div.image-holder img', 0)->src);
                            }

                            if (count($detail_artic->find('p')) > 0) {
                                foreach ($detail_artic->find('p') as $pr) {
                                    $ite.=$pr; //exit;
                                }
                            }
                            $item['ItemDesc'] = $ite;
                        }
                    }
                }


                $ret[] = $item;
            }
        }
        $view = new View(); //Creating the object for the class View
        $view->loadView($data, 'index/WebsiteCrawl'); //load view
    }

    public function twitterCrawlAction() {
        $data['user'] = $_REQUEST['fanwirename'];
        //        echo' heolo<pre>'; print_r($_REQUEST); echo'</pre>';
        //        echo "https://twitter.com/statuses/user_timeline/".$_REQUEST['fanwirename'].".json?count=50";
        //        exit;
        if ($_REQUEST['fanwirename']) {
            $tw_res = indexController::curl_get_file_contents("https://twitter.com/statuses/user_timeline/" . $_REQUEST['fanwirename'] . ".json?count=50");
        }
        $data['twitterRes'] = json_decode($tw_res);
        $view = new View(); //Creating the object for the class View
        $view->loadView($data, 'index/TwitterCrawl'); //load view
    }

    public function facebookCrawlAction() {
        //echo'<pre>'; print_r($_REQUEST); echo'</pre>'; exit;
        global $fc;
        if (isset($_REQUEST['fanwirename'])) {

            $data['user'] = strtolower($_REQUEST['fanwirename']); //"lilwayne";

            $data['profie_image'] = "https://graph.facebook.com/" . $data['user'] . "/picture";
            $facebook = new Facebook(array(
                        'appId' => FB_APP_ID,
                        'secret' => FB_APP_SECRET,
                        'cookie' => true
                    ));
            $object = new Users(); //Creating the object for the class users
            $app_id = FB_APP_ID;
            $app_secret = FB_APP_SECRET;
            $my_url = SITE_URL . "crawl"; //exit;
            $session = $facebook->getSession();
            //echo'<pre>'; print_r($_SESSION); echo'</pre>'; exit;
            if (empty($session)) {
                //echo $facebook->getLoginUrl();
                echo("<script> top.location.href='" . $facebook->getLoginUrl() . "'</script>");
            }


            // known valid access token stored in a database
            //$access_token = "206492729383450|2.N4RKywNPuHAey7CK56_wmg__.3600.1304560800.1-214707|6Q14AfpYi_XJB26aRQumouzJiGA";
            //echo'<br/>'.$code = $_REQUEST["code"];
            //       echo 'Code = '. $code = $_REQUEST["code"];
            //      echo $_SESSION['fb_'.$app_id."_code"];
            //echo'<pre>'; print_r($session); echo'</pre>'; //exit;
            //  echo'<pre>'; print_r($_REQUEST); echo'</pre>';
            //exit;
            // If we get a code, it means that we have re-authed the user
            //and can get a valid access_token.
            // If we get a code, it means that we have re-authed the user
            //and can get a valid access_token.
            //echo 'Code= '.$code;
            if (isset($code)) {
                //  echo'enter'; exit;
                $token_url = "https://graph.facebook.com/oauth/access_token?client_id="
                        . $app_id . "&redirect_uri=" . urlencode($my_url)
                        . "&client_secret=" . $app_secret
                        . "&code=" . $code . "&display=popup"; //exit;
                $response = file_get_contents($token_url);
                $params = null;
                parse_str($response, $params);
                $access_token = $params['access_token'];
            }


            //        if(empty($code)) {
            //            echo '<br/>helo';
            //            $_SESSION['state'] = md5(uniqid(rand(), TRUE)); //CSRF protection
            //            echo $dialog_url = "https://www.facebook.com/dialog/oauth?client_id="
            //            . $app_id . "&redirect_uri=" . urlencode($my_url) . "&state="
            //            . $_SESSION['state'];
            //
			//            echo("<script> top.location.href='" . $dialog_url . "'</script>");
            //        }
            //
			//        if (!isset($_SESSION['fb_'.$app_id."_code"])) {
            //
			//            echo  $token_url="https://graph.facebook.com/oauth/access_token?client_id="
            //            . $app_id . "&redirect_uri=" . urlencode($my_url)
            //            . "&client_secret=" . $app_secret
            //            . "&code=" . $code . "&display=popup"; //exit;
            //            $response = file_get_contents($token_url);
            //            $params = null;
            //            parse_str($response, $params);
            //            $access_token = $params['access_token'];
            //        }
            // Attempt to query the graph:
            // echo '<br/>'.$session['access_token'];
            $graph_url = "https://graph.facebook.com/" . $data['user'] . "/posts?"
                    . "access_token=" . $session['access_token'];
            $response = indexController::curl_get_file_contents($graph_url);
            $decoded_response = json_decode($response);
            //echo count($decoded_response->data);
            //echo'<pre>'; print_r($decoded_response); echo'</pre>';exit;
            //Check for errors
            if ($decoded_response->error) {
                // check to see if this is an oAuth error:
                if ($decoded_response->error->type == "OAuthException") {
                    // Retrieving a valid access token.
                    $dialog_url = "https://www.facebook.com/dialog/oauth?"
                            . "client_id=" . $app_id
                            . "&redirect_uri=" . urlencode($my_url);
                    echo("<script> top.location.href='" . $dialog_url
                    . "'</script>");
                } else {
                    echo "other error has happened";
                }
            } else {
                // success
                //echo("success" . $decoded_response->name);

                echo($access_token);
            }

            // note this wrapper function exists in order to circumvent PHP’s
            //strict obeying of HTTP error codes.  In this case, Facebook
            //returns error code 400 which PHP obeys and wipes out
            //the response.
            $data['fbresult'] = $decoded_response->data;
        }
        // echo'<pre>'; print_r($data); echo'</pre>'; exit;
        $view = new View(); //Creating the object for the class View
        $view->loadView($data, 'index/facebookL'); //load view
    }

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

    public function CrawlWebAction() {
        $obj = new Photos();
        $data['fanwires'] = $obj->getFanSites("");
        $objectArticle = new Articles();
        $data['Wsourceid'] = $_REQUEST['Crawltype'];
        $data['user'] = strtolower($_REQUEST['fanwirename']); //"lilwayne";
        $data['fanwireid'] = $_REQUEST['websiteCrawlid'];
        if ($data['Wsourceid'] != "") {
            //    echo'hi'; exit;
            $websitedet = $obj->getFanSites($data['Wsourceid']);
            //echo'<pre>'; print_r($websitedet); print_r($_REQUEST);exit;
            $request_url = $websitedet['source'];
            $data['keyword'] = $_REQUEST['searchkeyword'];
            $item['searchkeyword'] = $data['keyword'];
            $item['source'] = $request_url;
            //echo str_replace("###", str_replace(" ", $websitedet['search_seperator'], $data['keyword']), trim($websitedet['search_url'])); exit;
            $html = indexController::file_get_html(str_replace("###", str_replace(" ", $websitedet['search_seperator'], $data['keyword']), trim($websitedet['search_url'])));
            // echo'<pre>'; print_r($html); exit;

            $classElements = $obj->getSourceClasselements($data['Wsourceid']);
            // echo'<pre>'; print_r($classElements); exit;
            // echo $classElements['search_list_repeat_element'].'.'.$classElements['searchlist_repeat_element_class'];
            //echo '<br/>'.count($html->find('ul.search_list article_list li'));
            // exit;
            foreach ($html->find($classElements['search_list_repeat_element'] . '.' . $classElements['searchlist_repeat_element_class']) as $key => $article) {
                //echo'<pre>'; print_r($article); exit;
                $item = array();
                //echo'in';
                if ($key <= 10) {
                    $item['url'] = trim($article->find($classElements['searchlist_title_class'], 0)->href);
                    $item['tit'] = trim($article->find($classElements['searchlist_title_class'], 0)->plaintext);
                    $item['author'] = trim($article->find($classElements['searchlist_author_class'], 0)->plaintext);
                    $item['date'] = trim($article->find($classElements['search_list_date_element'], 0)->plaintext);
                    //echo'<pre>'; print_r($item); exit;
                    // get last two segments of host name
                    preg_match('@^(?:http://)?([^/]+)@i', $request_url, $matches);

                    $pos = strpos($item['url'], $matches[1]);
                    // The !== operator can also be used.  Using != would not work as expected
                    // because the position of 'a' is 0. The statement (0 != false) evaluates
                    // to false.
                    if ($pos !== false) {
                        
                    } else {
                        $item['url'] = $request_url . ltrim($item['url'], '/');
                        //echo "The string '$findme' was not found in the string '$mystring'";
                    }

                    //echo '<br/>' . $item['url'];
                    //exit;
                    $html_sub = indexController::file_get_html(trim($item['url']));
                    // echo $key.'<br/>';
                    //if($key==1)
                    //  echo'<pre>'; print_r($html_sub);echo'</pre>';   exit;
                    if ($html_sub != "") {
                        //echo $html_sub->find('div#storyTextContainer'); exit;
                        if ($classElements['description_content_class_type'] == 0)
                            $sep_item = '.';
                        else
                            $sep_item = '#';
                        //echo'<br/>div' . $sep_item . $classElements['description_div_class'];
                        $res_sub = $html_sub->find('div' . $sep_item . $classElements['description_div_class']);
                        //echo'<pre>'; print_r($res_sub);echo'</pre>';
                        if (empty($res_sub)) {
                            //  echo '<br/>NO resuls for Insert';
                        } else {
                            // echo 'div'.$sep_item.$classElements['description_div_class']; //exit;

                            foreach ($html_sub->find('div' . $sep_item . $classElements['description_div_class']) as $key => $detail_artic) {
                                $ite = "";
                                if ($key == 0) {
                                    // echo 'div.entry-content'.$classElements['entry-content'].' img';
                                    // echo'<pre>'; print_r($detail_artic); exit;'div.'.$classElements['description_image_class'].' img'
                                    // if (count($html_sub->find('div'.$sep_item.$classElements['description_div_class'])) > 1) {
                                    //$item['getreviewImage'] = trim($detail_artic->find('div.entry-thumb entry-thumb-article img', 0)->src);

                                    if ($classElements['description_image_class_type'] == 0)
                                        $sep_item = '.';
                                    else
                                        $sep_item = '#';

                                    $item['getreviewImage'] = trim($detail_artic->find('div.' . $sep_item . $classElements['description_image_class'] . ' img', 0)->src);

                                    if ($item['author'] == "") {
                                        $item['getAuthor'] = trim($detail_artic->find('div.' . $classElements['description_author_class'], 0)->plaintext);
                                    }//echo $item['date']; exit;
                                    if ($item['date'] == "") {
                                        $item['getDate'] = trim($detail_artic->find('div.' . $classElements['description_date_class'], 0)->plaintext);
                                    }
                                    /* if (trim($detail_artic->find('div.date', 0)->plaintext) != "") {
                                      $item['getDate'] = trim($detail_artic->find('div.date', 0)->plaintext);
                                      }
                                      if (trim($detail_artic->find('div.author a', 0)->plaintext) != "") {
                                      $item['getAuthor'] = trim($detail_artic->find('div.author a', 0)->plaintext);
                                      }
                                      } else {
                                      $item['getDate'] = trim($detail_artic->find('div#contentInfo .date', 0)->plaintext);
                                      $item['getAuthor'] = trim($detail_artic->find('div#contentInfo .author a', 0)->plaintext);
                                      $item['getreviewImage'] = trim($detail_artic->find('div.image-holder img', 0)->src);
                                      } */

                                    $ite = "";
                                    if (count($detail_artic->find('p')) > 0) {

                                        foreach ($detail_artic->find('p') as $pr) {
                                            //echo '<br/>';
                                            //$ite.=$pr; //exit;
                                            $ite = $ite . $pr;
                                        }
                                    } //echo $ite;
                                    $item['ItemDesc'] .= $ite; //strip_tags($ite, '<p><a><b><strong>');
                                }
                            }
                            //echo'<pre>'; print_r($item); exit;
                            $checkArticleTitle = $objectArticle->checkArticle(mysql_escape_string($item['tit']), 'website');

                            if ($checkArticleTitle == 0) {


                                $tiu = explode(' ', $data['user']);

                                $k = 0;
                                foreach ($tiu as $key => $value) {
                                    //  echo '<br>'.$value.'<br> '.$data['user'].'<br>';
                                    //$res = preg_match("/\b" . $value . "\b/i", $item['tit']);
                                    $res = strpos(strtolower($item['tit']), strtolower($value));
                                    if ($res) {
                                        $k++;
                                    }
                                }
                                //  echo $k;
                                //   exit;

                                if ($k > 0) {
                                    echo $item['tit'] . "<br>found";
                                    $filename = strtotime(date("Y-m-d H:i:s")) . "_artImg.jpg"; //$username.'.jpg';
                                    $img = file_get_contents($item['getreviewImage']);
                                    if ($img) {
                                        $file = DOC_ROOT_PATH . '/photos/' . $filename;
                                        file_put_contents($file, $img);
                                        $item['getreviewImageL'] = $filename;
                                    } else {
                                        $item['getreviewImageL'] = "";
                                    }

                                    $objectArticle->addArticleFromWebsite($item, 'website');
                                } else {
                                    //echo $item['tit'] . "<br>not found";
                                }
                            }
                        }
                    }


                    $ret[] = $item;
                }
            } /* echo'<pre>';
              print_r($ret);
              exit; */
            $data['websiteresults'] = $ret;
        }
        $data['Ctype'] = 'website';
        $view = new View(); //Creating the object for the class View
        $view->loadView($data, 'index/CrawlWeb'); //load view
    }

}

?>
