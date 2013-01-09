<?php
/*
include 'config.inc.php';
$sql = "select id,video from  tbl_videos";
$con = new Dao;
$res = $con->executeQuery($sql);
$cnt = 0;
foreach ($res as $key => $value) {
    $r = explode("iframe", $value['video']);
    $re = explode("?", $r[1]);
    $rss = explode('/', $re[0]);
    ($rss[4]);
    $sqls = 'update tbl_videos set `youtubeid`=\'' . $rss[4] . '\' where id =' . $value['id'];
    $con->executeUpdate($sqls) or die(mysql_error());
    $cnt++;
}
echo $cnt . "Recors are updated";
*/echo "hi this si naresh ";
?>
