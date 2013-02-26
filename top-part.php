<div id="top-part">

<?php 
if (!isset($_SESSION['userId'])) {
	
		echo "<a href='login.php'>Login</a>";
		echo "<a href='signup.php'>Sign up</a>";
	
	} else {

		echo "<a href='logout.php'>Logout</a>";
	
	}

	?>
	
	
	
</div>
