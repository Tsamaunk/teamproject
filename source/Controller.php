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
	public function orm($resource, $forceMulti = false) {
		$array = $this->convert($resource);
		if (count($array) == 1 && !$forceMulti) {
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
	  if (filter_var($email, FILTER_VALIDATE_EMAIL))
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
	 * function updates user role
	 */
	public function updateUserRole($id, $role = 1) {
		$sql = "UPDATE `users` SET " .
				"`role`='" . $role . "' ";
		$sql .= " WHERE userId = $id;";
		$this->mod->log(14, 'User role updated', $id);
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

	// =============================================================================================
	//	Days

	/**
	 * Function returns the schedule for the month for all users
	 */
	public function getSchedule($month) {
		$sql = "SELECT * FROM `days` WHERE `month` = '$month';";
		$result = $this->mod->query($sql);
		if ($result)
			return $this->orm($result, true);
		else return false;
	}

	/**
	 * Function returns the schedule for the month for the particular user
	 */
	public function getUserSchedule($userId, $month) {
		$sql = "SELECT * FROM `days` WHERE `month` = '$month' AND `userId` = '$userId';";
		$result = $this->mod->query($sql);
		if ($result)
			return $this->orm($result, true);
		else return false;
	}

	/**
	 * Function returns day-person link
	 */
	public function getDay($id) {
		$sql = "SELECT * FROM `days` WHERE `id` = '$id';";
		$result = $this->mod->query($sql);
		if ($result)
			return $this->orm($result);
		else return false;
	}

	/**
	 * Function adds new day-person link
	 */
	public function addDay($day) { // TODO NOT TESTED
		if (!isset($day->fromTime)) $day->fromTime = 0;
		if (!isset($day->toTime)) $day->toTime = 0;
		if (!isset($day->month)) $day->month = $day->assignedDate->format('M');
		$day->created = time();		
		$sql = "INSERT INTO `days` (`userId`, `assignedDate`, `month`, `fromTime`, `toTime`, `created`) VALUES (
			'".$day->userId."','".$day->assignedDate->format("Y-M-D")."','".$day->month."','".$day->fromTime."','".$day->toTime."',
			'".$day->created."');";
		$result = $this->mod->query($sql);
		return $result;
	}

	/**
	 * Function removes the day-person link
	 */
	public function removeDay($id) {
		$sql = "DELETE FROM `days` WHERE `id` = '$id';";
		$result = $this->mod->query($sql);
		return $result;	
	}

	/**
	 * function returns the last 500 lines of log as an ARRAY
	 */
	public function getLog($limit = 500) {
		$sql = "SELECT * FROM `log` ORDER BY `created` DESC LIMIT $limit;";
		$result = $this->mod->query($sql);
		return $this->convert($result);
	}

	// =============================================================================================
	//	Switches

	/**
	 * Function returns set of switches for user or for all users
	 */
	public function getListOfSwitches($userId = null) {
		if ($userId == null)
			$sql = "SELECT * FROM `switch` ORDER BY `date1` DESC;";
		else
			$sql = "SELECT * FROM `switch` WHERE `userId1` = '$userId' OR `userId2` = '$userId' ORDER BY `date1` DESC;";
		$result = $this->mod->query($sql);
		if ($result)
			return $this->orm($result);
		else return false;
	}

	/**
	 * returns number of new switches 
	 * $userId can be a user ID or NULL - returns all new switches
	 */
	public function getNumberOfNewSwitches($userId = null) {
		if ($userId == null)
			$sql = "SELECT COUNT(*) as c FROM `switch` WHERE `status` = 0;";
		else
			$sql = "SELECT COUNT(*) as c FROM `switch` WHERE `userId1` = '$userId' OR `userId2` = '$userId' AND `status` = 0;";
		$result = $this->mod->query($sql);
		$result = mysql_fetch_row($result);
		return $result[0];
	}

	/**
	 * function creates the new switch
	 */
	public function addSwitch($switch) {
		if (!isset($switch->fromTime1)) $day->fromTime1 = 0;
		if (!isset($switch->toTime1)) $day->toTime1 = 0;
		if (!isset($switch->fromTime2)) $day->fromTime2 = 0;
		if (!isset($switch->toTime2)) $day->toTime2 = 0;
		if (!isset($switch->status)) $day->status = 0;
		$switch->reason = $this->mod->clear($switch->reason);
		$switch->created = time();
		$sql = "INSERT INTO `switch` (`userId1`, `userId2`, `date1`, `date2`, `fromTime1`, `fromTime2`, `created`,
			`toTime1`,`toTime2`,`status`,`reason` ) VALUES (
			'".$switch->userId1."','".$switch->userId2."','".$switch->date1->format("Y-M-D")."','".$switch->date2->format("Y-M-D")."','".$switch->fromTime1."',
			'".$switch->fromTime2."','".$switch->created."','".$switch->toTime1."','".$switch->toTime2."','".$switch->status."','".$switch->reason."');";
		$result = $this->mod->query($sql);
		return $result;
	}

	/**
	 * function returns the particular switch
	 */
	public function getSwitch($id) {
		$sql = "SELECT * FROM `switch` WHERE `id` = '$id';";
		$result = $this->mod->query($sql);
		if ($result)
			return $this->orm($result);
		else return false;
	}

	/**
	 * Function confirms/declines/approves/disapproves the switch
	 */
	public function confirmSwitch($id, $confirm, $reason = '') {
		$sql = "UPDATE `switch` SET `status` = '$confirm' ".($reason==''?'':" `reason` = '".$reason."' ")." 
		WHERE `id` = '$id';";
		$result = $this->mod->query($sql);
		return $result;
	}

	// =============================================================================================
	//	Messages

	/**
	 * Function returns the list of dialogs for the user
	 */
	public function getMyDialogs($userId) {
		$sql = "SELECT * FROM `message` WHERE `fromId` = '$userId' OR `toId` = '$userId' ORDER BY `created` DESC;";
		$result = $this->mod->query($sql);
		if ($result)
			return $this->orm($result, true);
		else return false;
	}

	/**
	 * Function returns one dialog between two users
	 */
	public function getDialog($userId1, $userId2) {
		$sql = "SELECT * FROM `message` WHERE 
			(`fromId` = '$userId1' AND `toId` = '$userId2') OR
			(`fromId` = '$userId2' AND `toId` = '$userId1') ORDER BY `created` DESC;";
		$result = $this->mod->query($sql);
		if ($result)
			return $this->orm($result, true);
		else return false;
	}

	/**
	 * Function creates the new message
	 */
	public function addMessage($message) { // TODO NOT TESTED
		$message->subject = $this->mod->clear($message->subject);
		$message->text = $this->mod->clear($message->text);
		$message->created = time();
		$sql = "INSERT INTO `message` (`fromId`, `toId`, `subject`, `text`, `created`) VALUES (
			'".$message->fromId."','".$message->toId."','".$message->subject."','".$message->text."','".$message->created."');";
		$result = $this->mod->query($sql);
		return $result;
	}

	/**
	 * Function marks deleted all messages between two users
	 */
	public function deleteDialog($userId1, $userId2) {
		$sql = "UPDATE `message` SET `isDeleted` = TRUE WHERE 
			(`fromId` = '$userId1' AND `toId` = '$userId2') OR
			(`fromId` = '$userId2' AND `toId` = '$userId1');";
		$result = $this->mod->query($sql);
		return $result;
	}

	/**
	 * Function marks read all messages between two users
	 * I AM - userId2!!!
	 * So messages sent TO ME must be read
	 */
	public function markDialogRead($userId1, $userId2) {
		$sql = "UPDATE `message` SET `read` = TRUE WHERE 
			`fromId` = '$userId1' AND `toId` = '$userId2';";
		$result = $this->mod->query($sql);
		return $result;
	}

	/**
	 * Function returns number of new messages sent TO the user 
	 */
	public function getNumNewMessages($userId) {
		$sql = "SELECT COUNT(*) as c FROM `message` WHERE `toId` = '$userId' AND `isDeleted` = FALSE;";
		$result = $this->mod->query($sql);
		$result = mysql_fetch_row($result);
		return $result[0];
	}

	// =============================================================================================
	// 	Additional Admin Functions

	/**
	 * Function clears the month
	 */
	public function clearMonth($month) {

	}

	/**
	 * Function clears the messages
	 */
	public function clearMessages() {

	}

	/**
	 * Function clears switch history for month
	 */
	public function clearSwitches($month) {

	}


}

?>
