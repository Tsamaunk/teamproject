<!-- contaner start -->
<div class="contaner">
    <div class="contaner-top" style="border:0px #000 dotted;">
        <div align="right" id="guest_menu" style="font-size:20px;border:0px #000 dotted;display: <?php
if ($loggedin == 0)
    echo "block"; else
    echo "none";
?>">            <b>
                <a style="cursor: pointer; color: #fff;" onclick='$("#signup_form").hide();$("#login_form").toggle();'>Login</a>
                &nbsp;
                <a  style="cursor: pointer; color: #fff;" onclick='$("#login_form").hide();$("#signup_form").toggle();'>Register</a>
            </b>
        </div>
        <div id="user_menu" align="right" style="font-size:20px;display: <?php
             if ($loggedin == 1)
                 echo "block"; else
                 echo "none";
                 ?>">
            <a style="cursor: pointer; color: #fff;" id="submit_logout"><b>Log Out</b></a>
            <!--<input type="button" name="submit"  value="Log Out" />-->
        </div>

