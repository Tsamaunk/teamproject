<?php 

	// this page is a landing page for processing the token clicks in emails
	// token should login and redirect user to main page after execution automatically	

	$token = isset($_GET['token']) ? $_GET['token'] : null;
	if (!$token) {
		die('access denied!');
	} 
	include_once 'base.php';
	$hlp = new Helper();
	$token = $hlp->execToken($token);
	if (!$token) {
		die('invalid or expired token!');
	}
	
	function readRd() {
		$rd = array();
		$con = new Controller();
		$con->connect();
		$numberOfRd = $con->getSetting('numberOfRd');
		if (!$numberOfRd) die('"numberOfRd" property configured incorrectly!');
		for ($i = 1; $i <= $numberOfRd->intVal; $i++)
			$rd[] = $con->getSetting('rd_' . $i);
		$con->close();
		return $rd;
	}
	
	function showHeader() {
		echo "<html><body>
				<div style='display:block;width:600px;margin: 40px auto;'>";
	}
	
	function showFooter() {
		echo "</div></body></html>";
	}
	
	/* token->type = 1 - confirm email, 2 - approve user, 3 - decline user, 4 - confirm switch, 5 - decline switch,
	 * 6 - approve switch, 7 - disapprove switch, 8 - lost password
	 */
	if ($token->type == 1) { // confirm email
		$hlp -> destroy($token); // we dont want to notify RA several times
		$mailer = new Mailer();
		$rdSet = readRd();
		foreach ($rdSet as $rd) {		
			$approveToken = new stdClass();
			$approveToken->type = 2;
			$approveToken->raId1 = $token->raId1;
			$approveToken->rdId = $rd->intVal;
			$approveToken = $hlp -> makeToken($approveToken, $token->raId1, 'approveUserToken');
			
			$declineToken = new stdClass();
			$declineToken->type = 3;
			$declineToken->raId1 = $token->raId1;
			$declineToken->rdId = $rd->intVal;
			$declineToken = $hlp -> makeToken($declineToken, $token->raId1, 'declineUserToken');
			
			$mailer->compose(2, $approveToken, $declineToken);
			$mailer->mail();
		}
		showHeader();
		echo "Congrats, your email address has been confirmed.<br />";
		echo "We will notify you when your account approved by the administrator.<br />";
		echo "No further action is required.<br />";
		echo "<a href='index.php'>main page</a>";
		showFooter();
		exit;
	}
	
	if ($token->type == 2) { // approve user
		$hlp -> destroy($token);		
		// if user was deleted - we cannot approve him!!
		$con = new Controller();
		$con->connect();
		$user = $con->getUserById($token->raId1);
		if (!$user || $user->isDeleted) {
			$con->close();
			die('This user does not exist.');
		}
		if ($user->approvedBy > 0) {
			$con->close();
			die('This user account has been approved previously (Perhaps by other admin). Cannot approve it again.');
		}
		if (!$con->approveUser($token->raId1, $token->rdId)) die ('unknown error 76');
		$con->close();
		$mailer = new Mailer();
		$mailer->compose(3, $token);
		$mailer->mail();
		showHeader();
		echo "Congrats, user account has been confirmed.<br />";
		echo "User will be notified about it.<br />";
		echo "No further action is required.<br />";
		echo "<a href='index.php'>main page</a>";
		showFooter();
		exit;
	}
	
	if ($token->type == 3) { // decline user
		$hlp -> destroy($token);
		// if user is already approved - we cannot decline him!!!
		$con = new Controller();
		$con->connect();
		$user = $con->getUserById($token->raId1);
		if ($user->approvedBy > 0) {
			$con->close();
			die('This user account has been approved previously. Cannot decline the account. Please use the admin console to remove this user.');
		}
		if (!$con->approveUser($token->raId1, $token->rdId, false)) die ('unknown error 97');	
		$con->close();
		showHeader();
		echo "User account has been deleted.<br />";
		echo "It can be undeleted using <a href='admin.php?page=users'>admin console</a>.<br />";
		echo "User will NOT be notified about it.<br />";
		echo "No further action is required.<br />";
		echo "<a href='index.php'>main page</a>";
		showFooter();
		exit;
	}

	if ($token->type == 4) { // confirm switch
		$hlp -> destroy($token);
		$con = new Controller();
		$con->connect();
		$con->confirmSwitch($token->switchId, 1); // accept
		$con->close();
		$mailer = new Mailer();
		$rdSet = readRd();
		foreach ($rdSet as $rd) {
			$approveToken = new stdClass();
			$approveToken->type = 6;
			$approveToken->raId1 = $token->raId1;
			$approveToken->raId2 = $token->raId2;
			$approveToken->rdId = $rd->intVal;
			$approveToken = $hlp -> makeToken($approveToken, $token->raId1, 'approveSwitchToken');
				
			$declineToken = new stdClass();
			$declineToken->type = 7;
			$declineToken->raId1 = $token->raId1;
			$declineToken->raId2 = $token->raId2;
			$declineToken->rdId = $rd->intVal;
			$declineToken = $hlp -> makeToken($declineToken, $token->raId1, 'disapproveSwitchToken');
				
			$mailer->compose(6, $approveToken, $declineToken);
			$mailer->mail();
		}
		showHeader();
		echo "Thanks for accepting the switch.<br />";
		echo "We will notify you when this switch is approved by the administrator.<br />";
		echo "No further action is required.<br />";
		echo "<a href='index.php'>main page</a>";
		showFooter();
		exit;
	}
	
	if ($token->type == 5) { // decline switch
		if (isset($_POST['reason'])) {
			$hlp -> destroy($token);
			$con = new Controller();
			$con->connect();
			$con->confirmSwitch($token->switchId, 2, $_POST['reason']); // decline
			$con->close();
			$mailer = new Mailer();
			$mailer->compose(5, $token);
			$mailer->mail();
			showHeader();
			echo "The switch request has beed denied.<br />";
			echo "No further action is required.<br />";
			echo "<a href='index.php'>main page</a>";
			showFooter();
			exit;	
		} else {
			showHeader();
			echo "<form method='post' action='token.php?token=".$token->token."'>";
			echo "Prease specity the reason for denial: <br/><input type='text' name='reason'/> [optional]";
			echo "<br><button type='submit'>Submit</button>";
			echo "</form>";
			showFooter();
			exit;
		}
	}

	if ($token->type == 6) { // approve switch
		$hlp -> destroy($token);
		$con = new Controller();
		$con->connect();
		$con->confirmSwitch($token->switchId, 3); // approve
		$con->close();
		$mailer = new Mailer();
		$mailer->compose(7, $token);
		$mailer->mail();
		$token->raId1 = $token->raId2; // to make sure both RAs receive the email 
		$mailer->compose(7, $token);
		$mailer->mail();
		showHeader();
		echo "The switch request has beed approved.<br />";
		echo "Both RAs will be informed about it.<br />";
		echo "No further action is required.<br />";
		echo "<a href='index.php'>main page</a>";
		showFooter();
		exit;	
	}
	
	if ($token->type == 7) { // disapprove switch
		if (isset($_POST['reason'])) {
			$hlp -> destroy($token);
			$con = new Controller();
			$con->connect();
			$con->confirmSwitch($token->switchId, 4); // disapprove
			$con->close();
			$mailer = new Mailer();
			$mailer->compose(8, $token);
			$mailer->mail();
			$token->raId1 = $token->raId2; // to make sure both RAs receive the email
			$mailer->compose(8, $token);
			$mailer->mail();
			showHeader();
			echo "The switch request has beed declined.<br />";
			echo "Both RAs will be informed about it.<br />";
			echo "No further action is required.<br />";
			echo "<a href='index.php'>main page</a>";
			showFooter();
			exit;
		} else {
			showHeader();
			echo "<form method='post' action='token.php?token=".$token->token."'>";
			echo "Prease specity the reason for disapproving: <br/><input type='text' name='reason'/> [optional]";
			echo "<br><button type='submit'>Submit</button>";
			echo "</form>";
			showFooter();
			exit;
		}
	}
	
	if ($token->type == 8) { // lost password
		if (isset($_POST['password']) && strlen($_POST['password']) > 5) {
			$con = new Controller();
			$con -> connect();
			$con -> updateUserPassword($token->raId1, $_POST['password']);
			$con -> close();
			$hlp -> destroy($token);
			showHeader();
			echo "You can log in with your new password now.<br />";
			echo "<a href='index.php'>main page</a>";
			showFooter();
			exit;
		} else {
			showHeader();
			if (isset($_POST['password'])) {echo "Password must be 6 symbols or more!<br>";}
			echo "<form method='post' action='token.php?token=".$token->token."'>";
			echo "New Password: <input type='password' name='password'/>";			
			echo "<br><button type='submit'>Submit</button>";
			echo "</form>";
			showFooter();
			exit;
		}
	}
	
	
?>
