<?php

/**
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of fanwireController
 *
 * @author naresh
 */
class fanwiresController {
    /*
     *  redirecting to the main page
      creation date:24-04-2012
     */

    //myfanwire action
    public function myFanwireAction() {
        global $fc;
        $object = new Users(); //Creating the object for the class Users
        $objectFanwires = new Fanwires();
        $errorObject = new Errors(Error_XML); //Creating the object for the class Errors
        $data = array();
        $filters = $object->filterValues($_SESSION['id'], $_REQUEST); //get the selected twitter values
        $data['filters'] = $filters;
        //$filters = implode("','", $filters);
        //echo "<pre>";print_r($filters);exit;
        $data['path'] = $_REQUEST['path']; //'myFanwire'; //need in guest page to fetch zoom values
        $data['pagetitle'] = 'FanWire | Music | Celebrity | Entertainment News and Social Media';
        $data['metadescription'] = 'Fan Wire is a social media news platform that gives you news and information on music, celebrities, sports athletes, and sports teams for all social news websites.';
        $data['active'] = 'fanwire';
        if (isset($_SESSION['name'])) {
            $userDetails = $object->getUserDetails($_SESSION['id']);
            if ($userDetails[0]['profile_image'] != "")
                $data['profile_image'] = SITE_URL . "profile_images/" . $userDetails[0]['profile_image'];
            else
                $data['profile_image'] = SITE_URL . "views/images/logodef.png";
            $data['zoomValues'] = $objectFanwires->saveZoomValues('myFanwire', $_SESSION['id'], '', '', '');
            if ($data['zoomValues'] == 1)
                $data['zoomValues'] = array(array('value' => 68, 'width' => 246, 'height' => 186));
            $data['notifications'] = $object->getNotifications($_SESSION['id']);
        } else {
            header("Location:" . SITE_URL);
        }
        $data['limit'] = ENDLIMIT;

        if (empty($filters)) {
            $list = $objectFanwires->getHomeDetails();
        } else {
            if ($filters['article'] == 0 && $filters['facebook'] == 0 && $filters['twitter'] == 0 && $filters['video'] == 0 && $filters['photo'] == 0 && $filters['instagram'] == 0) {
                $list = $list = $objectFanwires->getFiltersPostsOnly(0, 30, ''); //""; // $objectFanwires->getHomeDetails();
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

















                    
//////// implementation for search -- ilker ////////

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


                    $list = $objectFanwires->getSearchedPosts(0, 30, trim($in, ','), $video, $photo, $searchterm, $searchcriteria);

                    $searchcounts = explode(',', $_SESSION['searchcounts']);


                    $data['all_filter_lbl'] = '<li' . $all_lbl_active . '><a href="#" id="all_c"><span><strong> ALL (' . $searchcounts[0] . ')</strong></span></a></li>';
                    $data['profiles_filter_lbl'] = '<li' . $profiles_lbl_active . '><a href="#" id="profiles_c"><span><strong> PROFILES (' . $searchcounts[1] . ')</strong></span></a></li>';
                    $data['media_filter_lbl'] = '<li' . $media_lbl_active . '><a href="#" id="media_c"><span><strong> MEDIA (' . $searchcounts[2] . ')</strong></span></a></li>';
                    $data['social_filter_lbl'] = '<li' . $social_lbl_active . '><a href="#" id="social_c"><span><strong> SOCIAL (' . $searchcounts[3] . ')</strong></span></a></li>';
                    $data['mycollection_filter_lbl'] = '<li' . $mycollection_lbl_active . '><a href="#" id="mycollection_c"><span><strong> MY COLLECTION (' . $searchcounts[4] . ')</strong></span></a></li>';



                    if (count($list) == 0) {




                        $returndata = file_get_contents(SITE_URL . 'didyoumean.php?phrase=' . $_POST['searchterm']);
                        if ($returndata != '') {
                            $list = $objectFanwires->getSearchedPosts(0, 30, trim($in, ','), $video, $photo, $returndata, $searchcriteria);
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

                    /////////////////
                } else {








                    $list = $objectFanwires->getFiltersPostsOnly(0, 30, trim($in, ','), $video, trim($photo, ','));
                }
            }
        }

        $data['pagetitle'] = 'FanWire | My Fanwires';
        $data['metadescription'] = "";
        $data['fanwires'] = $list;
        $data['fanwires_for_more'] = $objectFanwires->getMasterDetailsMore();
        $objectFanwires->getHistory();
        $data['history'] = $_SESSION['history'];

        $view = new View(); //Creating the object for the class View
        $view->loadView($data, 'fanwires/my_fanwire'); //load view
    }

    /*
     *  Connecting to social media
      creation date:24-04-2012
     */

    public function mediaCenterAction() {
        global $fc;
        $object = new Users(); //Creating the object for the class Users
        $errorObject = new Errors(Error_XML); //Creating the object for the class Errors
        $data = array();
        if (isset($_SESSION['name'])) {
            $data['active'] = 'fanwire';
        } else {
            header("Location:" . SITE_URL);
        }

        $view = new View(); //Creating the object for the class View
        $view->loadView($data, 'fanwires/media_center'); //load view
    }

    /*
     * settings controll
     * creation date 02.05.2012
     * */

    public function settingsAction() {
        global $fc;
        $object = new Users(); //Creating the object for the class users
        $errorObject = new Errors(Error_XML); //Creating the object for the class Errors
        $objectFanwires = new Fanwires();
        $data = array();

        if (isset($_SESSION['name'])) {
            $data['active'] = 'settings';


            $data['fanwires'] = $object->getMyFanwires();
            $data['fanwires_for_more'] = $objectFanwires->getMasterDetailsMore();
            $data['notifications'] = $object->getNotifications($_SESSION['id']);
            if (isset($_REQUEST['submit']) && $_REQUEST['submit'] == 'save changes') {
                $res = $object->updateName($_POST);
                if ($res) {
                    $data['msg'] = $errorObject->getErrorMessage('updated');
                }
            }
            $data['userdetails'] = $userDetails = $object->getUserDetails($_SESSION['id']);
            if ($userDetails[0]['profile_image'] != "")
                $data['profile_image'] = SITE_URL . "profile_images/" . $userDetails[0]['profile_image'];
            else
                $data['profile_image'] = SITE_URL . "views/images/logodef.png";
        } else {
            header("Location:" . SITE_URL);
        }
        $data['pagetitle'] = 'Fanwire | Settings';
        $data['metadescription'] = '';
        $obj = new Fanwires(); //Creating the object for the class Fanwires
        $obj->getHistory();
        $data['history'] = $_SESSION['history'];
        $view = new View(); //Creating the object for the class View
        $view->loadView($data, 'fanwires/settings'); //load view
    }

    /*
     *  social  controll
     * creation date 02.05.2012
     * */

    /* 	public function socialAction() {
      global $fc;
      $object = new Users();//Creating the object for the class users
      $errorObject = new Errors(Error_XML);//Creating the object for the class Errors
      $data = array();

      if (isset($_SESSION['name'])) {
      $data['active']='settings';
      $data['userdetails'] = $userDetails = $object->getUserDetails($_SESSION['id']);
      $data['profile_image'] = SITE_URL . "profile_images/" . $userDetails[0]['profile_image'];
      $data['fanwires'] = $object->getMyFanwires();
      $data['fanwires_for_more'] = $object->getMyFanwiresMore();
      } else {
      header("Location:" . SITE_URL);
      }
      $obj = new Fanwires();//Creating the object for the class Fanwires
      $obj->getHistory();
      $data['history'] = $_SESSION['history'];
      $view = new View();//Creating the object for the class View
      $view->loadView($data, 'fanwires/social');//load view
      } */

    public function socialAction() {
        global $fc;
        global $facebook;
        global $twitterObj1;
        $object = new Users(); //Creating the object for the class users
        $errorObject = new Errors(Error_XML); //Creating the object for the class Errors
        $objectFanwires = new Fanwires();
        $data = array();

        if (isset($_SESSION['name'])) {
            $data['active'] = 'settings';
            $data['userdetails'] = $userDetails = $object->getUserDetails($_SESSION['id']);
            if ($userDetails[0]['profile_image'] != "")
                $data['profile_image'] = SITE_URL . "profile_images/" . $userDetails[0]['profile_image'];
            else
                $data['profile_image'] = SITE_URL . "views/images/logodef.png";
            $data['fanwires'] = $object->getMyFanwires();
            //$data['fanwires_for_more'] = $object->getMyFanwiresMore();
            $data['fanwires_for_more'] = $objectFanwires->getMasterDetailsMore();
            $data['notifications'] = $object->getNotifications($_SESSION['id']);
            //facebook
            if ($_SESSION['user']) {
                
            } else {
                $_SESSION['user'] = $data['user'] = $user = $facebook->getUser(); //check user
                $users = $facebook->getSession(); //session information
                $data['loginurl'] = $facebook->getLoginUrl(); //get login url
                $data['logouturl'] = $facebook->getLogoutUrl(); //get logout url
                //$facebook->getAppId();
                if ($user) {
                    try {
                        // Proceed knowing you have a logged in user who's authenticated.

                        $_SESSION['user_facebook_profile'] = $user_profile = $facebook->api('/me'); //store user facebook info in session
                    } catch (FacebookApiException $e) {
                        echo '<pre>' . htmlspecialchars(print_r($e, true)) . '</pre>';
                        $user = null;
                    }
                }
            }
            //end facebook
            if ($_SESSION['ot']) {
                
            } else {
                //twitter start
                $data['auth_t'] = $oauth_token = $_GET['oauth_token']; //chech weather auth token is available or not
                if ($oauth_token == '') {
                    $data['t_login_url'] = $url = $twitterObj1->getAuthorizationUrl();
                } else {
                    $twitterObj1->setToken($_GET['oauth_token']);
                    $token = $twitterObj1->getAccessToken();
                    $twitterObj1->setToken($token->oauth_token, $token->oauth_token_secret);
                    $_SESSION['ot'] = $token->oauth_token;
                    $_SESSION['ots'] = $token->oauth_token_secret;
                    $twitterInfo = $twitterObj1->get_accountVerify_credentials();
                    $_SESSION['user_twitter_profile'] = $twitterInfo->response; //store the user twitter info in session variable
                    //$username = $twitterInfo->screen_name;
                    //$profilepic = $twitterInfo->profile_image_url;
                }
                //twitter end
            }
        } else {
            header("Location:" . SITE_URL);
        }
        $obj = new Fanwires(); //Creating the object for the class Fanwires
        $obj->getHistory();
        $data['history'] = $_SESSION['history'];
        $view = new View(); //Creating the object for the class View
        $view->loadView($data, 'fanwires/social'); //load view
    }

    /*
     * about controll
     * creation date 02.05.2012
     * */

    public function aboutAction() {
        global $fc;
        $object = new Users(); //Creating the object for the class users
        $errorObject = new Errors(Error_XML); //Creating the object for the class Errors
        $objectFanwires = new Fanwires();
        $data = array();

        if (isset($_SESSION['name'])) {
            $data['active'] = 'settings';
            $data['userdetails'] = $userDetails = $object->getUserDetails($_SESSION['id']);
            $data['notifications'] = $object->getNotifications($_SESSION['id']);
            if ($userDetails[0]['profile_image'] != "")
                $data['profile_image'] = SITE_URL . "profile_images/" . $userDetails[0]['profile_image'];
            else
                $data['profile_image'] = SITE_URL . "views/images/logodef.png";
            $data['fanwires'] = $object->getMyFanwires();
            // $data['fanwires_for_more'] = $object->getMyFanwiresMore();
            $data['fanwires_for_more'] = $objectFanwires->getMasterDetailsMore();
            if (isset($_REQUEST['save'])) {
                $res = $object->updateUserProfile($_REQUEST);
                if ($res) {
                    header("Location:" . SITE_URL . 'about?ms=1');
                }
            }
        } else {
            header("Location:" . SITE_URL);
        }
        $obj = new Fanwires(); //Creating the object for the class Fanwires
        $obj->getHistory();
        $data['history'] = $_SESSION['history'];
        $view = new View(); //Creating the object for the class View
        $view->loadView($data, 'fanwires/aboutMe'); //load view
    }

    /*
     * change name
     * creation date 02.05.2012
     * */

    public function changeNameAction() {
        global $fc;
        $object = new Users(); //Creating the object for the class Users
        $errorObject = new Errors(Error_XML); //Creating the object for the class Errors
        $data = array();
        $name = $_REQUEST['name'];
        if (empty($name)) {
            echo "Name Can't be null,    Please Provide Name.";
        } else {
            echo $object->updateName($name);
        }
    }

    /*
     * change name
     * creation date 02.05.2012
     * */

    public function changeUrlAction() {
        global $fc;
        $object = new Users(); //Creating the object for the class Users
        $errorObject = new Errors(Error_XML); //Creating the object for the class Errors
        $data = array();
        $name = $_REQUEST['url'];
        if (empty($name)) {
            echo "url Can't be null,    Please Provide url.";
        } else {
            echo $object->updateUrl($name);
        }
    }

    /*
     * change password
     * creation date 02.05.2012
     * */

    public function changePasswordAction() {
        global $fc;
        $object = new Users(); //Creating the object for the class Users
        $errorObject = new Errors(Error_XML); //Creating the object for the class Errors
        $data = array();
        if ($_POST['current_password'] == '' && $_POST['newpassword'] == '') {
            echo "Please enter the password.";
            exit;
        }
        $passCheck = $object->checkPassword($_POST['current_password']);
        if ($passCheck == '1') {
            $password = md5($_REQUEST['newpassword']);
            $object->changePassword($password);
            echo 1;
        } else {
            echo 0;
        }
    }

    /*
     * change email
     * creation date 03.05.2012
     * */

    public function changeEmailAction() {
        global $fc;
        $object = new Users(); //Creating the object for the class Users
        $errorObject = new Errors(Error_XML); //Creating the object for the class Errors
        $data = array();

        if (empty($_REQUEST['email']) || $_REQUEST['email'] == '') {
            echo "Please Provide Email Address. ";
        } elseif (!preg_match('/^[a-z0-9&\'\.\-_\+]+@[a-z0-9\-]+\.([a-z0-9\-]+\.)*+[a-z]{2}/is', $_REQUEST['email'])) {
            echo "Please Enter Valid Email Address";
        } elseif ($object->isDuplicateEmail($_REQUEST['email'])) {
            echo "This Email Id is Already Exists with our Records";
        } else {
            $email = $_REQUEST['email'];
            echo $object->changeEmail($email);
        }

        //echo $object->updateName($_REQUEST['password']);
    }

    /*
      changeProfilePicture
     */

    public function changeProfilePicAction() {
        global $fc;
        $actual_image_name = "";
        $object = new Users(); //Creating the object for the class Users
        $errorObject = new Errors(Error_XML); //Creating the object for the class Errors
        $data = array();
        $data['preview_title'] = "Current";
        $data['active'] = 'settings';
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
        $data['pagetitle'] = 'FANWIRE | Change Profile Picture';
        $data['metadescription'] = '';
        $data['fanwires'] = $object->getMyFanwires();
        $data['fanwires_for_more'] = $object->getMyFanwiresMore();
        $obj = new Fanwires(); //Creating the object for the class Fanwires
        $obj->getHistory();
        $data['history'] = $_SESSION['history'];
        $data['actual_image_name'] = $actual_image_name;
        $view = new View(); //Creating the object for the class View
        $view->loadView($data, 'fanwires/changeProfilePic'); //load view
    }

    /*
     * ajaxMore 
     */

    public function ajaxMoreAction() {
        global $fc;
        $actual_image_name = "";
        $object = new Users(); //Creating the object for the class Users
        $objectFanwires = new Fanwires();
        $errorObject = new Errors(Error_XML); //Creating the object for the class Errors
        $data = array();
        $userDetails = $object->getUserDetails($_SESSION['id']);
        if ($_SESSION['id'])
            $session = $_SESSION['id'];
        else
            $session = session_id();
        $filters = $object->filterValues($session, $_REQUEST); //get the selected twitter values

        $data['filters'] = $filters;
        if ($userDetails[0]['profile_image'] != "")
            $data['profile_image'] = SITE_URL . "profile_images/" . $userDetails[0]['profile_image'];
        else
            $data['profile_image'] = SITE_URL . "views/images/logodef.png";

        $zoom = $data['zoomValues'] = $objectFanwires->saveZoomValues($_POST['path'], $session, '', '', '');
        $start = $_POST['strlimit'];
        $lastmsg = $start + 30;

        if (empty($filters)) {
            $result = $objectFanwires->getHomeDetails($start);
        } else {
            if ($filters['article'] == 0 && $filters['facebook'] == 0 && $filters['twitter'] == 0 && $filters['video'] == 0 && $filters['photo'] == 0 && $filters['instagram'] == 0) {
                $result = $objectFanwires->getFiltersPostsOnly($start, 30, ''); //"";// $objectFanwires->getHomeDetails($start);
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
                $result = $objectFanwires->getFiltersPostsOnly($start, 30, trim($in, ','), $video, trim($photo, ','));
            }
        }
        //$result = $objectFanwires->getHomeDetails($start);




        $str = '';
        foreach ($result as $key => $value) {

            $cond1 = "";
            $cond2 = "";
            $cond3 = "";
            $cond4 = "";
            $cond5 = "";
            $cond6 = "";
            $cond7 = "";
            //echo "<pre>";print_r($value);



            if ($_SESSION['id']) {/*
              $str.='<div class = "element item block"><div class = "element_content"><div class = "element_content1">';
              $str.='<p>' . $value['name'] . '</p>';

              if ($value['source'] == 1) {
              $cond1.="small_icons_fb";
              } elseif ($value['source'] == 2) {
              $cond1.="small_icons_tw";
              } elseif ($value['source'] == 3) {
              $cond1.="small_icons_riview";
              } elseif ($value['type'] == 3) {
              $cond1.="small_icons_vid";
              } elseif ($value['type'] == 4) {
              $cond1.="small_icons_cam";
              }

              $str.='<a href="#" class="' . $cond1 . '"></a><div class = "clear"></div><span id = "spancontetnt">';
              if ($value['title_link'] != '' && $value['source'] != 1 && $value['source'] != 2) {
              $cond2.="<a href = '" . $value['title_link'] . "' style = \"text-transform: none;color: #333333;text-decoration: none;font-size: 14px;font-weight: bold;\">" . $value['title'] . "</a>";
              } elseif ($value['source'] != 1 && $value['source'] != 2) {
              $cond2.=$value['title'];
              }
              $str.='</span></div><p>' . $value['time'] . '</p>';

              if ($value['title_link'] != '' && $value['source'] != 1 && $value['source'] != 2) {
              $cond3.="video";
              } elseif ($value['photo'] == "") {
              $cond3.="image";
              } else {
              $cond3.="image";
              }

              if ($value['type'] == 0) {
              $cond5.=$value['link'];
              } else {
              $cond5.=$value['title_link'];
              }
              if ($value['source'] == 2) {
              $cond4.="<img src = " . IMAGE_URL . $value['photo'] . " width = \"75\" height = \"75\"/>";
              } elseif ($value['photo'] == "") {
              $cond4.="<img src = " . IMAGE_URL . $value['fanwire_profile_img'] . " width = \"75\" height = \"75\"/>";
              } else {
              $cond4.="<a href=\"" . $cond5 . "\"><img src = " . IMAGE_URL . $value['photo'] . " width = \"202\" height = \"" . (202 * ($value['height'] / $value['width'])) . "\"/></a>";
              }

              if ($value['source'] == 1) {
              $cond6.="Facebook";
              } elseif ($value['source'] == 1) {
              $cond6.="Twitter";
              } else {
              $cond6.="Article";
              }
              if ($value['type'] == 4) {
              $cond6.="Instagram";
              } elseif ($value['type'] == 3) {
              $cond6.="Youtube";
              } elseif ($value['type'] == 1) {
              $cond6.="Photos";
              }
              $str.='<div class = "' . $cond3 . '"><p>' . $cond6 . '</p></div><p>';

              if ($value['title_link'] != "" && $value['source'] != 1 && $value['source'] != 2) {
              $cond7.=$value['description'];
              } else {
              $cond7.=$value['description'];
              }

              $str.=$cond7 . '</p></div><div class = "clear"></div><div class = "twitter_bottom1"><p style = "float:left"><a href = "#">Share</a></p><p style = "float:right"><a href = "#">View More</a></p></div></div>';
             */
            } else {
                $str.='<div class = "element item block"><div class = "element_content"><div class = "element_content1">';
                $str.='<p>' . $value['name'] . '</p>';

                if ($value['source'] == 1) {
                    $cond1.="small_icons_fb";
                } elseif ($value['source'] == 2) {
                    $cond1.="small_icons_tw";
                } elseif ($value['source'] == 3) {
                    $cond1.="small_icons_riview";
                } elseif ($value['type'] == 3) {
                    $cond1.="small_icons_vid";
                } elseif ($value['type'] == 4) {
                    $cond1.="small_icons_cam";
                }

                $str.='<a href="#" class="' . $cond1 . '"></a><div class = "clear"></div><span id = "spancontetnt">';
                if ($value['title_link'] != '' && $value['source'] != 1 && $value['source'] != 2) {
                    $cond2.="<a href = '" . $value['title_link'] . "' style = \"text-transform: none;color: #333333;text-decoration: none;font-size: 14px;font-weight: bold;\">" . $value['title'] . "</a>";
                } elseif ($value['source'] != 1 && $value['source'] != 2) {
                    $cond2.=$value['title'];
                }
                $str.='</span></div><p>' . $value['time'] . '</p>';

                if ($value['title_link'] != '' && $value['source'] != 1 && $value['source'] != 2) {
                    $cond3.="video";
                } elseif ($value['photo'] == "") {
                    $cond3.="image";
                } else {
                    $cond3.="image";
                }

                if ($value['type'] == 0) {
                    $cond5.=$value['link'];
                } else {
                    $cond5.=$value['title_link'];
                }

                if ($value['source'] == 2) {
                    $cond4.="<img src = '" . IMAGE_URL . $value['photo'] . "' width = \"75\" height = \"75\"/>";
                } elseif ($value['photo'] == "") {
                    $cond4.="<img src = '" . IMAGE_URL . $value['fanwire_profile_img'] . "' width = \"75\" height = \"75\"/>";
                } else {
                    $cond4.="<a href=\"" . $cond5 . "\"><img src = '" . IMAGE_URL . $value['photo'] . "' width = \"202\" height = \"" . (202 * ($value['height'] / $value['width'])) . "\"/></a>";
                }

                if ($value['source'] == 1) {
                    $cond6.="Facebook";
                } elseif ($value['source'] == 2) {
                    $cond6.="Twitter";
                } elseif ($value['source'] == 3) {
                    $cond6.="Article";
                }
                if ($value['type'] == 4) {
                    $cond6.="Instagram";
                } elseif ($value['type'] == 3) {
                    $cond6.="Youtube";
                } elseif ($value['type'] == 1) {
                    $cond6.="Photos";
                }
                $str.='<div class = "' . $cond3 . '">' . $cond4 . '<p>' . $cond6 . '</p></div><p class="more">';

                if ($value['title_link'] != "" && $value['source'] != 1 && $value['source'] != 2) {
                    $cond7.=$value['description'];
                } else {
                    $cond7.=$value['description'];
                }

                $str.=$cond7 . '</p></div><div class = "clear"></div><div class = "twitter_bottom1"><p style = "float:left"><a href = "#">Share</a></p><p style = "float:right"><a href = "#">View More</a></p></div></div>';
            }
        }

        echo $str . "##" . $lastmsg;
    }

    /**
     * change privacy
     * creation date 14.05.2012
     * */
    public function changePrivacyAction() {
        global $fc;
        $object = new Users(); //Creating the object for the class Users
        $errorObject = new Errors(Error_XML); //Creating the object for the class errors
        $data = array();


        if (empty($_REQUEST['privacy']) || $_REQUEST['privacy'] == '') {
            echo "Please Provide Email Address. ";
        } else {
            $privacy = $_REQUEST['privacy'];
            echo $object->changePrivacy($privacy);
        }

        //echo $object->updateName($_REQUEST['password']);
    }

    /*
     * Adding addFanwires
      creation date:24-04-2012
     */

    public function addFanWiresAction() {
        global $fc;
        $object = new Users(); //Creating the object for the class Users Class
        $errorObject = new Errors(Error_XML); //Creating the object for the class Errors Class
        //echo $object->updateName($_REQUEST['password']);
    }

    /**
     * adding Fanwire
     * Fan of some of celebrity
     * */
    public function havingFanAction() {
        global $fc;
        $object = new Users(); //Creating the object for the class Users
        $errorObject = new Errors(Error_XML); //Creating the object for the class Errors
        $objectFanwires = new Fanwires();
        $data = array();
        $data['activeBrowseAddFan'] = "browse";
        $data['activeBrowse'] = "myfanwires";
        if (isset($_SESSION['name'])) {
            $userDetails = $object->getUserDetails($_SESSION['id']);
            if ($userDetails[0]['profile_image'] != "")
                $data['profile_image'] = SITE_URL . "profile_images/" . $userDetails[0]['profile_image'];
            else
                $data['profile_image'] = SITE_URL . "views/images/logodef.png";
            $data['fanwires'] = $object->getMyFanwires();
            $data['fanwires_for_more'] = $objectFanwires->getMasterDetailsMore();
        } else {
            header("Location:" . SITE_URL);
        }

        $objectFanwires->getHistory();
        $data['history'] = $_SESSION['history'];
        $data['zoomValues'] = $objectFanwires->saveZoomValues('addFan', $_SESSION['id'], '', '', '');
        $view = new View(); //Creating the object for the class View
        $view->loadView($data, 'fanwires/myFanwires'); //Load view
    }

    /*
     * Browsefanwire action to browse the fanwires */

    public function browseFansAction() {
        global $fc;
        $object = new Users(); //Creating the object for the class Users
        $errorObject = new Errors(Error_XML); //Creating the object for the class Errors Class
        $obj = new Fanwires();
        $data = array();
        $data['activeBrowseAddFan'] = "browse";
        $data['activeBrowse'] = "addfan";
        if (isset($_SESSION['name'])) {
            $userDetails = $object->getUserDetails($_SESSION['id']);
            if ($userDetails[0]['profile_image'] != "")
                $data['profile_image'] = SITE_URL . "profile_images/" . $userDetails[0]['profile_image'];
            else
                $data['profile_image'] = SITE_URL . "views/images/logodef.png";
            $data['fanwires'] = $object->getAllFanwiresBrowse();
            $data['fanwires_for_more'] = $obj->getMasterDetailsMore();
        } else {
            header("Location:" . SITE_URL);
        }
        $obj = new Fanwires(); //Creating the object for the class Fanwires
        $obj->getHistory();
        $data['history'] = $_SESSION['history'];
        $data['zoomValues'] = $obj->saveZoomValues('browseFans', $_SESSION['id'], '', '', '');
        $view = new View(); //Creating the object for the class View
        $view->loadView($data, 'fanwires/browseFanwires');
    }

    ///profile Information of fanwires
    public function fanwirePersonalProfileAction() {

        $object = new Users(); //Creating the object for the class Users
        $objectPhotos = new Photos();
        $errorObject = new Errors(Error_XML); //Creating the object for the class Errors
        $obj = new Fanwires(); //Creating the object for the class Fanwires
        global $fc;
        $data = array();
        $data['fanwires_for_moreguest'] = $object->getMyFanwiresGuest(); //for guest login



        $userDetails = $object->getUserDetails($_SESSION['id']); //current user details
        if ($userDetails[0]['profile_image'] != "")
            $data['profile_image'] = SITE_URL . "profile_images/" . $userDetails[0]['profile_image'];
        else
            $data['profile_image'] = SITE_URL . "views/images/logodef.png";

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
            //echo "<pre>";print_r($data['userDetails']);
        }
        $obj->getHistory();
        $data['history'] = $_SESSION['history'];
        //$data['fanwires_for_more'] = $object->getMyFanwiresMore();
        $data['fanwires_for_more'] = $obj->getMasterDetailsMore();
        $data['notifications'] = $object->getNotifications($_SESSION['id']);
        //$data['pagetitle'] = 'FANWIRE NAME News and Social media | FANWIRE NAME On FanWire.';
        //$data['metadescription'] = '';

        $view = new View(); //Creating the object for the class View
        if (isset($_SESSION['name'])) {

            $view->loadView($data, 'fanwires/fanwirePersonalProfile');
        } else {

            // $view->loadView($data, 'fanwires/fanwirePersonalProfileSocialMedia');
            $view->loadView($data, 'fanwires/fanwirePersonalProfile');
            //header('Location:'.SITE_URL);
        }
    }

