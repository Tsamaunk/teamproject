<?php
if (!isset($myId)) {
    die('unauthorized access');
}
//echo $myId;
?>

<div id="alert" style="display: none"></div>


<div id="all_messages" style="display:block">
</div>


<script>
    $(document).ready(function() {
        inbox();
    }
    );
</script>
<script>

    function inbox()
    {

        $.post("api/?getNot",
                {},
                function(data) {

                    if (data.success) {
                $('#all_messages').html('');
                //window.location.replace("index.php");
                //$("#menubar").text("HELLO");
                var cname = data.name;

                var html = '';

                //html += '<button id="archive" onclick=\'javascript:getConversation(' + uid + ',' + uid + ');\'>Archive</button><br><br>';

                html += '<table style="border:0px ; cellspacing="5"><tr><td width="80%"><b>Message</b></td><td width="110" nowrap><b>Date</b></td></tr>';

                $.each(data.notifications, function(entryIndex, entry) {
                    //                    alert(entry.subject+' '+entry.text+' '+entry.created);
                    //                    html += 'place ' + entryIndex + '<br/>';
                    html += '<tr>';
                    //                    html += '<td>' + entry.fromId + '</td>';
                    //                    html += '<td>' + entry.toId + '</td>';
                    html += '<td valign="top">' + entry.text + '</td>';
                    html += '<td valign="top">' + entry.created + '</td>';
                    //                    html += '<td>' + entry.created + '</td>';
                    html += '</tr>';
                });
                html += '</table>';
                //                alert (data.name);
                $('#all_messages').append(html);
//                $('#new_message').show();

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