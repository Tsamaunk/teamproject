<?php

if (!isset($myUser) || $myUser->role != 2) {
	die('unauthorized access');
}


?>