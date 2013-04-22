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
                        var html = '<table style="border:0px ; " cellspacing="5"><tr><td width="100"><b>With</b></td><td width="150"><b>Subject</b></td><td width="50%"><b>Message</b></td><td width="110"><b>Date</b></td></tr>';

                        $.each(data.dialogs, function(entryIndex, entry) {

                            if (entry.isDeleted == "0") {
                                if (entry.read == "0")
                                    flag = "color:#F308FF;";
                                else
                                    flag = "";
                                //                    html += 'place ' + entryIndex + '<br/>';
                                html += '<tr style="hover: color:#345;' + flag + ' " onclick="getConversation(' + entry.fromId + ',' + entry.toId + ',20);">';
                                //                    html += '<td>' + entry.fromId + '</td>';
                                //                    html += '<td>' + entry.toId + '</td>';
                                html += '<td valign="top">' + entry.name + '</td>';
                                html += '<td valign="top">' + entry.subject + '</td>';
                                html += '<td valign="top">' + entry.text + '</td>';
                                html += '<td valign="top">' + entry.created + '</td>';
                                html += '</tr>';
                            }
                        }
                        );
                        html += '</table>';
                        $('#all_messages').append($(html));

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