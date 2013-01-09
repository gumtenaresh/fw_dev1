<?php

/**
 * Description of Photos
 * @author naresh
 */
class Photos {

    private $conn = null;
    private $sql = null;
    private $rs = null;
    private $data = array();

    /**
     * creating dao object
     */
    function __construct() {
        $this->conn = Dao::getInstance();
        $this->conn1 = Dao1::getInstance();
    }

//destroying memory
    function __destruct() {
        unset($this->conn);
        unset($this->conn1);
        unset($this->rs);
        unset($this->sql);
        unset($this->data);
    }

    /**
     * gets the album details 
     * @param variable $aid
     * @return array
     */
    public function getAlbumDetails($aid) {

        $this->sql = "SELECT p.id,p.name as image_url,p.original_img_path,a.id as album_id,a.name,p.caption,a.pid,a.belongsTo,a.cdate,a.source,a.album_url FROM  tbl_photos p,tbl_albums a where p.album_id=a.id and a.id=?";
        return $this->rs = $this->conn->selectSql($this->sql, array($aid));
    }

    /**
     * gets one album details 
     * @param variable $aid
     * @return array
     */
    public function getThisAlbumDetails($aid) {
        $objUser = new Users();
        $obj = new Fanwires();
        $this->sql = "SELECT *  FROM tbl_albums  where id=?";
        $this->rs = $this->conn->selectSql($this->sql, array($aid));
        foreach ($this->rs as $val) {

            $this->data ['fanwire_id'] = $val['pid'];
            $this->data['album_id'] = $val['id'];
            $this->data['title'] = ($val['name']);
            $this->data['link'] = ($val['link']);
            $this->data['photo'] = $val['album_url'];
            $this->data['heightwidth'] = YoutubeUrl::thumbnail_sizefix($val['album_url'], 381, 441);
            $this->data['date'] = DatePicker::getTimeStamp($val['cdate']);
            $this->data['metadate'] = date("m/d/y", strtotime($val['cdate']));
            $this->data['source'] = $val['source'];
            $arr = explode(",", $objUser->getLikesCount($val['id'], PHOTO_TYPE));
            $this->data['like'] = $arr[0];
            $this->data['dislike'] = $arr[1];
            //$this->data['artstatus'] = $obj->getCollectedStatus($aid, PHOTO_TYPE);
        }
        //print_r($this->data);
        return $this->data;
    }

    /**
     * getalbum names 
     * @param variables id and whomeitsbelongsto
     * @return array
     */
    public function getAlbums($uid, $belongsTo) {

        $this->sql = "select a.id,a.name,a.status,a.pid,a.album_url, count(p.album_id) AS photoscnt from tbl_albums a left join tbl_photos p on a.id=p.album_id where a.pid=? and a.belongsTo=?  group by a.id limit 0,6";

        return $this->rs = $this->conn->selectSql($this->sql, array($uid, $belongsTo));
    }

