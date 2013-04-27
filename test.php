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
		$(document).ready(function(){
			var ht = "";
			$.getJSON('api/?getCalendar',{month: "4"}).done(function(data) {
				  $.each(data.calendar, function(key, val) {
					  ht += "day: "+key+"<br>";
					  $.each(val, function(ky, vl) {
						  if (ky == 'rd')
							ht += "   "+ky+":"+vl.userName+"["+vl.id+"] \t\t type:"+vl.type+"<br>";
						  else {
							  $.each(vl, function(kyx, vlx) {
								  ht += "   "+kyx+":"+vlx.userName+"["+vlx.id+"] \t\t type:"+vlx.type+" uid:"+vlx.userId+"<br>";
							  });
						  }
					  });
				  });
				  $('#holder').html(ht);
			});


			/*/
	        $.post("api/?addSwitch", {month:4, day1:10, day2:20, withUser:4},
	                function(data){
	                        if(!data.success){
	                                alert('error: ' + data.error);                                                        
	                                } else {
	                                	
	                                    $('#holder').html('success');
	                                }    
	        });       /**/                                                             
	                        
			});
	
	</script>
	
	<div id="holder">
	</div>
	</body>
	</html>
	