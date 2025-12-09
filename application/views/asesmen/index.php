<style>
	.tooltiptext {
		font-size: 12px;
		visibility: hidden;
		width: 120px;
		background-color: black;
		color: #fff;
		text-align: center;
		border-radius: 6px;
		padding: 5px 0;

		/* Position the tooltip */
		position: absolute;
		margin-top: 35px;
		z-index: 1;
	}
	
	#s_title {
		background-image: url('assets/images/icons/search.svg');
		background-repeat: no-repeat;
		background-position: 95% 50%;
		padding-right: 30px;
	}

	p.card-text {
		font-size: 14px;
	}

	.card {
		cursor: pointer;
	}

	.card:hover {
		background-color: rgba(181, 244, 252, 0.32);
		transform: scale(1.03);
	}

	

	button:hover .tooltiptext {
		visibility: visible;
	}

	button.nav-link.active#nav-asesmen-khusus-tab,
	button.nav-link.active#nav-asesmen-standar-tab {
		border-bottom-width: 2px !important;
		border-left-width: 0px;
		border-top-width: 0px;
		border-right-width: 0px;
		border-color: gray;
	}

	.nav-tabs button.nav-link:hover {
		border-color: #fff;
	}
</style>

<section class="explore-section section-padding" id="section_2">

	<!-- hide when display sm -->
	<div class="container mt-3 d-none d-sm-block">
		<h5>Lembar Asesmen</h5>

		<nav class="d-flex p-0 mt-5 ms-3">
			<div class="nav nav-tabs" id="nav-tab" role="tablist">
				<button class="nav-link active" id="nav-asesmen-standar-tab" data-bs-toggle="tab" data-bs-target="#nav-sesi" type="button" role="tab" aria-controls="nav-sesi" aria-selected="true"><i class="fa-regular fa-calendar-days h6"></i>
					Lembar Asesmen Standar 
					<i class="bi bi-info-circle-fill ms-2">
						<span class="tooltiptext">Lembar Asesmen yang di buat oleh guru untuk tiap-tiap kelas yang di ajar</span>
					</i>
				</button>
				<button class="nav-link" id="nav-asesmen-khusus-tab" data-bs-toggle="tab" data-bs-target="#nav-materi-guru" type="button" role="tab" aria-controls="nav-materi-guru" aria-selected="false"><i class="fa-solid fa-chalkboard-user h6"></i> 
					Lembar Asesmen Khusus
					<i class="bi bi-info-circle-fill ms-2">
						<span class="tooltiptext">Lembar Asesmen yang di buat oleh murid sebagai latihan</span>
					</i>
				</button>
			</div>
		</nav>

		<form class="row mt-5 mb-2 mx-1" name="frm-filter">

			<div class="col-lg-3 col-md-4 col-sm-6 mb-2">
				<div class="form-group mb-0">
					<input type="hidden" name="teacher_id" value="<?= $teacher_id ?>">
					<input type="hidden" name="class_id" value="<?= $class_id ?>">
					<input type="text" class="form-control" name="s_title" id="s_title" placeholder="Nama asesmen">
				</div>
			</div>

			<div class="col-lg-3 col-md-4 col-sm-6 mb-2">
				<select class="form-select" name="select-mapel" id="select-mapel" aria-label="Pilih Matapelajaran">
					<option value="">-- pilih mapel --</option>
					<?php foreach ($mapels as $mapel) : ?>
						<option value="<?= $mapel['subject_id'] ?>"><?= $mapel['subject_name'] ?></option>
					<?php endforeach ?>
				</select>
			</div>

			<?php if (isset($_SESSION['teacher_id'])) : ?>
				<div class="col-lg-3 col-md-4 col-sm-6 mb-2">
					<select class="form-select" name="select-kelas" id="select-kelas" aria-label="Pilih Kelas">
						<option value="">-- pilih kelas --</option>
						<?php foreach ($classes as $class) : ?>
							<option value="<?= $class['class_id'] ?>"><?= $class['class_name'] ?></option>
						<?php endforeach ?>
					</select>
				</div>
			<?php endif ?>

			<div class="col-lg-3 col-md-4 col-sm-6 mb-2">
				<button id="cari" class="btn btn-primary text-white" type="submit"><i class="bi bi-search text-white"></i> Cari</button>
			</div>
		</form>



		<!-- lembar asesmen -->
		<div class="container p-2">
			<div class="row">

				<?php if (isset($_SESSION['student_id'])) : ?>
					<input type="hidden" name="student_id" value="<?= $_SESSION['student_id'] ?>">
				<?php endif ?>


				<div class="row mb-3">

				</div>


				<div class="tab-content mb-4" id="nav-tabContent" style="overflow-x: auto;">

					<div class="tab-pane fade show active" id="nav-sesi" role="tabpanel" aria-labelledby="nav-asesmen-standar-tab" tabindex="0">

						<?php if (isset($_SESSION['teacher_id'])) : ?>
							<div class="col-12 d-flex flex-row-reverse mb-3">
								<a href="<?= base_url() . 'asesmen_standard/create' ?>" class="btn btn-light btn-outline-primary border-2 rounded-pill fw-semibold" id="create">+ Buat Asesmen</a>
							</div>
						<?php endif ?>

						<table class="table table-rounded align-middle" id="table-asesmen-standar" style="width: 100%;">
							<thead class="bg-primary text-white">
								<tr>
									<th>ID</th>
									<th>Nama Asesmen</th>
									<th>Nama Matapelajaran</th>
									<th>Kelas</th>
									<th>Waktu Mulai</th>
									<th>Waktu Berakhir</th>
									<th>Tanggal Pengerjaan</th>
									<th>Status</th>
									<th>Total nilai</th>
									<th>Status</th>
									<th>Aksi</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>

					<div class="tab-pane fade p-3" id="nav-materi-guru" role="tabpanel" aria-labelledby="nav-asesmen-khusus-tab" tabindex="0">
						<?php if (isset($_SESSION['student_id'])) : ?>
							<div class="col-12 d-flex flex-row-reverse">
								<a href="<?= base_url() . 'asesmen/create_mandiri' ?>" class="btn btn-clear border border-primary border-2 rounded-pill text-primary mb-3" id="create">+ Buat Asesmen</a>
							</div>
						<?php endif ?>

						<table class="table-rounded" id="table-asesmen-khusus" style="width: 100%;">
							<thead class="bg-primary text-white">
								<tr>
									<th>Exam ID</th>
									<th>ID</th>
									<th>Nama Asesmen</th>
									<th>Nama Matapelajaran</th>
									<th>Nama Murid</th>
									<th>Kelas</th>
									<th>Dibuat pada</th>
									<th>Tanggal Pengerjaan</th>
									<th>Aksi</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>


				</div>
			</div>
		</div>
	</div>

	<!-- show when display sm -->
	<div class="container mt-3 d-block d-sm-none text-center">
		<h5 class="text-start mb-5">Asesmen</h5>
		<img class="mt-5" src="assets/images/dont-access-asesmen.svg" alt="" width="200">
		<p class="mt-3 fw-bold">Halaman ini tidak dapat diakses dalam tampilan mobile, Silahkan buka dalam tampilan Laptop</p>
	</div>


	<!-- Modal Create New -->
	<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title fs-5" id="exampleModalLabel">Buat Lembar Asesmen Baru</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body p-4">
					<div class="row">

						<?php if (isset($_SESSION['teacher_id'])) : ?>
							<div class="col">
								<a href="<?= base_url() . 'asesmen/create_standar' ?>">
									<div class="card h-100">
										<div class="card-body">
											<h6 class="card-title"><i class="fa fa-note-sticky"></i>Lembar Asesmen Standar</h6>
											<p class="card-text">Melalui pilihan ini, Bapak/Ibu dapat membuat Lembar Asesmen dengan berbagai macam bentuk soal.
												Kunci jawaban untuk Lembar Asesmen yang dihasilkan juga akan tersedia.</p>
										</div>
									</div>
								</a>
							</div>
						<?php endif ?>

						<?php if (isset($_SESSION['student_id'])) : ?>
							<div class="col">
								<a href="<?= base_url() . 'asesmen/create_mandiri' ?>">
									<div class="card h-100">
										<div class="card-body">
											<h6 class="card-title"><i class="fa fa-note-sticky"></i>Lembar Asesmen Khusus</h6>

											<?php if (isset($_SESSION['teacher_id'])) : ?>
												<p class="card-text">Melalui pilihan ini Bapak/Ibu dapat membuat Lembar Asesmen khusus yang dapat Bapak/Ibu kirim sebagai tugas kelas.
													Nilai tugas siswa akan langsung dilaporkan kepada Bapak/Ibu setelah siswa selesai mengerjakan tugas.</p>
											<?php endif ?>

											<?php if (isset($_SESSION['student_id'])) : ?>
												<p class="card-text">Melalui pilihan ini, kamu dapat membuat Lembar Asesmen mandiri dan nilainya akan
													langsung dihitung secara otomatis setelah Lembar Asesmen selesai dikerjakan.</p>
											<?php endif ?>
										</div>
									</div>
								</a>
							</div>
						<?php endif ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- End Modal -->

	<!-- Modal Gandakan Asesmen -->
	<div class="modal fade" id="gandakanAsesmenModal" tabindex="-1" aria-labelledby="gandakanAsesmenModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title fs-5" id="gandakanAsesmenModalLabel">Gandakan lembar asesmen?</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body p-4">
					<span>
						Tindakan ini akan menggandakan lembar asesmen Anda. File duplikat akan ditempatkan dalam mode draf,
						setelah itu Anda melakukan tindakan edit.
						Tindakan duplikasi akan diperhitungkan dalam penggunaan salah satu kredit lembar asesmen Anda.
						Anda yakin ingin menggandakan lembar asesmen ini?
					</span>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary text-white" data-bs-dismiss="modal">Membatalkan</button>
					<button type="button" class="btn btn-danger text-white submit-gandakan-asesmen">Ya, Gandakan lembar asesmen</button>
				</div>
			</div>
		</div>
	</div>
	<!-- End Modal -->