    /**
     * getMedia files like photos,articles,videos
     * @param variable $uid,$belongsTo
     * @return  array
     */
    public function getMedia($uid, $belongsTo) {
        $obj = new Users();
        $object = new Fanwires();
        $userid = $_SESSION['id'];
        $list = array();
        $fan_id = $uid;
        $this->sql1 = "select a.id,a.name,a.status,a.pid,a.album_url, count(p.album_id) AS photoscnt,a.cdate 
            from tbl_albums a left join tbl_photos p on a.id=p.album_id and a.visible='1' 
            where a.pid=? and a.belongsTo=$belongsTo and a.released_date<=NOW() group by a.id";
        $rs1 = $this->conn->selectSql($this->sql1, array($fan_id));
        foreach ($rs1 as $val1) {
            $list[$i]['id'] = $val1['id'];
            $list[$i]['title'] = $val1['name'];
            $list[$i]['name'] = $object->getFanwireName($val1['pid']);
            $list[$i]['photo'] = $val1['album_url'];
            list($width, $height) = getimagesize(DOC_ROOT_PATH . "/photos/" . $val1['album_url']);
            $list[$i]['width'] = $width;
            $list[$i]['height'] = $height;
            $list[$i]['description'] = '';
            $list[$i]['title_link'] = SITE_URL . $object->getFanwireUrl($val1['pid']) . "/Gallery/" . str_replace(" ", "-", str_replace("'", "", $val1['name'])); //"viewAlbum?aid=" . $val1['id'];
            $list[$i]['link'] = SITE_URL . $object->getFanwireUrl($val1['pid']); //"phots?fwrid=" . $val1['pid'] . "&ac=1";
            $arr = explode(",", $obj->getLikesCount($val1['id'], '1'));
            $list[$i]['like'] = $arr[0];
            $list[$i]['dislike'] = $arr[1];
            $list[$i]['commentcnt'] = $obj->getCommentCount($val1['id'], '1');
            $list[$i]['status'] = $object->getCollectedStatus($val1['id'], '1');
            $list[$i]['type'] = '1';
            $list[$i]['source'] = '';
            $list[$i]['comments_for_this_post'] = $obj->getComments($val1['id'], '1');
            $list[$i]['cdate'] = DatePicker::getTimeStamp($val1['cdate']);
            $list[$i]['photoscnt'] = $val1['photoscnt'];

            $i++;
        }

        $this->sql2 = "select id,article_from,cdate,title,user_id,description,photo,source from tbl_articles where released_date!='0000-00-00 00:00:00' and  belongsTo='" . $belongsTo . "' and user_id=? and source in('0') and visible=1 and released_date<=NOW()";
        $rs2 = $this->conn->selectSql($this->sql2, array($fan_id));
        foreach ($rs2 as $val2) {
            $list[$i]['id'] = $val2['id'];
            $list[$i]['title'] = $val2['title'];
            $list[$i]['name'] = $object->getFanwireName($val2['user_id']);
            $list[$i]['photo'] = $val2['photo'];
            if ($val2['photo'] == "") {
                $list[$i]['fanwire_profile_img'] = $object->getFanwireProfileImage($val2['user_id']);
            }
            list($width, $height) = getimagesize(DOC_ROOT_PATH . "/photos/" . $val2['photo']);
            $list[$i]['width'] = $width;
            $list[$i]['height'] = $height;

            $list[$i]['title_link'] = SITE_URL . $object->getFanwireUrl($val2['user_id']) . "/Articles/" . str_replace(" ", "-", str_replace("'", "", $val2['title'])); //"viewArticle?aid=" . $val2['id'];
            $list[$i]['link'] = SITE_URL . $object->getFanwireUrl($val2['user_id']); //"phots?fwrid=" . $val2['user_id'] . "&ac=1";
            $arr = explode(",", $obj->getLikesCount($val2['id'], '2'));
            $list[$i]['like'] = $arr[0];
            $list[$i]['dislike'] = $arr[1];
            $list[$i]['commentcnt'] = $obj->getCommentCount($val2['id'], '2');
            $list[$i]['status'] = $object->getCollectedStatus($val2['id'], '2');
            $list[$i]['type'] = '2';
            $list[$i]['source'] = $val2['source'];
            if ($val2['source'] == 0)
                $list[$i]['description'] = $val2['description'];
            else
                $list[$i]['description'] = YoutubeUrl::replace_plain_text_link(strtolower($val2['description']));
            if ($list[$i]['article_from'] == "Twitter") {
                $list[$i]['description'] = preg_replace('/(#\w+)/', '<span class=\'twitcolor\'>\1</span>', $list[$i]['description']);
            }
            $list[$i]['comments_for_this_post'] = $obj->getComments($val2['id'], '2');
            $list[$i]['cdate'] = DatePicker::getTimeStamp($val2['cdate']);
            $name = $obj->getFanwireDetails($fan_id);
            $list[$i]['fanwirename'] = $name['name'];
            $list[$i]['fanwireid'] = $name['id'];
            $i++;
        }

        $this->sql3 = "select id,cdate,title,thumbnail,description,whomItBelongsTo from tbl_videos where released_date!='0000-00-00 00:00:00' and belongsTo='" . $belongsTo . "' and whomItBelongsTo=? and visible=1 and released_date<=NOW()";
        $rs3 = $this->conn->selectSql($this->sql3, array($fan_id));
        foreach ($rs3 as $val3) {
            $list[$i]['id'] = $val3['id'];
            $list[$i]['title'] = $val3['title'];
            $list[$i]['name'] = $object->getFanwireName($val3['whomItBelongsTo']);
            $list[$i]['photo'] = $val3['thumbnail'];
            list($width, $height) = getimagesize(DOC_ROOT_PATH . "/photos/" . $val3['thumbnail']);
            $list[$i]['width'] = $width;
            $list[$i]['height'] = $height;
            $list[$i]['description'] = $val3['description'];
            $list[$i]['title_link'] = SITE_URL . $object->getFanwireUrl($val3['whomItBelongsTo']) . "/Videos/" . str_replace(" ", "-", str_replace("'", "", $val3['title']));
            $list[$i]['link'] = SITE_URL . $object->getFanwireUrl($val3['whomItBelongsTo']); //"phots?fwrid=" . $val2['user_id'] . "&ac=1";
            $arr = explode(",", $obj->getLikesCount($val3['id'], '3'));
            $list[$i]['like'] = $arr[0];
            $list[$i]['dislike'] = $arr[1];
            $list[$i]['commentcnt'] = $obj->getCommentCount($val3['id'], '3');
            $list[$i]['status'] = $object->getCollectedStatus($val3['id'], '3');
            $list[$i]['type'] = '3';
            $list[$i]['source'] = '';
            $list[$i]['comments_for_this_post'] = $obj->getComments($val3['id'], '3');
            $list[$i]['cdate'] = DatePicker::getTimeStamp($val3['cdate']);
            $i++;
        }

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

        return $list = sortByOneKey($list, 'cdate', true);
    }

