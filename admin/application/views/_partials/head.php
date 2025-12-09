<!DOCTYPE html>
<html lang="id">
<head>
	<!-- update naquib 22 maret 2021 -->
	<base href="<?= base_url() ?>" />
	<meta name="csrf_token" content="<?= !empty($csrf_token) ? $csrf_token : NULL ?>">
	<meta name="x_type_token" content="<?= base64_encode($_SESSION['user_token'].':'.set_token($_SESSION['user_token'])) ?>">
	<!-- end update naquib 22 maret 2021 -->
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>WoowEdu</title>
	<meta name="description" content="<?= $settings->site_desk; ?>" />
	<meta name="keywords" content="<?= $settings->keywords; ?>" /> 
	<meta name="robots" content="all" />
	<meta name="revisit-after" content="1 Days" />
	<meta name="lang" content="<?=!isset($_SESSION['lang']) ? 'english' : trim($_SESSION['lang'])?>">
	<meta property="og:locale" content="id" />
	<meta property="og:site_name" content="<?= $settings->app_name; ?>" />

	<meta property="og:type" content="WooW Access" />
	<meta property="og:title" content="<?= $settings->site_title . ": " . ucfirst($this->uri->segment(1)) . " - " . ucfirst($this->uri->segment(2)) ?>" />
	<meta property="og:description" content="<?= $settings->site_desk; ?>" />
	<meta property="og:url" content="<?= base_url(); ?>" />
	<meta property="og:image" content="<?= base_url('assets/new/images/lgn-light.png'); ?>" />
	<meta property="og:image:width" content="750" />
	<meta property="og:image:height" content="415" />
	<meta name="twitter:card" content="summary_large_image" />
	<meta name="twitter:site" content="<?= $settings->app_name; ?>" />
	<meta name="twitter:title" content="<?=  $settings->site_title . ": " . ucfirst($this->uri->segment(1)) . " - " . ucfirst($this->uri->segment(2)) ?>" />
	<meta name="twitter:description" content="<?= $settings->site_desk; ?>" />
	<meta name="twitter:image" content="<?= base_url('assets/new/images/lgn-light.png'); ?>" />

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
	<!--<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests"> -->

	<!-- DataTables -->
	<link href="<?= base_url('assets/new/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') ?>" rel="stylesheet" type="text/css" />
	<link href="<?= base_url('assets/new/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') ?>" rel="stylesheet" type="text/css" />
	<link href="<?= base_url('assets/new/libs/datatables.net-buttons-bs4/css/select.bootstrap4.min.css') ?>" rel="stylesheet" type="text/css" /> 

	<link href="<?= base_url('assets/new/libs/select2/css/select2.min.css') ?>" rel="stylesheet" type="text/css" />
	<link href="<?= base_url('assets/new/libs/sweetalert2/sweetalert2.min.css') ?>" rel="stylesheet" type="text/css" />
	<link href="<?= base_url('assets/new/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css') ?>" rel="stylesheet">
	<link href="<?= base_url('assets/new/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css') ?>" rel="stylesheet" />
	<link href="<?= base_url('assets/new/libs/dropzone/min/dropzone.min.css') ?>" rel="stylesheet" />
	<link href="<?= base_url('assets/new/libs/magnific-popup/magnific-popup.css') ?>" rel="stylesheet" type="text/css" />

	<!-- Bootstrap Css -->
	<link href="<?= base_url('assets/new/css/bootstrap.min.css') ?>" id="bootstrap-style" rel="stylesheet" type="text/css" />

	<!-- Icons Css -->
	<link href="<?= base_url('assets/new/css/icons.min.css') ?>" rel="stylesheet" type="text/css" />
 
	<link href="<?= base_url('assets/new/css/app.min.css') ?>" id="app-style" rel="stylesheet" type="text/css" />
	<!-- helper add css -->
	<?= !(empty($page_css)) ? add_css($page_css) : trim('') ?> 

	<script src="<?= base_url('assets/new/libs/jquery/jquery.min.js'); ?>"></script>
	<script src="<?= base_url('assets/new/libs/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
	<script src="<?= base_url('assets/new/libs/metismenu/metisMenu.min.js'); ?>"></script>
	<script src="<?= base_url('assets/new/libs/simplebar/simplebar.min.js'); ?>"></script>
	<script src="<?= base_url('assets/new/libs/node-waves/waves.min.js'); ?>"></script>
	<script src="<?= base_url('assets/new/libs/blockui/blockui.min.js'); ?>"></script>

	<script>
		const BASE_URL = document.querySelector('base').href;
		const ADMIN_URL = '<?=html_escape($this->config->item('admin_url'))?>';
	</script>
	
	<!-- Lang -->
	<script src="assets/new/js/lang.js" async></script>


</head>
