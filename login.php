<html>
<?php
error_reporting(0);

include_once 'head.php';
include_once 'topBar.php';
include_once 'sideBar.php';
?>


<div id="content" >
	<h2 align="center">Login</h2>
    <form action="" id="login" method="post">
        <center>
            <table border="0">
                <tr>
                    <td>E-mail:</td>
                    <td><input name="email" id="email" type="text" /></td>
                </tr>
                <tr>
                    <td>Password:</td>
                    <td><input name="password" id="password" type="password" /></td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <input type="button" name="submit" id="submit" value="Submit" /></td>
                </tr>
            </table>
			</form>
            <div id="result"></div>

        </center>
</div>
<?php
include_once 'footer.php';
?>
</div>
<script>
    $("#submit").click(function() {

        $.post("api/?login", 
        {'email' : $("#email").val(), 'password' : $("#password").val()},
        function(data) {
            
            if(data.success){
                window.location.replace("index.php");

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