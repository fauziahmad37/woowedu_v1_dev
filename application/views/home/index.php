<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

<style>
	.highcharts-credits{
		display: none;
	}

	.disabled-link {
		pointer-events: none;
	}
</style> 

<?php
//use Dompdf\Css\Style;
$user_level = $this->session->userdata('user_level'); 
			$userid 	= $this->session->userdata('userid');
			$user 		= $this->db->where('userid', $userid)->get('users')->row_array();
			$imageLink 	= base_url('assets/images/users/').$user['photo'];
			$user_level	= $user['user_level'];

			$student = ($user_level == 4) ? $this->db->where('nis', $user['username'])->get('student')->row_array() : [];
			$student_id = ($student) ? $student['student_id'] : '';

			$teacher = ($user_level == 3) ? $this->db->where('nik', $user['username'])->get('teacher')->row_array() : [];
			$teacher_id = ($teacher) ? $teacher['teacher_id'] : '';
?>
<section class="explore-section section-padding py-3" id="section_2">

	<div class="row">
		<div id="home-carousel" class="col-12 justify-content-center rounded-5 py-3" style="height: 18rem;">
			<!-- COROUSEL -->
			<div id="carouselExampleCaptions" class="carousel h-100 slide">
				<div class="carousel-indicators">
					
					<?php if(isset($news[0])): ?>
					<button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
					<?php endif ?>

					<?php if(isset($news[1])): ?>
					<button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2"></button>
					<?php endif ?>
					
					<?php if(isset($news[2])): ?>
					<button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3"></button>
					<?php endif ?>
				</div>
				<div class="carousel-inner h-100" role="listbox"> 
					
					<div class="text-white papan-pengumuman" style="">
						<img src="<?=base_url('assets/images/icon-tag-pengumuman.png')?>" alt=""> 
						<p>Papan Pengumuman</p>
					</div>

					<?php if(isset($news[0])): ?>
					<div class="carousel-item h-100 active">
						<div class="d-block ms-3 pe-5 w-100 text-white pt-5 mt-3 text-center">
							<a href="<?=base_url('news/detail/').$news[0]['id']?>" class="h5 my-4 fw-bold text-decoration-none"><?=strtoupper(substr($news[0]['judul'], 0, 80))?></a>
							<p class="mt-4 ps-3 pe-3"><?=substr($news[0]['isi'], 0, 300)?>...</p>
						</div>
						<p class="text-white fw-bold tanggal-pengumuman"><?=date('d M Y - H:i', strtotime($news[0]['tanggal']))?></p>
					</div>
					<?php endif ?>

					<?php if(isset($news[1])): ?>
					<div class="carousel-item h-100">
						<div class="d-block ms-3 pe-5 w-100 text-white pt-5 mt-3 text-center">
							<a href="<?=base_url('news/detail/').$news[1]['id']?>" class="h5 my-4 fw-bold text-decoration-none"><?=strtoupper(substr($news[1]['judul'], 0, 80))?></a>
							<p class="mt-4 ps-3 pe-3"><?=substr($news[1]['isi'], 0, 300)?>...</p>
						</div>
						<p class="text-white fw-bold tanggal-pengumuman"><?=date('d M Y - H:i', strtotime($news[1]['tanggal']))?></p>
					</div>
					<?php endif ?>

					<?php if(isset($news[2])): ?>
					<div class="carousel-item h-100">
						<div class="d-block ms-3 pe-5 w-100 text-white pt-5 mt-3 text-center">
							<a href="<?=base_url('news/detail/').$news[2]['id']?>" class="h5 my-4 fw-bold text-decoration-none"><?=strtoupper(substr($news[2]['judul'], 0, 80))?></a>
							<p class="mt-4 ps-3 pe-3"><?=substr($news[2]['isi'], 0, 300)?>...</p>
						</div>
						<p class="text-white fw-bold tanggal-pengumuman"><?=date('d M Y - H:i', strtotime($news[2]['tanggal']))?></p>
					</div>
					<?php endif ?>

				</div>

				<?php if(isset($news[1])): ?>
				<button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
					<span class="carousel-control-prev-icon" aria-hidden="true"></span>
					<span class="visually-hidden">Previous</span>
				</button>
				<button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
					<span class="carousel-control-next-icon" aria-hidden="true"></span>
					<span class="visually-hidden">Next</span>
				</button>
				<?php endif ?>
			</div>
			<!-- COROUSEL -->
		</div>
	</div>

	<!-- SECTION CHART UNTUK KEPSEK -->
	<?php if($user_level == 6): ?>
		<div class="text-center mt-4">
			<button class="btn btn-primary btn-tab-siswa">Siswa</button>
			<button class="btn btn-light btn-tab-guru">Guru</button>
		</div>

		<div class="tab-siswa d-block">
			<div class="row mt-5">
				<div class="col-4 d-flex">
					<div class="bg-secondary-subtle rounded-4 p-3 d-inline-block"><i class="bi bi-people fs-3 mx-2 fw-bold"></i></div>
					<div class="d-inline-block ms-4 pt-3">
						<p class="fs-3 fw-bold text-secondary" style="line-height: 0px;"><?=$total_siswa?></p>
						<p class="lh-1 fs-5 mt-4 text-body-secondary" style="line-height: 0px;">Jumlah Siswa</p>
					</div>
				</div>
				
				<div class="col-4 d-flex">
					<div class="bg-success-subtle rounded-4 p-3 d-inline-block"><i class="bi bi-people fs-3 mx-2 fw-bold"></i></div>
					<div class="d-inline-block ms-4 pt-3">
						<p class="fs-3 fw-bold text-success" style="line-height: 0px;"><?=$total_siswa_aktif?></p>
						<p class="lh-1 fs-5 mt-4 text-body-secondary" style="line-height: 0px;">Jumlah Siswa Aktif</p>
					</div>
				</div>
	
				<div class="col-4 d-flex">
					<div class="bg-danger-subtle rounded-4 p-3 d-inline-block"><i class="bi bi-people fs-3 mx-2 fw-bold"></i></div>
					<div class="d-inline-block ms-4 pt-3">
						<p class="fs-3 fw-bold text-danger" style="line-height: 0px;"><?=$total_siswa_tidak_aktif?></p>
						<p class="lh-1 fs-5 mt-4 text-body-secondary" style="line-height: 0px;">Jumlah Siswa Tidak Aktif</p>
					</div>
				</div>
			</div>
	
			<figure class="highcharts-figure mt-5">
				<div id="container-chart"></div>
			</figure>
		</div>

		<div class="tab-guru d-none">
			<div class="row mt-5">
				<div class="col-4 d-flex">
					<div class="bg-secondary-subtle rounded-4 p-3 d-inline-block"><i class="bi bi-people fs-3 mx-2 fw-bold"></i></div>
					<div class="d-inline-block ms-4 pt-3">
						<p class="fs-3 fw-bold text-secondary" style="line-height: 0px;"><?=$teacher_status_chart[0][1] + $teacher_status_chart[1][1]?></p>
						<p class="lh-1 fs-5 mt-4 text-body-secondary" style="line-height: 0px;">Jumlah Guru</p>
					</div>
				</div>
				
				<div class="col-4 d-flex">
					<div class="bg-success-subtle rounded-4 p-3 d-inline-block"><i class="bi bi-people fs-3 mx-2 fw-bold"></i></div>
					<div class="d-inline-block ms-4 pt-3">
						<p class="fs-3 fw-bold text-success" style="line-height: 0px;"><?=$teacher_status_chart[0][1]?></p>
						<p class="lh-1 fs-5 mt-4 text-body-secondary" style="line-height: 0px;">Jumlah Guru Aktif</p>
					</div>
				</div>
	
				<div class="col-4 d-flex">
					<div class="bg-danger-subtle rounded-4 p-3 d-inline-block"><i class="bi bi-people fs-3 mx-2 fw-bold"></i></div>
					<div class="d-inline-block ms-4 pt-3">
						<p class="fs-3 fw-bold text-danger" style="line-height: 0px;"><?=$teacher_status_chart[1][1]?></p>
						<p class="lh-1 fs-5 mt-4 text-body-secondary" style="line-height: 0px;">Jumlah Guru Tidak Aktif</p>
					</div>
				</div>
			</div>
	
			<figure class="highcharts-figure mt-5">
				<div id="container-chart2"></div>
			</figure>
		</div>
		
	<?php endif ?>

	<div class="d-none">
		<?php if($user_level == 3) : ?>	
			<div class="row mt-5 py-3">
				<div class="col-lg-3 col-md-6 col-sm-12 col-xs-12 mb-4">
					<div class="card card-info border-0 bg-white shadow-lg">
						<div class="card-body">
							<a href="<?=base_url()?>materi?mode=table">
								<div class="d-flex">
									<div>
										<p class="mb-2 task-card-title">Materi Pelajaran</p>
										<p class="fs-12">Daftar materi pelajaran</p> 
									</div>
								</div>
							</a>
						</div>
					</div>
				</div>	

				<div class="col-lg-3 col-md-6 col-sm-12 col-xs-12  mb-4">
					<div class="card border-0 bg-white shadow-lg">
						<div class="card-body">
							<a href="<?=base_url()?>asesmen?mode=table">
								<div class="d-flex">
									<div>
										<p class="mb-2 task-card-title">Mengajar dan Review Performa</p>
										<p class="fs-12">Review Performa Siswa</p> 
									</div>
								</div>
							</a>
						</div>		
						
					</div>
				</div>	

				<div class="col-lg-3 col-md-6 col-sm-12 col-xs-12  mb-4">
					<div class="card border-0 bg-white shadow-lg">
						<div class="card-body">
							<a href="<?=base_url('task')?>">
								<div class="d-flex">
									<div>
										<p class="mb-2 task-card-title">Tugas</p>
										<p class="fs-12">Daftar Tugas</p> 
									</div>
								</div>
							</a>
						</div>
						
					</div>
				</div>
				
				<div class="col-lg-3 col-md-6 col-sm-12 col-xs-12  mb-4">
					<div class="card border-0 bg-white shadow-lg">
						<div class="card-body">
							<a href="<?=base_url('student/')?>">
								<div class="d-flex">
									<div>
										<p class="mb-2 task-card-title">Interaksi</p>
										<p class="fs-12">Interaksi dengan siswa</p> 
									</div>
								</div>
							</a>
						</div>
						
					</div>
				</div>				
			</div>			
		<?php endif; ?>	 
	
		<?php if($user_level == 4  ) : ?>	
			<div class="container-fluid mt-5 py-3">
				<div class="row justify-content-center">
					<div class="col-lg-3 col-md-6 col-sm-12 col-xs-12  mb-4">
						<div class="card card-info shadow-lg">
							<div class="card-body">
								<a class="text-decoration-none" href="<?=base_url()?>ebook">
									<div class="d-flex">
										<div>
											<p class="mb-2 task-card-title">Belajar</p>
											<p class="fs-12">Untuk belajar melalui E-Book</p> 
										</div>
									</div>
								</a>
							</div>
							
						</div>
					</div>	

					<div class="col-lg-3 col-md-6 col-sm-12 col-xs-12  mb-4">
						<div class="card bg-white shadow-lg">
							<div class="card-body">
								<a href="<?=base_url()?>task">
									<div class="d-flex">
										<div>
											<p class="mb-2 task-card-title">Tugas</p>
											<p class="fs-12">Daftar tugas yang diberikan</p> 
										</div>
									</div>
								</a>
							</div>
							
						</div>
					</div>	

					<div class="col-lg-3 col-md-6 col-sm-12 col-xs-12  mb-4">
						<div class="card bg-white shadow-lg">
							<div class="card-body">
								<a href="<?=base_url()?>teacher">
									<div class="d-flex">
										<div>
											<p class="mb-2 task-card-title">Interaksi</p>
											<p class="fs-12">Interaksi dengan guru</p> 
										</div>
									</div>
								</a>
							</div>
						
						</div>
					</div>
					
					<div class="col-lg-3 col-md-6 col-sm-12 col-xs-12  mb-4">
						<div class="card bg-white shadow-lg">
							<div class="card-body">
								<a href="<?=base_url('student/detail/').$student_id?>">
									<div class="d-flex">
										<div>
											<p class="mb-2 task-card-title">Ujian</p>
											<p class="fs-12">Nilai kemampuan diri</p> 
										</div>
									</div>
								</a>
							</div>
							
						</div>
					</div>				
				</div>		
			</div>		
		<?php endif ?>	
	</div>

	<!-- CARD TUGAS UNTUK LOGIN GURU DAN MURID -->
	<?php if($user_level == 3 || $user_level == 4 || $user_level == 5): ?>
		<div class="row mt-4">
			<div class="col"><h4 class="fw-bold"><i class="bi bi-pen-fill me-2"></i> Tugas</h4></div>
			<div class="col text-end"><a href="<?=base_url('task')?>">Lihat Semua</a></div>
		</div>
	
		<div class="row py-3">
				
			<?php 
				$cardType = [
					'success',
					'info',
					'warning'
				];
			?>

			<?php  if(isset($deadline)) : ?>
				<?php foreach ($deadline as $key => $value) : ?>
				<?php $color =  $cardType[array_rand($cardType)] ?>
				<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12  mb-4 px-0">
					<div class="card card-<?=$color?> card-overlay border-0 rounded-4 h-100 me-3" style="padding-top: 8px; padding-bottom: 16px; padding-left: 16px;">
						<!-- Jika tanggal mengerjakan tugas nya lebih besar dari sekarang maka disable href nya -->
						<a class="text-decoration-none <?=( ($user_level == 4) && ((strtotime($value['available_date'])) > time()) ) ? 'disabled-link' : ''; ?>" href="<?=base_url('task/detail/').$value['task_id']?>" >
							<div class="d-flex">
								<div>
									<p class="mb-2 task-card-title" style="font-weight: 600;"><?=$value['subject_name']?></p>

									<p class="fs-12 lh-lg <?=($user_level == 3) ? 'd-none':'' ?>">Guru: <?=$value['teacher_name']?></p>
									<h6 class="mt-2"><span class="badge bg-dark lh-lg bg-opacity-75 <?=(($user_level == 4) ? 'd-none':'')?>"><?=$value['class_name']?></span></h6>

									<span class="lh-lg badge bg-<?=$color?>">Waktu Mulai: <?= date('d M Y H:i', strtotime($value['available_date'])) ?></span>
									<p class="task-card-content mt-2" style="font-size: 12px;"><?=$value['title']?></p>
									<span class="lh-lg badge bg-<?=$color?>">Batas Akhir: <?= date('d M Y H:i', strtotime($value['due_date'])) ?></span>
								</div>
							</div>
						</a>
					</div>
				</div>
				<?php endforeach; ?>
			<?php endif; ?>

		</div>
	<?php endif ?>

</section>



