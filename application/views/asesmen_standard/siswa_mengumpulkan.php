<style>
	#a_name {
		background-image: url('assets/images/icons/search.svg');
		background-repeat: no-repeat;
		background-position: 95% 50%;
		padding-right: 30px;
	}

	.table thead th {
		background-color: var(--bs-primary);
		color: white;
	}
</style>

<div class="row mt-3">
	<div class="col"><img width="150" src="<?= base_url() ?>assets/themes/<?= isset($_SESSION['themes']) ? $_SESSION['themes'] : 'space' ?>/images/logowoowedu.png" alt=""></div>
	<div class="col text-end">
		<span style="font-size: 16px; font-weight: 600; display: block;"><?= $exam_header['title'] ?></span>
		<span style="font-size: 12px; display: block;"><?= $exam_header['subject_name'] ?> - <?= $exam_header['class_name'] ?></span>

		<button class="btn btn-lg bg-success text-white text-primary mt-3" id="download-student-collect-exam" style="font-size: 14px;">
			<i class="fas fa-download"></i>
			Unduh Sebagai PDF
		</button>
	</div>
</div>

<div class="row d-flex align-items-stretch my-5">
	<div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 mb-2 col-xs-12 d-flex align-items-stretch">
		<div class="card rounded-4 p-4 bg-primary w-100">
			<div class="card-icon text-center rounded-3" style="width: 36px; height: 36px; background-color: rgba(255, 255, 255, 0.2); padding-top: 10px;">
				<i class="fas fa-user-friends text-white text-primary h5"></i>
			</div>

			<p class="mt-3 mb-4" style="color: rgba(255, 255, 255, 0.75);">Total Siswa</p>

			<h3 class="mt-3 text-white fw-bold position-absolute" style="bottom: 0px;">
				<?= $total_siswa ?> Siswa
			</h3>
		</div>
	</div>

	<div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 mb-2 col-xs-12 d-flex align-items-stretch">
		<div class="card rounded-4 p-4 bg-primary w-100">
			<div class="card-icon text-center rounded-3" style="width: 36px; height: 36px; background-color: rgba(255, 255, 255, 0.2); padding-top: 10px;">
				<i class="fa-solid fa-person-circle-xmark text-white text-primary h5"></i>
			</div>

			<p class="mt-3 mb-4" style="color: rgba(255, 255, 255, 0.75);">Siswa Belum Mengerjakan</p>

			<h3 class="mt-3 text-white fw-bold position-absolute" style="bottom: 0px;">
				<?= $total_belum_mengerjakan ?> Siswa
			</h3>
		</div>
	</div>

	<div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 mb-2 col-xs-12 d-flex align-items-stretch">
		<div class="card rounded-4 p-4 bg-primary w-100">
			<div class="card-icon text-center rounded-3" style="width: 36px; height: 36px; background-color: rgba(255, 255, 255, 0.2); padding-top: 10px;">
				<i class="fa-solid fa-file-pen text-white text-primary h5"></i>
			</div>

			<p class="mt-3 mb-4" style="color: rgba(255, 255, 255, 0.75);">Siswa Menunggu Penilaian</p>

			<h3 class="mt-3 text-white fw-bold position-absolute" style="bottom: 0px;">
				<?= $total_menunggu_penilaian ?> Siswa
			</h3>
		</div>
	</div>

	<div class="col-xl-3 col-lg-4 col-md-4 col-sm-6 mb-2 col-xs-12 d-flex align-items-stretch">
		<div class="card rounded-4 p-4 bg-primary w-100">
			<div class="card-icon text-center rounded-3" style="width: 36px; height: 36px; background-color: rgba(255, 255, 255, 0.2); padding-top: 10px;">
				<i class="fa-solid fa-file-circle-check text-white text-primary h5"></i>
			</div>

			<p class="mt-3 mb-4" style="color: rgba(255, 255, 255, 0.75);">Siswa Sudah Dinilai</p>

			<h3 class="mt-3 text-white fw-bold position-absolute" style="bottom: 0px;">
				<?= $total_sudah_dinilai ?> Siswa
			</h3>
		</div>
	</div>
</div>

<form class="row mt-5 mb-2" name="frm-filter">

	<div class="col-lg-3 col-md-4 col-sm-6 mb-2">
		<div class="form-group mb-0">
			<input type="hidden" name="class_id" value="<?= $exam_header['class_id'] ?>">
			<input type="hidden" name="exam_id" value="<?= $exam_header['exam_id'] ?>">
			<input type="text" class="form-control" name="a_name" id="a_name" placeholder="Nama siswa">
		</div>
	</div>

	<div class="col-lg-3 col-md-4 col-sm-6 mb-2">
		<select class="form-select" name="a_status" id="a_status" aria-label="Pilih Status">
			<option value="">-- Status --</option>
			<option value="Belum Mengumpulkan">Belum Mengumpulkan</option>
			<option value="Menunggu Penilaian">Menunggu Penilaian</option>
			<option value="Sudah Dinilai">Sudah Dinilai</option>
		</select>
	</div>

	<div class="col-lg-3 col-md-4 col-sm-6 mb-2">
		<button id="cari" class="btn btn-primary text-white" type="submit"><i class="bi bi-search text-white"></i> Cari</button>
	</div>
</form>

<div class="container-student-table mt-5">
	<table id="student-table" class="table table-bordered table-hover w-100">
		<thead>
			<tr>
				<th>No</th>
				<th>NIS</th>
				<th>Nama Siswa</th>
				<th>Tgl Mengumpulkan</th>
				<th>Status</th>
				<th>Nilai</th>
				<th>Komentar</th>
				<th>Aksi</th>
			</tr>
		</thead>
		<tbody id="student-table-body">
		</tbody>
	</table>
</div>

<!-- Modal Beri Nilai -->
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

<script>
	$(document).ready(function() {
		

	});

	
</script>