    /**
     * Insert photos 
     * @param array $params This array consists of various data like name,fanwirename,coverimage.
     * @param array $dir_array This array consists the names of the photos
     */
    public function insertPhotos($params, $dir_array) {
        extract($params);
        $status = '1';

        $this->sql = "insert into tbl_albums(`name`,`album_url`,`pid`,`status`,`belongsTo`,`released_date`,description) values(?,?,?,?,?,?,?)";
        $data = array($name, $cover_image, $fanwireName, $status, '2', date("Y-m-d H:i:s", strtotime($released)), $description);
        $this->conn->executePrepared($this->sql, $data) or die(mysql_error());
        $albumid = $this->conn->getInsertId();
        foreach ($dir_array as $val) {
            $p = explode("_", $val);
            $q = explode('.', $p[1]);
            $this->sql = "insert into tbl_photos(`name`,userid,album_id,status,caption) values(?,?,?,?,?)";
            $data = array($val, $_SESSION['adminid'], $albumid, $status, $q[0]);
            $this->conn->executePrepared($this->sql, $data) or die(mysql_error());
        }

        if (isset($keyword)) {
            $keys = explode(",", $keyword);
            foreach ($keys as $val) {
                $this->sql = "insert into tbl_keywords(`keyword`,`referer_id`,`type`) values(?,?,?)";
                $data = array($val, $albumid, 1);
                $this->conn->executePrepared($this->sql, $data) or die(mysql_error());
            }
        }
        if (isset($fanwire)) {
            $fans = explode(",", $fanwire);
            foreach ($fans as $value) {
                $fanwire_id = $this->getFanwireId($value);
                if (isset($fanwire_id)) {
                    $this->sql = "insert into tbl_additional_fanwires(`fanwire_id`,`content_id`,`content_type`) values(?,?,?)";
                    $data = array($fanwire_id, $albumid, '0');
                    $this->conn->executePrepared($this->sql, $data) or die(mysql_error());
                }
            }
        }
    }

    /**
     * getFanwires
     * @return array returns the all related data of fanwires
     */
    public function getFanwires() {

        $this->sql = "SELECT id,name,url,facebook,twitter,youtube from tbl_fanwires where status=1 order by name";
        return $this->rs = $this->conn->selectSql($this->sql, array());
    }

    /**
     * getFanwires_network
     * @param object $val the network name (eg:facebook,twitter)
     * @return array the fanwires name realted to netwoks
     */
    public function getFanwires_network($val) {
        $this->sql = "SELECT a.id,a.name,a.url,a.facebook,a.twitter,a.youtube,i.last_crawl,i.crawl_source from tbl_fanwires a left join tbl_crawl_history i on a.id=i.crawl_id and i.crawl_source='$val' where a.status=1     order by name";
        return $this->rs = $this->conn->selectSql($this->sql, array());
    }

    /**
     * getFanwireId
     * @param object $name name of the fanwire to get the id of the fanwrie
     * @return object id of the fanwire
     */
    public function getFanwireId($name) {
        $this->sql = "SELECT id from tbl_fanwires where name=?";
        $this->rs = $this->conn->selectSql($this->sql, array($name));
        return $this->rs[0]['id'];
    }

