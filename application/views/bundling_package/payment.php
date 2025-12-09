<style>
	body div.modal-iframe {
		background-color: #FFF;
	}
</style>

<section id="main-content">
	<div class="checkout-header text-center">
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
				<div class="step pending noncompleted">
					<p class="step-label order-last order-sm-last order-md-last order-xl-last order-xxl-first">Pembayaran</p>
					<div class="step-number"></div>
					<div class="border-penghubung"></div>
					<div class="dots-penghubung"></div>
				</div>
				<div class="step">
					<p class="step-label order-last order-sm-last order-md-last order-xl-last order-xxl-first">Status Pembayaran</p>
					<div class="step-number"></div>
					<div class="border-penghubung"></div>
					<div class="dots-penghubung"></div>
				</div>
			</div>
		</div>

		<h5 class="fw-bold">Pembayaran</h5>
		<p class="fs-16" style="color: #6C757D;">Harap perhatikan metode pembayaran yang anda pilih dan jumlah bayar yang harus anda bayarkan</p>

	</div>
</section>

<!-- TODO: Remove ".sandbox" from script src URL for production environment. Also input your client key in "data-client-key" -->
<!-- <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-vaoPLqzIF6aDGQX9"></script> -->
<script src="assets/js/midtrans.js" data-client-key="SB-Mid-client-vaoPLqzIF6aDGQX9"></script>
<script type="text/javascript">
	// document.getElementById('pay-button').onclick = function() {
	// SnapToken acquired from previous step
	snap.pay('<?= $snapToken ?>', {
		// Optional
		onSuccess: function(result) {
			/* You may add your own js here, this is just example */
			document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
			console.log(result);
		},
		// Optional
		onPending: function(result) {
			/* You may add your own js here, this is just example */
			document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
			console.log(result);
		},
		// Optional
		onError: function(result) {
			/* You may add your own js here, this is just example */
			document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
		}
	});

	setInterval((e)=>{
		$.ajax({
			type: "get",
			async: false,
			url: "BundlingPackage/getTransactionStatus",
			data: {
				transaction_number: '<?=$transaction_number?>'
			},
			success: function (response) {
				let res = JSON.parse(response);
				console.log(res);
				if(res.transaction_status == 'settlement'){
					window.location.href = 'BundlingPackage/statusPembayaran?data='+JSON.stringify(res);
				}

				if(res.transaction_status == 'pending'){
					setTimeout(()=>{
						window.location.href = 'BundlingPackage/statusPembayaran?data='+JSON.stringify(res);
					}, 60000);
				}
			}
		});
	},5000);
</script>
