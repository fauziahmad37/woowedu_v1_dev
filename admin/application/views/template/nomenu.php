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
		<link href="<?php echo base_url('assets/materialize/css/materialize.css'); ?>" type="text/css" rel="stylesheet" media="screen,projection"/>
		<link href="<?php echo base_url('assets/css/materialize.clockpicker.css'); ?>" type="text/css" rel="stylesheet" media="screen,projection"/>
		<link href="<?php echo base_url('assets/css/woow.css'); ?>" type="text/css" rel="stylesheet" media="screen,projection"/>
		<style>
	        .userView {
	          display: flex;
	          /*min-height: 100vh;*/
	          flex-direction: column;
	  		background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
	  		background-size: 400% 400%;
	  		animation: gradientBG 15s ease infinite;
		}
		.side-nav.fixed li a{
			padding-left:16px !important;
		}
		.side-nav.fixed .collapsible-body li a{
			padding-left:26px !important;
		}
		ul#sidenav {
			position:absolute !important;
			overflow-y:hidden !important;
			height:auto !important;
		}
		tr#dev-hidden {
			display:none;
		}
	  	  @keyframes gradientBG {
	  	  	0% {
	  	  		background-position: 0% 50%;
	  	  	}
	  	  	50% {
	  	  		background-position: 100% 50%;
	  	  	}
	  	  	100% {
	  	  		background-position: 0% 50%;
	  	  	}
	  	  }
			img {
			    image-orientation: from-image;
			}
			ul#sidenav li.active a, ul#sidenav li.active i {
				color:#ffffff !important;
			}
		</style>
		<!--  Scripts-->
		<script src="<?php echo base_url('assets/js/jquery-3.4.1.min.js'); ?>"></script>
		<script src="<?php echo base_url('assets/plugins/tinymce/tinymce.min.js'); ?>"></script>
		<script src="<?php echo base_url('assets/materialize/js/materialize.js'); ?>"></script>
		<script src="<?php echo base_url('assets/js/materialize.clockpicker.js'); ?>"></script>
		<script src="<?php echo base_url('assets/js/woow.js'); ?>"></script>
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
	<body>
 
			<nav class="woowtix-bg red navbar-fixed" role="navigation">
				<div class="nav-wrapper container">
					<a id="logo-container" href="<?php echo base_url(); ?>" class="brand-logo center">
						<div class="valign-wrapper"><img class="responsive-img show-on-medium-and-up" style="width:auto;height:50px;" src="<?php echo base_url('assets/images/woowtix-circle.png'); ?>"> <img class="responsive-img hide-on-med-and-down" style="width:auto;height:40px;" src="<?php echo base_url('assets/images/logo-woowtix-text-white.png'); ?>"></div>
					</a>
						
					<a href="#" data-activates="sidenav" class="button-collapse"><i class="material-icons">menu</i></a>
				</div>
			</nav>
 

 
	<div class="container">
		<?php echo $pageContent; ?>
	</div>
 


		<footer class="page-footer white" style="padding:0">
			<div class="footer-copyright woowtix-bg red">
				<div class="container center">
					Copyright &copy; <?php echo date('Y');?> <a class="white-text text-lighten-3" href="http://www.hitcorporation.com">HIT Corporation</a>, All Rights Reserved.
				</div>
			</div>
		</footer>

		
  	</body>
</html>
