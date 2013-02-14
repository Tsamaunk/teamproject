<?php

class Helper {
	
	public function validToken($uid, $utoken) {
		return true;
	}
	
	public function createUserToken($uid) {
		return 'token';
	}
	
	public function execToken($token) {
		
	}
	
	public function makeToken($token = null, $userId = 0, $string = 'token') {
		if (!$token)
			$token = $this->generator($userId,$string);		
		
		//$result = save token to db
		
		if ($result)
			return $token;
		else return false;		
	}
	
	/**
	 * function created a token string
	 */
	public function generator($userId = 0, $string = 'token') {
		$str = md5($userId . microtime());
		$str .= md5($string . microtime());
		return $str;
	}
	
}

?>