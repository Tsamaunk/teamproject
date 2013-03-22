<?php
/**
 * This class contains set of functions for database interactions.
 */

define ("SITE_URL", "http://76.78.63.97/");
define ("SALT", "32745726357428736482734");

class Model {

	private $db 	= 'ProjectSwitch';
	private $host 	= 'localhost';
	private $user	= 'root';
	private $pasw 	= 'root';
	private $con 	= null;
    private $lastQuery = '';
	
	/**
	 * Function opens the database connection
	 */
	public function connect() {
		$this->con = mysql_connect($this->host, $this->user, $this->pasw); 
		if (!$this->con)
			$return = 'Could not connect: ' . mysql_error();
		else mysql_select_db($this->db, $this->con);
		return isset($return) ? $return : null;		
	}
		
	/**
	 * check if connection is correct
	 */
	public function isConnected() {
		return get_resource_type($this->con) == 'mysql link';
	}
	
	/**
	 * Function executed the query on the database
	 * !! connection must be open !!
	 * @param  query string
	 * @return result of the query.  
	 */
	public function query($query, $save = true) {
		if (!$this->isConnected()) $this->connect();
		if ($save) 
        	$this->lastQuery = $query;
        $result = mysql_query($query, $this->con);
        return $result;
	}
	
	/**
	 * Function closes existing connection
	 */
	public function close() {
		mysql_close($this->con);		
	}
	
	/**
	 * Function returns query cleared from the possible SQL injections
	 * @param query string
	 */
	public function clear($query) {
		return mysql_real_escape_string(stripslashes($query), $this->con); 	
	}
	
	/**
	 * Function saves data to log file
	 */
	public function log($event, $description, $userId2 = null, $needConnection = false) {
		if ($needConnection) {
			$this->connect();
		}
		$sql = "INSERT INTO `log` (`event`, `description`, `userId1`, `userId2`, `created`) VALUES (" .
				"'$event', '$description', " .
				"'" . (isset($_SESSION['userId'])?$_SESSION['userId']:'') . "', " .
				"'$userId2', " .
				"'" . time() . "');";
		$this->query($sql, false);
		if ($needConnection) {
			$this->close();
		}
	}
	/*
	 * Description of LOG events:
	 * 10 - user created
	 * 11 - delete user
	 * 12 - user info update
	 * 13 - user password updated
	 * 14 - user role updated
	 * 20 - approved user
	 * 
	 */
	
}


?>
