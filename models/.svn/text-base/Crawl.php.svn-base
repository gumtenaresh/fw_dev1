<?php

/**
 * Description of Crawl
 * @author naresh
 */
class Crawl {

    private $conn = null;
    private $sql = null;
    private $rs = null;
    private $data = array();

    /**
     * creating dao object
     */
    function __construct() {
        $this->conn =  Dao::getInstance();
    }

    //destroying memory
    function __destruct() {
        unset($this->conn);
        unset($this->rs);
        unset($this->sql);
        unset($this->data);
    }

    /**
     * getCrawlList
     * @param integer $id user id 
     * @param integer $pageSize page size
     * * @return array $data array f details
     */
    public function getCrawlList($id, $pageSize = 10) {
        if (isset($id) && $id != "")
            $this->sql = "SELECT * FROM  tbl_articles where user_id='$id' and source='3' order by id desc";
        else
            $this->sql = "SELECT * FROM  tbl_articles where source='3' order by id desc";
        $this->rs = $this->conn->selectSql($this->sql, array());
        $pageObject = new Pagination($pageSize, 9); //params: pagesize, scroll size
        $navigation = $pageObject->showNavigation(sizeof($this->conn->executeQuery($this->sql)));
        $this->sql .= $pageObject->getLimitQry();
        $this->rs = $this->conn->executeQuery($this->sql);
        $i = 0;
        foreach ($this->rs as $key => $value) {
            $data[$i]['id'] = $value['id'];
            $data[$i]['title'] = $value['title'];
            $data[$i]['description'] = $value['description'];
            $data[$i]['photo'] = $value['photo'];
            $data[$i]['visible'] = $value['visible'];
            $data[$i]['released_date'] = $value['released_date'];
            $data[$i]['cdate'] = $value['cdate'];
            $data[$i]['article_from'] = $value['article_from'];
            $i++;
        }
        $data['list'] = $data;
        $data['navigation'] = $navigation;
        return $data;
    }

    /**
     * deleteArticle
     * @param integer $id article id
     */
    public function deleteArticle($id) {
        $sql = "delete from tbl_articles where id=?";
        $this->conn->executePrepared($sql, array($id));
    }

    /**
     * changeStatus
     * @param integer $id article id
     * @param integer $staus 
     * @param date $date 
     */
    public function changeStatus($id, $status, $date) {
        $sql = "update tbl_articles set visible=?,released_date=? where id=?";
        $this->conn->executePrepared($sql, array($status, $date, $id));
    }

}