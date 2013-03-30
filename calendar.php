<?php

include_once 'base.php';
$hlp = new Helper();		// CREATE THE HELPER
$con = new Controller(); 	// AND A CONTROLLER


if (isset($_SESSION['userId']) && isset($_SESSION['userToken']) && $hlp->validToken($_SESSION['userId'], $_SESSION['userToken'])) {
	$myId = $_SESSION['userId'];
} else {
	header('Location: login.php');
	exit;
}

// SOME HEADER HERE

include_once 'header.php';
include_once 'topbar.php'; ?>

<div class="contaner-bottom">
<?php include_once 'sidebar.php'; ?>
	<div class="content">
	
	<?php
	
// AUTHOR: MIKE GORDO mgordo@live.com 03/26/2013 changed by KaI.
if (isset($_POST['update'])) {
	$id = $_POST['id'];
	$upd = $_POST['updType'];
	include_once '../base.php';
	$con = new Controller();
	if ($upd == 3) {
		$con->connect();
		$con->removeDay($id);		
		$con->close();
	}
	if ($upd == 1) {
		$day = new stdClass();
		$day->type = $_POST['type'];
		$day->assignedDate = new DateTime($_POST['date']);
		$day->userId = $_POST['userId'];
		$con->connect();
		$con->addDay($day);
		$con->close();
	}
	echo "ok";
	exit;
}



$users = $con->getAllAliveUsers();

$today = new DateTime();
$month = isset($_POST['month']) ? (int)$_POST['month'] : (isset($_SESSION['month']) ? $_SESSION['month'] : (int)$today->format('m'));
$_SESSION['month'] = $month;

$firstDay = new DateTime($today->format('Y') . '-' . $month . '-1');
$maxDays = $firstDay->format('t');
$firstDay = $firstDay -> format('N');
if ($firstDay ==7) $firstDay =0;
$firstDay = 1-$firstDay;

$sch = $con->getSchedule($month);

$cal = array();
foreach ($sch as $s) {
	if ($s->type == 1)
		$cal[$s->assignedDate]['ra'][] = $s;
	elseif($s->type == 2)
	$cal[$s->assignedDate]['rd'] = $s;
}

?>
<h1>Calendar</h1>

<form id="selectMonth" method="post" action="?page=schedule">
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

<table style="width: 95%;" id="calendar">
	<tr>
		<th style="width: 14.2%;">Sunday</th>
		<th style="width: 14.2%;">Monday</th>
		<th style="width: 14.2%;">Tuesday</th>
		<th style="width: 14.2%;">Wednesday</th>
		<th style="width: 14.2%;">Thursday</th>
		<th style="width: 14.2%;">Friday</th>
		<th style="width: 14.2%;">Saturday</th>
	</tr>
<?php
	$k = 0;
	for ($i = $firstDay; $i <= $maxDays; $i++) {
		if ($k==0 || $k % 7 == 0) echo "<tr>";
		echo "<td id='td_$i'>";
		if ($i>0) echo "<span class='date'>$i</span><br>";
		$index = $today->format('Y') . '-' . ($month>9?$month:'0'.$month) . '-' . ($i > 9 ? $i : '0'.$i);
		$cur = $cal[$index];

		if ($cur) {
			if ($cur['rd']) {
				echo "<span id='spn_".$cur['rd']->id."' class='rd'>".($cur['rd']->userName)."</span>";
			} else {
				echo "<strong>DOD1: </strong></a><br>";
				
			}

			if (count($cur['ra'])) {
				foreach ($cur['ra'] as $cu)
				echo "<span id='spn_".$cu->id."' class='ra'>".($cu->userName)."</span>";
			}

			echo "<a href='#'>duty ra</a><br>";
		} elseif ($i>0) {
			echo "<strong>DoD: </strong></a><br>";
			echo "<a href='#'>Duty Ra1</a><br>";
			echo "<a href='#'>Duty Ra2</a><br>";
			echo "<a href='#'>Duty Ra3</a><br>";
			echo "<a href='#'>Duty Ra4</a><br>";
			echo "<a href='#'>Duty Ra5</a><br>";
		}
		echo "</td>";
		if (($k+1) % 7 == 0) echo "</tr>";
		$k++;
	}
?>
</table>

	
	

	
	</div>
</div>
<?php 
	// SOME FOOTER HERE
	include 'footer.php';
?>
