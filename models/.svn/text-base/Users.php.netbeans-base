<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Users
 *
 * @author naresh
 */
class Users {

    private $conn = null;
    private $sql = null;
    private $rs = null;
    private $data = array();

    /**
     * creating dao object
     */
    function __construct() {
        $this->conn = Dao::getInstance();
    }

    //destroying memory
    function __destruct() {
        unset($this->conn);
        unset($this->rs);
        unset($this->sql);
        unset($this->data);
    }

    /**
     * isDuplicateEmail  this is used for validating email id
     * @param string $email
     * @return integer 
     * 
     */
    public function isDuplicateEmail($email) {
        $this->sql = "SELECT email FROM tbl_users WHERE email LIKE ?";
        $this->data[0] = $email;
        $this->rs = $this->conn->selectSql($this->sql, $this->data);
        return count($this->rs);
    }

    /**
     * insertNewFanwire Adds a new fanwire
     * @param string $email
     * @param string $password
     * @return integer

     */
    public function insertNewFanwire() {

        $this->sql = "INSERT INTO tbl_users(fname,lname,email,password,gender,DOB,status,cdate)values(?,?,?,?,?,?,?,now())";
        $this->data[0] = $_POST['firstname'];
        $this->data[1] = $_POST['lastname'];
        $this->data[2] = $_POST['email'];
        $this->data[3] = md5($_POST['password']);
        $this->data[4] = $_POST['sex'];
        $this->data[5] = $_POST['years'] . '-' . $_POST['months'] . '-' . $_POST['days'];
        $this->data[6] = '1';
        $this->conn->executePrepared($this->sql, $this->data) or die(mysql_error());
        return mysql_insert_id();
    }

    /**
     * isRegisteredUser
     * @param string $email
     * @param string password
     * @return object
     */
    function isRegisteredUser($email, $password) {
        $this->sql = "SELECT * FROM tbl_users WHERE email='$email' AND password ='$password' and usertype='1' ";
        $this->rs = $this->conn->executeQuery($this->sql);
        if (empty($this->rs)) {
            return false;
        } else {
            return $this->rs;
        }
    }

    /**
     * isvaliddUser check the user is validuser or not 
     * @param string $email
     * @param string $password
     * @return object
     */
    function isvaliddUser($email, $password) {
        $this->sql = "SELECT * FROM tbl_users WHERE email='$email' AND password ='$password' ";
        $this->rs = $this->conn->executeQuery($this->sql);
        if (empty($this->rs)) {
            return 0;
        } else {
            return $this->rs;
        }
    }

    /**
     * isAlreadyRegisteredUserUsingFb  cehck weather is already registered with facebook 
     * @param object $oauth_uid
     * @return array
     */
    function isAlreadyRegisteredUserUsingFb($oauth_uid) {
        $this->sql = "SELECT * FROM tbl_users WHERE registered_from = 'facebook' AND oauth_uid = " . $oauth_uid;
        $this->rs = $this->conn->executeQuery($this->sql);
        return $this->rs;
    }

    /**
     * RegisteredUserUsingFb
     * @param object oauth_uid
     * @param object oauth_name
     * @return array $res
     */
    function RegisteredUserUsingFb($oauth_uid, $oauth_name, $firstname, $lastname, $gender, $email, $link, $birthday, $profile_image, $password) {
        $this->sql = "INSERT INTO tbl_users(registered_from,oauth_uid, username,fname,lname,gender,email,fb_url_link,DOB,profile_image,password,cdate) VALUES (?,?,?,?,?,?,?,?,?,?,?,NOW())";
        $this->data[0] = 'facebook';
        $this->data[1] = $oauth_uid;
        $this->data[2] = $oauth_name;
        $this->data[3] = $firstname;
        $this->data[4] = $lastname;
        $this->data[5] = $gender;
        $this->data[6] = $email;
        $this->data[7] = $link;
        $this->data[8] = $birthday;
        $this->data[9] = $profile_image;
        $this->data[10] = $password;
        $this->conn->executePrepared($this->sql, $this->data) or die(mysql_error());
        $res = self::getUserDetails(mysql_insert_id());
        return $res;
    }

    /**
     * gettingUserDeetails
     * @param integer id
     * @return array $data
     */
    function getUserDetails($id) {
        $this->sql = "SELECT * FROM tbl_users WHERE id = " . $id;
        //  error_log($id.'asdfdasdfasdfasdf');
        $this->rs = $this->conn->executeQuery($this->sql);
        $i = 0;
        $data = array();
        foreach ($this->rs as $value) {
            $data[$i]['id'] = $value['id'];
            $data[$i]['fname'] = $value['fname'];
            $data[$i]['lname'] = $value['lname'];
            $data[$i]['username'] = $value['username'];
            $data[$i]['short_user_name'] = $value['short_user_name'];
            $data[$i]['email'] = $value['email'];
            $data[$i]['gender'] = $value['gender'];
            $data[$i]['DOB'] = $value['DOB'];
            $data[$i]['cdate'] = $value['cdate'];
            $data[$i]['profile_image'] = $value['profile_image'];
            $data[$i]['cover_photo'] = $value['cover_photo'];
            $data[$i]['registered_from'] = $value['registered_from'];
            $data[$i]['twitter_oauth_token'] = $value['twitter_oauth_token'];
            $data[$i]['twitter_oauth_token_secret'] = $value['twitter_oauth_token_secret'];
            $data[$i]['usertype'] = $value['usertype'];
            $data[$i]['status'] = $value['status'];
            $data[$i]['oauth_uid'] = $value['oauth_uid'];
            $data[$i]['privacy'] = $value['privacy'];
            list($width, $height) = getimagesize(DOC_ROOT_PATH . "/profile_images/" . $value['profile_image']);
            $data[$i]['width'] = $width;
            $data[$i]['height'] = $height;
            $i++;
        }
        return $data;
    }

    /**
     * gettingUserDeetails
     * @param  integer id
     * @return array $data
     */
    function getUserDetailsCombine($id) {
        $this->sql = "SELECT * FROM tbl_users WHERE id in(" . $id . ")";
        $this->rs = $this->conn->executeQuery($this->sql);
        $i = 0;
        $object = new Fanwires();
        foreach ($this->rs as $value) {
            $data[$i]['id'] = $value['id'];
            $data[$i]['name'] = $value['fname'] . ' ' . $value['lname'];
            $data[$i]['description'] = $value['description'];
            $data[$i]['profile_image'] = $value['profile_image'];
            $data[$i]['cover_photo'] = $this->getCoverPhoto($value['id']);
            $data[$i]['title'] = "";
            list($width, $height) = getimagesize(DOC_ROOT_PATH . "/photos/" . $data[$i]['photo']);
            $data[$i]['width'] = $width;
            $data[$i]['height'] = $height;
            $data[$i]['link'] = SITE_URL . "profile?uid=" . $value['id'] . "&ac=1";
            $arr = explode(",", self::getLikesCount($value['id'], '0'));
            $data[$i]['like'] = $arr[0];
            $data[$i]['dislike'] = $arr[1];
            $data[$i]['commentcnt'] = self::getCommentCount($value['id'], '0');
            $data[$i]['status'] = $object->getCollectedStatus($value['id'], '0');
            $data[$i]['type'] = '0';
            $data[$i]['source'] = '';
            $data[$i]['comments_for_this_post'] = self::getComments($value['id'], '0');
            $data[$i]['cdate'] = $value['last_updated'];
            $i++;
        }
        return $data;
    }

