<?php
	include_once 'base.php';
	$hlp = new Helper();
	$con = new Controller();
	$users = $con->getAllAliveUsers();
	
	if (isset($_SESSION['userId']) && isset($_SESSION['userToken']) && $hlp->validToken($_SESSION['userId'], $_SESSION['userToken'])) {
	    $myId = $_SESSION['userId'];
	} else {
	    header('Location: login.php');
	    exit;
	}
	
	$myUser = $con->getUserById($myId);
	
	include_once 'header.php';
	include_once 'topbar.php';
?>

<div class="contaner-bottom">
    <?php include_once 'sidebar.php'; ?>
    <div id="alert" style="display: none"></div>
    <div class="content">

        <?php

        $users = $con->getAllAliveUsers();

        $today = new DateTime();

        $t_day = $today->format("j");
        $t_month = $today->format("n");

        $month = isset($_POST['month']) ? (int) $_POST['month'] : (isset($_SESSION['month']) ? $_SESSION['month'] : (int) $today->format('m'));
        $_SESSION['month'] = $month;

        $firstDay = new DateTime($today->format('Y') . '-' . $month . '-1');
        $maxDays = $firstDay->format('t');
        $firstDay = $firstDay->format('N');
        if ($firstDay == 7)
            $firstDay = 0;
        $firstDay = 1 - $firstDay;

        ?>

        <div id="adminConsole" class="content-box">

            <h1>Calendar</h1>

            <form id="selectMonth" method="post" action="?page=schedule">
                <label style="width: 80px;">Month</label> <select name="month"
                                                                  onchange="javascript:$('#selectMonth').submit();">
                    <option value="1" <?php echo $month == 1 ? "selected='selected'" : ''; ?>>January</option>
                    <option value="2" <?php echo $month == 2 ? "selected='selected'" : ''; ?>>February</option>
                    <option value="3" <?php echo $month == 3 ? "selected='selected'" : ''; ?>>March</option>
                    <option value="4" <?php echo $month == 4 ? "selected='selected'" : ''; ?>>April</option>
                    <option value="5" <?php echo $month == 5 ? "selected='selected'" : ''; ?>>May</option>
                    <option value="6" <?php echo $month == 6 ? "selected='selected'" : ''; ?>>June</option>
                    <option value="7" <?php echo $month == 7 ? "selected='selected'" : ''; ?>>July</option>
                    <option value="8" <?php echo $month == 8 ? "selected='selected'" : ''; ?>>August</option>
                    <option value="9" <?php echo $month == 9 ? "selected='selected'" : ''; ?>>September</option>
                    <option value="10" <?php echo $month == 10 ? "selected='selected'" : ''; ?>>October</option>
                    <option value="11" <?php echo $month == 11 ? "selected='selected'" : ''; ?>>November</option>
                    <option value="12" <?php echo $month == 12 ? "selected='selected'" : ''; ?>>December</option>
                </select>
            </form>

            <table style="width: 95%;" id="calendar">
                <tr>
                    <th style="width: 14.2%;">Sunday</th>
                    <th style="width: 14.2%;">Monday</th>
                    <th style="width: 14.2%;">Tuesday</th>
                    <th style="width: 14.2%;">Wednesday</th>
                    <th style="width: 14.2%;">Thursday</th>
                    <th style="width: 14.2%;">Friday</th>
                    <th style="width: 14.2%;">Saturday</th>
                </tr>
                <?php
                $k = 0;
                for ($i = $firstDay; $i <= $maxDays; $i++) {
                    if ($k == 0 || $k % 7 == 0)
                        echo "<tr>";
                    if ($i>0)
                    	echo "<td id='d_$i'>";
                    else echo "<td>";

                    if ($i > 0)
                        echo "<span class='date'>$i</span><br>";
                    $index = $today->format('Y') . '-' . ($month > 9 ? $month : '0' . $month) . '-' . ($i > 9 ? $i : '0' . $i);
                    $cur = $cal[$index];

                    if ($cur) {
                        if ($cur['rd']) {
                            echo "<span id='spn_" . $cur['rd']->id . "' class='rd'>" . ($cur['rd']->userName) . "</span>";
                        } else {
                            
                        }

                        if (count($cur['ra'])) {
                            foreach ($cur['ra'] as $cu)
                                echo "<span id='spn_" . $cu->id . "' class='ra'>" . ($cu->userName) . "</span>";
                        }

                        echo "<br>";
                    } elseif ($i > 0) {
                        echo "<br>";

                        echo "<br>";
                    }
                    echo "</td>";
                    if (($k + 1) % 7 == 0)
                        echo "</tr>";
                    $k++;
                }
                ?>
            </table>

        </div>




    </div>
