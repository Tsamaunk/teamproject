<!-- column -->


<div class="column">
<div class="column" id="left_menu" style="width:200px;display: <?php
		if ($loggedin == 1)
			echo "block"; else
			echo "none";
		?>">
    
    
    
	<!-- column-links -->
	<!-- column-links -->
	<!-- <br /> <br /> <br /> <br /> <br /> -->
	<ul class="column-links-alt"><br>
		<li><a href="index.php">Home</a></li>
		<li><a href="admin.php">Profile</a></li>
		<li><a href="calendar.php">Calendar</a></li>
		<li><a href="admin.php?page=myschedule">My Schedule</a></li>
		<?php 
			if (isset($_SESSION['userRole']) && $_SESSION['userRole']==2) {
				echo "<li><a href=\"admin.php?page=schedule\">Edit Calendar</a></li>";
				echo "<li><a href=\"admin.php?page=users\">Users</a></li>";
				echo "<li><a href=\"admin.php?page=log\">Log</a></li>";
			}
		?>
		<li><a href="#">Notifications</a></li>                                    
		<li><a href="index.php?task=message">Messages</a></li>
	</ul>
</div>
</div>
