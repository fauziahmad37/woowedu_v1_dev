<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

<style>
	.left-side{
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
			<div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 left-side py-1">
				

				<label class="form-label mb-1">Bank Soal <span class="text-danger">*</span></label>

					<div class="form-group mb-3"> 
						<div class="form-check">
							<input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1" value="standar" checked>
							<label class="form-check-label" for="flexRadioDefault1">
								Bank Soal Standar
							</label>
						</div>
						<div class="form-check">
							<input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2" value="sendiri" >
							<label class="form-check-label" for="flexRadioDefault2">
								Buat soal anda sendiri
							</label>
						</div>
					</div>
					

					<div class="form-group mb-3"> 
						<label for="a_title" class="form-label mb-1">Judul lembar Asesmen <span class="text-danger">*</span></label>
						<input type="hidden" name="sekolah_id" value="<?=$_SESSION['sekolah_id']?>">
						<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
						<input type="hidden" name="teacher_id" value="<?=$teacher_id?>">
						<input type="hidden" name="exam_id" value="<?=isset($exam['exam_id']) ? $exam['exam_id'] : ''?>">
						<input type="checkbox" name="is_update" class="d-none" <?= ($is_update === TRUE ? 'checked' : trim('')) ?>>
						<input type="text" class="form-control" name="a_title" id="a_title" placeholder="Nama asesmen" value="<?=(isset($exam['title']) ? $exam['title'] : '')?>">
					</div>

					<div class="form-group mb-3"> 
						<label for="select-mapel" class="form-label mb-1">Mata Pelajaran <span class="text-danger">*</span></label>
						<select class="form-select" name="select-mapel" id="select-mapel" aria-label="Pilih Matapelajaran">
							<option value="">-- Pilih Mata Pelajaran --</option>
							<?php foreach($mapels as $mapel): ?>
								<option value="<?=$mapel['subject_id']?>" <?=(isset($exam['subject_id']) && $exam['subject_id'] == $mapel['subject_id']) ? 'selected' : ''?> ><?=$mapel['subject_name']?></option>
							<?php endforeach ?>
						</select>
					</div>

					<?php if(isset($_SESSION['teacher_id'])): ?>
					<div class="form-group mb-3"> 
						<label for="select-mapel" class="form-label mb-1">Pilih kelas <span class="text-danger">*</span></label>
						<select class="form-select" name="select-kelas" id="select-kelas" aria-label="Pilih Kelas">
							<option value="">-- Pilih Kelas --</option>
							<?php foreach($classes as $class): ?>
								<option value="<?=$class['class_id']?>" <?=(isset($exam['class_id']) && $exam['class_id'] == $class['class_id']) ? 'selected' : ''?> ><?=$class['class_name']?></option>
							<?php endforeach ?>
						</select>
					</div>
					<?php endif ?>

					<div class="mb-3">
						<label for="deskripsi" class="form-label mb-1">Deskripsi</label>
						<textarea class="form-control" name="deskripsi" id="deskripsi" rows="3"><?=(isset($exam['description'])) ? $exam['description'] : ''?></textarea>
					</div>

					<?php if(isset($_SESSION['teacher_id'])): ?>
					<div class="form-group mb-3"> 
						<label for="select-category" class="form-label mb-1">Pilih Kategori <span class="text-danger">*</span></label>
						<select class="form-select" name="select-category" id="select-category" aria-label="Pilih Kategori">
							<option value="">-- Pilih Kategori Asesmen --</option>
							<?php foreach($categories as $category): ?>
								<option value="<?=$category['category_id']?>" <?=(isset($exam['category_id']) && $exam['category_id'] == $category['category_id']) ? 'selected' : ''?>><?=$category['category_name']?></option>
							<?php endforeach ?>
						</select>
					</div>
					<?php endif ?>

					<div class="row">
						<div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="form-group mb-3"> 
								<label for="a_start" class="form-label mb-1">Waktu Mulai <span class="text-danger">*</span></label>
								<input type="datetime-local" class="form-control" name="a_start" id="a_start" min="<?=date('Y-m-d H:i')?>"
									   placeholder="waktu mulai" value="<?=isset($exam['start_date']) ? $exam['start_date'] : ''?>">
							</div>
						</div>
						<div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="form-group mb-3"> 
								<label for="a_end" class="form-label mb-1">Waktu Berakhir <span class="text-danger">*</span></label>
								<input type="datetime-local" 
									   class="form-control" name="a_end" id="a_end" placeholder="waktu berakhir" min="<?=date('Y-m-d H:i')?>" value="<?=isset($exam['end_date']) ? $exam['end_date'] : ''?>">
							</div>
						</div>
					</div>
					
			</div>
			<div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 right-side">
				
				<?php for($i=1; $i<=2; $i++): ?>
				<div class="testpaper-sectionContainer mt-2 mb-3 <?= $i > 1 ? 'd-none' : trim('')?>">
					<div class="card">
						<div class="card-header">
							SOAL
						</div>

						<div class="card-body">

							<div class="row">
								<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
									<div class="form-group mb-3"> 
										<label for="a_title" class="form-label">Jenis pertanyaan <span class="text-danger">*</span></label>
										<select class="form-select" name="a_jenis_pertanyaan_<?=$i?>" id="a_jenis_pertanyaan_<?=$i?>">
											<option value="">-- Pilih Tipe Soal --</option>
											<option value="1">Pilihan Ganda</option>
											<option value="2">Uraian</option>
										</select>
									</div>
								</div>

								<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
									<div class="form-group mb-3"> 
										<label for="a_jumlah_petanyaan_<?=$i?>" class="form-label">Jumlah pertanyaan <span class="text-danger">*</span></label>
										<input type="number" min="0" max="1000" class="form-control" name="a_jumlah_petanyaan_<?=$i?>" id="a_jumlah_petanyaan_<?=$i?>" placeholder="Masukan jumlah">
									</div>
								</div>

								<div class="col text-end">
									<button type="button" class="btn btn-sm btn-primary text-white pilih-pertanyaan-<?=$i?>" >+ Pilih pertanyaan</button>
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
				
				<div class="row mt-3">
					<div class="col d-flex justify-content-end">
						<!-- <div class="btn-group" role="group" aria-label="Basic mixed styles example">
							 <button type="button" class="text-white btn btn-success tambah-bagian-baru">+ Bagian Baru</button> 
							
						</div> -->
						<!-- <button type="button" class="text-white btn btn-danger tambah-pertanyaan me-1 d-none" data-bs-toggle="modal" data-bs-target="#tambahPertanyaanModal">+ Pertanyaan</button> -->
						<a href="asesmen/add_question" target="_blank" class="text-white btn btn-danger tambah-pertanyaan me-1 d-none">+ Pertanyaan</a>
						<button type="button" class="text-gray btn btn-warning me-1 simpan-draft"><i class="fa fa-save"></i> Simpan Draft</button>
						<button type="button" class="text-white btn btn-success d-none me-1 pratinjau"><i class="fa fa-eye"></i> Pratinjau</button>
						<button type="button" class="text-white btn btn-primary publish"><i class="fa fa-arrow-up"></i> Publish</button>
					</div>
				</div>
			</div>
		</form>
	
	</div>


	<!-- Modal Tambah Soal --> 
	<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title fs-5" id="exampleModalLabel">Pilih soal</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body p-4">
					<div class="row">
						<div class="col">
							<table id="tabelPilihSoal" class="table w-100">
								<thead>
									<tr>
										<th>code</th>
										<th>tema</th>
										<th>sub tema</th>
										<th>pertanyaan</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
	
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- End Modal -->

	<!-- Modal Tambah Soal --> 
	<!-- <div class="modal fade" id="tambahPertanyaanModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-fullscreen">
			<div class="modal-content">
				<div class="modal-header bg-primary" data-bs-theme="dark">
					<h1 class="modal-title text-white fs-5" id="exampleModalLabel">Buat soal</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body p-4">
					<div class="row">
						<div class="col-12">
							<div class="card">

								<div class="card-body">
									<form name="form-add" id="form-add" class="d-flex flex-column">
										<div class="row">
											<div class="col-12 col-lg-4">
												<h4 class="mb-4 text-underline">KETERANGAN</h4>
												<div class="row align-items-top mb-3">
													<div class="col-4">
														<label class="m-0">Kode Soal <span class="text-danger"><strong>*</strong></span></label>
													</div>
													<div class="col-8 mb-3">
														<input type="text" class="form-control form-control-sm" name="a_soal_code" required/>
													</div>
													// <div class="col-4">
													//	<label class="m-0">No. Urut <span class="text-danger"><strong>*</strong></span></label>
													//</div>
													//<div class="col-8 mb-3">
													//	<input type="number" class="form-control form-control-sm" name="a_soal_no" min="1" required/>
													//</div>
													<div class="col-4 pr-0">
														<label class="m-0">Mata Pelajaran <span class="text-danger"><strong>*</strong></span></label>
													</div>
													<div class="col-8 mb-3">
														<select class="form-select form-select-sm col-10" name="a_soal_subject" required>

														</select>
													</div>

													<div class="col-4">
														<label class="m-0">Jenis Soal <span class="text-danger"><strong>*</strong></span></label>
													</div>
													<div class="col-8 mb-3">
														<select class="form-select form-select-sm col-10" name="a_soal_type" required>
															<option value="0">-- Pilih Jenis Soal --</option>
															<option value="1">Pilihan Ganda</option>
															<option value="2">Essay</option>
															//<option value="3">BENAR/SALAH</option>
														</select>
													</div> 
													<div class="col-4">
														<label class="m-0">Jawaban <span class="text-danger"><strong>*</strong></span></label>
													</div>
													<div class="col-8 mb-3">
														<textarea class="form-control form-control-sm" name="a_soal_answer" rows="8" required></textarea>
														<small>Untuk jawaban pilihan ganda gunakan kunci (contoh: a, b)</small>
													</div>
													<div class="col-4">
														<label class="m-0">File pendukung</label>
													</div>
													<div class="col-8 mb-3">
														<div class="input-group mt-2">
															<input type="file" class="form-control form-control-sm" id="a_soal_file" name="a_soal_file">
															
														</div>
														<small>Upload file hanya support extensi video/mp4, image/png, image/jpeg, image/jpg, image/webp</small>
													</div>
												</div>
										
											</div>

											<div class="col">
												<h4 class="mb-4 text-underline">SOAL</h4>
												<div class="row">
													<div class="col-12">
														<div class="d-flex flex-column">
															<label for="detail-soal">Deskripsi Soal <span class="text-danger"><strong>*</strong></span</label>
															<div id="editor" class="form-control mb-3" style="height: 250px"></div>
															<textarea hidden name="a_soal_detail" class="form-control w-100" id="detail-soal" rows="10"></textarea>
														</div>
													</div>
												</div>
										
												<div class="row d-none" id="mc">
													<div class="col-12">
														<h4>Jawaban Pilihan Ganda</h4>
														<table id="table-choices" class="table table-sm w-100">
															<thead>
																<tr>
																<th>Pilihan</th>
																<th>Teks</th>
																<th>File</th>
																</tr>
															</thead>
															<tbody>

															</tbody>
														</table>
													</div>
												</div>
											</div>
										</div>
								
										<span class="w-100 d-flex flex-nogrow">
											// PRogress bar
											<div class="progress w-100 mt-2 d-none" id="import-progress-1">
												<div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
											</div>
											// end PRogress bar
										</span>
										<input type="hidden" name="a_id" />
										<input type="hidden" name="xsrf" />
										<span class="mt-3 w-100 d-flex flex-nogrow flex-nowrap justify-content-end">
											<input type="reset" class="btn btn-secondary" value="Ulangi">
											<input type="submit" class="btn btn-primary ms-2" value="Simpan">
										</span>
									</form>

								</div>

							</div>

						</div>

					</div>
				</div>
				
			</div>
		</div>
		// End Modal
	</div>
				-->

	<!-- Modal View uestion -->
	<div class="modal fade" tabindex="-1" id="mdl-view-soal">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header bg-primary" data-bs-theme="dark">
					<h5 class="text-uppercase mb-0 text-light">pratinjau soal</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-12" id="view-soal">

						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
				</div>
			</div>
		</div>
	</div>
</section>

<?php if(isset($_GET['edit']) && $_GET['edit'] == 1):?>
<script id="data-soal" type="application/json">
	<?=json_encode($soal, JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT)?>
</script>
<?php endif; ?>	

<!-- <script src="https://cdn.tiny.cloud/1/yj3hulphodusa2w55igxgbtlhapz0w10lja2gm88r8z0w195/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script> -->
<!-- Include the Quill library -->
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

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
