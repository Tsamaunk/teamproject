

<?php 
	
	// this part must be executed before headers are sent
	
	session_start();

	if (isset($_POST['trigger'])) {
		$_SESSION['userId'] = 1;
		header("Location: index.php");
		exit;
	}
?>


<?php 
	include_once 'header.php';
	include_once 'sidebar.php';
	
	include_once 'top-part.php';
	
?>



		
		<div id="content">
		
		
		<form method="post" action="login.php" class="la-form">
		
			<h2>Please enter your username and password:</h2>
			<input type="hidden" name="trigger" value="1" />
			<label>Username:</label>
			<input type="text" name="username" />
			<br>
			<label>Password:</label>
			<input type="password" name="password" />
			<br>
			<label></label>
			<button type="submit">Log in</button>	
		
		
		</form>	
		
		
		
		</div>
		
		
<?php 
	
	include_once 'footer.php';

?>