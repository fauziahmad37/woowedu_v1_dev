<style>
	/* GLOBAL STYLES FOR MOBILE */
	@media (max-width: 425px){
		/* Header Icon */
		.header-icon {
			width: 24px;
			height: 24px;
		}
	}
	
	/* MODAL ON BOARDING STEP */
	#onBoardingSteps .modal-body {
		height: 410px !important;
	}

	#onBoardingSteps .carousel-indicators {
		bottom: -50px !important;
	}

	@media (max-width: 425px) {
		#onBoardingSteps .modal-content {
			width: 80% !important;
		}

		#onBoardingSteps .modal-dialog {
			justify-content: center;
		}

		#onBoardingSteps .modal-body {
			height: 310px !important;
		}

		#onBoardingSteps .carousel-inner {
			height: 100% !important;
		}

		#carouselExampleCaptions {
			/* height: 305px !important; */
		}

		#onBoardingSteps .carousel-indicators {
			bottom: -10px !important;
		}
	}

	/* BANNER */
	#carouselBanner {
		height: 350px;
	}

	#carouselExampleCaptions {
		height: 361px;
	}

	#carouselBanner .carousel-item img {
		object-fit: fill;
	}

	/* MOBILE VIEW */
	@media (max-width: 576px) {
		#carouselBanner {
			height: 175px;
		}

		#carouselExampleCaptions {
			height: 300px;
		}

		#carouselExampleCaptions .carousel-caption {
			display: block !important;
		}

	}

	/* HIGHLIGHT */
	img.show-story {
		cursor: pointer;
	}

	#highlightModal .card-header,
	#highlightModal .my-card {
		cursor: pointer;
	}

	#highlightModal .my-card.next a,
	#highlightModal .my-card.prev a {
		pointer-events: none
	}

	@media (max-width: 425px) {
		#list-highlight-fitur-platform img {
			height: 60px !important;
			width: 60px !important;
			border-width: 3px !important;
		}

		#list-highlight-fitur-platform p {
			font-size: 12px !important;
			color: #3A3C47;
		}
	}

	/* MODAL HIGHLIGHT */
	#highlightModal .image-ebook-story {
		height: 390px;
		margin-top: 25px;
	}

	@media (max-width: 425px) {
		/* #thumbnail-highlight-fitur-platform > div {
			display: flex;
			overflow-x: auto;
		}

		#thumbnail-highlight-fitur-platform > div > .col {
			margin-right: 20px;
		}

		#thumbnail-highlight-fitur-platform > div > .col p {
			display: ruby-text;
		} */

		#highlight-fitur-platform>.row h5 {
			font-size: 14px;
		}
	}

	/* PILIHAN KATEGORI MENARIK */
	@media (max-width: 425px) {
		/* #list-pilihan-kategori-menarik > div {
			display: flex;
			overflow-x: auto;
		} */

		#pilihan-kategori-menarik {
			margin-top: 30px !important;
		}

		#pilihan-kategori-menarik>.row h5 {
			font-size: 14px;
		}

		#list-pilihan-kategori-menarik img {
			height: 60px !important;
			width: 60px !important;
		}

		#list-pilihan-kategori-menarik a > div {
			padding: 0px !important;
		}

		#list-pilihan-kategori-menarik h5 {
			font-size: 12px !important;
			color: #3A3C47;
		}
	}

	/* PROMO TERBATAS */
	@media (max-width: 425px) {
		.banner-promo-terbatas {
			display: none;
		}

		.promo-terbatas-header h5 {
			font-size: 16px;
			font-weight: 600;
		}

		.lihat-semua {
			font-size: 12px;
			font-weight: 400;
		}

		#promo-terbatas {
			margin-top: 15px !important;
		}
	}

	/* PENERBIT UNGGULAN */
	#list-penerbit-unggulan .publisher-name {
		/* only 2 line text */
		overflow: hidden;
		text-overflow: ellipsis;
		display: -webkit-box;
		-webkit-line-clamp: 2;
		-webkit-box-orient: vertical;
	}

	@media (max-width: 425px) {
		#penerbit-unggulan h5 {
			font-size: 14px;
		}

		#list-penerbit-unggulan .card { 
			width: 136px !important;
			height: 125px !important;
		}

		#list-penerbit-unggulan img {
			height: 40px !important;
			max-width: 100px !important;
		}

		#list-penerbit-unggulan .publisher-name {
			font-size: 12px !important;
			font-weight: 400 !important;
		}
	}

	/* LIST EBOOK REKOMENDASI GURU */
	@media (max-width: 425px) {
		#ebook-list-rekomendasi-guru>.row>.col-6 h5 {
			font-size: 14px;
		}

		#list-ebook-rekomendasi-guru .card-item {
			width: 275px;
			font-size: 12px;
		}

		#list-ebook-rekomendasi-guru .card-item p.fw-bold {
			font-size: 14px !important;
		}

		#lihat-rekomendasi-guru {
			font-size: 12px;
			font-weight: 400;
		}

		#ebook-list-rekomendasi-guru h5 {
			font-size: 14px;
		}

	}

	/* BUKU REKOMENDASI */

	#list-ebook-rekomendasi .card {
		height: 95%;
	}

	#list-ebook-rekomendasi .card img {
		width: 100% !important;
		height: 165px !important;
		object-fit: fill;
	}

	@media (max-width: 425px) {
		.banner-buku-rekomendasi {
			display: none;
		}

		#buku-rekomendasi .header-buku-rekomendasi {
			display: flex !important;
			justify-content: space-between !important;
		}

		#buku-rekomendasi H5 {
			font-size: 14px;
		}

		#lihat-rekomendasi {
			font-size: 12px;
			font-weight: 400;
		}

		#list-ebook-rekomendasi .card {
			height: 95%;
		}

		#list-ebook-rekomendasi .card img {
			width: 100% !important;
			height: 165px !important;
			object-fit: fill;
		}
	}

	/* BUKU BARU RILIS */

	#list-newest-books .card {
		height: 95%;
	}

	#list-newest-books .card img {
		width: 100% !important;
		height: 165px !important;
		object-fit: fill;
	}

	@media (max-width: 425px) {
		#buku-terlaris img.banner-buku-terbaru {
			display: none !important;
		}

		#buku-terlaris H5 {
			font-size: 14px;
		}

		/* #buku-terlaris :nth-child(1) > img {
			width: 24px;
			height: 24px;
		} */

		#buku-terlaris :nth-child(1) a {
			font-size: 12px;
			font-weight: 400;
		}

		#buku-terlaris .header-buku-terlaris {
			display: flex !important;
			justify-content: space-between !important;
		}

		#list-newest-books .card {
			height: 95%;
		}

		#list-newest-books .card img {
			width: 100% !important;
			height: 165px !important;
			object-fit: fill;
		}
	}

	/* PALING BANYAK DI BACA / MOST READ */
	@media (max-width: 425px) {
		#most-read H5 {
			font-size: 14px;
		}

		#most-read a {
			font-size: 12px;
			font-weight: 400;
		}
	}

	/* PAKET BUNDLING */
	@media (max-width: 425px) {
		#paket-bundling H5 {
			font-size: 14px;
		}

		#paket-bundling a {
			font-size: 12px;
			font-weight: 400;
		}

		#list-paket-bundling .card {
			width: 300px;
		}

		#list-paket-bundling .card img {
			width: 100% !important;
		}
	}
