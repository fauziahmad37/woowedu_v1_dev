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
						<?php
						// buat image menjadi thumbnail
						$img = explode('.', $package['package_image']);
						// $img[0] = $img[0] . '_thumb';
						$img = implode('.', $img);

						// cek file gambar ada atau tidak
						if (file_exists('assets/images/bundlings/' . $img)) {
							$imgUrl = 'assets/images/bundlings/' . $img;
							// $imgUrl = 'assets/images/paket-bundling.png';
						} else {
							$imgUrl = 'assets/images/paket-bundling.png';
						}
						?>
						<img id="thumbnail-main" src="<?= $imgUrl ?>" />
					</div>
				</figure>
			</div>
			<div class="col-12 col-lg-6 right-side">

				<input type="hidden" name="item_id" value="<?= $package['id'] ?>">
				<h6 class="text-title"><?= $package['publisher_name'] ?></h6>
				<h4 class="mb-4 fw-bold"><?= html_escape($package['package_name']) ?></h4>
				<span class="text-danger">
					<span class="badge badge-discount rounded-pill lh-base">10%</span>
					<i class="text-decoration-line-through">Rp. <?= html_escape("150.000") ?></i>
				</span>
				<h4><strong>Rp <?= str_replace(',', '.', number_format($package['price'])) ?></strong></h4>
				<div class="d-flex mx-0 pt-3">
					<div class="col-xs-12 col-md-8 col-lg-9 pe-3">
						<a href="<?= html_escape(base_url('BundlingPackage/checkout/' . $package['id'])) ?>" class="btn btn-lg btn-primary w-100">Beli Paket Bundling</a>
					</div>
					<div class="col-xs-12 col-md-4 col-lg-3 mt-sm-0 mt-xs-2">
						<button class="btn btn-lg btn-outline-primary lh-base <?= ($wishlist == true) ? 'active' : '' ?>" id="btn-wishlist" role="checkbox" aria-checked="false"><i class="bi bi-heart"></i></button>
						<button class="btn btn-lg btn-outline-primary lh-base <?= ($cart == true) ? 'active' : '' ?>" id="btn-cart" role="checkbox" aria-checked="false"><i class="bi bi-basket2"></i></button>
					</div>
				</div>

				<h5 class="mt-5 mb-3">Deskripsi Paket Bundling</h5>
				<p class="card-text desc-clamp fs-16" id="product-description" style="color: #6C757D;">
					<?= html_escape(trim($package['description'])) ?>
				</p>
				<a id="toggle-description" class="fs-12 fw-bold">Baca Selengkapnya</a>

				<h5 class="mt-5 mb-3">Ebook Yang Kamu Dapat</h5>

				<div class="list-book-section">
					<?php foreach ($package_items as $val) : ?>
						<div class="d-flex flex-row mt-2">
							<div class="">
								<?php
								// buat image menjadi thumbnail
								$img = explode('.', $val['cover_img']);
								$img[0] = $img[0] . '_thumb';
								$img = implode('.', $img);

								// cek file gambar ada atau tidak
								if (file_exists('assets/images/ebooks/cover/' . $img)) {
									$imgUrl = 'assets/images/ebooks/cover/' . $img;
									// $imgUrl = 'assets/images/paket-bundling.png';
								} else {
									$imgUrl = 'assets/images/paket-bundling.png';
								}
								?>
								<img src="<?= $imgUrl ?>" alt="" width="60" height="90">
							</div>
							<div class="ms-2 position-relative w-100">
								<p class="fs-12 text-body-tertiary mb-0">Penerbit: <?= $val['publisher_name'] ?></p>
								<p class="fs-14 fw-bold"><?= $val['title'] ?></p>
								<a class="position-absolute bottom-0 end-0 fs-12 fw-bold" href="ebook/detail/<?= $val['ebook_id'] ?>">Lihat detail buku</a>
							</div>
						</div>
					<?php endforeach ?>
				</div>

			</div>

		</div>

		<div class="w-100 d-flex flex-nowrap align-items-center mt-5 mb-3">
			<h4 class="mt-4">Paket Bundling Lainnya</h4>
			<a class="text-link-primary ms-auto">Lihat Semua</a>
		</div>

		<div id="group_paket_bundling" class="row flex-nowrap overflow-auto mt-5 pb-5 mx-1 list-ebook" style="overflow-x:hidden !important;">
			<?php foreach ($packages as $package) : ?>

				<div class="card rounded-4 me-4 pb-3" style="width: 233px; display:inline-block; float:none; padding-bottom:50px !important;">
					<?php
					// buat image menjadi thumbnail
					// $img = explode('.', $package['package_image']);
					// $img[0] = $img[0] . '_thumb';
					// $img = implode('.', $img);

					// cek file gambar ada atau tidak
					if (file_exists('assets/images/bundlings/' . $package['package_image'])) {
						$imgUrl = 'assets/images/bundlings/' . $package['package_image'];
						// $imgUrl = 'assets/images/paket-bundling.png';
					} else {
						$imgUrl = 'assets/images/paket-bundling.png';
					}
					?>
					<img class="img-fluid mt-2 rounded-3" src="<?= $imgUrl ?>" alt="">
					<p class="publisher-name fs-12 mt-3 text-body-secondary">Penerbit: <?= $package['publisher_name'] ?></p>
					<p class="book-title-card mt-2 fs-16 fw-bold"><?= $package['package_name'] ?></p>

					<a href="BundlingPackage/detail/<?= $package['id'] ?>" class="btn btn-primary btn-lg position-absolute mb-2 fs-12" style="bottom:5px; width: 90%;">Detail Paket Bundling
					</a>
				</div>

			<?php endforeach ?>
		</div>

</section>

<script>
	// Mengambil elemen yang diperlukan
	const description = document.getElementById('product-description');
	const toggleButton = document.getElementById('toggle-description');

	// Event listener untuk tombol
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


	// KONTEN SLIDER 

	contentSlider('#group_paket_bundling');

	function contentSlider(element) {
		const slider = document.querySelector(element);
		let isDown = false;
		let startX;
		let scrollLeft;

		slider.addEventListener('mousedown', (e) => {
			isDown = true;
			slider.classList.add('active');
			startX = e.pageX - slider.offsetLeft;
			scrollLeft = slider.scrollLeft;
		});
		slider.addEventListener('mouseleave', () => {
			isDown = false;
			slider.classList.remove('active');
		});
		slider.addEventListener('mouseup', () => {
			isDown = false;
			slider.classList.remove('active');
		});
		slider.addEventListener('mousemove', (e) => {
			if (!isDown) return;
			e.preventDefault();
			const x = e.pageX - slider.offsetLeft;
			const walk = (x - startX) * 3; //scroll-fast
			slider.scrollLeft = scrollLeft - walk;
		});

	}

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
						if(response.limit){
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
						}else{
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
		postCart();
	});

	function postCart() {
		$.ajax({
			type: "POST",
			url: "ShopingCart/post",
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
