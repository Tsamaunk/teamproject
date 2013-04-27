<?php

if (!isset($myUser) || $myUser->role != 2) {
	die('unauthorized access');
}
if (isset($_POST['update']) && $_POST['update'] == 1) { //confirm
	$id = $_POST['switchId'];
	$reason = $_POST['reason'];
	$confirm = 3;
	$con->connect();
	$con->confirmSwitch($id, $confirm, $reason);
	$con->close();
	unset($_POST);
	header('Location: /admin.php?page=switches');
}
if (isset($_POST['update']) && $_POST['update'] == 2) { //decline
	$id = $_POST['switchId'];
	$reason = $_POST['reason'];
	$confirm = 4;
	$con->connect();
	$con->confirmSwitch($id, $confirm, $reason);
	$con->close();
	unset($_POST);
	header('Location: /admin.php?page=switches');
}
	$sw = $con->getListOfSwitches();
?>
<h1>All Switches</h1>

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
	function approve(id) {
		var reason = prompt("Comment? [optional]","");
		var frm = $("<form/>",
				{ id: "snd", 
				method: "post", 
				action: "?page=switches" });
	    frm.append($("<input/>",
	    	    { type : "hidden", 
    	    	name: "update", 
    	    	value: "1" }));
    	frm.append($("<input/>",
    			{ type : "hidden", 
					name: "switchId", 
					value: id }));
    	frm.append($("<input/>",
    			{ type : "hidden", 
					name: "reason", 
					value: ""+reason+" " }));
		frm.submit();
	}

	function decline(id) {
		var reason = prompt("Reason? [optional]","");
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
					name: "switchId", 
					value: id }));
    	frm.append($("<input/>",
    			{ type : "hidden", 
					name: "reason", 
					value: ""+reason+" " }));
		frm.submit();  
	}

</script>
