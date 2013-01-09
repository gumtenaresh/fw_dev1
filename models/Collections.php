<?php

/* * *********mycollections ************* */

class Collections {

    private $conn = null;
    private $sql = null;
    private $rs = null;
    private $data = array();

    /**
     * creating dao object
     */
    function __construct() {
        $this->conn =  Dao::getInstance();
        $this->obj = new Users();
    }

    //destroying memory
    function __destruct() {
        unset($this->conn);
        unset($this->rs);
        unset($this->sql);
        unset($this->data);
    }

    /*
     *
     * name: CheckCollectFanwire
     * @param: user_id,fanid
     * @return :array
     * check the user already collected the fanwire or not
     */

    function checkCollectFanwire($user_id, $fanid, $type) {
        $this->sql = "SELECT * FROM tbl_collections WHERE user_id='" . $user_id . "' and fanwire_id='" . $fanid . "' and type='" . $type . "'";
        $this->rs = $this->conn->executeQuery($this->sql);

        return $this->rs;
    }

    /*
     *
     * name: CollectFanwire
     * @param: user_id,fanid
     * @return :array
     */

    function collectFanwire($user_id, $fanid, $type) {
        $rs = self::checkCollectFanwire($user_id, $fanid, $type);
        if (empty($rs)) {
            $this->sql = "INSERT INTO tbl_collections(user_id,fanwire_id,status,collected_date,type) VALUES (?,?,?,NOW(),?)";
            $data = array($user_id, $fanid, '1', $type);
            $res = $this->conn->executePrepared($this->sql, $data) or die(mysql_error());
            return "1";
        } else {
            if ($rs['0']['status'] == 2) {
                $this->sql = "UPDATE tbl_collections SET status = '1' WHERE user_id ='" . $user_id . "' and fanwire_id ='" . $fanid . "' and type='" . $type . "'";
                $this->conn->executeUpdate($this->sql);
                return "2";
            }
            $this->sql = "UPDATE tbl_collections SET status = '2' WHERE user_id ='" . $user_id . "' and fanwire_id='" . $fanid . "' and type='" . $type . "'";
            $this->conn->executeUpdate($this->sql);
            return "3";
        }
    }

    /**
     * name:getColleectedFanwires
     * @param:fanwire_id
     * @return:array
     * */
//Code by Rak
    public function getLikesCount($id, $type) {
        $sql = "select sum(`like`) as `like`,sum(`dislike`) as `dislike` from tbl_fanwire_likes where fanwireid=? and type=?";
        $rs = $this->conn->selectSql($sql, array($id, $type));

        return $data = $rs[0]['like'] . "," . $rs[0]['dislike'];
    }

