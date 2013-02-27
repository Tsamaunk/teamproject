<?php 

	class Mailer {
		
		private $subject;
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
			// populate private variables
			
		}
		
		public function mail() {
			// create a message
			// insert private variables
			// send mail
			// return error code
			
		}
		
		
		
	}

?>