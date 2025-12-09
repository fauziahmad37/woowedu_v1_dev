<style>
	.nav-group {
		display: flex;
	}

	.nav-group-item {
		border-style: solid;
		border-top: none;
		border-left: none;
		border-right: none;
		border-bottom: 2px solid #e0e0e0;
	}

	.nav-group-item.active {
		border-bottom: 2px solid #007bff;
	}

	.nav-group-item a {
		display: block;
		padding: 10px 20px;
		text-decoration: none;
		color: #333;
	}

	.nav-content-item {
		display: none;
	}

	.img-kosong {
		width: 400px;
	}

	/* make icon in title search */
	#title {
		background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8" /><line x1="21" y1="21" x2="16.65" y2="16.65" /></svg>');
		background-repeat: no-repeat;
		background-position: right 8px center;
		background-size: 15px;
		background-color: #f9f9f9;
	}

	#table-menunggu-pembayaran,
	#table-pesanan-selesai,
	#table-pesanan-dibatalkan,
	#table-semua-pesanan {
		width: 100% !important;
	}

	table thead th {
		background-color: #281B93;
		color: white;
	}

	.dataTables_length,
	#table-pesanan-selesai_filter,
	#table-menunggu-pembayaran_filter,
	#table-semua-pesanan_filter,
	#table-pesanan-dibatalkan_filter {
		display: none;
	}

	.btn-paket-bundling {
		margin-left: auto;
		margin-top: 10px;
	}

	.round-total-data {
		background-color: #007bff;
		color: white;
		border-radius: 50%;
		padding: 2px 7px;
		margin-left: 10px;
	}

	.card {
		border: 1px solid #D1D2D9 !important;
		border-radius: 10px;
		overflow: hidden;
	}

	.product-image {
		width: 80px;
		height: 120px;
		border-radius: 10px;
	}

	#table-semua-pesanan td,
	#table-menunggu-pembayaran td,
	#table-pesanan-selesai td,
	#table-pesanan-dibatalkan td {
		padding: 0px;
	}
</style>

<section class="main">
	<h4 class="fw-bold">Daftar Pesanan</h4>
	<h5>Silahkan periksa seluruh pesanan yang masuk</h5>

	<!-- tab group horizontal nav -->
	<div class="nav-group mt-4">
		<div class="nav-group-item active">
			<a class="btn btn-clear" data="all">Semua Pesanan
				<span class="round-total-data" id="round-total-semua-pesanan"></span>
			</a>
		</div>
		<div class="nav-group-item">
			<a class="btn btn-clear" data="menunggu-pembayaran">Menunggu Pembayaran
				<span class="round-total-data" id="round-total-menunggu-pembayaran"></span>
			</a>
		</div>
		<div class="nav-group-item">
			<a class="btn btn-clear" data="pesanan-selesai">Selesai
				<span class="round-total-data" id="round-total-pesanan-selesai"></span>
			</a>
		</div>
		<div class="nav-group-item">
			<a class="btn btn-clear" data="pesanan-dibatalkan">Dibatalkan
				<span class="round-total-data" id="round-total-pesanan-dibatalkan"></span>
			</a>
		</div>
	</div>

	<!-- nav group content -->
	<div class="nav-content">

		<div class="filter-group mt-3 mb-4">
			<!-- form filter inline -->
			<form class="form-inline">

				<!-- urutkan -->
				<div class="form-group mr-3">
					<label for="sort" class="sr-only">Urutkan</label>
					<select class="form-control" id="sort">
						<option value="">Urutkan</option>
						<option value="0-desc">Harga Tertinggi</option>
						<option value="0-asc">Harga Terendah</option>
					</select>
				</div>

				<!-- jenis pembelian -->
				<div class="form-group mr-3">
					<label for="jenis-pembelian" class="sr-only">Jenis Pembelian</label>
					<select class="form-control" id="jenis-pembelian">
						<option value="">Jenis Pembelian</option>
						<option value="ebook">Satuan</option>
						<option value="bundle">Bundling</option>
					</select>
				</div>

				<!-- pilih tanggal -->
				<div class="form-group mr-3">
					<label for="pilih-tanggal" class="sr-only">Pilih Tanggal</label>
					<input type="date" class="form-control" id="pilih-tanggal" max="<?= date('Y-m-d') ?>">
				</div>

				<!-- button cari -->
				<button type="submit" id="search" class="btn btn-primary">Cari</button>

				<!--  button reset filter -->
				<button type="reset" class="btn btn-clear" style="border: 1px solid #281B93; margin-left: 10px;">Reset</button>

			</form>

		</div>

		<div class="nav-content-item active" data="all">
			<!-- content here -->
			<table class="table table-clear w-100" id="table-semua-pesanan">
				<thead class="d-none">
					<tr>
						<th>Cover</th>
						<th>Jenis</th>
						<th>Status</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
		<div class="nav-content-item" data="menunggu-pembayaran">
			<!-- content here -->
			<table class="table table-clear w-100" id="table-menunggu-pembayaran">
				<thead class="d-none">
					<tr>
						<th>Cover</th>
						<th>Jenis</th>
						<th>Status</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
		<div class="nav-content-item" data="pesanan-selesai">
			<!-- content here -->
			<table class="table table-clear w-100" id="table-pesanan-selesai">
				<thead class="d-none">
					<tr>
						<th>Cover</th>
						<th>Jenis</th>
						<th>Status</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
		<div class="nav-content-item" data="pesanan-dibatalkan">
			<!-- content here -->
			<table class="table table-clear w-100" id="table-pesanan-dibatalkan">
				<thead class="d-none">
					<tr>
						<th>Cover</th>
						<th>Jenis</th>
						<th>Status</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>

	</div>

