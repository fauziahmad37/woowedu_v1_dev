<section id="ebook_list_section" class="mt-5">

	<input type="hidden" id="teacher_id" value="<?php echo isset($_SESSION['teacher_id']) ? $_SESSION['teacher_id'] : ''; ?>">
	<input type="hidden" id="class_id" value="<?php echo isset($_SESSION['class_id']) ? $_SESSION['class_id'] : ''; ?>">

	<div class="d-flex justify-content-between">
		<h5 class="d-inline-block"><?= isset($_SESSION['teacher_id']) ? 'Ebook List' : 'Ebook List Rekomendasi Guru' ?></h5>
		<a href="ebook/create_ebook_list_guru" class="btn btn-primary rounded-4 <?=isset($_SESSION['teacher_id']) ? '' : 'd-none'?>">Buat List</a>
	</div>

	<div id="container_grid_ebook_list_guru" class="row mt-4 d-flex align-items-stretch">
		<div class="text-center mt-5">
			<div class="spinner-border text-primary" role="status">
				<span class="visually-hidden">Loading...</span>
			</div>
		</div>
	</div>
</section>
