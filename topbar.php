<!-- contaner start -->
<div class="contaner">
    <div class="contaner-top">

        <div class="topbar">

            <div align="right" id="guest_menu" style="display: <?php
if ($loggedin == 0)
    echo "block"; else
    echo "none";
?>">
                <a href="#" onclick='$("#signup_form").hide();$("#login_form").toggle();'><b>Login</b></a>
                &nbsp;
                <a href="#" onclick='$("#login_form").hide();$("#signup_form").toggle();'><b>Register</b></a>
            </div>

            <div id="user_menu" align="right" style="display: <?php
                 if ($loggedin == 1)
                     echo "block"; else
                     echo "none";
?>">
                <a href="#" id="submit_logout"> <!--<?php echo $myId; ?> --> <b>Log Out</b></a>
                <!--<input type="button" name="submit"  value="Log Out" />-->
            </div>
        </div>  
    </div>