    /**
     * getCommonFawires
     * @param integer $id
     * @param integer $uid
     * @return array object
     */
    function getCommonFanwires($id, $uid) {
        $this->sql = " SELECT fanid, COUNT(  `fanid` ) AS fancnt FROM tbl_fanwire_user WHERE user_id IN (" . $id . "," . $uid . ") GROUP BY fanid HAVING fancnt =2";
        $this->rs = $this->conn->executeQuery($this->sql);
        return $this->rs;
    }

    /**
     * isAlreadyRegisteredUserUsingTwitter
     * @param string $username 
     * @return array
     */
    function isAlreadyRegisteredUserUsingTwitter($username) {
        $this->sql = "SELECT * FROM tbl_users WHERE registered_from = 'twitter' AND username =  '$username'";
        $this->rs = $this->conn->executeQuery($this->sql);
        return $this->rs;
    }

    /**
     * RegisteredUserUsingTwitter
     * @param object $oauth_uid
     * @param object $oauth_token
     * @param object $oauth_token_secret
     * @param object $name
     * @param object $image
     * @return array $res
     */
    function RegisteredUserUsingTwitter($oauth_uid, $oauth_token, $oauth_token_secret, $name, $image) {
        $this->sql = "INSERT INTO tbl_users(registered_from,oauth_uid,twitter_oauth_token,twitter_oauth_token_secret,username,profile_image,cdate) VALUES (?,?,?,?,?,?,NOW())";
        $this->data[0] = 'twitter';
        $this->data[1] = $oauth_uid;
        $this->data[2] = $oauth_token;
        $this->data[3] = $oauth_token_secret;
        $this->data[4] = $name;
        $this->data[5] = $image;
        $this->conn->executePrepared($this->sql, $this->data) or die(mysql_error());
        $res = self::getUserDetails(mysql_insert_id());
        return $res;
    }

    /**
     * updateProfile
     * @param: array $pic_path
     * @return :boolean
     */
    public function updateProfile($pic_path) {
        $this->sql = "UPDATE tbl_users SET profile_image = '" . mysql_real_escape_string($pic_path) . "' WHERE id ='" . $_SESSION['id'] . "'";
        return $this->conn->executeUpdate($this->sql);
    }

    /**
     * updateName
     * @param object $data 
     * @return array 
     */
    public function updateName($data) {
        extract($data);
        $this->sql = "UPDATE tbl_users SET fname = '" . mysql_real_escape_string($fname) . "',lname='" . mysql_real_escape_string($lname) . "',email='" . mysql_real_escape_string($email) . "' WHERE id ='" . $_SESSION['id'] . "'";
        return $this->conn->executeUpdate($this->sql);
    }

    /**
     * updateProfile
     * @param string $name
     * @return boolean
     */
    public function updateUrl($name) {
        $this->sql = "UPDATE tbl_users SET url = '" . mysql_real_escape_string($name) . "' WHERE id ='" . $_SESSION['id'] . "'";
        return $this->conn->executeUpdate($this->sql);
    }

    /**
     *  change password
     * @param string $password 
     * @return object 
     */
    public function changePassword($password) {

        $this->sql = "UPDATE tbl_users SET password = '" . mysql_real_escape_string($password) . "' WHERE id ='" . $_SESSION['id'] . "'";
        return $this->conn->executeUpdate($this->sql);
    }

    /**
     *  check password
     * @param string $password 
     * @return object 
     */
    public function checkPassword($password) {
        $this->sql = "select * from tbl_users WHERE password=? and id =?";
        $this->conn->selectSql($this->sql, array(md5($password), $_SESSION['id']));
        return $this->conn->getAffectedRows();
    }

    /**
     * change email
     * @param string $email 
     * @return object 
     */
    public function changeEmail($email) {
        $this->sql = "UPDATE tbl_users SET email = '" . mysql_real_escape_string($email) . "' WHERE id ='" . $_SESSION['id'] . "'";
        return $this->conn->executeUpdate($this->sql);
    }

    /**
     * getAllFanwires
     * @return array $data 
     */
    public function getAllFanwires() {
        //echo "hello ".$id;
        $this->sql = "SELECT * FROM tbl_fanwires ORDER BY id DESC LIMIT 6 ";
        $this->rs = $this->conn->executeQuery($this->sql);
        $i = 0;
        foreach ($this->rs as $key => $value) {
            $data[$i]['id'] = $value['id'];
            $data[$i]['name'] = $value['name'];
            $data[$i]['photo'] = $value['photo'];
            if (strlen($value['description']) > 100) {
                $data[$i]['description'] = substr($value['description'], 0, 100) . "...";
            } else {
                $data[$i]['description'] = $value['description'];
            }
            $i++;
        }

        return $data;
    }

    /**
     * getMyFanwires
     * @return arrray $data
     */
    public function getMyFanwires() {
        $obj = new Users();
        $this->sql = "SELECT fw.id, fw.description, fw.name, fwu.user_id, fwu.fan_status, fwu.fanid FROM tbl_fanwire_user fwu, tbl_fanwires fw where fw.id = fwu.fanid AND fwu.fan_status = '1' AND fwu.user_id = '" . $_SESSION['id'] . "' and fw.status=1 ORDER BY fw.name ASC "; //LIMIT 6
        $this->rs = $this->conn->executeQuery($this->sql);
        $i = 0;
        foreach ($this->rs as $key => $value) {
            $data[$i]['id'] = $value['id'];
            $data[$i]['name'] = $value['name'];
            $data[$i]['photo'] = $this->getAvtharPhoto($value['id']);
            list($width, $height) = getimagesize(DOC_ROOT_PATH . "/photos/" . $data[$i]['photo']);
            $data[$i]['width'] = $width;
            $data[$i]['height'] = $height;
            $data[$i]['description'] = $value['description'];
            $arr = explode(",", $obj->getLikesCount($value['id'], '0'));
            $data[$i]['like'] = $arr[0];
            $data[$i]['dislike'] = $arr[1];
            $data[$i]['commentcnt'] = $this->getCommentCount($value['id'], '0');
            $data[$i]['status'] = $value['status'];
            $data[$i]['fan_status'] = $value['fan_status'];
            
            $data[$i]['comments_for_this_post'] = $this->getComments($value['id'], '0');
            $i++;
        }
        return $data;
    }

    /**
     * getMyFanwiresMore 
     */
    public function getMyFanwiresMore() {
        $this->sql = "SELECT DISTINCT fw.id, fw.description, fw.name, fw.url as fanwire,fwu.user_id, fwu.fan_status, fwu.fanid
                      FROM tbl_fanwires fw, `tbl_fanwire_user` fwu
                      WHERE fw.id = fwu.fanid and `fan_status` = '1' and fw.status='1'
                      AND `user_id` = '" . $_SESSION['id'] . "'
                      ORDER BY name ASC";
        $this->rs = $this->conn->executeQuery($this->sql);
        $i = 0;
        foreach ($this->rs as $key => $value) {
            $data[$i]['id'] = $value['id'];
            $data[$i]['name'] = $value['name'];
            $data[$i]['url'] = $value['fanwire'];
            $data[$i]['fanwire'] = $value['fanwire'];
            $data[$i]['photo'] = $this->getAvtharPhoto($value['id']);
            if (strlen($value['description']) > 100) {
                $data[$i]['description'] = substr($value['description'], 0, 100) . "...";
            } else {
                $data[$i]['description'] = $value['description'];
            }
            $data[$i]['count'] = $i + 1;
            $i++;
        }

        return $data;
    }

