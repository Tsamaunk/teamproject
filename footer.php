
		
		<div class="nofloat"></div>
	</div> <!-- closing the container -->

	<script type="text/javascript">
		$(window).load(function (){
			var h = $(this).height();
			if (h > $("#sideBar").height()) $("#sideBar").height(h - 1.2*$("#topBar").height());
			});
	</script>
	
</body>
</html>
