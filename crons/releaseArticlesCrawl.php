<?php

include(realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config.inc.php'));
$objectArticle = new Articles();
$res = $objectArticle->getAllArticles();
foreach ($res as $key => $value) {
	foreach($value['keywords'] as $val){
		if(empty($val['keyword'])){
			$val['keyword']=$value['fanwireName'];
		}
		$pos = strpos(strtolower($value['title']), strtolower($val['keyword']));
		if ($pos === false) {
			echo "The string <b>\" ".$val['keyword']."\" </b>was not found in the string <u><b>\" ".$value['title']."\"</b></u>";
		} else {
			$objectArticle->releaseArticles($value['id']);
			echo "The string <b> \" ".$val['keyword']." \" </b>was found in the string <u><b>\" ".$value['title']."\"</b></u> and exists at position". $pos;
		}
		echo "<br>";
	}
	echo "<br><br>";
}
?>
