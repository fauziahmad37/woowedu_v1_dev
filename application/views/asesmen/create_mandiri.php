<style>
	.left-side, .right-side{
		border: 1px solid rgba(212,209,209,0.6);
	}
	.card p {
		font-size: 14px;
	}
</style>

<section class="explore-section section-padding" id="section_2">

	<div class="container mt-3">
		<p>Lembar Asesmen > <strong>Hasilkan lembar asesmen</strong></p>
		<form name="frm-input" class="row mb-5">
			<div class="row">
				<div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 left-side">
					<form class="row mt-3 mb-2 mx-1" name="frm-filter">

						<div class="form-group mt-3 mb-3"> 
							<label for="a_title" class="form-label">Judul lembar Asesmen <span class="text-danger">*</span></label>
							<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
							<input type="hidden" name="student_id" value="<?=(isset($_SESSION['student_id'])) ? $_SESSION['student_id'] : ''?>">
							<input type="hidden" name="sekolah_id" value="<?=$sekolah_id?>">
							<input type="hidden" name="exam_id" value="<?=isset($exam['exam_id']) ? $exam['exam_id'] : ''?>">
							<input type="text" class="form-control" name="a_title" id="a_title" placeholder="Nama asesmen" value="<?=(isset($exam['title']) ? $exam['title'] : '')?>">
						</div>

						<div class="form-group mb-3"> 
							<label for="select-mapel" class="form-label">Mata Pelajaran <span class="text-danger">*</span></label>
							<select required class="form-select" name="select-mapel" id="select-mapel" aria-label="Pilih Matapelajaran">
								<option value="">-- Pilih Mata Pelajaran --</option>
								<?php foreach($mapels as $mapel): ?>
									<option value="<?=$mapel['subject_id']?>" <?=(isset($exam['subject_id']) && $exam['subject_id'] == $mapel['subject_id']) ? 'selected' : ''?> ><?=$mapel['subject_name']?></option>
								<?php endforeach ?>
							</select>
						</div>

						<!-- <div class="form-group mb-3">
							<label for="select-materi" class="form-label">Materi <span class="text-danger">*</span></label>
							<select class="form-select" name="select-materi" id="select-materi"></select>
						</div> -->

						<?php if(isset($_SESSION['teacher_id'])): ?>
						<div class="form-group mb-3"> 
							<label for="select-mapel" class="form-label">Pilih kelas <span class="text-danger">*</span></label>
							<select class="form-select" name="select-kelas" id="select-kelas" aria-label="Pilih Kelas">
								<?php foreach($classes as $class): ?>
									<option value="<?=$class['class_id']?>" <?=(isset($exam['class_id']) && $exam['class_id'] == $class['class_id']) ? 'selected' : ''?> ><?=$class['class_name']?></option>
								<?php endforeach ?>
							</select>
						</div>
						<?php endif ?>

						<?php if(isset($_SESSION['student_id'])): ?>
							<input type="hidden" name="class_id" value="<?=isset($_SESSION['class_id']) ? $_SESSION['class_id'] : '' ?>">
						<?php endif ?>


						<input type="hidden" name="teacher_id" value="<?=isset($teacher_id) ? $teacher_id : ''?>">
						
						<div class="mb-3">
							<label for="deskripsi" class="form-label">Deskripsi</label>
							<textarea class="form-control" name="deskripsi" id="deskripsi" rows="3"><?=(isset($exam['description'])) ? $exam['description'] : ''?></textarea>
						</div>
						
						<input type="hidden" name="select-category" id="select-category" value="2">
						<!-- <div class="form-group mb-3"> 
							<label for="select-category" class="form-label">Pilih Kategori <span class="text-danger">*</span></label>
							<select class="form-select" name="select-category" id="select-category" aria-label="Pilih Kategori">
								<?php // foreach($categories as $category): ?>
									<option value="<? // =$category['category_id']?>" <? // =(isset($exam['category_id']) && $exam['category_id'] == $category['category_id']) ? 'selected' : ''?>><? // =$category['category_name']?></option>
								<?php // endforeach ?>
							</select>
						</div> -->

						<!-- <div class="form-group mb-3"> 
							<label for="a_start" class="form-label">Waktu Mulai <span class="text-danger">*</span></label>
							<input type="datetime-local" class="form-control" min="<? // =date('Y-m-d H:i')?>" name="a_start" id="a_start" placeholder="waktu mulai" value="<?=isset($exam['start_date']) ? $exam['start_date'] : ''?>">
						</div>

						<div class="form-group mb-3"> 
							<label for="a_end" class="form-label">Waktu Berakhir <span class="text-danger">*</span></label>
							<input type="datetime-local" class="form-control" min="<? // =date('Y-m-d H:i')?>" name="a_end" id="a_end" placeholder="waktu berakhir" value="<?=isset($exam['end_date']) ? $exam['end_date'] : ''?>">
						</div> -->
						
						<!-- <div class="form-group mb-3"> 
							<label for="a_duration" class="form-label">Durasi Mengerjakan <span class="text-danger">*</span></label>
							<input type="number" class="form-control" name="a_duration" id="a_duration" placeholder="Durasi Mengerjakan (JAM)" value="<?//=isset($exam['duration']) ? $exam['duration'] : ''?>">
						</div> -->

					</form>
				</div>
				<div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 right-side">
					<div class="row mt-2">
						<div class="col">
							<div class="btn-group" role="group" aria-label="Basic mixed styles example">
								<button type="button" class="text-white btn btn-danger tambah-pertanyaan d-none" data-bs-toggle="modal" data-bs-target="#tambahPertanyaanModal">+ Pertanyaan</button>
								<!-- <button type="button" class="text-white btn btn-success tambah-bagian-baru">+ Bagian Baru</button> -->
								<button type="button" class="text-gray btn btn-warning simpan-draft d-none"><i class="fa fa-save"></i> Simpan Draft</button>
								<button type="button" class="text-white btn btn-success pratinjau d-none"><i class="fa fa-eye"></i> Pratinjau</button>
								<button type="button" class="text-white btn btn-primary publish"><i class="fa fa-arrow-up"></i> Publish</button>
							</div>
						</div>
					</div>

					<?php for($i=1; $i<=1; $i++): ?>
					<div class="testpaper-sectionContainer mt-2 p-4">
						<div class="card">
							<div class="card-header">
								Bagian <?=$i?>
							</div>

							<div class="card-body">

								<div class="row">
									<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
										<div class="form-group mb-3"> 
											<label for="a_title" class="form-label">Jenis pertanyaan <span class="text-danger">*</span></label>
											<select class="form-select" name="a_jenis_pertanyaan_<?=$i?>" id="a_jenis_pertanyaan_<?=$i?>">
												<option value="1">Pilihan Ganda</option>
											</select>
										</div>
									</div>

									<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
										<div class="form-group mb-3"> 
											<label for="a_jumlah_petanyaan_<?=$i?>" class="form-label">Jumlah pertanyaan <span class="text-danger">*</span></label>
											<input type="number" class="form-control" name="a_jumlah_petanyaan_<?=$i?>" id="a_jumlah_petanyaan_<?=$i?>" placeholder="Masukan jumlah">
										</div>
									</div>

									<div class="col text-end">
										<button type="button" class="btn btn-sm btn-primary text-white pilih-pertanyaan-<?=$i?>">Generate Pertanyaan</button>
									</div>
								</div>

								<div class="row mt-4">
									<div class="content-<?=$i?>">
			
									</div>
								</div>

							</div>
						</div>

					</div>
					<?php endfor ?>
					
				</div>
			</div>
		
		</form>
	</div>


</section>

<div id="jumlah-soal-modal" class="modal" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="pemberitahuanModalLabel">Pemberitahuan</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body text-center">
				<!-- Icon -->
				<i class="bi bi-exclamation-circle text-warning" style="font-size: 4rem;"></i>
				<p class="mt-3">Bank soal belum tersedia dengan jumlah yang diinginkan siswa.</p>
			</div>
		</div>
	</div>
</div>


<?php if(isset($_GET['edit']) && $_GET['edit'] == 1):?>
<script id="data-soal" type="application/json">
	<?=json_encode($soal, JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT)?>
</script>
<?php endif; ?>	

<!-- <script src="https://cdn.tiny.cloud/1/yj3hulphodusa2w55igxgbtlhapz0w10lja2gm88r8z0w195/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script> -->

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
