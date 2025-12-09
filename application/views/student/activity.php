<!-- <link rel="stylesheet" href="https://pagination.js.org/dist/2.6.0/pagination.css"> -->

<section class="explore-section section-padding" id="section_2">
	
<div class="container">

	<h4>Aktifitas Siswa</h4>

	<!-- section search -->
	<div class="row mt-4">

 

		<div class="col-lg-4 col-md-12 col-sm-12">
			<div class="mb-3 row">
				<label for="start-date" class="col-lg-4 col-md-2 col-sm-2 col-form-label">Tanggal</label>
				<div class="col-lg-8 col-md-8 col-sm-8">
					<input type="hidden" id="student_id" value="<?=$id?>" />
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
		</div>

	</div>

 
	<!-- content -->
	<div class="row mt-4" id="news-content">

		
		
	</div>
		
	<nav aria-label="Page navigation example" class="d-flex justify-content-center">
		<!-- <ul class="pagination">
			
		</ul> -->
		<div id="demo"></div>
	</nav>

	
</section>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://pagination.js.org/dist/2.6.0/pagination.js"></script>
<script src="<?=base_url('assets/js/activity.js')?>"></script>
