<?php
/*
  session_start();
  if (isset($_POST['user']) && isset($_POST['pass'])) {

  $_SESSION['userId'] = 1;
  header('Location: index.php');
  }
 */
error_reporting(0);

include_once 'head.php';
include_once 'topBar.php';
include_once 'sideBar.php';
?>


<div id="content" >
    <br />
    <br />
    <br />
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
            <div id="result"></div>

        </center>
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