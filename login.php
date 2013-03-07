<?php
	include_once 'head.php';
	include_once 'topBar.php';
	include_once 'sideBar.php';
?>


<div id="content" >
    <form action="" id="login-form" method="post" onSubmit="return false;">
        
        <div id="error"></div>
        
		<label>E-mail:</label>
		<input name="email" id="email" type="text" /><br />
		
		<label>Password:</label>
		<input name="password" id="password" type="password" /><br />
		
		<label></label><button type="submit" id="submit">Login</button>
		
	</form>
        
</div>

<script>
    $("#submit").click(function() {

        $.post("api/?login", 
        {'email' : $("#email").val(), 'password' : $("#password").val()},
        function(data) {
            if(data.success){
                window.location.replace("index.php");
            } else {
                $("#error").html(data.error);                                                        
            }
        },
        "json");

    });                                              
</script>

<?php 
	include_once 'footer.php';
?>