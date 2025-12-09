<!-- <link rel="stylesheet" href="<? //=base_url('assets/node_modules/datatables.net-dt/css/dataTables.dataTables.min.css')
									?>"> -->
<section class="explore-section section-padding" id="section_2">
	<div class="container">

		<div class="card mt-5 rounded-top-4">
			<h6 class="card-header bg-primary text-white py-3 rounded-top-4"><?= $task['title'] ?></h6>
			<input type="hidden" id="task_id" value="<?=$task['task_id']?>">

			<div class="card-body p-0">

				<div class="row mt-3 mx-2 waktu-tugas">
					<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">Waktu Mulai: <span class="fw-bold"><?= date('d M Y H:i', strtotime($task['available_date'])) ?></span> </div>
					<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12 text-end">Waktu Berakhir: <span class="bg-danger-subtle rounded text-danger fw-bold py-1 px-2"><?= date('d M Y H:i', strtotime($task['due_date'])) ?></span></div>
				</div>

				<span class="border border-bottom border-dark-subtle d-block mx-0 px-0 mt-4"></span>

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
						if (!empty($task['task_file'])) { ?>
							<a target="_blank" class="p-3 mb-3 badge text-bg-primary fs-6 text-decoration-none fw-normal text-primary" style="--bs-bg-opacity: .3;" href="<?= base_url('assets/files/teacher_task/' . $task['teacher_id'] . '/') . $task['task_file'] ?>" download="<?= strip_tags($task['title']) ?>"><i class="bi bi-download"></i> Download File</a>
						<?php } else { ?>
							<span class="badge text-bg-primary opacity-50 fs-6 text-white rounded rounded-4">Tidak Ada File Tugas</span>
						<?php }
						?>
					</div>
				</div>



				<span class="border border-bottom border-dark-subtle d-block mx-0 px-0 mt-4"></span>

				<div class="col">
					<a class="btn btn-outline-danger mx-3 my-4 delete-task"><i class="bi bi-trash"></i> Hapus Tugas</a>
					<a href="<?= base_url() ?>task/create/<?= $task['task_id'] ?>" class="btn btn-primary text-white"><i class="bi bi-pencil-square"></i> Edit Tugas</a>
				</div>
			</div>
		</div>

		<div class="card mt-5 rounded-top-4">
			<h6 class="card-header text-primary py-3 rounded-top-4 fw-bold">List Murid yang mengumpulkan</h6>
			<div class="card-body" style="overflow-x: auto;">
				<table class="table table-rounded" id="myTable">
					<thead class="bg-primary text-white">
						<tr>
							<th>No</th>
							<th>Nis</th>
							<th>Nama</th>
							<th>Tanggal Mengumpulkan</th>
							<th>File Jawaban</th>
							<th>Nilai</th>
							<th>Komentar</th>
							<th>Aksi</th>
						</tr>
					</thead>
					<tbody>
						<?php $i = 1; ?>
						<?php foreach ($data_siswa_kelas as $key => $val) :


						?>
							<tr>
								<td><?= $i ?></td>
								<td><?= $val['nis'] ?></td>
								<td><?= $val['student_name'] ?></td>
								<td><?= (isset($val['detail_jawaban']['task_submit'])) ? date('d M Y, H:i', strtotime($val['detail_jawaban']['task_submit'])) : '' ?></td>
								<td class="text-center">
									<?php if (isset($val['detail_jawaban']['task_submit']) && $val['detail_jawaban']['task_file']) : ?>
										<a href="<?= base_url('assets/files/student_task/' . $task['class_id'] . '/' . $val['detail_jawaban']['task_file']) ?>"><i class="bi bi-file-text-fill fs-5 text-primary"></i></a>
									<?php endif ?>
								</td>
								<td class="text-center"><?= (isset($val['detail_jawaban']['task_nilai'])) ? $val['detail_jawaban']['task_nilai'] : '' ?></td>
								<td class="text-center"><?= (isset($val['detail_jawaban']['task_comment_nilai'])) ? $val['detail_jawaban']['task_comment_nilai'] : '' ?></td>
								<td class="text-center">
									<!--<a class="mx-1" href="<? // =base_url('task/detail/'.$task['task_id'].'?ts_id='.$val['detail_jawaban']['ts_id'])
																?>"><i class="bi bi-pencil"></i></a>-->
									<?php if (!empty($val['detail_jawaban']['ts_id'])) { ?>
										<div class="btn-group btn-group-sm">
											<a class="btn btn-sm btn-primary text-white rounded-2 me-1" href="<?= base_url('task/detail/' . $task['task_id'] . '?ts_id=' . $val['detail_jawaban']['ts_id']) ?>"><i class="bi bi-eye-fill"></i></a>
											<a class="btn btn-sm btn-primary text-white rounded-2 input-nilai" data="<?= $val['detail_jawaban']['ts_id'] ?>"><i class="bi bi-pencil"></i></a>
										</div>
			
									<?php } ?>
								</td>
							</tr>
							<?php $i++ ?>
						<?php endforeach ?>
					</tbody>
				</table>
			</div>
		</div>


	</div>
</section>


<!-- Modal Create New -->
<div class="modal fade" id="taskcomment" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-primary text-white">
				<h1 class="modal-title fs-5" id="exampleModalLabel">Penilaian Tugas</h1>
				<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body p-4">
				<form name="form-add" id="form-add" class="d-flex flex-column">
					<input type="hidden" id="ts_id" />
					<input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
					<div class="mb-3">
						<label class="form-label">Nilai <span class="text-danger"><strong>*</strong></span></label>
						<input type="number" class="form-control form-control-sm border border-light-subtle" id="task_nilai" placeholder="Masukan Nilai"/>
					</div>
					<div class="mb-3">
						<label class="form-label">Komentar <span class="text-danger"><strong>*</strong></span></label>
						<textarea class="form-control form-control-sm border border-light-subtle" id="task_comment_nilai" cols="50" rows="4" required></textarea>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" id="save-nilai">Simpan</button>
			</div>
		</div>
	</div>
</div>
<!-- End Modal -->


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?= base_url('assets/js/_detail_tugas_guru.js') ?>"></script>

<script>
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
</script>
