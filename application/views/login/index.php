<!DOCTYPE html>
<html lang="en">
<head>
	
	<?php 
		$theme = 'space';
		$admin_url =html_escape($this->config->item('admin_url'));
	?>
	<base href="<?=base_url()?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="admin_url" content="<?=$admin_url?>">
	<meta charset="utf-8" />
	<title><?= SITE_NAME .": Login" ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta content="<?= SITE_NAME .": Login" ?>" name="description" />
	<meta name="author" content="MXCode - Ramanda Rido Saputra" />

	<!-- App favicon -->
	<link rel="shortcut icon" href="<?= $admin_url.'/assets/images/favicon/favicon.ico' ?>">
	<!-- <link rel="apple-touch-icon" sizes="57x57" href="<?//= base_url('admin/assets/images/favicon'); ?>/apple-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="<?//= base_url('admin/assets/images/favicon'); ?>/apple-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="<?//= base_url('admin/assets/images/favicon'); ?>/apple-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="<?//= base_url('admin/assets/images/favicon'); ?>/apple-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="<?//= base_url('admin/assets/images/favicon'); ?>/apple-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="<?//= base_url('admin/assets/images/favicon'); ?>/apple-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="<?//= base_url('admin/assets/images/favicon'); ?>/apple-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="<?//= base_url('admin/assets/images/favicon'); ?>/apple-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="<?//= base_url('admin/assets/images/favicon'); ?>/apple-icon-180x180.png">
	<link rel="icon" type="image/png" sizes="192x192" href="<?//= base_url('admin/assets/images/favicon'); ?>/android-icon-192x192.png"> -->
	<!-- <link rel="icon" type="image/png" sizes="32x32" href="<?//= base_url('admin/assets/images/favicon'); ?>/favicon-32x32.png"> -->
	<!-- <link rel="icon" type="image/png" sizes="96x96" href="<?//= base_url('admin/assets/images/favicon'); ?>/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="<?//= base_url('admin/assets/images/favicon'); ?>/favicon-16x16.png"> -->
	<!-- <link rel="manifest" href="<?//= base_url('admin/assets/images/favicon'); ?>/manifest.json">
	
	<meta name="msapplication-TileColor" content="#da532c">
	<meta name="msapplication-TileImage" content="<?//= base_url('admin/assets/images/favicon'); ?>/ms-icon-144x144.png">
	<meta name="theme-color" content="#ffffff">

	

	<link href="<?//= base_url('admin/assets/new/css/bootstrap.min.css') ?>" id="bootstrap-style" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" type="text/css" href="<?//= base_url('admin/assets/login/css/fontawesome-all.min.css') ?>">
	<link rel="stylesheet" type="text/css" href="<?//= base_url('admin/assets/login/css/iofrm-style.css') ?>">
	<link rel="stylesheet" type="text/css" href="<?//= base_url('admin/assets/login/css/iofrm-theme5.css') ?>"> -->


	<script src="<?= $admin_url.'/assets/login/js/jquery.min.js' ?>"></script>
	<link href="<?= $admin_url.'/assets/new/libs/sweetalert2/sweetalert2.min.css' ?>" rel="stylesheet" type="text/css" />
	<!-- <script src="<?//= base_url('admin/assets/login/js/popper.min.js') ?>"></script>
	<script src="<?//= base_url('admin/assets/new/libs/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
	<script src="<?//= base_url('admin/assets/login/js/main.js') ?>"></script> -->
	<meta property="og:title" content="">
	<meta property="og:type" content="">
	<meta property="og:url" content="">
	<meta property="og:image" content="">
	<meta property="og:image:alt" content="">

	<?php $themes = $_SESSION['themes'] ??  'space' ?>

	<?php if(isset($_COOKIE['themes'])) {
			$themes = $_COOKIE['themes'];
		} 
	?>
	

	<link rel="stylesheet" href="assets/css/custom.css">
	<link rel="stylesheet" href="<?=base_url()?>assets/themes/<?=$themes?>/css/style.min.css">
	<style>
		#login-form {
			background-image: url('<?=base_url()?>assets/themes/<?=$themes?>/images/background_1.svg');
			background-position: center center;
			background-repeat: no-repeat;
			background-size: cover;
		}

		#login-image {
			height: 380px;
		}
	</style>

