<?php

/**
 * This is a wrapper class around AmazonS3
 * @author shiva
 *
 */
class AmazonS3Storage extends AmazonS3 implements FileStorageInterface {

    private $url;
    private $bucket;

    /**
     * Creates an instance of AmazonS3 class
     * @param string $bucket Bucket name
     * @param string $key Optional AWS Key
     * @param string $secretKey Optional AWS Secret
     */
    public function __construct($bucket, $key = null, $secretKey = null) {
        $this->bucket = $bucket;
        $this->url = 'http://s3.amazonaws.com/' . $bucket . '/';
        return parent::__construct($key, $secretKey);
    }

    /**
     * Adds a file
     * @param string $sourceFile full path of source path to be uploaded
     * @param string $fileName full path of the filename including dir structure if needed.
     * @return bool
     */
    public function saveFile($sourceFile, $fileName) {
         error_log(__METHOD__ . ' ' . $sourceFile . ', ' . $fileName);

        $maxRetries = 3;
        $bool = false;
        while (!$bool && $maxRetries--) {
            try {
                $resp = $this->create_object($this->bucket, $fileName, array(
                    'fileUpload' => $sourceFile,
                    'acl' => AmazonS3::ACL_PUBLIC,
                        ));
                $bool = true;
            } catch (Exception $e) {
                error_log(__METHOD__ . ': RequestCore_Exception Message: ' . $e->getMessage() . ' Bucket: ' . $this->bucket . ' fileName: ' . $fileName);
            }
        }

        if ($resp->status == 200) {
            return true;
        } else {
            error_log(__METHOD__ . ' Failed: ' . $fileName);
        }
        return false;
    }

    /**
     * Adds a file by contents as string
     * @param string $fileContents file contents
     * @param string $fileName full path of the filename including dir structure if needed.
     * @return bool
     */
    public function saveFileFromContents($fileContents, $fileName) {
        $maxRetries = 3;
        $bool = false;
        while (!$bool && $maxRetries--) {
            try {
                $resp = $this->create_object($this->bucket, $fileName, array(
                    'body' => $fileContents,
                    'acl' => AmazonS3::ACL_PUBLIC,
                    'storage' => AmazonS3::STORAGE_STANDARD
                        ));
                $bool = true;
            } catch (Exception $e) {
                error_log(__METHOD__ . ': RequestCore_Exception Message: ' . $e->getMessage() . ' Bucket: ' . $this->bucket . ' fileName: ' . $fileName);
            }
        }

        if ($resp->status == 200) {
            return true;
        } else {
            error_log(__METHOD__ . ' Failed: ' . $fileName);
        }
        return false;
    }

    /**
     * Returns the file data as a string
     * @param string $filePath
     * @return false|contents
     */
    public function getFileData($filePath) {

        $maxRetries = 3;
        $bool = false;
        $logFound = false;
        while (!$bool && $maxRetries--) {
            try {
                $resp = $this->get_object($this->bucket, $filePath);
                $bool = true;
            } catch (Exception $e) {
                error_log(__METHOD__ . ': RequestCore_Exception Message: ' . $e->getMessage() . ' Bucket: ' . $this->bucket . ' file: ' . $filePath);
                $logFound = true;
            }
        }
        if ($resp->status == 200) {

            if ($logFound) {
                error_log(__METHOD__ . ': RequestCore_Exception file found on retry: ' . (3 - $maxRetries) . ' Bucket: ' . $this->bucket . ' file: ' . $filePath);
            }

            if ($resp->body instanceof CFSimpleXML) { //To override aws s3 bug of auto parsing xml and returning as xml object rather than string
                return $resp->body->asXML();
            }
            return $resp->body;
        } else {
            //error_log(__METHOD__ . ' Failed: ' . $filePath);
        }

        return false;
    }

    /**
     * Deletes a file
     * @param string $filePath
     * @return bool
     */
    public function deleteFile($filePath) {
        $resp = $this->delete_object($this->bucket, $filePath);
        if ($resp->status == 204) {
            return true;
        } else {
            error_log(__METHOD__ . ' Failed: ' . $filePath);
        }
        return false;
    }

    /**
     * Returns the url to the file
     * @param string $filePath
     * @return string
     */
    public function getFileUrl($filePath) {
        return $this->url . '/' . $filePath;
    }

