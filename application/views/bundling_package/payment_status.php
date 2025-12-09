<section id="payment-status">
	<div class="payment-title text-center mt-5">
		<div class="stepper text-center">
			<div class="row justify-content-md-center">
				<div class="step active completed">
					<p class="step-label order-last order-sm-last order-md-last order-xl-last order-xxl-first">Memilih Paket Langganan</p>
					<div class="step-number order-sm-first"></div>
					<div class="border-penghubung"></div>
					<div class="dots-penghubung"></div>
				</div>
				<div class="step active completed">
					<p class="step-label order-last order-sm-last order-md-last order-xl-last order-xxl-first">Checkout</p>
					<div class="step-number"></div>
					<div class="border-penghubung"></div>
					<div class="dots-penghubung"></div>
				</div>
				<div class="step active completed">
					<p class="step-label order-last order-sm-last order-md-last order-xl-last order-xxl-first">Pembayaran</p>
					<div class="step-number"></div>
					<div class="border-penghubung"></div>
					<div class="dots-penghubung"></div>
				</div>
				<div class="step pending noncompleted">
					<p class="step-label order-last order-sm-last order-md-last order-xl-last order-xxl-first">Status Pembayaran</p>
					<div class="step-number"></div>
					<div class="dots-penghubung"></div>
				</div>
			</div>
		</div>

		<h5 class="fw-bold">Status Pembayaran</h5>
		<p class="fs-16" style="color: #6C757D;">Periksa status pembayaran anda untuk mengetahui perkembangan dari transaksi anda</p>

	</div>

	<div class="payment-body mt-5">

		<div id="transaction-status">

		</div>

		<div class="accordion accordion-flush mt-5" id="detailTransaksi">
			<div class="accordion-item">
				<h2 class="accordion-header">
					<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
						<h5 class="fw-bold">Detail Transaksi</h5>
					</button>
				</h2>
				<div id="flush-collapseOne" class="accordion-collapse collapse" data-bs-parent="#detailTransaksi">
					<div class="accordion-body">
						<div class="row mb-2">
							<div class="col-6">Order ID</div>
							<div class="col-6 text-end" id="order-id"></div>
						</div>
						<div class="row mb-2">
							<div class="col-6">Metode Pembayaran</div>
							<div class="col-6 text-end" id="payment-method"></div>
						</div>
						<div class="row mb-2">
							<div class="col-6">Status Pembayaran</div>
							<div class="col-6 text-end">
								<div id="img-status">
								</div>


							</div>
						</div>
						<div class="row mb-2">
							<div class="col-6">Waktu Pembayaran</div>
							<div class="col-6 text-end" id="transaction-time"></div>
						</div>
						<div class="row mb-2">
							<div class="col-6">Jumlah Bayar</div>
							<div class="col-6 text-end" id="gross_amount"></div>
						</div>
					</div>

				</div>


				<div id="btn-lanjutkan-pembayaran">

				</div>

			</div>

		</div>
	</div>
</section>

<script>
	getPaymentStatus();

	setInterval((e) => {
		getPaymentStatus();
	}, 5000);

	function getPaymentStatus() {
		$.ajax({
			type: "GET",
			async: false,
			// url: "BundlingPackage/getTransactionStatus",
			url: "checkout/getPaymentStatusMidtrans",
			data: {
				transaction_number: (localStorage.getItem('transaction_number')) ? localStorage.getItem('transaction_number') : '<?= $trx_number ?>'
			},
			success: function(response) {
				console.log(response);
				let res = response;

				$('#transaction-status').html('');
				$('#img-status').html('');
				$('#order-id').html('');
				$('#payment-method').html('');
				$('#transaction-time').html('');
				$('#gross_amount').html('');
				$('#btn-lanjutkan-pembayaran').html('');

				$('#order-id').append(`${res.order_id}`);
				$('#payment-method').append(`${res.payment_type}`);
				$('#transaction-time').append(`${res.transaction_time}`);
				$('#gross_amount').append(`${res.gross_amount}`);

				if (res.transaction_status == 'pending') {
					$('#transaction-status').append(`<div class="row text-center">
						<h5>
							<img class="" class="text-center" src="assets/images/icons/clock.png" alt="" style="height: 52px; width:52px;">
						</h5>
						<h6>TRANSAKSI PENDING</h6>
						<h4 class="fw-bold">${res.gross_amount}</h4>
					</div>`);

					$('#img-status').append(`<img src="assets/images/icons/clock.png" alt="" width="20">Pending`);
					$('#btn-lanjutkan-pembayaran').append(`
						<a href="${res.payment_link}" class="btn btn-lg btn-primary float-end mt-4 me-3">Lanjutkan Pembayaran</a>
						<button class="btn btn-lg border-primary text-primary float-end mt-4 me-3" onclick="cancelTransaction('${res.order_id}')">Batalkan Pembayaran</button>
						`);

				}

				if (res.transaction_status == 'settlement') {
					$('#transaction-status').append(`<div class="row text-center">
						<h5>
							<img class="" class="text-center" src="assets/images/icons/success.png" alt="" style="height: 52px; width:52px;">
						</h5>
						<h6>TRANSAKSI SUKSES</h6>
						<h4 class="fw-bold">${res.gross_amount}</h4>
					</div>`);

					$('#img-status').append(`<img src="assets/images/icons/success.png" alt="" width="20">Sukses`);
					$('#btn-lanjutkan-pembayaran').append(`<a href="user" class="btn btn-lg btn-primary float-end mt-4 me-3">Lihat Ebook</a>`);
				}

				if (res.transaction_status == 'cancel') {
					$('#transaction-status').append(`<div class="row text-center">
						<h5>
							<img class="" class="text-center" src="assets/images/icons/time-round-icon.png" alt="" style="height: 52px; width:52px;">
						</h5>
						<h6>TRANSAKSI DI BATALKAN</h6>
						<h4 class="fw-bold">${res.gross_amount}</h4>
					</div>`);

					$('#img-status').append(`<img src="assets/images/icons/time-round-icon.png" alt="" width="20">Dibatalkan`);
					$('#btn-lanjutkan-pembayaran').append(`<a href="user" class="btn btn-lg btn-primary float-end mt-4 me-3">Lihat History</a>`);
				}

				if (res.transaction_status == 'expire') {
					$('#transaction-status').append(`<div class="row text-center">
						<h5>
							<img class="" class="text-center" src="assets/images/icons/time-round-icon.png" alt="" style="height: 52px; width:52px;">
						</h5>
						<h6>TRANSAKSI KADALUARSA</h6>
						<h4 class="fw-bold">${res.gross_amount}</h4>
					</div>`);

					$('#img-status').append(`<img src="assets/images/icons/time-round-icon.png" alt="" width="20">Expired`);
					$('#btn-lanjutkan-pembayaran').append(`<a href="user" class="btn btn-lg btn-primary float-end mt-4 me-3">Lihat History</a>`);
				}
			}
		});
	}

	function cancelTransaction(orderId) {
		$.ajax({
			type: "GET",
			url: "checkout/cancelTransaction",
			data: {
				transaction_number: orderId
			},
			success: function(res) {
				if (res.fraud_status == "accept") {
					Swal.fire({
						type: 'success',
						title: "Berhasil!",
						text: 'Transaksi berhasil di batalkan',
						icon: "success"
					});
				}
			}
		});
	}
</script>
