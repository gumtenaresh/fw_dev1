<?php

class facebookConnectController {

    public function indexAction() {


        global $fc;

        $data = array();
        $object = new Users(); //Creating Users class object
        $errorObject = new Errors(Error_XML); //creating Error class object
        //"firstname="+fname+"&lastname="+lname+"&username="+name+"&email="+email+"&fb_url_link="+link+"&dob="+birthday+"&sex"+sex+"&fbid="+fbid;
        extract($_POST);


        # We have an active session, let's check if we have already registered the user
        $result = $object->isAlreadyRegisteredUserUsingFb($fbid);

        # If not, let's add it to the database
        $img = file_get_contents('https://graph.facebook.com/' . $fbid . '/picture?type=large');


        $file = DOC_ROOT_PATH . '/profile_images/' . $fbid . '.jpg';
        file_put_contents($file, $img);

        if (empty($result)) {
            //print_r($result);exit;
            $password = RandomNumberGenrator::random_generator(6);
            $result = $object->RegisteredUserUsingFb($fbid, $username, $firstname, $lastname, $sex, $email, $fb_url_link, $dob, $fbid . '.jpg', md5($password));
            if ($result) {
                $to = $email;
                $ip = SITE_URL; //$_SERVER['HTTP_HOST']; //get the ip address
                $subject = 'Your password';
                $body['html'] = " Hi " . $result[0]['username'] . ",<br><br>
                                Welcome to Fanwire !<br>  
                                <p>Your user name is :" . $email . ",<br>
                                Password is :" . $password . "
                                </p><br>
                                Thanks<br><br>
                                Team Fanwire<br><br>
                                <p>
 Note : This is NOT Spam . You have received this mail as a person named " . $result[0]['username'] . " has registered your name to be a member of Fanwire.
 If you are not " . $result[0]['username'] . " and this mail has reached you in error,please delete this mail and accept our apologies for the same. "
                ;

                if (Mailer::sendMail($to, $subject, $body, SUPPORT_EMAIL)) {
                    // this sets variables in the session
                    $_SESSION['id'] = $result[0]['id'];
                    $_SESSION['oauth_uid'] = $result[0]['oauth_uid'];
                    //  $_SESSION['oauth_provider'] = $result[0]['oauth_provider'];
                    $_SESSION['name'] = $result[0]['fname'];
                    $_SESSION['username'] = $result[0]['username'];
                    $_SESSION['fbid'] = $fbid;

                    $link = explode('/', $fb_url_link);
                    $_SESSION['fbusername'] = $link['3'];
                    $res = $object->updateLogin($result[0]['id']);
                    $body2['html'] = '<html><body><div style="background:#01738a; width:800px; height:1070px; margin:0 auto; padding-top:81px;"><div style="width:650px; height:995px; position:relative; background:#FFF; margin:0 auto;"><div style="width:110px; height:122px; position:absolute; top:-67px; left:55px;"><img src="' . SITE_URL . 'views/images/0_text.png" width="110" height="122"/> </div><div style="color:#02a0bf; font-size:14px; padding-top:37px; text-align:right;width:548px; margin:0 auto;"><a href="#"> Contact Us</a></div><div style="width:534px; margin:0 auto;"><p style="color:#686868; font-size:14px; padding-top:30px;">Dear Fellow Fan,</p><p style="color:#686868; font-size:14px; padding-top:30px;">I want to personally thank you for being a part of FanWire.Welcome!</p><p style="color:#686868; font-size:14px; padding-top:30px;">I started FanWire because there are many websites out there to bookmark, too many videos on YouTube to filter through, Tweets to scroll, or Fan Pages to click to, and we are busy people! This site is dedicated to the fans who want it all in one place, an easy and fast method of getting articles, photos, and videos for everything you are a fan of, without having you do the work. We do the work for you, just sit back, connect, and collect.</p><p style="color:#686868; font-size:14px; padding-top:30px;">When you log into FanWire, select some FanWires that interest you. Are we missing something? Please tell us! We have a special form so you can let us know who we need to add to the site, and we&rsquoll do our best to make it happen</p><p style="color:#686868; font-size:14px; padding-top:30px;">There are two features I want to introduce you to when you log back in: The Slider and the Collector. The Slider is the little bar at the top of the page, and when you click and drag it in either direction, it instantly resizes your FanWire blocks into a smaller or larger size. So if you prefer to have much smaller previews and want to skim through them faster, you can do that. If you prefer to have the blocks large and taking up your 40 inch screen like a boss, go for it!</p><p style="color:#686868; font-size:14px; padding-top:30px;">The other feature (and most important in my opinion) is the Collector. When you roll over a media block you can &quot;Collect&quot; an item, which allows you to save it for later in your My Collections section on the side. If you have music videos, photo galleries, or special articles you want to save, go for it. You can always uncollect them later.</p><p style="color:#686868; font-size:14px; padding-top:30px;">I hope this was a helpful introduction email. I want to make sure my fellow fans are happy and I&rsquo;ll continue to build the best site out there. You have my personal email, so if there&rsquo;s anything I can do for you, please let me know.!</p><p style="color:#686868; font-size:14px; padding-top:30px;">Best regards,</p><span style="float:left; padding-right:12px; padding-top:5px;"><img src="' . SITE_URL . 'views/images/0_kory.png" height="156" width="118" alt=""/></span><p>Kory (Founder / CEO)</p></div></div><div style="color:#FFF; font-size:12px;text-align:center; padding:5px 0 0 0;">&copy;Copyright 2012, FanWire</div></div></body></html> ';
                    Mailer::sendMail($to, $subject, $body2, SUPPORT_EMAIL); //sending the invitation

                    if ($res >= '3') {
                        echo true;
                        exit;
                    } else {
                        echo false;
                        exit;
                    }
                }
            } else {
                echo '404 error';
            }
        } else {
            $_SESSION['id'] = $result[0]['id'];
            $_SESSION['oauth_uid'] = $result[0]['oauth_uid'];
            //  $_SESSION['oauth_provider'] = $result[0]['oauth_provider'];
            $_SESSION['name'] = $result[0]['fname'];
            $_SESSION['username'] = $result[0]['username'];
            $_SESSION['fbid'] = $fbid;

            $link = explode('/', $fb_url_link);
            $_SESSION['fbusername'] = $link['3'];
            $res = $object->updateLogin($result[0]['id']);


            if ($res >= '3') {
                echo true;
                exit;
            } else {
                echo false;
                exit;
            }
        }
    }

