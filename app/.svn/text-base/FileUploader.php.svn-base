<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * Usage of FileUploader Class
 * ex:-     $fileName = "profilePhoto"
 *          $fileLabelName = "profile photo"
 *          $filePath = "/var/www/test/images/"
 *
 *          $obj = new FileUploader($filePath, $_FILES);
 * 
 *          $obj->universalFileUploader($fileName, $fileLabelName = null);
 */

/**
 * Description of FileUploader
 *
 * @author satyam
 * Takes files and validates, uploads to destination
 * 
 */
class FileUploader {

    private $fileName = null;
    private $fileLabelName = null;
    private $filePath = null;
    private $isRandomFileName = 'Y'; //'N'
    private $fileSize = 2097152;  // 2 mb
    private $file = array();
    private $fileType = array('image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/pjpeg', 'image/x-png');
    private $data = array();

    public function __construct($filePath, $file = array(), $fileType = null, $fileSize = null) {
        $this->filePath = $filePath;
        $this->file = $file;

        if (!empty($fileType)) {
            $this->fileType = $fileType;
        }
        if (!empty($fileSize)) {
            $this->fileSize = $fileSize;
        }
    }

    /**
     * universal file upload
     * @author satyam
     * @param fileName, filePath, file, fileType , fileSize
     * All Parameters Should be passed
     * returns array
     * array contains errors,  file name
     */
    public function universalFileUploader($fileName, $fileLabelName = null) {

        $this->fileName = $fileName;
        if (!empty($fileLabelName)) {
            $this->fileLabelName = $fileLabelName;
        } else {
            $this->fileLabelName = $fileName;
        }

        $this->data = array($this->fileName => '', 'error' => '');

        // file validation
        if (!empty($this->file[$this->fileName]['name'])) {

            if ((in_array($this->file[$this->fileName]["type"], $this->fileType)) && $this->file[$this->fileName]["size"] != 0 && ($this->file[$this->fileName]["size"] < $this->fileSize)) {

                if ($this->file[$this->fileName]["error"] > 0) {
                    $this->data['error'] = $this->file[$this->fileName]["error"];
                    return $this->data;
                } else {
                    // changing file name
                    $randId = uniqid();
                    $temp = explode(".", $this->file[$this->fileName]['name']);
                    $ext = $temp[count($temp) - 1];
                    if ($this->isRandomFileName == 'Y') {
                        $randomFileName = $randId . "." . $ext;
                    } else {
                        $randomFileName = $this->fileName . "." . $ext;
                    }
                    if (file_exists($this->filePath . "" . $randomFileName)) {
                        $this->data['error'] = $this->fileLabelName . " already exists with this name.";
                        return $this->data;
                    } else {
                        if (move_uploaded_file($this->file[$this->fileName]["tmp_name"], $this->filePath . "" . $randomFileName)) {
                            $this->data[$this->fileName] = $randomFileName;
                        }
                    }
                }
            } else {
                if (!in_array($this->file[$this->fileName]["type"], $this->fileType)) {
                    $this->data['error'] = "Invalid " . $this->fileLabelName . " file format.";
                    return $this->data;
                }
                $size = $this->fileSize / (1024 * 1024);
                $this->data['error'] = "Please choose file size less than " . $size . " mb for " . $this->fileLabelName . ".";
                return $this->data;
            }
        }

        return $this->data;
    }

}

