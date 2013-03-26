<?php

if (!isset($myUser) || $myUser->role != 2) {
	die('unauthorized access');
}
$con->connect();
$log = $con->getLog(100);
$con->close();
?>

<h1>Log</h1>

<table style="width: 95%;">
	<tr>
		<th style="text-align: left; width: 10%;">ID</th>
		<th style="text-align: left; width: 40%;">Event</th>
		<th style="text-align: left; width: 20%;">User</th>
		<th style="text-align: left; width: 30%;">Date/Time</th>
	</tr>
	<?php

	foreach ($log as $u) {
		echo "<tr>";
		echo "<td>" . $u->id . "</td>";
		echo "<td>" . $u->description . (trim($u->userName2) ? " (".$u->userName2.")" : "") . "</td>";
		echo "<td>" . $u->userName . "</td>";
		echo "<td>" . date("h:i a, m/d/y",$u->created). "</td>";
		echo "</tr>";
		}
		?>
</table>
