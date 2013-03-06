<?php
include_once 'head.php';
include_once 'topBar.php';
include_once 'sideBar.php';
?>

<div id="content">
<center>
<h1 align="center">Welcome to the UAlbany Residential life</h1>

    <?php
    if (!isset($_SESSION['userId']) && !isset($_SESSION['userToken']) && !$hlp->validToken($_SESSION['userId'], $_SESSION['userToken'])) {
         echo "Please Login to continue using the portal.";
    } else {
		echo "Hi! You are now logged in.";
    }
?>
</div>