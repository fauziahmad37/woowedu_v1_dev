<section class="bundling-package-section">

	<!-- section search -->
	<form class="row mt-4 align-items-end" name="frm-search">

		<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-xs-12 mb-2 autocomplete">
			<label>Judul</label>
			<input type="text" class="form-control" id="title" name="filter[title]" placeholder="Ketik nama atau sebagian nama yang di cari" />
		</div>

		<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-xs-12 mb-2">
			<label>Penerbit</label>
			<select class="form-select" id="publisher" name="filter[penerbit]">
				<option value="">-- Pilih Penerbit --</option>
				<?php // foreach ($penerbit as $pub) : 
				?>
				<!-- <option value="<? // = html_escape($pub['id']) 
									?>"><? // = html_escape(trim($pub['publisher_name'])) 
																		?></option> -->
				<?php // endforeach; 
				?>
			</select>
		</div>

		<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-xs-12 mb-2" id="btn-group-search">
			<button type="submit" class="btn btn-primary text-white me-2">Cari</button>
			<button type="reset" class="btn btn-outline-primary border-2 reset-filter">Reset Filter</button>
		</div>

	</form>

	<div class="row mb-3 mt-4">
		<div class="col-6">
			<h5>Paket Bundling <img src="assets/images/icons/emoji-sparkles.png" alt="" width="28"></h5>
		</div>
	</div>

	<div id="bundling-package-list" class="row mt-4">
		<!-- Daftar paket bundling akan ditampilkan di sini -->
	</div>

	<div id="load-more" class="text-center mt-4 d-none">
		<button class="btn btn-primary">Lihat Lebih Banyak <i class="bi bi-chevron-double-down"></i></button>
	</div>

</section>
