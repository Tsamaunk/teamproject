<button id="compose">Compose</button>
<button id="inbox">Inbox</button>
<button id="sent">Sent</button>
<br>
<br>
<div id="all_messages" style="display:block">

    <table style="border: #000 dotted">
        <tr>
            <td width="100">From</td>
            <td width="200">Subject</td>
            <td>Date</td>
        </tr>
        <?php
        for ($i = 0; $i < 5; $i++) {
            ?>
            <tr>
                <td>xxx <?php echo $i ?></td>
                <td>yyyy</td>
                <td>11-11-2013</td>
            </tr>
            <?php
        }
        ?>

    </table>
</div>

<div id="send_message" style="display:none">

    <form action="index.php" id="login" method="post">
        To: <br />
        <input name="email" type ="emil"> <br /> <br />
        Subject: <br />
        <input name ="subject" type="text"> <br /> <br />
        Body: <br />
        <textarea name="msg" cols="60" rows="20"></textarea> <br />
        <input type="button" name="submit" value="Send"> &nbsp; <input type="button" name="cancel" value="Cancel">
    </form>
</div>


<script>
    $("#compose").click(function() {

        $("#all_messages").hide();
        $("#send_message").show();
//        $("#left_menu").hide();

        return;
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
    $("#inbox").click(function() {

        $("#all_messages").show();
        $("#send_message").hide();
//        $("#left_menu").hide();

        return;
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

