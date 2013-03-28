	<!doctype html>
	<html>
	<head>
	<meta charset="UTF-8">
	<title>Welcome</title>
	<script src="js/jquery-1.8.0.min.js"></script>
	
	</head>
	
	<body>
	<pre>
	<?php
	include 'base.php';
	
	
	$con = new Controller();
	
	//$message = new stdClass();
	/*$message -> fromId = 3;
	$message -> toId = 1;
	$message -> subject = "Hey";
	$message -> text = "Great, how's yours???";
	*/
	
	$message = new stdClass();
	$message->fromId = 1;
	$message->toId = 2;
	$message->subject = $_POST['subject'] ? $_POST['subject'] : 'No subject';
	$message->text= "This is the end";
	$con->connect();
	$con->addMessage($message);
	var_dump(mysql_error());
	$con->close();
	
	/*$cnt = new Controller();
	$cnt->connect();
	$res = $cnt->getMyDialogs(1);
	//$cnt->addMessage($message);
	$cnt->close();

	$myId = 1;
	$ids = array();
	$dialogs = array();
	foreach ($res as $r) {
		if($r->fromId == $myId && !in_array($r->toId, $ids)) {
			$dialogs[] = $r;
			$ids[] = $r->toId;
		}
		if($r->toId == $myId && !in_array($r->fromId, $ids)) {
			$dialogs[] = $r;
			$ids[] = $r->fromId;
		}
	}
		
	var_dump($dialogs);
	*/
	
	$hlp = new Helper();
	/*$_SESSION['userId'] = 1;
	$_SESSION['userRole'] = 2;
	$_SESSION['userToken'] = $hlp->createUserToken($_SESSION['userId']);
	 	
	var_dump($_SESSION['userToken']);
	*/
	
		
	?>
	
	<script>
		$(documentdd).ready(function(){
	        $.post("api/?getDialogById", {'userId':'2'},
	                function(data){
	                        if(!data.success){
	                                alert('error: ' + data.error);                                                        
	                                }else{
	                                        alert(data.dialog[0].created);
	                                        }    
	        });                                                                    
	                        
			});
	
	</script>
	
	<div id="holder">
	</div>
	</body>
	</html>
	