</section>

<!-- modal detail -->
<div class="modal fade" id="modal-detail" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h5 class="modal-title text-white fs-5" id="exampleModalLabel">Rincian Total Pendapatan</h5>
				<button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body p-4">
				<div class="row">

					<div class="col-12">

						<!-- total penjualan -->
						<div class="d-flex justify-content-between">
							<h5 class="fw-bold">Total Penjualan</h5>
							<p class="total-penjualan"></p>
						</div>

						<!-- total ebook -->
						<div class="d-flex justify-content-between">
							<p class="fw-bold">Total Ebook</p>
							<p class="total-ebook"></p>
						</div>

						<!-- total harga -->
						<div class="d-flex justify-content-between">
							<p class="fw-bold">Total Harga</p>
							<p class="harga"></p>
						</div>

						<hr>

						<!-- total biaya layanan -->
						<div class="d-flex justify-content-between">
							<h5 class="fw-bold">Total Biaya Layanan</h5>
							<p class="biaya-layanan"></p>
						</div>

						<!-- total biaya admin -->
						<div class="d-flex justify-content-between">
							<p class="fw-bold">Total Biaya Admin</p>
							<p class="biaya-admin"></p>
						</div>

						<hr>

						<!-- total biaya kupon dan voucher -->
						<div class="d-flex justify-content-between">
							<h5 class="fw-bold">Total Biaya Kupon dan Voucher</h5>
							<p class="biaya-kupon-voucher"></p>
						</div>

						<!-- total biaya diskon -->
						<div class="d-flex justify-content-between">
							<p class="fw-bold">Total Biaya Diskon</p>
							<p class="biaya-diskon"></p>
						</div>

						<hr>

						<!-- Jumlah pendapatan -->
						<div class="d-flex justify-content-between">
							<h5 class="fw-bold">Jumlah Pendapatan</h5>
							<p class="jumlah-pendapatan"></p>
						</div>

						<div class="alert alert-primary mt-3" role="alert">
							<i class="fa fa-info-circle mr-1"></i> Harap perhatikan detail rincian pendapatan anda.
						</div>


					</div>
				</div>

			</div>

		</div>
	</div>
</div>