    /**
     * getMyFanwiresMore 
     */
    public function getMyFanwiresGuest() {
        $date = date("Y-m-d H:i:s");
        $obj = new Fanwires();
        $this->sql = "SELECT * FROM tbl_fanwires where status='1' and released_date<='$date' ORDER BY name ASC";
        $this->rs = $this->conn->executeQuery($this->sql);
        $i = 0;
        foreach ($this->rs as $key => $value) {
            $data[$i]['id'] = $value['id'];
            $data[$i]['name'] = $value['name'];
            $data[$i]['url'] = $value['url'];
            $data[$i]['photo'] = $this->getAvtharPhoto($value['id']);
            list($width, $height) = getimagesize(DOC_ROOT_PATH . "/photos/" . $data[$i]['photo']);
            $data[$i]['width'] = $width;
            $data[$i]['height'] = $height;

            if (strlen($value['description']) > 100) {
                $data[$i]['description'] = substr($value['description'], 0, 100) . "...";
            } else {
                $data[$i]['description'] = $value['description'];
            }
            $category = $obj->getFanwireCategories($value['id']);
            if (sizeof($category) > 0) {
                if ($category[1]['name'] != "")
                    $dd = ' / ' . $category[1]['name'];

                $data[$i]['category'] = $category[0]['name'] . $dd;
            }
            else
                $data[$i]['category'] = "";

            $data[$i]['count'] = $i + 1;
            $i++;
        }

        return $data;
    }

    /**
     * getAllFanwires with categiry 
     */
    public function getAllFanwiresCatPop($cat) {

        $this->sql = "select f.id,f.name,f.description 
                                from tbl_fanwires f,tbl_fanwire_categories fc
                                where f.id=fc.fanwire_id and fc.category='$cat' and f.status='1' order by f.name";//fc.category='$cat' and ;
        $this->rs = $this->conn->executeQuery($this->sql);
        $i = 0;
        foreach ($this->rs as $key => $value) {
            $data[$i]['id'] = $value['id'];
            $data[$i]['name'] = $value['name'];
            $data[$i]['fan_status'] = $this->getUserFanStatus($value['id']);
            $data[$i]['photo'] = $this->getAvtharPhoto($value['id']);
            if (strlen($value['description']) > 100) {
                $data[$i]['description'] = $value['description'];//substr($value['description'], 0, 100) . "...";
            } else {
                $data[$i]['description'] = $value['description'];
            }
            $data[$i]['photo'] = $this->getAvtharPhoto($value['id']);
            list($width, $height) = getimagesize(DOC_ROOT_PATH . "/photos/" . $data[$i]['photo']);
            $data[$i]['width'] = $width;
            $data[$i]['height'] = $height;
            $arr = explode(",", Users::getLikesCount($value['id'], '0'));
            $data[$i]['like'] = $arr[0];
            $data[$i]['dislike'] = $arr[1];
            $i++;
        }
        

        return $data;
    }

    /**
     * getUserFanStatus
     * @param integer $id
     * @return integer o or 1
     */
    function getUserFanStatus($id) {
        $this->sql = "select fan_status from tbl_fanwire_user where fanid=$id and user_id=" . $_SESSION['id'];
        $this->rs = $this->conn->executeQuery($this->sql);
        if ($this->rs['0']['fan_status'] == '1') {
            return $this->rs['0']['fan_status'];
        } else {
            return '0';
        }
    }

    /**
     * getAvtharPhoto
     * @param integer $fanwireid 
     * @return string 
     */
    function getAvtharPhoto($fanwireid) {
        $this->sql = "SELECT url from tbl_avatar_photos WHERE fanwire_id = ? and main_avator='1'";
        $this->rs = $this->conn->selectSql($this->sql, array($fanwireid));
        return $this->rs[0]['url'];
    }

    /**
     * getCoverPhoto
     * @param integer $fanwireid 
     * @return string 
     */
    function getCoverPhoto($fanwireid) {
        $this->sql = "SELECT url from tbl_timeline_photos
                      WHERE fanwire_id = ? and main_timeline='1'";
        $this->rs = $this->conn->selectSql($this->sql, array($fanwireid));
        return $this->rs[0]['url'];
    }

    /**
     * getAllFanwires with out category
     * getAllFanwiresWithOutCat
     * @return object $data
     */
    public function getAllFanwiresWithOutCat() {
        $this->sql = " SELECT DISTINCT fw.id, fw.category1, fw.description, fw.name, fwu.user_id, fwu.fan_status, fwu.fanid FROM tbl_fanwires fw, `tbl_fanwire_user` fwu WHERE fw.id = fwu.fanid and `fan_status` = '1' AND `user_id` = '" . $_SESSION['id'] . "' ORDER BY id DESC LIMIT 21 ";
        $this->rs = $this->conn->executeQuery($this->sql);
        $i = 0;
        foreach ($this->rs as $key => $value) {
            $data[$i]['id'] = $value['id'];
            $data[$i]['name'] = $value['name'];
            $data[$i]['user_id'] = $value['user_id'];
            $data[$i]['fanid'] = $value['fanid'];
            $data[$i]['fan_status'] = $value['fan_status'];
            $data[$i]['photo'] = $this->getAvtharPhoto($value['id']);
            if (strlen($value['description']) > 100) {
                $data[$i]['description'] = substr($value['description'], 0, 100) . "...";
            } else {
                $data[$i]['description'] = $value['description'];
            }
            $i++;
        }

        return $data;
    }

    /**
     * getAllFanwires with out category
     * getAllFanwiresWithOutCat2
     * @return object $data
     */
    public function getAllFanwiresWithOutCat2() {
        $this->sql = "select * from tbl_fanwires";
        $this->rs = $this->conn->executeQuery($this->sql);
        $i = 0;
        foreach ($this->rs as $key => $value) {
            $data[$i]['id'] = $value['id'];
            $data[$i]['name'] = $value['name'];
            $data[$i]['photo'] = $this->getAvtharPhoto($value['id']);
            $data[$i]['userStatus'] = $this->getUserFanStatus($value['id']);
            if (strlen($value['description']) > 100) {
                $data[$i]['description'] = substr($value['description'], 0, 100) . "...";
            } else {
                $data[$i]['description'] = $value['description'];
            }
            $i++;
        }


        return $data;
    }

    /**
     *  Forgot pass
     * @param string $password new password to update with old one
     * @param string $email
     * @return object 
     */
    public function forgotPass($password, $email) {
        $this->sql = "UPDATE tbl_users SET password = '" . mysql_real_escape_string($password) . "' WHERE email ='" . $email . "'";
        return $this->conn->executeUpdate($this->sql);
    }

    /**
     * updateemailpassword
     * @param string $email
     * @param string $password
     * @return boolean
     */
    public function updateEmailPass($email, $password) {
        $this->sql = "UPDATE tbl_users SET  `email` = '" . mysql_real_escape_string($email) . "' ,`password`='" . mysql_real_escape_string($password) . "' WHERE `id` ='" . $_SESSION['id'] . "'";
        return $this->conn->executeUpdate($this->sql);
    }

    /**
     * change privacy
     * @param string $privacy 
     * @return object
     */
    public function changeprivacy($privacy) {
        $this->sql = "UPDATE tbl_users SET privacy = '" . mysql_real_escape_string($privacy) . "' WHERE id ='" . $_SESSION['id'] . "'";
        return $this->conn->executeUpdate($this->sql);
    }

