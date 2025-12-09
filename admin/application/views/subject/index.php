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
				<form class="form-horizontal searchCompany" action="#" method="post" id="search-transaction">
					<div class="row align-items-center">
 
						<div class="col-12 col-md-2">
							<div class="form-group mb-0"> 
								<input type="search" class="form-control form-control-sm" name="s_sn" id="s_sn" placeholder="Nama Pelajaran">
								<input type="hidden" name="sekolah_id" value="<?=isset($_SESSION['sekolah_id']) ? $_SESSION['sekolah_id'] : ''?>">
							</div>
						</div> 
						<div class="col-12 col-md-2 align-self-center">
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
					<button type="button" class="btn btn-sm btn-success waves-light waves-effect" id="btn-add" data-toggle="modal" data-target="#modal-add">
							<i class="fa fa-plus-circle fa-fw"></i> <?=$this->lang->line('woow_button_new');?>
					</button>							 	
	        		<button type="button" class="btn btn-sm btn-pink waves-light waves-effect" id="delete_all"><i class="fa fa-trash fa-fw"></i> <?=$this->lang->line('woow_button_delete_all');?></button>
					<div class="btn-group btn-group-sm" role="toolbar">
						<button type="button" class="btn btn-info waves-light waves-effect dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="btn-import">
							<i class="fa fa-upload fa-fw"></i> Import
						</button>
						<div class="dropdown-menu">
							<a class="dropdown-item" type="button" data-toggle="modal" data-target="#modal-import">Import</a>
							<a class="dropdown-item" href="<?=html_escape('assets/files/download/template_mapel.xlsx')?>">Download template</a>
						</div>
					</div>
					 
				</div>
				<div class="table-responsive mt-1">
					<table id="tbl_mapel" class="table table-striped table-sm nowrap w-100">
						<thead class="bg-dark text-white">
							<tr> 
								<th width="2%" class="theader text-center">
									<input type="checkbox" class="custom-control-input" id="select_all">
								</th>
								<th>ID Mapel</th>   
								<th>Kode Mapel</th>   
								<th>Nama Mapel</th>   
								<th>ID Kelas</th>   
								<th>Tingkat Kelas</th>   
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
      <div class="modal-header bg-success">
        <h5 class="modal-title text-capitalize text-light text-shadow">Tambah Mata Pelajaran</h5>
        <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="false">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form name="form-add" id="form-add" class="d-flex flex-column">
            <div class="row align-items-center mb-3">
                <div class="col-4">
                    <label class="m-0">Kode Mapel <span class="text-danger"><strong>*</strong></span></label>
                </div>
                <div class="col-8 mb-3">
                    <input type="text" class="form-control form-control-sm" name="a_subject_code" />
                </div>
                <div class="col-4">
                    <label class="m-0">Nama Mapel<span class="text-danger"><strong>*</strong></span></label>
                </div>
                <div class="col-8">
                    <input type="text" class="form-control form-control-sm" name="a_subject_name" />
                </div>
            </div>
            <div class="row align-items-center">
                <div class="col-4">
                    <label class="m-0">Tingkat Kelas <span class="text-danger"><strong>*</strong></span></label>
                </div>
                <div class="col-8 mb-3">
                    <select type="text" class="form-control form-control-sm" name="a_subject_class_level" data-live-search="true">
										<option>==Pilih==</option>
										<?php foreach($data_class as $rec){ ?>
										<option value="<?php echo $rec['class_level_id']; ?>"><?php echo $rec['class_level_name']; ?></option>
										<?php } ?>
										</select>
                </div>
                <div class="col-4 d-none">
                    <label class="m-0">Poster Thumbnail<span class="text-danger"><strong> </strong></span></label>
                </div>
                <div class="col-8 mb-3 d-none">
									<div class="custom-file">
                    <input type="file" class="custom-file-input form-control-sm" name="a_subject_thumbnail_pic" 
                      accept="image/png, image/gif, image/jpeg" />		
                        <label class="custom-file-label thumbnail-label">Pilih File</label>											
									</div>
                </div>
                <div class="col-4 d-none">
                    <label class="m-0">Poster Detail<span class="text-danger"><strong> </strong></span></label>
                </div>
                <div class="col-8 d-none">
                    <input type="file" class="custom-file-input form-control-sm" name="a_subject_detail_pic"   accept="image/png, image/gif, image/jpeg" />		
										       <label class="custom-file-label detail-label">Pilih File</label>	
                </div>								
            </div>

            <input type="hidden" name="a_id" />
            <input type="hidden" name="xsrf" />
						
						
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="save-subject">Simpan</button>
      </div>
			</form>
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
