<?php

if (!isset($myUser)) {
	die('unauthorized access');
}

if ($_POST['update'] && $_POST['password'] && strlen($_POST['password']) > 6) {
	$con->connect();
	if ($con->updateUserPassword($myUser->userId, $_POST['password']))
		$updated = true;
	$con->close();
}

if ($_POST['update']) {
	$user = new stdClass();
	$user -> email = $_POST['email'];
	$user -> firstName = $_POST['firstName'];
	$user -> lastName = $_POST['lastName'];
	$con->connect();
	if ($con->updateUserInfo($myUser->userId, $user)) {
		$updated = true;
		$myUser->email = $user->email;
		$myUser->firstName = $user->firstName;
		$myUser->lastName = $user->lastName;		
	}
	$con->close();	
}
?>

<h1>My Profile</h1>
<?php 
	if (isset($updated))
		echo "<p>Updated succesfully</p>";
?>

<form method="post" action="?page=profile">
	<input type="hidden" name="update" value="1" />
	<label>User type:</label>
	<input type="text" readonly="readonly" value="<?php echo $myUser->type == 2 ? "Administrator" : "Regular";?>" /><br />
	<label>Approved by:</label>
	<input type="text" readonly="readonly" value="<?php 
		$admin = $con->getUserById($myUser->approvedBy);
		echo $admin->firstName . " " . $admin->lastName;?>" /><br />
	<br />
	<label>First name:</label>
	<input type="text" required="required" name="firstName" value="<?php echo $myUser->firstName;?>" /><br />
	<label>Last name:</label>
	<input type="text" required="required" name="lastName" value="<?php echo $myUser->lastName;?>" /><br />
	<label>E-mail:</label>
	<input type="email" required="required" name="email" value="<?php echo $myUser->email;?>" /><br />
	<br />
	<label>Password:</label>
	<input type="password" id="pasw1" name="password" value="" /><br />
	<label>Confirm password:</label>
	<input type="password" id="pasw2" value="" /> <label id="dontmatch" style="display:none;">Passwords don't match!</label><br />	
	<button type="button" onclick="javascript:submitForm();" >Update</button>
</form>

<script>
	var allowPost = true;
	$(document).ready(function(){
		$("#dontmatch").hide();
		$("#pasw1").keyup(function(){
			allowPost = false;
			if ($("#pasw2").val().length > 0 && $("#pasw2").val()!=$("#pasw1").val()) {
				$("#dontmatch").fadeIn(200);
			} else {$("#dontmatch").hide();}
		});	
		$("#pasw2").keyup(function(){
			allowPost = false;
			if ($("#pasw2").val()!=$("#pasw1").val()) {
				$("#dontmatch").fadeIn(200);
				allowPost = false;
			} else {$("#dontmatch").hide();allowPost = true;}
		});	
	});
	function submitForm() {
		if (!allowPost) return false;
		else $("form").submit();
	}
</script>