    /**
     * getLibrary
     * @param object $id id of the fanwire
     * @return array returns the data of the fanwire
     */
    public function getLibrary($id) {
        $this->sql = "SELECT id,url FROM  tbl_avatar_photos  where fanwire_id = ?";
        return $this->rs = $this->conn->selectSql($this->sql, array($id));

        $i = 0;
        foreach ($this->rs as $value) {
            $data[$i]['id'] = $value['id'];
            $data[$i]['url'] = $value['url'];
            list($width, $height) = getimagesize(IMAGE_URL . $data[$i]['url']);
            $data[$i]['width'] = 143;
            $data[$i]['height'] = floor(($height / $width) * 143);
            $i++;
        }
        return $data;
    }

    /**
     * getCoverImages
     * @param object  $id id of the fanwrie
     * @return array returns an array with id and url info of the fanwire
     */
    public function getCoverImages($id) {
        $this->sql = "SELECT id,url FROM  tbl_timeline_photos  where fanwire_id = ?";
        return $this->rs = $this->conn->selectSql($this->sql, array($id));
    }

    /**
     * removePhotos
     * @param object  $id id of the fanwrie and album id
     * @return boolean returns boolean value
     */
    public function removePhotos($id, $fanwireId) {
        $this->sql = "delete FROM tbl_avatar_photos where id=? and fanwire_id=?";
        return $this->rs = $this->conn->executePrepared($this->sql, array($id, $fanwireId));
    }

    /**
     * removeCoverPhotos
     * @param object  $id id of the fanwrie and album id
     * @return boolean returns boolean value
     * 
     */
    public function removeCoverPhotos($id, $fanwireId) {
        $this->sql = "delete FROM tbl_timeline_photos where id=? and fanwire_id=?";
        return $this->rs = $this->conn->executePrepared($this->sql, array($id, $fanwireId));
    }

    /**
     * setPhotos
     * @param object  $id id of the fanwrie and album id
     * @return boolean returns boolean value
     * 
     */
    public function setPhotos($id, $fanwireId) {
        $this->sql = "update tbl_avatar_photos set main_avator = ? where id=? and fanwire_id=?";
        $this->rs = $this->conn->executePrepared($this->sql, array('1', $id, $fanwireId));
        $this->sql = "update tbl_avatar_photos set main_avator = ? where  id != ? and fanwire_id = ?";
        return $rs = $this->conn->executePrepared($this->sql, array('0', $id, $fanwireId));
    }

    /**
     * setCoverPhotos
     * @param object  $id id of the fanwrie and album id
     * @return boolean returns boolean value
     * 
     */
    public function setCoverPhotos($id, $fanwireId) {
        $this->sql = "update tbl_timeline_photos set main_timeline = ? where id=? and fanwire_id=?";
        $this->rs = $this->conn->executePrepared($this->sql, array('1', $id, $fanwireId));
        $this->sql = "update tbl_timeline_photos set main_timeline = ? where  id != ? and fanwire_id = ?";
        return $rs = $this->conn->executePrepared($this->sql, array('0', $id, $fanwireId));
    }

    /**
     * getRelatedAlbumnames
     * @param  object$string 
     * @return array 
     * 
     */
    function getRelatedAlbumsNames($string) {
        $objectFanwires = new Fanwires();
        if ($_SESSION['id']) {
            $limit = 6;
        } else {
            $limit = 10;
        }
        $this->sql = "SELECT DISTINCT id,name,album_url,pid from tbl_albums where `name` NOT LIKE '$string' ORDER BY RAND() ASC LIMIT 0,$limit  "; // and `type_source`='0'
        $this->rs = $this->conn->executeQuery($this->sql);
        $i = 0;
        foreach ($this->rs as $value) {
            $data[$i]['id'] = $value['id'];
            $data[$i]['title'] = $value['name'];
            $data[$i]['photo'] = $value['album_url'];
            if ($value['name'] == '') {
                $title = $value['id'];
                $data[$i]['title_link'] = SITE_URL . $objectFanwires->getFanwireUrl($value['pid']) . "/Instagram/" . $title;
            } else {
                $data[$i]['title_link'] = SITE_URL . $objectFanwires->getFanwireUrl($value['pid']) . "/Gallery/" . YoutubeUrl::fixurl($value['name']);
            }
            $data[$i]['fanwirename'] = $objectFanwires->getFanwireName($value['pid']);

            list($width, $height) = getimagesize(IMAGE_URL . $data[$i]['photo']);


            $data[$i]['width'] = 40;
            $data[$i]['height'] = floor(($height / $width) * 40);
            $i++;
        }
        return $data;
    }