</head>
<body style="height: 100vh !important;">
	<div class="background-wrapper">
		<img src="<?=base_url()?>assets/themes/<?=trim($themes)?>/images/bg-mobile.png" />
	</div>

		<div class="container-fluid h-100 form-body">
            <div class="row h-100">

                <div class="col-6 d-flex flex-column flex-nowrap justify-content-center align-items-center" id="login-left-side">
                    <img src="<?=base_url()?>assets/themes/<?=trim($themes)?>/images/logo.png" class="logo mb-1" style="height: 8vw" />
                    <img id="login-image" src="<?=base_url()?>assets/themes/<?=trim($themes)?>/images/login-image.svg" class="img-fluid" style="height: 22vw"/>
                </div>
                <div id="login-form" class="col-6 d-flex flex-column justify-content-center align-items-center">
                    <form name="login-form" class="col-12 col-sm-8 col-lg-7">
						<div class="icon-woowedu">
							<img src="<?=base_url()?>assets/themes/<?=trim($themes)?>/images/logo.png" class="logo mb-1" width="175" />
						</div>
                        <h3 class="text-capitalize text-left text-white text-shadow">Welcome !</h3>
                        <div class="form-floating mb-3">
							<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
                            <input type="text" class="form-control" name="username" id="username" placeholder="username">
                            <label for="username">Username</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" name="password" id="password" placeholder="password">
                            <label for="password">Password</label>
                        </div>
                        <div class="d-flex justify-content-end py-2">
                            <button type="submit" class="btn bg-white text-primary px-4"><strong>Login</strong></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

	<!-- <div class="form-body" style="overflow-y: hidden; height: 100vh; width: 100vw">
 
		<div class="row">
			<div class="img-holder">
				<div class="bg"  style="opacity: 0.8;background-image: none;"><img src="<?= base_url('assets/images/woowedu-logo.png') ?>" alt="" class="mt-0" width="500"></div>
				<div class="info-holder">
					
				</div>
			</div>
			<div class="form-holder">
				<div class="form-content">
					<div class="form-items">
						<h3><?= SITE_NAME ?></h3> 
						<form class="loginForm" method="post" id="login-form">
							<input class="form-control validate" type="text" name="username" id="username" placeholder="Username">
							<input class="form-control validate" type="password" name="password" id="password" placeholder="Password">
							<div class="form-button">
								<button type="submit" name="submit" value="login" class="btn-login ibtn">Login</button>
							</div>
							<h5 class="text-danger w-100 my-2">Demo will expired in <span id="timespan"></span> days</h5>
						</form>
						<div class="other-links">
							<p>&copy; <?= date('Y'); ?> &nbsp;&nbsp;&nbsp; <a class="text-right" href="#"><?= strtoupper(SITE_NAME) ?></a> <i class="mdi mdi-heart text-danger"></i></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div> -->
	
	<script src="<?= $admin_url.'assets/new/libs/sweetalert2/sweetalert2.min.js' ?>"></script>

	<script>

	
 

	$('#login-form').on('submit', function(e) {
			e.preventDefault();

			$.ajax({
				url: "<?= base_url('auth/loginx'); ?>",
				type: 'POST',
				data: $(e.target).serialize(),
				beforeSend: (xhr) => {
                	Swal.fire({
                	    html: 	'<div class="d-flex flex-column align-items-center">'
                	    + '<span class="spinner-border text-primary"></span>'
                	    + '<h3 class="mt-2">Loading...</h3>'
                	    + '<div>',
                	    showConfirmButton: false,
                	    width: '10rem'
                	});
            	},
            	success: (resp) => {
								//var res = JSON.parse(resp);
					var res = resp;
					var ulevel = res.ulevel;

					window.localStorage.setItem('token', res.token);
					window.localStorage.setItem('level', window.btoa(ulevel));
					
					Swal.fire({
						type: res.err_status,
						title:'<h5 class="text-success text-uppercase">'+res.err_status+'</h5>',
						html: res.message,
						timer: 1000
					}).then((t) => {
						if(ulevel==8)
						window.location.href = '<?=base_url('transaction/add')?>';
						else
						window.location.href = '<?=base_url('dashboard')?>';
					});
									//csrfToken.setAttribute('content', res.token);
            	},
            	error: (err) => {
            	    var response = JSON.parse(err.responseText);
					if(err.status == 402){
						Swal.fire({
							width: '660px',
							showCancelButton: false,
  							showConfirmButton: false,
							html: `<div class="text-start mx-4 mt-4">
								<img class="mb-4" src="<?=base_url()?>assets/images/sad.png" />
								<h6 class="text-danger text-left">${response.message}</h6>
								<p class="">Halo ${response.username}</p>
								<p class="">Akun Anda telah habis.Untuk melanjutkan menggunakan semua fitur premium kami, silakan lakukan upgrade sekarang!</p>
								<div class="d-grid gap-2 d-md-flex justify-content-md-end">
									<button class="btn btn-danger text-white" type="button">Upgrade Sekarang</button>
								</div>
							</div>`,
						});
						$('input[name="csrf_token_name"]').val(response.token);
						return
					}

            	    Swal.fire({
            	        type: response.err_status,
            	        title: '<h5 class="text-danger text-uppercase">'+response.err_status+'</h5>',
            	        html: response.message,
						timer: 2000
            	    });
            	    //csrfToken.setAttribute('content', response.token);
            	    $('input[name="csrf_token_name"]').val(response.token);
            	}
			});
		});

 
	</script>

</body>
</html>
