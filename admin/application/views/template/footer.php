
		<footer class="page-footer white">
			<div class="footer-copyright woowtix-bg red">
				<div class="container center">
					Copyright Hit Corporation &copy;  2020, All Rights Reserved.
				</div>
			</div>
		</footer>
		<script>
		if ($(window).width() > 890) {
			$(document).ajaxComplete(function( event, request, settings ) {
		 	var sidebarLeftHeight = $("aside ul#sidenav").height();
			$("main").css("min-height", sidebarLeftHeight + "px");
			var elmnt = document.getElementById("body-content");
			var h = elmnt.scrollHeight;
			$("aside").css("height", h + "px");
			});
			$(document).ready(function () {
				$("a.close-sidebar").click(function() {
					close();
				}); 
				$("a.open-sidebar").click(function() {
					open();
				});
				if (window.location.href.indexOf('dashboard') > -1) {
					close();
				}
				function close() {
					$("a.close-sidebar, aside").css('display','none');
					$("a.open-sidebar").css('display','block');
					$("ul#sidenav").css("transform", "translateX(-100%)");
					$("header, main, footer").css("padding-left", "0px");
					$("main > .container, .nav-wrapper").css("max-width", "100%");
				}
				function open() {
					$("a.open-sidebar").css('display','none');
					$("a.close-sidebar, aside").css('display','block');
					$("ul#sidenav").css("transform", "translateX(0)");
					$("header, main, footer").css("padding-left", "300px");
				}
 
			});
		}
		else {
			$(document).ready(function () {
				$("a.close-sidebar, a.open-sidebar").css('display','none');
			});
		}
		</script>
  	</body>
</html>