    /**
     * getPhotosList
     * @param  object $fanwire_id the fanwire id photos are belongs to
     * @param object $pageSize page size for pagination
     * @return array 
     * 
     */
    public function getPhotosList($fanwire_id, $pageSize = 100) {
        $this->sql = "SELECT * from tbl_albums where belongsTo=2 and pid=? order by id desc";
        $pageObject = new Pagination($pageSize, 9); //params: pagesize, scroll size
        $navigation = $pageObject->showNavigation(sizeof($this->conn->selectSql($this->sql, array($fanwire_id))));
        $this->sql .= $pageObject->getLimitQry();
        $this->rs = $this->conn->selectSql($this->sql, array($fanwire_id));

        $i = 0;
        $obj = new Fanwires();
        foreach ($this->rs as $val) {
            $this->data[$i]['id'] = $val['id'];
            $this->data[$i]['title'] = $val['name'];
            $this->data[$i]['photo'] = $val['album_url'];
            $this->data[$i]['fanwire'] = $obj->getFanwireName($val['pid']);
            $this->data[$i]['cdate'] = $val['cdate'];
            $this->data[$i]['released_date'] = $val['released_date'];
            $i++;
        }
        $this->data['list'] = $this->data;
        $this->data['navigation'] = $navigation;
        return $this->data;
    }

    /**
     * updateDate
     * @param  object $params
     * @return boolean 
     * 
     */
    function updateDate($params, $cdate1) {
        extract($params);
        $this->sql = "update tbl_albums set cdate=? where id=?";
        //$data = array(date("Y-m-d H:i:s", strtotime($cdate)), $photo_id);
        $data = array($cdate1, $photo_id);
        $this->conn->executePrepared($this->sql, $data);
    }

    /**
     * updateReleaseDate
     * @param  object $params
     * @return boolean 
     * 
     */
    function updateReleasedDate($params, $dt_release) {
        extract($params);
        $this->sql = "update tbl_albums set released_date=? where id=?";
        //$data = array(date("Y-m-d H:i:s", strtotime($released_date)), $photo_id);
        $data = array($dt_release, $photo_id);
        $this->conn->executePrepared($this->sql, $data);
    }

    /**
     * deletePhotos
     * @param integer $id id of the album
     * 
     */
    public function deletePhotos($id) {
        $this->sql = "delete from tbl_albums where id=?";
        $data = array($id);
        $this->conn->executePrepared($this->sql, $data);

        $this->sql = "delete from tbl_photos where album_id=?";
        $data = array($id);
        $this->conn->executePrepared($this->sql, $data);
    }

    /**
     * deleteImage
     * @param integer $id id of the album
     * 
     */
    public function deleteImage($id) {
        $this->sql = "delete from tbl_photos where id=?";
        $data = array($id);
        $this->conn->executePrepared($this->sql, $data);
    }

    /**
     * getAlbum
     * @param integer $aid albumid
     * @return array list of photos
     */
    public function getAlbum($aid) {
        $this->sql = "select * from tbl_albums where id=?";
        $this->rs = $this->conn->selectSql($this->sql, array($aid));
        $i = 0;
        $obj = new Fanwires();
        foreach ($this->rs as $value) {
            $data[$i]['id'] = $value['id'];
            $data[$i]['name'] = $value['name'];
            $data[$i]['description'] = $value['description'];
            $data[$i]['album_url'] = $value['album_url'];
            $data[$i]['released_date'] = $value['released_date'];
            $data[$i]['keywords'] = $obj->getKeywords($value['id'], '1');
            $data[$i]['additional_fans'] = $obj->getAdditionalFans($value['id'], '0');
            $i++;
        }
        return $data;
    }

