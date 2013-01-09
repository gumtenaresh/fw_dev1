<?php
class CacheFactory{

	public static $_instance = null;

	public static function getObject(){
		if(!(self::$_instance !== null)) {
			
			$gameCode = defined('GAME_CODE')?GAME_CODE:'';
			
			self::$_instance = new Apc($gameCode);
		}
		return self::$_instance;
	}

	private function __clone() {
		//keep it null, so that this object can't be created by cloning
	}
}