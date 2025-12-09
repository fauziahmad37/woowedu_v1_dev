<style>
	/* IMAGE THUMBNAIL STYLE */
	@media (max-width: 1440px) {
		#thumbnail-main {
			width: 100%;
			max-height: 400px;
			object-fit: cover;
		}
	}

	@media (max-width: 1024px) {
		figure {
			max-height: 350px !important;
		}

		#thumbnail-main {
			width: 100%;
			height: 100%;
			object-fit: fill;
		}

		.container-thumbnail-main {
			width: 250px !important;
			height: auto;
			box-shadow: unset !important;
		}

		figcaption > label {
			margin-bottom: 10px;
		}
	}

	@media (max-width: 425px) {
		.container-thumbnail-main {
			width: 100%;
			height: auto;
			box-shadow: unset !important;
		}
		
		figcaption > label {
			margin-bottom: 10px;
		}
	}

	/*RATING STYLE  */
	@media (min-width:1024px) {
		.star-container {
			width: 25% !important;
		}
	}

	@media (max-width: 1023px) {
		.star-container {
			width: 20% !important;
		}
	}

	@media (max-width: 768px) {
		.star-container {
			width: 25% !important;
		}
	}

	@media (max-width: 425px) {
		.star-container {
			width: 35% !important;
		}
	}

</style>