    /**
     * editPhotos
     * @param object $params albumid
     * @return array list of photos
     */
    public function editPhotos($params, $photos) {
        extract($params);
        $this->sql = "update tbl_albums set name=?,description=?,released_date=? where id=?";
        $data = array($name, $description, date("Y-m-d H:i:s", strtotime($released_date)), $photo_id);
        $this->conn->executePrepared($this->sql, $data);
        $obj = new Fanwires();
        foreach ($photos as $val) {
            $p = explode("_", $val);
            $q = explode('.', $p[1]);
            $this->sql = "insert into tbl_photos(`name`,userid,album_id,status,caption) values(?,?,?,?,?)";
            $data = array($val, $_SESSION['adminid'], $photo_id, '1', $q[0]);
            $this->conn->executePrepared($this->sql, $data);
        }
        if (isset($keyword) && $keyword != "") {
            $keys = explode(",", $keyword);
            foreach ($keys as $val) {
                $this->sql = "insert into tbl_keywords(`keyword`,`referer_id`,`type`) values(?,?,?)";
                $data = array($val, $photo_id, '1');
                $this->conn->executePrepared($this->sql, $data) or die(mysql_error());
            }
        }

        if (isset($fanwire) && $fanwire != "") {
            $fans = explode(",", $fanwire);
            foreach ($fans as $value) {
                $fan_id = $obj->getFanwireIdByName($value);
                $this->sql = "insert into tbl_additional_fanwires(`fanwire_id`,`content_id`,`content_type`) values(?,?,?)";
                $data = array($fan_id, $photo_id, '0');
                $this->conn->executePrepared($this->sql, $data) or die(mysql_error());
            }
        }
    }

    /**
     * getwebSource
     * @param object $id albumid
     * @return array 
     */
    public function getwebSource($id) {
        $this->sql = "SELECT * from tbl_www_article_source order by id";
        return $this->rs = $this->conn->selectSql($this->sql, array());
    }

    /**
     * getFanSites
     * @param object $id
     * @return array 
     */
    public function getFanSites($id) {
        if ($id == "")
            $this->sql = "SELECT * from tbl_www_article_source where status=0 order by id";
        else
            $this->sql = "SELECT * from tbl_www_article_source where id=$id and  status=0 order by id";

        $this->rs = $this->conn->selectSql($this->sql, array());
        if ($id == "")
            return $this->rs;
        else
            return $this->rs[0];
    }

    /**
     * getSourceClasselements
     * @param object $id
     * @return array 
     */
    public function getSourceClasselements($id) {
        $this->sql = "SELECT ta.*,ts.source websitename,ts.search_seperator,ts.search_url from tbl_www_articles ta,tbl_www_article_source ts where ta.id=$id and ta.source=ts.id";
        $this->rs = $this->conn->selectSql($this->sql, array());
        return $this->rs[0];
    }

    /**
     * getArticleTypes
     * @return array 
     */
    public function getArticleTypes() {
        $this->sql = "SELECT * from tbl_www_article_type";
        return $this->rs = $this->conn->selectSql($this->sql, array());
    }

    /**
     * getMainClass
     * @param integer $article_id article id
     * @param integer $type_id 
     * @return array 
     */
    public function getMainClass($article_id, $type_id) {
        $this->sql = "SELECT * from tbl_www_article_type_classes where article_source =$article_id and article_type=$type_id";
        $this->rs = $this->conn->selectSql($this->sql, array());
        return $this->rs[0];
    }

    /**
     * getclassesInfo
     * @param integer $source_id  

     * @return array 
     */
    public function getclassesInfo($source_id) {
        if ($source_id != "") {
            $this->sql = "SELECT *,ts.source,ts.id from tbl_www_article_source ts join tbl_www_articles ta join tbl_www_article_type_classes tatc on ta.id=$source_id";
            $this->rs = $this->conn->selectSql($this->sql, array());
            return $this->rs[0];
        } else {
            $this->sql = "SELECT *,ts.source,ts.id as source_id from tbl_www_article_source ts join tbl_www_articles ta on ta.source=ts.id";
            $this->rs = $this->conn->selectSql($this->sql, array());
            return $this->rs;
        }
        //   exit;
    }

    /**
     * getclasses
     * @param integer $article_id  
     * @param integer $type_id 
     * @return array 
     */
    public function getclasses($article_id, $type_id) {

        $this->sql = "SELECT *,ts.source,ts.id from tbl_www_article_source ts left join tbl_www_articles ta on ts.id=ta.source left join tbl_www_article_type_classes tatc on tatc.article_source=ts.id";
        return $this->rs = $this->conn->selectSql($this->sql, array());
    }

