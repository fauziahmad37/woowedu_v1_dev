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

#mc {
  height: 240px;
  overflow-y: auto;
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
				<form class="form-horizontal searchCompany" action="#" method="post" id="search-transaction">
					<div class="row align-items-center">
            <div class="col-12 col-md-2">
							<div class="form-group mb-0"> 
                <label class="mb-0">Kode</label>
								<input type="search" class="form-control form-control-sm" name="s_code" id="s_code" placeholder="">
							</div>
						</div> 
						<div class="col-12 col-md-2">
							<div class="form-group mb-0"> 
              <label class="mb-0">Materi</label>
								<select class="form-control form-control-sm" name="s_materi" id="s_materi" placeholder="Materi" data-live-search="true" data-size="5"></select>
							</div>
						</div> 
						<div class="col-12 col-md-2">
							<div class="form-group mb-0"> 
              <label class="mb-0">Kelas</label>
								<select class="form-control form-control-sm" name="s_kelas" id="s_kelas" placeholder="Nama Kelas" data-live-search="true" data-size="5"></select>
							</div>
						</div> 
						<div class="col-12 col-md-2">
							<div class="form-group mb-0"> 
                <label class="mb-0">Guru</label>
								<select class="form-control form-control-sm" name="s_guru" id="s_guru" placeholder="Guru" data-live-search="true" data-size="5"></select>
							</div>
						</div> 
						
						<div class="col-12 col-md-2 mt-4">
							<div class="btn-group btn-group-sm">
								<button type="button" class="btn btn-primary" id="search-button"><i class="fa fa-search"></i></button>
								<button type="button" class="btn btn-danger" id="reset-search"><i class="fa fa-times"></i></button>
							</div>
						</div>

					</div>
				</form>
				<hr>
				<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
				
					<a role="button" class="btn btn-warning text-white" id="btn-refresh"><i class="fas fa-sync"></i> <?=$this->lang->line('woow_button_refresh');?></a> 
					<a role="button" class="btn btn-sm btn-success waves-light waves-effect text-white" id="btn-add" data-toggle="modal" data-target="#modal-add">
							<i class="fa fa-plus-circle fa-fw"></i> <?=$this->lang->line('woow_button_new');?>
          </a>							 	
	        		<button type="button" class="btn btn-sm btn-pink waves-light waves-effect" id="delete_all"><i class="fa fa-trash fa-fw"></i> <?=$this->lang->line('woow_button_delete_all');?></button>
					<div class="btn-group btn-group-sm" role="toolbar">
						<button type="button" class="btn btn-info waves-light waves-effect dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="btn-import">
							<i class="fa fa-upload fa-fw"></i> Import
						</button>
						<div class="dropdown-menu">
							<a class="dropdown-item" type="button" data-toggle="modal" data-target="#modal-import">Import</a>
							<a class="dropdown-item" href="<?=html_escape('assets/files/download/tugas/tugas.xlsx')?>">Download template</a>
						</div>
					</div>
					 
				</div>
				<div class="table-responsive mt-1">
					<table id="tbl_tugas" class="table table-striped table-sm nowrap w-100">
						<thead class="bg-dark text-white">
							<tr> 
								<th width="2%" class="theader text-center">
									<input type="checkbox" id="select_all">
                </th>
                <th>ID Tugas</th>
                <th>Kode Tugas</th>
                <th>ID Materi</th>
                <th>Materi</th>
                <th>ID Kelas</th>
                <th>Kelas</th>
                <th>ID Guru</th>
                <th>Guru</th>
                <th>Mulai</th>
                <th>Selesai</th>
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
  <div class="modal-dialog modal-xl">
    <div class="modal-content border-0">
      <div class="modal-header bg-success">
        <h5 class="modal-title text-capitalize text-light text-shadow">Tambah Tugas</h5>
        <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="false">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form name="form-add" id="form-add" class="d-flex flex-column">
            <div class="row flex-nowrap">
                <div class="col-12 col-lg-4">
                    <h4 class="mb-4 text-underline">KETERANGAN</h4>
                    <div class="d-flex flex-column">
                        <div class="form-group">
                            <label class="m-0">Kode Tugas <span class="text-danger"><strong>*</strong></span></label>
                            <input type="text" class="form-control form-control-sm" name="a_tugas_code" required/>
                        </div>
                        
                        <div class="form-group">
                            <label class="m-0">Materi <span class="text-danger"><strong>*</strong></span></label>
                            <span class="d-flex flex-nowrap">
                              <select type="text" class="form-control form-control-sm col" name="a_tugas_materi" data-live-search="true" required></select>
                              <button type="button" id="reset-materi" class="btn btn-sm btn-primary"><i class="fas fa-undo"></i></button>
                            </span>
                            <input type="hidden" name="a_tugas_materi_text">
                        </div>
                        <div class="form-group">
                            <label class="m-0">Guru <span class="text-danger"><strong>*</strong></span></label>
                            <span class="d-flex flex-nowrap">
                              <select type="text" class="form-control form-control-sm col" name="a_tugas_guru" data-live-search="true" required></select>
                              <button type="button" id="reset-guru" class="btn btn-sm btn-primary"><i class="fa fa-undo"></i></button>
                            </span>
                            <input type="hidden" name="a_tugas_guru_text">
                        </div>

                        <div class="form-group">
                            <label class="m-0">Kelas <span class="text-danger"><strong>*</strong></span></label>
                            <span class="d-flex flex-nowrap">
                              <select type="text" class="form-control form-control-sm col" name="a_tugas_class" data-live-search="true" required></select>
                              <button type="button" id="reset-class" class="btn btn-sm btn-primary"><i class="fa fa-undo"></i></button>
                            </span>
                            <input type="hidden" name="a_tugas_class_text">
                        </div>

                        <div class="form-group">
                          <label class="m-0">Periode <span class="text-danger"><strong>*</strong></span></label>
                          <input type="text" class="form-control form-control-sm" name="a_tugas_periode" id="a_tugas_periode">
                          <input type="hidden" name="a_tugas_start">
                          <input type="hidden" name="a_tugas_end">
                        </div>

                        <div class="form-group">
                          <label class="m-0">File Pendukung</label>
                          <div class="custom-file">
                            <input type="file" class="custom-file-input" id="a_tugas_file" name="a_tugas_file" aria-describedby="a_tugas_file">
                            <label class="custom-file-label overflow-hidden" for="a_tugas_file" data-browse="Pilih File"></label>
                          </div>
                        </div>
                    
                    </div>
                    
                </div>
                <div class="col">
                    <h4 class="mb-4 text-underline">TUGAS</h4>
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex flex-column">
                                <label for="">Deskripsi <span class="text-danger"><strong>*</strong></span</label>
                                <textarea name="a_tugas_detail" class="form-control" id="detail-tugas" rows="8" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row d-none" id="mc">
                      <div class="col-12">
                       
                      </div>
                    </div>
                </div>
            </div>

            <input type="hidden" name="a_id" />
            <input type="hidden" name="xsrf" />
        </form>
        <span class="w-100 d-flex flex-nogrow">
            <!-- PRogress bar-->
          <div class="progress mt-2 w-100" id="import-progress-1">
            <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
          </div>
          <!-- end PRogress bar-->
        </span>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="save-tugas">Simpan</button>
      </div>
    </div>
  </div>
