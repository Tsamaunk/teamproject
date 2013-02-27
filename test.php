	<!doctype html>
	<html>
	<head>
	<meta charset="UTF-8">
	<title>Welcome</title>
	<script src="js/jquery-1.8.0.min.js"></script>
	
	</head>
	
	<body>
	
	<?php
		include 'base.php'; 
		echo "<pre>BACKEND TESTING AREA\n";
		$cnt = new Controller();
		$cnt -> connect();
		$cnt -> setSetting('numberOfRd', 2, 1);
		$cnt -> setSetting('rd_1', 1, 1);
		$cnt -> setSetting('rd_2', 2, 1);
		
		$cnt->close();
		
		
		$token = new stdClass();
		$token -> raId1 = 2;
		$token -> type = 1;
		
		$hlp = new Helper();
		//$token = $hlp -> makeToken($token, 2, 'confirmMailToken');
		
		$token = $hlp->execToken('9521649eb4623a2dd16e06d30e19c87c28986e85db75310acfa06cecbc428a7d');
		
		if ($token) echo "TOKEN FOUND\n\n";
		var_dump($token);
		
		var_dump(md5("admin"));
		
		
	 	
		
	?>
	
	<script>
		$(document).ready(function(){
	        $.post("api/?lost_password", {'email' : 'himor.cre@gmail.com'},
	                function(data){
	                        if(!data.success){
	                                alert('error: ' + data.error);                                                        
	                                }else{
	                                        alert('success');
	                                        }    
	        });                                                                    
	                        
			});
	
	</script>
	
	<div id="holder">
	</div>
	</body>
	</html>
	