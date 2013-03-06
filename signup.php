<?php
include_once 'head.php';
include_once 'topBar.php';
include_once 'sideBar.php';
?>


<div id="content">
    <center>
        <br />
        <br />


        <h2>REGISTRATION</h2>

        <form id="signup" action="" method="post">

            <table border="0">
                <tr>
                    <td width="141">First Name:</td>
                    <td width="154"><input type="text" name="firstName" id="firstName" /></td>
                </tr>
                <tr>
                    <td>Last Name: </td>
                    <td><input type="text" name="lastName" id="lastName" /></td>
                </tr>
                <tr>
                    <td>E-mail: </td>
                    <td><input type="text" name="email" id="email" /></td>
                </tr>
                <tr>
                    <td>Password: </td>
                    <td><input type="password" name="password" id="password" /></td>
                </tr>
                <tr>
                    <td>Confirm Password: </td>
                    <td><input type="password" name="cpass" id="cpass" /></td>
                </tr>
                <tr>
                    <td colspan="2" align="center"><input type="button" name="submit" id="submit" value="Submit" /></td>
                </tr>
            </table>
        </form>
    <div id="result"></div>
    </center>
</div>
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



</body>
</html>