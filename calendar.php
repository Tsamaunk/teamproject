<?php
<?php

include_once 'base.php';
$hlp = new Helper();  	// CREATE THE HELPER
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
  
  
  
?>
