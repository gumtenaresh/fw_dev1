<?php

/**
 * Cron actions *
 * @author naresh
 * 
 */
class cronsController {

    public function cronFacebookProfAndCoverPicsAction() {

        $objectFanwire = new Fanwires();
        $photosObj = new Photos();
        // $objectController = new fanwiresController();

        $list = $objectFanwire->getAllFanwireIds();
        $i = 0;
        foreach ($list as $key => $value) {

            $username = end(explode('/', $value['facebook']));
            $fanwire_id = $value['id'];

            $url = 'http://graph.facebook.com/' . $username;
            $data = json_decode($objectFanwire->get_data($url));
            $fid = $data->id;

            $img = file_get_contents('https://graph.facebook.com/' . $fid . '/picture?type=large');
            if ($img) {


                $filename = $fid . "_avator.jpg"; //strtotime(date("Y-m-d H:i:s")) . rand(9, 9999) . "New.jpg"; //$username.'.jpg';
                // $file = DOC_ROOT_PATH . '/photos/' . $filename;
                // file_put_contents($file, $img);
                $test = StorageFactory::getObject();
                $test->saveFileFromContents($img, 'photos/' . $filename);
            }

            $url = 'http://graph.facebook.com/' . $username . "?fields=cover";
            $data = json_decode($objectFanwire->get_data($url));
            $imgs = file_get_contents($data->cover->source);
            if ($imgs) {
                $cover_filename = $fid . "_timeline.jpg"; //$username.'_cover.jpg';
                $test = StorageFactory::getObject();
                $test->saveFileFromContents($img, 'photos/' . $cover_filename);
                // $file = DOC_ROOT_PATH . '/photos/' . $cover_filename;
                //file_put_contents($file, $imgs);
            }
            // print_r($data);
            $objectFanwire->InsertFbPhotos($fanwire_id, $filename, $cover_filename);
        }

        $i++;
    }

}

//end class
?>