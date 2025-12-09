<section class="main-content">
	<div class="checkout-header text-center">
		<div class="stepper mt-5">
			<div class="d-flex align-middle" style="align-self: center; justify-content: center;">

				<div class="step me-2 pending noncompleted">
					<p class="p-0 m-0 step-label order-last order-sm-last order-md-last order-xl-last order-xxl-first">Checkout</p>
					<div class="step-number"></div>
					<div class="border-penghubung"></div>
					<div class="dots-penghubung"></div>
				</div>
				<div class="step me-2">
					<p class="p-0 m-0 step-label order-last order-sm-last order-md-last order-xl-last order-xxl-first">Pembayaran</p>
					<div class="step-number"></div>
					<div class="dots-penghubung"></div>
				</div>
				<div class="step me-2">
					<p class="p-0 m-0 step-label order-last order-sm-last order-md-last order-xl-last order-xxl-first">Status Pembayaran</p>
					<div class="step-number"></div>
					<div class="dots-penghubung"></div>
				</div>
			</div>
		</div>

		<div class="checkout-title mt-4">
			<h5 class="fw-bold">Checkout</h5>
			<p class="fs-16" style="color: #6C757D;">Harap periksa dengan detail semua pesananmu sebelum melanjutkan ke pembayaran</p>
		</div>
	</div>

	<div class="checkout-body mt-5">
		<div class="row">
			<div class="col-7" id="list-card-pesanan">

			</div>
			<div class="col-5">
				<div class="card ms-3 rounded-4">
					<form id="form-checkout">

						<input type="hidden" name="id" value="<?= $id ?>">
						<input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
						<input type="hidden" name="biaya_admin" value="0">
						<input type="hidden" name="jumlah_bayar">

						<div class="card-header pt-3">
							<h5 class="fw-bold">Ringkasan Pesanan</h5>
						</div>
						<div class="card-body">
							<div class="row mb-2">
								<div class="col">Total Barang</div>
								<div class="col text-end" id="total_qty"></div>
							</div>
							<div class="row mb-2">
								<div class="col">Total Harga</div>
								<div class="col text-end" id="gross_amount"></div>
							</div>
							<div class="row mb-2">
								<div class="col">Biaya Admin</div>
								<div class="col text-end" id="biaya_admin"></div>
							</div>

							<div class="row mb-2 diskon-voucher-container d-none">
								<div class="col">Diskon Kupon / Voucher</div>
								<div class="col text-end" id="voucher_discount"></div>
							</div>

							<hr style="color: #a1a1a1;">

							<div class="row" style="font-size: 16px; font-weight: 600;">
								<div class="col">Jumlah Bayar</div>
								<div class="col text-end" id="total_price">Rp </div>
							</div>

							<hr style="color: #a1a1a1;">

							<div class="btn-voucher border rounded-3 p-3" style="cursor: pointer;">
								<div class="d-flex">
									<div class="w-100"><img class="me-2" src="assets/images/icons/promo-coupon.png" alt="">Kupon & Voucher</div>
									<div class="flex-shrink-1"><img src="assets/images/icons/arrow-right.png" alt=""></div>
								</div>
							</div>

							<div class="row mt-2">
								<div class="col-6 mt-3">
									<!-- <button type="button" id="btn-cancel" onclick="history.back()" class="btn btn-lg btn-outline-secondary text-primary fw-bold w-100 fs-6">Batalkan</button> -->
									<button type="button" id="btn-cancel" onclick="cancelCheckout" class="btn btn-lg btn-clear border-primary text-primary fw-bold w-100 fs-6">Batalkan</button>
								</div>
								<div class="col-6">
									<button id="simpan" name="simpan" class="mt-3 btn btn-lg btn-primary w-100 text-white fs-6">Pembayaran</button>
								</div>
							</div>

							<div class="row mt-2">
								<div class="col mt-3 align-items-center d-flex">
									<input class="form-check-input me-2 border-primary" style="width: 20px; height: 20px;" type="checkbox" id="terms" name="terms" value="1">
									<a class="text-primary">Syarat & ketentuan</a>
								</div>
							</div>

						</div>
					</form>
				</div>
			</div>
		</div>
	</div>


