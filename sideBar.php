<?php 

	if (!isset($_SESSION['userId']) && !isset($_SESSION['userToken']) && !$hlp->validToken($_SESSION['userId'], $_SESSION['userToken'])) {
		echo "<div id=\"sideBar\"></div>";
		return;
	}
        else{


?>
<style type="text/css">
<!--
.style1 {color: #FFFFFF}
-->
</style>


<div id="sideBar">
<br />
<table height="177" border="0">
  <tr>
    <td width="104"><a href="#" class="style1">Notifications</a></td>
  </tr>
  <tr>
    <td><a href="#" class="style1">Calender</a></td>
  </tr>
</table>

</div>

<?php

        }
        
        ?>