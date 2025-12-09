<style>
	@media screen and (max-width: 576px) {
		form[name="frm-filter"]{
			display: flex;
		}

		form[name="frm-filter"] div:nth-child(1) {
			width: 75%;
		}
	
		form[name="frm-filter"] div:nth-child(2) {
			width: 25%;
		}
	}

	@media screen and (max-width: 375px) {
		form[name="frm-filter"]{
			display: flex;
		}

		form[name="frm-filter"] div:nth-child(1) {
			width: 70%;
		}
	
		form[name="frm-filter"] div:nth-child(2) {
			width: 30%;
		}
	}

	@media screen and (max-width: 320px) {
		form[name="frm-filter"]{
			display: flex;
		}

		form[name="frm-filter"] div:nth-child(1) {
			width: 65%;
		}
	
		form[name="frm-filter"] div:nth-child(2) {
			width: 35%;
			padding-left: 0px;
			padding-right: 0px;
		}
	}
	
	@media screen and (max-width: 425px){
		#cari {
			float: right;
		}
	}
	
</style>

<section class="explore-section section-padding" id="section_2">
	
<div class="container mt-3">
	<h5 class="my-5">Materi Sekolah</h5>

	<form class="row mb-3" name="frm-filter">
		<input type="hidden" name="user_level" value="<?=$_SESSION['user_level']?>">

		<div class="col-lg-4 col-md-6 col-sm-12 mb-2">
            <select class="form-select" name="select-mapel" id="select-mapel" aria-label="Pilih Matapelajaran">
                
            </select>
		</div>

        <div class="col-lg-4 col-md-6 col-sm-12 mb-2">
            <button class="btn btn-primary text-white d-inline-block" id="cari" type="submit" ><i class="bi bi-search text-white"></i> Cari</button>
        </div>
		<input type="hidden" name="sekolah_id" value="<?=(isset($_SESSION['sekolah_id'])) ? $_SESSION['sekolah_id'] : ''?>">
       
    </form>

	<div class="row mb-5" style="overflow-x: auto;">
		
		<div class="row mb-2">
			<div class="col-md-8 col-lg-10"></div>
			<div class="col-md-4 col-lg-2 d-flex flex-nowrap justify-content-end">
				<?php
					$bisaliat = [1, 3, 10];
					$_level = intval($_SESSION['user_level']) ?? 0;

					if(in_array($_level, $bisaliat)):
				?>
				
				<?php endif;  ?>
			</div>
		</div>

		<div class="row mb-3 <?=$_SESSION['user_level'] !== 6 ? 'd-none' : ''?>">
			<div class="col-12 d-flex flex-row-reverse">
				<button class="btn btn-outline-primary rounded-pill border-2 fw-bold" id="create" data-bs-toggle="modal" data-bs-target="#modal-add"><i class="bi bi-upload"></i> Unggah Materi</button>
			</div>
		</div>

		<?php 
			if($datamodel != 'grid'):
				$this->load->view('mapel/table_view');
			endif; 
		?>
	</div>
</div>

</section>

<!-- Start Modal -->
	<section class="modal hide fade" id="modal-add">
	  <div class="modal-dialog modal-lg">
		<div class="modal-content border-0">
		  <div class="modal-header bg-primary">
			<h5 class="modal-title text-capitalize text-light text-shadow">Buat Materi Baru</h5>
			<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
	
		  </div>
		  <div class="modal-body">
			<form name="form-add" id="form-add" class="d-flex flex-column" >
				<div class="row">
					<div class="col-12 col-lg-12">
						<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
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
									<?php foreach($mapels as $mapel): ?>
										<option value="<?=$mapel['subject_id']?>"><?=$mapel['subject_name']?></option>
									<?php endforeach ?>
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
			<button type="button" class="btn text-white" style="background-color: gray;" data-bs-dismiss="modal">Keluar</button>
			<button type="button" class="btn btn-primary" id="save-subject">Simpan</button>
		  </div>
		</div>
	  </div>
	</section>
<!-- End Modal -->
