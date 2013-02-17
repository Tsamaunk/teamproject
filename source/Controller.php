<?php

include_once 'Model.php';

class Controller {

	private $mod;
	
	/**
	 * This constructor created an instance of Model
	 * We can use this instance only inside this controller 
	 */
	function __construct() {
		$this->mod = new Model();
	}
	
	/**
	 * Function opens database connection
	 */
	public function connect() {
		return $this->mod->connect();
	}
	
	/**
	 * Function closes the database connection
	 */
	public function close() {
		$this->mod->close();
	}
	
	/**
	 * Convert Array to Object
	 */
	private function atoo($array) {
		$obj = new stdClass();
		foreach ($array as $key => $value)
			if (!is_numeric($key))		
				$obj->$key = $value;
		return $obj;
	}
	
	/**
	 * Function converts MYSQL resource into array of comments
	 */
	private function convert($resource) {
		$array = array();
		while ($r = mysql_fetch_array($resource)) {
			$array[] = $r;
		}
		return $array;
	}
	
	/**
	 * ORM 
	 */
	public function orm($resource) {
		$array = $this->convert($resource);
		if (count($array) == 1) {
			return $this->atoo($array[0]);
		} else {
			$obj = array();
			foreach ($array as $a)
				$obj[] = $this->atoo($a);
			return $obj;
		}
	}	
	
	/**
	 *  Handy function to validate email
	 */
	public function validate($email) {
	  $email = filter_var($email, FILTER_SANITIZE_EMAIL);
	  if(filter_var($email, FILTER_VALIDATE_EMAIL))
	    return TRUE;
	  else
	  	return FALSE;
	}
	
	/**
	 * generate random string
	 */
	public function randomString($length = 10) {
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$size = strlen( $chars );
		for( $i = 0; $i < $length; $i++ )
			$str .= $chars[ rand( 0, $size - 1 ) ];
		return $str;
	}
	
	/**
	 * function returns user Object by email, or FALSE if does not exists
	 */
	public function getUserByEmail($email) {
		$email = $this->mod->clear($email);
		$sql = "SELECT * FROM `users` WHERE `email` ='$email' AND `isDeleted` = FALSE;";
		$result = $this->mod->query($sql);
        if ($result)
			return $this->orm($result);
        else return false;
	}
	
	/**
	 * function deletes user by id
	 */
	public function deleteUser($userId) {
		$this->mod->log(11, 'User deleted', $userId);
		$sql = "UPDATE `users` SET `isDeleted` = TRUE WHERE `userId` = '$userId' ;";
		return $this->mod->query($sql);
	}
	
