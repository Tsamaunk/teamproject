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
		$cnt = new Controller();
		$cnt->connect();
		$res = $cnt->getMyDialogs(1);
		$cnt->close();
		
		var_dump($res);
	 	
		
	?>
	
	<script>
		$(documentdd).ready(function(){
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
	