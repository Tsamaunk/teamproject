<?php 

// include swiftmail bullshit

	class Mailer {
		
		private $subject = '[Project Switch] ';
		private $address;
		private $from;
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
			$model = new Model();
			
			$model->logger->info("MAILER:compose called for type = $type");
			
			//$rd = $con->getSetting('rd_1');
			//$rd = $con->getUserById($rd->intVal);
			//$this->from = $rd->firstName . ' ' . $rd->lastName . ' <' . $rd->email . '>';
			
			$this->from = "Project Switch <mgordo@" . DOMAIN_URL . ">";

			switch ($type) {
				case 1:
					$ra1 = $con->getUserById($token1->raId1);
					$this->address = $ra1->firstName . ' ' . $ra1->lastName . ' <' . $ra1->email . '>'; // FIRST LAST <EMAIL@DOMAIN>
					$this->subject .= "Confirmation Notification";
					$this->content = "Dear " . $ra1->firstName . ",\r\n";
					$this->content .= "Please confirm your email here " . SITE_URL . "token.php?token=" . $token1->token . "\r\n";
					break;
				case 2:
					$rd = $con->getUserById($token1->rdId);
					$ra1 = $con->getUserById($token1->raId1);
					$this->address = $rd->firstName . ' ' . $rd->lastName . ' <' . $rd->email . '>'; // FIRST LAST <EMAIL@DOMAIN>
					$this->subject .= "User Confirmation Notification";
					$this->content = "Dear " . $rd->firstName . ",\r\n";
					$this->content .= "User " . $ra1->firstName . " " . $ra1->lastName . " needs confirmation: \r\n to confirm click " . SITE_URL . "token.php?token=" . $token1->token . "\r\n to deny click " . SITE_URL . "token.php?token=" . $token2->token . " \r\n";
					break;
				case 3:
					$ra1 = $con->getUserById($token1->raId1);
					$this->address = $ra1->firstName . ' ' . $ra1->lastName . ' <' . $ra1->email . '>';
					$this->subject .= "Confirmation Approved";
					$this->content = "Dear " . $ra1->firstName . ",<br><br>";
					$this->content .= "Congratulations, your email has been approved! Please login <a href='" . SITE_URL . "login.php" . "'>here</a>.<br><br>";
					break;
				case 4:
					$ra1 = $con->getUserById($token1->raId1);
					$ra2 = $con->getUserById($token1->raId2);
					$this->address = $ra2->firstName . ' ' . $ra2->lastName . ' <' . $ra2->email . '>';
					$this->subject .= "Switch Request Notification";
					$this->content = "Dear " . $ra2->firstName . ",<br><br>";
					$this->content .= $ra1->firstName . " wants to switch a duty day with you: <a href='" . SITE_URL . "token.php?token=" . $token1->token . "'>ACCEPT</a>  <a href='" . SITE_URL . "token.php?token=" . $token2->token . ">DECLINE</a><br><br>";
					$thic->content .= "<a href='" . SITE_URL . "login.php" . "'>Login</a> to view specifics.<br><br>";
					break;
				case 5:
					$ra1 = $con->getUserById($token1->raId1);
					$ra2 = $con->getUserById($token1->raId2);
					$this->address = $ra1->firstName . ' ' . $ra1->lastName . ' <' . $ra1->email . '>';
					$this->subject .= "Switch Request Declined";
					$this->content = "Dear " . $ra1->firstName . ",<br><br>";
					$this->content .= $ra2->firstName . " has declined your requested duty switch. <a href='" . SITE_URL . "login.php" . "'>Login</a> to view any accompanying messages.<br><br>";
					break;
				case 6:
					$rd = $con->getUserById($token1->rdId);
					$ra1 = $con->getUserById($token1->raId1);
					$ra2 = $con->getUserById($token1->raId2);
					$this->address = $rd->firstName . ' ' . $rd->lastName . ' <' . $rd->email . '>';
					$this->subject .= "Switch Request Notification";
					$this->content = "Dear " . $rd->firstName . ",<br><br>";
					$this->content .= $ra1->firstName . " and " . $ra2->firstName . " have confirmed a duty switch request: <a href='" . SITE_URL . "token.php?token=" . $token1->token . "'>APPROVE</a>  <a href='" . SITE_URL . "token.php?token=" . $token2->token . ">DENY</a><br><br>";
					$thic->content .= "<a href='" . SITE_URL . "login.php" . "'>Login</a> to view specifics.<br><br>";
					break;
				case 7:
					$rd = $con->getUserById($token1->rdId);
					$ra1 = $con->getUserById($token1->raId1);
					$this->address = $ra1->firstName . ' ' . $ra1->lastName . ' <' . $ra1->email . '>';
					$this->subject .= "Switch Request Approved";
					$this->content = "Dear " . $ra1->firstName . ",<br><br>";
					$this->content .= $rd->firstName . " has approved your requested switch.<br><br>";
					break;
				case 8:
					$rd = $con->getUserById($token1->rdId);
					$ra1 = $con->getUserById($token1->raId1);
					var_dump($token);
					$this->address = $ra1->firstName . ' ' . $ra1->lastName . ' <' . $ra1->email . '>';
					$this->subject .= "Switch Request Denied";
					$this->content = "Dear " . $ra1->firstName . ",<br><br>";
					$this->content .= $rd->firstName . " has denied your requested switch. <a href='" . SITE_URL . "login.php" . "'>Login</a> to view specifics.<br><br>";
					break;
				case 9:
					$ra1 = $con->getUserById($token1->raId1);
					$this->address = $ra1->firstName . ' ' . $ra1->lastName . ' <' . $ra1->email . '>';
					$this->subject .= "Password Recovery";
					$this->content = "Dear " . $ra1->firstName . ",<br><br>";
					$this->content .= "A password recovery request has been intitiated for you.  Please click <a href='" . SITE_URL . "toekn.php?token=" . $token1->token . "'>here</a> to complete the recovery process.<br><br>";
					break;
				default:
					$this->address = "mgrunert0322@gmail.com";
					$this->subject = "SOMETHING'S WRONG IN THE MAILER";
					$this->content = "You fucked up somewhere.  Go find it, dumbass.<br><br>";
			}		

			$this->content .= "\r\nThanks,\r\nThe Project Switch Team";
			$con->close();
		}
		
		public function mail($test = false) {
			// create a message
			// insert private variables
			// send mail
			// return error code

			// for testing purposes - just print the content on the screen
			if ($test) {
				$str = "Address: " . $this->address;
				$str .= "<br>From: " . $this->from;
				$str .= "<br>Subject: " . $this->subject;
				$str .= "<br>Content: " . $this->content;
				return $str;
			} else {
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/plain; charset=utf-8' . "\r\n";
				$headers .= 'From: ' . $this->from . "\r\n" .
				'Return-Path: ' . $this->from . "\r\n" . 
				'Reply-To: ' . $this->from;
				$str = mail($this->address, $this->subject, $this->content, $headers);
				$model = new Model();
				if ($str)
				$model->logger->info("MAILER:mail function sent the message");
				else $model->logger->warn("MAILER:mail function failed");
				return $str;
			}
			
		}
		
		
		
	}

?>