    /**
     * @name insertInstaPhotos
     * @params list array
     * @return type array
     */
    public function insertInstaPhotos($list) {
        $specialChars = array("@", "%", "|", ",", "'", "\"", "#", "!", "*", "&", "(", ")", "?", "^", "`", "~", "<", ">", "+", ";");
        foreach ($list as $val) {
            if (empty($val['caption'])) {
                $val['caption'] = ""; // $val['albumname'] . " Instagram on " . strtotime(date("Y-m-d H:i:s")).rand(1,8000);
            } else {
                $val['caption'] = $val['caption'];
            }
            $this->sql = "SELECT count(*) as cnt from tbl_albums where album_url=?";
            $this->rs = $this->conn->selectSql($this->sql, array($val['src']));
            if ($this->rs[0]['cnt'] == 0) {
                $this->sql = "insert into tbl_albums(`name`,`album_url`,`pid`,`status`,`belongsTo`,description,`cdate`,`source`,`link`) values(?,?,?,?,?,?,?,?,?)";
                $data = array(str_replace($specialChars, "", $val['caption']), $val['src'], $val['fanwireid'], '1', '2', str_replace($specialChars, "", $val['caption']), $val['datetime'], 'Instagram', $val['link']);
                $this->conn->executePrepared($this->sql, $data);
                $this->conn1->executePrepared($this->sql, $data);
            }
        }
    }

    /**
     * @name CheckInstaPhotos
     * @params list array
     * @return type array
     */
    public function checkInstaPhotos($list) {
        $list = implode("','", $list);
        $this->sql = "SELECT * from tbl_albums where album_url in('" . $list . "')";
        return $this->rs = $this->conn->selectSql($this->sql, array());
    }

    /**
     * getFanwiresInstagram
     * @return array the fanwires name realted to network
     */
    public function getFanwiresInstagram() {
        $this->sql = "SELECT * FROM `tbl_fanwires` where `instagram` <> '' order by name";
        return $this->rs = $this->conn->selectSql($this->sql, array());
    }

    public function GetArticle($id) {
        $this->sql = "select ta.*,ts.*,ts.id as id FROM tbl_www_article_source ts, tbl_www_articles ta where ts.id=? and ts.id=ta.source";
        $this->rs = $this->conn->selectSql($this->sql, array($id));
        return $this->rs[0];
    }

    public function DeleteCrawlArticle($id) {
        //  echo 'in';
        $this->sql_a = "delete FROM tbl_www_articles where source=?";
        $this->rs = $this->conn->executePrepared($this->sql_a, array($id));

        $this->sql = "delete FROM tbl_www_article_source where id=?";
        return $this->rs = $this->conn->executePrepared($this->sql, array($id));
    }

    public function UpdateArticles($data) {
        /* echo'<pre>';
          print_r($data);
          echo'</pre>'; */
        $this->sql_u = "UPDATE `tbl_www_article_source` SET `source` = ?,`search_url` = ?, `search_seperator` = ?,`status` =?,`description`=? WHERE `tbl_www_article_source`.`id` = ?;";
        $this->rs_u = $this->conn->executePrepared($this->sql_u, array($data['websitename'], $data['search_url'], $data['search_seperator'], $data['status'], $data['description'], $data['Wid'])) or die(mysql_error());

        $this->sql_uA = "UPDATE `tbl_www_articles` SET `search_list_repeat_element` = ?,
            `searchlist_repeat_element_class` = ?,
            `searchlist_title_class` = ?,
            `searchlist_author_class` = ?,
            `search_list_date_element` = ?,
            `search_list_date_class` = ?,
            `search_list_date_class_type` = ?,
            `searchlist_image_class` = ?,
            `searchlist_image_class_element` = ?,
            `searchlist_image_class_type` = ?,
            `description_div_class` = ?,
            `description_content_class_type` = ?,
            `description_title_class` = ?,
            `description_author_class` = ?,
            `description_date_class` = ?,
            `description_image_class` = ?, `description_image_class_type` = ?, `description_content_class` = ?, `description_div_class_element` = ? WHERE `tbl_www_articles`.`source` = ?;";

        $this->rs_ua = $this->conn->executePrepared($this->sql_uA, array($data['search_list_repeat_element'],
            $data['searchlist_repeat_element_class'],
            $data['searchlist_title_class'],
            $data['searchlist_author_class'],
            $data['search_list_date_element'],
            $data['search_list_date_class'],
            $data['search_list_date_class_type'],
            $data['searchlist_image_class'],
            $data['searchlist_image_class_element'],
            $data['searchlist_image_class_type'],
            $data['description_div_class'],
            $data['description_content_class_type'],
            $data['description_title_class'],
            $data['description_author_class'],
            $data['description_date_class'],
            $data['description_image_class'], $data['description_image_class_type'], $data['description_content_class'], $data['description_div_class_element'], $data['Wid']));
        return $this->rs_ua;
    }

