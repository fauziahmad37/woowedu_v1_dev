<style>
	.line {
		width: 100%;
		border-bottom: 1px solid rgba(0, 0, 0, 0.1);
	}

	p {
		font-size: 14px;
	}

	.fa-check {
		background-color: #28a745;
		color: #fff;
		text-align: center;
		border-radius: 50%;
		width: 20px;
		height: 20px;
		padding-top: 3px;
	}

	.fa-xmark {
		background-color: #dc3545;
		color: #fff;
		text-align: center;
		border-radius: 50%;
		width: 20px;
		height: 20px;
		padding-top: 3px;
	}

	.multiple-choice-answer .form-check-input {
		width: 24px !important;
		height: 24px !important;
	}

	.form-check-input:checked {
		background-color: #34C38F;
	}

	.input-jawaban {
		border-color: white;
		border-style: dashed;
		background-color: var(--bs-primary-300);
	}

</style>

<section class="explore-section section-padding" id="section_2">

	<a class="text-decoration-none d-inline-block mt-3 mb-3" href="<?= base_url('asesmen') ?>">
		<h6>
			< kembali ke lembar asesmen</h6>
	</a>

	<?php if (isset($_SESSION['teacher_id'])) : ?>
		<div class="alert alert-primary d-flex align-items-center" role="alert">
			<svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16" role="img" aria-label="Warning:">
				<path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
			</svg>
			<div style="font-size: 12px;">
				Skema penilaian hanya akan terlihat oleh Bapak/Ibu Guru dan tidak akan terlihat oleh siswa Bapak/Ibu Guru ketika mereka mengerjakan tugas yang diberikan.
			</div>
		</div>
	<?php endif ?>


	<div class="row">

		<nav class="d-flex justify-content-start">
			<div class="nav nav-tabs" id="nav-tab" role="tablist">
				<button class="nav-link active" id="nav-lembar-soal-tab" data-bs-toggle="tab" data-bs-target="#nav-lembar-soal" type="button" role="tab" aria-controls="nav-lembar-soal" aria-selected="true"><i class="fas fa-comments"></i> Lembar Soal</button>
				<button class="nav-link" id="nav-asesmen-khusus-tab" data-bs-toggle="tab" data-bs-target="#nav-skema-penilaian" type="button" role="tab" aria-controls="nav-skema-penilaian" aria-selected="false"><i class="fa-solid fa-file-circle-check"></i> Skema Penilaian</button>
				<button class="nav-link" id="nav-siswa-mengumpulkan-tab" data-bs-toggle="tab" data-bs-target="#nav-siswa-mengumpulkan" type="button" role="tab" aria-controls="nav-siswa-mengumpulkan" aria-selected="false"><i class="fas fa-user-friends"></i> Siswa Mengumpulkan</button>
			</div>
		</nav>

		<div class="tab-content mb-4" id="nav-tabContent" style="overflow-x: auto;">

			<div class="tab-pane fade show active" id="nav-lembar-soal" role="tabpanel" aria-labelledby="nav-lembar-soal-tab" tabindex="0">
				<?php $this->load->view('asesmen_standard/lembar_soal', ['exam_header' => $exam_header, 'soal_exam' => $soal_exam]) ?>
			</div>

			<div class="tab-pane fade p-3" id="nav-skema-penilaian" role="tabpanel" aria-labelledby="nav-asesmen-khusus-tab" tabindex="0">
				<?php $this->load->view('asesmen_standard/skema_penilaian', ['exam_header' => $exam_header, 'soal_exam' => $soal_exam]) ?>
			</div>
			
			<div class="tab-pane fade p-3" id="nav-siswa-mengumpulkan" role="tabpanel" aria-labelledby="nav-siswa-mengumpulkan-tab" tabindex="0">
				<?php $this->load->view('asesmen_standard/siswa_mengumpulkan', ['exam_header' => $exam_header, 'soal_exam' => $soal_exam]) ?>
			</div>

		</div>
	</div>
</section>

<!-- Modal Preview Soal -->
<?php $this->load->view('asesmen_standard/preview_question', ['title' => $exam_header['title']]) ?>

<script>
	// create swall alert
	$(document).ready(function() {
		<?php if (!empty($_SESSION['success']) && $_SESSION['success']['success'] == true) : ?>
			Swal.fire({
				icon: 'success',
				title: '<h4 class="text-success"></h4>',
				html: '<span class="text-success"><?= $_SESSION['success']['message'] ?></span>',
				timer: 5000
			});

		<?php endif; ?>

		<?php if (!empty($_SESSION['success']) && $_SESSION['success']['success'] == false) : ?>
			Swal.fire({
				icon: 'error',
				title: '<h4 class="text-danger"></h4>',
				html: '<span class="text-danger"><?= $_SESSION['success']['message'] ?></span>',
				timer: 5000
			});
		<?php endif; ?>
	});

	// download soal asesmen standard
	$('#download-asesmen-standard').on('click', function() {
		var exam_id = $('input[name="exam_id"]').val();
		window.location.href = '<?= base_url('asesmen_standard/download_soal_to_pdf/') ?>' + exam_id;
	});
</script>
