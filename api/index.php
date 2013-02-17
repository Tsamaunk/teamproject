<?php 

	// API INTERFACE
	
	header('Cache-Control: no-cache, must-revalidate');
	header('Content-type: application/json');
	
	include_once '../base.php';
	
	$hlp = new Helper();
	$con = new Controller();	
	
	// =========================================
	// 			LOGIN   AND   SIGN UP
	// =========================================
	
	if (isset($_GET['login'])) {
		if(!$_POST['email'] || !$_POST['password']) {
			echo json_encode(array('success' => false, 'error' => 'Please provide the Email address and Password.'));
			exit;
		}
		$email = $_POST['email'];
		$password = $_POST['password'];
		$con->connect();
		
		$userExist = $con->checkUser($email);
		
		$user = $con->checkCredentials($email, $password);
		$con->close();
		if (!$user && $userExist) {
			echo json_encode(array('success' => false, 'error' => 'User is not approved yet!'));
			exit;
		} elseif (!$user) {
			echo json_encode(array('success' => false, 'error' => 'Incorrect email address / password!'));
			exit;
		}
		
		// correct user, set up the session
		$_SESSION['userId'] = $user->id;
		$_SESSION['userToken'] = $hlp->createUserToken($_SESSION['userId']);
		
		echo json_encode(array('success' => true, 'error' => null));
		exit;		
	}
	
	if (isset($_GET['logout'])) {
		unset($_SESSION['userId']);
		unset($_SESSION['userToken']);
		echo json_encode(array('success' => true, 'error' => null));
		exit;
	}
		
	
	if (isset($_GET['signup'])) {
		if(!$_POST['email'] || !$_POST['password']) {
			echo json_encode(array('success' => false, 'error' => 'Please provide the Email address and Password.'));
			exit;
		}
		if(!$_POST['firstName'] || !$_POST['lastName']) {
			echo json_encode(array('success' => false, 'error' => 'Please provide First and Last name.'));
			exit;
		}
		
		$user = new stdClass();
		$user->firstName = $_POST['firstName'];
		$user->lastName = $_POST['lastName'];
		$user->email = $_POST['email'];
		$user->password= $_POST['password'];
		
		if(strlen($user->password) < 6) {
			echo json_encode(array('success' => false, 'error' => 'Password is too short! Minimum 6 symbols.'));
			exit;
		}
		if(!$con->validate($user->email)) {
			echo json_encode(array('success' => false, 'error' => 'Email address is not valid.'));
			exit;
		}
		$con->connect();
		$userExists = $con->checkUser($user->email);
		if ($userExists) {
			$con->close();
			echo json_encode(array('success' => false, 'error' => 'This email address is already registered!'));
			exit;
		}
		
		$result = $con->createUser($user);
		$con->close();
		if (!$result) {
			echo json_encode(array('success' => false, 'error' => 'Cannot registed user at this time.'));
			exit;
		}
				
		// create 'confirm email' token
		$token = new stdClass();
		$token -> type = 1;
		$token -> raId1 = $result;
		$token = $hlp -> makeToken($token, $result, 'confirmMailToken');
		// send this token to the new user to confirm email
		
		$mailer = new Mailer();
		$mailer -> compose (1, $token);
		$mailer -> mail();

		// after user email is confirmed - in token.php - send email to RD to confirm the user
		
		echo json_encode(array('success' => true, 'error' => null, 'userId' => $result));
		exit;		
	}

	
	
	
	
	// CHECK THE ACCESS
	
	if (isset($_SESSION['userId']) && isset($_SESSION['userToken']) && $hlp->validToken($_SESSION['userId'], $_SESSION['userToken'])) {
		$myId = $_SESSION['userId'];
	} else {
		echo json_encode(array('success' => false, 'error' => 'Access denied: Invalid token or no token.'));
		exit;
	}
	
	
	

?>