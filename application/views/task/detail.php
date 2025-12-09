<!-- QUILLJS CSS -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">


<style>

.swal2-popup .swal-wide-button {
	background-color: #281B93 !important;
	width: 100% !important;
	border: none !important;
	outline: none !important;
	box-shadow: none !important;
	color: #fff; 
	border-radius: 6px; 
}

	.icon-faild {
		font-size:40px; 
		color:#f27474; 
		margin-bottom:10px;
	}
	</style>
<section class="explore-section section-padding" id="section_2">
	<div class="container mt-5">

		<div class="card">
			<h6 class="card-header bg-primary text-white"><?= $task['title'] ?></h6>
			<div class="card-body p-0">

				<div class="row mt-3 mx-2 waktu-tugas">
					<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">Waktu Mulai: <span class="fw-bold"><?= date('d M Y H:i', strtotime($task['available_date'])) ?></span> </div>
					<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12 text-end">Waktu Berakhir: <span class="bg-danger-subtle rounded text-danger fw-bold py-1 px-2"><?= date('d M Y H:i', strtotime($task['due_date'])) ?></span></div>
				</div>

				<span class="mt-3 border border-bottom border-1 d-block"></span>

				<div class="card-body-content p-3">
					<p class="mb-1">Mata Pelajaran:</p>
					<p><b><?= $task['subject_name'] ?></b></p>

					<p class="mb-1">Nama Guru:</p>
					<p><b><?= $task['teacher_name'] ?></b></p>

					<p class="mb-1">Catatan:</p>
					<div class="fw-bold"><?= $task['note'] ?></div>

					<br>
					<p>File Soal Tugas:</p>
					<div class="container p-0">
						<?php
						if (!empty($task['task_file'])) {
							echo '<a class="p-3 mb-3 badge text-bg-primary fs-6 text-decoration-none fw-normal text-primary" style="--bs-bg-opacity: .3;" href="' . base_url('assets/files/teacher_task/' . $task['teacher_id'] . '/') . $task['task_file'] . '"><i class="bi bi-download"></i> Download File</a>';
						} else { ?>
							<span class="badge text-bg-primary opacity-50 fs-5 text-white rounded">Tidak Ada File Tugas</span>
						<?php }
						?>
					</div>

					<!-- Nilai -->
					<?php if (isset($task_student['ts_id'])) : ?>
						<div class="mt-3">
							<p class="mb-1">Nilai:</p>
							<p class="fw-bold"><?= ($task_student['task_nilai'] != null) ? (($task_student['task_nilai'] != 0) ? $task_student['task_nilai'] : 'Belum Dinilai') : 'Belum Dinilai' ?></p>
						</div>
					<?php else : ?>
						<div class="mt-3">
							<p class="mb-1">Nilai:</p>
							<p class="fw-bold">Belum Dikerjakan</p>
						</div>
					<?php endif; ?>

				</div>


			</div>
		</div>

		<div class="card my-5">
			<h5 class="card-header bg-primary text-white">Jawaban Tugas</h5>


			<div class="card-body">
				<form id="form-answer" action="<?= base_url('task/store_file') ?>" method="POST" enctype="multipart/form-data">
					<input type="hidden" id="is_due_date" value="<?= (strtotime($task['due_date']) < time()) ? 'false' : 'true'; ?>">
					<input type="hidden" name="task_id" value="<?= $task['task_id'] ?>">
					<input type="hidden" name="class_id" value="<?= $task['class_id'] ?>">
					<input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

					<p class="fw-bold">Tulis Jawabanmu disini</p>
					<!-- Create the editor container -->
					<div id="editor" class="" style="height: 120px;"><?= isset($task_student['task_note']) ? $task_student['task_note'] : '' ?></div>
					<textarea hidden name="task_note" id="task_note"></textarea>

					<div class="mb-3 mt-3 col-lg-4 col-md-4 col-sm-6 col-xs-12 <?= isset($task_student['ts_id']) ? 'd-none' : '' ?>">
						<label for="formFile" class="form-label fw-bold">Unggah jawaban kamu dibawah ini</label>
						<input type="file" class="form-control p-2" id="formFile" name="formFile">
					</div>

					<p class="text-danger fs-12">* Unggah file tugas anda disini, dengan maksimal Ukuran File 2MB, Jenis file: Jpg, Png, Pdf, Docx, Xlsx, MP4</p>

					<?php if (isset($task_student['ts_id']) && $task_student['task_file'] != null) : ?>
						<a class="btn btn-outline-primary border-2 mt-3" href="<?= base_url() . 'assets/files/student_task/' . $task['class_id'] . '/' . $task_student['task_file'] ?>"><i class="bi bi-download"></i> Download File</a>
					<?php endif ?>
				
					<?php 
						$kondisiMurid = ( time() > strtotime($task['available_date']) && time() < strtotime($task['due_date']) && $_SESSION['user_level'] == 4);
						// var_dump(date('Y-m-d H:i:s', time())); die;
					?>

					<?php if($kondisiMurid): ?>
						<button <?= isset($task_student['ts_id']) ? 'hidden' : '' ?> type="submit" class="btn btn-primary text-white mt-3">Simpan Jawaban</button>
					<?php endif ?>

				</form>

			</div>
		</div>

	</div>
