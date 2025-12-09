<section id="main_detail_ebook_list_guru" class="card border-primary rounded-top-4 mt-5" style="min-height: 400px;">

	<input type="hidden" name="ebook_teacher_id" value="<?= $id ?>">
	<input type="hidden" name="teacher_id" value="<?= $teacher_id ?>">

	<div class="card-header bg-primary text-white rounded-top-4 p-4 d-flex justify-content-between">
		<div class="col">
			<h5 class="card-title">Detail Ebook List Guru</h5>
			<p class="card-description" style="font-size: 12px;">Berikut adalah detail dari ebook yang dipilih.</p>
			<div class="d-flex">
				<div class="me-3">
					<img class="me-2 text-white" src="assets/images/icons/book-marked-fill-white.png" alt="icon ebook" width="14"><span class="total-ebooks"></span> Ebook
				</div>
				<div>
					<img class="me-2 text-white" src="assets/images/icons/class-scene-svgrepo-com 1-white.png" alt="icon class" width="14">Kelas <span class="class-levels"></span>
				</div>
			</div>
		</div>
		<div class="col d-flex btn-group-share" style="flex-direction: row-reverse;">

			<div class="dropdown" style="align-self: center;">
				<button class="btn btn-clear border-light rounded-3 text-white" data-bs-toggle="dropdown" aria-expanded="false">
					<i class="bi bi-three-dots"></i>
				</button>
				<ul class="dropdown-menu" style="min-width: 40px;">
					<li class="mx-2">
						<button id="add_ebook" data-bs-toggle="modal" data-bs-target="#modalTambahEbook" class="btn btn-light text-center"><i class="bi bi-plus text-primary"></i></button>
						<button class="btn btn-light text-center" id="delete_all_data"><i class="bi bi-trash text-danger"></i></button>
					</li>
				</ul>
			</div>

			<button class="btn btn-clear border-light rounded-3 text-white me-2" id="button_share" style="align-self: center;" data-bs-toggle="modal" data-bs-target="#modalShareKelas">
				<i class="bi bi-share"></i>
			</button>
		</div>
	</div>

	<div class="card-body" id="list_ebook_dipilih">

	</div>

	<!-- Modal Share Kelas -->
	<div class="modal fade" id="modalShareKelas" tabindex="-1" aria-labelledby="modalShareKelasLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header bg-primary text-white">
					<h5 class="modal-title" id="modalShareKelasLabel">Bagikan Ebook List</h5>
					<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<p class="fw-bold">Pilih Kelas:</p>

					<div id="pilih_kelas_section"></div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" id="simpan_share">Simpan</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal tambah ebook -->
	<div class="modal fade" id="modalTambahEbook" tabindex="-1" aria-labelledby="modalTambahEbookLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header bg-primary text-white">
					<h5 class="modal-title" id="modalTambahEbookLabel">Tambah Ebook</h5>
					<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="alert alert-primary mb-4 border-0 rounded-4" role="alert" style="font-size: 12px;">
						<i class="bi bi-exclamation-circle-fill"></i> Ebook yang dapat kamu tambahkan hanya ebook yang sudah kamu miliki
					</div>

					<div id="filter_ebook" class="mt-3">
						<div class="row">
							<div class="col">
								<input type="text" id="a_title" name="a_title" class="form-control" placeholder="Cari Judul Ebook">
							</div>
							<div class="col">
								<select class="form-select" name="select-category" id="select-category" aria-label="Pilih Kategori"></select>
							</div>
						</div>
					</div>

					<div id="daftar_ebook" class="mt-4" style="height: 400px; overflow-y: auto;">
						<!-- Daftar ebook akan ditampilkan di sini -->

					</div>

					<div class="modal-footer mt-4">
						<button type="button" id="simpan_ebook" class="btn btn-primary">Simpan</button>
					</div>
				</div>
			</div>
		</div>
	</div>

</section>
