<?php

session_start();
        error_reporting(0);

include_once 'head.php';
	include_once 'topBar.php';
	include_once 'sideBar.php';

        
        if (isset($_POST['user']) && isset($_POST['pass'])){
            
            $_SESSION['userId']=1;
            header( 'Location: index.php' ) ;
}
        
        
        ?>


<div id="content" >
<br />
<br />
<br />
<form action="login.php" method="post">
<center>
<table border="0">
  <tr>
    <td>E-mail:</td>
    <td><input name="user" type="text" /></td>
  </tr>
  <tr>
    <td>Password:</td>
    <td><input name="pass" type="password" /></td>
  </tr>
  <tr>
    <td colspan="2" align="center">
        <input name="Login" type="submit" value="Log In" /></td>
  </tr>
</table>
</center>
</div>
</body>
</html>