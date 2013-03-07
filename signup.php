<?php
include_once 'head.php';
include_once 'topBar.php';
include_once 'sideBar.php';
?>


<div id="content">
        <h2 style="text-align: center; margin-top: 100px;">Registration</h2>

        <form id="signup-form" action="" method="post">
	    <div id="error"></div>
		<label>First name:</label>
		<input type="text" name="firstName" id="firstName" /></br />
		
        <label>Last Name: </label>
        <input type="text" name="lastName" id="lastName" /><br />

        <label>E-mail: </label>
		<input type="email" name="email" id="email" /><br />

		<label>Password: </label>
        <input type="password" name="password" id="password" /><br />
        
        <label>Confirm Password: </label>
        <input type="password" name="cpass" id="cpass" /><br />
		
		<label></label><button name="submit" id="submit">Submit</button>

		</form>
</div>


<script>
    $("#submit").click(function() {
		$(this).attr('disabled','disabled');
        $.post("api/?signup", 
        {'email' : $("#email").val(), 'password' : $("#password").val(), 'firstName' : $("#firstName").val(), 'lastName' : $("#lastName").val()},
        function(data) {
            if(data.success){
            	$("#error").attr('id', 'success');
                $("#success").html("You Are succesfully Registered and waiting for approval.");
            }else{
            	$("#error").html(data.error);
            	$("#submit").removeAttr('disabled');
            }
            
        },
        "json");

    });                                              
</script>

<?php 
	include_once 'footer.php';
?>