	/**
	 * Create a new user
	 */
	public function createUser($user) {
		if (!isset($user->role)) $user->role = 1;
		if (!isset($user->approvedBy)) $user->approvedBy = 0;
		$user->firstName = $this->mod->clear($user->firstName);
		$user->lastName = $this->mod->clear($user->lastName);
		$user->email = $this->mod->clear($user->email);
		$user->password = $this->mod->clear($user->password);		
		$user->created = time();
		$user->password = md5($user->password);
		$sql = "INSERT INTO `users` (`firstName`, `lastName`, `password`, `email`, `role`, `created`, `approvedBy`) VALUES (
			'".$user->firstName."','".$user->lastName."','".$user->password."','".$user->email."','".$user->role."',
					'".$user->created."','".$user->approvedBy."');";
		$result = $this->mod->query($sql);
		if (!$result) return false; // something went wrong
		// lets return user id
		$sql = "SELECT `userId` FROM `users` WHERE `email` = '".$user->email."' AND `password` = '".$user->password."';";
		$result = $this->mod->query($sql);
		$result = mysql_fetch_row($result);
		$this->mod->log(10, 'User created', $result[0]);
		return $result[0];
	}		
	
	/**
	 * Function checks if user with this email is already registered
	 */
	public function checkUser($email) {
		$email = $this->mod->clear($email);
		$sql = "SELECT COUNT(email) as c FROM `users` WHERE `email` = '$email' AND `isDeleted` = FALSE;";
		$result = $this->mod->query($sql);
		$result = mysql_fetch_row($result);
		$result = $result[0] > 0;
		return $result;
	}
	
	/**
	 * Function checks if user with this id is registered
	 */
	public function checkUserById($id) {
		$sql = "SELECT COUNT(userId) FROM `users` WHERE `userId` = '$id' AND `isDeleted` = FALSE AND `approvedBy` > 0;";
		$result = $this->mod->query($sql);
		$result = mysql_fetch_row($result);
		$result = $result[0] > 0;
		return $result;
	}
	
	/**
	 * function returns user Object by id, or false
	 * can return deleted and unapproved user
	 */
	public function getUserById($id) {
		$sql = "SELECT * FROM `users` WHERE `userId` = $id;";
		$result = $this->mod->query($sql);
		if ($result)
			return $this->orm($result);
        else return false;
	}
		
	/**
	 * function updates the user object by ID
	 */
	public function updateUserInfo($id, $user) {
		$user->email = $this->mod->clear($user->email);
		$user->firstName = $this->mod->clear($user->firstName);
		$user->lastName = $this->mod->clear($user->lastName);
		$sql = "UPDATE `users` SET `email`='".$user->email."',
				`firstName`='".$user->firstName."',
				`lastName`='".$user->lastName."'";
		$sql .= " WHERE userId = $id;";
		$this->mod->log(12, 'User info updated', $id);
		return $this->mod->query($sql);
	}

	/**
	 * function updates user password
	 */
	public function updateUserPassword($id, $password) {
		$password = $this->mod->clear($password);
		$password = md5($password);
		$sql = "UPDATE `users` SET " .
				"`password`='" . $password . "' ";
		$sql .= " WHERE userId = $id;";
		$this->mod->log(13, 'User password updated', $id);
		return $this->mod->query($sql);
	}
	
	/**
	 * function approves user 
	 */
	public function approveUser($id, $adminId, $approved = true) {
		if (!$approved) {
			$this->deleteUser($id);
			return -1; // whatever
		} else {
			$this->mod->log(20, 'Approved user', $id);
			$sql = "UPDATE `users` SET " .
					"`approvedBy`='" . $adminId . "' ";
			$sql .= " WHERE userId = $id;";
			return $this->mod->query($sql);
		}		
	}
	
	/**
	 * function checks if email + password are correct and return the User Object or FALSE
	 */
	public function checkCredentials($email, $password) {
		$password = $this->mod->clear($password);
		$password = md5($password);
		$sql = "SELECT * FROM `users` WHERE `email` = '$email' AND `password` = '$password' AND `isDeleted` = FALSE AND `approvedBy` > 0;";
		$result = $this->mod->query($sql);
		if ($result)
			return $this->orm($result);
        else return false;
	}
	
	/**
	 * Function returns setting object by name
	 */
	public function getSetting($name) {
		$sql = "SELECT * FROM `setting` WHERE `setting` = '$name';";
		$result = $this->mod->query($sql);
		if ($result)
			return $this->orm($result);
        else return false;
	}
	
	/**
	 * Function creates OR updates the setting
	 * type = 1 - INTEGER, 2 - STRING
	 */
	public function setSetting($name, $value, $type = 1) {
		$sql = "SELECT COUNT(id) as c FROM `setting` WHERE `setting` = '$name';";
		$result = $this->mod->query($sql);
		$result = mysql_fetch_row($result);
		$exists = $result[0] > 0;
		if ($exists) {
			$sql = "UPDATE `setting` SET `setting` = '$name', `".($type == 1 ? 'intVal' : 'charVal')."` = '$value'
				WHERE id = ".$result[0].";";
			return $this->mod->query($sql);			 
		} else {
			$sql = "INSERT INTO `setting` (`setting`, `".($type == 1 ? 'intVal' : 'charVal')."`) VALUES
					('$name', '$value');";
			return $this->mod->query($sql);
		}
	}
	
	
	
	
}

?>