</section>

<div class="modal fade" id="tugas-ebook">
	<div class="modal-dialog modal-fullscreen">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="m-0">TUGAS EBOOK</h6>
				<button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-6">

					</div>
					<div class="col-6 position-relative">
						<div class="position-absolute">
							<div id="ebook-container"></div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>

<!-- Include the Quill library -->
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
	let isDueDate = $('#is_due_date').val();
	console.log(isDueDate);
	var quill = new Quill('#editor', {
		theme: 'snow',
		placeholder: 'Tuliskan jawabanmu disini...'
	});

	$('#form-answer').submit(e => {

		$('textarea[name="task_note"]').val(quill.container.firstChild.innerHTML);

		// check if file or note is empty
		if (!$('input[name="formFile"]').val() && quill.container.firstChild.innerHTML == '<p><br></p>') {
			e.preventDefault();
			Swal.fire({
				icon: 'error',
				title: '<h4 class="text-danger"></h4>',
				html: '<span class="text-danger">File atau Catatan tidak boleh kosong</span>',
				timer: 5000
			});
			return;
		}
	});

	quill.enable(isDueDate);

	// alert message login
	<?php if (!empty($_SESSION['simpan'])) : ?>

		<?php if ($_SESSION['simpan']['success'] == true) { ?>
			Swal.fire({
				icon: 'success',
				title: '<h4 class="text-success"></h4>',
				html: '<span class="text-success"><?= $_SESSION['simpan']['message'] ?></span>',
				timer: 5000
			});
		<?php } else { ?>
			Swal.fire({
				icon: 'error',
				title: '<h4 class="text-danger"></h4>',
				html: '<span class="text-danger"><?= $_SESSION['simpan']['message'] ?></span>',
				timer: 5000
			});
		<?php } ?>

	<?php endif; ?>




	// untuk upload file Maks 2 MB 

document.getElementById('formFile').addEventListener('change', function () {
	const file = this.files[0];
	if (file) {
		const maxSize = 2 * 1024 * 1024; // 2MB
		if (file.size > maxSize) {
			Swal.fire({
				html: `
					<div class="icon-faild">&#10060;</div>
					<h2 style="margin:0; font-size:1.4em;">Ukuran File Terlalu Besar</h2>
					<p style="margin-top:8px;">Ukuran file melebihi 2MB. Silakan pilih file yang lebih kecil.</p>
				`,
				showCloseButton: false,
				showConfirmButton: true,
				confirmButtonText: 'Upload Ulang',
				customClass: {
					confirmButton: 'swal-wide-button'
				}
			});
			this.value = ""; // reset input
		}
	}
});

	// end upload file 2 MB
</script>
	
