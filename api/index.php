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
		if (!$user) {
			echo json_encode(array('success' => false, 'error' => 'Incorrect email address / password or user is pending approval!'));
			exit;
		}
		$_SESSION['userId'] = $user->userId;
		$_SESSION['userRole'] = $user->role;
		$_SESSION['userToken'] = $hlp->createUserToken($_SESSION['userId']);
		echo json_encode(array('success' => true, 'error' => null));
		exit;		
	}
	
	if (isset($_GET['logout'])) {
		unset($_SESSION['userId']);
		unset($_SESSION['userToken']);
		unset($_SESSION['userRole']);
		echo json_encode(array('success' => true, 'error' => null));
		exit;
	}
	
	if (isset($_GET['lost_password'])) {
		if(!$_POST['email']) {
			echo json_encode(array('success' => false, 'error' => 'Please provide the Email address.'));
			exit;
		}
		$con->connect();
		$user = $con->getUserByEmail($_POST['email']);
		$con->close();
		$token = new stdClass();
		$token -> type = 8;
		$token -> raId1 = $user->userId;
		$token = $hlp -> makeToken($token, $user->userId, 'lostPasswordToken');
		$mailer = new Mailer();
		$mailer -> compose (9, $token);
		$mailer -> mail();
		echo json_encode(array('success' => true, 'error' => null, 'userId' => $result));
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
		$token = new stdClass();
		$token -> type = 1;
		$token -> raId1 = $result;
		$token = $hlp -> makeToken($token, $result, 'confirmMailToken');
		$mailer = new Mailer();
		$mailer -> compose (1, $token);
		$crap = $mailer -> mail(false);
		echo json_encode(array('success' => true, 'error' => null, 'userId' => $result, 'output' => $crap));
		exit;		
	}
	
	// CHECK THE ACCESS
	
	if (isset($_SESSION['userId']) && isset($_SESSION['userToken']) && $hlp->validToken($_SESSION['userId'], $_SESSION['userToken'])) {
		$myId = $_SESSION['userId'];
	} else {
		echo json_encode(array('success' => false, 'error' => 'Access denied: Invalid token, no token, or expired token.'));
		exit;
	}
	
	if (isset($_GET['getUser'])) {
		$con->connect();
		$user = $con->getUserById($myId);
		$con->close();
		unset($user->password);
		echo json_encode(array('success' => true, 'error' => null, 'user' => $user));
		exit;
	}
	
	// ==================================
	//			INTERNAL MAILING
	// ==================================
	if (isset($_GET['sendMail'])) {
		if(!$_POST['to'] || !$_POST['text']) {
			echo json_encode(array('success' => false, 'error' => 'You must specify the content of the message.'));
			exit;
		}
		$message = new stdClass();
		$message->fromId = $myId;
		$message->toId = $_POST['to'];
		$message->subject = $_POST['subject'] ? $_POST['subject'] : 'No subject';
		$message->text= $_POST['text'];
		$con->connect();
		$con->addMessage($message);
		$con->close();
		echo json_encode(array('success' => true, 'error' => null));
		exit;
	}
	
	if (isset($_GET['getDialogs'])) {
		$con->connect();
		$res = $con->getMyDialogs($myId);
		$ids = array();
		$dialogs = array();
		foreach ($res as $r) {
			if($r->fromId == $myId && !in_array($r->toId, $ids)) {
				$rt = $r;
				$tempUser = $con->getUserById($r->toId);
				$rt->name = $tempUser->firstName . ' ' . $tempUser->lastName;
				$rt->created = $rt->created? date("h:i a m/d/y",$rt->created) : '';
				$dialogs[] = $rt;
				$ids[] = $r->toId;
			}
			if($r->toId == $myId && !in_array($r->fromId, $ids)) {
				$rt = $r;
				$tempUser = $con->getUserById($r->fromId);
				$rt->name = $tempUser->firstName . ' ' . $tempUser->lastName;
				$rt->created = $rt->created? date("h:i a m/d/y",$rt->created) : '';
				$dialogs[] = $rt;
				$ids[] = $r->fromId;
			}
		}
		$con->close();
		echo json_encode(array('success' => true, 'error' => null, 'dialogs' => $dialogs));
		exit;
	}
	
	if (isset($_GET['getDialogById'])) {
		if(!$_POST['userId']) {
			echo json_encode(array('success' => false, 'error' => 'You must specify the id  of the user.'));
			exit;
		}
		$con->connect();
		$res = $con->getDialog($myId, $_POST['userId'], $_POST['limit']);
		$user = $con->getUserById($_POST['userId']);
		$con->markDialogRead($_POST['userId'], $myId);
		$con->close();
		foreach ($res as $rt)
			$rt->created = $rt->created? date("h:i a m/d/y",$rt->created) : '';
		echo json_encode(array('success' => true, 'error' => null, 'dialog' => $res, 'name' => $user->firstName . ' ' . $user->lastName));
		exit;
	}
	
	if (isset($_GET['deleteDialog'])) {
		if(!$_POST['userId']) {
			echo json_encode(array('success' => false, 'error' => 'You must specify the id  of the user.'));
			exit;
		}
		$con->connect();
		$con->deleteDialog($myId, $_POST['userId']);
		$con->close();
		echo json_encode(array('success' => true, 'error' => null));
		exit;
	}
	
	if (isset($_GET['getUserList'])) {
		$con->connect();
		$users = $con->getAllAliveUsers();
		$con->close();
		$output = array();
		foreach ($users as $u)
			if ($u->userId != $myId)
				$output[] = array('id' => $u->userId, 'name' => $u->firstName . " " . $u->lastName);
		echo json_encode(array('success' => true, 'users' => $output, 'size' => count($output)));
		exit;
	}
	
	// ==================================
	//			SWITCHES
	// ==================================
	
	
	// ==================================
	//			CALENDARS AND SCHEDULES
	// ==================================
	
	if (isset($_GET['getCalendar'])) {
		$today = new DateTime();
		$month = isset($_POST['month']) ? (int)$_POST['month'] : (int)$today->format('m');
		
		$firstDay = new DateTime($today->format('Y') . '-' . $month . '-1');
		$maxDays = $firstDay->format('t');
		$firstDay = $firstDay -> format('N');
		if ($firstDay ==7) $firstDay = 0;
		$firstDay = 1 - $firstDay;
		
		$con->connect();
		$sch = $con->getSchedule($month);
		$con->close();
		
		$cal = array();
		foreach ($sch as $s) {
			$assDate = new DateTime($s->assignedDate);
			$ass = $assDate->format('m_d'); // date is month_day, 4_29
			if ($s->type == 1)
				$cal[$ass]['ra'][] = $s;
			elseif($s->type == 2)
				$cal[$ass]['rd'] = $s;
		}
		
		echo json_encode(array('success' => true, 'calendar' => $cal, 'firstDay' => $firstDay. 'maxDays' => $maxDays));
		exit;
	}
	

?>