</style>

<section class="explore-section section-padding" id="section_2">

	<input type="hidden" id="is_new_user" value="<?= $isNewUser ?>">
	<input type="hidden" id="class_id" value="<?= isset($class_id) ? $class_id : '' ?>">

	<div class="container">

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


		<div id="ebook-siswa-onboarding">

			<!-- section carousel banner -->
			<div id="carouselBanner" class="carousel slide mt-5" data-bs-ride="carousel">
				<div class="carousel-indicators" style="margin-bottom: -3rem;">
					<button type="button" data-bs-target="#carouselBanner" data-bs-slide-to="1" class="bg-primary rounded-circle active" aria-current="true" aria-label="Slide 1" style="height:10px; width: 10px;"></button>
				</div>
				<div class="carousel-inner rounded-5">
					<div class="carousel-item rounded-5 active" data-bs-interval="5000">
						<a>
							<img src="assets/images/banner-empty.png" class="d-block w-100 rounded-5" style="height: 400px">
						</a>
					</div>
				</div>
				<button class="carousel-control-prev" type="button" data-bs-target="#carouselBanner" data-bs-slide="prev">
					<span class="carousel-control-prev-icon" aria-hidden="true"></span>
					<span class="visually-hidden">Previous</span>
				</button>
				<button class="carousel-control-next" type="button" data-bs-target="#carouselBanner" data-bs-slide="next">
					<span class="carousel-control-next-icon" aria-hidden="true"></span>
					<span class="visually-hidden">Next</span>
				</button>
			</div>

			<!-- section highlight fitur platform -->
			<section id="highlight-fitur-platform" class="mt-5 pt-4">
				<div class="row mb-3">
					<div class="">
						<h5>Highlight Fitur Platform <img class="header-icon" src="assets/images/icons/emoji-sparkles.png" alt="" width="28"></h5>
					</div>
				</div>

				<section id="thumbnail-highlight-fitur-platform" class="splide" aria-label="The carousel with thumbnails. Selecting a thumbnail will change the Beautiful Gallery carousel.">
					<div class="splide__track">
						<ul class="splide__list" id="list-highlight-fitur-platform">

						</ul>
					</div>
				</section>

				<!-- <section id="thumbnail-highlight-fitur-platform">
					<div class="d-flex text-center">
						<?php //$a = 5;
						// for ($i = 1; $i <= $a; $i++) :
						?>
							<div class="col" style="">
								<img class="show-story rounded-circle bg-primary border border-5 " data-id="1" src="" alt="" style="width:100px; height: 100px; object-fit: contain;">
								<p class="text-body-secondary mt-3">Baca Tanpa Batas</p>
							</div>
						<?php // endfor; 
						?>
					</div>
				</section> -->

			</section>

			<!-- section pilihan kategori menarik -->
			<section id="pilihan-kategori-menarik" class="mt-5">
				<div class="row mb-3">
					<div class="">
						<h5>Pilihan Kategori Menarik <img class="header-icon" src="assets/images/icons/emoji-sparkles.png" alt="" width="28"></h5>
					</div>
				</div>

				<section>
					<!-- <div class="row text-center" id="list-pilihan-kategori-menarik"> -->
					<section id="thumbnail-pilihan-kategori-menarik" class="splide" aria-label="The carousel with thumbnails. Selecting a thumbnail will change the Beautiful Gallery carousel.">
						<div class="splide__track">
							<ul class="splide__list" id="list-pilihan-kategori-menarik">

							</ul>
						</div>
					</section>

					<?php // $kategoriMenarik = [
					// ['title' => 'Populer', 'image' => 'assets/images/icons/populer.png'],
					// ['title' => 'Baru', 'image' => 'assets/images/icons/baru.png'],
					// ['title' => 'Edukasi', 'image' => 'assets/images/icons/edukasi.png'],
					// ['title' => 'Komik', 'image' => 'assets/images/icons/komik.png'],
					// ['title' => 'Lainnya', 'image' => 'assets/images/icons/lainnya.png'],
					// ]; 
					?>
					<?php // foreach ($kategoriMenarik as $kategori) : 
					?>
					<!-- <div class="col mb-3">
								<a href="ebook/show_ebook_by_category?category=<? //= urlencode($kategori['title']) 
																				?>" class="text-decoration-none">
									<div class="p-3">
										<img src="<? //= $kategori['image'] 
													?>" alt="" width="100" height="100">
										<h5 class="text-body-dark mt-3"><? //= $kategori['title'] 
																		?></h5>
									</div>
								</a>
							</div> -->
					<?php // endforeach; 
					?>
					<!-- </div> -->
				</section>

			</section>

			<!-- Section Promo Terbatas -->
			<section id="promo-terbatas" class="mt-5 rounded-5 p-4" style="background-color: #F8D7DA;">
				<div class="row promo-terbatas-header">
					<div class="col-6">
						<h5>Promo Terbatas </h5>
					</div>

					<div class="col-6 text-end fw-bold"><a href="ebook/show_promo_terbatas?category=promo" id="lihat-promo-terbatas" class="text-primary border-primary lihat-semua" style="cursor: pointer;">Lihat Semua</a></div>
				</div>

				<div class="row mt-4">
					<div class="col-xl-3 col-lg-4 col-md-4 banner-promo-terbatas"><img src="assets/images/icons/flash-sale.png" alt="" class="img-fluid" style="height: 360px; width: 240px;"></div>
					<div class="col-xl-9 col-lg-8 col-md-8">

						<section id="thumbnail-carousel-promo-terbatas" class="splide" aria-label="The carousel with thumbnails. Selecting a thumbnail will change the Beautiful Gallery carousel.">
							<div class="splide__track">
								<ul class="splide__list" id="list-promo-terbatas">

								</ul>
							</div>
						</section>

					</div>
				</div>
			</section>

			<!-- Penerbit Unggulan -->
			<section id="penerbit-unggulan" class="mt-5">
				<div class="row">
					<div class="col-12">
						<h5>Penerbit Unggulan <img class="header-icon" src="assets/images/icons/emoji-sparkles.png" alt="" width="28"></h5>
					</div>

				</div>

				<div class="row mt-4">
					<div class="col-12">
						<section id="thumbnail-carousel-penerbit-unggulan" class="splide" aria-label="The carousel with thumbnails. Selecting a thumbnail will change the Beautiful Gallery carousel.">
							<div class="splide__track">
								<ul class="splide__list" id="list-penerbit-unggulan">
									<?php $a = 5;
									for ($i = 1; $i <= $a; $i++) :
									?>

										<li class="splide__slide">
											<a href="Ebook/detail/" class="text-decoration-none">
												<div class="card rounded-3 border-light-subtle p-3 m-1">
													<img class="rounded-3" src="assets/images/image-loading-book-card.png" alt="" width="128" height="172">
													<p class="fs-12 lh-1 mt-3 mb-2 book-publiher-card">Penerbit: </p>
													<p class="fs-14 mb-0 book-title-card"></p>
												</div>
											</a>
										</li>
									<?php endfor; ?>
								</ul>
							</div>
						</section>
					</div>
				</div>
			</section>

			<?php if ($_SESSION['user_level'] == 4) : ?>
				<!-- EBOOK LIST REKOMENDASI GURU -->
				<section id="ebook-list-rekomendasi-guru" class="mt-5">
					<div class="row">
						<div class="col-8">
							<h5>Ebook list rekomendasi guru <img class="header-icon" src="assets/images/icons/emoji-sparkles.png" alt="" width="28"></h5>
						</div>

						<div class="col-4 text-end fw-bold">
							<a href="ebook/ebook_list_guru" class="text-decoration-none"><span id="lihat-rekomendasi-guru" class="text-primary border-bottom border-primary" style="cursor: pointer;">Lihat Semua</span></a>
						</div>
					</div>

					<div class="row mt-4">
						<div class="col-12">

							<section id="thumbnail-carousel-rekomendasi-guru" class="splide" aria-label="The carousel with thumbnails. Selecting a thumbnail will change the Beautiful Gallery carousel.">
								<div class="splide__track">
									<ul class="splide__list" id="list-ebook-rekomendasi-guru">
										<?php $a = 5;
										for ($i = 1; $i <= $a; $i++) :
										?>

											<li class="splide__slide">
												<a href="Ebook/detail/" class="text-decoration-none">
													<div class="card rounded-3 border-light-subtle p-3 m-1">
														<img class="rounded-3" src="assets/images/image-loading-book-card.png" alt="" width="128" height="172">
														<p class="fs-12 lh-1 mt-3 mb-2 book-publiher-card">Penerbit: </p>
														<p class="fs-14 mb-0 book-title-card"></p>
													</div>
												</a>
											</li>
										<?php endfor; ?>
									</ul>
								</div>
							</section>

						</div>
					</div>
				</section>

			<?php endif; ?>

			<!-- AKTIFITAS EBOOK SAYA -->
			<?php if ($_SESSION['user_level'] == 3) : ?>
				<div id="aktivitas-ebook-saya-container" class="mt-5">
					<a href="ebook/ebook_list_guru" class="text-decoration-none text-body-dark">
						<h5>Aktifitas Ebook Saya</h5>

						<div class="card-aktifitas-ebook-saya border border-1 rounded-4 mt-5 border-primary p-4" style="width: 300px; height: 165px;">
							<div class="bg-primary-subtle text-center rounded-3" style="height: 48px; width: 48px;">
								<img src="assets/images/icons/bookmark-3-fill.png" alt="" width="24" height="24" style="margin-top: 10px;">
							</div>

							<p class="fw-bold" style="font-size: 20px; margin-top: 15px; margin-bottom: 5px;">Ebook List Saya</p>
							<p class="mb-0" style="font-size: 12px; color: #59595A;">Rangkuman Ebook yang dimiliki dan sudah di jadikan dalam satu daftar list</p>
						</div>
					</a>
				</div>
			<?php endif; ?>

			<!-- content buku rekomendasi -->
			<section id="buku-rekomendasi" class="mt-4">
				<div class="row header-buku-rekomendasi">
					<div class="col-6">
						<h5>Rekomendasi Untukmu <img class="header-icon" src="assets/images/icons/emoji-sparkles.png" alt="" width="28"></h5>
					</div>

					<div class="col-6 text-end fw-bold"><span id="lihat-rekomendasi" class="text-primary border-bottom border-primary" style="cursor: pointer;">Lihat Semua</span></div>
				</div>

				<div class="row mt-4">
					<div class="col-xl-3 col-lg-3 col-md-4 banner-buku-rekomendasi"><img src="assets/images/banner-best-seller-book.png" alt="" class="img-fluid"></div>
					<div class="col-xl-9 col-lg-9 col-md-8">

						<section id="thumbnail-carousel-rekomendasi" class="splide" aria-label="The carousel with thumbnails. Selecting a thumbnail will change the Beautiful Gallery carousel.">
							<div class="splide__track">
								<ul class="splide__list" id="list-ebook-rekomendasi">
									<?php $a = 5;
									for ($i = 1; $i <= $a; $i++) :
									?>

										<li class="splide__slide">
											<a href="Ebook/detail/" class="text-decoration-none">
												<div class="card rounded-3 border-light-subtle p-3 m-1">
													<img class="rounded-3" src="assets/images/image-loading-book-card.png" alt="" width="128" height="172">
													<p class="fs-12 lh-1 mt-3 mb-2 book-publiher-card">Penerbit: </p>
													<p class="fs-14 mb-0 book-title-card"></p>
												</div>
											</a>
										</li>
									<?php endfor; ?>
								</ul>
							</div>
						</section>

					</div>
				</div>
			</section>

			<!-- content buku terbaru -->
			<section id="buku-terlaris" class="mt-5">
				<div class="row header-buku-terlaris">
					<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6">
						<h5>Baru Saja Dirilis! <img class="header-icon" src="assets/images/icons/emoji-rocket.png" alt="" width="28"></h5>
					</div>
					<div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-xs-6 text-end fw-bold"><a href="Ebook/show_ebook_by_category?category=baru_dirilis">Lihat Semua</a></div>
				</div>

				<div class="row mt-4">
					<div class="col-xl-3 col-lg-3 col-md-4"><img src="assets/images/banner-newest-book.png" alt="" class="img-fluid banner-buku-terbaru"></div>
					<div class="col-xl-9 col-lg-9 col-md-8">

						<section id="thumbnail-carousel-terbaru" class="splide" aria-label="The carousel with thumbnails. Selecting a thumbnail will change the Beautiful Gallery carousel.">
							<div class="splide__track">
								<ul class="splide__list" id="list-newest-books">
									<?php $a = 5;
									for ($i = 1; $i <= $a; $i++) :
									?>
										<li class="splide__slide">
											<a href="Ebook/detail/" class="text-decoration-none">
												<div class="card rounded-3 border-light-subtle p-3 m-1">
													<img class="rounded-3" src="assets/images/image-loading-book-card.png" alt="" width="128" height="172">
													<p class="fs-12 lh-1 mt-3 mb-2 book-publiher-card">Penerbit: </p>
													<p class="fs-14 mb-0 book-title-card"></p>
												</div>
											</a>
										</li>
									<?php endfor; ?>
								</ul>
							</div>
						</section>

					</div>
				</div>
			</section>

			<!-- content buku paling banyak di baca -->
			<section id="most-read" class="mt-5">

				<div class="row">
					<div class="col-6">
						<h5>Paling Banyak Dibaca</h5>
					</div>
					<div class="col-6 text-end fw-bold"><a href="Ebook/show_ebook_by_category?category=terbanyak_dibaca">Lihat Semua</a></div>
				</div>

				<div class="row mt-4">
					<section id="thumbnail-carousel-terbanyak-dibaca" class="splide" aria-label="The carousel with thumbnails. Selecting a thumbnail will change the Beautiful Gallery carousel.">
						<div class="splide__track">
							<ul class="splide__list" id="list-most-read">
								<?php $a = 5;
								for ($i = 1; $i <= $a; $i++) :
								?>
									<li class="splide__slide">
										<a href="Ebook/detail/" class="text-decoration-none">
											<div class="card rounded-3 border-light-subtle p-3 m-1">
												<img class="rounded-3" src="assets/images/image-loading-book-card.png" alt="" width="128" height="172">
												<p class="fs-12 lh-1 mt-3 mb-2 book-publiher-card">Penerbit: </p>
												<p class="fs-14 mb-0 book-title-card"></p>
											</div>
										</a>
									</li>
								<?php endfor; ?>
							</ul>
						</div>
					</section>
				</div>
			</section>

			<!-- content buku bundling -->
			<section id="paket-bundling" class="mt-5">
				<div class="row">
					<div class="col-6">
						<h5>Paket Bundling</h5>
					</div>
					<div class="col-6 text-end fw-bold"><a href="BundlingPackage">Lihat Semua</a></div>
				</div>

				<div class="row mt-4">
					<section id="thumbnail-carousel-paket-bundling" class="splide" aria-label="The carousel with thumbnails. Selecting a thumbnail will change the Beautiful Gallery carousel.">
						<div class="splide__track">
							<ul class="splide__list" id="list-paket-bundling">
								<?php $a = 5;
								for ($i = 1; $i <= $a; $i++) :
								?>
									<li class="splide__slide">
										<a href="Ebook/detail/" class="text-decoration-none">
											<div class="card rounded-3 border-light-subtle p-3 m-1">
												<img class="rounded-3" src="assets/images/image-loading-book-card.png" alt="" width="128" height="172">
												<p class="fs-12 lh-1 mt-3 mb-2 book-publiher-card">Penerbit: </p>
												<p class="fs-14 mb-0 book-title-card"></p>
											</div>
										</a>
									</li>
								<?php endfor; ?>
							</ul>
						</div>
					</section>
				</div>
			</section>

			<!-- Content Testimonial -->
			<section id="testimonial" class="mt-5 p-4" style="border-radius: 20px; background-color: #140E4A;">
				<h5 class="text-white text-center mb-4">Kepuasan Para Pembeli</h5>
				<div class="row mt-5" id="testimonial-container">
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

		<!-- Content Ebook Footer -->
		<section id="ebook-footer" class="mt-3 p-4 rounded-top-5 text-white" style="background-color: #140E4A;">
			<div class="row my-3">
				<div class="col-xl-8 col-lg-6 col-md-6 col-sm-12 col-xs-12">
					<img src="assets/images/icons/logo-woowedu-putih.png" alt="" width="160" height="70" class="mb-3">
					<p class="">Platform Inovatif untuk Membantu</p>
					<p class="">Pembelajaran Berbasis Digital di Sekolah</p>
				</div>

				<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-xs-12">

					<div class="row">
						<div class="col-6">
							<p style="color: #98A2B3;">Kategori</p>
							<ul class="list-unstyled">
								<li><a href="Ebook/show_ebook_by_category?category=populer" class="text-white">Populer</a></li>
								<li><a href="Ebook/show_ebook_by_category?category=baru_dirilis" class="text-white">Baru</a></li>
								<li><a href="Ebook/show_ebook_by_category?category=edukasi" class="text-white">Edukasi</a></li>
								<li><a href="Ebook/show_ebook_by_category?category=komik" class="text-white">Komik</a></li>
								<li><a href="Ebook/show_ebook_by_category?category=lainnya" class="text-white">Lainnya</a></li>
							</ul>
						</div>
						<div class="col-6">
							<p style="color: #98A2B3;">Lainnya</p>
							<ul class="list-unstyled">
								<li><a href="https://www.facebook.com/woowedu" target="_blank" class="text-white">Tentang</a></li>
								<li><a href="https://www.instagram.com/woowedu.id/" target="_blank" class="text-white">Bantuan</a></li>
								<li><a href="https://www.youtube.com/@woowedu" target="_blank" class="text-white">Kontak</a></li>
								<li><a href="https://woowedu.id" target="_blank" class="text-white">Kebijakan</a></li>
							</ul>
						</div>
					</div>

				</div>


			</div>

			<div class="text-center text-white mb-3">
				<p class="mb-0">© 2025 WoowEdu. All rights reserved.</p>
			</div>

		</section>

	</div>
