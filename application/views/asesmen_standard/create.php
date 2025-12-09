<style>
	/* soal */
	.main-soal .card-left {
		height: 200px;
		background-color: rgba(255, 255, 255, 0.1);
		border-color: #FFFFFF;
	}
</style>

<section class="content">
	<h5 class="mt-5 mb-5" style="color: #281B93; font-size: 16px;">
		<a href="asesmen" style="color: #281B93; text-decoration: none;">
			<i class="fas fa-chevron-left"></i>
			Kembali ke lembar asesmen
		</a>
	</h5>

	<div class="p-3 rounded-3" style="background-color: #D4D1E9; color: #281B93;">
		<span class="rounded-circle me-2" style="background-color: #281B93; padding: 1px 7px; color: white; font-size: 10px;">i</span>
		<span style="font-size: 12px;">Harap perhatikan detail dalam pembuatan asesmen, pastikan data yang Bapak/Ibu Guru masukkan sudah sesuai</span>
	</div>

	<p class="my-4" style="font-size: 20px; font-weight: 600;">Buat lembar asesmen</p>
	<p class="my-4" style="font-size: 18px; font-weight: 600;">Informasi Asesmen</p>

	<!-- Select Mapel -->
	<div class="form-group mb-4">
		<label for="select-mapel" class="form-label mb-2">Mata Pelajaran <span class="text-danger">*</span></label>
		<select class="form-select" name="select-mapel" id="select-mapel" aria-label="Pilih Matapelajaran">
			<option value="">-- Pilih Mapel --</option>
			<?php foreach ($mapels as $mapel) : ?>
				<option value="<?= $mapel['subject_id'] ?>" <?= (isset($exam['subject_id']) && $exam['subject_id'] == $mapel['subject_id']) ? 'selected' : '' ?>><?= $mapel['subject_name'] ?></option>
			<?php endforeach ?>
		</select>
	</div>

	<!-- Select Kelas -->
	<?php if (isset($_SESSION['teacher_id'])) : ?>
		<div class="form-group mb-4">
			<label for="select-mapel" class="form-label mb-2">Kelas <span class="text-danger">*</span></label>
			<select class="form-select" name="select-kelas" id="select-kelas" aria-label="Pilih Kelas">
				<option value="">-- Pilih Kelas --</option>
				<?php foreach ($classes as $class) : ?>
					<option value="<?= $class['class_id'] ?>" <?= (isset($exam['class_id']) && $exam['class_id'] == $class['class_id']) ? 'selected' : '' ?>><?= $class['class_name'] ?></option>
				<?php endforeach ?>
			</select>
		</div>
	<?php endif ?>

	<!-- Select Kategori Ujian (PTS, UAS, HARIAN, DLL) -->
	<?php if (isset($_SESSION['teacher_id'])) : ?>
		<div class="form-group mb-4">
			<label for="select-category" class="form-label mb-2">Kategori Asesmen <span class="text-danger">*</span></label>
			<select class="form-select" name="select-category" id="select-category" aria-label="Pilih Kategori">
				<option value="">-- Pilih Kategori --</option>
				<?php foreach ($categories as $category) : ?>
					<option value="<?= $category['category_id'] ?>" <?= (isset($exam['category_id']) && $exam['category_id'] == $category['category_id']) ? 'selected' : '' ?>><?= $category['category_name'] ?></option>
				<?php endforeach ?>
			</select>
		</div>
	<?php endif ?>

	<!-- Judul Ujian -->
	<div class="form-group mb-4">
		<label for="a_title" class="form-label mb-2">Judul lembar Asesmen <span class="text-danger">*</span></label>
		<input type="hidden" name="sekolah_id" value="<?= $_SESSION['sekolah_id'] ?>">
		<input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
		<input type="hidden" name="teacher_id" value="<?= $teacher_id ?>">
		<input type="hidden" name="exam_id" value="<?= isset($exam['exam_id']) ? $exam['exam_id'] : '' ?>">
		<input type="checkbox" name="is_update" class="d-none" <?= ($is_update === TRUE ? 'checked' : trim('')) ?>>
		<input type="text" class="form-control" name="a_title" id="a_title" placeholder="Nama asesmen" value="<?= (isset($exam['title']) ? $exam['title'] : '') ?>">
	</div>

	<!-- Deskripsi Ujian -->
	<div class="mb-3">
		<label for="deskripsi" class="form-label mb-2">Deskripsi</label>
		<textarea class="form-control" name="deskripsi" id="deskripsi" rows="3"><?= (isset($exam['description'])) ? $exam['description'] : '' ?></textarea>
	</div>

	<!-- Waktu Mulai dan Waktu Berakhir -->
	<div class="row">
		<div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="form-group mb-3">
				<label for="a_start" class="form-label mb-2">Waktu Mulai <span class="text-danger">*</span></label>
				<input type="datetime-local" class="form-control" name="a_start" id="a_start" min="<?= date('Y-m-d H:i') ?>" placeholder="waktu mulai" value="<?= isset($exam['start_date']) ? $exam['start_date'] : '' ?>">
			</div>
		</div>
		<div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="form-group mb-3">
				<label for="a_end" class="form-label mb-2">Waktu Berakhir <span class="text-danger">*</span></label>
				<input type="datetime-local" class="form-control" name="a_end" id="a_end" placeholder="waktu berakhir" min="<?= date('Y-m-d H:i') ?>" value="<?= isset($exam['end_date']) ? $exam['end_date'] : '' ?>">
			</div>
		</div>
	</div>

	<!-- Durasi Ujian -->
	<div class="row">
		<div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="form-group mb-3">
				<label for="a_duration" class="form-label mb-2">Durasi Asesmen (menit) <span class="text-danger">*</span></label>
				<input type="number" class="form-control" name="a_duration" id="a_duration" placeholder="Durasi asesmen" value="<?= isset($exam['duration']) ? $exam['duration'] : '' ?>">
			</div>
		</div>
	</div>

	<p class="my-4 informasi-asesmen" style="font-size: 18px; font-weight: 600;">Informasi Asesmen</p>

	<p class="my-4 tambah-soal-asesmen d-none" style="font-size: 18px; font-weight: 600;">Tambah Soal Asesmen</p>

	<!-- Kontainer Soal2 Ujian -->
	<div id="add-soal-container">
		<!-- Kondisi Jika Edit -->

		<?php if (isset($exam['exam_id'])) : ?>
			<?php $this->load->view('asesmen_standard/question_edit', ['questions' => $questions]) ?>
		<?php endif ?>
	</div>


	<!-- Pilih Jenis Soal -->
	<div class="form-group mb-3">
		<label for="a_title" class="form-label">Jenis Soal <span class="text-danger">*</span></label>
		<select class="form-select" name="a_jenis_pertanyaan" id="a_jenis_pertanyaan">
			<option value="">-- Pilih Tipe Soal --</option>
			<option value="1">Pilihan Ganda</option>
			<option value="2">Uraian</option>
			<option value="3">Benar Atau Salah</option>
			<option value="4">Isi Yang Kosong</option>
			<option value="5">Menjodohkan</option>
			<option value="6">Seret Lepas</option>
		</select>
	</div>

	<!-- Input Jumlah Soal -->
	<div class="form-group mb-3">
		<label for="a_jumlah_petanyaan" class="form-label">Jumlah Soal <span class="text-danger">*</span></label>
		<input type="number" min="0" max="1000" class="form-control" name="a_jumlah_petanyaan" id="a_jumlah_petanyaan" placeholder="Masukan jumlah">
	</div>

	<!-- Button Tambah Soal -->
	<div class="text-end mb-5">
		<button id="create-soal" class="text-end p-2 border-1 rounded-3" style="background-color: #D4D1E9; color:#281B93; border-color:#281B93;">
			+ Buat Soal
		</button>
	</div>

	<!-- Button Simpan Draft dan Publish Asesmen -->
	<div class="text-end mb-4">
		<button id="save-draft" class="btn light me-2 border-1" style="border-color:#281B93; color:#281B93;">
			<i class="fa-solid fa-save me-1"></i>
			Simpan Draft
		</button>
		<button id="publish" class="btn btn-primary text-white">
			<i class="fa-solid fa-paper-plane me-1"></i>
			Publish Asesmen
		</button>
	</div>

