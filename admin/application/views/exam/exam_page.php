<!DOCTYPE html>
<html lang="id">
<head>
	<!-- update naquib 22 maret 2021 -->
	<base href="<?= base_url() ?>" >
	<meta name="csrf_token" content="<?= !empty($csrf_token) ? $csrf_token : NULL ?>">
	<!-- end update naquib 22 maret 2021 -->
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?=  $settings->site_title . " : " . $pageTitle ?></title>
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
	<?= !empty($header_js) ? add_js($header_js) : trim('') ?>


	<script src="<?= base_url('assets/new/libs/jquery/jquery.min.js'); ?>"></script>
	<script src="<?= base_url('assets/new/libs/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
	<script src="<?= base_url('assets/new/libs/metismenu/metisMenu.min.js'); ?>"></script>
	<script src="<?= base_url('assets/new/libs/simplebar/simplebar.min.js'); ?>"></script>
	<script src="<?= base_url('assets/new/libs/node-waves/waves.min.js'); ?>"></script>
	<script src="<?= base_url('assets/new/libs/blockui/blockui.min.js'); ?>"></script>
	<!-- Lang -->
	<script src="assets/new/js/lang.js" async></script>
</head>

<body>
    <div id="layout-wrapper">
        <header id="page-topbar">
            <div class="navbar-header bg-info">
                <div class="d-flex">
                    <!-- LOGO -->
                    <div class="navbar-brand-box">
                        <a href="<?= site_url() . 'dashboard' ?>" class="logo logo-dark">
                            <span class="logo-sm">
                                <img src="<?= base_url('assets/images/logo/' . $settings->logo_mini) ?>" alt="" height="35" class="mt-0">
                            </span>
                            <span class="logo-lg">
                                <img src="<?= base_url('assets/images/logo/' . $settings->logo_light) ?>" alt="" height="40" class="mt-0">
                            </span>
                        </a>

                        <a href="<?= site_url() . 'dashboard' ?>" class="logo logo-light">
                            <span class="logo-sm">
                                <img src="<?= base_url('assets/images/logo/' . $settings->logo_mini) ?>" alt="" height="35" class="mt-0">
                            </span>
                            <span class="logo-lg">
                                <img src="<?= base_url('assets/images/logo/' . $settings->logo_light) ?>" alt="" height="40" class="mt-0">
                            </span>
                        </a>
                    </div>

                    <!-- <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect text-white" id="vertical-menu-btn">
                        <i class="fa fa-fw fa-bars"></i>
                    </button> -->

                </div>

                <div class="d-flex">
        

                    <div class="d-inline-block mt-4 mr-3">
                        <span class="badge badge-pill badge-danger font-size-12"><?php echo $this->lang->line('woow_header_badge_admin'); ?></span>
                    </div>

                    <div class="dropdown d-none d-lg-inline-block ml-1">
                        <button type="button" class="btn header-item noti-icon waves-effect" data-toggle="fullscreen">
                            <i class="bx bx-fullscreen text-white"></i>
                        </button>
                    </div>

                    <div class="dropdown d-inline-block">
        

                        <div class="dropdown-menu dropdown-menu-right">

                            <!-- item-->
                            <a href="<?php echo base_url('language/english') ?>" class="dropdown-item notify-item">
                                <img src="<?php echo base_url(); ?>assets/new/images/flags/usa.png" alt="user-image" class="mr-1" height="12"> <span class="align-middle"><?php echo $this->lang->line('woow_header_language_english'); ?></span>
                            </a>

                            <!-- item-->
                            <a href="<?php echo base_url('language/indonesia') ?>" class="dropdown-item notify-item">
                                <img src="<?php echo base_url(); ?>assets/new/images/flags/indonesia.png" alt="user-image" class="mr-1" height="12"> <span class="align-middle"><?php echo $this->lang->line('woow_header_language_indonesian'); ?></span>
                            </a>

                            <a href="<?php echo base_url('language/germany') ?>" class="dropdown-item notify-item">
                                <img src="<?php echo base_url(); ?>assets/new/images/flags/germany.png" alt="user-image" class="mr-1" height="12"> <span class="align-middle"><?php echo $this->lang->line('woow_header_language_germany'); ?></span>
                            </a>
                        </div>
                    </div>

                    <div class="dropdown d-inline-block">
                        <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img class="rounded-circle header-profile-user" src="<?= !empty($_SESSION['userpic']) ? base_url('assets/files/users/'.$_SESSION['userpic']) : base_url('assets/new/images/ava-user.png') ?>" alt="Header Avatar">
                            <span class="d-none d-xl-inline-block ml-1 text-white"><?= $this->session->userdata('username') ?></span>
                            <i class="mdi mdi-chevron-down d-none d-xl-inline-block text-white"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <!-- item-->
                            <?php if ($this->session->userdata('id_role') == 1) { ?>
                                <a class="dropdown-item" href="<?= base_url() . 'admin/profile/' ?>"><i class="bx bx-user font-size-16 align-middle mr-1"></i> <?php echo $this->lang->line('woow_header_profile'); ?></a>
                            <?php } else { ?>
                                <a class="dropdown-item" href="<?= base_url() . 'profile/' ?>"><i class="bx bx-user font-size-16 align-middle mr-1"></i> <?php echo $this->lang->line('woow_header_profile'); ?></a>
                            <?php } ?> 

                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="<?= base_url() . 'auth/logout' ?>"><i class="bx bx-power-off font-size-16 align-middle mr-1 text-danger"></i> <?php echo $this->lang->line('woow_header_logout'); ?></a>
                        </div>
                    </div>

                </div>
            </div>
        </header>
        <!-- 
            MAIN CONTENT
        -->
        <div class="main-content ml-0">
            <div class="page-content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <!-- <a href="<?=base_url('exam/student')?>" class="text-primary"><strong><- Kembali</strong></a> -->
                        </div>
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header d-flex flex-nowrap bg-info text-white align-items-center">
                                    <span class="col-4 d-flex flex-column">
                                        <h4 class="text-white"><?=html_escape($judul)?></h4>
                                        <h6 class="text-white"><?=html_escape('Kelas '.$kelas)?></h6>
                                    </span>
                                    <span class="col d-flex justify-content-end">
                                        <h2 id="countdown" class="mb-0 text-white mr-2" style="text-shadow: 0px 1px 2px rgba(51, 51, 61, .4)"></2>
                                    </span>
                                </div>
                                <div class="card-body">
                                
                                    <div class="row">
                                        <!-- DEATAILS SOAL-->
                                        <div class="col-12 col-lg-9 d-flex flex-column">
                                        <?php 
                                            if(!empty($soal['question_file'])): 
                                                $ext = pathinfo($soal['question_file'], PATHINFO_EXTENSION);

                                                if($ext == 'mp4'):
                                        ?>
                                                <video width="360" height="240" src="<?=html_escape(base_url($soal['question_file']))?>" controls autoplay></video>
                                        <?php elseif(in_array($ext, ['png', 'jpeg', 'jpg', 'webp'])): ?>
                                                <img width="360" height="240" src="<?=html_escape(base_url($soal['question_file']))?>" />
                                        <?php
                                                endif; 
                                            endif; 
                                        ?>
                                            <span class="d-flex flex-nowrap">
                                                <h5 class="mr-2"><?=html_escape($soal['no_urut'])?> .</h5>
                                                <span class="d-flex flex-column">
                                                   
                                                    <h5 class="px-1 d-flex flex-wrap mb-0"><?=$soal['question']?></h5>
                                                    
                                                </span>
                                            </span>
                                            <form class="d-flex flex-column pl-4" name="submit-answer" method="post" action="<?=base_url('api/exam/submitSoal')?>">
                                                <?php 
                                                    switch($soal['type']): 
                                                        case 1:
                                                            $choice = ['a', 'b', 'c', 'd', 'e', 'f'];
                                                            // loop throughr choice
                                                            foreach($choice as $c):
                                                                if(empty($soal['choice_'.$c]) && empty($soal['choice_'.$c.'_file'])) continue;
                                                ?>

                                                <span class="d-flex flex-nowrap">
                                                    <div class="form-check mr-2">
                                                        <input class="form-check-input" type="radio" name="answer" value="<?=$c?>" id="choice_<?=$c?>">
                                                        <label class="form-check-label" for="choice_<?=$c?>">
                                                            <?=$c?>.
                                                        </label>
                                                    </div>
                                                    <?php if(!empty($soal['choice_'.$c.'_file'])): ?>
                                                        <span class="mr-1">
                                                            <img src="<?=$soal['choice_'.$c.'_file']?>" width="20" height="20">
                                                        </span>
                                                    <?php endif; ?>
                                                    <?php if(!empty($soal['choice_'.$c])): ?>
                                                        <p class="mb-1">
                                                            <?=$soal['choice_'.$c]?>
                                                        </p>
                                                    <?php endif; ?>
                                                </span>

                                                <?php 
                                                            endforeach;
                                                        break;
                                                        case 2:
                                                ?>
                                                    <textarea class="form-control form-control-sm" row="8" name="answer"></textarea>
                                                <?php 
                                                        break;
                                                ?>

                                                <?php 
                                                    endswitch; 
                                                    ?>
                                                <input type="hidden" name="kode_ujian" value="<?=$_GET['kode_ujian']?>">
                                                <input type="hidden" name="kode_soal" value="<?=$soal['soal_code']?>">
                                                <input type="hidden" name="total_soal" value="<?=$total_soal?>">
                                                <input type="hidden" name="nomor" value="<?=$_GET['no']?>">
                                                <input type="hidden" name="csrf_token" value="<?=$csrf_token?>">
                                                <span class="d-flex justify-content-center mt-5">
                                                    <button type="reset" class="btn btn-secondary mr-1"><i class="fas fa-undo"></i> Ulangi</button>
                                                    <?php if(intval($_GET['no']) == $total_soal):?>
                                                    <button type="submit" class="btn btn-danger"> Selesai</a>
                                                    <?php else: ?>
                                                    <button type="submit" class="btn btn-primary"><i class="fas fa-arrow-right"></i> Soal Selanjutnya</button>
                                                    <?php endif; ?>
                                                </span>
                                            </form>
                                        </div>
                                        <!-- END DETAILS SOAL -->
                                        <!-- MISCELANOUS -->
                                        <div class="col-12 col-lg-3">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h5 class="text-danger text-center mb-2">PESERTA</h5>
                                                        <img class="mx-auto" src="<?=base_url('assets/new/images/ava-user.png')?>">
                                                        <h3 class="text-primary text-center">Nama</h3>
                                                        <ul class="list-unstyled d-flex flex-wrap">
                                                            <?php 
                                                            
                                                                $i=1;
                                                                while($i <= $total_soal):
                                                                    echo '<li id="is_answered_'.$i.'" class="rounded-circle border border-primary text-center mr-1 align-middle" style="width: 30px; height: 30px">
                                                                            <a role="button" href="'.base_url('exam/questions/?kode_ujian='.$_GET['kode_ujian'].'&no='.$i).'">'.$i.'</a>
                                                                          </li>';
                                                                    echo '<input type="checkbox" class="d-none" name="is-answered['.$i.']" >';
                                                                    flush();
                                                                    $i++;
                                                                endwhile;
                                                            ?>
                                                            
                                                        </ul>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        <!-- MISCELANOUS -->
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="footer" style="left: 0!important">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <script>
                            document.write(new Date().getFullYear())
                        </script> <?= $settings->copyright; ?>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-sm-right d-none d-sm-block">
                            <?php echo $this->lang->line('woow_footer_develop'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </footer>

    </div>


<?php 
    $this->load->view("_partials/js.php"); 
    $_start = new DateTime($_SESSION['start_time']);
    $_end = date_modify($_start, '+'.$soal['duration'].' hours');
    $_now = new DateTime();
    $_interval = $_end->diff($_now)->format("%H:%I:%s");
    //echo strtotime('+'.$soal['duration'].' hours', strtotime($_start)) - strtotime($_now);
    //echo $_interval / (1000 * 3600);
?>

    <script>
        'use strict';
        const base_url = document.querySelector('base').href;
        
        getAnswered();

        async function getAnswered() {
            try {
                let xhr = await fetch(`${base_url}api/exam/getAllAnsweredExam?exam_id=<?=$_GET['kode_ujian']?>`);
                let d = await xhr.json();

                for(let val of d.data)
                {
                    if(val.exam_answer)
                    {
                        let circle = document.getElementById(`is_answered_${val.no_urut}`);
                        circle.classList.add('bg-primary');
                        circle.querySelector('a').classList.add('text-white');
                    }
                }
            }
            catch(err) {

            }
        }                                       

        let end = new Date('<?=$_end->format('Y-m-d H:i:s')?>').getTime();
        
        var x = setInterval(function() {

            // Get today's date and time
            var now = new Date().getTime();

            // Find the distance between now and the count down date
            var distance = end - now;

            // Time calculations for days, hours, minutes and seconds
            //var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Display the result in the element with id="demo"
            document.getElementById("countdown").innerHTML =  (hours < 10 ? '0' + hours : hours) + 
                                                                ":" + (minutes < 10 ? '0' + minutes : minutes) + 
                                                                ":" + (seconds < 10 ? '0' + seconds : seconds);

            // If the count down is finished, write some text
            if (distance < 0) 
            {
                clearInterval(x);
                document.getElementById("countdown").innerHTML = "EXPIRED";
            }
        }, 1000);
    </script>
    </body>
</html>
