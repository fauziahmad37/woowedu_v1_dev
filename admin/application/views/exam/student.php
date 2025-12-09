<style>
a.close_corner_top_left {
	position: absolute;
	top: -14px;
	left: -10px;
}
.mr-20 {
	margin-right:20px;
}
.dataTables_filter, .dataTables_length {
	display: none;
}

.bg-purple {
    background-color: var(--purple);
    color: var(--white);
}

.bg-pink {
    background-color: var(--pink);
    color: var(--white);
}

.bg-orange {
    background-color: var(--orange);
    color: var(--white);
}

[type="checkbox"]:not(:checked), [type="checkbox"]:checked {
	position: static!important;
	left: 0px!important;
	opacity: 1!important;
	visibility: visible!important;
	pointer-events: all!important;
}

#tbl_reserve_wrapper > .row:nth-child(2), #tbl_selected_wrapper > .row:nth-child(2) {
  min-height: 300px;
}
</style>
<div class="row">
	<div class="col-12">
		<div class="card"> 
		<div class="card-header"></div>
			<div class="card-body">
				<form class="form-horizontal searchCompany" action="#" method="post" id="form-search" name="form-search">
					<div class="row align-items-center">
 
						<div class="col-12 col-md-2">
							<div class="form-group mb-0"> 
                <label class="mb-0">Kode Ujian</label>
								<input type="search" class="form-control form-control-sm" name="s_code" id="s_code" placeholder="Kode Ujian">
							</div>
						</div> 
						<div class="col-12 col-md-2">
							<div class="form-group mb-0"> 
                <label class="mb-0">Kelas</label>
								<select class="form-control form-control-sm" name="s_kelas" id="s_kelas" data-live-search="true"></select>
							</div>
						</div> 
						<div class="col-12 col-md-2">
							<div class="form-group mb-0"> 
                <label class="mb-0">Mapel</label>
								<select class="form-control form-control-sm" name="s_mapel" id="s_mapel" data-live-search="true"></select>
							</div>
						</div> 
						<div class="col-12 col-md-2">
							<div class="form-group mb-0"> 
                <label class="mb-0">Tanggal</label>
								<input type="search" class="form-control form-control-sm" name="s_tanggal" id="s_tanggal" placeholder="Tanggal">
							</div>
						</div> 
						<div class="col-12 col-md-2 align-self-center">
							<div class="btn-group btn-group-sm mt-3">
								<button type="button" class="btn btn-primary" id="search-button"><i class="fa fa-search"></i></button>
								<button type="button" class="btn btn-danger" id="reset-search"><i class="fa fa-times"></i></button>
							</div>
						</div>

					</div>
				</form>
				<hr>
				<div class="table-responsive mt-1">
					<table id="tbl_ujian" class="table table-striped table-sm nowrap w-100">
						<thead class="bg-dark text-white">
							<tr> 
								<th width="2%" class="theader text-center">
									<input type="checkbox" id="select_all">
								</th>
								<th>ID Ujian</th>   
								<th>Kode Ujian</th> 
                                <th>ID Kategori</th>
                                <th>Kategori</th>
								<th>ID Kelas</th> 
                                <th>Kelas</th>     
                                <th>ID Mapel</th>     
                                <th>Kode Mapel</th>     
								<th>Mata Pelajaran</th>   
								<th>Tanggal Awal</th>   
								<th>Tanggal Akhir</th>   
								<th>Durasi (Jam)</th>
                                <th>Total Soal</th>
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

<script src="<?= base_url('assets/new/libs/sweetalert2/sweetalert2.min.js') ?>"></script>


<?php if(isset($_SESSION['flash_message']) && !empty($_SESSION['flash_message'])): ?>
    <script>
        Swal.fire({
            type: 'error',
            title: '<h5 class="text-danger text-uppercase">error</h5>',
            html: '<?= $_SESSION['flash_message']?>'
        });
    </script>
<?php endif; unset($_SESSION['flash_message']);?>
