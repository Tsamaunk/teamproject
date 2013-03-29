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
                
                window.location.replace("index.php");

            }else{
                alert('Error: ' + data.error);                                                        
            }
                    
        })
        .done(function() {  })
        .fail(function() {  })
        .always(function() {  },
        "json");

    });

    $(window).resize(function(){
    	windowResize();
        });
    $(window).load(function(){
        windowResize();
        
    });
    function windowResize() {
   		$('div.column').height($('#header').height() - $('div.topbar').height() - $('#footer').height() - 100);
    }                 
</script>


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

</div>	<!--close div main - updated by MG --->

</body>
</html>