<!-- load moment js -->
<script src="<?= $this->config->item('url_client') . 'assets/node_modules/moment/moment.js' ?>"></script>
<script>
	// Get all the nav-group-item elements
	const navGroupItems = document.querySelectorAll('.nav-group-item');

	// Add an event listener to each nav-group-item
	navGroupItems.forEach(item => {
		item.addEventListener('click', () => {
			// Remove the active class from all nav-group-item elements
			navGroupItems.forEach(item => {
				item.classList.remove('active');
			});

			// Add the active class to the clicked nav-group-item element
			item.classList.add('active');

			// Get the data attribute value of the clicked nav-group-item element
			const data = item.querySelector('a').getAttribute('data');

			// Get all the nav-content-item elements
			const navContentItems = document.querySelectorAll('.nav-content-item');

			// Hide all the nav-content-item elements
			navContentItems.forEach(contentItem => {
				contentItem.style.display = 'none';
			});

			// Show the nav-content-item element with the same data attribute value as the clicked nav-group-item element
			const contentItem = document.querySelector(`.nav-content-item[data="${data}"]`);
			contentItem.style.display = 'block';
		});

		navGroupItems[0].click();

	});


	let tableSemuaPesanan, tableMenungguPembayaran, tablePesananSelesai, tablePesananDibatalkan;

	// data table ajax semua paket	
	$(document).ready(function() {

		tableSemuaPesanan = $('#table-semua-pesanan').DataTable({
			"processing": true,
			"serverSide": true,
			paging: true,
			info: true,
			filter: true,
			"ajax": {
				"url": "<?= base_url('Payment_transaction/getAll') ?>",
				"type": "GET"
			},
			language: {
				"emptyTable": "Tidak ada data yang tersedia pada tabel ini",
				"processing": '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>'
			},
			"columns": [{
					data: "package_image",
					render: function(data, type, row, meta) {
						let badge;
						if (row.status == 'settlement') {
							badge = `<span class="badge p-2 mr-2 text-success" style="background-color:#D1E7DD;">Berhasil</span>`;
						} else if (row.status == 'pending') {
							badge = `<span class="badge p-2 mr-2 text-warning" style="background-color:#FFF3CD;">Menunggu Pembayaran</span>`;
						} else if (row.status == 'cancel') {
							badge = `<span class="badge p-2 mr-2 text-danger" style="background-color:#F8D7DA;">Dibatalkan</span>`;
						} else if (row.status == 'expire') {
							badge = `<span class="badge p-2 mr-2 text-danger" style="background-color:#F8D7DA;">Kadaluarsa</span>`;
						}

						let card = `
							<div class="card border w-100">
								
								<div class="card-header-custom m-3 d-flex">
									${badge} - <span class="ml-2 mr-2" style="color: #281B93; font-weight:600; ">${row.transaction_number}</span> -
									<span class="ml-2 mr-2 text-muted">${moment(row.transaction_time).format('D MMM, HH.mm')}</span> -
									<span class="ml-2 mr-2 text-muted">${row.buyer_name}</span>
								</div>

								<div class="card-body">
									<div class = "d-flex">
										<img src="${row.detail.image}" class="product-image" alt="${row.package_name}">
										<div class="ml-3">
											<h5 class="card-title">${row.detail.package_name}</h5>
											<p class="card-text">Rp. ${number_format(row.detail.price, 0, ',', '.')}</p>
										</div>
									</div>
								</div>

								<div class="card-footer">
									<div class="d-flex justify-content-between">
										<div>
											<p class="card-text">Total Penjualan</p>
										</div>
										<div class="d-flex">
											<p class="card-text m-0" style="font-weight: 600;">Rp ${number_format(row.total_payment, 0, ',', '.')}</p>
											<span class="btn btn-clear p-0 ml-2" data="${row.detail}" onclick="showDetail(this)"><i class="fa fa-eye"></i></span>
										</div>
									</div>
								</div>
							</div>

						`;

						return card;
					}
				},
				{
					data: "status",
					visible: false
				},
				{
					data: "field_table",
					visible: false
				}

			],
			"columnDefs": [{
				"targets": 0,
				"data": "package_name",
				"render": function(data, type, row, meta) {
					return data;
				}
			}],
			pageLength: '10',
			drawCallback: function(settings) {
				// round total data
				$('#round-total-semua-pesanan').text(settings.json.data.length);
			}
		});

		tableMenungguPembayaran = $('#table-menunggu-pembayaran').DataTable({
			"processing": true,
			"serverSide": true,
			paging: true,
			info: true,
			filter: true,
			"ajax": {
				"url": "<?= base_url('Payment_transaction/getAll') ?>",
				"type": "GET"
			},
			language: {
				"emptyTable": "Tidak ada data yang tersedia pada tabel ini",
				"processing": '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>'
			},
			"columns": [{
					data: "package_image",
					render: function(data, type, row, meta) {
						let badge;
						if (row.status == 'settlement') {
							badge = `<span class="badge p-2 mr-2 text-success" style="background-color:#D1E7DD;">Berhasil</span>`;
						} else if (row.status == 'pending') {
							badge = `<span class="badge p-2 mr-2 text-warning" style="background-color:#FFF3CD;">Menunggu Pembayaran</span>`;
						} else if (row.status == 'cancel') {
							badge = `<span class="badge p-2 mr-2 text-danger" style="background-color:#F8D7DA;">Dibatalkan</span>`;
						} else if (row.status == 'expire') {
							badge = `<span class="badge p-2 mr-2 text-danger" style="background-color:#F8D7DA;">Kadaluarsa</span>`;
						}

						let card = `
							<div class="card border w-100">
								
								<div class="card-header-custom m-3 d-flex">
									${badge} - <span class="ml-2 mr-2" style="color: #281B93; font-weight:600; ">${row.transaction_number}</span> -
									<span class="ml-2 mr-2 text-muted">${moment(row.created_at).format('DD MMM YYYY, HH.mm')}</span> -
									<span class="ml-2 mr-2 text-muted">${row.buyer_name}</span>
								</div>

								<div class="card-body">
									<div class = "d-flex">
										<img src="${row.detail.image}" class="product-image" alt="${row.package_name}">
										<div class="ml-3">
											<h5 class="card-title">${row.detail.package_name}</h5>
											<p class="card-text">Rp. ${number_format(row.detail.price, 0, ',', '.')}</p>
										</div>
									</div>
								</div>

								<div class="card-footer">
									<div class="d-flex justify-content-between">
										<div>
											<p class="card-text">Total Penjualan</p>
										</div>
										<div>
											<p class="card-text" style="font-weight: 600;">Rp ${number_format(row.total_payment, 0, ',', '.')}</p>
										</div>
									</div>
								</div>
							</div>

						`;

						return card;
					}
				},
				{
					data: "status",
					visible: false
				},
				{
					data: "field_table",
					visible: false
				}
			],
			"columnDefs": [{
				"targets": 0,
				"data": "package_name",
				"render": function(data, type, row, meta) {
					return data;
				}
			}],
			pageLength: '10',
			drawCallback: function(settings) {
				// round total data
				$('#round-total-menunggu-pembayaran').text(settings.json.data.length);
			}
		});
		tableMenungguPembayaran.columns(2).search('pending').draw();

		tablePesananSelesai = $('#table-pesanan-selesai').DataTable({
			"processing": true,
			"serverSide": true,
			paging: true,
			info: true,
			filter: true,
			language: {
				"emptyTable": "Tidak ada data yang tersedia pada tabel ini",
				"processing": '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>'
			},
			"ajax": {
				"url": "<?= base_url('Payment_transaction/getAll') ?>",
				"type": "GET"
			},
			"columns": [{
					data: "package_image",
					render: function(data, type, row, meta) {
						let badge;
						if (row.status == 'settlement') {
							badge = `<span class="badge p-2 mr-2 text-success" style="background-color:#D1E7DD;">Berhasil</span>`;
						} else if (row.status == 'pending') {
							badge = `<span class="badge p-2 mr-2 text-warning" style="background-color:#FFF3CD;">Menunggu Pembayaran</span>`;
						} else if (row.status == 'cancel') {
							badge = `<span class="badge p-2 mr-2 text-danger" style="background-color:#F8D7DA;">Dibatalkan</span>`;
						} else if (row.status == 'expire') {
							badge = `<span class="badge p-2 mr-2 text-danger" style="background-color:#F8D7DA;">Kadaluarsa</span>`;
						}

						let card = `
							<div class="card border w-100">
								
								<div class="card-header-custom m-3 d-flex">
									${badge} - <span class="ml-2 mr-2" style="color: #281B93; font-weight:600; ">${row.transaction_number}</span> -
									<span class="ml-2 mr-2 text-muted">${moment(row.created_at).format('DD MMM YYYY, HH.mm')}</span> -
									<span class="ml-2 mr-2 text-muted">${row.buyer_name}</span>
								</div>

								<div class="card-body">
									<div class = "d-flex">
										<img src="${row.detail.image}" class="product-image" alt="${row.package_name}">
										<div class="ml-3">
											<h5 class="card-title">${row.detail.package_name}</h5>
											<p class="card-text">Rp. ${number_format(row.detail.price, 0, ',', '.')}</p>
										</div>
									</div>
								</div>

								<div class="card-footer">
									<div class="d-flex justify-content-between">
										<div>
											<p class="card-text">Total Penjualan</p>
										</div>
										<div>
											<p class="card-text" style="font-weight: 600;">Rp ${number_format(row.total_payment, 0, ',', '.')}</p>
										</div>
									</div>
								</div>
							</div>

						`;

						return card;
					}
				},
				{
					data: "status",
					visible: false
				},
				{
					data: "field_table",
					visible: false
				}
			],
			"columnDefs": [{
				"targets": 0,
				"data": "package_name",
				"render": function(data, type, row, meta) {
					return data;
				}
			}],
			pageLength: '10',
			drawCallback: function(settings) {
				// round total data
				$('#round-total-pesanan-selesai').text(settings.json.data.length);
			}
		});
		tablePesananSelesai.columns(2).search('settlement').draw();

		tablePesananDibatalkan = $('#table-pesanan-dibatalkan').DataTable({
			"processing": true,
			"serverSide": true,
			paging: true,
			info: true,
			filter: true,
			language: {
				"emptyTable": "Tidak ada data yang tersedia pada tabel ini",
				"processing": '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>'
			},
			"ajax": {
				"url": "<?= base_url('Payment_transaction/getAll') ?>",
				"type": "GET"
			},
			"columns": [{
					data: "package_image",
					render: function(data, type, row, meta) {
						let badge;
						if (row.status == 'settlement') {
							badge = `<span class="badge p-2 mr-2 text-success" style="background-color:#D1E7DD;">Berhasil</span>`;
						} else if (row.status == 'pending') {
							badge = `<span class="badge p-2 mr-2 text-warning" style="background-color:#FFF3CD;">Menunggu Pembayaran</span>`;
						} else if (row.status == 'cancel') {
							badge = `<span class="badge p-2 mr-2 text-danger" style="background-color:#F8D7DA;">Dibatalkan</span>`;
						} else if (row.status == 'expire') {
							badge = `<span class="badge p-2 mr-2 text-danger" style="background-color:#F8D7DA;">Kadaluarsa</span>`;
						}

						let card = `
							<div class="card border w-100">
								
								<div class="card-header-custom m-3 d-flex">
									${badge} - <span class="ml-2 mr-2" style="color: #281B93; font-weight:600; ">${row.transaction_number}</span> -
									<span class="ml-2 mr-2 text-muted">${moment(row.created_at).format('DD MMM YYYY, HH.mm')}</span> -
									<span class="ml-2 mr-2 text-muted">${row.buyer_name}</span>
								</div>

								<div class="card-body">
									<div class = "d-flex">
										<img src="${row.detail.image}" class="product-image" alt="${row.package_name}">
										<div class="ml-3">
											<h5 class="card-title text-danger">${row.detail.package_name}</h5>
											<p class="card-text">Rp. ${number_format(row.detail.price, 0, ',', '.')}</p>
										</div>
									</div>
								</div>

								<div class="card-footer">
									<div class="d-flex justify-content-between">
										<div>
											<p class="card-text">Total Penjualan</p>
										</div>
										<div>
											<p class="card-text" style="font-weight: 600;">Rp ${number_format(row.total_payment, 0, ',', '.')}</p>
										</div>
									</div>
								</div>
							</div>

						`;

						return card;
					}
				},
				{
					data: "status",
					visible: false
				},
				{
					data: "field_table",
					visible: false
				}
			],
			"columnDefs": [{
				"targets": 0,
				"data": "package_name",
				"render": function(data, type, row, meta) {
					return data;
				}
			}],
			pageLength: '10',
			drawCallback: function(settings) {
				// round total data
				$('#round-total-pesanan-dibatalkan').text(settings.json.data.length);
			}
		});
		tablePesananDibatalkan.columns(2).search('cancel').draw();
	});



	// ========================================================================================================
	// AJAX SEARCH
	// ========================================================================================================

	$('#search').on('click', function(e) {
		e.preventDefault();
		const sort = $('#sort').val();

		// filter jenis pembelian
		const jenisPembelian = $('#jenis-pembelian').val();
		tableSemuaPesanan.columns(0).search(jenisPembelian).draw();

		if (sort != '') {
			tableSemuaPesanan.order([sort.split('-')]).draw();
		}

		// filter tanggal
		const tanggal = $('#pilih-tanggal').val();
		tableSemuaPesanan.columns(1).search(tanggal).draw();

		// tableMenungguPembayaran.order([sort.split('-')]).draw();
		// tablePesananSelesai.order([sort.split('-')]).draw();
	});


	// thousands separator
	function number_format(number, decimals, dec_point, thousands_sep) {
		number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
		var n = !isFinite(+number) ? 0 : +number,
			prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
			sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
			dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
			s = '',
			toFixedFix = function(n, prec) {
				var k = Math.pow(10, prec);
				return '' + Math.round(n * k) / k;
			};
		// Fix for IE parseFloat(0.55).toFixed(0) = 0;
		s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
		if (s[0].length > 3) {
			s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
		}
		if ((s[1] || '').length < prec) {
			s[1] = s[1] || '';
			s[1] += new Array(prec - s[1].length + 1).join('0');
		}
		return s.join(dec);
	}

	// AJAX ACTIVE PRODUCT
	function activeProduct(e) {
		const id = e.getAttribute('data');
		const checked = e.parentElement.children[0].checked;
		$.ajax({
			url: '<?= base_url('bundling/activeProduct') ?>',
			type: 'POST',
			data: {
				id: id,
				status: !checked ? 1 : 0
			},
			success: function(response) {
				if (response.status == 'success') {
					Swal.fire({
						icon: 'success',
						title: 'Berhasil',
						text: response.message,
						showConfirmButton: false,
						timer: 1500
					});

					$('#table-semua-pesanan').DataTable().ajax.reload();
				} else {
					Swal.fire({
						icon: 'error',
						title: 'Gagal',
						text: response.message,
						showConfirmButton: false,
						timer: 1500
					});
				}
			}
		});
	}

	// show detail
	function showDetail(e) {
		const data = ($(e).parents('table').DataTable().row($(e).parents('tr')).data());

		$('.total-penjualan').text(`Rp ${number_format(data.total_payment, 0, ',', '.')}`);
		$('.total-ebook').text(`Rp ${number_format(data.total_ebook, 0, ',', '.')}`);
		$('.harga').text(`Rp ${number_format(data.detail.price, 0, ',', '.')}`);	
		$('.biaya-layanan').text(`Rp ${number_format(data.biaya_admin, 0, ',', '.')}`);
		$('.biaya-admin').text(`Rp ${number_format(data.biaya_admin, 0, ',', '.')}`);
		$('.biaya-kupon-voucher').text(`Rp ${number_format(data.biaya_kupon_voucher, 0, ',', '.')}`);
		$('.biaya-diskon').text(`Rp ${number_format(data.discount, 0, ',', '.')}`);

		const jumlahPendapatan = data.total_payment - data.biaya_admin - data.discount;
		$('.jumlah-pendapatan').text(`Rp ${number_format(jumlahPendapatan, 0, ',', '.')}`);

		// show modal
		$('#modal-detail').modal('show');

	}
</script>
