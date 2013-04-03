<?php
define ("SITE_URL", "http://littleboxofhorrors.no-ip.org/");
define ("DOMAIN_URL", "littleboxofhorrors.no-ip.org");
define ("SALT", "h4h32jadsf2388hskjfdoi854324yeruw768435");
define ("TOKEN_EXP", "30"); // minutes until token is expired
define ("TOKEN_HALFLIFE", "15"); // minutes until token is renewed

class Model {
	private $db 	= 'ProjectSwitch';
	private $host 	= 'localhost';
	private $user	= 'root';
	private $pasw 	= 'root';
	private $con 	= null;
    private $lastQuery = '';
    private $logger = null;
    function __construct() {
    	Logger::configure('config.xml');
    	$this->logger = Logger::getLogger("main");
    }
	public function connect() {
		$this->con = mysql_connect($this->host, $this->user, $this->pasw); 
		if (!$this->con) {
			$this->logger->fatal('Could not connect: ' . mysql_error());
			$return = 'Could not connect: ' . mysql_error();
		} else {
			mysql_select_db($this->db, $this->con);
		}
		return isset($return) ? $return : null;		
	}
	public function isConnected() {
		return get_resource_type($this->con) == 'mysql link';
	}
	public function query($query, $save = true) {
		if (!$this->isConnected()) $this->connect();
		if ($save) 
        	$this->lastQuery = $query;
        $result = mysql_query($query, $this->con);
        return $result;
	}
	public function close() {
		mysql_close($this->con);		
	}
	public function clear($query) {
		return mysql_real_escape_string(stripslashes($query), $this->con); 	
	}
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
	 * 15 - undelete user
	 * 20 - approved user
	 * 
	 */
	
}


?>
