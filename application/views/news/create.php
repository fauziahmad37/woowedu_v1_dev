<!-- QUILLJS CSS -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

<section class="explore-section section-padding" id="section_2">
	
	<div class="container">

		<div class="col-12 text-center">
			<h4 class="mb-4">Buat Pengumuman Baru</h4>
		</div>
		
		<div class="col-12">
			<form id="form-create-news" action="news/save" method="POST" enctype="multipart/form-data">
	
				<input type="hidden" id="id" name="id" value="<?=isset($data['id']) ? $data['id'] : '' ?>">
				<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">
	
				<div class="mb-3 col-lg-8 col-md-10 col-sm-12 col-xs-12">
					<label for="title" class="form-label">Judul</label>
					<input type="text" class="form-control <?=empty($_SESSION['error']['errors']['title']) ?: 'is-invalid' ?>" id="title" name="title" value="<?=isset($data['judul']) ? $data['judul'] : ''?><?=$_SESSION['error']['old']['title'] ?? NULL ?>">
					<?php if(!empty($_SESSION['error']['errors']['title'])): ?>
						<small data-error="title" class="text-danger"><?=$_SESSION['error']['errors']['title']?></small>
					<?php endif ?>
				</div>

				<div class="mb-3 col-lg-8 col-md-10 col-sm-12 col-xs-12">
					<!-- Create the editor container -->
					<!-- <div id="editor" class="form-control mb-3"><? // =isset($data['isi']) ? $data['isi'] : '' ?></div> -->
					<textarea class="form-control <?=empty($_SESSION['error']['errors']['isi']) ?: 'is-invalid' ?>" rows="5" placeholder="Masukan deskripsi berita ..." name="isi" id="isi"><?=isset($data['isi']) ? $data['isi'] : ''?><?=$_SESSION['error']['old']['isi'] ?? NULL ?></textarea>
					<?php if(!empty($_SESSION['error']['errors']['isi'])): ?>
						<small data-error="isi" class="text-danger"><?=$_SESSION['error']['errors']['isi']?></small>
					<?php endif ?>
				</div>
				
				<div class="mb-3 col-lg-8 col-md-10 col-sm-12 col-xs-12">
					<input type="file" class="form-control" id="lampiran" name="lampiran" accept="image/*">
				</div>
	
				<div class="mb-3 mt-3">
					<button type="submit" class="btn btn-primary" type="submit" name="save">Simpan</button>
				</div>
			</form>
		</div>

	</div>

</section>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

	// jika gagal upload file
	<?php if(isset($_SESSION['error']) && !empty($_SESSION['error']['message'])): ?>
		Swal.fire({
			icon: 'error',
			title: '<h4 class="text-danger">Gagal</h4>',
			html: '<h5 class="text-danger"><?=$_SESSION['error']['message']?></h5>'
		});
	<?php endif; ?>

</script>
