<!-- contaner start -->
<div class="contaner">
    <div class="contaner-top">
        <div id="guest_menu" style="display: <?php
if ($loggedin == 0)
    echo "block"; else
    echo "none";
?>">            
            <button onclick='$("#signup_form").hide();$("#login_form").toggle();'>Login</button>
            <button onclick='$("#login_form").hide();$("#signup_form").toggle();'>Register</button>
        </div>
        <div id="user_menu" style="display: <?php
             if ($loggedin == 1)
                 echo "block"; else
                 echo "none";
?>">
            <input type="button" name="submit" id="submit_logout" value="Log Out" />
        </div>

