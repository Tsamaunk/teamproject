<?php
if (!isset($myUser)) {
	die('unauthorized access');
}

if (isset($_POST['update']) && $_POST['update'] == 1) { //confirm
	$id = $_POST['switchId'];
	$confirm = 1;
	$con->connect();
	$con->confirmSwitch($id, $confirm);
	$con->close();
	unset($_POST);
	header('Location: /admin.php?page=myschedule');
}
if (isset($_POST['update']) && $_POST['update'] == 2) { //decline
	$id = $_POST['switchId'];
	$reason = $_POST['reason'];
	$confirm = 2;
	$con->connect();
	$con->confirmSwitch($id, $confirm, $reason);
	$con->close();
	unset($_POST);
	header('Location: /admin.php?page=myschedule');
}

$today = new DateTime();
$month = isset($_POST['month']) ? (int)$_POST['month'] : (isset($_SESSION['month']) ? $_SESSION['month'] : (int)$today->format('m'));
$_SESSION['month'] = $month;
$sch = $con->getSchedule($month);
$sw = $con->getListOfSwitches($myUser->userId);
$cal = array();
$ncal = array();
foreach ($sch as $s) {
	if ($s->type == 1)
		$cal[$s->assignedDate]['ra'][] = $s;
	elseif($s->type == 2)
	$cal[$s->assignedDate]['rd'] = $s;
}

foreach ($sw as $s) { // inject switches to calendar
	if ($s->status == 0 || $s->status == 1 || $s->status == 3) {
		$cal[$s->date1]['sw'] = $s;
		$cal[$s->date2]['sw'] = $s;
	}
}

foreach ($cal as $date => $key) {
	if ($key['rd']->userId == $myUser->userId)
		$ncal[$date] = $key;
	else {
		foreach($key['ra'] as $kra)
			if ($kra->userId == $myUser->userId)
				$ncal[$date] = $key;
		if ($key['sw']->userId1 == $myUser->userId || $key['sw']->userId2 == $myUser->userId)
			$ncal[$date] = $key;
	}
}
?>

<h1>Schedule</h1>

<form id="selectMonth" method="post" action="?page=myschedule">
	<label style="width: 80px;">Month</label> <select name="month"
		onchange="javascript:$('#selectMonth').submit();">
		<option value="1" <?php echo $month==1?"selected='selected'":'';?>>January</option>
		<option value="2" <?php echo $month==2?"selected='selected'":'';?>>February</option>
		<option value="3" <?php echo $month==3?"selected='selected'":'';?>>March</option>
		<option value="4" <?php echo $month==4?"selected='selected'":'';?>>April</option>
		<option value="5" <?php echo $month==5?"selected='selected'":'';?>>May</option>
		<option value="6" <?php echo $month==6?"selected='selected'":'';?>>June</option>
		<option value="7" <?php echo $month==7?"selected='selected'":'';?>>July</option>
		<option value="8" <?php echo $month==8?"selected='selected'":'';?>>August</option>
		<option value="9" <?php echo $month==9?"selected='selected'":'';?>>September</option>
		<option value="10" <?php echo $month==10?"selected='selected'":'';?>>October</option>
		<option value="11" <?php echo $month==11?"selected='selected'":'';?>>November</option>
		<option value="12" <?php echo $month==12?"selected='selected'":'';?>>December</option>
	</select>
</form>

<br>

<table id="myschedule">
<?php 
foreach ($ncal as $date => $c) {
	$dta = new DateTime($date);
	$date = $dta->format('d M Y, D');
	echo "<tr>";
	echo "<td>" . $date . "</td>";
	echo "<td>";
	foreach ($c['ra'] as $cra) {
		
		if ($c['sw']->userId1 == $cra->userId || $c['sw']->userId2 == $cra->userId) echo "<s>";
		if ($cra->userId == $myUser->userId) echo "<strong>"; 
		echo $cra->userName;
		if ($cra->userId == $myUser->userId) echo "</strong>";
		if ($c['sw']->userId1 == $cra->userId || $c['sw']->userId2 == $cra->userId) echo "</s>";
		
		if ($c['sw']->userId1 == $cra->userId)
			echo "&nbsp;&nbsp;&nbsp;&nbsp;<em>Switching with ".$c['sw']->userName2 . "</em>";
		if ($c['sw']->userId2 == $cra->userId)
			echo "&nbsp;&nbsp;&nbsp;&nbsp;<em>Switching with ".$c['sw']->userName1 . "</em>";
		
		if ($c['sw']->userId2 == $cra->userId && $c['sw']->status == 0) {
			echo " <span style=\"font-size:.9em;\"><a href=\"javascript:confirm(".$c['sw']->id.");\">confirm</a> &middot; <a href=\"javascript:decline(".$c['sw']->id.");\">decline</a></span>";
		}
		
		if (($c['sw']->userId2 == $cra->userId || $c['sw']->userId1 == $cra->userId) && $c['sw']->status == 1) {
			echo " <span style=\"font-size:.9em;\"><strong>confirmed</strong></span>";
		}
		if (($c['sw']->userId2 == $cra->userId || $c['sw']->userId1 == $cra->userId) && $c['sw']->status == 3) {
			echo " <span style=\"font-size:.9em;\"><strong>approved</strong></span>";
		}
		
		echo "<br>";
		}
	if ($c['rd']->userId == $myUser->userId) echo "<strong>";
	echo "Director on duty: " . $c['rd']->userName;
	if ($c['rd']->userId == $myUser->userId) echo "</strong>";
	echo "</td>";
	echo "</tr>\n";
	}

	?>
</table>

<script>
	function confirm(id) {
		var frm = $("<form/>",
				{ id: "snd", 
				method: "post", 
				action: "?page=myschedule" });
	    frm.append($("<input/>",
	    	    { type : "hidden", 
    	    	name: "update", 
    	    	value: "1" }));
    	frm.append($("<input/>",
    			{ type : "hidden", 
					name: "switchId", 
					value: id }));
		frm.submit();
	}

	function decline(id) {
		var reason = prompt("Reason? [optional]","");
		var frm = $("<form/>",
				{ id: "snd", 
				method: "post", 
				action: "?page=myschedule" });
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