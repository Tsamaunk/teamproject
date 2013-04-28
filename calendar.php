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
                    if ($i > 0)
                        echo "<td id='d_$i' class='d_day' >";
                    else
                        echo "<td>";

                    if ($i > 0)
                        echo "<span class='date'>$i</span><br>";

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
    <h1 class="caption"></h1>
    <p class="text" id="msg"></p>


    <br><br><br><br><br>
    <p style="text-align:right;"><a href="javascript:closeMask();">Close</a></p>
</div>

<input type="hidden" name="month" id="month" value="<?php echo $month ?>">
<input type="hidden" name="day1" id="day1">
<input type="hidden" name="day2" id="day2">
<input type="hidden" name="withUser" id="withUser">


<script>
                                                                      var myId =<?php echo $myId ?>;
                                                                      $(document).ready(function() {
                                                                          loadSwitch();

                                                                      });

</script>
<script>

    function loadSwitch() {
        var ht = "";
        var cell = "";
        $.getJSON('api/?getCalendar', {month: "<?php echo $month ?>"}).done(function(data) {
            $.each(data.calendar, function(key, val) {
                ht = '';
                cell = '<span class="date">' + key.substring(2) + '</span><br>';
                //ht = "day: "+key+"<br>";
                $.each(val, function(ky, vl) {
                    if (ky == 'rd') {
                        ht += "   " + ky + ":" + vl.userName + "[" + vl.id + "] \t\t type:" + vl.type + "<br>";
                        cell += '<span class="rd">' + vl.userName + "</span>";
                        ;
                    }
                    else {
                        $.each(vl, function(kyx, vlx) {
                            if (vlx.type == "c") {
                                if (vlx.userId == myId)
                                    cell += '<span class="ra"><b><a href="javascript:switchFrom(\'' + key + '\');">' + vlx.userName + "</a></b></span>";
                                else
                                    cell += '<span class="ra"><a href="javascript:switchTo(' + vlx.userId + ',\'' + key + '\');">' + vlx.userName + "</a></span>";

                            }
                            else
                            {
                                cell += '<span class="' + vlx.type + '">' + vlx.userName + '</span>';

                                //alert (state);
                            }

//								  ht += "   "+kyx+":"+vlx.userName+"["+vlx.id+"] \t\t type:"+vlx.type+" uid:"+vlx.userId+"<br>";
//                                                  cell+="<br>";

                        });
                    }
                });
                $('#' + key).html(cell);

            });
            //  $('#holder').append(ht);
        });


        /*/
         $.post("api/?addSwitch", {month:4, day1:10, day2:20, withUser:4},
         function(data){
         if(!data.success){
         alert('error: ' + data.error);                                                        
         } else {
         
         $('#holder').html('success');
         }    
         });       /**/


    }

    function switchFrom(d_day) {
        for (i = 1; i <= 31; i++)
        {
            $('#d_' + i).css('background-color', '');
        }

        $('#day1').val(d_day.substring(2));
        $('#' + d_day).css('background-color', '#ccc');

    }

    function switchTo(userId, d_day) {
        for (i = 1; i <= 31; i++)
        {
            if ($('#day1').val() != i)
                $('#d_' + i).css('background-color', '');
        }


        if ($('#day1').val() != '') {
            $('#day2').val(d_day.substring(2));
            $('#withUser').val(userId);

            $('#' + d_day).css('background-color', '#aaa');

            // 	$('#field .text').text("R u sure about switching with user "+$('#withUser').val()+" From:"+$('#day1').val()+" To:"+$('#day2').val()+" ?");
            $('#msg').html("Are you sure about switching with user " + $('#withUser').val() + " From:" + $('#day1').val() + " To:" + $('#day2').val() + " ?" + '<button type="button" onclick="javascript:doSwitch();window.location.reload();">Confirm</button>');

            $('#mask').fadeIn(300);
            $('#field').fadeIn(300);

            //if (confirm("R u sure about switching with user "+$('#withUser').val()+" From:"+$('#day1').val()+" To:"+$('#day2').val()+" ?"))
            {
                //        doSwitch();
            }
//            else
            {
//                    return;
            }
        }
        else {
// $('#field .text').text("Choose src!");
            $('#msg').html("Please choose source first");
            $('#mask').fadeIn(300);
            $('#field').fadeIn(300);
//    alert("Choose src first!");
        }
    }
</script>

<script>
    function doSwitch()
    {

        if ($('#withUser').val() && $('#day1').val() && $('#day2').val())
        {
            a = 1;
        }
        else
            return;

        $.post("api/?addSwitch",
                {'month': $("#month").val(), 'day1': $("#day1").val(), 'day2': $("#day2").val(), 'withUser': $("#withUser").val()},
        function(data) {

            if (data.success) {
                $("#day1").val();
                $("#day2").val();
                $("#withUser").val();
                for (i = 1; i <= 31; i++)
                {
                    $('#d_' + i).css('background-color', '');
                }
                closeMask();
                loadSwitch();
            } else {
                alert('Error: ' + data.error);
            }

        })
                .done(function() {
        })
                .fail(function() {
        })
                .always(function() {
        },
                "json");





    }

</script>        

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


    function closeMask() {
        $("#field").fadeOut(100);
        $("#mask").fadeOut(100)
        for (i = 1; i <= 31; i++)
        {
            $('#d_' + i).css('background-color', '');
        }
    }
    $(document).keyup(function(e) {
        if (e.keyCode == 27) {
            $("#field").fadeOut(100);
            $("#mask").fadeOut(100);
        }   // esc
        /*for (i = 1; i <= 31; i++)
        {
            $('#d_' + i).css('background-color', '');
        }*/
		$('.d_day').css('background-color', '');
    });


</script>

<?php
include 'footer.php';
?>