</section>
<!-- end modal add-->

<!-- Modal Export -->
<section class="modal fade" tabindex="-1" id="modal-export">
  <div class="modal-dialog">
    <div class="modal-content border-0">
      <div class="modal-header bg-purple">
        <h5 class="modal-title text-capitalize text-light text-shadow">Export Data</h5>
        <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="false">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form name="export-area" class="container">
            <div class="row align-items-center">
                <span class="col-4">File Format</span>
                <span class="col-8">
                    <select name="file-format" class="form-control">
                        <option value="excel">EXCEL</option>
                        <option value="pdf">PDF</option>
                        <option value="csv">CSV</option>
                        <option value="txt">TXT</option>
                        <option value="json">JSON</option>
                    </select>
                </span>
            </div>
            <div class="row mt-3">
                <span class="col-4">Data To Export</span>
                <div class="col-8 d-flex flex-column">
                   <div class="form-check">
                        <input type="radio" class="form-check-input" name="check-qty" value="all" checked/>
                        <label class="form-check-label">All (Max. 30000)</label>
                   </div>
                   <div class="form-check">
                        <input type="radio" class="form-check-input" name="check-qty" value="selected" />
                        <label class="form-check-label">Selected (Max 30000)</label>
                   </div>
                   <div class="d-flex flex-wrap align-item-center mt-2">
                        <span class="col-5"><label>Start Position</label></span>
                        <span class="col-7"><input type="number" name="start-position" class="form-control form-control-sm" min="1" value="1" style="width: 4rem" disabled/></span>
                   </div>
                   <div class="d-flex flex-wrap align-item-center mt-2">
                        <span class="col-5"><label>Total Records</label></span>
                        <span class="col-7"><input type="number" name="total-records" class="form-control form-control-sm" min="1" max="30000" value="1" style="width: 6rem" disabled/></span>
                   </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="ml-auto">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-primary" value="Export">
                </div>
            </div>
        </form>
      </div>
    </div>
  </div>
</section>
<!-- End Modal Export -->
<!-- Modal Import -->
<section class="modal fade" tabindex="-1" id="modal-import">
  <div class="modal-dialog">
    <div class="modal-content border-0">
      <div class="modal-header bg-info">
        <h5 class="modal-title text-capitalize text-light text-shadow">import Data</h5>
        <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="false">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form name="import-area" class="container">
            <div class="form-group row">
                <span class="col-4">
                    <label>File Format</label>
                </span>
                <span class="col-8">
                    <div class="form-check align-items-center">
                        <input type="radio" class="form-check-input" name="import-format" value="excel" checked/>
                        <label class="form-check-label">Excel</label>
                    </div>
                </span>
            </div>
            <div class=" form-group row align-items-center">
                <span class="col-4">
                    <label>File Upload</label>
                </span>
                <span class="col-8">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input form-control-sm" name="file-upload" 
                            accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                        <label class="custom-file-label">Pilih File</label>
                    </div>
                </span>
            </div>
                
            <div class="row mt-5">
                <div class="ml-auto">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-primary" value="Import">
                </div>
            </div>
        </form>
        <!-- PRogress bar-->
        <div class="progress" id="import-progress">
          <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
        <!-- end PRogress bar-->
      </div>
    </div>
  </div>
</section>
<!-- End Modal Import -->