    /**
     * Renames a file in the same bucket
     * @param string $src
     * @param string $dest
     * @return bool
     */
    public function renameFile($src, $dest) {
        $opt = array('acl' => AmazonS3::ACL_PUBLIC);
        $src = array('bucket' => $this->bucket, 'filename' => $src);
        $dest = array('bucket' => $this->bucket, 'filename' => $dest);

        $maxRetries = 3;
        $bool = false;
        while (!$bool && $maxRetries--) {
            try {
                $resp = $this->copy_object($src, $dest, $opt);
                $bool = true;
            } catch (Exception $e) {
                error_log(__METHOD__ . ': RequestCore_Exception Message: ' . $e->getMessage() . ' Bucket: ' . $this->bucket . ' src: ' . $src . ' dest: ' . $dest);
            }
        }

        if ($resp->status == 200) {
            $bl = true;
        } else {
            error_log(__METHOD__ . ' Failed: ' . $sourceFile . ' ' . $destination);
        }
        $resp = $this->deleteFile($src['filename']);

        return $bl;
    }

    /**
     * Return file size
     * @param string $filename
     * @return integer
     */
    public function getFileSize($filename) {
        $maxRetries = 3;
        $bool = false;
        $logFound = false;
        while (!$bool && $maxRetries--) {
            try {
                $filesize = $this->get_object_filesize($this->bucket, $filename);
                $bool = true;
            } catch (Exception $e) {
                error_log(__METHOD__ . ': RequestCore_Exception Message: ' . $e->getMessage() . ' Bucket: ' . $this->bucket . ' file: ' . $filename);
                $logFound = true;
            }
        }
        if ($logFound) {
            error_log(__METHOD__ . ': RequestCore_Exception file found on retry: ' . (3 - $maxRetries) . ' Bucket: ' . $this->bucket . ' file: ' . $filename);
        }

        return $filesize;
    }

    /**
     * copies the file from src to dest
     * @param $sourceFile
     * @param $destination
     * @return bool
     */
    public function copyFile($sourceFile, $destination) {
        $opt = array('acl' => AmazonS3::ACL_PUBLIC);
        $src = array('bucket' => $this->bucket, 'filename' => $sourceFile);
        $dest = array('bucket' => $this->bucket, 'filename' => $destination);
        $maxRetries = 3;
        $bool = false;
        while (!$bool && $maxRetries--) {
            try {
                $resp = $this->copy_object($src, $dest, $opt);
                $bool = true;
            } catch (Exception $e) {
                error_log(__METHOD__ . ': RequestCore_Exception Message: ' . $e->getMessage() . ' Bucket: ' . $this->bucket . ' src: ' . $src . ' dest:' . $dest);
            }
        }

        if ($resp->status == 200) {
            $bl = true;
        } else {
            error_log(__METHOD__ . ' Failed: ' . $sourceFile . ' ' . $destination);
        }
        return $bl;
    }

    /**
     * Deletes specified folder recursively.
     * Note: this method might take considerable amount of time, please try to avoid it.
     * @param string $dir
     * @return bool
     */
    public function removeDirectory($dir) {
        $opt = array(
            'prefix' => $dir . DIRECTORY_SEPARATOR
        );
        $files = $this->get_object_list($this->bucket, $opt);

        for ($i = 0, $cnt = count($files); $i < $cnt; ++$i) {
            $resp = $this->delete_object($this->bucket, $files[$i]);
            if ($resp->status != 204) {
                error_log(__METHOD__ . ' Failed: ' . $files[$i]);
                return false;
            }
        }

        return true;
    }

    /**
     * checks whether a file exists or not.
     * @param str $filePath
     * @return bool
     */
    public function fileExists($filePath) {
        $maxRetries = 3;
        $bool = false;
        while (!$bool && $maxRetries--) {
            try {
                $bl = $this->if_object_exists($this->bucket, $filePath);
                $bool = true;
            } catch (Exception $e) {
                error_log(__METHOD__ . ': RequestCore_Exception Message: ' . $e->getMessage() . ' Bucket: ' . $this->bucket . ' filePath: ' . $filePath);
            }
        }
        return $bl;
    }

}