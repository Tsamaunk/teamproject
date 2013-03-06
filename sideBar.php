<?php 

	if (!isset($_SESSION['userId']) && !isset($_SESSION['userToken']) && !$hlp->validToken($_SESSION['userId'], $_SESSION['userToken'])) {
		echo "<div id=\"sideBar\"></div>";
		return;
	}
        else{


?>

<div id="sideBar">
<br />
<table height="177" border="0">
  <tr>
    <td width="104">Notifications</td>
  </tr>
  <tr>
    <td>Calender</td>
  </tr>
</table>

</div>

<?php

        }
        
        ?>