<?php
include_once 'base.php'; // THIS THE THE BACKEND CONNECTOR
$hlp = new Helper();  // CREATE THE HELPER

if (!isset($_SESSION['userId']) && !isset($_SESSION['userToken']) && !$hlp->validToken($_SESSION['userId'], $_SESSION['userToken'])) {
    $loggedin = 0;
} else {
    $loggedin = 1;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
    <head>
        <script type="text/javascript" src="jquery.min.js"></script>
        <script type="text/javascript" src="jquery.validate.js"></script>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Department of Residential Life- University at Albany - SUNY </title>
        <link rel="stylesheet" type="text/css" href="html_all_2.css" />	
        <link rel="stylesheet" href="html_all_2_print.css" media="print" type="text/css" />
        <link rel="stylesheet" type="text/css" href="assessment_icon.css" />

    </head>
    <body>

        <!--main container-->
        <div id="main">
            <!-- header start -->
            <!--header-->
            <div id="header">
                <div class="header-holder">
                    <strong class="logo"><img src="http://www.albany.edu/templates_2009/images/Header_UAlbany_Banner_Logo_Standard.gif" alt="University At Albany" /></strong>
                </div>
                <!-- header end -->
                <!-- content holder start-->
                <div id="content">
                    <!-- contaner start -->
                    <div class="contaner">
                        <div class="contaner-top">
                            <div class="contaner-bottom">
                                <!-- content -->
                                <div class="content">
                                    <div class="img">
                                        <span><img></img></span>
                                        <div><span>&nbsp;</span></div>
                                    </div>
                                    <div class="content-box">
                                        <h1>Project Switch</h1>
                                        <p class="intro">Welcome to the Department of Residential Life!   </p>
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



                                        <!-- text-box -->
                                        <div class="text-box" style="align: left">
                                            <div id="login_form" style="display: none">
                                                <form action="index.php" id="login" method="post">
                                                    E-mail:<br />
                                                    <input name="email" id="email" type="text" /><br />
                                                    Password:<br />
                                                    <input name="password" id="password" type="password" /><br />
                                                    <input type="button" name="submit" id="submit_login" value="Login" />                                                      
                                                </form>
                                            </div>


                                            <div id="signup_form" style="display:none">
                                                <form id="signup" method="post" >
                                                	<span style="float: left">                                                    
                                                    First Name:<br />
                                                    <input type="text" name="SU_firstName" id="SU_firstName" /><br />
                                                    Last Name:<br />
                                                    <input type="text" name="SU_lastName" id="SU_lastName" /><br />
                                                    E-mail:<br />
                                                    <input type="text" name="SU_email" id="SU_email" /><br />                                                    
                                                    </span>
                                                    <span style="float: right">
                                                    Password:<br />
                                                    <input type="password" name="SU_password" id="SU_password" /><br />
                                                    Confirm Password:<br />
                                                    <input type="password" name="SU_cpass" id="SU_cpass" /><br />
                                                    <input type="button" name="submit" id="submit_signup" value="Sign Up"" />
                                                    </span><br />                                                                                                        
                                                </form>
                                            </div>



                                            <?php ?>

                                            <p class="intro"><?php //echo $loggedin      ?></p>
                                            <div id="result"></div>

                                            <? ?>


                                            <script>
                                                $("#submit_logout").click(function() {

                                                    $.post("api/?logout", 
                                                    {'email' : 'test@test.com'},
                                                    function(data) {
            
                                                        if(data.success){
                                                            //window.location.replace("index.php");
                                                            //$("#menubar").text("HELLO");
                                                            $("#user_menu").hide();
                                                            $("#guest_menu").show();
                                                            $("#left_menu").hide();

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
                                                $("#submit_login").click(function() {

                                                    $.post("api/?login", 
                                                    {'email' : $("#email").val(), 'password' : $("#password").val()},
                                                    function(data) {
            
                                                        if(data.success){
                                                            //window.location.replace("index.php");
                                                            //$("#menubar").text("HELLO");
                                                            $("#guest_menu").hide();
                                                            $("#login_form").hide();
                                                            $("#user_menu").show();
                                                            $("#left_menu").show();
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
                                            </script>

                                        </div>
                                        <!-- frame-box -->
                                    </div>
                                </div>
                                <!-- column -->
                                <div class="column" id="left_menu" style="display: <?php
                                            if ($loggedin == 1)
                                                echo "block"; else
                                                echo "none";
                                            ?>">
                                    <!-- column-links -->
                                    <!-- column-links -->
                                    <br /> <br />
                                    <ul class="column-links-alt">
                                        <li><a href="">Notifications</a></li>
                                        <li><a href="">My Calender</a></li>                                    
                                    </ul>                                   
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- contaner end -->
                    <!-- sidebar start -->
                    <div class="sidebar"  style="display: none">
                        <div class="gray-box">
                            <div class="gray-box-body">
                                <div class="gray-box-text">
                                    <ul>
                                        <li>
                                            <h3>Residential	 Life</h3>
                                            University at Albany, State University of New York
                                        </li>
                                        <li>
                                            <address>
                                                <span>State Quad U-Lounge</span>
                                                <span>1400 Washington Avenue</span>
                                                <span>Albany, NY 12222</span>
                                                <span>PHONE (518) 442-5875</span>
                                                <span>FAX   (518) 442-5835</span>
                                            </address>

                                        </li>
                                    </ul>

                                </div><!-- close div gray-box-text -->
                            </div><!-- close div gray-box-body -->
                        </div><!-- close div gray-box -->
                    </div>
                    <!-- sidebar end -->
                </div>
            </div>
        </div>
        <!-- content holder end-->
        <!--footer-->
        <!--footer-->

        <div style="clear:both"></div>
<br /> <br /><br /> <br /><br /> <br />
        <div id="footer">

            <div class="footer-holder">

                <address>

                    <span>University at Albany, State University of New York</span>

                    <span>1400 Washington Avenue, Albany, NY 12222 . Phone (518) 442-3300</span>

                </address>

                <ul>

                    <li><a href="https://abhiiem05.wix.com/group-6#!contact/czpl">Contact Us</a></li>
                </ul>

            </div><!--close div footer-holder-->

        </div><!--close div footer-->

        </div><!--close div main-->

    </body>
</html>