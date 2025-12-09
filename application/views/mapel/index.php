<style>
	.show{ width: 100%; }
	.border-card-materi{
		border-style: solid;
		border-width: 2px;
		border-color: rgba(var(--bs-primary-rgb));
		border-radius: 12px;
	}
	.border-card-materi img {
		margin-left: 12px;
		margin-bottom: 12px;
		padding: 12 12;
		border-radius: 8px;
		height: 56px;
		width: 56px;
	}

	@media screen and (max-width: 576px) {
		form[name="frm-filter"]{
			display: flex;
		}

		form[name="frm-filter"] div:nth-child(1) {
			width: 65%;
		}
	
		form[name="frm-filter"] div:nth-child(3) {
			width: 35%;
		}
	}
</style>

<?php 
	$theme = isset($_SESSION['themes']) ? $_SESSION['themes'] : 'space';
?>

<section class="explore-section section-padding mt-4" id="section_2">
	
	<input type="hidden" name="sekolah_id" value="<?=$this->session->userdata('sekolah_id');?>">
	<input type="hidden" name="user_level" value="<?=$this->session->userdata('user_level');?>">

	<?php if(isset($_SESSION['student_id'])): ?>
		<input type="hidden" name="class_level_id" value="<?=isset($_SESSION['class_level_id']) ? $_SESSION['class_level_id'] : ''; ?>">
	<?php endif ?>

	<input type="hidden" name="class_id" value="<?=isset($_SESSION['class_id']) ? $_SESSION['class_id'] : ''?>">

	<?php if(isset($_SESSION['student_id'])): ?>
		<form class="row mb-4" name="frm-filter">
			
			<div class="col-lg-4 col-md-6 col-sm-12 mb-2">
				<select class="form-select" name="select-mapel" id="select-mapel" aria-label="Pilih Matapelajaran">
					
				</select>
			</div>


			<div class="col-lg-4 col-md-6 col-sm-12 mb-2 <?=($_SESSION['user_level'] == 4 || $_SESSION['user_level'] == 5)  ? ' d-none':''?>">
				<select class="form-select" name="select-teacher" id="select-teacher" aria-label="Pilih Guru">
					
				</select>
			</div>

			<div class="col-lg-4 col-md-6 col-sm-12 mb-2">
				<button class="btn btn-primary text-white" type="submit" ><i class="bi bi-search text-white"></i> Cari</button>
			</div>
		
		</form>
	<?php endif ?>

	<?php if(isset($_SESSION['teacher_id'])): ?>
		<div class="row mb-3">

			<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12 p-2 <?=($_SESSION['user_level'] == 6) ? 'd-none' : '' ?>">
				<div class="border-card-materi h-100">
					<a class="text-decoration-none" href="<?=base_url('materi/materi_saya')?>" style="height: 100%; cursor: pointer;">
						<div class="row p-3">
							<img class="border-card-materi" src="<?=base_url('assets/themes/'.$theme.'/icons/folder-icon.svg')?>" alt="folder-icon">
							
							<p style="font-size: 18px"><b>Materi Saya</b></h6>
							<p style="font-size: 12px;">Anda dapat mengunggah materi yang anda miliki, 
							seperti lampiran File maupun Link URL, dan bisa dibagikan kelas-kelas yang Anda ajar.
							</p>
							
						</div>
					</a>
				</div>
			</div>

			<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12 p-2">
				<div class="border-card-materi h-100">
					<a class="text-decoration-none" href="<?=base_url('materi/materi_sekolah')?>" style="height: 100%; cursor: pointer;">
						<div class="row p-3">
							<img class="border-card-materi" src="<?=base_url('assets/themes/'.$theme.'/icons/school-icon.svg')?>" alt="folder-icon">
							
							<p style="font-size: 18px"><b>Materi Sekolah</b></h6>
							<p style="font-size: 12px;">Anda dapat berbagi dan mengakses materi pendidikan, metode pengajaran, 
							dan sumber daya untuk meningkatkan pengajaran disekolah
							</p>
							
						</div>
					</a>
				</div>
			</div>

			<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12 p-2">
				<div class="border-card-materi h-100">
					<a class="text-decoration-none" href="<?=base_url('materi/tutorial')?>" style="height: 100%; cursor: pointer;">
						<div class="row p-3">
							<img class="border-card-materi" src="<?=base_url('assets/themes/'.$theme.'/icons/globe-icon.svg')?>" alt="folder-icon">
							
							<p style="font-size: 18px"><b>Tutorial</b></h6>
							<p style="font-size: 12px;">Anda dapat mengakses berbagai materi dan panduan dalam format file atau melalui tautan URL, 
							yang dirancang untuk mendukung kebutuhan pengajaran Anda.
							</p>
							
						</div>
					</a>
				</div>
			</div>
		</div>
	<?php endif ?>

	
	<div class="row mb-2 px-0 mx-0">
		<div class="col-md-6 col-lg-6"></div>
		
	</div>

	<div style="overflow-x: auto;">
		<?php 
			if($datamodel != 'grid'):
				$this->load->view('mapel/table_view');
			endif; 
		?>
	</div>
	


	<!-- Modal add -->
	<section class="modal hide fade" id="modal-add">
	  <div class="modal-dialog modal-lg">
		<div class="modal-content border-0">
		  <div class="modal-header bg-primary">
			<h5 class="modal-title text-capitalize text-light text-shadow">Buat Materi Baru</h5>
			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	
		  </div>
		  <div class="modal-body">
			<form name="form-add" id="form-add" class="d-flex flex-column" >
				<div class="row">
					<div class="col-12 col-lg-12">

						<input type="hidden" name="teacher_id" value="<?=isset($_SESSION['teacher_id']) ? $_SESSION['teacher_id'] : ''?>">
						 
						<div class="row align-items-top mb-3">
							<div class="col-4">
								<label class="m-0">Jenis File Materi <span class="text-danger"><strong>*</strong></span></label>
							</div>
							<div class="col-8 mb-3">
								<select class="form-select form-select-sm border border-1 p-1" name="a_jenis_materi" aria-label="Default select example">
									<option value="">Pilih jenis materi</option>
									<option value="file">File</option>
									<option value="link">Tautan</option>
								</select>
							</div>
							<div class="col-4">
								<label class="m-0">Mata Pelajaran <span class="text-danger"><strong>*</strong></span></label>
							</div>
							<div class="col-8 mb-3">
								<select class="form-select form-select-sm col-11" name="a_materi_subject" data-live-search="true" style="width: 100%;">
									<option value="">-- Pilih materi --</option>
								</select>
								<input type="hidden" name="a_materi_subject_text">
							</div> 									
							<div class="col-4">
								<label class="m-0">Nama / Judul Materi <span class="text-danger"><strong>*</strong></span></label>
							</div>
							<div class="col-8 mb-3">
								<input required type="text" class="form-control form-control-sm" name="a_materi_title" />
							</div>

							<div class="lampiran row pe-0 d-none">
								<div class="col-4">
									<label class="m-0">File Lampiran <span class="text-danger"><strong>*</strong></span></label>
								</div>
								<div class="col-8 mb-3 ps-2">
									<div class="input-group input-group-sm ms-2 me-0">
										<input type="file" class="form-control form-control-sm" id="videoFile" name="a_materi_video">
										<label class="form-label overflow-hidden" id="video-label" for="videoFile" data-browse="Unggah Video"></label>
									</div>
								</div>					
							</div>	
							
							<div class="link row pe-0 d-none">
								<div class="col-4">
									<label class="m-0">Tautan <span class="text-danger"><strong>*</strong></span></label>
								</div>
								<div class="col-8 mb-3 ps-3 pe-1">
									<input type="text" class="form-control form-control-sm" name="a_tautan" />
								</div>
							</div>					
						</div>
					</div>
					
				</div>
	
				<input type="hidden" name="a_id" />
				<input type="hidden" name="xsrf" />
			</form>
			<span class="w-100 d-flex flex-nogrow">
			  <!-- PRogress bar-->
				<div id="upload-progress" class="progress w-100 d-none">
				  <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
				</div>
			  <!-- end PRogress bar-->
			</span>
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
			<button type="button" class="btn btn-primary" id="save-subject">Simpan</button>
		  </div>
		</div>
	  </div>
	</section>
	<!-- end modal add-->
	
	<!-- Modal Show -->
	<div class="modal fade" id="modal-show" tabindex="-1">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header bg-purple">
					<h5 class="modal-title text-white" id="tema"></h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<h6 id="sub-tema" class="fs-5"></h6>
					<strong><span id="judul" class="fs-16"></span></strong>
					<p id="note" class="fs-14"></p>
					<div class="w-100 d-flex flex-nowrap justify-content-end align-items-center mt-3">
						<a class="btn btn-success text-white" id="file-link">
							<i class="bi bi-download"></i> Unduh File
						</a>
					</div>
					
				</div>
				
			</div>
		</div>
	</div>
	<!-- End Modal Show -->


	
</section>


<script>
	// $('#basic-usage').select2({
	// 	theme: "bootstrap-5",
	// 	width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
	// 	placeholder: $( this ).data( 'placeholder' ),
	// });
</script>
