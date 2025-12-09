<style>
	/* Style untuk deskripsi yang terpotong */
	.desc-clamp {
		display: -webkit-box;
		-webkit-line-clamp: 3;
		/* Batas jumlah baris */
		-webkit-box-orient: vertical;
		overflow: hidden;
	}

	.left-side {
		padding-right: 30px;
	}

	.right-side {
		padding-left: 30px;
	}

	#thumbnail-main {
		width: 100%;
		height: 100%;
		object-fit: cover;
	}
</style>

<section class="explore-section section-padding" id="section_2">

	<script id="ebook-detail" type="application/json">
		<?= json_encode($data) ?>
	</script>

	<input type="hidden" name="item_id" value="<?= $id ?>">
	<input type="hidden" name="publisher_id" value="">

	<div class="container">

		<?php if (isset($_SESSION['error'])) : ?>
			<div class="alert alert-danger">
				<?php
				echo $_SESSION['error']['message'];
				unset($_SESSION['error']);
				?>

			</div>
		<?php endif; ?>

		<!-- section search -->
		<div class="row mt-4 h-25">

			<div class="col-12 col-lg-6 left-side">
				<!-- <img class="img-thumbnail" src="<? //= $book['from_api'] == 1 ? html_escape($book['cover_img']) : base_url('assets/images/ebooks/cover/'.$book['cover_img']) 
														?>"/> -->
				<figure class="d-flex flex-nowrap w-full">
					<div class="overflow-hidden rounded d-inline-block shadow">						
						<img id="thumbnail-main" src="" />
					</div>
				</figure>
			</div>
			<div class="col-12 col-lg-6 right-side">

				<!-- <input type="hidden" name="item_id" value="<? //= $package['id'] 
																?>"> -->
				<h6 class="text-title" id="publisher_name"></h6>
				<h4 class="mb-4 fw-bold" id="package_name"></h4>
				<span class="text-danger">
					<!-- <span class="badge badge-discount rounded-pill lh-base" id="percentage">10%</span> -->
					<i class="text-decoration-line-through" id="normal_price"></i>
				</span>
				<h4 id="main_price" class="fw-bold mt-2"></h4>
				<div class="d-flex mx-0 pt-3">
					<div class="col-xs-12 col-md-8 col-lg-9 pe-3">
						<!-- <a href="<?//= html_escape(base_url('BundlingPackage/checkout/' . $id)) ?>" class="btn btn-lg btn-primary w-100">Beli Paket Bundling</a> -->
						<a onclick="checkout(<?= $id ?>)" class="btn btn-lg btn-primary w-100">Beli Paket Bundling</a>
					</div>
					<div class="col-xs-12 col-md-4 col-lg-3 mt-sm-0 mt-xs-2">
						<button class="btn btn-lg btn-outline-primary lh-base <?= ($wishlist == true) ? 'active' : '' ?>" id="btn-wishlist" role="checkbox" aria-checked="false"><i class="bi bi-heart"></i></button>
						<button class="btn btn-lg btn-outline-primary lh-base <?= ($cart == true) ? 'active' : '' ?>" id="btn-cart" role="checkbox" aria-checked="false"><i class="bi bi-basket2"></i></button>
					</div>
				</div>

				<h5 class="mt-5 mb-3">Deskripsi Paket Bundling</h5>
				<p class="card-text desc-clamp fs-16" id="product_description" style="color: #6C757D;">
					<!-- Deskripsi akan dimasukkan di sini melalui JavaScript -->
				</p>
				<a id="toggle-description" class="fs-12 fw-bold">Baca Selengkapnya</a>

				<h5 class="mt-5 mb-3">Ebook Yang Kamu Dapat</h5>

				<div class="list-book-section" id="list-book-section">
					
				</div>

			</div>

		</div>

		<div class="w-100 d-flex flex-nowrap align-items-center mt-5 mb-3">
			<h4 class="mt-4">Paket Bundling Lainnya</h4>
			<a href="BundlingPackage" class="text-link-primary ms-auto">Lihat Semua</a>
		</div>

		<section id="paket-bundling" class="mt-5">

			<div class="row mt-4">
				<section id="thumbnail-carousel-paket-bundling" class="splide" aria-label="The carousel with thumbnails. Selecting a thumbnail will change the Beautiful Gallery carousel.">
					<div class="splide__track">
						<ul class="splide__list" id="list-paket-bundling">

						</ul>
					</div>
				</section>
			</div>
		</section>

