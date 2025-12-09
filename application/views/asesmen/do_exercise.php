<?php
	
	if (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') === FALSE && strpos($_SERVER['HTTP_USER_AGENT'], 'CriOS') === FALSE) {
		header('Content-Type: text/plain');
	   	echo 'Browser ini tidak support untuk fitur ujian !!!';
	   	return;
   	}

	$theme = isset($_SESSION['themes']) ? $_SESSION['themes'] : 'space';
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<base href="<?=base_url()?>">
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Ujian</title>

	<script async>
		const visility = document.visibilityState;

		console.log(visibility);
	</script>

	<script type="application/json" id="period">
		<?=json_encode(['start_time' => $exam['start_date'], 'end_time' => $exam['end_date']])?>
	</script>


	<link rel="stylesheet" href="<?=base_url()?>assets/themes/<?=$theme?>/css/style.min.css">
	<link rel="stylesheet" href="<?=base_url()?>assets/themes/<?=$theme?>/css/dataTables.bootstrap5.min.css">
	<link rel="stylesheet" href="<?=base_url('assets/node_modules/bootstrap-icons/font/bootstrap-icons.min.css')?>">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/quill@2.0.0-rc.4/dist/quill.snow.css">
	<link rel="stylesheet" href="<?=base_url()?>assets/node_modules/sweetalert2/dist/sweetalert2.min.css">

	<style>
		@font-face {
			font-family: 'TickingTimebomb';
			src: url('assets/fonts/TickingTimebombBB.ttf');
		}

		.line{
			width: 100%;
			border-bottom: 1px solid rgba(0, 0, 0, 0.1);
		}

		p {
			font-size: 14px;
		}

		.section-answer {
			background-color: rgba(148, 196, 79, 0.05);
		}

		.youre-answer {
			width: 200px;
		}

		.alphabet {
			margin-left: 35px;
			font-size: 18px;
		}

		.round {
			position: absolute;
		}

		.round label {
			background-color: #fff;
			border: 1px solid #ccc;
			border-radius: 50%;
			cursor: pointer;
			height: 28px;
			left: 0;
			position: absolute;
			top: 0;
			width: 28px;
		}

		.round label:after {
			border: 2px solid #fff;
			border-top: none;
			border-right: none;
			content: "";
			height: 6px;
			left: 7px;
			opacity: 0;
			position: absolute;
			top: 8px;
			transform: rotate(-45deg);
			width: 12px;
		}

		.round input[type="radio"] {
			visibility: hidden;
		}

		.round input[type="radio"]:checked + label {
			background-color: #66bb6a;
			border-color: #66bb6a;
		}

		.round input[type="radio"]:checked + label:after {
			opacity: 1;
		}

		.timer-container{
			/* From https://css.glass */
			background: rgba(6, 225, 255, 0.2);
			/* border-radius: 16px; */
			box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
			backdrop-filter: blur(5px);
			-webkit-backdrop-filter: blur(5px);
			/* border: 1px solid rgba(6, 225, 255, 0.3); */
		}

		#shortcut-container {
			width: 100%;
			height: 400px;
			overflow-y: auto;
		}

		#shortcut-container ul.shortcut-list > .list-box,
		#shortcut-container ul.shortcut-list .list-box > a {
			height: 40px;
			width: 40px;
			cursor: pointer;
		}

		#editor {
			height: 240px;
		}

		.essay-answer {
			max-height: 100px;
			overflow: auto;  
			text-wrap: pretty;
		}

		#timer {
			font-family:'TickingTimebomb', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
			font-size: 28px;
		}

		.mt-lg-6 {
			margin-top: 4rem !important;
		}
		
		.question {
			scroll-margin-top: 75px;
		}

	</style>
