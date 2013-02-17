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
		echo "<pre>";
		$cnt = new Controller();
		$cnt -> connect();
		
		$user = new stdClass();
		$user->firstName = 'John';
		$user->lastName = 'Kennedy';
		$user->email = 'jk@john.com';
		$user->password= '0321745';
		
		$cnt->createUser($user);
		
		//$cnt->checkCredentials('himor.cre@gmail.com', 'password');
		
		$cnt->close();
		
		
	 	
		
	?>
	
	<script>
		$(document).ready(function(){
	        $.post("api/?logout", {'email' : 'himor.cre@gmail.com', 'password' : 'password'},
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
	