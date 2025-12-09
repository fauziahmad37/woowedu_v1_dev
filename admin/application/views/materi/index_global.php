<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />

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
								<input type="search" class="form-control form-control-sm" name="s_title" id="s_title" placeholder="Judul">
							</div>
						</div>  
						<div class="col-12 col-md-2">
							<div class="form-group mb-0"> 
								<input type="search" class="form-control form-control-sm" name="s_mapel" id="s_mapel" placeholder="Nama Mapel">
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

				<div class="row">

					<div class="col-6">
						<div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
						
							<a role="button" class="btn btn-warning text-white" id="btn-refresh"><i class="fas fa-sync"></i> <?=$this->lang->line('woow_button_refresh');?></a> 
							
							<button type="button" class="btn btn-sm btn-primary waves-light waves-effect" id="btn-add-folder" data-toggle="modal" data-target="#modal-add-folder">
									<i class="fa fa-plus-circle fa-fw"></i> folder
							</button>	

							<button type="button" class="btn btn-sm btn-success waves-light waves-effect" id="btn-add" data-toggle="modal" data-target="#modal-add">
									<i class="fa fa-plus-circle fa-fw"></i> add
							</button>	

							<button type="button" class="btn btn-sm btn-pink waves-light waves-effect" id="delete_all"><i class="fa fa-trash fa-fw"></i> <?=$this->lang->line('woow_button_delete_all');?></button>
						
						</div>
					</div>

					<div class="col-6 text-right">
						<button type="button" class="btn btn-sm btn-info waves-light waves-effect" id="kembali"><i class="fa fa-arrow-left fa-fw"></i>kembali</button>
					</div>
				</div>

				
				
				<div class="table-responsive mt-1">
					<table id="tbl_materi" class="table table-striped table-sm nowrap w-100">
						<thead class="bg-dark text-white">
							<tr> 
								<th width="2%" class="theader text-center">
									<input type="checkbox" id="select_all">
								</th>
								<th>ID Materi</th>
								<th>Nama Materi</th>
								<th>Terakhir update</th>
								<th>Ukuran</th>
								<th>Aksi</th>
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

<!-- Modal Add Folder -->
<section class="modal fade" tabindex="-1" id="modal-add-folder">
  <div class="modal-dialog">
    <div class="modal-content border-0">
      <div class="modal-header bg-purple">
        <h5 class="modal-title text-capitalize text-light text-shadow">Create Folder</h5>
        <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="false">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form name="export-area" class="container">

					<div class="row align-items-center mb-3">
							<div class="col-4">
									<label class="m-0">Nama Folder <span class="text-danger"><strong>*</strong></span></label>
							</div>
							<div class="col-8"><input type="text" class="form-control form-control-sm" name="folder_name" /></div>
					</div>

					<div class="row">

						
						<!-- 3 setup a container element -->
						<div class="jstree">

							<!-- in this example the tree is populated from inline HTML -->
							<ul>
								<?php $folders = $this->db->where('parent_id', null)->where('materi_type', 0)->get('materi_global')->result(); ?>
								<?php foreach($folders as $folder): ?>
									<li id="<?=$folder->materi_global_id?>">
										<?=$folder->title?>
										<?php $childs1 = $this->db->where('parent_id', $folder->materi_global_id)->where('materi_type', 0)->get('materi_global')->result() ?>
										<?php if($childs1): ?>
											<ul>
												<?php foreach($childs1 as $child1): ?>
													<li id="<?=$child1->materi_global_id?>"><?=$child1->title?></li>
												<?php endforeach ?>
											</ul>
										<?php endif ?>
									</li>
								<?php endforeach ?>
							</ul>
						</div>
					</div>

					<div class="row mt-5">
							<div class="ml-auto">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
									<input type="submit" name="create-folder" class="btn btn-primary" value="create">
							</div>
					</div>
        </form>
      </div>
    </div>
  </div>
</section>
<!-- End Modal Add Folder -->

<!-- Modal Add -->
<section class="modal fade" tabindex="-1" id="modal-add">
  <div class="modal-dialog">
    <div class="modal-content border-0">
      <div class="modal-header bg-purple">
        <h5 class="modal-title text-capitalize text-light text-shadow">Create Folder</h5>
        <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="false">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form name="frm-add" class="container" action="<?=html_escape(base_url('materi/store_global'))?>" method="POST" enctype="multipart/form-data">

					<div class="row align-items-center mb-3">
							<div class="col-4">
									<label class="m-0">Pilih Folder <span class="text-danger"></span></label>
							</div>
					</div>

					<div class="row">
						<!-- 3 setup a container element -->
						<div class="jstree">

							<!-- in this example the tree is populated from inline HTML -->
							<ul>
								<?php $folders = $this->db->where('parent_id', null)->where('materi_type', 0)->get('materi_global')->result(); ?>
								<?php foreach($folders as $folder): ?>
									<li id="<?=$folder->materi_global_id?>">
										<?=$folder->title?>
										<?php $childs1 = $this->db->where('parent_id', $folder->materi_global_id)->where('materi_type', 0)->get('materi_global')->result() ?>
										<?php if($childs1): ?>
											<ul>
												<?php foreach($childs1 as $child1): ?>
													<li id="<?=$child1->materi_global_id?>"><?=$child1->title?></li>
												<?php endforeach ?>
											</ul>
										<?php endif ?>
									</li>
								<?php endforeach ?>
							</ul>
						</div>
					</div>

					<input type="hidden" name="parent_id">

					<div class="row align-items-center mb-3 mt-3">
							<div class="col-4">
									<label class="m-0">Judul Materi <span class="text-danger"><strong>*</strong></span></label>
							</div>
							<div class="col-8"><input type="text" class="form-control form-control-sm" name="a_title" /></div>
					</div>

					<div class="row align-items-center mb-3">
							<div class="col-4">
									<label class="m-0">Jenis <span class="text-danger"><strong>*</strong></span></label>
							</div>
							<div class="col-8">
								<select type="text" class="form-control form-control-sm" name="a_type">
									<option value="">Pilih Jenis</option>
									<option value="file">File</option>
									<option value="link">Link / Tautan</option>
								</select>
							</div>
					</div>

					<div class="row align-items-center mb-3 mt-3 input-link-group d-none">
						<div class="col-4">
								<label class="m-0">Link <span class="text-danger"><strong>*</strong></span></label>
						</div>
						<div class="col-8"><input type="text" class="form-control form-control-sm" name="a_link" /></div>
					</div>

					<div class="form-group row input-file-group d-none">
						<label for="" class="col-sm-4 col-form-label">Berkas PDF</label>
						<div class="col-8">
							<input type="file" class="" name="input-file1">
						</div>
					</div>

					<div class="row mt-5">
							<div class="ml-auto">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
									<input type="submit" name="save" class="btn btn-primary" value="create">
							</div>
					</div>
        </form>
      </div>
    </div>
  </div>
</section>
<!-- End Modal Add -->

<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.1/jquery.min.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>
