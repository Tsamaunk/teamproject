<?php

class Helper {
	
	private $mod;
	
	function __construct() {
		$this->mod = new Model();
	}
	
	public function updateTokenTime($token, $timer = TOKEN_EXP) {
		$sql = "UPDATE `security` SET `timer` = '" . (time() + 60*$timer )."' WHERE `userToken` = '$token';";
		$this->mod->query($sql);
		$this->mod->close();
		$this->mod->logger->info('updateTokenTime called');
	}
		
	public function validToken($uid, $utoken) {
		$this->mod->logger->info("validToken called for user $uid");
		if ($utoken != $this->hasher($uid)) return false;
		$sql = "SELECT `timer` FROM `security` WHERE `userToken` = '$utoken' AND `userId` = '$uid' ;";
		$result = $this->mod->query($sql);
		$this->mod->close();
		$result = mysql_fetch_row($result);
		$timer = $result[0] - time();
		if ($timer > 0 && $timer < 60 * TOKEN_HALFLIFE) $this->updateTokenTime($utoken);
		if ($timer <= 0) return false;
		return true;
	}
	
	public function createUserToken($uid, $timer = TOKEN_EXP) {
		$this->mod->logger->info("createUserToken called for user $uid");
		$userToken = $this->hasher($uid);
		$sql = "DELETE FROM `security` WHERE `userToken` = '$userToken' AND `userId` = '$uid'; ";
		$this->mod->connect();
		$this->mod->query($sql);		
		$sql = "INSERT INTO `security` VALUES (0, '$userToken', '$uid', '".(time() + 60*$timer) ."')";
		$this->mod->query($sql);
		echo mysql_error();
		$this->mod->close();
		return $userToken;
	}
	
	/**
	 * Function returns token 
	 */
	public function execToken($token) {
		$this->mod->logger->info('execToken called');
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
		$this->mod->logger->info("new token \"$string\" created");
		if (!isset($token->token) || !$token->token)
			$token->token = $this->generator($userId,$string);
		if (!isset($token->created)) $token->created = time();
		if (!isset($token->expired)) $token->expired = time() + 7 * 24 * 60 * 60; // one week
		if (!isset($token->raId1)) $token->raId1 = null;
		if (!isset($token->raId2)) $token->raId2 = null;
		if (!isset($token->rdId)) $token->rdId = null;
		if (!isset($token->switchId)) $token->switchId = null;		
		$sql = "INSERT INTO `token` (`created`,`expired`,`type`,`token`,`raId1`,`raId2`,`rdId`,`switchId`) VALUES (
				'".$token->created."','".$token->expired."','".$token->type."','".$token->token."',".$token->raId1.",".$token->raId2.
				",".$token->rdId.",".$token->switchId.");";
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
		$this->mod->logger->info("token ".$token->id." destroyed");
		$sql = "DELETE FROM `token` WHERE `id` = '".$token->id."';";
		$con = new Model();
		$result = $con->query($sql);
		$con->close();
		return $result;
	}
	
	public function generator($userId = 0, $string = 'token') {
		$this->mod->logger->info("generator made a hash for user $userId");
		$str = md5($userId . microtime() . SALT);
		$str .= md5($string . microtime());
		return $str;
	}
	
	private function hasher($id) {
		return md5('hash' . $id . 'code' . SALT);
	}
	
}

?>