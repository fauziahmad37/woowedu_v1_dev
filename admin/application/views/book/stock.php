<?php $this->layout('layouts::main_template', ['title' => 'Stok Buku'])?>

<?php $this->start('css') ?>
<link rel="stylesheet" href="<?=$this->e(base_url('assets/node_modules/sweetalert2/dist/sweetalert2.min.css'))?>">
<link rel="stylesheet" href="<?=$this->e(base_url('assets/node_modules/@selectize/selectize/dist/css/selectize.bootstrap4.css'))?>">
<link rel="stylesheet" href="<?=$this->e(base_url('assets/css/main.min.css'))?>">

<?php $this->stop() ?>

<?php $this->start('contents') ?>
<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between pb-2 mb-3 px-2 border-bottom">
		<h1 class="h3 mb-0 text-gray-800"><?=$this->e('Stok Buku')?></h1>
		<button id="btn-add" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"  data-target="#modal_stock" data-toggle="modal">
			<i class="fas fa-plus fa-sm text-white-50"></i> 
			Tambah Data
		</button>
	</div>
	<div class="card">
		<div class="card-header">
			<form name="form-search" class="row">
				<div class="col-12 col-lg-3">
					<input type="text" class="form-control form-control-sm" name="s_stock_code" placeholder="Kode Stok"> 
				</div>
				<div class="col-12 col-lg-3">
					<input type="text" class="form-control form-control-sm" name="s_book" placeholder="Judul Buku">
				</div>
				<div class="col-12 col-lg-3">
					<select class="form-control form-control-sm" name="s_is_available" placeholder="Status"> 
						<option value="">- Pilih Ketersediaan -</option>
						<option value="ya">Tersedia</option>
						<option value="tidak">Tidak Tersedia</option>
					</select>
				</div>
				<div class="col-12 col-lg-3 d-flex flex-nowrap">
					<div class="btn-group btn-group-sm">
						<button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-search"></i></button>
						<button type="reset" class="btn btn-sm btn-danger"><i class="fas fa-times"></i></button>
					</div>
				</div>
			</form>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table id="table-main" class="table table-sm">
					<thead class="bg-purple">
						<tr>
							<th>ID</th>
							<th>Kode Stok</th>
							<th>Book Id</th>
							<th>Judul</th>
							<th>Rak No</th>
							<th>Status</th>
							<th></th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>

</div>

<div id="modal-update" class="modal fade" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-info text-white">
				<h5 class="modal-title mb-0">Ubah Data</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
			</div>
			<form class="modal-body" name="form-update" action="<?=$this->e(base_url('stock/edit'))?>" method="POST">
				<input type="text" class="d-none" name="stock_id">
				<fieldset class="form-row">
					<label class="form-label col-12 col-lg-3 mb-0">Kode Stok <span class="text-danger">*</span></label>
					<div class="col-12 col-lg-9">
						<input type="text" class="form-control <?php if(!empty($_SESSION['error']['errors']['stock_code'])): ?> is-invalid <?php endif ?>" name="stock_code" value="<?=$_SESSION['error']['old']['stock_code'] ?? NULL ?>">
						<?php if(!empty($_SESSION['error']['errors']['stock_code'])): ?>
							<small class="text-danger"><?=$this->e($_SESSION['error']['errors']['stock_code'])?></small>
						<?php endif ?>
					</div>
				</fieldset>
				<fieldset class="form-row mt-3">
					<label class="form-label col-12 col-lg-3 mb-0">Buku <span class="text-danger">*</span></label>
					<div class="col-12 col-lg-9">
						<select class="form-control <?php if(!empty($_SESSION['error']['errors']['stock_book'])): ?> is-invalid <?php endif ?>" name="stock_book" value="<?=$_SESSION['error']['old']['stock_book'] ?? NULL ?>"></select>
						<?php if(!empty($_SESSION['error']['errors']['stock_book'])): ?> 
							<small class="text-danger"><?=$this->e($_SESSION['error']['errors']['stock_book'])?></small>
						<?php endif ?>
					</div>
				</fieldset>
				<fieldset class="row justify-content-end mt-4 border-top pt-3 px-2">
                    <button type="reset" class="btn btn-sm btn-secondary"><i class="fas fa-sync"></i> Ulangi</button>
                    <button type="submit" class="btn btn-sm btn-primary ml-2"><i class="fas fa-save"></i> Simpan</button>
                </fieldset>

			</form>
		</div>
	</div>
</div>

<?php $this->insert('book/modal_stock', ['book_id' => ($_SESSION['error']['old']['book'] ?? NULL ), 'is_readonly' => FALSE]) ?>

<?php $this->stop() ?>

<?php $this->start('js') ?>
<script src="<?=$this->e(base_url('assets/node_modules/sweetalert2/dist/sweetalert2.all.min.js'))?>"></script>
<script src="<?=$this->e(base_url('assets/node_modules/@selectize/selectize/dist/js/selectize.min.js'))?>"></script>

<?php if(isset($_SESSION['success'])): ?>
<script>
   
    Swal.fire({
        icon: 'success',
        title: '<h4 class="text-success">SUKSES</h4>',
        html: '<h5 class="text-success"><?=$_SESSION['success']['message']?></h5>',
        timer: 1500
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
	<?php if($_SESSION['error']['is_stockform']):?>
		$('#modal_stock').modal('show');
	<?php else: ?>
		$('#modal-update').modal('show');
	<?php endif ?>
</script>
<?php endif; ?>

<script src="<?=$this->e(base_url('assets/js/pages/stocks.js'))?>"></script>

<?php $this->insert('book/modal_stock_js', ['is_readonly' => false]) ?>

<?php $this->stop() ?>