    /* use:facebook cover image importing
     *
     */

    public function fbCoverImportAction() {
        // authentication
        if (empty($_SESSION['id'])) {
            header("Location:" . SITE_URL);
            exit();
        }
        $Obj = new Fanwires();
        $objectUser = new Users();
        $username = $_SESSION['fbusername'];
        $fbid = $_SESSION['fbid'];
        $userid = $_POST['userid'];
        $url = 'http://graph.facebook.com/' . $fbid . "?fields=cover";

        $data = json_decode($Obj->get_data($url));

        $cover_filename = strtotime(date("Y-m-d H:i:s")) . "_cover.jpg"; //$username.'_cover.jpg';
        $img = file_get_contents($data->cover->source);
        $file = DOC_ROOT_PATH . '/photos/' . $cover_filename;
        file_put_contents($file, $img);

        echo $objectUser->updateFacebookTimelinePhotoUser($cover_filename, $userid);
        exit;
    }

    public function twitterRegisterAction() {
        $screen_name = $_REQUEST['screen_name'];
        $obj = new Users();
        $res = $obj->isAlreadyRegisteredUserUsingTwitter($screen_name);
        if (sizeof($res) == 0) {
            $page_data = file_get_contents("https://api.twitter.com/1/users/show.json?include_entities=true&screen_name=$screen_name");
            $data = json_decode($page_data);

            $img = file_get_contents($data->profile_image_url);
            $file = DOC_ROOT_PATH . '/profile_images/' . $data->id . '.jpg';
            file_put_contents($file, $img);
            $res = $obj->RegisteredUserUsingTwitter($data->name, $screen_name, $data->id . '.jpg');
        }
        $_SESSION['id'] = $res[0]['id'];
        $_SESSION['name'] = $res[0]['fname'];
        $_SESSION['username'] = $res[0]['username'];
    }

}

?>
