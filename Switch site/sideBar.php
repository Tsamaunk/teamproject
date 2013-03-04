<?php 

	if (!isset($_SESSION['userId'])) {
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
    <td>Stuff</td>
  </tr>
  <tr>
    <td>Stuff</td>
  </tr>
  <tr>
    <td>Stuff</td>
  </tr>
  <tr>
    <td>Stuff</td>
  </tr>
</table>

</div>

<?php

        }
        
        ?>