</section>

<script>
	// Mengambil elemen yang diperlukan
	const description = document.getElementById('product-description');
	const toggleButton = document.getElementById('toggle-description');

	// Event listener untuk tombol deskripsi
	toggleButton.addEventListener('click', function() {
		// Jika deskripsi masih terpotong, tampilkan seluruhnya
		if (description.classList.contains('desc-clamp')) {
			description.classList.remove('desc-clamp');
			toggleButton.textContent = 'Sembunyikan';
		} else { // Jika deskripsi sudah penuh, sembunyikan kembali
			description.classList.add('desc-clamp');
			toggleButton.textContent = 'Baca Selengkapnya';
		}
	});


	// image drag nya di matikan untuk mengoptimalkan slider nya
	let imgFluid = document.getElementsByClassName('img-fluid');
	for (let i = 0; i < imgFluid.length; i++) {
		imgFluid[i].setAttribute('draggable', false);
	}

	// AJAX FITUR WISHLIST
	$('#btn-wishlist').on('click', function(e) {
		postWishlist();
	});

	function postWishlist() {
		$.ajax({
			type: "POST",
			url: "wishlist/post",
			data: {
				csrf_token_name: $('meta[name="csrf_token"]')[0].content,
				item_type: 'bundling',
				item_id: $('input[name="item_id"]').val(),
			},
			dataType: "JSON",
			success: function(response) {
				if (response) {
					$('meta[name="csrf_token"]')[0].content = response.csrf_token;

					if (response.isLiked) {
						$('#btn-wishlist')[0].classList.add('active')
						Swal.fire({
							type: 'success',
							title: "Berhasil!",
							text: response.message,
							icon: "success"
						});
					} else {
						if (response.limit) {
							$('#btn-wishlist')[0].classList.remove('active')
							Swal.fire({
								type: 'info',
								title: "<h4>Maaf, Wishlist anda sudah penuh!</h4>",
								text: response.message,
								// icon: "info",
								showConfirmButton: false,
								iconHtml: '<img src="assets/images/icons/wishlist-round.png">',
								footer: '<a class="btn btn-primary" href="user/index">Atur Wishlist</a>'
							});
						} else {
							$('#btn-wishlist')[0].classList.remove('active')
							Swal.fire({
								type: 'info',
								iconHtml: '<img src="assets/images/icons/wishlist-round.png">',
								title: "Peringatan!",
								text: response.message,
								// icon: "info"
							});
						}
					}
				}
			}
		});
	}

	// AJAX FITUR CART
	$('#btn-cart').on('click', function(e) {
		postCart(e);
	});

	function postCart(e) {
		// Cek apakah tombol cart sudah aktif
		let url;
		if (e.currentTarget.classList.contains('active')) {
			url = "api/Api_shopping_cart/removeFromShoppingCart";
		} else {
			url = "api/Api_shopping_cart/addToShoppingCart";
		}

		$.ajax({
			type: "POST",
			url: url,
			data: {
				csrf_token_name: $('meta[name="csrf_token"]')[0].content,
				item_type: 'bundling',
				ebook_id: $('input[name="item_id"]').val(),
			},
			dataType: "JSON",
			success: function(response) {
				if (response) {
					$('meta[name="csrf_token"]')[0].content = response.csrf_token;

					if (response.isLiked) {
						$('#btn-cart')[0].classList.add('active')
						Swal.fire({
							type: 'success',
							title: "Berhasil!",
							text: response.message,
							icon: "success"
						});
					} else {
						$('#btn-cart')[0].classList.remove('active')
						Swal.fire({
							type: 'info',
							title: "Peringatan!",
							text: response.message,
							icon: "info"
						});
					}
				}
			}
		});
	}
</script>
