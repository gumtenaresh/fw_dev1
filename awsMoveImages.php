<?php

include 'config.inc.php';
$photos = new CombineFeatures();
$fs = StorageFactory::getObject();
if ($_GET['path'] == 'photos') {
    $val = 'photos';
} else {
    $val = 'profile_images';
}
$list = $photos->moveImages($_GET['path']);
$i = 0;
$j = 0;
foreach ($list as $key => $value) {
    $tt = $fs->saveFile(DOC_ROOT_PATH . "/" . $val . "/" . $value['photo'], $val . "/" . $value['photo']);
    if ($tt) {
        $i++;
        echo"The images named" . $value['photo'] . " is moved suucessfully on  " . date("Y-m-d H:i:s") . "<br>";
    } else {
        $j++;
        echo "The images named" . $value['photo'] . " is moved failed on  " . date("Y-m-d H:i:s") . "<br>";
    }
}
echo $i . "Records are moved successfully<br>";
echo $j . "Records are failed to upload";
exit();
?>