</div>
<div id="mask">
</div>
<div id="field">
    <h2 class="caption"></h2>
    <p class="text"></p>
    <input type="hidden" id="switchSlot" name="switchSlot">
    <button type="button" onclick="javascript:confirmSwitch();">Submit</button>
    <br><br><br><br><br>
    <p style="text-align:right;"><a href="javascript:closeMask();">Close</a></p>
</div>
<script>
                                                                      var userId = <?php echo $myUser->userId; ?>;
                                                                      var username = "<?php echo $myUser->firstName . " " . $myUser->lastName; ?>";
                                                                      var type = 0;
                                                                      var data = "";
                                                                      $(window).load(function() {
                                                                          $('.ra').mouseenter(function() {
                                                                              //            alert ();
                                                                              if (!$(this).attr('data-tmp'))
                                                                                  $(this).attr('data-tmp', $(this).html());
                                                                              var thisId = $(this).attr('id').split('_');
                                                                              if ($(this).attr('data-tmp') != username)
                                                                                  $(this).html('<a href="javascript:raSwitch(' + thisId[1] + ');">Switch</a>');
                                                                          });
                                                                          $('.ra').mouseleave(function() {
                                                                              $(this).html($(this).attr('data-tmp'));
                                                                              $(this).removeAttr('data-tmp');
                                                                          });
                                                                          $('#field').css('left', ($(window).width() / 2 - $('#field').width() / 2) + 'px');
                                                                          $('#field').css('top', ($(window).height() / 2 - $('#field').height() / 2) + 'px');
                                                                      });


                                                                      function raSwitch(data2) {
                                                                          type = 1;
                                                                          data = data2;
                                                                          $('#field .caption').text('Switch RA on duty with this guy?');
                                                                          $('#field .text').text('Are You Sure ' + data2 + ' ?');
                                                                          $('#switchSlot').val(data2);
                                                                          $('#mask').fadeIn(300);
                                                                          $('#field').fadeIn(300);
                                                                      }
                                                                      function closeMask() {
                                                                          $("#field").fadeOut(100);
                                                                          $("#mask").fadeOut(100)
                                                                      }

                                                                      function confirmSwitch() {

                                                                          //        alert($('#switchSlot').val());
                                                                          //        return;
                                                                          var switchId = $('#switchSlot').val();

                                                                          $.post("api/?sendMail",
                                                                                  {'to': '1', 'subject': 'Switch RA', 'text': 'Switch me with:' + switchId},
                                                                          function(data) {

                                                                              if (data.success) {
                                                                                  $("#alert").html("Message Sent!");
                                                                                  $("#alert").show();
                                                                                  //alert('Message Sent!');
                                                                                  //                $('#msg')[0].reset();
                                                                                  //                $('#sendmail').attr("disabled", true);

                                                                              } else {
                                                                                  alert('Error: ' + data.error);
                                                                              }

                                                                          })
                                                                                  .done(function(   ) {
                                                                          })
                                                                                  .fail(function() {
                                                                          })
                                                                                  .always(function() {
                                                                              $('#mask').fadeOut(300);
                                                                              $('#field').fadeOut(300);
                                                                          },
                                                                                  "json");

                                                                      }
</script>

<?php
include 'footer.php';
?>