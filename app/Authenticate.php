<?php
class Authenticate{
	
	/**
	 *
	 */
	public function checkAuthentication(){
        return Authenticate::checkBasicAuthentication();
	}
	
	/**
	 *
	 */
	private function checkBasicAuthentication(){
        global $USER;	   
	   
	   if($_SERVER['PHP_AUTH_USER'] && $_SERVER['PHP_AUTH_PW']){
	       
	        include_once(DOC_ROOT_PATH . '/modules/users/model/Users.php');
			$USER = Users::authenticateUser($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);
			
			if(!empty($USER)){
				return $USER;
			}else{
			     header('WWW-Authenticate: Basic realm="'.SITE_NAME.'"');
			}
		}else{
		    header('WWW-Authenticate: Basic realm="'.SITE_NAME.'"');
		}
		return false;
	}
}