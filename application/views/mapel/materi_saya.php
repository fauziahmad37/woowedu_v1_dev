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
	
	@media screen and (max-width: 425px){
		#cari {
			float: right;
		}

		
	}

	.maximum-file {
    color:#f46a6a; 
    font-size:10px;
}

  .swal2-popup .swal-wide-button {
	background-color:#281B93 !important;
        width: 100% !important;
    }
	.icon-faild {
		font-size:40px; 
		color:#f27474; 
		margin-bottom:10px;
	}
</style>

<section class="explore-section section-padding" id="section_2">

	<input type="hidden" name="teacher_id" value="<?=$_SESSION['teacher_id']?>">
	
		<h5 class="mt-4"><b>Materi  Saya</b></h5>

		<form class="row mt-5 mb-5" name="frm-filter">
			<div class="col-lg-4 col-md-6 col-sm-12 mb-2">
				<select class="form-select" name="select-mapel" id="select-mapel" aria-label="Pilih Matapelajaran">
					<option value="">-- Semua materi --</option>
					<?php foreach($mapels as $mapel): ?>
						<option value="<?=$mapel['subject_id']?>"><?=$mapel['subject_name']?></option>
					<?php endforeach ?>
				</select>
			</div>
			<div class="col-lg-4 col-md-6 col-sm-12 mb-2">
				<button id="cari" class="btn btn-primary text-white" type="submit" ><i class="bi bi-search text-white"></i> Cari</button>
			</div>
		</form>

		<div class="row mb-3">
			<div class="col-12 d-flex flex-row-reverse">
				<button class="btn btn-outline-primary rounded-pill border-2 fw-bold" id="create" data-bs-toggle="modal" data-bs-target="#modal-add"><i class="bi bi-upload"></i> Unggah Materi</button>
			</div>
		</div>

		<div class="row mb-5" style="overflow-x: auto;">
			<table class="table table-rounded" id="myTable">
				<thead class="bg-primary text-white">
					<tr>
						<th>ID</th>
						<th>Nama Mapel</th>
						<th>Nama Materi</th>
						<th>Terakhir di update</th>
						<th>Ukuran</th>
						<th>File / Tautan</th>
						<th>Tindakan</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>

	

	<!-- Modal Create New --> 
	<!-- <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title fs-5" id="exampleModalLabel">Buat Materi Baru</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body p-4">
					<form name="form-add" method="POST" action="materi/store_materi_saya" enctype="multipart/form-data">
							
						<div class="col-4 mb-3">
							<label class="m-0">Jenis File Materi <span class="text-danger"><strong>*</strong></span></label>
						</div>
						<div class="col-12 mb-3">
							<select required class="form-select form-select-sm border border-1 p-1" name="a_jenis_materi" aria-label="Default select example">
								<option value="">Pilih jenis materi</option>
								<option value="file">File</option>
								<option value="link">Tautan</option>
							</select>
						</div>
						
						<div class="mb-3">
							<label for="subject_id" class="form-label">Mapel <span class="text-danger"><strong>*</strong></span></label>
							<select class="form-select" name="subject_id" id="subject_id" aria-label="Pilih Matapelajaran">
								<?php // foreach($mapels as $mapel): ?>
									<option value="<?//=$mapel['subject_id']?>"><?//=$mapel['subject_name']?></option>
								<?php // endforeach ?>
							</select>
						</div>
						<div class="mb-3">
							<input type="hidden" name="materi_id" value="">
							<label for="input_materi" class="form-label">Nama / Judul Materi <span class="text-danger"><strong>*</strong></span></label>
							<input required type="text" class="form-control" id="input_materi" name="input_materi">
						</div>
						
						 <div class="mb-3">
							<label for="input_file" class="form-label">Lampiran *</label>
							<input type="file" class="form-control" id="input_file" name="input_file">
							<div id="emailHelp" class="form-text">Max ukuran file: 100 MB</div>
						</div>

						<div class="lampiran row pe-0 d-none">
							<div class="col-4">
								<label class="m-0">File Lampiran <span class="text-danger"><strong>*</strong></span></label>
							</div>
							<div class="col-12 mb-3 ps-2">
								<div class="input-group input-group-sm ms-2">
									<input type="file" class="form-control form-control-sm" id="input_file" name="input_file">
									<label class="form-label overflow-hidden" id="video-label" for="videoFile" data-browse="Unggah Video"></label>
								</div>
							</div>					
						</div>	
						
						<div class="link row pe-0 d-none">
							<div class="col-4">
								<label class="m-0">Tautan <span class="text-danger"><strong>*</strong></span></label>
							</div>
							<div class="col-12 mb-3 ps-3 pe-1">
								<input type="text" class="form-control form-control-sm" name="a_tautan" />
							</div>
						</div>

						<div>
							<button type="submit" class="btn btn-primary btn-sm text-white">Submit</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div> -->

	<section class="modal hide fade" id="modal-add">
	  <div class="modal-dialog modal-md">
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

							<div class="mb-3">
								<label class="form-label">Jenis File Materi <span class="text-danger"><strong>*</strong></span></label>
								<select class="form-select" name="a_jenis_materi" aria-label="Default select example">
									<option value="">Pilih jenis materi</option>
									<option value="file">File</option>
									<option value="link">Tautan</option>
								</select>
							</div>

							<div class="mb-3">
								<label class="m-0">Mata Pelajaran <span class="form-label"><strong>*</strong></span></label>
								<select class="form-select" name="a_materi_subject" data-live-search="true">
									<option value="">-- Pilih materi --</option>
									<?php foreach($mapels as $mapel): ?>
										<option value="<?=$mapel['subject_id']?>"><?=$mapel['subject_name']?></option>
									<?php endforeach ?>
								</select>
								<input type="hidden" name="a_materi_subject_text">
							</div>

							<div class="mb-3">
								<label class="form-label">Nama / Judul Materi <span class="text-danger"><strong>*</strong></span></label>
								<input required type="text" class="form-control" name="a_materi_title" />
							</div>

							<div class="mb-3">
								<div class="lampiran row pe-0 d-none">
									<label class="form-label">File Lampiran <span class="text-danger"><strong>*</strong></span></label>

									<div class="input-group">
										<input type="file" class="form-control" id="videoFile" name="a_materi_video">
										<label class="form-label overflow-hidden" id="video-label" for="videoFile" data-browse="Unggah Video"></label>
									</div>
									<p class="maximum-file">Maksimal ukuran file 2MB, Tipe File JPG, JPEG, PNG, DOCX, XLSX, PPT, PDF, MP3 dan MP4</p>
								</div>
							
								<div class="link row pe-0 d-none">
									<div class="mb-3">
										<label class="form-label">Tautan <span class="text-danger"><strong>*</strong></span></label>
										<input type="text" class="form-control" name="a_tautan" />
									</div>
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
			<button type="button" class="btn btn-primary" id="save-subject">Simpan</button>
		  </div>
		</div>
	  </div>
	</section>
	<!-- End Modal -->

	<!-- Modal Relasi -->
	<section class="modal fade" id="modal-relasi" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content border-0">
				<div class="modal-header bg-success">
					<h5 class="modal-title text-capitalize text-light text-shadow">Atur Relasi</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<form name="form-relasi" id="form-relasi" class="d-flex flex-column">
					<div class="modal-body">
						<div  id="div_relasi">
						</div>  
						<input type="hidden" name="a_materi_id" id="relasi_materi_id" />
						<input type="hidden" name="xsrf" id="relasi_xsrf" />
					</div>

					<div class="modal-footer">
						<button type="submit" class="btn btn-primary btn-sm text-white" id="save-relasi">simpan</button>
					</div>
				</form>
			</div>
		</div>
	</section>
	<!-- end modal relasi-->

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



	// untuk upload file maksimal 2 mb

    document.getElementById('videoFile').addEventListener('change', function () {
        const file = this.files[0];
		const allowedTypes = ['image/jpeg', 'image/png', 'application/pdf', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-powerpoint', 'audio/mpeg', 'video/mp4'];
        if (file) {
			// Cek apakah file ada dan tipe file sesuai
			if (!allowedTypes.includes(file.type)) {
				Swal.fire({
					html: `
						<div class="icon-faild">&#10060;</div>
						<h2 style="margin:0; font-size:1.4em;">Tipe File Tidak Didukung</h2>
						<p style="margin-top:8px;">Hanya file JPG, JPEG, PNG, DOCX, XLSX, PPT, PDF, MP3, dan MP4 yang diperbolehkan.</p>
					`,
					showCloseButton: false,
					showConfirmButton: true,
					confirmButtonText: 'Upload Ulang',
					customClass: {
						confirmButton: 'swal-wide-button'
					}
				});
				this.value = "";
				return;
			}

            const maxSize = 2 * 1024 * 1024; // 2MB
            if (file.size > maxSize) {
                Swal.fire({
                    html: `
                        <div class="icon-faild">&#10060;</div>
                        <h2 style="margin:0; font-size:1.4em;">Ukuran File Terlalu Besar</h2>
                        <p style="margin-top:8px;">Ukuran file melebihi 2MB. Silakan pilih file yang lebih kecil atau gunakan tautan.</p>
                    `,
                    showCloseButton: false,
                    showConfirmButton: true,
                    confirmButtonText: 'Upload Ulang',
                    customClass: {
                        confirmButton: 'swal-wide-button'
                    }
                });
                this.value = "";
            }
        }
    });
	// end file maksimal
</script>