<section class="explore-section section-padding container" id="section_2">

	<input type="hidden" id="ebook_id" value="<?= $ebook_id ?>" />

	<script id="ebook-detail" type="application/json">
		<?= json_encode($book) ?>
	</script>


	<?php if (isset($_SESSION['error'])) : ?>
		<div class="alert alert-danger">
			<?php
			echo $_SESSION['error']['message'];
			unset($_SESSION['error']);
			?>

		</div>
	<?php endif; ?>

	<h4>Detail Buku</h4>

	<!-- section search -->
	<div class="row mt-4 h-25">

		<div class="col-xl-4 col-lg-6 col-md-12 col-sm-12">
			<!-- <img class="img-thumbnail" src="<? //= $book['from_api'] == 1 ? html_escape($book['cover_img']) : base_url('assets/images/ebooks/cover/'.$book['cover_img']) 
													?>"/> -->
			<figure class="d-flex flex-nowrap w-full">
				<div class="overflow-hidden rounded d-inline-block shadow container-thumbnail-main">
					<img id="thumbnail-main"/>
				</div>
				<figcaption class="d-flex flex-column justify-content-around ps-4">
					<label for="thumbnail-select-1" class="lbl-thumbnail-select overflow-hidden d-inline-block rounded shadow">
						<img class="thumbnail-select" id="thumbnail-select-1-img" />
						<input type="radio" class="d-none position-absolute input-thumbnail-select" id="thumbnail-select-1" />
					</label>
					<label for="thumbnail-select-2" class="lbl-thumbnail-select overflow-hidden d-inline-block rounded shadow">
						<img class="thumbnail-select" id="thumbnail-select-2-img" />
						<input type="radio" class="d-none position-absolute input-thumbnail-select" id="thumbnail-select-2" />
					</label>
					<label for="thumbnail-select-3" class="lbl-thumbnail-select overflow-hidden d-inline-block rounded shadow">
						<img class="thumbnail-select" id="thumbnail-select-3-img" />
						<input type="radio" class="d-none position-absolute input-thumbnail-select" id="thumbnail-select-3" />
					</label>
					<label for="thumbnail-select-4" class="lbl-thumbnail-select overflow-hidden d-inline-block rounded shadow">
						<img class="thumbnail-select" id="thumbnail-select-4-img" />
						<input type="radio" class="d-none position-absolute input-thumbnail-select" id="thumbnail-select-4" />
					</label>

				</figcaption>
			</figure>
		</div>
		<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">

			<div class="section-category mb-3">
				<h6 class="mt-0 mb-2" style="color: #74788D;">Kategori:</h6>
			
				<div id="category-list">

				</div>
				
				
			</div>

			<span class="fs-6 fw-semibold publisher_name"></span>

			<h4 class="mb-4" style="line-height: 30px;" id="ebook_title"></h4>
			<span class="text-danger">
				<span class="badge badge-discount rounded-pill lh-base"></span>
				<i class="text-decoration-line-through harga-coret"></i>
			</span>
			<h4 id="ebook_price"></h4>

			<div class="d-flex mx-0 pt-3">
				<div class="col-xs-12 col-md-8 col-lg-9 pe-3">
					<?//php if ($ebook_member) : ?>
						<!-- <a href="<?//= html_escape(base_url('ebook/open_book?id=' . $book['id'])) ?>" class="btn btn-lg btn-primary w-100">Baca Ebook</a> -->
					<?//php else : ?>
						<a href="<?= html_escape(base_url('ebook/paket?book_no=' . $ebook_id)) ?>" class="btn btn-lg btn-primary w-100">Beli Sekarang</a>
					<?//php endif ?>
				</div>
				<div class="col-xs-12 col-md-4 col-lg-3 mt-sm-0 mt-xs-2">
					<button class="btn btn-lg btn-outline-primary lh-base" id="btn-wishlist" role="checkbox" aria-checked="false"><i class="bi bi-heart"></i></button>
					<button class="btn btn-lg btn-outline-primary lh-base" id="btn-cart" role="checkbox" aria-checked="false"><i class="bi bi-basket2"></i></button>
				</div>
			</div>
			<h5 class="mt-5 mb-3">Detail</h5>
			<div class="row row-cols-3">
				<div class="col mb-3">
					<h6 class="text-title mb-1">Penerbit</h6>
					<span class="fs-6 fw-semibold publisher_name"></span>
				</div>
				<div class="col mb-2">
					<h6 class="text-title mb-0">ISBN</h6>
					<span class="fs-6 fw-semibold" id="isbn"></span>
				</div>
				<div class="col mb-2">
					<h6 class="text-title mb-0">Halaman</h6>
					<span class="fs-6 fw-semibold" id="total_pages"></span>
				</div>
				<!-- <div class="col mb-2">
					<h6 class="text-title mb-0">Jenjang Pendidikan</h6>
					<span class="fs-6 fw-semibold"><?= html_escape(rtrim($book["class_level_name"], "-")) ?></span>
				</div> -->
				<div class="col mb-2">
					<h6 class="text-title mb-0">Tahun Terbit</h6>
					<span class="fs-6 fw-semibold" id="publish_year"></span>
				</div>

			</div>
			<h5 class="mt-5 mb-3">Deskripsi</h5>
			<p>
				<span class="desc-content text-justify" id="description">
					<!-- Deskripsi buku akan dimuat di sini -->
					
				</span>
				<a role="button" id="open-content" class="d-none"><small>Baca Selengkapnya</small></a>
				<br />
				<a role="button" id="close-content" class="d-none"><small>Tutup Kembali</small></a>
			</p>
		</div>

	</div>

	<!-- Rating Summary -->
	<div class="row mt-4">
		<h4>Rating Buku</h4>
		<div class="col-lg-2 col-md-4">
			<div class="card">
				<div class="card-body d-flex flex-column justify-content-center align-items-center" style="min-height: 130px">
					<h1 class="fs-1" id="value-avg">0.0</h1>
					<div id="gpa-container" class="d-flex position-relative">
						<div id="empty-star" class="d-flex align-middle position-relative">
							<span class="d-inline-flex">
								<i class="bi bi-star text-warning"></i>
								<i class="bi bi-star text-warning"></i>
								<i class="bi bi-star text-warning"></i>
								<i class="bi bi-star text-warning"></i>
								<i class="bi bi-star text-warning"></i>
							</span>

						</div>
						<div id="filled-star" class="d-flex position-absolute top-0 overflow-hidden" style="width: 0%">
							<span class="m-0 p-0 d-inline-flex">
								<i class="bi bi-star-fill text-warning"></i>
								<i class="bi bi-star-fill text-warning"></i>
								<i class="bi bi-star-fill text-warning"></i>
								<i class="bi bi-star-fill text-warning"></i>
								<i class="bi bi-star-fill text-warning"></i>
							</span>

						</div>
					</div>

					<h6>Ebook Rating</h6>
				</div>
			</div>
		</div>
		<div class="col-md-12 col-sm-12 col-xs-12 d-flex flex-nowrap">
			<table class="w-100 rating-table">
				<tbody>
					<tr>
						<td class="star-container" style="width: 12%">
							<span class="d-inline-block">
								<i class="bi bi-star-fill text-warning"></i>
								<i class="bi bi-star-fill text-warning"></i>
								<i class="bi bi-star-fill text-warning"></i>
								<i class="bi bi-star-fill text-warning"></i>
								<i class="bi bi-star-fill text-warning"></i>
							</span>
						</td>
						<td style="width: 80%">
							<div class="progress bg-warning bg-opacity-25" role="progressbar" aria-label="rate-5" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="height: 10px">
								<div class="progress-bar bg-warning" id="rate-5-prog" style="width: 0%"></div>
							</div>
						</td>
						<td id="rate-5-percent" class="text-end"></td>
					</tr>
					<tr>
						<td>
							<span class="d-inline-block">
								<i class="bi bi-star-fill text-warning"></i>
								<i class="bi bi-star-fill text-warning"></i>
								<i class="bi bi-star-fill text-warning"></i>
								<i class="bi bi-star-fill text-warning"></i>
								<i class="bi bi-star text-warning"></i>
							</span>
						</td>
						<td>
							<div class="progress bg-warning bg-opacity-25" role="progressbar" aria-label="rate-4" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="height: 10px">
								<div class="progress-bar bg-warning" id="rate-4-prog" style="width: 0%"></div>
							</div>
						</td>
						<td id="rate-4-percent" class="text-end"></td>
					</tr>
					<tr>
						<td>
							<span class="d-inline-block">
								<i class="bi bi-star-fill text-warning"></i>
								<i class="bi bi-star-fill text-warning"></i>
								<i class="bi bi-star-fill text-warning"></i>
								<i class="bi bi-star text-warning"></i>
								<i class="bi bi-star text-warning"></i>
							</span>
						</td>
						<td>
							<div class="progress bg-warning bg-opacity-25" role="progressbar" aria-label="rate-3" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="height: 10px">
								<div class="progress-bar bg-warning" id="rate-3-prog" style="width: 0%"></div>
							</div>
						</td>
						<td id="rate-3-percent" class="text-end"></td>
					</tr>
					<tr>
						<td>
							<span class="d-inline-block">
								<i class="bi bi-star-fill text-warning"></i>
								<i class="bi bi-star-fill text-warning"></i>
								<i class="bi bi-star text-warning"></i>
								<i class="bi bi-star text-warning"></i>
								<i class="bi bi-star text-warning"></i>
							</span>
						</td>
						<td>
							<div class="progress bg-warning bg-opacity-25" role="progressbar" aria-label="rate-3" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="height: 10px">
								<div class="progress-bar bg-warning" id="rate-2-prog" style="width: 0%"></div>
							</div>
						</td>
						<td id="rate-2-percent" class="text-end"></td>
					</tr>
					<tr>
						<td>
							<span class="d-inline-block">
								<i class="bi bi-star-fill text-warning"></i>
								<i class="bi bi-star text-warning"></i>
								<i class="bi bi-star text-warning"></i>
								<i class="bi bi-star text-warning"></i>
								<i class="bi bi-star text-warning"></i>
							</span>
						</td>
						<td>
							<div class="progress bg-warning bg-opacity-25" role="progressbar" aria-label="rate-3" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="height: 10px">
								<div class="progress-bar bg-warning" id="rate-1-prog" style="width: 0%"></div>
							</div>
						</td>
						<td id="rate-1-percent" class="text-end"></td>
					</tr>
				</tbody>
			</table>

		</div>
	</div>

	<!-- Ulasan Pembeli -->
	<div class="d-flex flex-nowrap align-items-center">
		<h4 class="mt-5 mb-3">Ulasan Pembeli</h4>
		<div class="ms-auto">
			<div class="dropdown" id="rating-dropdown">
				<button type="button" class="btn bg-white dropdown-toggle form-select border ps-2" data-bs-toggle="dropdown" aria-expanded="false" style="--bs-btn-padding-x: 2rem">
					Pilih Rating
				</button>
				<ul role="listbox" id="list-rating" class="dropdown-menu">
					<li role="option" data-value="" aria-selected="true"><a role="button" class="dropdown-item">Pilih Rating</a></li>
					<li role="option" data-value="1" aria-selected="false">
						<a role="button" class="dropdown-item"><i class="bi bi-star-fill text-warning"></i><span class="ms-2">Rating 1</span></a>
					</li>
					<li role="option" data-value="2" aria-selected="false">
						<a role="button" class="dropdown-item"><i class="bi bi-star-fill text-warning"></i><span class="ms-2">Rating 2</span></a>
					</li>
					<li role="option" data-value="3" aria-selected="false">
						<a role="button" class="dropdown-item"><i class="bi bi-star-fill text-warning"></i><span class="ms-2">Rating 3</span></a>
					</li>
					<li role="option" data-value="4" aria-selected="false">
						<a role="button" class="dropdown-item"><i class="bi bi-star-fill text-warning"></i><span class="ms-2">Rating 4</span></a>
					</li>
					<li role="option" data-value="5" aria-selected="false">
						<a role="button" class="dropdown-item"><i class="bi bi-star-fill text-warning"></i><span class="ms-2">Rating 5</span></a>
					</li>


				</ul>
			</div>
		</div>
	</div>

	<ul id="daftar-ulasan" class="list-group list-group-flush">

	</ul>

	<div class="d-flex justify-content-center mt-3">
		<button class="btn btn-primary" id="load-more-rating">Muat Lebih Banyak</button>
	</div>



	<div class="w-100 d-flex flex-nowrap align-items-center mt-5 mb-3">
		<h4 class="mt-4">Buku Serupa</h4>
		<a class="text-link-primary ms-auto" href="ebook/show_ebook_by_category?category=similar&ebook_id=<?= $ebook_id ?>">Lihat Semua</a>
	</div>

	<div class="row mx-0 px-0 py-2 mb-5" id="similar-books">
		<section id="thumbnail-carousel-similar-book" class="splide" aria-label="The carousel with thumbnails. Selecting a thumbnail will change the Beautiful Gallery carousel.">
			<div class="splide__track">
				<ul class="splide__list" id="list-similar-book">

				</ul>
			</div>
		</section>
	</div>

