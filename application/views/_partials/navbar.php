

<div class="nav_list">
	<ul class="nav flex-column">
		<li class="nav-item pl-4">
			<a class="nav-link" aria-current="page" href="<?=base_url()?>home">
				<div class="row row-cols-2">
					<div class="col sidebar-icon">
						<img src="assets/themes/space/images/ic_home.png">
					</div>
					<h5 class="p-nav">
						HOME
					</h5>
				</div>
			</a>
		</li>

		<li class="nav-item pl-4">
			<a class="nav-link" aria-current="page" href="<?=base_url()?>Materi">
				<div class="row row-cols-2">
					<div class="col sidebar-icon">
						<img src="assets/themes/space/images/ic_materi.png">
					</div>
					<h5 class="p-nav">
						MATERI
					</h5>
				</div>
			</a>
		</li>

		<!-- JIKA LOGIN SEBAGAI GURU -->
		<?php if( $_SESSION['user_level'] == 3): ?> 
		<li class="nav-item pl-4">
			<a class="nav-link" aria-current="page" href="<?=base_url()?>Sesi">
				<div class="row row-cols-2">
					<div class="col sidebar-icon">
						<img src="assets/themes/space/icons/ic_sesi.svg">
					</div>
					<h5 class="p-nav">
						SESI
					</h5>
				</div>
			</a>
		</li>
		<?php endif ?>

		<!-- JIKA LOGIN SEBAGAI MURID / GURU -->
		<?php if($_SESSION['user_level'] == 3 || $_SESSION['user_level'] == 4): ?> 
		<li class="nav-item pl-4">
			<a class="nav-link" aria-current="page" href="<?=base_url()?>asesmen">
				<div class="row row-cols-2">
					<div class="col sidebar-icon">
						<img src="assets/themes/space/images/ic_asesmen.png">
					</div>
					<h5 class="p-nav">
						ASESMEN
					</h5>
				</div>
			</a>
		</li>
		<?php endif ?>

		<!-- JIKA LOGIN SEBAGAI MURID / GURU / KEPSEK / ORTU -->
		<?php // if($_SESSION['user_level'] == 3 || $_SESSION['user_level'] == 4 || $_SESSION['user_level'] == 6): ?> 
		<li class="nav-item pl-4">
			<a class="nav-link" aria-current="page" href="<?=base_url()?>ebook">
				<div class="row row-cols-2">
					<div class="col sidebar-icon">
						<img src="assets/themes/space/images/ic_ebook.png">
					</div>
					<h5 class="p-nav">
						EBOOK
					</h5>
				</div>
			</a>
		</li>
		<?php // endif ?>

		<!-- JIKA LOGIN SEBAGAI MURID / GURU -->
		<?php if($_SESSION['user_level'] == 3 || $_SESSION['user_level'] == 4): ?> 
		<li class="nav-item pl-4">
			<a class="nav-link" aria-current="page" href="<?=base_url()?>task">
				<div class="row row-cols-2">
					<div class="col sidebar-icon">
						<img src="assets/themes/space/images/ic_tugas.png">
					</div>
					<h5 class="p-nav">
						TUGAS
					</h5>
				</div>
			</a>
		</li>
		<?php endif ?>

		<!-- JIKA USER LEVEL GURU / KEPSEK -->
		<?php if($_SESSION['user_level'] == 3 || $_SESSION['user_level'] == 6) : ?>
		<li class="nav-item pl-4">
			<a class="nav-link" aria-current="page" href="<?=base_url()?>student">
				<div class="row row-cols-2">
					<div class="col sidebar-icon">
						<img src="assets/images/icons/student-person.svg">
					</div>
					<h5 class="p-nav">
						SISWA
					</h5>
				</div>
			</a>
		</li>
		<?php endif ?>
		
		<?php if($_SESSION['user_level'] == 6) : ?>
		<li class="nav-item pl-4">
			<a class="nav-link" aria-current="page" href="<?=base_url()?>teacher">
				<div class="row row-cols-2">
					<div class="col sidebar-icon">
						<img src="assets/themes/space/images/ic_ruangsiswa.png">
					</div>
					<h5 class="p-nav">
						GURU
					</h5>
				</div>
			</a>
		</li>
		<?php endif ?>

		<!-- JIKA USER LEVEL MURID OR ORTU -->
		<?php if($_SESSION['user_level'] == 4 || $_SESSION['user_level'] == 5) : ?>
			<li class="nav-item pl-4">
				<a class="nav-link" aria-current="page" href="<?=base_url().'student/detail/'.$_SESSION['student_id'] ?>">
					<div class="row row-cols-2">
						<div class="col sidebar-icon">
							<img src="assets/themes/space/images/ic_ruangsiswa.png">
						</div>
						<h5 class="p-nav">
							SISWA
						</h5>
					</div>
				</a>
				<!-- <a class="nav-link" href="<?//=($user_level == 3) ? base_url('teacher/tasks') : base_url('student/detail/').$student_id ?>">Ruang Siswa</a> -->
			</li>											
		<?php endif ?>
		
		<li class="nav-item pl-4 d-none">
			<a class="nav-link" aria-current="page" href="<?=base_url()?>chat">
				<div class="row row-cols-2">
					<div class="col sidebar-icon">
						<img src="assets/themes/space/images/chat-dots-fill.png">
					</div>
					<h5 class="p-nav">
						CHATTING 
					</h5>
				</div>
			</a>
		</li>

		<li class="nav-item pl-4">
			<a class="nav-link" aria-current="page" href="<?=base_url()?>news">
				<div class="row row-cols-2">
					<div class="col sidebar-icon">
						<img src="assets/themes/space/images/ic_Pengumuman.png">
					</div>
					<h5 class="p-nav">
						PENGUMUMAN
					</h5>
				</div>
			</a>
		</li>

	</ul>
</div>
