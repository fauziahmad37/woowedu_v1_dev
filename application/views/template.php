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

		<!-- Modal Empty State "File Tidak Dapat Dimuat dengan Sempurna" -->
		<!-- dengan icon dan teks di bawah nya -->

		<div class="modal fade" id="modal-file-not-found" tabindex="-1" aria-labelledby="emptyStateModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-body text-center">
						

						<svg width="200" height="200" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
							<ellipse opacity="0.1" cx="100" cy="194" rx="100" ry="6" fill="#3A3C47"/>
							<path fill-rule="evenodd" clip-rule="evenodd" d="M48 50C48 43.3726 53.3726 38 60 38H115.915C116.309 38 116.701 38.0194 117.09 38.0576V64.9107C117.09 69.329 120.672 72.9107 125.09 72.9107H150V122.644C148.067 122.222 146.06 122 144 122C128.536 122 116 134.536 116 150C116 154.578 117.099 158.899 119.046 162.714H60C53.3726 162.714 48 157.342 48 150.714V50ZM121.534 166.714H60C51.1634 166.714 44 159.551 44 150.714V50C44 41.1634 51.1634 34 60 34H115.915C120.159 34 124.228 35.6857 127.229 38.6863L149.314 60.771C152.314 63.7716 154 67.8412 154 72.0847V72.7685L154.001 72.9107H154V123.839C164.525 127.864 172 138.059 172 150C172 165.464 159.464 178 144 178C134.801 178 126.638 173.564 121.534 166.714ZM66.5858 62.5858C67.3668 61.8047 68.6332 61.8047 69.4142 62.5858L76.5 69.6716L83.5858 62.5858C84.3668 61.8047 85.6332 61.8047 86.4142 62.5858C87.1953 63.3668 87.1953 64.6332 86.4142 65.4142L79.3284 72.5L86.4142 79.5858C87.1953 80.3668 87.1953 81.6332 86.4142 82.4142C85.6332 83.1953 84.3668 83.1953 83.5858 82.4142L76.5 75.3284L69.4142 82.4142C68.6332 83.1953 67.3668 83.1953 66.5858 82.4142C65.8047 81.6332 65.8047 80.3668 66.5858 79.5858L73.6716 72.5L66.5858 65.4142C65.8047 64.6332 65.8047 63.3668 66.5858 62.5858ZM66 101C66 98.2386 68.2386 96 71 96H101C103.761 96 106 98.2386 106 101C106 103.761 103.761 106 101 106H71C68.2386 106 66 103.761 66 101ZM71 100C70.4477 100 70 100.448 70 101C70 101.552 70.4477 102 71 102H101C101.552 102 102 101.552 102 101C102 100.448 101.552 100 101 100H71ZM71 112C68.2386 112 66 114.239 66 117C66 119.761 68.2386 122 71 122H127C129.761 122 132 119.761 132 117C132 114.239 129.761 112 127 112H71ZM70 117C70 116.448 70.4477 116 71 116H127C127.552 116 128 116.448 128 117C128 117.552 127.552 118 127 118H71C70.4477 118 70 117.552 70 117ZM160.163 50.4514C158.85 48.5876 156.485 47.7826 154.308 48.4583C150.9 49.5159 149.513 53.5892 151.568 56.5064L155.927 62.6935C157.278 64.611 158.009 66.8962 158.022 69.2418L158.065 76.8451C158.081 79.7534 160.45 82.0987 163.358 82.0859C166.27 82.073 168.619 79.7014 168.605 76.7897L168.564 68.5314C168.544 64.4964 167.288 60.5645 164.964 57.2659L160.163 50.4514ZM155.493 52.2786C156.014 52.1171 156.579 52.3095 156.893 52.7551L161.694 59.5695C163.547 62.1994 164.548 65.3342 164.564 68.5511L164.605 76.8094C164.608 77.5112 164.042 78.0828 163.34 78.0859C162.639 78.089 162.068 77.5237 162.065 76.8228L162.022 69.2194C162.004 66.0567 161.018 62.9753 159.197 60.3898L154.838 54.2027C154.347 53.5053 154.679 52.5314 155.493 52.2786ZM120 150C120 136.745 130.745 126 144 126C157.255 126 168 136.745 168 150C168 163.255 157.255 174 144 174C130.745 174 120 163.255 120 150ZM139 137C139 134.239 141.239 132 144 132C146.761 132 149 134.239 149 137V149C149 151.761 146.761 154 144 154C141.239 154 139 151.761 139 149V137ZM144 136C143.448 136 143 136.448 143 137V149C143 149.552 143.448 150 144 150C144.552 150 145 149.552 145 149V137C145 136.448 144.552 136 144 136ZM144 168C141.239 168 139 165.761 139 163C139 160.239 141.239 158 144 158C146.761 158 149 160.239 149 163C149 165.761 146.761 168 144 168ZM143 163C143 163.552 143.448 164 144 164C144.552 164 145 163.552 145 163C145 162.448 144.552 162 144 162C143.448 162 143 162.448 143 163Z" fill="#3A3C47"/>
						</svg>


						<p class="mt-3">File tidak dapat dimuat dengan sempurna</p>
						<button type="button" class="btn btn-primary mt-3" data-bs-dismiss="modal">Tutup</button>
					</div>
				</div>
			</div>
		</div>

		<script>
			const JWT_TOKEN = document.querySelector('meta[name="jwt_token"]').getAttribute('content');

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

			// ==========================================================
			// timer auto logout if user idle
			let idleTime = 0;
			$(document).ready(function () {
				// Increment the idle time counter every minute.
				setInterval(timerIncrement, 1000); //  1 minute for testing, change to 900000 for 15 minutes

				// Zero the idle timer on mouse movement or key press
				$(this).mousemove(resetTimer);
				$(this).keypress(resetTimer);
			});
			function timerIncrement() {
				idleTime++;
				//console.log("Idle time: " + idleTime + " minutes");
				if (idleTime >= 900) { // 1 minutes for testing, change to 15 for 30 minutes
					window.location.href = "<?=base_url('auth/logout')?>";
				}
			}
			function resetTimer() {
				idleTime = 0;
			}

			
		</script>
	</body>
	</html>