    /**
     * getUserList  this function is used to get users list.
     * @param integer $type 
     * @param integer $pageSize
     * @return array $data
     */
    public function getUsersList($type = 1, $pageSize = 100) {
        $this->sql = "SELECT * FROM tbl_users WHERE usertype='$type' and status = 1 ";
        $pageObject = new Pagination($pageSize, 9); //params: pagesize, scroll size
        $navigation = $pageObject->showNavigation(sizeof($this->conn->executeQuery($this->sql)));
        $this->sql .= $pageObject->getLimitQry();
        $this->rs = $this->conn->executeQuery($this->sql);
        $data['list'] = $this->rs;
        $data['navigation'] = $navigation;
        return $data;
    }

    public function getAdminList($pageSize = 100) {
        $this->sql = "SELECT * FROM tbl_users WHERE usertype in ('2','3') and status = 1 ";
        $pageObject = new Pagination($pageSize, 9); //params: pagesize, scroll size
        $navigation = $pageObject->showNavigation(sizeof($this->conn->executeQuery($this->sql)));
        $this->sql .= $pageObject->getLimitQry();
        $this->rs = $this->conn->executeQuery($this->sql);
        $data['list'] = $this->rs;
        $data['navigation'] = $navigation;
        return $data;
    }

    /**
     * deleteUser
     * @param integer $id
     */
    public function deleteUser($id) {
        $this->sql = "update tbl_users set status=2 where id=?";
        $this->conn->executePrepared($this->sql, array($id)) or die(mysql_error());
    }

    /**
     *
     * @param type $id
     */
    public function deactivateUser($id, $password) {

        $re = self::checkPassword($password);
        if ($re == 1) {
            $this->sql = "update tbl_users set account_status='0',de_re_activated_time=NOW() where id=?";
            $this->conn->executePrepared($this->sql, array($id)) or die(mysql_error());
            return $this->conn->getAffectedRows();
        }else
            return 0;
    }

    /**
     *
     * @param type $id
     */
    public function activateAccount($id) {
        $this->sql = "update tbl_users set account_status='1',de_re_activated_time=NOW() where id=?";
        $this->conn->executePrepared($this->sql, array($id)) or die(mysql_error());
    }

    /**
     *   This Function Used To Authenticate admin
     * @param email
     * @param password
     * return admin array/false
     */
    public function authenticateUser($email, $password) {
        $this->sql = "SELECT * FROM tbl_users WHERE email=? AND password=? and usertype in ('2','3') and status ='1'";
        $this->rs = $this->conn->selectSql($this->sql, array($email, md5($password)));
        if (count($this->rs) > 0) {
            return $this->rs;
        }
        return false;
    }

    /**
     *
     * @param type $id
     * @param type $password
     * @return boolean
     */
    public function isAuthorisedUser($id, $password, $type) {
        $this->sql = "SELECT * FROM tbl_users WHERE id=? AND password=? and usertype=? and status =1";
        $this->rs = $this->conn->selectSql($this->sql, array($id, md5($password), $type));
        if (count($this->rs) > 0) {
            return true;
        }
        return false;
    }

    /**
     *
     * @param type $id
     * @param type $password
     * @return boolean
     */
    public function updatePassword($id, $password) {
        $this->sql = "update tbl_users set password=? where id=?";
        $this->rs = $this->conn->executePrepared($this->sql, array(md5($password), $id));
    }

    /*
      getAllFanwires with categiry

     */

    public function getCategory($list) {
        //echo "hello ".$id;
        $this->sql = "SELECT id FROM tbl_category WHERE `name`='" . $list . "'";
        $this->rs = $this->conn->executeQuery($this->sql);

        return $this->rs;
    }

    //adding Fanwire in to his.her account means user is fan of fanwire
    //addFanwire

    public function addFanwire($id_fanwire) {

        if (self::checkFanwire($id_fanwire) > 0) {

            $this->sql = "update tbl_fanwire_user set `fan_status`='1' where `user_id`=? and `fanid`=?";
            $data = array($_SESSION['id'], $id_fanwire);
            return $this->conn->executePrepared($this->sql, $data) or die(mysql_error());
        } else {

            $this->sql = "insert into tbl_fanwire_user(`user_id`,`fanid`,`fan_status`) values(?,?,?)";
            $data = array($_SESSION['id'], $id_fanwire, '1');
            return $this->conn->executePrepared($this->sql, $data) or die(mysql_error());
        }
    }

    //un Fanwire in to his.her account means user is fan of fanwire
    //unFanwire

    public function unFanwire($id_fanwire) {

        $this->sql = "update tbl_fanwire_user set `fan_status`='0' where `user_id`=? and `fanid`=?";
        $data = array($_SESSION['id'], $id_fanwire);
        return $this->conn->executePrepared($this->sql, $data) or die(mysql_error());
    }

    //check the user is alaeaady the fan of celebrity or nor
    public function checkFanwire($id_fanwire) {

        $this->sql = "select count(*) as count from  tbl_fanwire_user where `user_id`= '" . $_SESSION['id'] . "'  and `fanid`='" . $id_fanwire . "'";
        $this->rs = $this->conn->selectSql($this->sql, $id_fanwire) or die(mysql_error());
        return $this->rs[0]['count'];
    }

    //check the user is fan of three fanwires
    public function checkMinThreeFan() {

        $this->sql = "select count(*) as count from  tbl_fanwire_user where `user_id`= '" . $_SESSION['id'] . "'  and `fan_status`='1'";
        $this->rs = $this->conn->selectSql($this->sql, $id) or die(mysql_error());
        return $this->rs[0]['count'];
    }

    /*
      for browsing the fanwires

     */

    public function getAllFanwiresBrowse() {
        $obj = new Users();
        $this->sql = "
            SELECT *
FROM `tbl_fanwires` where status=1 and id NOT IN (SELECT fanid from tbl_fanwire_user WHERE user_id = '" . $_SESSION['id'] . "' AND fan_status LIKE 1)
ORDER BY name ASC ";
        $this->rs = $this->conn->executeQuery($this->sql);
        $i = 0;
        foreach ($this->rs as $key => $value) {
            $data[$i]['id'] = $value['id'];
            $data[$i]['name'] = $value['name'];
            $data[$i]['photo'] = $this->getAvtharPhoto($value['id']);
            $arr = explode(",", $obj->getLikesCount($value['id'], '0'));
            $data[$i]['like'] = $arr[0];
            $data[$i]['dislike'] = $arr[1];

            if (strlen($value['description']) > 100) {
                $data[$i]['description'] = $value['description'];//substr($value['description'], 0, 100) . "...";
            } else {
                $data[$i]['description'] = $value['description'];
            }
            $i++;
        }

        return $data;
    }

    //getting the selected fanwire personal details
    public function getFanwireDetails($fwrid) {
        $obj = new Fanwires();
        $this->sql = "SELECT * FROM tbl_fanwires WHERE id=" . $fwrid;
        $this->rs = $this->conn->executeQuery($this->sql);
        $dd = ''; //second catergory
        foreach ($this->rs as $key => $value) {
            $data['id'] = $value['id'];
            $data['name'] = $value['name'];
            $data['url'] = $value['url'];
            $data['facebook'] = $value['facebook'];
            $data['photo'] = $this->getAvtharPhoto($value['id']);
            $data['cover_photo'] = $this->getCoverPhoto($value['id']);
            $data['description'] = $value['description'];
            $category = $obj->getFanwireCategories($value['id']);
            if (sizeof($category) > 0) {
                if ($category[1]['name'] != "")
                    $dd = ' / ' . $category[1]['name'];
                $data['category'] = $category[0]['name'] . $dd;
                $data['categoryid'] = $category[0]['id'];
            }
            else
                $data['category'] = "";
            $data['areTheyFan'] = $this->checkAreTheyFan($value['id']);
            $data['numberOfFans'] = $this->getCountOfFansOfFanwire($value['id']);
        }

        return $data;
    }

