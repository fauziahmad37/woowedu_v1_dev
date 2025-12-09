<!-- <link rel="stylesheet" href="https://pagination.js.org/dist/2.6.0/pagination.css"> -->

<section class="explore-section section-padding" id="section_2">

	<!-- section search -->
	<!-- <div class="row mt-4 col-xl-8 col-lg-10 col-md-12 col-sm-12"> -->

		
		<div style="margin-top: 40px;">
            <img src="<?=base_url()?>assets/themes/space/images/papan-pengumuman.png" style="width: 100%;">
        </div>

		<div class="col-lg-4 col-md-12 col-sm-12 mt-3">
			<div class="mb-3 input-group mb-3">
				<input type="text" class="form-control" id="judul" name="judul" placeholder="Cari pengumuman disini">
				<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
				
				<button class="btn btn-primary border shadow-sm" id="search"><i class="bi bi-search text-white"></i> Cari</button>
			</div>
		</div>

		<?php if(isset($_SESSION['teacher_id'])): ?>
			<div class="container d-flex justify-content-end p-0">
				<a href="<?=base_url()?>news/create" class="btn btn-light btn-outline-primary border-2 rounded-pill fw-semibold">
					+ Buat Pengumuman
				</a>
			</div>
		<?php endif ?>

		<!-- <div class="col-lg-4 col-md-12 col-sm-12">
			<div class="mb-3 row">
				<label for="start-date" class="col-lg-4 col-md-2 col-sm-2 col-form-label">Tanggal</label>
				<div class="col-lg-8 col-md-8 col-sm-8">
					<input type="date" class="form-control" id="start-date">
				</div>
			</div>
		</div>

		<div class="col-lg-4 col-md-12 col-sm-12">
			<div class="mb-3 row">
				<label for="end-date" class="col-lg-2 col-md-2 col-sm-2 col-form-label">s/d</label>

				<div class="col-lg-8 col-md-8 col-sm-8">
					<input type="date" class="form-control" id="end-date">
				</div>

				<div class="col-lg-2 col-md-2 col-sm-2 d-flex justify-content-end">
					<button class="btn btn-primary border shadow-sm" id="search"><i class="bi bi-search text-white"></i></button>
				</div>
			</div>
		</div> -->

	<!-- </div> -->

	

	<!-- content -->
	<div class="mb-3" style="overflow-x: auto;">
		<div class="mt-4 d-inline-flex" id="news-content">
	
			
			
		</div>
	</div>
		
	<nav aria-label="Page navigation example" class="d-flex justify-content-center">
		<!-- <ul class="pagination">
			
		</ul> -->
		<div id="demo"></div>
	</nav>

	
</section>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- <script src="https://pagination.js.org/dist/2.6.0/pagination.js"></script> -->
<script src="<?=base_url('assets/js/news.js')?>"></script>

<?php if($this->session->flashdata('success')): ?>
<script>
	Swal.fire({
		icon: 'success',
		title: '<h4 class="text-success">SUKSES</h4>',
		html: '<h5 class="text-success"><?=$_SESSION['success']['message']?></h5>'
	});
</script>
<?php endif; ?>

<?php if(isset($_SESSION['error'])): ?>
<script>
   <?php if(!empty($_SESSION['error']['message'])): ?>
		Swal.fire({
			icon: 'error',
			title: '<h4 class="text-danger">GAGAL</h4>',
			html: '<h5 class="text-danger"><?=$_SESSION['error']['message']?></h5>',
			timer: 1500
		});
	<?php endif ?>
</script>
<?php endif; ?>
