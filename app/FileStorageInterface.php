<?php
/**
 * This interface defines the common functions required to save / delete files
 * @author shiva
 *
 */
interface FileStorageInterface{
	
	public function saveFile($sourceFile, $fileName);
	public function saveFileFromContents($fileContents, $fileName);
	public function getFileData($filePath);
	public function deleteFile($filePath);
	public function getFileUrl($filePath);
	public function renameFile($src, $dest);
	public function getFileSize($filename);
	public function copyFile($sourceFile, $destination);
	public function removeDirectory($dir);
	public function fileExists($filePath);
	
}