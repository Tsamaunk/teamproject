<?php
if (!isset($myUser)) {
	die('unauthorized access');
}
$today = new DateTime();
$month = isset($_POST['month']) ? (int)$_POST['month'] : (isset($_SESSION['month']) ? $_SESSION['month'] : (int)$today->format('m'));
$_SESSION['month'] = $month;
$sch = $con->getSchedule($month);
$cal = array();
$ncal = array();
foreach ($sch as $s) {
	if ($s->type == 1)
		$cal[$s->assignedDate]['ra'][] = $s;
	elseif($s->type == 2)
	$cal[$s->assignedDate]['rd'] = $s;
}
foreach ($cal as $date=>$key) {
	if ($key['rd']->userId == $myUser->userId)
		$ncal[$date] = $key;
	else {
		foreach($key['ra'] as $kra)
			if ($kra->userId == $myUser->userId)
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