</section>

<!-- Modal Add to chat -->
<!-- Modal -->
<div class="modal fade" id="modalAddToChartSuccess" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalAddToChartSuccessLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content rounded-4" style="width: 400px;">
		
		<div class="modal-body text-center">
			<div class="my-5 d-flex justify-content-center">
				<img src="assets/images/icons/shopping-basket-fill.png" alt="" class="img-fluid img-fluid rounded-circle" style="padding:10px; width: 48px; height: 48px; background-color: #D4D1E9;">
			</div>
			<h5 style="font-size: 18px; margin-bottom: 10px;">Berhasil di tambahkan ke keranjang <img src="assets/images/icons/emoji_party popper.png" alt="" width="20" height="20" class="ms-2"></h5>
			<p style="font-size: 14px; color: #74788D;">Buku favorit kamu aman di keranjang, cek yuk semua buku mu di keranjang.</p>
		</div>

		<div class="row p-4">
			<div class="col-6">
			<button type="button" class="btn btn-primary rounded-3 text-primary bg-light w-100" data-bs-dismiss="modal">Close</button>
			</div>
			<div class="col-6">
			<a href="user?menu=cart" class="btn btn-primary rounded-3 w-100">Lihat Keranjang</a>
			</div>
		</div>

		</div>
	</div>
