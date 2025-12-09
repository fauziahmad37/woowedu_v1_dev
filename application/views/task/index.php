<section class="explore-section section-padding" id="section_2">
<!-- <link rel="stylesheet" href="https://pagination.js.org/dist/2.6.0/pagination.css"> -->
	
<!-- <div class="container"> -->
	<input type="hidden" name="user_level" id="user_level" value="<?=$_SESSION['user_level']?>">
	<!-- section search -->
	<div class="row mt-5 <?=($_SESSION['user_level'] == 3) ? 'd-none' : '';?>">
		<div class="col-xl-4 col-lg-6 col-md-12 col-sm-12 mb-2">
			<div class="container border border-3 border-primary rounded-4 p-3">
				<div class="d-flex flex-nowrap">
					<div class="order-1">
						<img src="<?=base_url()?>assets/images/task-card-1.png" class="me-3">
					</div>
					<div class="order-2 ps-3">
						<p style="font-size: 16px; line-height:30px">Total tugas</p>
						<p style="font-size: 40px; line-height:5px" class="fw-bold text-primary"><?=$total_task?></p>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xl-4 col-lg-6 col-md-12 col-sm-12">
			<div class="container border border-3 border-primary rounded-4 p-3">
				<div class="d-flex flex-nowrap">
					<div class="order-1">
						<img src="<?=base_url()?>assets/images/task-card-2.png" class="me-3">
					</div>
					<div class="order-2 ps-3">
						<p style="font-size: 16px; line-height:30px">Deadline</p>
						<p style="font-size: 40px; line-height:5px" class="fw-bold text-primary"><?=count($deadline)?></p>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row mt-4 d-flex">

		<div class="mt-2 col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-6">
			<label for="select-mapel" class="form-label fw-bold">Mata Pelajaran</label>
			<select class="form-select" name="select-mapel" id="select-mapel" aria-label="Pilih Matapelajaran">
				<option  value="" >-- Pilih --</option>
				<?php foreach($mapelop as $key => $val) : ?>
					<option  value="<?=$val['subject_id']?>" ><?=$val['subject_name']?></option>
				<?php endforeach ?>
			</select>
		</div>

		<div class="mt-2 col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-6">
			<label for="start-date" class="form-label fw-bold">Tanggal</label>
			<input type="date" class="form-control" id="start-date">
		</div>

		<div class="mt-2 col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-6">
			<input type="date" class="form-control" id="end-date" style="margin-top: 28px;">
		</div>

		<div class="mt-2 col-xl-3 col-lg-3 col-md-4 col-sm-6 col-xs-6" id="btn-group-search">
			<button class="btn btn-primary text-white" id="search" style="margin-top: 28px;">
				<i class="bi bi-search"></i>
				Cari
			</button>
		</div>

	</div>

	<?php if($this->session->userdata('user_level') == 3) : ?>
	<div class="row justify-content-end mt-4">
		<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 d-flex justify-content-end">

			<a class="btn btn-light btn-outline-primary rounded-pill border-2 fw-semibold" href="<?=trim(base_url('task/create?type=regular'))?>">+ Buat Tugas</a>

			<!-- <a class="btn btn-outline-primary rounded-pill" href="<?=trim(base_url('task/create?type=regular'))?>">Buat Tugas</a> -->


			<!-- <div class="dropdown">
				<button class="btn btn-outline-primary rounded-pill dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
					Buat Tugas
				</button>
				<ul class="dropdown-menu" style="width: 180px">
					<li><a class="dropdown-item" href="<?=trim(base_url('task/create?type=regular'))?>">Tugas Reguler</a></li>
					<li><a class="dropdown-item" role="button" data-bs-toggle="modal" data-bs-target="#ebook-selector">Tugas Ebook</a></li>
				</ul>
			</div> -->
		</div>
	</div>
	<?php endif ?>

	<!-- content -->
	<div class="row mt-4" id="task-content">

		
		
	</div>
		
	<nav aria-label="Page navigation example" class="d-flex justify-content-center">
		<div id="page"></div>
	</nav>
	
</section>

<div class="modal fade" id="ebook-selector" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-secondary">
        <h5 class="modal-title">Pilih Ebook</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
			<div id="ebook-all-container" class="col-12">
				<div class="input-group">
					<input type="search" id="search-ebook-task" class="form-control" placeholder="Cari Ebook Berdasarkan Nama ...">
					<span class="input-group-text input-group-text-transparent"><i class="bi bi-search"></i></span>
				</div>
				
			</div>
			<h6 class="mt-3 mb-0"><small>Pilih salah satu ebook</small></h6>
			<div class="col-12 mt-2">
				<div id="ebook-list-container" class="border rounded overflow-auto" style="height: 400px">
				</div>
			</div>
		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batalkan</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade <?php if(!empty($this->session->flashdata('errors'))): ?> show <?php endif ?>" id="ebook-pages" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-secondary">
				<h5 class="mb-0">Pilih Halaman</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<?php if(!empty($this->session->flashdata('errors'))): ?> 
					<div class="alert alert-danger">
						<?php foreach($this->session->flashdata('errors') as $err): ?>
						<span><?=$err?></span><br/>
						<?php endforeach ?>
					</div>
					
				<?php endif ?>
				<form name="frm-task-ebook" enctype="multipart/form-data" action="<?=base_url()?>/task/ebookTask" method="post">
					<input type="hidden" name="book_code"/>
					<input type="hidden" name="title"/>
					<div id="pages-grid" class="row" style="height: 420px; overflow-y: auto">
						
					</div>
					<div class="col-12 d-flex flex-nowrap justify-content-end pt-3">
						<button type="reset" class="btn btn-secondary">Batalkan</button>
						<button type="submit" class="btn btn-primary ms-1">Buat tugas ebook</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<dialog id="dialog-preview" class="rounded border shadow p-2">
	<div class="d-flex w-100">
		<h3 class="text-shadow text-capitalize ms-2">Preview</h3>
		<button type="button" class="btn-close ms-auto" aria-label="Close" onclick="document.getElementById('dialog-preview').close()"></button>
	</div>
	<div id="dialog-body" class="d-block w-100 rounded border overlow-auto">
		<img class="img-fluid" id="img-preview">
	</div>
</dialog>
