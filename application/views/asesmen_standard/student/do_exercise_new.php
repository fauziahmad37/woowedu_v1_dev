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
	<base href="<?= base_url() ?>">
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="csrf_name" content="<?= !empty($this->security->get_csrf_token_name()) ? $this->security->get_csrf_token_name() : NULL ?>">
	<meta name="csrf_token" content="<?= !empty($this->security->get_csrf_hash()) ? $this->security->get_csrf_hash() : NULL ?>">
	<title>Ujian</title>

	<script>
		const extId = "bfehndilmpolmnclhcecjnfddjiijmbc";

		// chrome.runtime.sendMessage(extId, {
		// 		command: "startExam",
		// 		params: {
		// 			"startAt": new Date().toLocaleString()
		// 		}
		// 	},
		// 	function(resp) {
		// 		console.log(resp);
		// 	}
		// );

	</script>

	<script type="application/json" id="period">
		<?= json_encode(['start_time' => $exam['start_date'], 
						 'end_time' => $exam['end_date'], 
						 'duration' => $exam['duration'], 
						 'class_id' => $exam['class_id'],
						 'class_name' => $exam['class_name'],
						 'subject_id' => $exam['subject_id'],
						 'subject_name' => $exam['subject_name'],
						 'title' => $exam['title']]) ?>
	</script>


	<link rel="stylesheet" href="<?= base_url() ?>assets/themes/<?= $theme ?>/css/style.min.css">
	<link rel="stylesheet" href="<?= base_url() ?>assets/themes/<?= $theme ?>/css/dataTables.bootstrap5.min.css">
	<link rel="stylesheet" href="<?= base_url('assets/node_modules/bootstrap-icons/font/bootstrap-icons.min.css') ?>">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/quill@2.0.0-rc.4/dist/quill.snow.css">
	<link rel="stylesheet" href="<?= base_url() ?>assets/node_modules/sweetalert2/dist/sweetalert2.min.css">

	<!-- Google Font -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

	<script src="<?= base_url() ?>assets/js/jquery.min.js"></script>
	<script src="https://kit.fontawesome.com/b377b34fd7.js"></script>

	<link rel="stylesheet" href="<?= base_url() ?>assets/themes/<?= $theme ?>/css/do_exercise.css">

	<style>
		.poppins-regular {
			font-family: "Poppins", sans-serif;
			font-weight: 400;
			font-style: normal;
		}

		html,
		body {
			background-color: var(--bs-primary);
			min-height: 100% !important;
			height: 100%;
		}

		.bg-primary-600 {
			color: var(--bs-white);
			background-color: var(--bs-primary-600);
		}
		.bg-primary-700 {
			color: var(--bs-white);
			background-color: var(--bs-primary-700);
		}

		.bg-primary-200 {
			color: var(--bs-white);
			background-color: var(--bs-primary-200);
		}
		.bg-primary-300 {
			color: var(--bs-white);
			background-color: var(--bs-primary-300);
		}
		.bg-primary-400 {
			color: var(--bs-white);
			background-color: var(--bs-primary-400);
		}
		.bg-primary-600 {
			color: var(--bs-white);
			background-color: var(--bs-primary-600);
		}

		.box-answer-drop {
			border: 1px dashed var(--bs-light);
		}

		.btn-next-question {
			background-color: var(--bs-white);
		}
		.btn-next-question:hover {
			color: var(--bs-btn-active-color);
			background-color: var(--bs-btn-active-bg);
			border-color: var(--bs-btn-active-border-color);
		}
		.btn-next-question:disabled {
			background-color: var(--bs-gray-400);
			color: var(--bs-gray-700);
		}
		.text-question {
			display: block;
			transform: translate(-50%, -50%);
		}
		.nomor {
			transform: translateX(-50%);
			border: 1px solid var(--bs-white);
			z-index: 200;
			margin-top: -15px;
			padding: .5rem 3rem;
		}
		.soal-footer {
			overflow: hidden
		}

		.soal-footer:before {
			content: '';
			width: 100%;
			height: 100%;
			position: absolute;
			top: 0;
			left: 0;
			z-index: 50;
			transform: scaleX(0);
			transform-origin: left left;
			transition: transform 500ms ease-in-out 140ms;
		}

		#text-submit-status, #text-dd-submit {
			opacity: 0;
			visibility: hidden;
			z-index: 56;
			transition: opacity 500ms ease-in 140ms, visibility 500ms ease-in 140ms, top 500ms ease-in 140ms;
		}

		.soal-footer.is-success:before {
			transform: scaleX(1);
			transform-origin: left left;
			transition: transform 500ms ease-in-out 140ms;
			background-color: var(--bs-success);
		}
		.soal-footer.is-failed:before {
			transform: scaleX(1);
			transform-origin: left left;
			transition: transform 500ms ease-in-out 140ms;
			background-color: var(--bs-danger);
		}

		:is(.soal-footer.is-failed, .soal-footer.is-success) #text-submit-status,
		:is(.soal-footer.is-failed, .soal-footer.is-success) #text-dd-submit {
			opacity: 1;
			visibility: visible;
			transition: opacity 500ms ease-in 140ms, visibility 500ms ease-in 140ms, top 500ms ease-in 140ms;
		}

		.drop-box {
			overflow: hidden;
			display: inline-block;
		}
		.dragbox {
			cursor: grab;
		}
	</style>
</head>