    //email confirming action
    public function emailConfirmingAction() {

        $object = new Users(); //Creating the object for the class Users
        $errorObject = new Errors(Error_XML); //Creating the object for the class Errors
        $data = array();
        $id = $_GET['id'];
        $userDetails = $object->getUserDetails($id);
        $data['active'] = 'fanwire';
        if ($userDetails[0]['status'] == 1) {
            $msg = $data['activatedmsg'] = $errorObject->getErrorMessage('activated');
            header('Location:' . SITE_URL . '?cnfrm=fNwraCt');
        } elseif ($object->updateUserEmail($id)) {
            //header('Location:'.SITE_URL);
            $msg = $data['activatedmsg'] = $errorObject->getErrorMessage('activate');
            header('Location:' . SITE_URL . '?cnfrm=fNwr');
        }
    }

    /*
      Ajax image action for the croping the image
     */

    public function ajax_imageAction() {

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
            exit();
        }
    }

    //fanwires fans (who ever the fans of the perticular fanwire)

    public function fanwiresFanAction() {
        global $fc;
        $objectFanwires = new Fanwires();
        $data = array();
        if (isset($_GET['fwrid'])) {
            $data['fwrid'] = $fwrid = $_GET['fwrid'];
            $data['ac'] = 5;
        } else {
            $arr = explode("=", $fc->extra);
            $data['fwrid'] = $fwrid = $arr[1];
            $data['ac'] = 5;
        }
        $object = new Users(); //Creating the object for the class Users
        $errorObject = new Errors(Error_XML); //Creating the object for the class Errors

        $obj = new Fanwires(); //Creating the object for the class Fanwires
        $obj->getHistory();
        $data['fanwiresguest'] = $object->getMyFanwiresGuest(); //for guest login
        $data['fanwires_for_moreguest'] = $object->getMyFanwiresGuest(); //for guest login
        $data['history'] = $_SESSION['history'];
        $data['active'] = 'fanwire';
        $userDetails = $object->getUserDetails($_SESSION['id']);
        if ($userDetails[0]['profile_image'] != "")
            $data['profile_image'] = SITE_URL . "profile_images/" . $userDetails[0]['profile_image'];
        else
            $data['profile_image'] = SITE_URL . "views/images/logodef.png";
        //getting the fanwire profile image
        $data['fanwires'] = $object->getFanwireDetails($fwrid);
        // $data['fanwires_for_more'] = $object->getMyFanwiresMore();
        $data['fanwires_for_more'] = $objectFanwires->getMasterDetailsMore();
        //get the fans of fanwire from tbl_fanwire_user table
        $data['fans'] = $object->getFanwiresFans($fwrid);
        $view = new View(); //Creating the object for the class view
        if (isset($_SESSION['name'])) {
            $view->loadView($data, 'fanwires/fanwiresFan'); //load view
        } else {
            //header("Location:" . SITE_URL);
            $view->loadView($data, 'fanwires/fanwiresFanSocialMedia'); //load view
        }
    }

    public function clearSessionAction() {
        echo "session";
        unset($_SESSION['history']);
    }

    //ajax collect action

    public function collectAction() {
        $fwrid = $_POST['fwrid'];
        $type = $_POST['type'];
        $object = new Users(); //Creating the object for the class Users
        $collectionObject = new Collections();
        $errorObject = new Errors(Error_XML); //Creating the object for the class Errors
        $data = array();
        echo $collectionObject->collectFanwire($_SESSION['id'], $fwrid, $type);
    }

