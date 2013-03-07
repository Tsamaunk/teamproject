<?php
	include_once 'head.php';
	include_once 'topBar.php';
	include_once 'sideBar.php';
?>

<div id="content">
	<h1 align="center">Welcome to the UAlbany Residential life</h1>
	
	    <?php
	    if (!isset($_SESSION['userId']) || !isset($_SESSION['userToken']) || !$hlp->validToken($_SESSION['userId'], $_SESSION['userToken'])) {
			echo "<p align='center'>Please Login to continue using the portal.";
			echo "</p></div>"; 
			return;
	    }
		?>
		
		<?php
	
		
			// display calendar
		
		?>
	
</div>

<?php 
	include_once 'footer.php';
?>