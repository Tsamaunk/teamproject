<?php
	/**
	 * This file connects all the other files  
	 */
	
	define ("ROOT", dirname(__FILE__) . '/'); //  /teamproject/
	
	if(session_id() == '') {
		session_start();
	}
	//error_reporting(0);

	include_once('apache-log4php-2.3.0/src/main/php/Logger.php');
	
	include_once 'source/Model.php';
	include_once 'source/Controller.php';
	include_once 'source/Helper.php';
	include_once 'source/Mailer.php';
	
	function fixObject (&$object)
	{
		if (!is_object ($object) && gettype ($object) == 'object')
			return ($object = unserialize (serialize ($object)));
		return $object;
	}
	
?>