<div id="topBar">
	<a href="index.php">Homepage</a>

	<?php 
	if (!isset($_SESSION['userToken'])) {
		
			echo "<span style=\"float:right;\"><a href=\"login.php\">Login</a> &middot;";
			echo " <a href=\"signup.php\">Sign Up</a></span>";
		
		} else {
	
			echo "<span style=\"float:right;\"><a href=\"logout.php\">Logout</a>&nbsp;</span>";
		
		}
	
		?>
</div>