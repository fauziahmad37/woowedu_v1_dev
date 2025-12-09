<?php // if(isset($_SESSION['simpan']['old'])) print_r($_SESSION['simpan']['old']) ?>
<style>
	.select2-container--default .select2-selection--multiple{
		opacity: 0.5;
	}

</style>

<section class="explore-section section-padding" id="section_2">
	
	<div class="container">

		<h4 class="mb-4 mt-3">Buat Tugas Regular</h4>

		<div class="col-12">
			<form name="form-tugas" id="form-tugas" enctype="multipart/form-data">
			<!-- <form id="form-tugas" action="task/save"> -->
			
				<input type="hidden" id="id" name="id" value="<?=isset($data['task_id']) ? $data['task_id'] : '' ?>">
				<input type="hidden" name="tipeTugas" value="<?= $_GET['type'] ?? 'regular' ?>"/>
				<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">

				<?php if(!empty($bookinfo)): ?>
					<div class="mb-3 col-lg-8 col-md-10 col-sm-12 col-xs-12">
						<label for="">Ebook</label>
						<input type="text" class="form-control" name="ebook_title" value="<?=html_escape($bookinfo['title'])?>" readonly>
						<input type="hidden" name="ebook_code" value="<?=html_escape($bookinfo['book_code'])?>">
						<input type="hidden" name="pages" value="<?=html_escape($bookinfo['pages'])?>">
					</div>
				<?php endif; ?>

				<div class="mb-3 col-lg-8 col-md-10 col-sm-12 col-xs-12">
					<label for="title" class="form-label">Mata Pelajaran <span class="text-danger">*</span></label>

					<select required class="form-select" name="select_mapel" id="select_mapel" aria-label="Pilih Matapelajaran">
						<option value="" >-- Pilih --</option>
						<?php foreach($mapelop as $key => $val) : ?>
							<?php if(isset($data['subject_id']) && $data['subject_id'] == $val['subject_id']){ ?>
								<option selected value="<?=$val['subject_id']?>"><?=$val['subject_name']?></option>
							<?php } else { ?>
								<option value="<?=$val['subject_id']?>"><?=$val['subject_name']?></option>
							<?php } ?>
						<?php endforeach ?>
					</select>	
				</div>

				<div class="mb-3 col-lg-8 col-md-10 col-sm-12 col-xs-12">
					<label for="title" class="form-label">Kelas <span class="text-danger">*</span></label>

					<select required class="form-control" name="pilih_kelas[]" id="select_class" multiple="multiple">
						
					</select>	
					
				</div>
		

				<div class="row mb-3 col-lg-8 col-md-10 col-sm-12 col-xs-12">
					<div class="mb-3 col-xl-3 col-lg-3 col-md-6 col-sm-6 col-xs-6">
						<label for="title" class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
						<input required type="date"  class="form-control" id="tanggal_start" name="tanggal_start" min="<?=date('Y-m-d')?>" value="<?=isset($data['available_date']) ? date("Y-m-d",strtotime($data['available_date'])) : ''?>">
					</div> 
					<div class="mb-3 col-xl-3 col-lg-3 col-md-6 col-sm-6 col-xs-6">
						<label for="title" class="form-label">Jam Mulai <span class="text-danger">*</span></label>
						<input required type="time" class="form-control" id="jamstart" name="jamstart" value="<?=isset($data['available_date']) ? date("H:i",strtotime($data['available_date'])) : ''?>">
					</div> 
					
					<div class="mb-3 col-xl-3 col-lg-3 col-md-6 col-sm-6 col-xs-6">
						<label for="title" class="form-label">Tanggal Akhir <span class="text-danger">*</span></label>
						<input required type="date"  class="form-control" id="tanggal_end" name="tanggal_end" min="<?=date('Y-m-d')?>" value="<?=isset($data['due_date']) ? date("Y-m-d",strtotime($data['due_date'])) : ''?>">
					</div> 				
					<div class="mb-3 col-xl-3 col-lg-3 col-md-6 col-sm-6 col-xs-6">
						<label for="title" class="form-label">Jam Akhir <span class="text-danger">*</span></label>
						<input required type="time" class="form-control" id="jamend" name="jamend" value="<?=isset($data['due_date']) ? date("H:i",strtotime($data['due_date'])) : ''?>">
					</div> 						
				</div>
			
				<div class="mb-3 col-lg-8 col-md-10 col-sm-12 col-xs-12">
					<label for="title" class="form-label">Judul Tugas <span class="text-danger">*</span></label>

					<!-- <textarea required class="form-control" id="keterangan" name="keterangan"  ><?//=isset($data['note']) ? $data['note'] : ''?></textarea> -->
					<!-- <div id="keterangan" class="form-control mb-3"><?//=isset($data['note']) ? $data['note'] : '' ?></div> -->

					<!-- Create the editor container -->
					<input type="text" name="title" id="title" class="form-control" value="<?=isset($data['title']) ? $data['title'] : '' ?>" required>

				</div>

				<div class="mb-3 col-lg-8 col-md-10 col-sm-12 col-xs-12">
					<label for="keterangan" class="form-label">Deskripsi Soal Tugas <span class="text-danger">*</span></label>

					<!-- Create the editor container -->
					<div id="keterangan" class="form-control mb-3"><?=isset($data['note']) ? $data['note'] : '' ?></div>
					<textarea hidden name="keterangan"></textarea>
				</div>


				<div class="mb-3 col-lg-8 col-md-10 col-sm-12 col-xs-12">
					<label for="lampiran" class="form-label">Lampiran</label>
					<input type="file" class="form-control" id="lampiran" name="lampiran">
				</div>

				<p class="text-danger fs-12">* Unggah file tugas anda disini, dengan maksimal Ukuran File 100Mb, Jenis file: Jpg, Png, Pdf, Docx, Xlsx, MP4</p>

		
				<div class="mb-3">
					<button type="submit" class="btn btn-primary mb-4" id="simpan">Simpan</button>
				</div>
			</form>
		</div>

	</div>

</section>



