<html>
<body>
<pre>
<h3>API INTERFACE</h3><?php 
	// ================================================
	// 			USER OPERATIONS
	// ================================================
?><div style="padding:0px 15px 10px;background-color:#ddd;width:200px;float:right;position:fixed;top:5px;right:5px;">
<h3 style="margin:0px;">Content</h3>
<a href="#login">login</a>
<a href="#signup">signup</a>
<a href="#lost_password">lost_password</a>
<a href="#getUser">getUser</a>
<a href="#getUserList">getUserList</a>
<a href="#sendMail">sendMail</a>
<a href="#getDialogs">getDialogs</a>
<a href="#getDialogById">getDialogById</a>
<a href="#deleteDialog">deleteDialog</a>
<a href="#getCalendar">getCalendar</a>
<a href="#getNot">getNot</a>
<a href="#getNotCount">getNotCount</a></div>
<div style="background-color:#fd7;padding:10px;"><h3>How do we check the session? (PHP CODE)</h3>Have this code in the beggining of your file: 
&lt;?php 
include_once 'base.php';	// THIS THE THE BACKEND CONNECTOR
$hlp = new Helper();		// CREATE THE HELPER

if (isset($_SESSION['userId']) && isset($_SESSION['userToken']) && $hlp->validToken($_SESSION['userId'], $_SESSION['userToken'])) {
		CONGRATS! WE HAVE A SESSION WITH USER ID = $_SESSION['userId'];
	} else {
		NO SESSION FOUND - <font color="red">PROBABLY THE SESSION HAS EXPIRED!</font> OR NEVER EXISTED
	}
?&gt;</div> 
<a name="login"></a>	
<span style="background-color:#000;color:#fff;"><b>/api/?login</b></span>
this function sets up the Session variable
Session expires after a few minutes of inactivity
POST Parameters:
	email - open text
	password - open text
RESPONSE:
	{"success": true / false,
	"error": error message}

<a name="signup"></a>
<span style="background-color:#000;color:#fff;"><b>/api/?signup</b></span>
this function creates the user record and sends the 'confirm email' email
POST Parameters:
	email - open text
	password - open text [minimum 6 symbols]
	firstName - open text
	lastName - open text
RESPONSE:
	{"success": true / false,
	"error": error message,
	"userId": id of new user in case of success}
	
<a name="lost_password"></a>	
<span style="background-color:#000;color:#fff;"><b>/api/?lost_password</b></span>
this function must be executed in case user want to reset the password
POST Parameters:
	email - open text
RESPONSE:
	{"success": true / false,
	"error": error message}
<h3>The following functions will work only for logged users</h3>
<a name="getUser"></a>	
<span style="background-color:#000;color:#fff;"><b>/api/?getUser</b></span>
function returns the information about user
POST Parameters:
	none
RESPONSE:
	{"success": true / false,
	"error": error message,
	"user": user object}
NOTE: user has:
	firstName, lastName, email, role, approvedBy
	
<a name="sendMail"></a>	
<span style="background-color:#000;color:#fff;"><b>/api/?sendMail</b></span>
this function creates the new message
POST Parameters:
	to - integer - the id of the recepient
	text - string - the content of the message
	subject - string [optional] - subject of the message
RESPONSE:
	{"success": true / false,
	"error": error message}
	
<a name="getDialogs"></a>	
<span style="background-color:#000;color:#fff;"><b>/api/?getDialogs</b></span>
this function returns list of all the existing dialogs (similar to left column of the facebook messenger)
POST Parameters:
	none
RESPONSE:
	{"success": true / false,
	"error": error message,
	"dialogs": array of dialogs (one *last* message in each)}
Note: Each dialog is and array of parameters:
	fromId - int - id of user
	toId - int - id of user
	subject - string
	text - string
	read - boolean - have user read this message yet?
	created - string in format HH:MM M/D/Y
	additional parameter: "name" - name of the user (not me, the other user)
	
