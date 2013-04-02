<?php
if (!isset($myId)) {
    die('unauthorized access');
}
//echo $myId;
?>


<button id="compose" onclick='javascript:$("#active_users").show();$("#all_messages").hide();$("#send_message").show();$("#new_message").hide();'>Compose</button>
<button id="inbox">Conversation</button>
<br>
<br>

<script>
    $(document).ready(function() {
        $("#all_messages").show();
        $("#send_message").hide();

        $.post("api/?getDialogs", 
        {},
        function(data) {
                    
            if(data.success){
                $('#all_messages').html('');
                //window.location.replace("index.php");
                //$("#menubar").text("HELLO");
                var html = '<table style="border:0px ; "><tr><td width="100"><b>With</b></td><td width="150"><b>Subject</b></td><td width="50%"><b>Message</b></td><td width="110"><b>Date</b></td></tr>';

                $.each(data.dialogs, function(entryIndex, entry) {
                    //                    html += 'place ' + entryIndex + '<br/>';
                    html += '<tr style="hover: color:#345;" onclick="getConversation('+entry.fromId+','+entry.toId+',20);">';
                    //                    html += '<td>' + entry.fromId + '</td>';
                    //                    html += '<td>' + entry.toId + '</td>';
                    html += '<td>' + entry.name + '</td>';
                    html += '<td>' + entry.subject + '</td>';
                    html += '<td>' + entry.text + '</td>';
                    html += '<td>' + entry.created + '</td>';
                    html += '</tr>';
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

<div id="all_messages" style="display:block">
</div>

<div id="new_message" style="display:none">
    <form method="post" id="msg_live">
        <input name="to" id="to_live" type ="hidden"> <br /> <br />
        Subject: <br />
        <input name ="subject" id="subject_live" type="text"> <br /> <br />
        Body: <br />
        <textarea name="text" id="text_live" cols="40" rows="5"></textarea> <br />
        <input type="button" name="sendmail_live" id="sendmail_live" value="Send"> <!-- &nbsp; <input type="button" name="cancel" value="Cancel"> -->
    </form>
</div>


<div id="send_message" style="display:none">

    <form method="post" id="msg">
        To:
        <div id="active_users" style="display:none">
        </div>

        <input name="to" id="to" type ="hidden"> <br /> <br />
        Subject: <br />
        <input name ="subject" id="subject" type="text"> <br /> <br />
        Body: <br />
        <textarea name="text" id="text" cols="40" rows="5"></textarea> <br />
        <input type="button" name="sendmail" id="sendmail" value="Send" disabled> <!-- &nbsp; <input type="button" name="cancel" value="Cancel"> -->
    </form>
</div>
<div id="dictionary"></div>



<script>
    function getConversation(sid,rid,limit){
        if (sid==<?php echo $myId ?>) uid=rid; else if (rid==<?php echo $myId ?>) uid=sid;
        var conv = $.post("api/?getDialogById", 
        {'userId':uid, 'limit':limit},
        function(data) {
                    
            if(data.success){
                $('#all_messages').html('');
                //window.location.replace("index.php");
                //$("#menubar").text("HELLO");
                var cname=data.name;
                
                var html='';
                html += "Conversation with <b>"+data.name+'</b><br><br>';

                html +='<button id="archive" onclick=\'javascript:getConversation('+uid+','+uid+');\'>Archive</button><br><br>';

                html +='<table style="border:0px ; "><tr><td width="150"><b>#</b></td><td width="150"><b>Subject</b></td><td width="40%"><b>Message</b></td><td width="110" nowrap><b>Date</b></td></tr>';

                $.each(data.dialog, function(entryIndex, entry) {
                    //                    alert(entry.subject+' '+entry.text+' '+entry.created);
                    //                    html += 'place ' + entryIndex + '<br/>';
                    html += '<tr>';
                    //                    html += '<td>' + entry.fromId + '</td>';
                    //                    html += '<td>' + entry.toId + '</td>';
                    if (entry.fromId==<?php echo $myId ?>) html += '<td>You</td>'; else html += '<td>' + cname + '</td>';
                    html += '<td>' + entry.subject + '</td>';
                    html += '<td>' + entry.text + '</td>';
                    html += '<td>' + entry.created + '</td>';
                    //                    html += '<td>' + entry.created + '</td>';
                    html += '</tr>';
                });
                html += '</table>';
                //                alert (data.name);
                $('#all_messages').append(html);
                $('#new_message').show();
                $('#to_live').val(uid);

            }else{
                alert('Error: ' + data.error);                                                        
            }
                    
        })
        .done(function() { })
        .fail(function() {  })
        .always(function() { });
 
    }
</script>
<script>
    $("#compose").click(function() {
        //        $("#all_messages").hide();
        //        $("#send_message").show();
        //        $("#left_menu").hide();

        $.post("api/?getUserList", 
        {},
        function(data) {

            if(data.success){
                $('#active_users').html('');

                var html = '<select id="receiver" onchange="setReceiver();">';
                html += '<option>-</option>';

                $.each(data.users, function(entryIndex, entry) {
                    //                    html += 'place ' + entryIndex + '<br/>';
                    html += '<option value="' + entry.id + '">' + entry.name + '</option>';
                });
                html += '</select>';

                //                alert('test4');

                $('#active_users').append($(html));
                $('#active_users').show();


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
    $("#sendmail").click(function() {
        $("#all_messages").hide();
        $("#send_message").show();
        //        $("#left_menu").hide();

        $.post("api/?sendMail", 
        {'to' : $("#to").val(),'subject' : $("#subject").val(),'text' : $("#text").val()},
        function(data) {
                    
            if(data.success){
                alert('Message Sent!');
                $('#msg')[0].reset();
                $('#sendmail').attr("disabled", true);

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
    $("#sendmail_live").click(function() {
        //        $("#all_messages").hide();
        //        $("#send_message").show();
        //        $("#left_menu").hide();
        $.post("api/?sendMail", 
        {'to' : $("#to_live").val(),'subject' : $("#subject_live").val(),'text' : $("#text_live").val()},
        function(data) {
                    
            if(data.success){
                //                alert('Message Sent!');
                $('#msg_live')[0].reset();
                getConversation($("#to_live").val(),$("#to_live").val(),20);
                //                $('#sendmail_live').attr("disabled", true);

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
                var html = '<table style="border:0px ; "><tr><td width="100"><b>With</b></td><td width="150"><b>Subject</b></td><td width="50%"><b>Message</b></td><td width="110"><b>Date</b></td></tr>';

                $.each(data.dialogs, function(entryIndex, entry) {
                    //                    html += 'place ' + entryIndex + '<br/>';
                    html += '<tr style="hover: color:#345;" onclick="getConversation('+entry.fromId+','+entry.toId+',20);">';
                    //                    html += '<td>' + entry.fromId + '</td>';
                    //                    html += '<td>' + entry.toId + '</td>';
                    html += '<td>' + entry.name + '</td>';
                    html += '<td>' + entry.subject + '</td>';
                    html += '<td>' + entry.text + '</td>';
                    html += '<td>' + entry.created + '</td>';
                    html += '</tr>';
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
<script>
    function setReceiver(){
        uid=$('#receiver').val();
        $('#to').val(uid);
        //        $('#sendmail').active();
        $('#sendmail').attr("disabled", false);
    }

</script>
