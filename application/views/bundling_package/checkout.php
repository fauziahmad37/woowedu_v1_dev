<section class="main-content">
	<div class="checkout-header text-center">
		<div class="stepper text-center">
			<div class="row justify-content-md-center">
				<div class="step active completed">
					<p class="step-label order-last order-sm-last order-md-last order-xl-last order-xxl-first">Memilih Paket Langganan</p>
					<div class="step-number order-sm-first"></div>
					<div class="border-penghubung"></div>
					<div class="dots-penghubung"></div>
				</div>
				<div class="step pending noncompleted">
					<p class="step-label order-last order-sm-last order-md-last order-xl-last order-xxl-first">Checkout</p>
					<div class="step-number"></div>
					<div class="border-penghubung"></div>
					<div class="dots-penghubung"></div>
				</div>
				<div class="step">
					<p class="step-label order-last order-sm-last order-md-last order-xl-last order-xxl-first">Pembayaran</p>
					<div class="step-number"></div>
					<div class="dots-penghubung"></div>
				</div>
				<div class="step">
					<p class="step-label order-last order-sm-last order-md-last order-xl-last order-xxl-first">Status Pembayaran</p>
					<div class="step-number"></div>
					<div class="dots-penghubung"></div>
				</div>
			</div>
		</div>

		<h5 class="fw-bold">Checkout</h5>
		<p class="fs-16" style="color: #6C757D;">Harap periksa dengan detail semua pesananmu sebelum melanjutkan ke pembayaran</p>
	</div>

	<div class="checkout-body mt-5">
		<div class="row">
			<div class="col-7">
				<div class="card p-3 rounded-4">
					<div class="row mt-2">
						<div class="col">
							<h6 class="fw-bold">Pesanan 1</h6>
						</div>
						<div class="col text-end"><i class="bi bi-shop"></i> Erlangga</div>
					</div>

					<hr style="border: 1px solid #b1b1b1; margin-top:7px;">
					<div class="row list-book-section">
						<?php //foreach ($data as $val) : 
						?>
						<div class="col-9">
							<div class="d-flex flex-row mt-2">
								<div class="">
									<input type="hidden" name="item_ids[]" value="<?= $data['id'] ?>">
									<?php
									// buat image menjadi thumbnail
									$img = explode('.', $data['package_image']);
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
									<p class="fs-12 text-body-tertiary mb-0">Penerbit: <?= $data['publisher_name'] ?></p>
									<p class="fs-14 fw-bold"><?= $data['package_name'] ?></p>

								</div>
							</div>
						</div>
						<div class="col-3 text-end align-self-end">
							<p class="fs-12 fw-bold mb-2 text-body-tertiary" style="bottom: 20px;">Total Harga</p>
							<h6 class="fw-bold">Rp <?= str_replace(',', '.', number_format($data['price'])) ?></h6>
						</div>

						<?php // endforeach 
						?>
					</div>
				</div>
			</div>
			<div class="col-5">
				<div class="card ms-3 rounded-4">
					<form id="form-checkout">

						<input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
						<input type="hidden" name="id" value="<?= $data['id'] ?>">
						<input type="hidden" name="biaya_admin" value="0">
						<input type="hidden" name="jumlah_bayar" value="<?= $data['price'] ?>">

						<div class="card-header pt-3">
							<h5 class="fw-bold">Ringkasan Pesanan</h5>
						</div>
						<div class="card-body">
							<div class="row mb-2">
								<div class="col">Total Barang</div>
								<div class="col text-end"><?= 1 ?></div>
							</div>
							<div class="row mb-2">
								<div class="col">Total Harga</div>
								<div class="col text-end" id="total_harga">Rp <?= str_replace(',', '.', number_format($data['price'])) ?></div>
							</div>
							<div class="row mb-2">
								<div class="col">Biaya Admin</div>
								<div class="col text-end">Rp 0</div>
							</div>

							<hr style="color: #a1a1a1;">

							<div class="row" style="font-size: 16px; font-weight: 600;">
								<div class="col">Jumlah Bayar</div>
								<div class="col text-end" id="gross_amount">Rp <?= str_replace(',', '.', number_format($data['price'])) ?></div>
							</div>

							<hr style="color: #a1a1a1;">
							<div class="btn-vuocher border rounded-3 p-3" style="cursor: pointer;">
								<div class="row">
									<div class="col"><img class="me-2" src="assets/images/icons/promo-coupon.png" alt="">Kupon & Voucher</div>
									<div class="col text-end"><img src="assets/images/icons/arrow-right.png" alt=""></div>
								</div>
							</div>

							<button id="simpan" name="simpan" class="mt-3 btn btn-lg btn-primary w-100 text-white fs-6">Pembayaran</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>


</section>

<script>
	$('#simpan').on('click', function() {
		let itemIds = [];
		$('input[name^="item_ids"]').each(function() {
			itemIds.push($(this).val());
		});

		let csrfToken = $('meta[name="csrf_token"]').attr('content');
		$.ajax({
			type: "POST",
			// url: "BundlingPackage/checkout",
			url: "checkout/addItem",
			data: {
				csrf_token_name: csrfToken,
				tax: 0,
				discount: 0,
				total_price: $('#total_harga').html(),
				voucher_id: null,
				biaya_admin: 0,
				gross_amount: $('#gross_amount').html(),
				simpan: 'simpan',
				item_ids: itemIds,
				item_type: 'bundle'
			},
			success: function(res) {
				if (res.success) {
					window.location.href = 'Checkout/payment/' + res.data.id;
				} else {
					Swal.fire({
						type: 'error',
						title: '<h5 class="text-danger text-uppercase">Checkout Gagal dilakukan</h5>',
					});
				}
			}
		});

		return false;
	});
</script>
