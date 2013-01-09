<?php

/**
 * This is the wrapper for moving, deleting and storing files
 * @author shiva
 *
 */
class FileStorage implements FileStorageInterface {

    private $url;
    private $bucket;

    /**
     * Creates an instance of FileStorage class
     * @param string $bucket Bucket name
     * @param string $key Optional AWS Key
     * @param string $secretKey Optional AWS Secret
     */
    public function __construct($baseDir, $baseUrl) {
        $this->bucket = $baseDir;
        $this->url = $baseUrl;
    }

    /**
     * Adds a file
     * @param string $sourceFile full path of source path to be uploaded
     * @param string $fileName full path of the filename including dir structure if needed.
     * @return bool
     */
    public function saveFile($sourceFile, $fileName) {
        $fileName = $this->bucket . DIRECTORY_SEPARATOR . $fileName;
        return FileUtils::copyFile($sourceFile, $fileName);
    }

    /**
     * Adds a file by contents as string
     * @param string $fileContents file contents
     * @param string $fileName full path of the filename including dir structure if needed.
     * @return bool
     */
    public function saveFileFromContents($fileContents, $fileName) {
        $fileName = $this->bucket . DIRECTORY_SEPARATOR . $fileName;
        $pathInfo = pathinfo($fileName);
        $bl = FileUtils::writeToFile($fileContents, $pathInfo['dirname'], $pathInfo['basename']);
        if ($bl === false) {
            return false;
        }
        return true;
    }

    /**
     * Returns the file data as a string
     * @param string $filePath
     * @return false|contents
     */
    public function getFileData($filePath) {
        $filePath = $this->bucket . DIRECTORY_SEPARATOR . $filePath;
        if (!file_exists($filePath)) {
            return false;
        }
        return file_get_contents($filePath);
    }

    /**
     * Deletes a file
     * @param string $filePath
     * @return bool
     */
    public function deleteFile($filePath) {
        $filePath = $this->bucket . DIRECTORY_SEPARATOR . $filePath;
        if (!file_exists($filePath)) {
            return false;
        }
        unlink($filePath);
        return true;
    }

    /**
     * Returns the url to the file
     * @param string $filePath
     * @return string
     */
    public function getFileUrl($filePath) {
        return $this->url . $filePath;
    }

    /**
     * Renames a file
     * @param string $src
     * @param string $dest
     * @return bool
     */
    public function renameFile($src, $dest) {
        $src = $this->bucket . DIRECTORY_SEPARATOR . $src;
        $dest = $this->bucket . DIRECTORY_SEPARATOR . $dest;
        return FileUtils::moveFile($src, $dest);
    }

    /**
     * Return file size
     * @param string $filename
     * @return integer
     */
    public function getFileSize($filename) {
        $filesize = filesize($this->bucket . DIRECTORY_SEPARATOR . $filename);
        return $filesize;
    }

    /**
     * copies the file from src to dest
     * @param $sourceFile
     * @param $destination
     * @return bool
     */
    public function copyFile($sourceFile, $destination) {
        $src = $this->bucket . DIRECTORY_SEPARATOR . $sourceFile;
        $dest = $this->bucket . DIRECTORY_SEPARATOR . $destination;
        return FileUtils::copyFile($src, $dest);
    }

    /**
     * Removes a directory
     * @param string $dir
     * @return bool
     */
    public function removeDirectory($dir) {
        $dirPath = $this->bucket . DIRECTORY_SEPARATOR . $dir;
        return FileUtils::deleteDirectory($dirPath);
    }

    /**
     * checks whether a given file exists or not.
     * @param str $filePath
     * @return bool
     */
    public function fileExists($filePath) {
        return file_exists($this->bucket . DIRECTORY_SEPARATOR . $filePath);
    }

}