</section>

<!-- modal option add question -->
<div class="modal fade" id="optionAddQuestionModal" tabindex="-1" aria-labelledby="optionAddQuestionModalLabel" aria-hidden="true">
	<div class="modal-dialog" style="text-align: -webkit-center;">

		<div class="modal-content border-0 rounded-4 shadow" style="width: fit-content;">

			<div class="modal-body">
				<div class="text-center" style="max-width: 300px;">
					<div class="bg-primary-subtle text-primary rounded-circle p-3 d-inline-block my-4" style="width: 50px; height: 50px;">
						<i class="fa-solid fa-file" style="font-size:20px;"></i>
					</div>
					<h5 class="modal-title" id="optionAddQuestionModalLabel">Silahkan tentukan opsi untuk menambahkan soal</h5>
				</div>
			</div>
			<div class="justify-content-center py-4">
				<button onclick="createBankSoal()" type="button" class="btn btn-light text-primary border-primary">Buat Soal Baru</button>
				<!-- <a href="<? //= base_url() 
								?>asesmen/add_question" target="_blank" type="button" class="btn btn-light text-primary border-primary">Buat Soal Baru</a> -->
				<button type="button" id="btn-question-bank-modal" class="btn btn-primary text-white" data-bs-toggle="modal" data-bs-target="#questionBankModal">Pakai Bank Soal</button>
			</div>
		</div>
	</div>
