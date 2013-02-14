<?php 

	// this page is a landing page for processing the token clicks in emails
	// token should login and redirect user to main page after execution automatically	


	$token = isset($_GET['token']) ? $_GET['token'] : null;
	if (!$token) {
		die('access denied!');
	} 
	include_once 'base.php';
	$hlp = new Helper();
	$token = $hlp->execToken($token);
	if (!$token) {
		die('invalid or expired token!');
	}
	
	// and other shit
	
	
?>