    public function insertCrawlArticle($data) {
        $this->sql = "INSERT INTO  tbl_www_article_source (`id`, `source`, `search_url`, `search_seperator`, `status`,`description`) VALUES (NULL,?,?,?,?,?);";
        $data_source = array($data['websitename'], $data['search_url'], $data['search_seperator'], $data['description'], $data['status']);
        $this->conn->executePrepared($this->sql, $data_source) or die(mysql_error());
        $source_id = $this->conn->getInsertId();
        $this->sql_articles = " INSERT INTO `tbl_www_articles` (`id`, `source`, `search_list_repeat_element`, `searchlist_repeat_element_class`, `searchlist_title_class`, `searchlist_title_headtag`, `searchlist_author_class`, `search_list_date_element`, `search_list_date_class`, `search_list_date_class_type`, `searchlist_image_class`, `searchlist_image_class_element`, `searchlist_image_class_type`, `description_div_class`, `description_content_class_type`, `description_title_class`, `description_author_class`, `description_date_class`, `description_image_class`, `description_image_class_type`, `description_content_class`,`description_div_class_element`) VALUES (NULL,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);";
        $data_article = array($source_id, $data['search_list_repeat_element'], $data['searchlist_repeat_element_class'], $data['searchlist_title_class'], $data['searchlist_title_headtag'], $data['searchlist_author_class'], $data['search_list_date_element'], $data['search_list_date_class'], $data['search_list_date_class_type'], $data['searchlist_image_class'], $data['searchlist_image_class_element'], $data['searchlist_image_class_type'], $data['description_div_class'], $data['description_content_class_type'], $data['description_title_class'], $data['description_author_class'], $data['description_date_class'], $data['description_image_class'], $data['description_image_class_type'], $data['description_content_class'], $data['description_div_class_element']);
        $this->rs = $this->conn->executePrepared($this->sql_articles, $data_article) or die(mysql_error());

        if (count($this->rs) > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public function getaccessToken($val = '') {
        if ($val != "") {
            echo $this->sql1 = "INSERT INTO  tbl_acc_tok (`accesstoken`, `site`) VALUES (?,'instagram');";
            $data_source = array($val);
            $this->conn->executePrepared($this->sql1, $data_source) or die(mysql_error());
        }
        echo $this->sql = "SELECT accesstoken from tbl_acc_tok where site='instagram'"; //exit;
        $this->rs = $this->conn->selectSql($this->sql, array());
        return $this->rs;
    }

    public function getfbaccessToken($val = '', $id = '') {
        if ($val != "") {
            echo $this->sql1 = "UPDATE  tbl_acc_tok set `accesstoken`=?, `site`='facebook' where `id`=?";
            // echo $this->sql1 = "INSERT INTO  tbl_acc_tok (`accesstoken`, `site`) VALUES (?,'facebook') where id=?";
            $data_source = array($val, $id);
            $this->conn->executePrepared($this->sql1, $data_source);
        }
        echo $this->sql = "SELECT accesstoken from tbl_acc_tok where site='facebook'"; //exit;
        $this->rs = $this->conn->selectSql($this->sql, array());
        return $this->rs;
    }

    function getSimilarPhotos($fanwireid, $presentArtId) {
        $objectFanwires = new Fanwires();
        if ($_SESSION['id']) {
            $limit = 6;
        } else {
            $limit = 6; //10;
        }
        $this->sql = "SELECT DISTINCT id,name,album_url,pid from tbl_albums where `pid`=" . $fanwireid . " and id !='" . $presentArtId . "' ORDER BY RAND() ASC LIMIT 0,$limit  ";
        $this->rs = $this->conn->executeQuery($this->sql);
        $i = 0;
        foreach ($this->rs as $value) {
            $data[$i]['id'] = $value['id'];
            $data[$i]['title'] = $value['name'];
            $data[$i]['photo'] = $value['album_url'];
            if ($value['name'] == '') {
                $title = $value['id'];
                $data[$i]['title_link'] = SITE_URL . $objectFanwires->getFanwireUrl($value['pid']) . "/Instagram/" . $title;
            } else {
                $data[$i]['title_link'] = SITE_URL . $objectFanwires->getFanwireUrl($value['pid']) . "/Gallery/" . YoutubeUrl::fixurl($value['name']);
            }
            list($width, $height) = getimagesize(IMAGE_URL . $data[$i]['photo']);
            $data[$i]['width'] = 110;
            $data[$i]['height'] = floor(($height / $width) * 110);
            $i++;
        }
        return $data;
    }

}