</head>
<body>

	<section class="container-fluid explore-section section-padding" id="section_2">

		<nav class="navbar fixed-top bg-body-tertiary py-1 px-3">
			<div class="row w-100">
				<div class="col-lg-9 row">
					<div class="col"><img width="150" src="<?=base_url()?>assets/themes/<?=isset($_SESSION['themes']) ? $_SESSION['themes'] : 'space'?>/images/logowowedu.png" alt=""></div>
					<div class="col text-end">
						<span style="font-size: 16px; font-weight: 600; display: block;"><?=$exam['title']?></span>
						<span style="font-size: 14px; display: block;"><?=$exam['subject_name']?> - <?=$exam['class_name']?></span>
					</div>
				</div>
				<div class="col-lg-3 d-flex justify-content-end align-items-center">
					<span id="timer"></span>

				</div>
			</div>
		</nav>
	
		<!-- <a class="text-decoration-none mt-3" href="<?//=base_url('asesmen')?>"><h5>< kembali ke lembar asesmen</h5></a> -->
	
		<?php if(isset($_SESSION['teacher_id'])): ?>
			<div class="alert alert-primary d-flex align-items-center" role="alert">
				<i class="bi bi-exclamation-triangle-fill text-primary me-2"></i>
				<div>
					Skema penilaian hanya akan terlihat oleh Bapak/Ibu Guru dan tidak akan terlihat oleh siswa Bapak/Ibu Guru ketika mereka mengerjakan tugas yang diberikan.
				</div>
			</div>
		<?php  endif ?>

		<div class="row mt-3 py-5">
			<button id="qwertzoo" hidden></button>
			<div class="col-lg-9">
	
				<div class="w-100" id="nav-sesi"  tabindex="0">
					

					<input type="hidden" name="csrf_token_name" value="<?=$this->security->get_csrf_hash();?>">
					<input type="hidden" name="exam_id" value="<?=isset($exam_id) ? $exam_id : ''?>">
					<input type="hidden" name="tipe" value="<?=isset($exam['tipe']) ? $exam['tipe'] : ''?>">

					<?php for($i=1; $i<=2; $i++): ?>
						<div class="testpaper-sectionContainer <?= $i > 1 ? 'd-none' : trim('')?> mt-2">
							<div class="card">
								<div class="card-header bg-primary text-white">
									Bagian <?=$i?>
								</div>

								<?php if($i == 1): ?>
									<div class="row px-3 pt-3">
										<div class="col">
											<p>Waktu Mulai: <span class="fw-bold"><?=date('d M Y, H:i', strtotime($exam['start_date']))?></span></p>
										</div>
										<div class="col text-end">
											<p>Waktu Berakhir: <span class="badge text-red bg-danger fs-6"><?=date('d M Y, H:i', strtotime($exam['end_date']))?></span></p>
										</div>
										<input id="end-date" type="hidden" value="<?=($exam['end_date'])?>">									
									</div>
									<hr>
								<?php endif ?>


								<div class="card-body">

									<div class="row">
										<div class="content-<?=$i?>"></div>
									</div>

								</div>
							</div>

						</div>
					<?php endfor ?>


				</div>
					

				<div class="row my-3">
					<div class="col-12 d-flex flex-row-reverse">
						
						<?php if($exam_answer <= 0): ?>
							<button class="btn btn-sm btn-primary text-white ms-1" id="exam_submit">Selesaikan Ujian</button>
						<?php endif ?>

						<?php if($exam_answer > 0): ?>
							<a href="<?=base_url().'asesmen/show_answer?id='.$exam_id?>" class="btn btn-sm btn-primary text-white ms-1" id="show_answer" target="_blank">Lihat Jawaban</a>
						<?php endif ?>

						<!-- <button class="btn btn-sm btn-success text-white ms-1" id="create" data-bs-toggle="modal" data-bs-target="#exampleModal">Unduh sebagai PDF</button> -->
					</div>
				</div>


			</div>
			
			<div class="col-lg-3 pt-2 vh-100">
				<div id="shortcutCard" class="card sticky-top end-0 mt-1 me-3" style="height: 400px; width: 100%">
					<div class="card-body">
						<div id="shortcut-container">
							<h4 class="mb-0">TOMBOL PINTAS</h4> <hr class="my-1 mb-2 w-100">
							<div id="c-1" class="w-100"></div>
							<h4 class="mb-0 mt-4 d-none">BAGIAN II</h4> <hr class="my-1 w-100 d-none">
							<div id="c-2" class="w-100 d-none"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
			

	
		<!-- Modal -->
		<div class="modal fade" id="answerModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h1 class="modal-title fs-5" id="exampleModalLabel">Masukan Jawaban Anda</h1>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<!-- Create the editor container -->
						<div id="editor">
							
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary save">Save changes</button>
					</div>
				</div>
			</div>
		</div>
	
	
	</section>

	<script>
		const BASE_URL = '<?=base_url()?>',
			  ADMIN_URL = '<?=html_escape($this->config->item('admin_url'))?>';
	</script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

	<script src="<?=base_url('assets/node_modules/moment/moment.js')?>"></script>
	<script src="<?= base_url('assets/js/jquery.min.js'); ?>"></script>
	<script src="<?=base_url('assets/themes/space/js/jquery.dataTables.min.js')?>"></script>
	<script src="<?=base_url('assets/themes/space/js/dataTables.bootstrap5.min.js')?>"></script>
	<script src="<?=base_url('assets/node_modules/sweetalert2/dist/sweetalert2.min.js')?>"></script>

	<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/quill@2.0.0-rc.4/dist/quill.js"></script>
	<script src="<?= base_url()?>assets/js/storingAnswer.js" defer></script>
	<script src="<?= base_url()?>assets/js/_do_exercise.js" defer></script>
	
	<script>
		// create swall alert
		$(document).ready(function () {
			<?php if(!empty($_SESSION['success']) && $_SESSION['success']['success'] == true) : ?>
				Swal.fire({
					icon: 'success',
					title: '<h4 class="text-success"></h4>',
					html: '<span class="text-success"><?= $_SESSION['success']['message'] ?></span>',
					timer: 5000
				});
		
			<?php endif; ?>
		
			<?php if(!empty($_SESSION['success']) && $_SESSION['success']['success'] == false) : ?>
				Swal.fire({
					icon: 'error',
					title: '<h4 class="text-danger"></h4>',
					html: '<span class="text-danger"><?= $_SESSION['success']['message'] ?></span>',
					timer: 5000
				});
			<?php endif; ?>
		});
	</script>
</body>
</html>



