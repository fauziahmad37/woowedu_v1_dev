<?php $this->load->view("_partials/head") ?>
	<style>
		.datepicker {
		  z-index: 1600 !important; /* has to be larger than 1050 */
		}

	</style>
	<body data-sidebar="dark" data-keep-enlarged="true" id="body-pd">

		<div class="offcanvas-sm offcanvas-start" tabindex="-1" id="staticBackdrop" aria-labelledby="staticBackdropLabel" style="width: 280px;">
			<div class="offcanvas-header">
				<h5 class="offcanvas-title" id="staticBackdropLabel">Offcanvas</h5>
				
			</div>
			<div class="offcanvas-body">
				<div>
					<div class="l-navbar" id="nav-bar">
						<nav class="nav">
							
							<div>
								

								<?php $this->load->view("_partials/header") ?>
					
								<!-- ========== Left Sidebar Start ========== -->
					
								<?php $this->load->view("_partials/navbar") ?>
					
								<!-- Left Sidebar End -->
					
								
							</div>

							<a onclick="logoutConfirm()" class="btn btn-default btn-logout text-center position-absolute bottom-0 ">
								<img src="<?=base_url()?>assets/themes/<?=isset($_SESSION['themes']) ? $_SESSION['themes'] : 'space' ?>/images/ic_logout.png" alt="">

								<span class="text-primary fw-semibold">Keluar Akun</span>
							</a>
						</nav>
					</div>
				</div>
			</div>
		</div>

		<!-- Begin page -->
		<!-- <div class="l-navbar" id="nav-bar">

			<span class="h6 position-absolute h-0 bg-white p-1 rounded-2 close-menu" style="right: 10px; top: 10px; z-index: 100">X</span>
			<nav class="nav">
				<div> -->
					

					<?php // $this->load->view("_partials/header") ?>
		
					<!-- ========== Left Sidebar Start ========== -->
		
					<?php // $this->load->view("_partials/navbar") ?>
		
					<!-- Left Sidebar End -->
		
					
				<!-- </div>

				<a href="auth/logout" class="btn btn-default btn-logout text-center position-absolute bottom-0">
					<img src="<?//=base_url()?>assets/themes/<?//=isset($_SESSION['themes']) ? $_SESSION['themes'] : 'space' ?>/images/ic_logout.png" alt="">

					<span class="text-primary fw-semibold">LOGOUT</span>
				</a>
			</nav>
		</div> -->


		<!--Container Main start-->
		

        

    <!--Container Main end-->

		<!-- ============================================================== -->
		<!-- Start right Content here -->
		<!-- ============================================================== -->
		<div class="main-content">
		<div class="navbar border-bottom mb-3">
			<!-- <img class="me-3 hide-left-side" src="assets/images/icons/hide-open-icon.png" alt="" width="24" style="cursor: pointer;">  -->
			<button class="btn btn-primary btn-sm btn-menu" type="button" data-bs-toggle="offcanvas" data-bs-target="#staticBackdrop" aria-controls="staticBackdrop">
				<i class="bi bi-list fs-4 text-white"></i>
			</button>

            <div class="nav-link">
            	<span class="p-header">
                	Halo, <?=$_SESSION['nama']?>
				</span><br>
    
                <span class="p2-header poppins-extrabold">
					<?php if(isset($_SESSION['student_id'])): ?>
                    	Mau Belajar Apa Hari Ini?
					<?php endif ?>
					<?php if(isset($_SESSION['teacher_id'])): ?>
                    	Selamat Mengajar Bapak/Ibu Guru
					<?php endif ?>
				</span>
            </div>
                   
            <!--Notification & profile start-->
            <div class="d-flex flex-nowrap ms-auto">

				<!-- NOTIF DROPDOWN -->
				<div class="dropdown">
					<div class="notif-group me-4" style="margin-top: 13px;" data-bs-toggle="dropdown" aria-expanded="false">
						<?php $color = ''; 
							if($_SESSION['themes'] == 'space'){
								$color = '#281B93';
							} else if($_SESSION['themes'] == 'space-yellow'){
								$color = '#FFC107';
							} else if($_SESSION['themes'] == 'dino'){
								$color = '#7aaa23';
							}
						?>

						<svg width="25" height="25" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M29.3333 26.6667H2.66663V24H3.99996V14.7086C3.99996 8.05799 9.37255 2.66669 16 2.66669C22.6274 2.66669 28 8.05799 28 14.7086V24H29.3333V26.6667ZM12.6666 28H19.3333C19.3333 29.841 17.8409 31.3334 16 31.3334C14.159 31.3334 12.6666 29.841 12.6666 28Z" fill="<?=$color?>"/>
							<!-- <circle cx="25.1428" cy="6.85713" r="4.57143" fill="#DC3545"/> -->
						</svg>
						<span class="text-light rounded text-white fs-12 bg-danger rounded-5 notif-number py-0" style="padding: 3px;">0</span>
					</div>
					<ul class="notif-content dropdown-menu dropdown-menu-end px-2 border border-primary-subtle rounded-4" style="height: 300px; overflow-y:auto; overflow-x:hidden; width:400px;">
						
					</ul>
				</div>

				<!-- PROFILE DROPDOWN -->
				<div class="dropdown">
					<button class="btn rounded-pill p-1 btn-profile" type="button" data-bs-toggle="dropdown" aria-expanded="false">
						<div class="row d-flex align-items-stretch" style="margin-left: 0px;">
							<div class="col-auto d-flex align-items-center p-0 profile-btn-left">
								<img class="m-0 img-profile rounded-circle" src="<?=($_SESSION['userpic']) ? base_url('assets/images/users/').$_SESSION['userpic'] : base_url('assets/images/profile.png') ?>" width="40">
							</div>
							<div class="col-auto d-flex align-items-center profile-btn-center">
								<div class="bg-light w-100">
									<div class="row">
										<div class="col text-center">
											<p class="d-inline" style="font-size: 16px; font-weight:600;"><?=$_SESSION['nama']?></p>
										</div>
									</div>
									<div class="row">
										<div class="col text-center">
											<?=($_SESSION['user_level']==4) ? $this->db->where('class_id', $_SESSION['class_id'])->get('kelas')->row_array()['class_name']:''; ?>
										</div>
									</div>
								</div>
							</div>
							<div class="col-auto d-flex align-items-center profile-btn-right">
								<img src="assets/images/panah-bawah.png" alt="" width="12">
							</div>
						</div>
						
						
					</button>
					<ul class="dropdown-menu dropdown-menu-end px-3 py-4 rounded-4 border">
						<li class="text-center pt-3">
							<a class="text-decoration-none">
								<img class="d-inline-block rounded-pill" src="<?=base_url()?>assets/images/users/<?=$_SESSION['userpic']?>" alt="" width="75">
								<span class="d-block my-2">Hi <?=$_SESSION['nama']?></span>
							</a>
						</li>
						
						<li class="dropdown-submenu text-left" id="ganti-tema">
							<a class="dropdown-item fs-6 fw-bold" tabindex="-1" href="#"><i class="bi bi-arrow-left-right"></i> Ganti Tema <span class="caret"></span></a>
							<ul class="dropdown-menu px-2 rounded-4">
								<li class="mb-2">
									<a class="fs-6 d-block text-decoration-none choice-theme" href="<?=base_url('user/change_theme?theme=space')?>">
										<img class="border rounded-3" width="25" height="25" src="assets/themes/space/images/theme-icon.png" alt="Space">
										Space
									</a>
								</li>
								<li class="mb-2">
									<a class="fs-6 d-block text-decoration-none choice-theme" href="<?=base_url('user/change_theme?theme=space-yellow')?>">
										<img class="border rounded-3" width="25" height="25" src="assets/themes/space-yellow/images/theme-icon.png" alt="Space Yellow">
										Space Yellow
									</a>
								</li>
								<li class="mb-2">
									<a class="fs-6 d-block text-decoration-none choice-theme" href="<?=base_url('user/change_theme?theme=dino')?>">
										<img class="border rounded-3" width="25" height="25" src="assets/themes/dino/images/theme-icon.png" alt="Dino">
										Dino
									</a>
								</li>
							</ul>
						</li>
						
						<li>
							<a class="dropdown-item fs-6 text-left fw-bold" href="<?=base_url()?>user">
								<i class="bi-person"></i> Profile
							</a>
						</li>
						<li>
							<a class="dropdown-item fs-6 text-left fw-bold" onclick="logoutConfirm()">
								<i class="bi-box-arrow-left"></i> Keluar Akun
							</a>
						</li>
					</ul>
				</div>

            </div>
            <!--Notification & profile end-->
        </div>
			<div class="page-content">
				<div class="container-fluid">

					<!-- start page title -->
					<?php $this->load->view("_partials/breadcrumb") ?>
					<!-- end page title -->

					<?php echo $contents; ?>

				</div>
				<!-- container-fluid -->
			</div>
			<!-- End Page-content -->

			<?php $this->load->view("_partials/footer") ?>
		</div>
		<!-- end main content-->

		<!-- END layout-wrapper -->

		<!-- JAVASCRIPT -->
		
		<?php $this->load->view("_partials/js") ?>
		<?= !empty($page_js) ? add_js($page_js) : trim('') ?>

		<?=!empty($pathjs) ? $this->load->view($pathjs, NULL, TRUE) : trim('') ?>

		<!-- alert konfirmasi perubahan tema -->
		<div class="bg-primary text-white p-4 text-center rounded-4 alert-perubahan-tema">
			<p class="m-0">Tema berhasil di ubah</p>
			<button class="btn btn-light mt-3 cancel-change-theme">Batalkan</button>
		</div>

		<script>
			/** 
			 * DROP DOWN GANTI TEMA
			*/
			$(document).ready(function(){
				$('#ganti-tema a.dropdown-item').on("click", function(e){
					$(this).next('ul').toggle();
					e.stopPropagation();
					e.preventDefault();
				});
			});

			$('.choice-theme').on('click', function(e){
				localStorage.setItem('previous_theme', '<?=isset($_SESSION['themes']) ? $_SESSION['themes'] : 'space'?>');
			});

			$('.cancel-change-theme').on('click', function(){
				window.location.href = '<?=base_url('user/change_theme?theme=')?>' + localStorage.getItem('previous_theme');
			});

			// flash message ganti tema
			<?php if($this->session->flashdata('change_theme_success')): ?>
				setTimeout(function(){
					$('.alert-perubahan-tema').addClass('show');
				}, 1);
				setTimeout(function(){
					$('.alert-perubahan-tema').removeClass('show');
				}, 3000);
			<?php endif; ?>
		</script>
	</body>
	</html>
