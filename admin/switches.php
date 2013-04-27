<?php

if (!isset($myUser) || $myUser->role != 2) {
	die('unauthorized access');
}
/*
if (isset($_POST['update']) && $_POST['update'] == 1) {
	$user = new stdClass();
	$user->role = $_POST['type'];
	$user->email = $_POST['email'];
	$user->firstName = $_POST['firstName'];
	$user->lastName = $_POST['lastName'];
	$user->password = $_POST['password'];
	$con->connect();
	$con->createUser($user);
	$con->close();
	unset($_POST);
	header('Location: /admin.php?page=switches');
}

if (isset($_POST['update']) && $_POST['update'] == 2) {
	$userId = $_POST['userId'];
	$con->connect();
	$con->approveUser($userId, $myUser->userId);
	$con->close();
	unset($_POST);
	header('Location: /admin.php?page=switches');
}

if (isset($_POST['update']) && $_POST['update'] == 3) {
	$userId = $_POST['userId'];
	$con->connect();
	$con->approveUser($userId, $myUser->userId, false);
	$con->close();
	unset($_POST);
	header('Location: /admin.php?page=switches');
}

if (isset($_POST['update']) && $_POST['update'] == 4) {
	$userId = $_POST['userId'];
	$con->connect();
	$con->undeleteUser($userId);
	$con->close();
	unset($_POST);
	header('Location: /admin.php?page=switches');
}

if (isset($_POST['update']) && $_POST['update'] == 5) {
	$userId = $_POST['userId'];
	$con->connect();
	$con->updateUserRole($userId, 2);
	$con->updateAdmins();
	$con->close();
	unset($_POST);
	header('Location: /admin.php?page=switches');
}

if (isset($_POST['update']) && $_POST['update'] == 6) {
	$userId = $_POST['userId'];
	$con->connect();
	$con->updateUserRole($userId, 1);
	$con->updateAdmins();
	$con->close();
	unset($_POST);
	header('Location: /admin.php?page=switches');
}
*/
?>

<?php if ($myUser->role == 2) :
	$sw = $con->getListOfSwitches();
?>
<h1>All Switches</h1>
<?php endif;?>

<table style="width: 95%;" id="usersTable">
	<tr>
		<th style="text-align:left; width:6%;">ID</th>
		<th style="text-align:left; width:21%;">User 1</th>
		<th style="text-align:left; width:21%;">User 2</th>
		<th style="text-align:left; width:13%;">Date 1</th>
		<th style="text-align:left; width:13%;">Date 2</th>
		<th style="text-align:left; width:10%;">Status</th>
		<th style="text-align:left; width:16%;">Options</th>
	</tr>
	<?php

	foreach ($sw as $u) {
		echo "<tr>";
		echo "<td>" . $u->id . "</td>";
		echo "<td>" . $u->userName1 . " [" . $u->userId1 . "]</td>";
		echo "<td>" . $u->userName2 . " [" . $u->userId2 . "]</td>";
		echo "<td>" . $u->date1 . "</td>";
		echo "<td>" . $u->date2 . "</td>";
		echo "<td>";
			if ($u->status == 0) echo "New";
			if ($u->status == 1) echo "Confirmed";
			if ($u->status == 2) echo "Declined";
			if ($u->status == 3) echo "Approved";
			if ($u->status == 4) echo "Denied";
		echo "</td>";
		echo "<td>";
			if ($u->status == 1) echo "<a href='javascript:approve(".$u->id.");'>Approve</a> &middot; ".
					"<a href='javascript:decline(".$u->id.");'>Decline</a>";
		echo "</td></tr>";
		if ($u->reason) {
			echo "<tr>";
			echo "<td colspan=\"7\" style=\"text-align:right\">";
			echo $u->reason;			
			echo "</td>";
			echo "</tr>";
		}
	}
?>
</table>

<script>
	function blockUser(id) {
		var frm = $("<form/>",
				{ id: "snd", 
				method: "post", 
				action: "?page=switches" });
	    frm.append($("<input/>",
	    	    { type : "hidden", 
    	    	name: "update", 
    	    	value: "3" }));
    	frm.append($("<input/>",
    			{ type : "hidden", 
					name: "userId", 
					value: id }));
		frm.submit();  
	}

	function unblockUser(id) {
		var frm = $("<form/>",
				{ id: "snd", 
				method: "post", 
				action: "?page=switches" });
	    frm.append($("<input/>",
	    	    { type : "hidden", 
    	    	name: "update", 
    	    	value: "4" }));
    	frm.append($("<input/>",
    			{ type : "hidden", 
					name: "userId", 
					value: id }));
		frm.submit();  
	}

	function approveUser(id) {
		var frm = $("<form/>",
				{ id: "snd", 
				method: "post", 
				action: "?page=switches" });
	    frm.append($("<input/>",
	    	    { type : "hidden", 
    	    	name: "update", 
    	    	value: "2" }));
    	frm.append($("<input/>",
    			{ type : "hidden", 
					name: "userId", 
					value: id }));
		frm.submit();    
	}

	function makeAdmin(id) {
		var frm = $("<form/>",
				{ id: "snd", 
				method: "post", 
				action: "?page=switches" });
	    frm.append($("<input/>",
	    	    { type : "hidden", 
    	    	name: "update", 
    	    	value: "5" }));
    	frm.append($("<input/>",
    			{ type : "hidden", 
					name: "userId", 
					value: id }));
		frm.submit();    
	}

	function makeUser(id) {
		var frm = $("<form/>",
				{ id: "snd", 
				method: "post", 
				action: "?page=switches" });
	    frm.append($("<input/>",
	    	    { type : "hidden", 
    	    	name: "update", 
    	    	value: "6" }));
    	frm.append($("<input/>",
    			{ type : "hidden", 
					name: "userId", 
					value: id }));
		frm.submit();
	}
	
	function createUser() {
		$("#createUserForm").fadeIn(200);
		$("#usersTable").hide();
	}

</script>
