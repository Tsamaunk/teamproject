<?php
include_once 'head.php';
include_once 'topBar.php';
include_once 'sideBar.php';
?>


<div id="content">
        <h2 style="text-align: center; margin-top: 100px;">REGISTRATION</h2>

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
        $("#signup").validate({
            rules: {
                password: {
                    required: true, minlength: 6
                },
                cpass: {
                    required: true, 
                    equalTo: password, 
                    minlength: 6
                },
                email: {
                    required: true, 
                    email: true
                },
                firstName: {
                    required: true
                },
                lastName: {
                    required: true
                }

            }
        });

        $.post("api/?signup", 
        {'email' : $("#email").val(), 'password' : $("#password").val(), 'firstName' : $("#firstName").val(), 'lastName' : $("#lastName").val()},
        function(data) {
            
            if(data.success){
                $("#result").html("You Are succesfully Registered and waiting for approval.");
				document.getElementById('signup').reset();
            }else{
                alert('Error: ' + data.error);                                                        
            }
            
        })
        .done(function() {  })
        .fail(function() {  })
        .always(function() {  },
        "json");

    });                                              
</script>

<?php 
	include_once 'footer.php';
?>