    //getting the category name by id and its category
    public function getCategoryName($id, $val) {
        //echo "hello ".$id;
        $this->sql = "SELECT name FROM tbl_category WHERE `id`='" . $id . "'";
        $this->rs = $this->conn->executeQuery($this->sql);

        return $this->rs[0]['name'];
    }

    /*
     * Activate user
     * */

    public function updateUserEmail($id) {
        $this->sql = "UPDATE tbl_users SET status = '1' WHERE id ='" . $id . "'";
        return $this->conn->executeUpdate($this->sql);
    }

    /*
     * updateLogin
     * */

    public function updateLogin($id) {
        $this->sql = "SELECT count(*) as coun FROM tbl_fanwire_user WHERE user_id=" . $id;
        $this->rs = $this->conn->executeQuery($this->sql);

        return $this->rs[0]['coun'];
    }

    public function updateUserProfile($data) {

        extract($data);
        $this->sql = "UPDATE `tbl_users` SET `website` = '" . $data['website'] . "',`hometown` = '" . $data['hometown'] . "',`currentcity` = '" . $data['currentcity'] . "',`likes` = '" . $data['keyword'] . "' WHERE `tbl_users`.`id` =" . $_SESSION['id'];

        //$this->sql = "UPDATE tbl_users SET profile_image = '' WHERE id ='" . $_SESSION['id'] . "'";
        return $this->conn->executeUpdate($this->sql);
    }

    /*
     * update user profile images */

    public function updateProfileImage($id) {
        extract($data);
        $this->sql = "UPDATE tbl_users SET profile_image = '' WHERE id ='" . $id . "'";
        return $this->conn->executeUpdate($this->sql);
    }

    //get fanwires fans id
    public function getFanwiresFans($fwrid) {

        $this->sql = "SELECT user_id
FROM `tbl_fanwire_user`
WHERE `fanid` = '" . $fwrid . "'
AND `fan_status` = '1'";
        $this->rs = $this->conn->executeQuery($this->sql);

        $i = 0;
        foreach ($this->rs as $key => $value) {
            $id = $value['user_id'];
            $data[$i] = self::getUserDetails($id);
            $i++;
        }
        //echo "<pre>";print_r($data);
        return $data;
    }

    //get fanwires fans id Allll
    public function getFanwiresFansAll($fwrid) {
        $this->sql = "SELECT user_id
FROM `tbl_fanwire_user`
WHERE `fanid` = '" . $fwrid . "'
AND `fan_status` = '1'
 ";
        $this->rs = $this->conn->executeQuery($this->sql);

        $i = 0;
        foreach ($this->rs as $key => $value) {
            $id = $value['user_id'];
            $data[$i] = self::getUserDetails($id);
            $i++;
        }

        return $data;
    }

    /**
     *
     * @param type
     * @param type
     * @return boolean
     */
    public function removeTheFanwire($id, $type) {

        if ($type == 0) {
            $this->sql = "update tbl_fanwire_user set fan_status='0' where user_id=? and fanid=?";
            $res = $this->rs = $this->conn->executePrepared($this->sql, array($_SESSION['id'], $id));
        } elseif ($type == 1) {
            $this->sql = "update tbl_albums set visible='0' where id=?";
            $res = $this->rs = $this->conn->executePrepared($this->sql, array($id));
        } elseif ($type == 2) {
            $this->sql = "update tbl_articles set visible='0' where id=?";
            $res = $this->rs = $this->conn->executePrepared($this->sql, array($id));
        } else {
            $this->sql = "update tbl_videos set visible='0' where id=?";
            $res = $this->rs = $this->conn->executePrepared($this->sql, array($id));
        }
        return $res;
    }

    public function getLikesCount($id, $type) {

        $sql = "select sum(`like`) as `like`,sum(`dislike`) as `dislike` from tbl_fanwire_likes where `fanwireid`='$id' and `type`='$type'";

        $rs = $this->conn->executeQuery($sql);

        return $data = $rs[0]['like'] . "," . $rs[0]['dislike'];
    }

    public function getCommentCount($id, $type) {
        //$this->sql = "select count(*) as cnt from tbl_comments where receiver_id=?";
        $this->sql = "select count(*) as cnt from tbl_comments where receiver_id=? AND receiver_type=?";
        $this->rs = $this->conn->selectSql($this->sql, array($id, $type));
        return $this->rs[0]['cnt'];
    }

    public function fanwireLikes($fanwireid, $userid, $like, $dislike, $type) {
        $this->sql = "select count(*) as cnt from tbl_fanwire_likes where fanwireid=? and userid=? and type=?";
        $this->rs = $this->conn->selectSql($this->sql, array($fanwireid, $userid, $type));
        if ($this->rs[0]['cnt'] > 0) {
            return false;
        } else {
            $this->sql = "insert into tbl_fanwire_likes(`userid`,`fanwireid`,`like`,`dislike`,`type`) values(?,?,?,?,?)";
            $this->rs = $this->conn->executePrepared($this->sql, array($userid, $fanwireid, $like, $dislike, $type));
        }
        $this->sql = "select sum(`like`) as `like`,sum(`dislike`) as `dislike` from tbl_fanwire_likes where fanwireid=? and type=?";
        $this->rs = $this->conn->selectSql($this->sql, array($fanwireid, $type));
        return $this->rs[0]['like'] . "::" . $this->rs[0]['dislike'];
    }

    public function connectAsFriend($id1, $id2, $status) {
        $this->sql = "insert into tbl_user_friends(`requester_user_id`,`accepter_user_id`,`status`,`time`) values(?,?,?,NOW())";
        return $this->rs = $this->conn->executePrepared($this->sql, array($id1, $id2, $status));
    }

    public function getConnects($id, $uid) {
        $this->sql = "select * from tbl_user_friends where requester_user_id =? and accepter_user_id=?";
        return $this->rs = $this->conn->selectSql($this->sql, array($id, $uid));
    }