</div>
<!-- end modal option add soal -->

<!-- modal show question bank -->
<div class="modal fade" id="questionBankModal" tabindex="-1" aria-labelledby="questionBankModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">

		<div class="modal-content border-0 rounded-4 shadow">
			<div class="modal-header bg-primary text-white rounded-top-4">
				<h5 class="modal-title" id="questionBankModalLabel">Bank Soal</h5>
				<button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body">
				<!-- list question bank -->
				<table id="table-list-question-bank" class="table table-hover w-100">
					<thead>
						<tr>
							<th scope="col">ID</th>
							<th scope="col"></th>
							<th scope="col"></th>
							<th scope="col">Soal</th>
							<th scope="col">Jenis Soal</th>
							<th scope="col">Aksi</th>
						</tr>
					</thead>
					<tbody>

					</tbody>
				</table>
				<!-- end list question bank -->

			</div>

		</div>
	</div>
</div>
<!-- end modal show question bank -->

<!-- modal edit jumlah soal -->
<div class="modal fade" id="editJumlahSoalModal" tabindex="-1" aria-labelledby="editJumlahSoalModalLabel" aria-hidden="true">
	<div class="modal-dialog">

		<div class="modal-content border-0 rounded-4 shadow">
			<div class="modal-header bg-primary text-white rounded-top-4">
				<h5 class="modal-title" id="editJumlahSoalModalLabel">Edit Jumlah Soal</h5>
				<button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body">
				<div class="form-group mb-3">
					<label for="a_jumlah_petanyaan_new" class="form-label
					">Jumlah Soal <span class="text-danger">*</span></label>
					<input type="number" min="0" max="1000" class="form-control" name="a_jumlah_petanyaan_new" placeholder="Masukan jumlah">
				</div>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
				<button type="button" class="btn btn-primary" onclick="editJumlahSoal()">Simpan</button>
			</div>
		</div>
	</div>
</div>
<!-- end modal edit jumlah soal -->
<div class="soal-pilihan-ganda-container d-none">
	<?php $this->load->view('asesmen_standard/create_pilihan_ganda') ?>
</div>

<!-- Create Soal Fill The Blank -->
<div class="soal-fill-the-blank-container d-none">
	<?php $this->load->view('asesmen_standard/create_fill_the_blank') ?>
</div>

<!-- Create Soal True Or False -->
<div class="soal-true-or-false-container d-none">
	<?php $this->load->view('asesmen_standard/create_true_or_false') ?>
</div>

<!-- Modal Tinjau Soal -->
<?php $this->load->view('asesmen_standard/preview_question') ?>

<!-- Modal Add Pairing Question--->
<?php $this->load->view('asesmen_standard/create_pairing_question') ?>
<!-- Modal Add Drag And Question--->
<?php $this->load->view('asesmen_standard/create_dragdrop_question') ?>

<!-- urutan 1 load lib data table  -->
<script src="https://cdn.datatables.net/2.2.1/js/dataTables.min.js"></script>