    /**
     * name:getCollectedFanwires
     * @param:user_id
     * @return:array
     * */
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
                $this->sql0 = "select f.id,f.name,f.last_updated,f.description,fw.url as fanwire,a.url from tbl_fanwires f,tbl_avatar_photos a where f.id=? and f.status=1 and f.id=a.fanwire_id and a.main_avator='1' and released_date<=NOW()";
                $rs0 = $this->conn->selectSql($this->sql0, array($fan_id));
                foreach ($rs0 as $val0) {
                    $list[$i]['id'] = $val0['id'];
                    $list[$i]['name'] = $val0['name'];
                    $list[$i]['photo'] = $val0['url'];
                    $list[$i]['description'] = $val0['description'];
                    $list[$i]['link'] = SITE_URL .   $val0['fanwire'];// "profileInfo?fwrid=" . $val0['id'] . "&ac=1";
                    $arr = explode(",", $obj->getLikesCount($val0['id'], '0'));
                    $list[$i]['like'] = $arr[0];
                    $list[$i]['dislike'] = $arr[1];
                    $list[$i]['commentcnt'] = $obj->getCommentCount($val0['id'], '0');
                    $list[$i]['status'] = $object->getCollectedStatus($val0['id'], '0');
                    $list[$i]['type'] = '0';
                    $list[$i]['source'] = '';
                    $list[$i]['comments_for_this_post'] = $obj->getComments($val0['id'], '0');
                    $list[$i]['time'] = DatePicker::getTimeStamp($val0['last_updated']); //$val0['last_updated'];
                    $i++;
                }
            }
            if ($type == '1') {

                $fan_id . $this->sql1 = "select id,cdate,released_date,name,album_url,status,pid from tbl_albums where belongsTo='2' and id=? and visible='1'";
                $rs1 = $this->conn->selectSql($this->sql1, array($fan_id));
                foreach ($rs1 as $val1) {
                    $list[$i]['id'] = $val1['id'];
                    $list[$i]['title'] = $val1['name'];
                    $list[$i]['photo'] = $val1['album_url'];
                    $list[$i]['name'] = $object->getFanwireName($val1['pid']);
                    $list[$i]['description'] = '';
                    // $list[$i]['link'] = SITE_URL . "viewAlbum?aid=" . $val1['id'];
                    $list[$i]['title_link'] = SITE_URL . $object->getFanwireUrl($val1['pid'])."/Gallery/" . str_replace(" ", "-", str_replace("'", "",$val1['name']));//"viewAlbum?aid=" . $val1['id'];
                    $list[$i]['link'] = SITE_URL . $object->getFanwireUrl($val1['pid']);//"phots?fwrid=" . $val1['pid'] . "&ac=1";
                    $arr = explode(",", $obj->getLikesCount($val1['id'], '1'));
                    $list[$i]['like'] = $arr[0];
                    $list[$i]['dislike'] = $arr[1];
                    $list[$i]['commentcnt'] = $obj->getCommentCount($val1['id'], '1');
                    $list[$i]['status'] = $object->getCollectedStatus($val1['id'], '1');
                    $list[$i]['type'] = '1';
                    $list[$i]['source'] = '';
                    $list[$i]['comments_for_this_post'] = $obj->getComments($val1['id'], '1');
                    $list[$i]['time'] = DatePicker::getTimeStamp($val1['cdate']); //$val1['released_date'];
                    $i++;
                }
            }
            if ($type == '2') {
                $this->sql2 = "select id,cdate,released_date,title,description,photo,source,user_id from tbl_articles where visible='1' and  released_date!='0000-00-00 00:00:00' and belongsTo='2' and id=?";
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
                    $list[$i]['description'] = $val2['description'];//YoutubeUrl::replace_plain_text_link(strtolower($val2['description']));
                    $list[$i]['title_link'] = SITE_URL . $object->getFanwireUrl($val2['user_id'])."/Articles/" . str_replace(" ", "-", str_replace("'", "",$val2['title']));//"viewArticle?aid=" . $val2['id'];
                    //  $list[$i]['title_link'] = SITE_URL . "viewAlbum?aid=" . $val2['id'];
                    $list[$i]['link'] = SITE_URL . $object->getFanwireUrl($val2['user_id']);//"phots?fwrid=" . $val2['user_id'] . "&ac=1";
                    $arr = explode(",", $obj->getLikesCount($val2['id'], '2'));
                    $list[$i]['like'] = $arr[0];
                    $list[$i]['dislike'] = $arr[1];
                    $list[$i]['commentcnt'] = $obj->getCommentCount($val2['id'], '2');
                    $list[$i]['status'] = $object->getCollectedStatus($val2['id'], '2');
                    $list[$i]['type'] = '2';
                    $list[$i]['source'] = $val2['source'];
                    $list[$i]['comments_for_this_post'] = $obj->getComments($val2['id'], '2');
                    $list[$i]['time'] = DatePicker::getTimeStamp($val2['cdate']); //$val2['released_date'];
                    $i++;
                }
            }
            if ($type == '3') {
                $this->sql3 = "select id,cdate,released_date,title,thumbnail,description,whomItBelongsTo from tbl_videos where visible='1' and released_date!='0000-00-00 00:00:00' and belongsTo='2' and id=?";
                $rs3 = $this->conn->selectSql($this->sql3, array($fan_id));
                foreach ($rs3 as $val3) {
                    $list[$i]['id'] = $val3['id'];
                    $list[$i]['title'] = $val3['title'];
                    $list[$i]['name'] = $object->getFanwireName($val3['whomItBelongsTo']);
                    $list[$i]['photo'] = $val3['thumbnail'];
                    $list[$i]['description'] = $val3['description'];
                    // $list[$i]['link'] = SITE_URL . "viewVideos?vid=" . $val3['id'];
                    $list[$i]['title_link'] = SITE_URL .$object->getFanwireUrl($val3['whomItBelongsTo'])."/Videos/" . str_replace(" ", "-", str_replace("'", "",$val3['title']));
                    //  $list[$i]['title_link'] = SITE_URL . "viewAlbum?aid=" . $val2['id'];
                    $list[$i]['link'] = SITE_URL . $object->getFanwireUrl($val3['whomItBelongsTo']);//"phots?fwrid=" . $val2['user_id'] . "&ac=1";
                    $arr = explode(",", $obj->getLikesCount($val3['id'], '3'));
                    $list[$i]['like'] = $arr[0];
                    $list[$i]['dislike'] = $arr[1];
                    $list[$i]['commentcnt'] = $obj->getCommentCount($val3['id'], '3');
                    $list[$i]['status'] = $object->getCollectedStatus($val3['id'], '3');
                    $list[$i]['type'] = '3';
                    $list[$i]['source'] = '';
                    $list[$i]['comments_for_this_post'] = $obj->getComments($val3['id'], '3');
                    $list[$i]['time'] = DatePicker::getTimeStamp($val3['cdate']); //$val3['released_date'];
                    $i++;
                }
            }
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

        return $list = sortByOneKey($list, 'time', false);
    }

}