<body class="poppins-regular">

	<section class="bg-primary h-100" style="padding: 24px;">

		<section class="header">
			<input name="exam_id" type="hidden" value="<?= $exam['exam_id'] ?>">
			<input name="student_id" type="hidden" value="<?= isset($_SESSION['student_id']) ? $_SESSION['student_id'] : $_SESSION['teacher_id'] ?>">

			<div class="row">
				<div class="col-6">
					<span class="text-white p-2 rounded-3" style="background-color: rgba(255, 255, 255, 0.2); font-size: 16px;">
						<i class="fa-solid fa-stopwatch"></i>
						<span id="timer" class="fw-bold"></span>
					</span>
				</div>
				<div class="col-6">
					<div class="text-end">
						<!-- full screen -->
						<button class="btn text-white p-2 rounded-3 me-2 expand-screen" style="background-color: rgba(255, 255, 255, 0.2); font-size: 16px;">
							<i class="fa-solid fa-expand"></i>
						</button>

						<button class="btn text-white p-2 rounded-3" style="background-color: rgba(255, 255, 255, 0.2); font-size: 16px;" data-bs-toggle="modal" data-bs-target="#logoutExamModal">
							<i class="fa-solid fa-power-off"></i>
						</button>

					</div>
				</div>
		</section>

		<section class="content mt-4 h-100">

			<!-- soal fill the blank -->
			<div id="student-fill-the-blank-container" class="h-100 d-none">
				<?php $this->load->view('asesmen_standard/student/student_fill_the_blank', ['exam' => $exam]) ?>
			</div>

			<!-- Soal true or false -->
			<div id="student-true-or-false-container" class="h-100 d-none">
				<?php $this->load->view('asesmen_standard/student/student_true_or_false', ['exam' => $exam]) ?>
			</div>

			<!-- Soal Uraian -->
			<div id="student-essay-container" class="h-100 d-none">
				<?php $this->load->view('asesmen_standard/student/student_essay', ['exam' => $exam]) ?>
			</div>

			<!-- Soal Multiple Choice / Pilihan Ganda -->
			<div id="student-multiple-choice-container" class="h-100 d-none">
				<?php $this->load->view('asesmen_standard/student/student_multiple_choice', ['exam' => $exam]) ?>
			</div>

			<form name="frm-soal" class="h-100 w-100">

			</form>

		</section>

	</section>

	<?php $this->load->view('asesmen_standard/student/student_pairing') ?>
	<?php $this->load->view('asesmen_standard/student/student_dd') ?>

	<!-- Modal Logout Exam -->
	<div class="modal fade" id="logoutExamModal" tabindex="-1" aria-labelledby="logoutExamModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content rounded-4 p-3">
				<div class="modal-header border-0">
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>

				<div class="modal-body">
					<span class="badge bg-danger-subtle rounded-circle p-2 h1">
						<img src="<?= base_url('assets/images/icons/logout-circle-r-line.svg') ?>" alt="logout" style="width: 50px;">
					</span>

					<h3 class="mt-3 mb-3">Tinggalkan Ujian?</h3>
					<p style="color: #667085;">Ada soal yang belum selesai! Jawabanmu saat ini akan disimpan jika meninggalkan ujian.</p>
				</div>
				<div class="text-center">
					<button type="button" class="btn btn-light border m-2" onclick="logoutExam()">Tinggalkan</button>
					<!-- <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Batalkan</button> -->
				</div>
			</div>
		</div>
	</div>

	<!-- modal sukses mengumpulkan ujian -->
	<div class="modal fade" id="modalFinishExam" tabindex="-1" aria-labelledby="modalFinishExamLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content" style="width: 400px;" style="border-radius: 16px;">
				<div class="modal-body text-center p-4">
					<img src="<?= base_url('assets/images/icons/champion.svg') ?>" alt="finish-exam" style="width: 280px; height: 280px;">

					<p class="h5 mt-3">Selamat! Ujianmu telah selesai!!</p>
					<p class="mb-3" style="font-size: 12px; color: #616476;">Ujianmu telah berhasil dikirim, Kamu dapat memantau status ujian di halaman asesmen.</p>

					<a type="button" class="btn btn-lg btn-primary mt-4 w-100" onclick="kembaliKeAsesmen()" data-bs-dismiss="modal" style="font-size: 14px;">Kembali ke asesmen</a>
				</div>
			</div>
		</div>
	</div>

	<!-- modal image -->
	<div class="modal fade" id="modalImage" tabindex="-1" aria-labelledby="modalImageLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalImageLabel">Image Soal</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body text-center">
					<img class="rounded-3 m-auto" src="<?= base_url('assets/images/icons/image-empty.png') ?>" alt="image" style="max-width: 100%; max-height: 100%;">
				</div>
			</div>
		</div>
	</div>



	<script>
		const BASE_URL = '<?= base_url() ?>',
			ADMIN_URL = '<?= html_escape($this->config->item('admin_url')) ?>';

		const csrfName = document.querySelector('meta[name="csrf_name"]');
		const csrfToken = document.querySelector('meta[name="csrf_token"]');
	</script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

	<script src="<?= base_url('assets/node_modules/moment/moment.js') ?>"></script>
	<script src="<?= base_url('assets/js/jquery.min.js'); ?>"></script>
	<script src="<?= base_url('assets/themes/space/js/jquery.dataTables.min.js') ?>"></script>
	<script src="<?= base_url('assets/themes/space/js/dataTables.bootstrap5.min.js') ?>"></script>
	<script src="<?= base_url('assets/node_modules/sweetalert2/dist/sweetalert2.min.js') ?>"></script>

	<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/quill@2.0.0-rc.4/dist/quill.js"></script>
	<script src="<?= base_url('assets/node_modules/handlebars/dist/handlebars.min.js') ?>"></script>
	<script src="https://cdn.jsdelivr.net/npm/@tsparticles/confetti@3.0.3/tsparticles.confetti.bundle.min.js"></script>

	<script src="<?= base_url() ?>assets/js/_do_exercise_new.js" defer></script>

	<script>
		
	</script>
</body>

</html>
