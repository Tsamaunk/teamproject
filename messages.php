<?php
if (!isset($myId)) {
    die('unauthorized access');
}
//echo $myId;
?>


<button id="compose" onclick='javascript:$("#all_messages").hide();$("#send_message").show();'>Compose</button>
<button id="inbox">Inbox</button>
<br>
<br>
<div id="all_messages" style="display:block">
</div>

<div id="send_message" style="display:none">

    <form method="post">
        To: <br />
        <input name="to" id="to" type ="text"> <br /> <br />
        Subject: <br />
        <input name ="subject" id="subject" type="text"> <br /> <br />
        Body: <br />
        <textarea name="text" id="text" cols="40" rows="5"></textarea> <br />
        <input type="button" name="sendmail" id="sendmail" value="Send"> &nbsp; <input type="button" name="cancel" value="Cancel">
    </form>
</div>
<div id="dictionary"></div>

<script>
    $("#sendmail").click(function() {
        $("#all_messages").hide();
        $("#send_message").show();
        //        $("#left_menu").hide();

        $.post("api/?sendMail", 
        {'to' : $("#to").val(),'subject' : $("#subject").val(),'text' : $("#text").val()},
        function(data) {
                    
            if(data.success){
                alert('Message Sent!'+ data.error);

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

        $.post("api/?getDialogs", 
        {},
        function(data) {
                    
            if(data.success){
                $('#all_messages').html('');
                //window.location.replace("index.php");
                //$("#menubar").text("HELLO");
                var html = '<table style="border:1px solid #000; "><tr><td width="100">From</td><td width="200">Subject</td><td>Message</td><td>Date</td></tr>';

                $.each(data.dialogs, function(entryIndex, entry) {
                    //                    html += 'place ' + entryIndex + '<br/>';
                    html += '<tr>';
                    html += '<td>' + entry.fromId + '</td>';
                    html += '<td>' + entry.subject + '</td>';
                    html += '<td>' + entry.text + '</td>';
                    html += '<td>' + entry.created + '</td>';
                });
                html += '</table>';
                $('#all_messages').append($(html));

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

