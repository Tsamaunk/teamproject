<?php

	include_once 'base.php';
	$hlp = new Helper();		// CREATE THE HELPER
	$con = new Controller(); 	// AND A CONTROLLER

	if (isset($_SESSION['userId']) && isset($_SESSION['userToken']) && $hlp->validToken($_SESSION['userId'], $_SESSION['userToken'])) {
			$myId = $_SESSION['userId'];
		} else {
			header('Location: login.php');
			exit;
		}
		
	// SOME HEADER HERE
	// include_once 'header.php';
	// MAKE SURE myId IS STILL SET
	
	$con -> connect();
	$myUser = $con->getUserById($myId);
	$con -> close();
	
	$page = isset($_POST['page']) ? $_POST['page'] : 'profile';
	
	if ($myUser->role == 2) { // admin
		if ($page == 'profile') include_once 'admin/profile.php';

	} elseif ($myUser->role == 1) { // user
		if ($page == 'profile') include_once 'admin/profile.php';
	
	}
	
	// SOME FOOTER HERE
	// include_once 'footer.php';

?>