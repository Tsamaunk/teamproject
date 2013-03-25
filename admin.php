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
	 include_once 'header.php';
	// MAKE SURE myId IS STILL SET
	?>
	
	<style>
	td a {
		margin-right:10px;
	}
	</style>
	
	<?php
	$con -> connect();
	$myUser = $con->getUserById($myId);
	$con -> close();
	
	$page = isset($_GET['page']) ? $_GET['page'] : 'profile';
	
	if ($myUser->role == 2) { // admin
		if ($page == 'profile') include_once 'admin/profile.php';
		if ($page == 'users') include_once 'admin/users.php';
		if ($page == 'log') include_once 'admin/log.php';
		if ($page == 'schedule') include_once 'admin/schedule.php';

	} elseif ($myUser->role == 1) { // user
		if ($page == 'profile') include_once 'admin/profile.php';
		if ($page == 'myschedule') include_once 'admin/myschedule.php';
	
	}
	
	// SOME FOOTER HERE
	 include_once 'footer.php';

?>