    public function createThumbs($fname, $pathToImages, $pathToThumbs, $thumbWidth) {
        $info = pathinfo($pathToImages . $fname);
        if (strtolower($info['extension']) == 'jpg' || strtolower($info['extension']) == 'jpeg') {
            $img = imagecreatefromjpeg($pathToImages . $fname);
        } else if (strtolower($info['extension']) == "png") {
            $img = imagecreatefrompng($pathToImages . $fname);
        } else {
            $img = imagecreatefromgif($pathToImages . $fname);
        }

        $width = imagesx($img);
        $height = imagesy($img);
        $new_width = $thumbWidth;
        $new_height = floor($height * ( $thumbWidth / $width ));


        $tmp_img = imagecreatetruecolor($new_width, $new_height);
        imagecopyresized($tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        $res=imagejpeg($tmp_img, $pathToThumbs . $fname);
        return $res;
    }

    /* public function getAlbumsList($userid) {
      $this->sql = "select a.id,a.name,a.album_url,a.status, count( p.album_id ) AS photoscnt from tbl_albums a left join tbl_photos p on a.id=p.album_id and p.userid=? group by a.id limit 0,6";
      $this->rs = $this->conn->selectSql($this->sql, array($userid));
      return $this->rs;
      } */

    public function getUserName($userid) {
        $sql = "select fname,lname from tbl_users where id=?";
        $rs = $this->conn->selectSql($sql, array($userid));
        return $rs[0]['fname'] . " " . $rs[0]['lname'];
    }

    /**
     * getAlbumList
     * @param integer $userid
     * @return array $data
     */
    public function getAlbumsList($userid) {
        $obj = new Users();
        $this->sql = "select a.id,a.name,a.album_url,a.status, count(p.album_id ) AS photoscnt from tbl_albums a left join tbl_photos p on a.id=p.album_id and p.userid=? where a.belongsTo=1 group by a.id limit 0,6";
        $this->rs = $this->conn->selectSql($this->sql, array($userid));
        $i = 0;
        foreach ($this->rs as $val) {
            $this->data[$i]['id'] = $val['id'];
            $this->data[$i]['title'] = $val['title'];
            $this->data[$i]['name'] = $val['name'];
            $this->data[$i]['description'] = stripslashes($val['description']);
            $this->data[$i]['photo'] = $val['photo'];
            $this->data[$i]['album_url'] = $val['album_url'];
            $this->data[$i]['photoscnt'] = $val['photoscnt'];
            $this->data[$i]['status'] = $val['status'];
            $arr = explode(",", $obj->getLikesCount($val['id']));
            $this->data[$i]['like'] = $arr[0];
            $this->data[$i]['dislike'] = $arr[1];
            $this->data[$i]['commentcnt'] = $obj->getCommentCount($val['id'], 1);
            $this->data[$i]['userid'] = $this->getUserName($val['user_id']);
            $this->data[$i]['comments_for_this_post'] = $obj->getComments($val['id'], 1);
            $i++;
        }
        return $this->data;
    }

    /**
     * getnotifications
     * @param integer $id 
     * @return array returns users information
     */
    public function getNotifications($id) {
        $this->sql = "select * from tbl_user_friends where accepter_user_id=? and status=1";
        $this->rs = $this->conn->selectSql($this->sql, array($id));
        return $this->rs;
    }

    /**
     * getFansOfUser
     * @param integer $id 
     * @return array returns users information
     */
    public function getFansOfUser($id) {
        $this->sql = "select * from tbl_user_friends where accepter_user_id=? and status=4";
        $this->rs = $this->conn->selectSql($this->sql, array($id));
        return $this->rs;
    }

    /**
     * requestAction 
     * @param integer $userId 
     * @param integer $fanId 
     * @param integer $action
     * @return obejct 
     */
    public function requestAction($userId, $fanId, $action) {

        $this->sql = "update tbl_user_friends set `status`=? where `requester_user_id`=? and `accepter_user_id`=?";
        $this->rs = $this->conn->executePrepared($this->sql, array($action, $fanId, $userId));
        return $this->rs;
    }

    /**
     * getAllUser
     * @return array
     */
    public function getAllUser() {
        $this->sql = "select * from tbl_users where usertype=1 and status='1'";
        $this->rs = $this->conn->executeQuery($this->sql);
        return $this->rs;
    }

    /**
     * getFansFanwires
     * @param integer $uid 
     * @return array returns fanwires fans list
     */
    public function getFansFanwires($uid) {//fan is one of user
        $this->sql = "select * from tbl_fanwire_user where `user_id`=? and `fan_status`='1'";
        $this->rs = $this->conn->selectSql($this->sql, array($uid));
        return $this->rs;
    }

    /**
     * getFansFanwiresDetails
     * @param array $ids list of fanwires id's
     * @return array $data
     */
    public function getFansFanwiresDetails($ids) {//fan is one of user
        $this->sql = "select * from tbl_fanwires where `id` in ($ids)";
        $this->rs = $this->conn->executeQuery($this->sql);
        $i = 0;
        $object = new Fanwires();
        foreach ($this->rs as $value) {
            $data[$i]['id'] = $value['id'];
            $data[$i]['name'] = $value['name'];
            $data[$i]['description'] = $value['description'];
            $data[$i]['photo'] = $this->getAvtharPhoto($value['id']);
            list($width, $height) = getimagesize(DOC_ROOT_PATH . "/photos/" . $data[$i]['photo']);
            $data[$i]['width'] = $width;
            $data[$i]['height'] = $height;
            $data[$i]['cover_photo'] = $this->getCoverPhoto($value['id']);
            $data[$i]['title'] = "";
            $data[$i]['type'] = '0';
            $data[$i]['link'] = SITE_URL . $value['url']; //"phots?fwrid=" . $val0['id'] . "&ac=1";
            $arr = explode(",", self::getLikesCount($value['id'], $data[$i]['type']));
            $data[$i]['like'] = $arr[0];
            $data[$i]['dislike'] = $arr[1];
            $data[$i]['commentcnt'] = self::getCommentCount($value['id'], $data[$i]['type']);
            $data[$i]['status'] = $object->getCollectedStatus($value['id'], $data[$i]['type']);
            $data[$i]['source'] = '';
            $data[$i]['comments_for_this_post'] = self::getComments($value['id'], $data[$i]['type']);
            $data[$i]['cdate'] = $value['last_updated'];

            $i++;
        }
        return $data;
    }

    /**
     * getCollectedFanwires
     * @param integer $user_id
     * @return array 
     */
    public function getCollectedFanwires($user_id) {
        $obj = new Users();
        $object = new Fanwires();
        $userid = $_SESSION['id'];
        $this->sql = "select fanwire_id,type from tbl_collections where user_id=? and status='1'";
        $rs = $this->conn->selectSql($this->sql, array($userid));
        $list = array();
        $i = 0;
        foreach ($rs as $val) {
            $fan_id = $val['fanwire_id'];
            $type = $val['type'];
            if ($type == '0') {
                $this->sql0 = "select f.id,f.name,f.last_updated,f.description,a.url,f.url as fanwire from tbl_fanwires f,tbl_avatar_photos a where f.id=? and f.status=1 and f.id=a.fanwire_id and a.main_avator='1'";
                $rs0 = $this->conn->selectSql($this->sql0, array($fan_id));
                foreach ($rs0 as $val0) {
                    $list[$i]['id'] = $val0['id'];
                    $list[$i]['name'] = $val0['name'];
                    $list[$i]['photo'] = $val0['url'];
                    $list[$i]['description'] = $val0['description'];
                    $list[$i]['type'] = '0';
                    $list[$i]['link'] = SITE_URL . $val0['fanwire']; //"phots?fwrid=" . $val0['id'] . "&ac=1";
                    $arr = explode(",", $obj->getLikesCount($val0['id'], $list[$i]['type']));
                    $list[$i]['like'] = $arr[0];
                    $list[$i]['dislike'] = $arr[1];
                    $list[$i]['commentcnt'] = $obj->getCommentCount($val0['id'], $list[$i]['type']);
                    $list[$i]['status'] = $object->getCollectedStatus($val0['id'], $list[$i]['type']);
                    $list[$i]['source'] = '';
                    $list[$i]['comments_for_this_post'] = $obj->getComments($val0['id'], $list[$i]['type']);
                    $list[$i]['cdate'] = $val0['last_updated'];
                    $i++;
                }
            }
            if ($type == '1') {
                $fan_id . $this->sql1 = "select id,cdate,name,album_url,status from tbl_albums where belongsTo='2' and id=?";
                $rs1 = $this->conn->selectSql($this->sql1, array($fan_id));
                foreach ($rs1 as $val1) {
                    $list[$i]['id'] = $val1['id'];
                    $list[$i]['name'] = $val1['name'];
                    $list[$i]['photo'] = $val1['album_url'];
                    $list[$i]['description'] = '';
                    $list[$i]['type'] = '1';
                    $list[$i]['link'] = SITE_URL . "viewAlbum?aid=" . $val1['id'];
                    $arr = explode(",", $obj->getLikesCount($val1['id'], $list[$i]['type']));
                    $list[$i]['like'] = $arr[0];
                    $list[$i]['dislike'] = $arr[1];
                    $list[$i]['commentcnt'] = $obj->getCommentCount($val1['id'], $list[$i]['type']);
                    $list[$i]['status'] = $object->getCollectedStatus($val1['id'], $list[$i]['type']);
                    $list[$i]['source'] = '';
                    $list[$i]['comments_for_this_post'] = $obj->getComments($val1['id'], $list[$i]['type']);
                    $list[$i]['cdate'] = $val1['cdate'];
                    $i++;
                }
            }
            if ($type == '2') {
                $this->sql2 = "select id,cdate,title,description,photo,source from tbl_articles where visible='1' and released_date!='0000-00-00 00:00:00' and  belongsTo='2' and id=?";
                $rs2 = $this->conn->selectSql($this->sql2, array($fan_id));
                foreach ($rs2 as $val2) {
                    $list[$i]['id'] = $val2['id'];
                    $list[$i]['name'] = $val2['title'];
                    $list[$i]['photo'] = $val2['photo'];
                    $list[$i]['description'] = $val2['description'];
                    $list[$i]['link'] = SITE_URL . "viewArticle?aid=" . $val2['id'];
                    $list[$i]['type'] = '2';
                    $arr = explode(",", $obj->getLikesCount($val2['id'], $list[$i]['type']));
                    $list[$i]['like'] = $arr[0];
                    $list[$i]['dislike'] = $arr[1];
                    $list[$i]['commentcnt'] = $obj->getCommentCount($val2['id'], $list[$i]['type']);
                    $list[$i]['status'] = $object->getCollectedStatus($val2['id'], $list[$i]['type']);
                    $list[$i]['source'] = $val2['source'];
                    $list[$i]['comments_for_this_post'] = $obj->getComments($val2['id'], $list[$i]['type']);
                    $list[$i]['cdate'] = $val2['cdate'];
                    $i++;
                }
            }
            if ($type == '3') {
                $this->sql3 = "select id,cdate,title,thumbnail,description from tbl_videos where visible='1' released_date!='0000-00-00 00:00:00' and belongsTo='2' and id=?";
                $rs3 = $this->conn->selectSql($this->sql3, array($fan_id));
                foreach ($rs3 as $val3) {
                    $list[$i]['id'] = $val3['id'];
                    $list[$i]['name'] = $val3['title'];
                    $list[$i]['photo'] = $val3['thumbnail'];
                    $list[$i]['description'] = $val3['description'];
                    $list[$i]['link'] = SITE_URL . "viewVideos?vid=" . $val3['id'];
                    $list[$i]['type'] = '3';
                    $arr = explode(",", $obj->getLikesCount($val3['id'], $list[$i]['type']));
                    $list[$i]['like'] = $arr[0];
                    $list[$i]['dislike'] = $arr[1];
                    $list[$i]['commentcnt'] = $obj->getCommentCount($val3['id'], $list[$i]['type']);
                    $list[$i]['status'] = $object->getCollectedStatus($val3['id'], $list[$i]['type']);
                    $list[$i]['source'] = '';
                    $list[$i]['comments_for_this_post'] = $obj->getComments($val3['id'], $list[$i]['type']);
                    $list[$i]['cdate'] = $val3['cdate'];
                    $i++;
                }
            }
        }

        /**
         * SORT elements by passed value
         */
        function sortByOneKey(array $array, $key, $asc = true) {
            $result = array();

            $values = array();
            foreach ($array as $id => $value) {
                $values[$id] = isset($value[$key]) ? $value[$key] : '';
            }

            if ($asc) {
                asort($values);
            } else {
                arsort($values);
            }
            $i = 0;
            foreach ($values as $key => $value) {
                $result[$i] = $array[$key];
                $i++;
            }

            return $result;
        }

        return $list = sortByOneKey($list, 'cdate', false);
    }

    /**
     * getFansCollections
     * @param integer $ids 
     * @return array
     */
    public function getFansCollections($ids) {//fan is one of user
        $this->sql = "select * from tbl_collections where `user_id` =$ids and status=1";
        $this->rs = $this->conn->executeQuery($this->sql);
        return $this->rs;
    }

    /**
     * getFanwiresNotIn  getting the fanwires who are not fan of current user
     * @param integer $id 
     * @return array $data
     */
    public function getFanwiresNotIn($ids) {
        $obj = new Fanwires();
        $this->sql = "select * from tbl_fanwires where `id` not in($ids) LIMIT 8";
        $this->rs = $this->conn->executeQuery($this->sql);
        $i = 0;
        foreach ($this->rs as $value) {
            $data[$i]['id'] = $value['id'];
            $data[$i]['name'] = $value['name'];
            $data[$i]['url'] = $value['url'];
            $data[$i]['description'] = $value['description'];
            $data[$i]['photo'] = $this->getAvtharPhoto($value['id']);
            $data[$i]['cover_photo'] = $this->getCoverPhoto($value['id']);
            $arr = explode(",", $this->getLikesCount($value['id'], '0'));
            $data[$i]['like'] = $arr[0];
            $data[$i]['dislike'] = $arr[1];
            $category = $obj->getFanwireCategories($value['id']);
            if (sizeof($category) > 0) {
                if ($category[1]['name'] != "")
                    $dd = ' / ' . $category[1]['name'];
                $data[$i]['category'] = $category[0]['name'] . $dd;
            }
            else
                $data[$i]['category'] = "";
            $i++;
        }
        return $data;
    }

    /**
     * getComments getting the comments of fanwires
     * @param integer $id 
     * @return array 
     */
    public function getComments($id, $type) {
        $count = $this->getCommentCount($id, $type);
        $count = 0;
        $this->sql = "select comment_time,userid,comment  from tbl_comments where receiver_id=? AND receiver_type = " . $type . " ORDER BY comment_time ASC LIMIT " . $count . ",5";
        $this->rs = $this->conn->selectSql($this->sql, array($id, $type));
        $i = 0;
        $time = '';
        foreach ($this->rs as $value) {
            $data[$i]['id'] = $value['userid'];
            $data[$i]['comment'] = $value['comment'];
            $d = $this->getUserDetails($value['userid']);
            if ($d[0]['profile_image'] != "")
                $data[$i]['profile_image'] = SITE_URL . "profile_images/" . $d[0]['profile_image'];
            else
                $data[$i]['profile_image'] = SITE_URL . "views/images/logodef.png";
            $data[$i]['name'] = $d[0]['fname'];
            $data[$i]['comment_time'] = $value['comment_time'];
            $time = DatePicker::getTimeStamp($value['comment_time']);
            $data[$i]['stamp'] = $time;
            $i++;
            $time = '';
        }
        return $data;
    }

    /**
     * viewAllComments
     * @param type $receiver
     * @param type $userid
     * @param type $type
     */
    public function viewAllComments($receiver, $userid, $type) {
        $this->sql1 = "select *  from tbl_comments where receiver_id=? AND receiver_type = '" . $type . "' ORDER BY comment_time ASC ";
        $data = array($receiver);
        $this->rs = $this->conn->selectSql($this->sql1, $data) or die(mysql_error());
        $i = 0;
        foreach ($this->rs as $value) {
            $d = $this->getUserDetails($value['userid']); //get the user details
            $data1[$i]['id'] = $value['userid'];
            $data1[$i]['cmtid'] = $value['id'];
            $data1[$i]['comment'] = $value['comment'];
            if ($d[0]['profile_image'] != "")
                $data1[$i]['profile_image'] = SITE_URL . "profile_images/" . $d[0]['profile_image'];
            else
                $data1[$i]['profile_image'] = SITE_URL . "views/images/logodef.png";

            $data1[$i]['name'] = $d[0]['fname'];
            $time = DatePicker::getTimeStamp($value['comment_time']); //get the time stamps 
            $data1[$i]['comment_time'] = $time;
            $i++;
        }
        return $data1;
    }

    /**
     * getLatestSixComments
     * @param integer $id 
     * @param integer $type 
     * @return array $data
     */
    public function getLatestSixComments($id, $type) {
        $this->sql = "select * from tbl_comments where receiver_id=? and receiver_type=? ORDER BY  `id` DESC LIMIT 0, 1";
        $this->rs = $this->conn->selectSql($this->sql, array($id, $type));
        $i = 0;
        foreach ($this->rs as $value) {
            $data[$i]['id'] = $value['userid'];
            $data[$i]['comment'] = $value['comment'];
            $d = $this->getUserDetails($value['userid']);
            $data[$i]['profile_image'] = $d[0]['profile_image'];
            $data[$i]['name'] = $d[0]['fname'];
            $data[$i]['comment_time'] = $value['comment_time']; //get the time of the comment
            $i++;
        }
        return $data;
    }

    /**
     * getuserPhoto
     * @param integer $uid 
     * @return string returns the profile omage
     */
    function getUserPhoto($uid) {
        $this->sql = "SELECT profile_image from tbl_users WHERE id =?";
        $this->rs = $this->conn->selectSql($this->sql, array($uid));
        return $this->rs[0]['profile_image'];
    }

    /**
     * checkAreTheyFan checking the user is fan of the current session or not
     * @param integer $id 
     */
    function checkAreTheyFan($id) {
        $this->sql = "SELECT * from tbl_fanwire_user WHERE user_id =? and fanid=? and fan_status='1'";
        $this->rs = $this->conn->selectSql($this->sql, array($_SESSION['id'], $id));
        if ($this->rs) {
            return 'yes';
        } else {
            return 'no';
        }
    }

    /**
     * getCountOfFansOfFanwire  get the no of fans of a particular fanwire
     * @param integer $fanwireid 
     * @return integer count of fans
     */
    function getCountOfFansOfFanwire($fanwireid) {

        $this->sql = "SELECT count(*) as number from tbl_fanwire_user WHERE fanid =?  and fan_status='1'";
        $this->rs = $this->conn->selectSql($this->sql, array($fanwireid));

        return $this->rs[0]['number'];
    }

    /**
     * updateFacebookTimelinePhotoUser update user facebook timeline photos
     * @param integer $userid 
     * @param string $cover_filename
     * @return object 
     */
    public function updateFacebookTimelinePhotoUser($cover_filename, $userid) {

        $this->sql = "update tbl_users set cover_photo = ? where id = ? ";
        $rs = $this->conn->executePrepared($this->sql, array($cover_filename, $userid));

        if ($rs == '1') {
            return $cover_filename;
        } else {
            return '';
        }
    }

    /**
     * filterValues
     * @param integer $id 
     * @param object $params 
     * @return array 
     */
    public function filterValues($id, $params) {


        if (!isset($params['facebook']) && !isset($params['twitter']) && !isset($params['photo']) && !isset($params['article']) && !isset($params['video']) && !isset($params['instagram'])) {
            $this->sql = "select twitter,facebook,article,photo,video,instagram from  tbl_filters where user_id=? and page=?";
            $rs = $this->conn->selectSql($this->sql, array($id, $params['path']));
            if (empty($rs)) {
                $params['facebook'] = '1';
                $params['twitter'] = '1';
                $params['photo'] = '1';
                $params['video'] = '1';
                $params['article'] = '1';
                $params['instagram'] = '1';
            } else {
                $params['facebook'] = $rs[0]['facebook'];
                $params['twitter'] = $rs[0]['twitter'];
                $params['photo'] = $rs[0]['photo'];
                $params['video'] = $rs[0]['video'];
                $params['article'] = $rs[0]['article'];
                $params['instagram'] = $rs[0]['instagram'];
            }
        } else {
            $this->sql = "select twitter,facebook,article,photo,video,instagram from  tbl_filters where user_id=? and page=?";
            $rs = $this->conn->selectSql($this->sql, array($id, $params['path']));
            if ($params['facebook'] == '')
                $params['facebook'] = '1';
            if ($params['twitter'] == '')
                $params['twitter'] = '1';
            if ($params['photo'] == '')
                $params['photo'] = '1';
            if ($params['video'] == '')
                $params['video'] = '1';
            if ($params['article'] == '')
                $params['article'] = '1';
            if ($params['instagram'] == '')
                $params['instagram'] = '1';
        }

        if (empty($rs)) {

            $this->sql1 = "insert into tbl_filters(page,user_id,facebook,twitter,photo,video,article,instagram) values(?,?,?,?,?,?,?,?)";
            $data = array($params['path'], $id, $params['facebook'], $params['twitter'], $params['photo'], $params['video'], $params['article'], $params['instagram']);
            $tt = $this->conn->executePrepared($this->sql1, $data);
        } else {

            $this->sql2 = "update tbl_filters set `facebook`='" . trim($params['facebook']) . "',`twitter`='" . trim($params['twitter']) . "',`video`='" . trim($params['video']) . "',`article`='" . trim($params['article']) . "',`photo`='" . trim($params['photo']) . "',`instagram`='" . trim($params['instagram']) . "' where user_id=? and page=?";
            $data = array($id, $params['path']);
            $this->conn->executePrepared($this->sql2, $data);
        }
        $this->sql = "select twitter,facebook,article,photo,video,instagram from  tbl_filters where user_id=? and page=?";
        $rs = $this->conn->selectSql($this->sql, array($id, $params['path']));

        return $rs[0];
    }

    /**
     * createUser
     * @param array $params array consist of varius details like name,email,usertype,cdate
     */
    public function createUser($params) {
        extract($params);
        $this->sql = "INSERT INTO tbl_users(fname,lname,email,password,usertype,cdate)values(?,?,?,?,?,?)";
        $this->data[0] = $firstname;
        $this->data[1] = $lastname;
        $this->data[2] = $email;
        $this->data[3] = md5($password);
        $this->data[4] = 3;
        $this->data[5] = date("Y-m-d H:i:s");
        $this->conn->executePrepared($this->sql, $this->data) or die(mysql_error());
    }

    public function chagneUserType($id, $type) {
        $this->sql = "update tbl_users set usertype = ? where id = ? ";
        $this->conn->executePrepared($this->sql, array($type, $id));
    }

    public function updateEditorProfile($params) {
        extract($params);
        if ($password != "") {
            $this->sql = "UPDATE `tbl_users` SET `fname` =?,lname=?,email=?,password=?,usertype=? where id=? ";
            $data = array($firstname, $lastname, $email, md5($password), $usertype, $id);
        } else {
            $this->sql = "UPDATE `tbl_users` SET `fname` =?,lname=?,email=?,usertype=? where id=? ";
            $data = array($firstname, $lastname, $email, $usertype, $id);
        }
        return $this->conn->executePrepared($this->sql, $data);
    }

}