<a name="getDialogById"></a>	
<span style="background-color:#000;color:#fff;"><b>/api/?getDialogById</b></span>
returns the array of messages between two people
WARNING! this function marks messages as 'read' automatically
POST Parameters:
	userId - id of the user (who am I talking to)
	limit - int [optional] if not specified - returns all messages in the dialog!
RESPONSE:
	{"success": true / false,
	"error": error message,
	"dialog": array of messages (see previous example),
	"name": the name of the user}
	
<a name="deleteDialog"></a>	
<span style="background-color:#000;color:#fff;"><b>/api/?deleteDialog</b></span>
function kills the dialog between two people
POST Parameters:
	userId - id of the user (who am I talking to)
RESPONSE:
	{"success": true / false,
	"error": error message}	

<a name="getUserList"></a>	
<span style="background-color:#000;color:#fff;"><b>/api/?getUserList</b></span>
function returns list of all active users except for myself
POST Parameters:
	none
RESPONSE:
	{"success": true / false,
	"error": error message,
	"users": array of users(id, name),
	"size": size of the users array}	
	
<a name="getCalendar"></a>	
<span style="background-color:#000;color:#fff;"><b>/api/?getCalendar</b></span>
function returns calendar
NOTE: each day is accessible via day = calendar.d_&lt;day&gt;
	day is an object
	day.rd - is an object representing Director on Duty
	day.ra - is an array of objects representing RAs
	both: ra and rd have fields: id, userId, userName, type. type = 'c' means from calendar, 's' means from switch
	$.getJSON must be used to retrieve this content.
POST Parameters:
	month - integer [optional] 1-12, current - if not specified
RESPONSE:
	{"success": true / false,
	"error": error message,
	"calendar": array of days}
NOTE:
	firstDay - left upper corned of the calendar. If month starts on Sunday, firstDay = 0; if month starts on Tuesday, firstDay = -2
	maxDays - number of days in this month
	
<div style="background-color:#fd7;padding:10px;"><h4>Example of calendar retrieval</h4>var ht = "";
$.getJSON('api/?getCalendar',{month: "5"}).done(function(data) {
	  $.each(data.calendar, function(key, val) {
		  ht += "day: "+key+"&lt;br&gt;";
		  $.each(val, function(ky, vl) {
			  if (ky == 'rd')
				ht += "   "+ky+":"+vl.userName+"["+vl.id+"] - "+vl.type+"&lt;br&gt;";
			  else {
				  $.each(vl, function(kyx, vlx) {
					  ht += "   "+kyx+":"+vlx.userName+"["+vlx.id+"] - "+vlx.type+" uid:"+vlx.userId+"&lt;br&gt;";
				  });
			  }
		  });
	  });
	  $('#holder').html(ht);
});<h4>output with switches</h4>day: d_06
   rd:John Smith[86] - c
   0:John Smith[1] - switch_0 uid:2
day: d_09
   rd:Mikhail Gordo[93] - c
   0:Mike Gordo[1] - switch_0 uid:1
day: d_23
   rd:John Smith[92] - c
day: d_24
   rd:Mike Gordo[90] - c
   0:John Smith[91] - c uid:2
day: d_25
   0:Mike Gordo[89] - c uid:1
</div>
<a name="getNot"></a>	
<span style="background-color:#000;color:#fff;"><b>/api/?getUserList</b></span>
function returns list of all notifications and marks notifications and not-new
POST Parameters:
	none
RESPONSE:
	{"success": true / false,
	"error": error message,
	"notifications": array of notifications}
NOTE:
	each notifications is an object:
	text - STRING - content of notification,
	created - STRING - date and time of notification
	
<a name="getNotCount"></a>	
<span style="background-color:#000;color:#fff;"><b>/api/?getUserList</b></span>
function returns number of users NEW notifications
POST Parameters:
	none
RESPONSE:
	{"success": true / false,
	"error": error message,
	"number": number of notifications}	
	
</pre>
</body>
</html>