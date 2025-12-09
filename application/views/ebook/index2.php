<section class="explore-section section-padding" id="section_2">

	<div class="container-fluid">

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
					<?php foreach ($penerbit as $pub) : ?>
						<option value="<?= html_escape($pub['id']) ?>"><?= html_escape(trim($pub['publisher_name'])) ?></option>
					<?php endforeach; ?>
				</select>
			</div>

			<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-xs-12 mb-2" id="btn-group-search">
				<button type="submit" class="btn btn-primary text-white me-2">Cari</button>
				<button type="reset" class="btn btn-outline-primary border-2 reset-filter">Reset Filter</button>
			</div>

		</form>

		<!-- content buku rekomendasi -->
		<div id="ebook-siswa-onboarding">

			<section id="buku-terlaris" class="mt-5">
				<div class="row">
					<div class="col-6">
						<h5>Rekomendasi Hanya Untukmu <img src="assets/images/icons/emoji-sparkles.png" alt="" width="28"></h5>
					</div>

					<div class="col-6 text-end fw-bold"><span id="lihat-rekomendasi" class="text-primary border-bottom border-primary" style="cursor: pointer;">Lihat Semua</span></div>
				</div>

				<div class="row mt-4">
					<div class="col-xl-3 col-lg-3 col-md-4"><img src="assets/images/banner-best-seller-book.png" alt="" class="img-fluid"></div>
					<div class="col-xl-9 col-lg-9 col-md-8">

						<section id="thumbnail-carousel-rekomendasi" class="splide" aria-label="The carousel with thumbnails. Selecting a thumbnail will change the Beautiful Gallery carousel.">
							<div class="splide__track">
								<ul class="splide__list" id="list-buku-terlaris">
									<?php foreach ($bukuRekomendasi as $val) : ?>
										<li class="splide__slide">
											<a href="Ebook/detail/<?= $val['id'] ?>" class="text-decoration-none">
												<div class="card rounded-3 border-light-subtle p-3 m-1">
													<?php
													// buat image menjadi thumbnail
													$img = explode('.', $val['cover_img']);
													$img[0] = $img[0] . '_thumb';
													$img = implode('.', $img);
													?>
													<img class="rounded-3" src="assets/images/ebooks/cover/<?= $img ?>" alt="" width="128" height="172">
													<p class="fs-12 lh-1 mt-3 mb-2 book-publiher-card">Penerbit: <?= $val['publisher_name'] ?></p>
													<p class="fs-14 mb-0 book-title-card"><?= $val['title'] ?></p>
												</div>
											</a>
										</li>
									<?php endforeach ?>
								</ul>
							</div>
						</section>

					</div>
				</div>
			</section>

			<!-- content buku terbaru -->
			<section id="buku-terlaris" class="mt-5">
				<div class="row">
					<div class="col-6">
						<h5>Baru Saja Dirilis! <img src="assets/images/icons/emoji-rocket.png" alt="" width="28"></h5>
					</div>
					<div class="col-6 text-end fw-bold"><a href="#">Lihat Semua</a></div>
				</div>

				<div class="row mt-4">
					<div class="col-xl-3 col-lg-3 col-md-4"><img src="assets/images/banner-newest-book.png" alt="" class="img-fluid"></div>
					<div class="col-xl-9 col-lg-9 col-md-8">

						<section id="thumbnail-carousel-terbaru" class="splide" aria-label="The carousel with thumbnails. Selecting a thumbnail will change the Beautiful Gallery carousel.">
							<div class="splide__track">
								<ul class="splide__list" id="list-buku-terlaris">
									<?php foreach ($baruDirilis as $val) : ?>
										<li class="splide__slide">
											<a href="Ebook/detail/<?= $val['id'] ?>" class="text-decoration-none">
												<div class="card rounded-3 border-light-subtle p-3 m-1">
													<?php
													// buat image menjadi thumbnail
													$img = explode('.', $val['cover_img']);
													$img[0] = $img[0] . '_thumb';
													$img = implode('.', $img);
													?>
													<img class="rounded-3" src="assets/images/ebooks/cover/<?= $img ?>" alt="" width="128" height="172">
													<p class="fs-12 lh-1 mt-3 mb-2 book-publiher-card">Penerbit: <?= $val['publisher_name'] ?></p>
													<p class="fs-14 mb-0 book-title-card"><?= $val['title'] ?></p>
												</div>
											</a>
										</li>
									<?php endforeach ?>
								</ul>
							</div>
						</section>

					</div>
				</div>
			</section>

			<!-- content buku paling banyak di baca -->
			<section id="buku-terlaris" class="mt-5">
				<div class="row">
					<div class="col-6">
						<h5>Paling Banyak Dibaca</h5>
					</div>
					<div class="col-6 text-end fw-bold"><a href="#">Lihat Semua</a></div>
				</div>

				<div class="row mt-4">
					<section id="thumbnail-carousel-terbanyak-dibaca" class="splide" aria-label="The carousel with thumbnails. Selecting a thumbnail will change the Beautiful Gallery carousel.">
						<div class="splide__track">
							<ul class="splide__list" id="list-buku-terlaris">
								<?php foreach ($terbanyakDibaca as $val) : ?>
									<li class="splide__slide">
										<a href="Ebook/detail/<?= $val['book_id'] ?>" class="text-decoration-none">
											<div class="card rounded-3 border-light-subtle p-3 m-1">
												<?php
												// buat image menjadi thumbnail
												$img = explode('.', $val['cover_img']);
												$img[0] = $img[0] . '_thumb';
												$img = implode('.', $img);

												// cek file gambar ada atau tidak
												if (file_exists('assets/images/ebooks/cover/' . $img)) {
													$imgUrl = 'assets/images/ebooks/cover/' . $img;
												} else {
													$imgUrl = 'assets/images/images-placeholder.png';
												}
												?>
												<img class="rounded-3" src="<?= $imgUrl ?>" alt="" width="128" height="172">
												<p class="fs-12 lh-1 mt-3 mb-2 book-publiher-card">Penerbit: <?= $val['publisher_name'] ?></p>
												<p class="fs-14 mb-0 book-title-card"><?= $val['title'] ?></p>
											</div>
										</a>
									</li>
								<?php endforeach ?>
							</ul>
						</div>
					</section>
				</div>
			</section>

			<!-- content buku bundling -->
			<section id="buku-terlaris" class="mt-5">
				<div class="row">
					<div class="col-6">
						<h5>Paket Bundling</h5>
					</div>
					<div class="col-6 text-end fw-bold"><a href="#">Lihat Semua</a></div>
				</div>

				<div class="row mt-4">
					<section id="thumbnail-carousel-bundling" class="splide" aria-label="The carousel with thumbnails. Selecting a thumbnail will change the Beautiful Gallery carousel.">
						<div class="splide__track">
							<ul class="splide__list" id="list-buku-terlaris">
								<?php foreach ($bundlingPackages as $val) : ?>
									<li class="splide__slide">
										<a href="BundlingPackage/detail/<?= $val['id'] ?>" class="text-decoration-none">
											<div class="card rounded-3 border-light-subtle p-3 m-1">
												<?php
												// buat image menjadi thumbnail
												// $img = explode('.', $val['package_image']);
												// $img[0] = $img[0] . '_thumb';
												// $img = implode('.', $img);

												// cek file gambar ada atau tidak
												if (file_exists('assets/images/bundlings/' . $val['package_image'])) {
													$imgUrl = 'assets/images/bundlings/'.$val['package_image'];
													// $imgUrl = 'assets/images/paket-bundling.png';
												} else {
													$imgUrl = 'assets/images/bundlings.png';
												}
												?>
												<img class="rounded-3" src="<?= $imgUrl ?>" alt="" style="width:340px; height:220px; object-fit:fill;">
												<p class="fs-12 lh-1 mt-3 mb-2 book-publiher-card">Penerbit: <?= $val['publisher_name'] ?></p>
												<p class="fs-14 mb-0 book-title-card"><?= $val['package_name'] ?></p>
												<button class="btn btn-primary text-white mt-3">Detail Paket Bundling</button>
											</div>
										</a>
									</li>
								<?php endforeach ?>
							</ul>
						</div>
					</section>
				</div>
			</section>

		</div>

		<!-- content hasil pencarian buku -->
		<div id="title-pencarian" class="mt-4">

		</div>

		<div class="row mt-4" id="container-hasil-pencarian">

		</div>

		<nav aria-label="Page navigation example" class="d-flex justify-content-center">
			<div id="page"></div>
		</nav>

	</div>
</section>

<script>
	var classLevels = <?=json_encode($class_levels)?>;
</script>
