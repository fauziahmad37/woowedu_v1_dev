<?php
	//ini_set('date.timezone', 'Asia/Jakarta');
?>
<!DOCTYPE html>
<html lang="en">
	<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>
	<link rel="apple-touch-icon" sizes="57x57" href="<?php echo base_url('assets/images/favicon'); ?>/apple-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="<?php echo base_url('assets/images/favicon'); ?>/apple-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="<?php echo base_url('assets/images/favicon'); ?>/apple-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="<?php echo base_url('assets/images/favicon'); ?>/apple-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="<?php echo base_url('assets/images/favicon'); ?>/apple-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="<?php echo base_url('assets/images/favicon'); ?>/apple-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="<?php echo base_url('assets/images/favicon'); ?>/apple-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="<?php echo base_url('assets/images/favicon'); ?>/apple-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="<?php echo base_url('assets/images/favicon'); ?>/apple-icon-180x180.png">
	<link rel="icon" type="image/png" sizes="192x192"  href="<?php echo base_url('assets/images/favicon'); ?>/android-icon-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="<?php echo base_url('assets/images/favicon'); ?>/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="<?php echo base_url('assets/images/favicon'); ?>/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url('assets/images/favicon'); ?>/favicon-16x16.png">
	<link rel="manifest" href="<?php echo base_url('assets/images/favicon'); ?>/manifest.json">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="<?php echo base_url('assets/images/favicon'); ?>/ms-icon-144x144.png">
	<meta name="theme-color" content="#ffffff">
    <title><?php echo $pageTitle; ?> | Woow VMS Admin</title>
		<link href="<?php echo base_url('assets/materialize/css/icon.css'); ?>" rel="stylesheet">
	    <?php
	 //   if (!$this->session->userdata('style')) {
	  	  $style = $this->session->userdata('style');
	  //  }
	  //  else {
	 //	  $style = 'blue-light';
	 //   }	
	    ?>
		<link href="<?php echo base_url('assets/css/styles/'.$style.'.css'); ?>" rel="stylesheet">
		<link href="<?php echo base_url('assets/fontawesome/css/font-awesome.min.css'); ?>" rel="stylesheet">
		<link href="<?php echo base_url('assets/materialize/css/materialize.css'); ?>" type="text/css" rel="stylesheet" media="screen,projection"/>
		<link href="<?php echo base_url('assets/css/materialize.clockpicker.css'); ?>" type="text/css" rel="stylesheet" media="screen,projection"/>
		<link href="<?php echo base_url('assets/css/woow.css'); ?>" type="text/css" rel="stylesheet" media="screen,projection"/>
		<style>
		.side-nav.fixed li a{
			padding-left:16px !important;
		}
		.side-nav li > a > i {
			margin-right:10px !important;
		}
		.side-nav.fixed .collapsible-body li a{
			padding-left:26px !important;
		}
		.side-nav {
			box-shadow:none !important;
		}
		aside {
			box-shadow:0 2px 2px 0 rgba(0, 0, 0, 0.14), 0 1px 5px 0 rgba(0, 0, 0, 0.12), 0 3px 1px -2px rgba(0, 0, 0, 0.2) !important;
		}
		
		tr#dev-hidden {
			display:none;
		}
		.container {
			max-width:100% !important;
		}

			img {
			    image-orientation: from-image;
			}
			ul#sidenav li.active a, ul#sidenav li.active i {
				color:#ffffff !important;
			}
			.blinking{
			    animation:blinkingText 1.2s infinite;
			}
			@keyframes blinkingText{
			    0%{     color: #000;    }
			    49%{    color: #000; }
			    60%{    color: transparent; }
			    99%{    color:transparent;  }
			    100%{   color: #000;    }
			}
			a.close-sidebar, a.open-sidebar {
				float: left;
				position: relative;
				z-index: 1;
				height: 56px;
				margin: 0 18px;
			}
		
		</style>
		<!--  Scripts-->
		<script src="<?php echo base_url('assets/js/jquery-3.4.1.min.js'); ?>"></script>
		<script src="<?php echo base_url('assets/plugins/tinymce/tinymce.min.js'); ?>"></script>
		<script src="<?php echo base_url('assets/materialize/js/materialize.js'); ?>"></script>
		<script src="<?php echo base_url('assets/js/materialize.clockpicker.js'); ?>"></script>
		<script src="<?php echo base_url('assets/js/woow.js'); ?>"></script>
		<script src="<?php echo base_url('assets/js/jquery.matchHeight.js'); ?>"></script>
		<script>
		    $(document).ready(function () {
		      $('.modal').modal();
			 	
		    });
		  </script>
	 <script>
		  $(document).ready(function() {

		              var CurrentUrl= document.URL;
		              var CurrentUrlEnd = CurrentUrl.split('/').filter(Boolean).pop();
		              console.log(CurrentUrlEnd);
		              $("ul#sidenav li a").each(function() {
		                    var ThisUrl = $(this).attr('href');
		                    var ThisUrlEnd = ThisUrl.split('/').filter(Boolean).pop();

		                    if(ThisUrlEnd == CurrentUrlEnd){
		                    $(this).closest('li').addClass('active red')
		                    }
		              });

		     });
	  </script>
	</head>
	<body id="body-content">
		

		<header style="padding-left:0px">
			<nav class="woowtix-bg red navbar-fixed" role="navigation">
				<div class="nav-wrapper container">
					<a id="logo-container" href="<?php echo base_url(); ?>" class="brand-logo center">
						<div class="valign-wrapper"><?php
	 //   if (!$this->session->userdata('title')) {
	  	  $vmstitle = $this->session->userdata('title');
	   // }
	  //  else {
	  //	  $vmstitle = 'Woow VMS';
	  //  }	
		echo $vmstitle;
	    ?></div>
					</a>
					<a href="#" class="close-sidebar"><i class="material-icons">menu</i></a>
					<a style="display:none;" href="#" class="open-sidebar"><i class="material-icons">menu</i></a>
					<a href="#" data-activates="sidenav" class="button-collapse"><i class="material-icons">menu</i></a>
				</div>
			</nav>
		</header>
		
<main style="padding-left:0px">
	<div class="container">
		<div style="padding:5px" id="daily_recap">Daily Visitor</div>
		<?php echo $pageContent; ?>
	</div>
<script type="text/javascript">
$(document).ready(function() {

	$("#daily_recap").load('<?php echo base_url()?>dashboard/minidash');
});
</script>	
</main>		
		
 



		<footer class="page-footer white" style="padding-left:0px">
			<div class="footer-copyright woowtix-bg red">
				<div class="container center">
					Copyright &copy; <?php echo date('Y');?> <a class="white-text text-lighten-3" href="http://www.hitcorporation.com">HIT Corporation</a>, All Rights Reserved.
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
					$("a.close-sidebar, aside").css('display','none');
					$("a.open-sidebar").css('display','block');
					$("ul#sidenav").css("transform", "translateX(-100%)");
					$("header, main, footer").css("padding-left", "0px");
					$("main > .container, .nav-wrapper").css("max-width", "100%");
				}); 
				$("a.open-sidebar").click(function() {
					$("a.open-sidebar").css('display','none');
					$("a.close-sidebar, aside").css('display','block');
					$("ul#sidenav").css("transform", "translateX(0)");
					$("header, main, footer").css("padding-left", "300px");
				});

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
		