//remove or hide the perticular block
    public function removeFanwirePermanantlyAction() {
        $data = array();
        $id = $_GET['id']; //get the id
        $type = $_GET['type']; //get the type of block
        $object = new Users(); //Creating the object for the class Users
        $errorObject = new Errors(Error_XML); //Creating the object for the class Errors
        $objectFanwires = new Fanwires();
        if (isset($_SESSION['name'])) {
            $data['active'] = 'fanwire';
            $userDetails = $object->getUserDetails($_SESSION['id']);
            if ($userDetails[0]['profile_image'] != "")
                $data['profile_image'] = SITE_URL . "profile_images/" . $userDetails[0]['profile_image'];
            else
                $data['profile_image'] = SITE_URL . "views/images/logodef.png";
            //getting the fanwire profile image
            $data['fanwires'] = $object->getMyFanwires();
            //$data['fanwires_for_more'] = $object->getMyFanwiresMore();
            $data['fanwires_for_more'] = $objectFanwires->getMasterDetailsMore();
            //delete the fanwire
            $object->removeTheFanwire($id, $type);
            $page = end(explode('/', $_SERVER['HTTP_REFERER']));

            header("Location:" . SITE_URL . $page);
        } else {
            header("Location:" . SITE_URL);
        }
        $obj = new Fanwires(); //Creating the object for the class Fanwires
        $obj->getHistory();
        $data['history'] = $_SESSION['history'];
        $view = new View(); //Creating the object for the class view
        $view->loadView($data, 'fanwires/my_fanwire'); //load view
    }

    /**
     * share content
     * */
    public function shareContentAction() {
        $fwrid = $_POST['fwrid'];

        $object = new Users(); //Creating the object for the class Users
        $errorObject = new Errors(Error_XML); //Creating the object for the class Errors
        $data = array();
        $objectFanwires = new Fanwires();

        $data['active'] = 'fanwire';
        $userDetails = $object->getUserDetails($_SESSION['id']);
        if ($userDetails[0]['profile_image'] != "")
            $data['profile_image'] = SITE_URL . "profile_images/" . $userDetails[0]['profile_image'];
        else
            $data['profile_image'] = SITE_URL . "views/images/logodef.png";
        //getting the fanwire profile image
        $data['fanwires'] = $object->getMyFanwires();
        //$data['fanwires_for_more'] = $object->getMyFanwiresMore();
        $data['fanwires_for_more'] = $objectFanwires->getMasterDetailsMore();
        $data['fans'] = $object->getFanwiresFans($fwrid);

        if ((isset($_POST['email_addresses'])) && $_POST['email_share'] == 'true') {

            if ($_POST['email_addresses'] == '') {
                echo 'You must provide the email address to share the content';
                exit;
            } elseif (!filter_var($_POST['email_addresses'], FILTER_VALIDATE_EMAIL)) {
                //elseif (preg_match("/^[^@]*@[^@]*\.[^@]*$/", trim($_POST['email_addresses']))) {

                echo "Please enter valid email address.";
                exit;
            } else {

                $to = $toEmails = $_POST['email_addresses'];

                if (strstr($toEmails, ',')) {
                    $toAddress = explode(",", $toEmails);
                    $to = $toAddress[0];
                    $cc = implode(",", array_slice($toAddress, 1));
                } else {
                    $to = $toEmails;
                }
                //$to = $_POST['email_addresses'];

                $ip = $_SERVER['HTTP_HOST']; //get the ip address
                $subject = 'Fanwire Invitation';
                $body['html'] = " Hi " . $_POST['fanid'] . "<br>" . $_POST['emailMessage'];
                if (Mailer::sendMail($to, $subject, $body, SUPPORT_EMAIL, '', '', $cc, '')) {
                    //header("Location:" . SITE_URL.'?cnf=1');
                    // header("Location:" . SITE_URL . 'myFanwire?su=1');
                    echo 101;
                    exit;
                } else {
                    echo "false";
                    exit;
                    //header("Location:" . SITE_URL . 'myFanwire?su=0');
                }
            }
        } else if ((isset($_POST['email_addresses'])) && $_POST['email_share'] == 'false') {
            echo "Please select the option to share";
            exit;
        }

        if (isset($_POST['det']) && $_POST['det'] != '') {

            $toMails = $_POST['det'];

            $toAddress = explode(",", $toMails);
            $to = $toAddress[0];
            $cc = implode(",", array_slice($toAddress, 1));

            $ip = $_SERVER['HTTP_HOST']; //get the ip address
            $subject = 'Fanwire Invitation';
            $body['html'] = " Hi ";
            if (Mailer::sendMail($to, $subject, $body, SUPPORT_EMAIL, '', '', $cc, '')) {

                echo 101;
                exit;
            } else {
                echo 'false';
                exit;
            }
        }
    }

    //fannetwork
    public function fanNetworkAction() {
        $object = new Users(); //Creating the object for the class Users
        $errorObject = new Errors(Error_XML); //Creating the object for the class Errors
        $data = array();
        $objectFanwires = new Fanwires();
        if (isset($_SESSION['name'])) {
            $data['active'] = 'fanwire';
            $userDetails = $object->getUserDetails($_SESSION['id']);
            if ($userDetails[0]['profile_image'] != "")
                $data['profile_image'] = SITE_URL . "profile_images/" . $userDetails[0]['profile_image'];
            else
                $data['profile_image'] = SITE_URL . "views/images/logodef.png";
            //getting the fanwire profile image
            $data['fanwires'] = $object->getFanwireDetails($fwrid);
            //$data['fanwires_for_more'] = $object->getMyFanwiresMore();
            $data['fanwires_for_more'] = $objectFanwires->getMasterDetailsMore();
        } else {
            header("Location:" . SITE_URL);
        }
        $obj = new Fanwires(); //Creating the object for the class Fanwires
        $album_det = $obj->getAlbums();
        $obj->getHistory();
        $data['history'] = $_SESSION['history'];
        $view = new View(); //Creating the object for the class view
        $view->loadView($data, 'fanwires/fan_network'); //load view
    }

    //get the fanwires fans
    public function getFanwireFansAction() {

        $fwrid = $_POST['fwrid'];
        $object = new Users(); //Creating the object for the class Users
        $errorObject = new Errors(Error_XML); //Creating the object for the class Errors
        $data = array();
        $data['fans'] = $object->getFanwiresFansAll($fwrid);

        foreach ($data['fans'] as $key => $value) {
            foreach ($value as $k => $v) {
                echo '<input type="checkbox"  class="case" name="contacts[]" multiple="multiple" value="' . $v['email'] . '">' . $v['fname'] . '<br>';
            }
        }
    }

    // to save the zoom slider position
    public function zoomPositionsAction() {
        //$object = new Users();//Creating the object for the class Users
        $objectFanwire = new Fanwires(); //Creating the object for the class Fanwires
        $errorObject = new Errors(Error_XML); //Creating the object for the class Errors
        $data = array();
        $page = end(explode('/', $_SERVER['HTTP_REFERER']));
        $pages = explode('?', $page);
        $page = $pages[0];
        if ($_SESSION['id'])
            $session = $_SESSION['id'];
        else
            $session = session_id();
        // if ($res) {
        if (empty($page))
            $page = $session;
        else
            $page = $page; //$res['0'];

















            
// }

        echo $objectFanwire->saveZoomValues($page, $session, $_POST['value'], $_POST['width'], $_POST['height']);
    }

    public function aboutThisFanwireAction() {


        global $fc;
        $objectFanwires = new Fanwires();
        $data = array();
        if (isset($_GET['fwrid'])) {
            $data['fwrid'] = $fwrid = $_GET['fwrid'];
            $data['ac'] = 3;
        } else {
            $arr = explode("=", $fc->extra);
            $data['fwrid'] = $fwrid = $arr[1];
            $data['ac'] = 3;
        }
        $object = new Users(); //Creating the object for the class Users
        $errorObject = new Errors(Error_XML); //Creating the object for the class Errors
        $data['fanwires_for_moreguest'] = $object->getMyFanwiresGuest(); //for guest login
        $obj = new Fanwires(); //Creating the object for the class Fanwires
        $obj->getHistory();
        $data['history'] = $_SESSION['history'];
        $data['active'] = 'fanwire';
        $userDetails = $object->getUserDetails($_SESSION['id']);
        if ($userDetails[0]['profile_image'] != "")
            $data['profile_image'] = SITE_URL . "profile_images/" . $userDetails[0]['profile_image'];
        else
            $data['profile_image'] = SITE_URL . "views/images/logodef.png";
        $data['fanwires'] = $object->getFanwireDetails($fwrid); //fanwires details
        //  $data['fanwires_for_more'] = $object->getMyFanwiresMore();
        $data['fanwires_for_more'] = $objectFanwires->getMasterDetailsMore();
        $data['fanid'] = $uid;
        $data['userid'] = $_SESSION['id'];
        $data['userDetails'] = $object->getUserDetails($uid); //get the user details
        $data['fansFanwiresDeetails'] = $object->getUserDetails($uid); //getFansFanwiresDetails($nids);
        $data['notifications'] = $object->getNotifications($_SESSION['id']);
        $data['connects'] = $object->getConnects($_SESSION['id'], $uid);
        $view = new View(); //Creating the object for the class View
        //echo '<pre>';print_r($data['fanwires']);
        if (isset($_SESSION['name'])) {

            $view->loadView($data, 'fanwires/aboutFanwire');
        } else {

            $view->loadView($data, 'fanwires/aboutFanwireGuest');
        }
    }

    /*
     * function name:deactivateAccount
     * date:13 sep 2012
     * use: Deactivate the current account
     */

    function deactivateAccountAction() {
        $object = new Users(); //Creating the object for the class Users
        $objectFanwire = new Fanwires(); //Creating the object for the class Fanwires
        if ($_POST['submit'] == 'submit') {
            $res = $object->deactivateUser($_SESSION['id'], $_POST['password']);
            if ($res) {
                echo 1;
            } else {
                echo 0;
            }
        }
    }

    /*     * function name ajaxBiography
     * 
     */

    public function ajaxBiographyAction() {
        $obj = new CombineFeatures();
        $boigraphy = $obj->getBio();
        $str = '<div class="plr_about_left"><img src="' . IMAGE_URL . $boigraphy[0]['image'] . '" ' . $boigraphy['heightwidth'] . ' alt="" ></div>
                <div class="plr_about_right"><p>' . $boigraphy['name'] . '</p><span>' . substr($boigraphy[0]['description'], 0, 150) . '";</span>
                </div><div class="clear"></div><div class="plr_about_center" >';
        foreach ($boigraphy[0] as $key => $value) {
            if ($key == "image") {
                break;
            }
            if ($key == "description") {
                
            } else {
                $str.='<p>' . $key . ':<span>' . substr($value, 0, 45) . '</span></p>';
            }
        }
        $str.='</div>';
        $str.=":::" . $boigraphy['navigation'];
        $str.=":::" . $boigraphy['name'];

        echo $str;
    }

}

?>
