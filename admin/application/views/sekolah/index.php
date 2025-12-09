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
</style>
<div class="row">
	<div class="col-12">
		<div class="card"> 
		<div class="card-header"></div>
			<div class="card-body">


					 
				<form class="form-horizontal searchClass" action="#" method="post" id="search-kelas">
           <div class="row"> 
                <div class="col">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="s_sekolah_nama">Nama Sekolah</span>
                        </div>
                        <input type="search" class="form-control" aria-describedby="s_sekolah_nama" name="s_sekolah_nama" placeholder="Cari Nama Sekolah" />
                    </div>
                </div>
                <div class="col">
                    <button type="button" id="btn-search_class" class="btn btn-primary shadow">
                        <i class="fa fa-search fa-fw"></i>
                        Cari
                    </button>
                </div>
           </div>
				</form>
				<hr>
				<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
				
					<a role="button" class="btn btn-warning text-white" id="btn-refresh"><i class="fas fa-sync"></i> <?=$this->lang->line('woow_button_refresh');?></a> 

					<button type="button" class="btn btn-sm btn-success waves-light waves-effect" id="btn-add" data-toggle="modal" data-target="#modal-add">
							<i class="fa fa-plus-circle fa-fw"></i> <?=$this->lang->line('woow_button_new');?>
					</button>
													 	
	        <button type="button" class="btn btn-sm btn-pink waves-light waves-effect" id="delete_all"><i class="fa fa-trash fa-fw"></i> <?=$this->lang->line('woow_button_delete_all');?></button>
					
					 
				</div>
				<div class="table-responsive mt-1">
					<table id="tbl_sekolah" class="table table-striped table-sm nowrap">
						<thead class="bg-dark text-white">
							<tr> 
								<th width="2%" class="theader text-center">
									<input type="checkbox" class="custom-control-input" id="select_all">
								</th>
								<th>#</th>  
								<th>Kode</th>     
								<th>Nama</th>
								<th>Alamat</th>
								<th>Telp</th>
								<th>Kode Aktivasi</th>
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


<!-- Modal add -->
<section class="modal fade" tabindex="-1" id="modal-add">
  <div class="modal-dialog">
    <div class="modal-content border-0">
      <div class="modal-header bg-primary">
        <h5 class="modal-title text-capitalize text-light text-shadow">Tambah Sekolah</h5>
        <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="false">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form name="form-add" id="form-add" class="d-flex flex-column">

						<input type="hidden" name="a_sekolah_id">
            <div class="row align-items-center mb-3">
                <div class="col-4">
                    <label class="m-0">Nama Sekolah<span class="text-danger"><strong>*</strong></span></label>
                </div>
                <div class="col-8"><input type="text" class="form-control form-control-sm" name="a_sekolah_nama" /></div>
            </div>
            
						<div class="row align-items-center mb-3">
                <div class="col-4">
                    <label class="m-0">Kode Sekolah<span class="text-danger"><strong>*</strong></span></label>
                </div>
                <div class="col-8"><input type="text" class="form-control form-control-sm" name="a_sekolah_kode" /></div>
            </div>

						<div class="row align-items-center mb-3">
                <div class="col-4">
                    <label class="m-0">Alamat<span class="text-danger"><strong>*</strong></span></label>
                </div>
                <div class="col-8"><textarea class="form-control form-control-sm" name="a_sekolah_alamat" id="a_sekolah_alamat"></textarea></div>
            </div>

						<div class="row align-items-center mb-3">
                <div class="col-4">
                    <label class="m-0">Telp Sekolah</label>
                </div>
                <div class="col-8"><input type="text" class="form-control form-control-sm" name="a_sekolah_phone" /></div>
            </div>

						<div class="row align-items-center mb-3 d-none">
                <div class="col-4">
                    <label class="m-0">Kode Aktivasi<span class="text-danger"><strong>*</strong></span></label>
                </div>
                <div class="col-8"><input type="text" class="form-control form-control-sm" name="a_kode_aktivasi" /></div>
            </div>

            <input type="hidden" name="xsrf" />
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="save-kelas">Simpan</button>
      </div>
    </div>
  </div>
</section>
<!-- end modal add-->
