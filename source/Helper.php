<?php

class Helper {
	
	public function validToken($uid, $utoken) {
		return ($utoken == $this->hasher($uid));
	}
	
	public function createUserToken($uid) {
		return $this->hasher($uid);
	}
	
	/**
	 * Function returns token 
	 */
	public function execToken($token) {
		$sql = "SELECT * FROM `token` WHERE `token` = '$token';";
		$con = new Model();
		$result = $con->query($sql);
		$con->close();
		
		$cnt = new Controller();
		return $cnt->orm($result);		
	}
	
	/**
	 * Create mail token
	 * token->type = 1 - confirm email, 2 - approve user, 3 - decline user, 4 - confirm switch, 5 - decline switch,
	 * 6 - approve switch, 7 - disapprove switch  
	 */
	public function makeToken($token = null, $userId = 0, $string = 'token') {
		if (!isset($token->token) || !$token->token)
			$token->token = $this->generator($userId,$string);
		if (!isset($token->created)) $token->created = time();
		if (!isset($token->expired)) $token->expired = time() + 7 * 24 * 60 * 60; // one week
		if (!isset($token->raId1)) $token->raId1 = null;
		if (!isset($token->raId2)) $token->raId2 = null;
		if (!isset($token->rdId)) $token->rdId = null;
		if (!isset($token->switchId)) $token->switchId = null;		
		$sql = "INSERT INTO `token` (`created`,`expired`,`type`,`token`,`raId1`,`raId2`,`rdId`,`switchId`) VALUES (
				'".$token->created."','".$token->expired."','".$token->type."','".$token->token."','".$token->raId1."','".$token->raId2.
				"','".$token->rdId."','".$token->switchId."');";
		$con = new Model();
		$result = $con->query($sql);
		$con->close();
		if ($result)
			return $token;
		else return false;		
	}
	
	/*
	 * Function removes token
	 */
	public function destroy($token) {
		$sql = "DELETE FROM `token` WHERE `id` = '".$token->id."';";
		$con = new Model();
		$result = $con->query($sql);
		$con->close();
		return $result;
	}
	
	/**
	 * function creates a token string
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