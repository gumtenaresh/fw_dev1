<?php

class FileUtils {

    /**
     * Make a directory
     * @param $path
     * @return bool
     */
    public function makeDir($path) {
        if (!is_dir($path)) {
            return mkdir($path, 777, true);
        }
    }

    /**
     * Write base64encoded data to file
     * @param $content
     * @param $targetPath
     * @param $fileName (Optional)
     * @return string|false filepath on success, false on failure
     */
    public function writeEncodedFile($content, $targetPath, $fileName = null) {

        FileUtils::makeDir($targetPath);

        if (empty($fileName)) {
            $fileName = uniqid();
        }
        $filePath = $targetPath . '/' . $fileName;
        $bl = file_put_contents($filePath, base64_decode($content));
        //$bl = file_put_contents($filePath, $content);
        if ($bl === false) {
            return false;
        }
        return $filePath;
    }

    function saveUploadedFile($input_field, $target_path, $user_id = null, $max_size = 2097152) {
        //size is zero, error
        if ($_FILES[$input_field]['size'] == 0) {
            return null;
        }
        //max. size is 2 MB
        if ($_FILES[$input_field]['size'] > $max_size) {
            return false;
        }

        preg_match('/.*\.(\w+)/', $_FILES[$input_field]['name'], $t);
        $ext = $t[1];
        $img_name = uniqid() . '.' . $ext;

        //Check user folder
        $user_folder = $target_path;

        if (!empty($user_id)) {
            $user_folder .= '/' . $user_id;
        }

        FileUtils::makeDir($user_folder);

        if (!empty($_FILES[$input_field]['tmp_name'])) {
            move_uploaded_file($_FILES[$input_field]['tmp_name'], $user_folder . '/' . $img_name);
        }
        return (empty($user_id) ? '' : $user_id . '/') . $img_name;
    }

}
