<style>
	.line{
		width: 100%;
		border-bottom: 1px solid rgba(0, 0, 0, 0.1);
	}

	p {
		font-size: 14px;
	}

</style>

<section class="explore-section section-padding" id="section_2">

	<div class="container mt-3">
		<a href="<?=base_url('asesmen')?>"><h6>< kembali ke lembar asesmen</h6></a>
		
		<div class="container p-0 border rounded mt-2">
			<p class="m-3" style="font-size: 14px;">List murid yang telah mengerjakan soal:</p>
			<p class="m-3 fw-bold">Total Murid: <?=$total_murid?></p>
			<p class="m-3 fw-bold">Total Mengerjakan: <?=$total_mengerjakan?></p>
			<div class="line"></div>
		
			<input type="hidden" value="<?=$exam_id?>" name="exam_id">

			<div class="row m-3">
				<table id="table-exam-student" class="table table-rounded" style="width: 100%;">
					<thead class="bg-primary text-white">
						<tr>
							<th>ES_ID</th>
							<th>NIS</th>
							<th>Nama</th>
							<th>Tanggal Mengumpulkan</th>
							<th>Nilai</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>

					</tbody>
				</table>
			</div>
			
		</div>

	</div>

	<!-- Modal Tambah Soal --> 
	<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title fs-5" id="exampleModalLabel">Masukan Nilai</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body p-4">
					<div class="row">
						<div class="form-group">
							<label for="score">Masukan Nilai Siswa</label>
							<input type="hidden" name="exam_student_id">
							<input type="text" name="score" class="form-control">
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary text-white" data-bs-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary text-white save-score">Save</button>
				</div>
			</div>
		</div>
	</div>
	<!-- End Modal -->

</section>

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
