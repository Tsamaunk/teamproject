<?php
	include_once 'head.php';
	include_once 'topBar.php';
	include_once 'sideBar.php';
?>

<div id="content">
	<h1 style="text-align: center; margin-top: 100px;">Welcome to the UAlbany Residential life</h1>
	
	    <?php
	    if (!isset($_SESSION['userId']) || !isset($_SESSION['userToken']) || !$hlp->validToken($_SESSION['userId'], $_SESSION['userToken'])) :
			echo "<p style='text-align: center;'>Please Login to continue using the portal.";
			echo "</p></div>"; 
	     else :
		?>
		
		<?php
	
		
			// display calendar
		
		?>
	
</div>
	
	<?php endif;?>
	
<?php 
	include_once 'footer.php';
?>