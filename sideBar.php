<?php 

	if (!(isset($_SESSION['userId']) && isset($_SESSION['userToken']) && $hlp->validToken($_SESSION['userId'], $_SESSION['userToken']))) :
		echo "<div id=\"sideBar\"></div>";
		return;
	endif;
?>

<div id="sideBar">

<?php if ($_SESSION['userRole'] == 1):?>
	<a href="index.php">Calender</a>
	<a href="mailing.php">Mailing</a>
	<a href="switch.php">My Switches</a>
	<a href="schedule.php">My Schedule</a>

<?php elseif ($_SESSION['userRole'] == 2):?>

	<a href="index.php">Calender</a>
	<a href="mailing.php">Mailing</a>
	<a href="users.php">Users</a>
	<a href="updateSchedule.php">Schedule</a>
	<a href="switch.php">History</a>
	<a href="log.php">Log</a>


<?php endif;?>

</div>
