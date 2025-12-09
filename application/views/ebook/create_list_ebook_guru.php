<style>
	.scroll::-webkit-scrollbar {
		background-color: red;
	}
</style>

<section id="create_list_ebook_guru">
	<h5>Buat</h5>

	<form class="mt-4">
		<input type="hidden" name="teacher_id" id="teacher_id" value="<?=$teacher_id?>">

		<div class="mb-3">
			<label for="title" class="form-label">Nama / Judul Ebook List <span class="text-danger">*</span></label>
			<input type="text" class="form-control" id="title" name="title">
		</div>
		<div class="mb-3">
			<label for="description" class="form-label">Description</label>
			<textarea class="form-control" id="description" rows="3"></textarea>
		</div>

		<div id="pilihan_ebook" class="mt-5">
			<div class="justify-content-between d-flex">
				<h5 class="d-inline-block">Pilihan Ebook</h5>
				<span>( <span id="jumlah_ebook">0</span> Ebook )</span>
			</div>

			<div id="filter_ebook" class="mt-3">
				<div class="row">
					<div class="col-xl-8 col-lg-8 col-md-12 col-sm-12">
						<div class="row">
							<div class="col">
								<input type="text" id="a_title" name="a_title" class="form-control" placeholder="Cari Judul Ebook">
							</div>
							<div class="col">
								<select class="form-select" name="select-category" id="select-category" aria-label="Pilih Kategori"></select>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div id="daftar_ebook" class="mt-4" style="height: 400px; overflow-y: auto;">
				
			</div>

		</div>
		
		<div class="text-end mt-4">
			<button type="button" class="btn btn-light border-primary" style="width: 200px;">Batal</button>
			<button type="button" class="btn btn-primary" id="simpan" style="width: 200px;" onclick="saveData()">Simpan</button>
		</div>
	</form>
</section>
