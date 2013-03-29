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
include_once 'topbar.php'; ?>

<div class="contaner-bottom">
<?php include_once 'sidebar.php'; ?>
	<div class="content">
	<style>
	td a {
		margin-right: 10px;
	}
	</style>
	
	<?php
	$con -> connect();
	$myUser = $con->getUserById($myId);
	$con -> close();
	
	$page = isset($_GET['page']) ? $_GET['page'] : 'profile';
	
	echo "<div id=\"adminConsole\" class=\"content-box\">\n";
	
	if ($page == 'profile') include_once 'admin/profile.php';
	if ($page == 'users') include_once 'admin/users.php';
	if ($page == 'log') include_once 'admin/log.php';
	if ($page == 'schedule') include_once 'admin/schedule.php';
	if ($page == 'myschedule') include_once 'admin/myschedule.php';
	
	echo "</div>\n";
	
	?>
	</div>
</div>
<?php 
	// SOME FOOTER HERE
	include 'footer.php';
?>