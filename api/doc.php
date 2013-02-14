<html>
<body>
<pre>
<h3>API INTERFACE</h3>
<?php 
	// ================================================
	// 			USER OPERATIONS
	// ================================================
?>
<div style="padding:0px 15px 10px;background-color:#ddd;width:200px;float:right;position:fixed;top:5px;right:5px;">
<h3 style="margin:0px;">Content</h3>
<a href="#login">asignin</a>
<a href="#signup">signup</a>
</div>	
<a name="login"></a>	
<span style="background-color:#000;color:#fff;"><b>/api/?login</b></span>
this function sets up the Session variable
POST Parameters:
	email - open text
	password - open text
RESPONSE:
	{"success": true / false,
	"error": error message}

<a name="signup"></a>
<span style="background-color:#000;color:#fff;"><b>/api/?signup</b></span>
this function creates the user record and send the 'confirm email' email
POST Parameters:
	email - open text
	password - open text [minimum 6 symbols]
	firstName - open text
	lastName - open text
RESPONSE:
	{"success": true / false,
	"error": error message,
	"userId": id of new user in case of success}
	
</pre>
</body>
</html>