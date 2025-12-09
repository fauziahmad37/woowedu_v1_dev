<?php 
$url = parse_url(base_url());
$port = isset($url['port']) ? $url['port'] : '';
$base =  $url['scheme'].'://'.$url['host'].':'.$port;
?>

<style>
    .table td, .table th {
        text-overflow: ellipsis;
        white-space: nowrap;
        max-width: 120px;
        overflow: hidden;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">

            <div class="d-sm-flex align-items-center justify-content-between pb-2 mb-3 px-2 border-bottom">
                <h1 class="h3 mb-0 text-gray-800"><?=html_escape('Buku')?></h1>
				<div class="row">
					<!-- <a id="btn-add" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2"  data-target="#modal-input" data-toggle="modal"> -->
					<a id="btn-add" href="<?=base_url('book/add')?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2">
						<i class="fas fa-plus fa-sm text-white-50"></i> 
						Tambah Data
					</a>
                    <div class="dropdown">
					    <button id="btn-import" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
					    	<i class="fa fa-download" aria-hidden="true"></i> 
					    	Import Data
					    </button>
                        <div class="dropdown-menu">
                            <a role="button" class="dropdown-item" href="<?=html_escape(base_url('assets/files/download/template/book_template.xlsx'))?>" download>Unduh Berkas Templat</a>
                            <a role="button" class="dropdown-item" data-target="#modal-import" data-toggle="modal">Unggah Dari Templat Excel</a>
                        </div>
                    </div>
				</div>
            </div>
			<div class="card">
            	<div class="card-body">

					<div class="col-xl-12 col-lg-12 col-md-12 float-right">
						<form name="form-search">
							<div class="row" style="text-align: end;">
								<div class="col-xl-3 col-lg-3 col-md-4 mb-2">
									<input type="text" class="form-control form-control-sm" name="s_book_author" placeholder="Nama Penulis">
								</div>
								<div class="col-xl-3 col-lg-3 col-md-4 mb-2">
									<input type="text" class="form-control form-control-sm" name="s_book_publisher" placeholder="Nama Penerbit">
								</div>
								<div class="col-xl-3 col-lg-3 col-md-4 mb-2">
									<input type="text" class="form-control form-control-sm" name="s_book_name" placeholder="Nama Buku">
								</div>
								<div class="col-xl-1 col-lg-1 col-md-1">
									<div class="btn-group btn-group-sm">
										<button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-search"></i></button>
										<button type="reset" class="btn btn-sm btn-danger"><i class="fas fa-times"></i></button>
									</div>
								</div>
							</div>
						</form>
					</div>

					<div class="table-responsive">
						<table id="table-main" class="table table-sm w-100">
							<thead class="bg-primary text-white">
								<tr>
									<th>ID</th>
									<th>Gambar</th>
									<th>Judul</th>
									<th>Kode Buku</th>
									<th>Kategori ID</th>
									<th>Kategori</th>
									<th>Penerbit ID</th>
									<th>Penerbit</th>
									<th>Penulis</th>
									<th>ISBN</th>
									<th>Stok</th>
									<th>Tanggal Input</th>
									<th>Operation</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
       		 </div>
			
            
        </div>
    </div>
</div>



<div id="modal-input" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header bg-indigo text-white">
                <h5 class="modal-title">Input Buku</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form name="form-input" action="<?=html_escape(base_url('book/store'))?>" class="modal-body" method="POST" enctype="multipart/form-data">
                <fieldset class="row">
                    <div class="col-12 col-lg-8">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Kode Buku/Barcode <span class="text-danger">*</span></label>
                            <div class="col-8">
                                <input type="text" class="form-control <?=empty($_SESSION['error']['errors']['book-code']) ?: 'is-invalid' ?>" 
                                       name="book-code" value="<?=$_SESSION['error']['old']['book-code'] ?? NULL ?>">
                                <?php if(!empty($_SESSION['error']['errors']['book-code'])): ?>
                                    <small data-error="book-code" class="text-danger"><?=$_SESSION['error']['errors']['book-code']?></small>
                                <?php endif ?>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Judul Buku <span class="text-danger">*</span></label>
                            <div class="col-8">
                                <input type="text" class="form-control <?=empty($_SESSION['error']['errors']['book-title']) ?: 'is-invalid' ?>" name="book-title" value="<?=$_SESSION['error']['old']['book-title'] ?? NULL ?>">
                                <?php if(!empty($_SESSION['error']['errors']['book-title'])): ?>
                                    <small data-error="book-title" class="text-danger"><?=$_SESSION['error']['errors']['book-title']?></small>
                                <?php endif ?>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Kategori Buku <span class="text-danger">*</span></label>
                            <div class="col-8">
                                <input type="text" class="d-none" name="book-category" value="<?=$_SESSION['error']['old']['book-category'] ?? NULL ?>">
                                <input type="text" class="form-control dropdown-toggle cursor-pointer <?=empty($_SESSION['error']['errors']['book-category']) ?: 'is-invalid' ?>" 
                                    name="book-category_text" 
                                    id="book-category_text" 
                                    data-toggle="dropdown" 
                                    readonly>

                                <div id="category-tree" class="dropdown-menu">
                                    <div class="overflow-auto">

                                    </div>
                                </div>
                                <?php if(!empty($_SESSION['error']['errors']['book-category'])): ?>
                                    <small data-error="book-category" class="text-danger"><?=$_SESSION['error']['errors']['book-category']?></small>
                                <?php endif ?>
                            </div>
                        </div>
                       
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Penulis <span class="text-danger">*</span></label>
                            <div class="col-8">
                                <input type="text" class="form-control <?=empty($_SESSION['error']['errors']['book-author']) ?: 'is-invalid' ?>" 
                                       name="book-author" value="<?=$_SESSION['error']['old']['book-author'] ?? NULL ?>">
                                <?php if(!empty($_SESSION['error']['errors']['book-author'])): ?>
                                    <small data-error="book-author" class="text-danger"><?=$_SESSION['error']['errors']['book-author']?></small>
                                <?php endif ?>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Penerbit <span class="text-danger">*</span></label>
                            <div class="col-8">
                                <select class="form-control <?=empty($_SESSION['error']['errors']['book-publisher']) ?: 'is-invalid' ?>" 
                                        name="book-publisher" value="<?=$_SESSION['error']['old']['book-publisher'] ?? NULL ?>">
                                </select>
                                <?php if(!empty($_SESSION['error']['errors']['book-publisher'])): ?>
                                    <small data-error="book-publisher" class="text-danger"><?=$_SESSION['error']['errors']['book-publisher']?></small>
                                <?php endif ?>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">ISBN</label>
                            <div class="col-8">
                                <input type="text" class="form-control" name="book-isbn" value="<?=$_SESSION['error']['old']['book-isbn'] ?? NULL ?>">
                                <?php if(!empty($_SESSION['error']['errors']['book-isbn'])): ?>
                                    <small data-error="book-isbn" class="text-danger"><?=$_SESSION['error']['errors']['book-isbn']?></small>
                                <?php endif ?>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="book-year" class="col-sm-4 col-form-label">Tahun Terbit</label>
                            <div class="col-8">
                                <input type="number" min="1923" class="form-control" name="book-year" id="book-year" value="<?=$_SESSION['error']['old']['book-year'] ?? NULL ?>">
                                <?php if(!empty($_SESSION['error']['errors']['book-year'])): ?>
                                    <small data-error="book-year" class="text-danger"><?=$_SESSION['error']['errors']['book-year']?></small>
                                <?php endif ?>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="book-qty" class="col-sm-4 col-form-label">Stok</label>
                            <div class="col-8">
                                <input type="number" min="0" class="form-control <?=empty($_SESSION['error']['errors']['book-qty']) ?: 'is-invalid' ?>" name="book-qty" id="book-qty" value="<?=$_SESSION['error']['old']['book-qty'] ?? 0 ?>">
                            </div>
                            <?php if(!empty($_SESSION['error']['errors']['book-qty'])): ?>
                                    <small data-error="book-description" class="text-danger"><?=$_SESSION['error']['errors']['book-qty']?></small>
                            <?php endif ?>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Deskripsi/Sinopsis</label>
                            <div class="col-8">
                                <textarea class="form-control" name="book-description" rows="5">
                                    <?=$_SESSION['error']['old']['book-description'] ?? NULL ?>
                                </textarea>
                                <?php if(!empty($_SESSION['error']['errors']['book-description'])): ?>
                                    <small data-error="book-description" class="text-danger"><?=$_SESSION['error']['errors']['book-description']?></small>
                            <?php endif ?>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="" class="col-sm-4 col-form-label">Berkas PDF</label>
                            <div class="col-8">
                                <span class="custom-file">
                                    <input type="file" class="custom-file-input" name="input-file1" id="input-file1">
                                    <label class="custom-file-label" for="input-file1" aria-describedby="input-file1">Pilih File</label>
                                </span>
                            </div>
                        </div>
                        <input type="text" name="book-id" class="d-none">
                    </div>
                    <div class="col-12 col-lg-4">
                        <label>Cover</label>
                        <div class="shadow p-1">
                            <input type="text" name="book-img_name" class="d-none">
                            <label for="book-image" aria-describedby="book-image" class="m-0 p-0">
                                <img id="img-cover" class="img-fluid d-block mx-auto" src="<?=html_escape($base.'/assets/images/ebooks/cover/default.png')?>" height="265" width="228">
                            </label>
                            <input type="file" class="d-none" name="book-image" id="book-image" 
                                accept="image/png, image/jpeg, image/jpg, image/gif">
                        </div>

                        
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

<div id="modal-show" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">DETAIL BUKU</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 col-lg-3">
                        <img data-item="cover_img" class="img-fluid" src="" alt="">
                    </div>
                    <aside class="col-12 col-lg-9">
                        <h3 class="mb-4 text-dark" data-item="title"></h3>
                        <dl class="row">
                            <dt class="col-4 text-primary">
                                Kode Buku
                            </dt>
							<dd class="col-8 mb-1">
                                :&nbsp;<span data-item="book_code"></span>
                            </dd>
                            <dt class="col-4 text-primary">
                                Penulis
                            </dt>
                             <dd class="col-8 mb-1">
                                :&nbsp;<span data-item="author"></span>
                            </dd>
                            <dt class="col-4 text-primary">
                                Penerbit
                            </dt>
                             <dd class="col-8 mb-1">
                                :&nbsp;<span data-item="publisher_name"></span>
                            </dd>
                            <dt class="col-4 text-primary">
                                Tahun Terbit
                            </dt>
                             <dd class="col-8 mb-1">
                                :&nbsp;<span data-item="publish_year"></span>
                            </dd>
                            <dt class="col-4 text-primary">
                                ISBN
                            </dt>
                             <dd class="col-8 mb-1">
                                :&nbsp;<span data-item="isbn"></span>
                            </dd>
                        </dl>
                        
                    </aside>
                    <span class="col-12">
                        <hr class="my-3" />
                        <h6 class="font-weight-bold text-primary">Deskripsi</h6>
                        <p data-item="description" class="text-justify font-weight-light text-dark"></p>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- modal import -->
<div id="modal-import" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header bg-indigo text-white">
                <h5 class="modal-title">Import Data Excel</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form name="form-input" action="<?=html_escape(base_url('book/import'))?>" class="modal-body" method="POST" enctype="multipart/form-data">
                <fieldset class="row">
                    <div class="col-12">
						<span>* Data harus beformat ms. excel / xlsx</span>
						<br>
						<span>* Data harus memiliki sususanan nama kolom yang sesuai</span>
						<br>
						<span>* Data tidak bisa lebih dari 500 data</span>
						<br>
						<div class="form-group row mt-3 border rounded-lg mx-3 shadow">
							<label class="col-sm-2 col-form-label">File Excel</label>
							<div class="col-sm-10">
								<input type="file" name="file" class="my-2" required>
							</div>
						</div>
                        
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
