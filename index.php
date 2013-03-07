<html>
<?php
include_once 'head.php';
include_once 'topBar.php';
include_once 'sideBar.php';
?>

<div id="content">
<center>
<h1 align="center">Welcome to the UAlbany Residential life</h1>

    <?php
    if (!isset($_SESSION['userId']) && !isset($_SESSION['userToken']) && !$hlp->validToken($_SESSION['userId'], $_SESSION['userToken'])) {
         echo "<span style=\"color:#0000FF\"> Please <a href=\"login.php\">login</a> to continue using the portal.</span>";
    } else {
		echo "<span style=\"color:#0000FF\">Hi! You are now logged in.</span>";
    }
?>
</center>
</div>
<?php
include_once 'footer.php';
?>
</div>
</body>
</html>