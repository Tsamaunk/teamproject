<?php
include_once 'header.php';
include_once 'topbar.php';
// if ($loggedin) {
// 	header('location: calendar.php');
// 	exit;
// }
?>

<div class="contaner-bottom">
    <!--<div class="column-links-alt">-->
<?php include_once 'sidebar.php'; ?>

    <!--</div>-->
    <!-- content -->
    <div class="content">

        <div class="content-box">
            <h1>Project Switch</h1>
            <p class="intro">Department of Residential Life</p>

            <?php
            if ($_GET['task'] == "") {
                ?>


                <!-- text-box -->
                <div class="text-box" >
                    <div id="login_form" style="display: none">
                        <form id="login" method="post">
                            E-mail:<br />
                            <input name="email" id="email" type="text" /><br />
                            Password:<br />
                            <input name="password" id="password" type="password" /><br />
                            <button type="button" name="submit" id="submit_login">Login</button>                                                      
                        </form>
                    </div>


                    <div id="signup_form" style="display:none">
                        <form id="signup" method="post" >
                            First Name:<br />
                            <input type="text" name="SU_firstName" id="SU_firstName" /><br />
                            Last Name:<br />
                            <input type="text" name="SU_lastName" id="SU_lastName" /><br />                                                        
                            E-mail:<br />
                            <input type="text" name="SU_email" id="SU_email" /><br />                                                                                                    
                            Password:<br />
                            <input type="password" name="SU_password" id="SU_password" /><br />                                                                                                        
                            Confirm Password:<br />
                            <input type="password" name="SU_cpass" id="SU_cpass" /><br />
                            <button type="button" name="submit" id="submit_signup">Sign up</button>
                        </form>
                    </div>




                    <p class="intro"><?php //echo $loggedin                       ?></p>
                    <div id="result"></div>

    <? ?>

                    <script>
                        $("#submit_login").click(function() {

                            $.post("api/?login", 
                            {'email' : $("#email").val(), 'password' : $("#password").val()},
                            function(data) {
                                
                                if(data.success){
                                    window.location.reload();
                                }else{
                                    alert('Error: ' + data.error);                                                        
                                }
                                
                            })
                            .done(function() {  })
                            .fail(function() {  })
                            .always(function() {  },
                            "json");

                        });                                              
                    </script>
                    <script>
                        $("#submit_signup").click(function() {
                            $("#signup").validate({
                                rules: {
                                    SU_password: {
                                        required: true, minlength: 6
                                    },
                                    SU_cpass: {
                                        required: true, 
                                        equalTo: password, 
                                        minlength: 6
                                    },
                                    SU_email: {
                                        required: true, 
                                        email: true
                                    },
                                    SU_firstName: {
                                        required: true
                                    },
                                    SU_lastName: {
                                        required: true
                                    }

                                }
                            });

                            $.post("api/?signup", 
                            {'email' : $("#SU_email").val(), 'password' : $("#SU_password").val(), 'firstName' : $("#SU_firstName").val(), 'lastName' : $("#SU_lastName").val()},
                            function(data) {
                                
                                if(data.success){
                                    $("#result").html("You Are succesfully Registered and waiting for approval.");
                                    document.getElementById('signup').reset();
                                    $("#signup_form").hide();

                                }else{
                                    alert('Error: ' + data.error);                                                        
                                }
                                
                            })
                            .done(function() {  })
                            .fail(function() {  })
                            .always(function() {  },
                            "json");

                        });

						$(document).ready(function(){
								$('#password').keyup(function(event) {
								if (event.which == 13)
									$("#submit_login").trigger('click');
								})});
                        
                    </script>

                    <?php
                } else if ($_GET['task'] == "message" && $loggedin) {
                    include 'messages.php';
                }
                ?>

            </div>
            <!-- frame-box -->
        </div> <!-- content-box -->
    </div> <!-- content -->
</div> <!-- container-bottom -->

</div> <!-- content ----- was open in header -->
</div> <!-- header ----- was open in header -->


<div style="clear:both;"></div>


<?php
include_once 'footer.php';
?>

