<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

<section class="explore-section section-padding" id="section_2">
	
	<div class="container">

		<div class="col-12 text-center">
			<h4 class="mb-4">Buat Ujian Baru</h4>
		</div>
		
 
		<div class="col-12">
	<form action="<?=base_url('teacher/saveujian')?>" method="POST" enctype="multipart/form-data">
	
				<input type="hidden" id="id" name="id" value="<?=isset($data['task_id']) ? $data['task_id'] : '' ?>">
	
				<div class="mb-3 col-lg-8 col-md-10 col-sm-12 col-xs-12">
					<label for="title" class="form-label">Mata Pelajaran</label>
 <?php
 if(!empty($id))
   $subject_id=$data['subject_id'];
 else
	 $subject_id='0';
  
 ?>
					<select class="form-select" name="select_mapel" id="select_mapel" aria-label="Pilih Matapelajaran">
						<option  value="" >==Pilih==</option>
						<?php 
						
						foreach($mapelop as $key => $val) : 
						 ?>
							<option  value="<?=$val['subject_id']?>" ><?=$val['subject_name']?></option>
						<?php 
						 
						endforeach ?>
					</select>	
				</div>
 
				<div class="mb-3 col-lg-8 col-md-10 col-sm-12 col-xs-12">
					<label for="title" class="form-label">Kelas</label>
 
					<select class="form-select" name="select_kelas" id="select_kelas" aria-label="Pilih Matapelajaran">
						<option  value="" >==Pilih==</option>
						<?php 
						
						foreach($kelasop as $key => $val) : 
						 ?>
							<option  value="<?=$val['class_id']?>" ><?=$val['class_name']?></option>
						<?php 
						 
						endforeach ?>
					</select>	
				</div>
 
		<div class="row mb-3 col-lg-8 col-md-10 col-sm-12 col-xs-12">
					<div class="mb-3 col-lg-3 col-md-3 col-sm-3 col-xs-3">
					<label for="title" class="form-label">Tanggal Mulai </label>
					<input type="date"  class="form-control" id="tanggal_start" name="tanggal_start" value="<?=isset($data['available_date']) ? date("Y-m-d",strtotime($data['available_date'])) : ''?>">
				</div> 
					<div class="mb-3 col-lg-3 col-md-3 col-sm-3 col-xs-3">
					<label for="title" class="form-label">Jam Mulai</label>
					<input type="time" class="form-control" id="jamstart" name="jamstart" value="<?=isset($data['available_date']) ? date("H:i",strtotime($data['available_date'])) : ''?>">
				</div> 
				
					<div class="mb-3 col-lg-3 col-md-3 col-sm-3 col-xs-3">
					<label for="title" class="form-label">Tanggal Akhir</label>
					<input type="date"  class="form-control" id="tanggal_end" name="tanggal_end" value="<?=isset($data['due_date']) ? date("Y-m-d",strtotime($data['due_date'])) : ''?>">
				</div> 				
				<div class="mb-3 col-lg-3 col-md-3 col-sm-3 col-xs-3">
				<label for="title" class="form-label">Jam Akhir</label>
					<input type="time" class="form-control" id="jamend" name="jamend" value="<?=isset($data['due_date']) ? date("H:i",strtotime($data['due_date'])) : ''?>">
				</div> 						
		</div>
	
 

 
				<div class="mb-3">
					<button type="submit" class="btn btn-primary" name="simpan">Simpan</button>
				</div>
			</form>
		</div>

	</div>

</section>
 