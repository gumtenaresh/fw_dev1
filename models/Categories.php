<?php

/**
 * Description of Categories
 * @author gangaraju
 */
class Categories {

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
     * get categories list
     * @param type $pageSize
     * @return type 
     */
    public function getCategoriesList($pageSize = 10) {
        $this->sql = "SELECT c1.id as c1id, c1.name as c1name, c2.id as c2id, c2.name as c2name  FROM tbl_category c1
                        LEFT JOIN tbl_category c2
                            ON c2.parentid = c1.id 
                        WHERE c1.parentid = 0";
        $this->rs = $this->conn->executeQuery($this->sql);
        $this->rs = $this->getArrayFormat2($this->rs);
        return $this->rs;
    }

    /**
     * add category
     * @param type $name
     * @return type 
     */
    public function addCategory($params) {
        extract($params);
        $this->sql = "insert into tbl_category(`name`,`parentid`,`metatitle`) values(?,?,?)";
        $data = array($name, $parent, $metatitle);
        return $this->conn->executePrepared($this->sql, $data) or die(mysql_error());
    }

    /**
     * get category name
     * @param type $id
     * @return type 
     */
    public function getCategory($id) {
        $this->sql = "SELECT * from tbl_category where id='$id'";
        $this->rs = $this->conn->executeQuery($this->sql);
        return $this->rs;
    }

    /**
     * edit category
     * @param type $name
     * @return type 
     */
    public function editCategory($params) {
        extract($params);
        $this->sql = "update tbl_category set `name`= ?,`metatitle`=? where id=?";
        $data = array($name, $metatitle, $id);
        return $this->conn->executePrepared($this->sql, $data) or die(mysql_error());
    }

    /**
     * delete category
     * @param type $id
     * @return type 
     */
    public function deleteCategory($id) {
        $parentid = $this->getChildCategory($id);
        $this->sql = "delete from tbl_category where id=? or parentid in(?,?) ";
        $data = array($id, $id, $parentid);
        return $this->conn->executePrepared($this->sql, $data) or die(mysql_error());
    }

    /**
     * get categories
     * @return type 
     */
    public function getCategories() {
        //  $this->sql = "SELECT * from tbl_category WHERE parentid = 0";
        $this->sql = "SELECT c.id, c.name, c.parentid
                    FROM `tbl_category` c, `tbl_fanwire_categories` fc
                    WHERE c.`parentid` = '0'
                    AND c.id = fc.category
                    GROUP BY c.id
                    LIMIT 0 , 30";
        $this->rs = $this->conn->executeQuery($this->sql);
        //$this->rs = $this->getArrayFormat2($this->rs);
        return $this->rs;
    }

    /**
     * this function will take the category array and return the formated  2 level array.
     * @param type $cat
     * @return category array 
     */
    public function getArrayFormat2($cat) {
        $cat1 = array();
        foreach ($cat as $key => $value) {
            $cat1[$value['c1id']] = $value['c1name'];
        }
        $cat1 = array_unique($cat1);
        $cat2 = array();
        foreach ($cat as $key => $value) {
            if ($value['c2id'] != "") {
                $cat2[$value['c2id']]['parentid'] = $value['c1id'];
                $cat2[$value['c2id']]['id'] = $value['c2id'];
                $cat2[$value['c2id']]['name'] = $value['c2name'];
            }
        }
        $i = 0;
        $data = array();
        foreach ($cat1 as $key1 => $val1) {
            $data[$i]['id'] = $key1;
            $data[$i]['name'] = $val1;
            $data[$i]['parentid'] = 0;
            $data[$i]['level'] = 0;
            foreach ($cat2 as $key2 => $val2) {
                if ($val2['parentid'] == $key1) {
                    $i++;
                    $data[$i]['id'] = $val2['id'];
                    $data[$i]['name'] = $val2['name'];
                    $data[$i]['parentid'] = $val2['parentid'];
                    $data[$i]['level'] = 1;
                }
            }
            $i++;
        }
        $i = 0;
        foreach ($data as $key => $val) {
            $data[$i]['id'] = $val['id'];
            $data[$i]['name'] = $val['name'];
            $data[$i]['parentid'] = $val['parentid'];
            $data[$i]['level'] = $val['level'];
            $data[$i]['metatitle'] = $this->getMetaTitle($val['id']);
            $i++;
        }
        // echo "<pre>";print_r($data);
        return $data;
    }

    public function getMetaTitle($id) {
        $this->sql = "SELECT `metatitle` from tbl_category where id='$id'";
        $this->rs = $this->conn->executeQuery($this->sql);
        return $this->rs[0]['metatitle'];
    }

    /**
     * this function will take the category array and return the formated  3 level array.
     * @param type $cat
     * @return category array 
     */
    public function getArrayFormat3($cat) {

        $cat1 = array();
        foreach ($cat as $key => $value) {
            $cat1[$value['c1id']] = $value['c1name'];
        }
        $cat1 = array_unique($cat1);
        $cat2 = array();
        foreach ($cat as $key => $value) {
            if ($value['c2id'] != "") {
                $cat2[$value['c2id']]['parentid'] = $value['c1id'];
                $cat2[$value['c2id']]['id'] = $value['c2id'];
                $cat2[$value['c2id']]['name'] = $value['c2name'];
            }
        }

        $cat3 = array();
        foreach ($cat as $key => $value) {
            if ($value['c3id'] != "") {
                $cat3[$value['c3id']]['parentid'] = $value['c2id'];
                $cat3[$value['c3id']]['id'] = $value['c3id'];
                $cat3[$value['c3id']]['name'] = $value['c3name'];
            }
        }
        $i = 0;
        $data = array();
        foreach ($cat1 as $key1 => $val1) {
            $data[$i]['id'] = $key1;
            $data[$i]['name'] = $val1;
            $data[$i]['parentid'] = 0;
            $data[$i]['level'] = 0;
            foreach ($cat2 as $key2 => $val2) {
                if ($val2['parentid'] == $key1) {
                    $i++;
                    $data[$i]['id'] = $val2['id'];
                    $data[$i]['name'] = $val2['name'];
                    $data[$i]['parentid'] = $val2['parentid'];
                    $data[$i]['level'] = 1;

                    foreach ($cat3 as $key3 => $val3) {
                        if ($val3['parentid'] == $val2['id']) {
                            $i++;
                            $data[$i]['id'] = $val3['id'];
                            $data[$i]['name'] = $val3['name'];
                            $data[$i]['parentid'] = $val3['parentid'];
                            $data[$i]['level'] = 2;
                        }
                    }
                }
            }
            $i++;
        }
        return $data;
    }

    /**
     * getChildCategory
     * @param integer $id 
     */
    public function getChildCategory($id) {
        $this->sql = "SELECT `id` from tbl_category where parentid='$id'";
        $this->rs = $this->conn->executeQuery($this->sql);
        return $this->rs[0]['id'];
    }

}