</section>

<div class="modal fade" id="rulesDialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-primary text-white">
				<h4 class="mb-0">TATA TERTIB UJIAN</h4>
			</div>
			<div class="modal-body">
				<div class="alert alert-primary d-flex align-items-center d-none" role="alert">
					<i class="bi bi-info-circle-fill text-primary"></i>
					<div class="ms-2">An example alert with an icon</div>
				</div>
				<ul>
					<li class="mb-2">Pastikan perangkat (komputer/laptop/tablet) yang akan digunakan memiliki koneksi internet yang stabil.</li>
					<li class="mb-2">Pastikan perangkat sudah terhubung ke sumber daya listrik atau baterai terisi penuh.</li>
					<li class="mb-2">Pastikan lingkungan sekitar tenang dan kondusif untuk mengikuti ujian.</li>
					<li class="mb-2">Timer ujian akan dimulai saat peserta memasuki halaman ujian dan akan terus berjalan hingga waktu habis atau peserta menyelesaikan ujian dan menekan tombol "Kumpulkan".</li>
					<li class="mb-2">Selama ujian berlangsung, peserta tidak diperkenankan untuk membuka atau berpindah ke tab lain pada browser. Jika terdeteksi melakukan pindah tab dapat menyebabkan akan dinyatakan telah mengumpulkan secara otomatis.</li>
				</ul>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batalkan</button>
				<a role="button" id="btnStartExam" class="btn btn-primary" href="javascript:void(0)">Mulai</a>
			</div>
		</div>
	</div>
</div>

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
</script>