</section>

<!-- Modal Voucher -->
<div class="modal fade" id="listVoucherModal" tabindex="-1" aria-labelledby="listVoucherModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content rounded-4">
			<div class="modal-header bg-primary text-white rounded-top-4">
				<h1 class="modal-title fs-5" id="listVoucherModalLabel">Kupon & Voucher</h1>
				<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body p-4">
				<div class="row" style="height: 48px;">
					<div class="col-8">
						<input type="text" class="form-control h-100" id="a_voucher_code" placeholder="Masukan kode promo">
					</div>
					<div class="col-4">
						<button class="btn btn-primary h-100 w-100" id="btn-gunakan-voucher">Gunakan</button>
					</div>
				</div>

				<hr>

				<div class="row mt-4">
					<div class="col">
						Kupon & Voucher Untukmu
					</div>

					<div class="col text-end">
						<a href="#" class="text-primary" id="btn-lihat-semua-voucher">Lihat Semua</a>
					</div>

				</div>

				<div class="row mt-4" id="list-voucher" style="max-height: 300px; overflow-y: auto;">
					<!-- List voucher akan dimuat di sini -->

				</div>

			</div>

		</div>
	</div>
</div>

<!-- Modal Paket Langganan -->
<div class="modal fade" id="listPaketLanggananModal" tabindex="-1" aria-labelledby="listPaketLanggananModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-top">
		<div class="modal-content rounded-4">
			<div class="modal-header bg-primary text-white rounded-top-4">
				<h1 class="modal-title fs-5" id="listPaketLanggananModalLabel">Paket Langganan</h1>
				<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body p-4">

				<!-- List paket langganan akan dimuat di sini -->
				<div id="list-paket-langganan" class="row row-cols-1 w-100 justify-content-center pt-3 pb-5">

					<input type="hidden" id="ebook_id" value="">
					<!-- <input type="hidden" name="publisher_id" value="9"> -->
					<input type="hidden" name="publisher_id">

					<div class="col-8 text-center mt-1">
						<h4 class="mt-4 fw-semibold">Ubah Paket Langganan</h4>
						<span>Kami menawarkan beragam paket langganan yang fleksibel dan sesuai dengan berbagai tingkat pengguna.</span>
					</div>

					<div class="col d-flex justify-content-center pt-4">
						<ul class="nav nav-pills border border-1 rounded p-2" role="tablist">
							<li class="nav-item" role="presentation">
								<a role="tab" class="nav-link active" id="buy-tab" data-bs-toggle="tab" data-bs-target="#buy-pane" aria-controls="buy-pane" aria-selected="true">Akses Selamanya</a>
							</li>
							<li class="nav-item" role="presentation">
								<a role="tab" class="nav-link" id="subscribe-tab" data-bs-toggle="tab" data-bs-target="#subscribe-pane" aria-controls="buy-pane" aria-selected="false" tabindex="-1">Langganan Bulanan</a>
							</li>
						</ul>
					</div>

					<div class="col">
						<div class="tab-content">
							<!-- tab beli langsung -->
							<div class="tab-pane fade active show" id="buy-pane" role="tabpanel" aria-labelledby="#buy-tab">
								<div class="row justify-content-center" id="list-akses-selamanya">

								</div>
							</div>
							<!-- tab subscribe -->
							<div class="tab-pane fade" id="subscribe-pane" role="tabpanel" aria-labelledby="#subscribe-tab">
								<div class="row justify-content-center row-cols-1 row-cols-md-3 g-4" id="list-akses-bulanan">
									<!-- ROW 1 -->

								</div>
							</div>
						</div>
					</div>

				</div>



			</div>

		</div>
	</div>
</div>