</section>

<!-- Modal Highlight -->
<div class="modal fade" id="highlightModal" tabindex="-1" aria-labelledby="highlightModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-fullscreen">
		<div class="modal-content" style="background-color: rgba(0, 0, 0, 0.9);">
			<div class="modal-header border-0">
				<h1 class="modal-title fs-5 text-white" id="highlightModalLabel">WoowEdu</h1>
				<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body" style="height: 100%;">
				<div class="card-carousel">
					<!-- <div class="my-card">1</div>
						<div class="my-card">2</div>
						<div class="my-card">3</div>
						<div class="my-card">4</div>
						<div class="my-card">5</div>
						<div class="my-card">6</div>
						<div class="my-card">7</div>
						<div class="my-card">8</div>
						<div class="my-card">9</div> -->
				</div>
			</div>

		</div>
	</div>
</div>

<!-- Modal Melihat On Boarding Steps -->
<!-- Modal -->
<div class="modal fade" id="onBoardingSteps" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="onBoardingStepsLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content" style="border-radius: 20px;">

			<div class="modal-body">
				<div id="carouselExampleCaptions" class="carousel slide">
					<div class="carousel-indicators mt-3" style="bottom: -20px;">
						<button class="bg-primary rounded-circle active" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" aria-current="true" aria-label="Slide 1" style="width: 10px; height:10px;"></button>
						<button class="bg-primary rounded-circle" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2" style="width: 10px; height:10px;"></button>
						<button class="bg-primary rounded-circle" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3" style="width: 10px; height:10px;"></button>
					</div>
					<div class="carousel-inner text-dark">
						<div style="top:0px;" class="carousel-item active">
							<img src="assets/images/icons/step-1.png" class="d-block" style="height: unset;" alt="...">
							<div class="carousel-caption text-dark d-none d-md-block position-relative top-0" style="left:0;">
								<h5><img class="me-2" src="assets/images/icons/search.png" alt="" style="width: 18px !important; height: 18px !important;">Pilih Buku Favoritmu</h5>
								<p>Temukan buku yang pas buat kebutuhan atau minatmu.</p>
							</div>
						</div>
						<div style="top:0px;" class="carousel-item">
							<img src="assets/images/icons/step-2.png" class="d-block" style="height: unset;" alt="...">
							<div class="carousel-caption text-dark d-none d-md-block position-relative top-0" style="left:0;">
								<h5><img class="me-2" src="assets/images/icons/cc.png" alt="" style="width: 18px !important; height: 18px !important;">Pilih Buku Favoritmu</h5>Bayar Sesuai Pilihanmu</h5>
								<p>Gunakan metode pembayaran favoritmu, mudah dan cepat.</p>
							</div>
						</div>
						<div style="top:0px;" class="carousel-item">
							<img src="assets/images/icons/step-3.png" class="d-block" style="height: unset;" alt="...">
							<div class="carousel-caption text-dark d-none d-md-block position-relative top-0" style="left:0;">
								<h5><img class="me-2" src="assets/images/icons/search.png" alt="" style="width: 18px !important; height: 18px !important;">Pilih Buku Favoritmu</h5>Akses dan Baca Bukumu</h5>
								<p>Buku langsung tersedia di akunmu, siap dibaca kapan saja dan dimana saja</p>
							</div>
						</div>
					</div>
					<!-- <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
						<span class="carousel-control-prev-icon" aria-hidden="true"></span>
						<span class="visually-hidden">Previous</span>
					</button>
					<button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
						<span class="carousel-control-next-icon" aria-hidden="true"></span>
						<span class="visually-hidden">Next</span>
					</button> -->
				</div>
			</div>

			<div class="modal-footer border-0">
				<!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
				<button type="button" class="btn btn-primary w-100 btn-selanjutnya" style="border-radius: 8px;" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">Selanjutnya</button>
			</div>
		</div>
	</div>
</div>

<script>
	var classLevels = <?= json_encode($class_levels) ?>;
</script>

<script>
	const MediaQueryList = window.matchMedia("(max-width: 425px)");

	function handleTabletChange(e) {
		if (e.matches) {
			// If media query matches
			// document.querySelector("#list-pilihan-kategori-menarik").classList.remove("row");
			// document.querySelector("#list-pilihan-kategori-menarik").classList.add("flex");

			// document.querySelector("#thumbnail-highlight-fitur-platform > div").classList.remove("row");
			// document.querySelector("#thumbnail-highlight-fitur-platform > div").classList.add("flex");

			document.querySelector("#buku-terlaris .header-buku-terlaris").classList.remove("row");
			document.querySelector("#buku-rekomendasi .header-buku-rekomendasi").classList.remove("row");
			document.querySelector(".header-buku-rekomendasi :nth-child(1)").classList.remove("col-6");
			document.querySelector(".header-buku-rekomendasi :nth-child(2)").classList.remove("col-6");

		}
	}

	// Daftarkan listener untuk mendengarkan perubahan
	MediaQueryList.addListener(handleTabletChange);

	// Panggil fungsi sekali untuk kondisi awal
	handleTabletChange(MediaQueryList);
</script>
