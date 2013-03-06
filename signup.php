<?php
	include_once 'head.php';
	include_once 'topBar.php';
	include_once 'sideBar.php';
?>

 <script type="text/javascript">
      $(document).ready(function() {
      $("#signupform").validate({
        rules: {
          pass: {
                required: true, minlength: 4
          },
          cpass: {
                required: true, 
				equalTo: "#pass", 
				minlength: 4
          },
          email: {
		  			required: true, 
					email: true,
		          },
		   first: {
		   			required: true
				},
			last: {
					required: true
				}

          }
          }
      );
    });
    </script>
<script>
jQuery(document).ready(function($) {
    $('#signupform').find(':submit').click(function(e) {
        if( ! isemail( $('#email').val() ) ) {
            e.preventDefault(); // Prevent the form from submitting
            alert('You can only register with an @albany.edu email address!');
            return false;
        }
    });
});

function isemail( address ) {
    return address.indexOf("@albany.edu") !== -1;
}</script>

<div id="content">
<center>
<br />
<br />


REGISTRATION

<form id="signupform" action="index.php" method="post">

<table border="0">
  <tr>
    <td width="141">First Name:</td>
    <td width="154"><input type="text" name="first" id="first" /></td>
  </tr>
  <tr>
    <td>Last Name: </td>
    <td><input type="text" name="last" id="last" /></td>
  </tr>
  <tr>
    <td>E-mail: </td>
    <td><input type="text" name="email" id="email" /></td>
  </tr>
  <tr>
    <td>Password: </td>
    <td><input type="password" name="pass" id="pass" /></td>
  </tr>
  <tr>
    <td>Confirm Password: </td>
    <td><input type="password" name="cpass" id="cpass" /></td>
  </tr>
  <tr>
    <td colspan="2" align="center"><input type="submit" name="submit" id="submit" value="Submit" /></td>
  </tr>
</table>
</form>
</center>
</div>
</div>
</body>
</html>