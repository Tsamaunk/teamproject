<?php 

	class Mailer {
		
		private $subject;
		private $address;
		private $from = "Project Switch <noreply@none.com>";
		private $content;
		
		/**
		 * @param int $type
		 * type = 1 - confirmation email
		 * 			user ID is in $token->raId1;
		 * type = 2 - email to RD to confirm the user
		 * 			RD ID is in $token->rdId, 
		 * 			user ID is in $token->raId1;
		 * type = 3 - notification email to new user $token->raId1 if he's confirmed by RD
		 * type = 4 - switch initiated by $token->raId1 - send mail to $token->raId2 to confirm
		 * type = 5 - switch declined by user $token->raId2 - send mail to $token->raId1
		 * type = 6 - switch initiated by $token->raId1 confirmed by user $token->raId2 - send mail to RD id $token->rdId
		 * type = 7 - switch approved by RD, send email to $token->raId1 -- this shall be called twice for each RA
		 * type = 8 - switch declined by RD, send email to $token->raId1 -- this shall be called twice for each RA
		 * type = 9 - lost password email. user who lost password is in $token->raId1
		 * @param token object $token
		 */
		public function compose($type, $token1, $token2 = null, $text = '') {
			// Open connection to database
			$con = new Controller();
			$con->connect();

			switch ($type) {
				case 1:
					$ra1 = $con->getUserById($token1->raId1);
					$this->address = $ra1->email;
					$this->subject = "Confirmation Notification";
					$this->content = "Dear " . $ra1->firstName . ",<br><br>";
					$this->content .= "Please confirm your email by clicking <a href='" . SITE_URL . "token.php?token=" . $token1->token . "'> here</a>.<br><br>";
					$this->content .= "Thanks,<br>The ProjectSwitch Team";
					break;
				case 2:
					$rd = $con->getUserById($token1->rdId);
					$ra1 = $con->getUserById($token1->raId1);
					$this->address = $rd->email;
					$this->subject = "User Confirmation Notification";
					$this->content = "Dear " . $rd->firstName . ",<br><br>";
					$this->content .= "User " . $ra1->firstName . " " . $ra1->lastName . " needs confirmation: <a href='" . SITE_URL . "token.php?token=" . $token1->token . "'>CONFIRM</a>  <a href='" . SITE_URL . "token.php?token=" . $token2->token . ">DENY</a><br><br>";
					$this->content .= "Thanks,<br>The ProjectSwitch Team";
					break;
				case 3:
					$ra1 = $con->getUserById($token1->raId1);
					$this->address = $ra1->email;
					$this->subject = "Confirmation Approved";
					$this->content = "Dear " . $ra1->firstName . ",<br><br>";
					$this->content .= "Congratulations, your email has been approved! Please login <a href='" . SITE_URL . "login.php" . "'>here</a>.<br><br>";
					$this->content .= "Thanks,<br>The ProjectSwitch Team";
					break;
				case 4:
					$ra1 = $con->getUserById($token1->raId1);
					$ra2 = $con->getUserById($token1->raId2);
					$this->address = $ra2->email;
					$this->subject = "Switch Request Notification";
					$this->content = "Dear " . $ra2->firstName . ",<br><br>";
					$this->content = $ra1->firstName . " wants to switch a duty day with you. Please <a href='" . SITE_URL . "login.php" . "'>login</a> to respond to this request.<br><br>";
					$this->content .= "Thanks,<br>The ProjectSwitch Team";
					break;
				case 5:
					$ra1 = $con->getUserById($token1->raId1);
					$ra2 = $con->getUserById($token1->raId2);
					$this->address = $ra1->email;
					$this->subject = "Switch Request Notification";
					$this->content = "Dear " . $ra1->firstName . ",<br><br>";
					$this->content = $ra2->firstName . " has declind your requested duty switch. <a href='" . SITE_URL . "login.php" . "'>Login</a> to view any accompanying messages.<br><br>";
					$this->content .= "Thanks,<br>The ProjectSwitch Team";
					break;
				default:
					// Fatal error.  Shoot yourself in the foot and try again, dumbass.
			}		

			$con->close();
		}
		
		public function mail($test = false) {
			// create a message
			// insert private variables
			// send mail
			// return error code
			
			// for testing purposes - just print the content on the screen
			if ($test) {
				echo "<pre>";
				echo "Address: " . $this->address;
				echo "From: " . $this->from;
				echo "Subject: " . $this->subject;
				echo "Content: " . $this->content;
				die();
			}
			
		}
		
		
		
	}

?>
