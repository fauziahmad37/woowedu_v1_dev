<!DOCTYPE html>
<html lang="en">
<head>
	
	<meta charset="utf-8" />
	<title><?= SITE_NAME .": Login" ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta content="<?= SITE_NAME .": Login" ?>" name="description" />
	<meta name="author" content="MXCode - Ramanda Rido Saputra" />

	<!-- App favicon -->
	<link rel="shortcut icon" href="<?= base_url('assets/images/favicon/favicon.ico') ?>">
	<link rel="apple-touch-icon" sizes="57x57" href="<?= base_url('assets/images/favicon'); ?>/apple-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="<?= base_url('assets/images/favicon'); ?>/apple-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="<?= base_url('assets/images/favicon'); ?>/apple-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="<?= base_url('assets/images/favicon'); ?>/apple-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="<?= base_url('assets/images/favicon'); ?>/apple-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="<?= base_url('assets/images/favicon'); ?>/apple-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="<?= base_url('assets/images/favicon'); ?>/apple-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="<?= base_url('assets/images/favicon'); ?>/apple-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="<?= base_url('assets/images/favicon'); ?>/apple-icon-180x180.png">
	<link rel="icon" type="image/png" sizes="192x192" href="<?= base_url('assets/images/favicon'); ?>/android-icon-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="<?= base_url('assets/images/favicon'); ?>/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="<?= base_url('assets/images/favicon'); ?>/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="<?= base_url('assets/images/favicon'); ?>/favicon-16x16.png">
	<link rel="manifest" href="<?= base_url('assets/images/favicon'); ?>/manifest.json">
	
	<meta name="msapplication-TileColor" content="#da532c">
	<meta name="msapplication-TileImage" content="<?= base_url('assets/images/favicon'); ?>/ms-icon-144x144.png">
	<meta name="theme-color" content="#ffffff">

	<link href="<?= base_url('assets/new/libs/sweetalert2/sweetalert2.min.css') ?>" rel="stylesheet" type="text/css" />

	<link href="<?= base_url('assets/new/css/bootstrap.min.css') ?>" id="bootstrap-style" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/login/css/fontawesome-all.min.css') ?>">
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/login/css/iofrm-style.css') ?>">
	<link rel="stylesheet" type="text/css" href="<?= base_url('assets/login/css/iofrm-theme5.css') ?>">


	<script src="<?= base_url('assets/login/js/jquery.min.js') ?>"></script>
	<script src="<?= base_url('assets/login/js/popper.min.js') ?>"></script>
	<script src="<?= base_url('assets/new/libs/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
	<script src="<?= base_url('assets/login/js/main.js') ?>"></script>

</head>
<body style="height: 100vh; width: 100vw">

	<div class="form-body" style="overflow-y: hidden; height: 100vh; width: 100vw">
 
		<div class="row">
			<div class="img-holder">
				<div class="bg"  style="opacity: 0.8; background-image: none;"><img src="<?= base_url('assets/images/login-image.svg') ?>" alt="" class="mt-0"></div>
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
						<!--	<h5 class="text-danger w-100 my-2">Demo will expired in <span id="timespan"></span> days</h5>-->
						</form>
						<div class="other-links">
							<p>&copy; <?= date('Y'); ?> &nbsp;&nbsp;&nbsp; <a class="text-right" href="#"><?= strtoupper(SITE_NAME) ?></a> <i class="mdi mdi-heart text-danger"></i></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<script src="<?= base_url('assets/new/libs/sweetalert2/sweetalert2.min.js') ?>"></script>

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
            	    Swal.fire({
            	        type: response.err_status,
            	        title: '<h5 class="text-danger text-uppercase">'+response.err_status+'</h5>',
            	        html: response.message,
						timer: 2000
            	    });
            	    //csrfToken.setAttribute('content', response.token);
            	}
			});
		});

		var ts = document.getElementById('timespan'),
					text = null;

		function getInTime() {
			$.get('<?=base_url()?>api/lliv/get', (resp) => {
			
				var date1 = new Date(resp.total_days),
					date2 = new Date();
				var span = date1.getTime() - date2.getTime();
				console.log(date1);
				ts.innerHTML = parseInt(span / (1000 * 3600 * 24));				
			});
		}

		getInTime();
	</script>

</body>
</html>
