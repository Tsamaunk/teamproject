<?php
if (!isset($myUser)) {
	die('unauthorized access');
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
echo "<pre>";
var_dump($ncal);

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
			echo "&nbsp;&nbsp;&nbsp;&nbsp;Switching with ".$c['sw']->userName2;
		if ($c['sw']->userId2 == $cra->userId)
			echo "&nbsp;&nbsp;&nbsp;&nbsp;Switching with ".$c['sw']->userName1;
		
		if ($c['sw']->userId2 == $cra->userId && $c['sw']->status == 0) {
			echo "<a href=\"javascript:confirm(".$c['sw']->id.");\">confirm</a> &middot; <a href=\"javascript:decline(".$c['sw']->id.");\">decline</a>";
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

}

function decline(id) {


}

</script>