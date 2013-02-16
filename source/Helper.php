<?php

class Helper {
	
	public function validToken($uid, $utoken) {
		return ($utoken == $this->hasher($uid));
	}
	
	public function createUserToken($uid) {
		return $this->hasher($uid);
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
	
	private function hasher($id) {
		return md5('hash' . $id . 'code');
	}
	
}

?>