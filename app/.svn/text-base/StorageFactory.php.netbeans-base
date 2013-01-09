<?php
class StorageFactory{

	public static $_instance = null;

	public static function getObject(){
		if(!(self::$_instance !== null)) {
			if(defined('AMAZON_KEY') && defined('AMAZON_SECRET_KEY') && defined('AMAZON_BUCKET')){
				include_once(DOC_ROOT_PATH . '/libs/amazon-sdk/sdk.class.php');
				self::$_instance = new AmazonS3Storage(AMAZON_BUCKET, AMAZON_KEY, AMAZON_SECRET_KEY);
			}else{
				self::$_instance = new FileStorage(DOC_ROOT_PATH . DIRECTORY_SEPARATOR . 'data', SITE_URL . 'data/');
			}
		}
		return self::$_instance;
	}

	private function __clone() {
		//keep it null, so that this object can't be created by cloning
	}
}