</div>

<!-- Modal Add to wishlist -->
<!-- Modal -->
<div class="modal fade" id="modalAddToWishlistSuccess" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalAddToWishlistSuccessLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content rounded-4" style="width: 400px;">
		
		<div class="modal-body text-center">
			<div class="my-5 d-flex justify-content-center">
				<img src="assets/images/icons/hearth-icon.png" alt="" class="img-fluid img-fluid rounded-circle" style="padding:10px; width: 48px; height: 48px; background-color: #D4D1E9;">
			</div>
			<h5 style="font-size: 18px; margin-bottom: 10px;">Berhasil di tambahkan ke wishlist <img src="assets/images/icons/emoji_party popper.png" alt="" width="20" height="20" class="ms-2"></h5>
			<p style="font-size: 14px; color: #74788D;">Buku favorit kamu aman di wishlist, cek yuk semua buku mu di wishlist.</p>
		</div>

		<div class="row p-4">
			<div class="col-6">
			<button type="button" class="btn btn-primary rounded-3 text-primary bg-light w-100" data-bs-dismiss="modal">Close</button>
			</div>
			<div class="col-6">
			<a href="user?menu=wishlist" class="btn btn-primary rounded-3 w-100">Lihat wishlist</a>
			</div>
		</div>

		</div>
	</div>
</div>
