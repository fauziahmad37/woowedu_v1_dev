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

		<input type="hidden" id="transaction_number" value="<?= $transaction_number ?>">

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
	let countUpdateSettlement = 0;
	let timeInterval = 10000; // 10 seconds

	getPaymentStatus();

	const paymentStatusInterval = setInterval((e) => {
		getPaymentStatus();
	}, timeInterval);

	function getPaymentStatus() {
		let transactionNumber = $('#transaction_number').val();

		let ajax = new XMLHttpRequest();
		ajax.open('GET', 'api/api_ebook/getTransactionStatus?transaction_number=' + transactionNumber, true);
		ajax.onreadystatechange = function() {
			if (ajax.readyState == 4 && ajax.status == 200) {
				let response = JSON.parse(ajax.responseText);
				res = response.data;

				let transactionTime = (res.transaction_status == 'pending') ? '-' : res.transaction_time;

				$('#transaction-status').html('');
				$('#img-status').html('');
				$('#order-id').html('');
				$('#payment-method').html('');
				$('#transaction-time').html('');
				$('#gross_amount').html('');
				$('#btn-lanjutkan-pembayaran').html('');

				$('#order-id').append(`${res.order_id}`);
				$('#payment-method').append(`${res.payment_type}`);
				$('#transaction-time').append(`${transactionTime}`);
				$('#gross_amount').append(`Rp ${parseInt(res.gross_amount).toLocaleString()}`);

				// JIKA RESPONSE DATA NYA FALSE DAN STATUS NYA FALSE UPDATE STATUS MENJADI CANCEL
				if (response.data == false && response.status == false) {
					let xhr = new XMLHttpRequest();
					xhr.open('GET', 'api/api_ebook/updateTransactionStatus?transaction_number=' + transactionNumber + '&status=cancel&payment_method=&settlement_time=' + moment().format('YYYY-MM-DD HH:mm:ss', true), true);
					xhr.onreadystatechange = function() {
						if (xhr.readyState == 4 && xhr.status == 200) {
							let updateRes = JSON.parse(xhr.responseText);
							if (updateRes.status) {
								console.log('Transaction status updated to cancel');

								$('#transaction-status').append(`<div class="row text-center">
										<h5>
											<img class="" class="text-center" src="assets/images/icons/time-round-icon.png" alt="" style="height: 52px; width:52px;">
										</h5>
										<h6>TRANSAKSI DI BATALKAN</h6>
									</div>`);

								$('#detailTransaksi').html('');
							} else {
								console.error('Failed to update transaction status:', updateRes.message);
							}
						}
					};
					xhr.send();
				}

				// JIKA RESPONSE DATA transaction_status EXPIRE  & STATUS TRUE UPDATE STATUS MENJADI EXPIRE
				if (res.transaction_status == 'expire' && response.status == true) {
					updateTransactionStatus(res, 'expire');
				}

				if (res.transaction_status == 'pending') {
					$('#transaction-status').append(`<div class="row text-center">
						<h5>
							<img class="" class="text-center" src="assets/images/icons/clock.png" alt="" style="height: 52px; width:52px;">
						</h5>
						<h6>TRANSAKSI PENDING</h6>
						<h4 class="fw-bold">Rp ${parseInt(res.gross_amount).toLocaleString()}</h4>
					</div>`);

					$('#img-status').append(`<img src="assets/images/icons/clock.png" alt="" width="20">Pending`);
					$('#btn-lanjutkan-pembayaran').append(`
							<a onclick="getTransactionDetail('${res.order_id}')" class="btn btn-lg btn-primary float-end mt-4 me-3">Lanjutkan Pembayaran</a>
							<button class="btn btn-lg border-primary text-primary float-end mt-4 me-3" onclick="cancelTransaction('${res.order_id}')">Batalkan Pembayaran</button>
						`);

				}

				if (res.transaction_status == 'settlement') {

					// JIKA STATUS NYA SETTLEMENT MAKA LAKUKAN UPDATE API TRANSACTION DATA
					if (countUpdateSettlement == 0) {
						let xhr2 = new XMLHttpRequest();
						xhr2.open('GET', 'api/api_ebook/updateTransactionStatus?transaction_number=' + res.order_id + '&status=' + res.transaction_status + '&payment_method=' + res.acquirer + '&settlement_time=' + res.transaction_time, true);
						xhr2.onreadystatechange = function() {
							if (xhr2.readyState == 4 && xhr2.status == 200) {
								let updateRes = JSON.parse(xhr2.responseText);
								if (updateRes.status) {
									console.log('Transaction data updated successfully');

									// lakukan update count settlement agar tidak melakukan update berulang kali
									countUpdateSettlement++;
								} else {
									console.error('Failed to update transaction data:', updateRes.message);
								}
							}
						};
						xhr2.send();

					}

					// UBAH WAKTU INTERVAL MENJADI 1 JAM
					timeInterval = 3600000; // 1 hour
					clearInterval(paymentStatusInterval); // Clear the previous interval
					setInterval(getPaymentStatus, timeInterval); // Set a new interval with the updated time

					// END UPDATE API TRANSACTION DATA

					$('#transaction-status').append(`<div class="row text-center">
						<h5>
							<img class="" class="text-center" src="assets/images/icons/success.png" alt="" style="height: 52px; width:52px;">
						</h5>
						<h6>TRANSAKSI SUKSES</h6>
						<h4 class="fw-bold">Rp ${numberWithCommas(parseInt(res.gross_amount))}</h4>
					</div>`);

					$('#img-status').append(`<img src="assets/images/icons/success.png" alt="" width="20">Sukses`);
					$('#btn-lanjutkan-pembayaran').append(`<a href="user?menu=laporan_ebook&tab=belum_dibaca" class="btn btn-lg btn-primary float-end mt-4 me-3">Lihat Ebook</a>`);
				}

				if (res.transaction_status == 'cancel') {
					$('#transaction-status').append(`<div class="row text-center">
						<h5>
							<img class="" class="text-center" src="assets/images/icons/time-round-icon.png" alt="" style="height: 52px; width:52px;">
						</h5>
						<h6>TRANSAKSI DI BATALKAN</h6>
						<h4 class="fw-bold">Rp ${numberWithCommas(parseInt(res.gross_amount))}</h4>
					</div>`);

					$('#img-status').append(`<img src="assets/images/icons/time-round-icon.png" alt="" width="20">Dibatalkan`);
					$('#btn-lanjutkan-pembayaran').append(`<a href="user" class="btn btn-lg btn-primary float-end mt-4 me-3">Lihat History</a>`);
				}

				if (res.transaction_status == 'expire') {
					updateTransactionStatus(res, 'expire');

					$('#transaction-status').append(`<div class="row text-center">
						<h5>
							<img class="" class="text-center" src="assets/images/icons/time-round-icon.png" alt="" style="height: 52px; width:52px;">
						</h5>
						<h6>TRANSAKSI KADALUARSA</h6>
						<h4 class="fw-bold">Rp ${numberWithCommas(parseInt(res.gross_amount))}</h4>
					</div>`);

					$('#img-status').append(`<img src="assets/images/icons/time-round-icon.png" alt="" width="20">Expired`);
					$('#btn-lanjutkan-pembayaran').append(`<a href="user" class="btn btn-lg btn-primary float-end mt-4 me-3">Lihat History</a>`);
				}


			}

		};
		ajax.send();
	}

	function updateTransactionStatus(res, status) {
		let xhr = new XMLHttpRequest();
		xhr.open('GET', 'api/api_ebook/updateTransactionStatus?transaction_number=' + res.order_id + '&status=' + status + '&payment_method=&settlement_time=' + moment().format('YYYY-MM-DD HH:mm:ss', true), true);
		xhr.onreadystatechange = function() {
			if (xhr.readyState == 4 && xhr.status == 200) {
				let updateRes = JSON.parse(xhr.responseText);
				if (updateRes.status) {
					console.log('Transaction status updated to ' + res.transaction_status);
				} else {
					console.error('Failed to update transaction status:', updateRes.message);
				}
			}
		};
		xhr.send();
	}


	function cancelTransaction(orderId) {
		$.ajax({
			type: "GET",
			// url: "checkout/cancelTransaction",
			url: "api/api_ebook/updateTransactionStatus",
			data: {
				transaction_number: orderId,
				status: 'cancel',
				payment_method: '',
				settlement_time: moment().format('YYYY-MM-DD HH:mm:ss', true)
			},
			success: function(res) {
				res = JSON.parse(res);
				if (res.status) {
					Swal.fire({
						type: 'success',
						title: "Berhasil!",
						text: 'Transaksi berhasil di batalkan',
						icon: "success"
					});

					setTimeout(function() {
						location.href = 'user?menu=riwayat_pembelian';
					}, 2000);
				}
			}
		});
	}

	function getTransactionDetail(orderId) {
		let xhr = new XMLHttpRequest();
		xhr.open('GET', 'api/api_ebook/getTransactionDetail?transaction_number=' + orderId, true);
		xhr.onreadystatechange = function() {
			if (xhr.readyState == 4 && xhr.status == 200) {
				let res = JSON.parse(xhr.responseText);
				if (res.status) {
					let callback_url = res.data.payment_link;
					localStorage.setItem('redirect_url', callback_url);
					window.location.href = 'BundlingPackage/payment?callback_url=' + encodeURIComponent(callback_url);
				} else {
					Swal.fire({
						type: 'error',
						title: "Gagal!",
						text: res.message,
						icon: "error"
					});
				}
				// window.location.href = 'BundlingPackage/payment?transaction_number=' + orderId;
			}
		};
		xhr.send();
	}

	function numberWithCommas(x) {
		return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
	}
</script>
