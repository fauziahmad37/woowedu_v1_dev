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
		width:400px;
	}

	/* make icon in title search */
	#title {
		background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8" /><line x1="21" y1="21" x2="16.65" y2="16.65" /></svg>');
		background-repeat: no-repeat;
		background-position: right 8px center;
		background-size: 15px;
		background-color: #f9f9f9;
	}

	#table-paket-aktif,
	#table-paket-inactive {
		width: 100% !important;
	}

	table thead th {
		background-color: #281B93;
		color: white;
	}

	.dataTables_length,
	#table-paket-inactive_filter,
	#table-paket-aktif_filter,
	#table-semua-paket_filter {
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
</style>

<section class="main">
	<h4 class="fw-bold">Paket Bundling</h4>
	<h5>Kelola paket bundling yang sudah anda tambahkan disini</h5>

	<!-- tab group horizontal nav -->
	<div class="nav-group mt-4">
		<div class="nav-group-item active">
			<a class="btn btn-clear" data="all">Semua Paket Bundling
				<span class="round-total-data" id="round-total-semua"></span>	
			</a>
		</div>
		<div class="nav-group-item">
			<a class="btn btn-clear" data="active">Paket Bundling Aktif
				<span class="round-total-data" id="round-total-aktif"></span>
			</a>
		</div>
		<div class="nav-group-item">
			<a class="btn btn-clear" data="inactive">Paket Bundling Tidak Aktif
				<span class="round-total-data" id="round-total-inactive"></span>
			</a>
		</div>
	</div>

	<!-- nav group content -->
	<div class="nav-content">

		<div class="filter-group mt-3 mb-4">
			<!-- form filter inline -->
			<form class="form-inline">
				<!-- title -->
				<div class="form-group mr-3">
					<label for="title" class="sr-only">Cari</label>
					<input type="text" class="form-control" id="title" placeholder="Cari Paket Bundling">
				</div>

				<!-- kategori -->
				<!-- <div class="form-group mr-3">
					<label for="kategori" class="sr-only">Kategori</label>
					<select class="form-control" id="kategori">
						<option value="">Semua Kategori</option>
						<option value="1">Kategori 1</option>
						<option value="2">Kategori 2</option>
						<option value="3">Kategori 3</option>
					</select>
				</div> -->

				<!-- status -->
				<div class="form-group mr-3">
					<label for="status" class="sr-only">Status</label>
					<select class="form-control" id="status-produk">
						<option value="">Semua Status</option>
						<option value="1">Aktif</option>
						<option value="0">Tidak Aktif</option>
					</select>
				</div>

				<!-- urutkan -->
				<div class="form-group mr-3">
					<label for="sort" class="sr-only">Urutkan</label>
					<select class="form-control" id="sort">
						<option value="">Urutkan</option>
						<option value="2-desc">Harga Tertinggi</option>
						<option value="2-asc">Harga Terendah</option>
					</select>
				</div>

				<div class="btn-paket-bundling">
					<a href="bundling/create" class="btn btn-md btn-primary">+ Tambah Paket Bundling</a>
				</div>
			</form>

		</div>

		<div class="nav-content-item active" data="all">
			<!-- content here -->
			<table class="table table-bordered table-striped" id="table-semua-paket">
				<thead>
					<tr>
						<th>Cover</th>
						<th>Nama Paket</th>
						<th>Harga</th>
						<th>Kuota</th>
						<th>Terjual</th>
						<th>Aktif</th>
						<th>Status</th>
						<th>Aksi</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
		<div class="nav-content-item" data="active">
			<!-- content here -->
			<table class="table table-bordered table-striped" id="table-paket-aktif">
				<thead>
					<tr>
						<th>Cover</th>
						<th>Nama Paket</th>
						<th>Harga</th>
						<th>Kuota</th>
						<th>Terjual</th>
						<th>Aktif</th>
						<th>Status</th>
						<th>Aksi</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
		<div class="nav-content-item" data="inactive">
			<!-- content here -->
			<table class="table table-bordered table-striped" id="table-paket-inactive">
				<thead>
					<tr>
						<th>Cover</th>
						<th>Nama Paket</th>
						<th>Harga</th>
						<th>Kuota</th>
						<th>Terjual</th>
						<th>Aktif</th>
						<th>Status</th>
						<th>Aksi</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>

	</div>

</section>

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


	let tableSemuaPaket, tablePaketAktif, tablePaketInactive;

	// data table ajax semua paket	
	$(document).ready(function() {

		tableSemuaPaket = $('#table-semua-paket').DataTable({
			"processing": true,
			"serverSide": true,
			paging: true,
			info: true,
			filter: true,
			"ajax": {
				"url": "<?= base_url('bundling/getAll') ?>",
				"type": "GET"
			},
			language: {
				"emptyTable": "Tidak ada data yang tersedia pada tabel ini",
				"processing": '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>'
			},
			"columns": [{
					data: "package_image",
					render: function(data, type, row, meta) {
						return `<img src="<?= $this->config->item('url_client') . 'assets/images/bundlings/' ?>${data}" alt="${row.package_name}" class="img-fluid" style="width: 100px;">`;
					}
				},
				{
					data: "package_name"
				},
				{
					data: "price",
					render: function(data, type, row, meta) {
						return `Rp. ${number_format(data, 0, ',', '.')}`;
					}
				},
				{
					data: "stock",
					render: function(data, type, row, meta) {
						return data;
					}
				},
				{
					data: "total_terjual"
				},
				{
					data: "status",
					render: function(data, type, row, _meta) {
						let switchButton;

						if (row.status == 1 && row.switch_status == 'show') {
							switchButton = `<div class="custom-control custom-switch">
												<input disabled type="checkbox" checked class="custom-control-input" id="active_${_meta.row}">
												<label onclick="activeProduct(this)" data="${row.id}" class="custom-control-label" for="active_${_meta.row}">&nbsp;</label>
											</div>`;
						} else if (row.status == 0 && row.switch_status == 'show') {
							switchButton = `<div class="custom-control custom-switch">
												<input disabled type="checkbox" class="custom-control-input" id="active_${_meta.row}">
												<label onclick="activeProduct(this)" data="${row.id}" class="custom-control-label" for="active_${_meta.row}">&nbsp;</label>
											</div>`;
						} else {
							switchButton = `-`;
						}

						return switchButton;
					}
				},
				{
					data: "status",
					render: function(data, type, row, meta) {
						let button;
						if (row.status == 1 && row.switch_status == 'show') {
							button = `<span class="badge p-2 text-success" style="background-color:#D1E7DD;">Aktif</span>`;
						} else if (row.status == 0 && row.switch_status == 'show') {
							button = `<span class="badge p-2 text-danger" style="background-color:#E3E4E8;">Non Aktif</span>`;
						} else {
							button = `<span class="badge p-2 text-warning" style="background-color:#FFF3CD;">Akan Datang</span>`;
						}

						return button;
					}
				},
				{
					data: null,
					render: function(data, type, row, meta) {
						// dropdown action
						return `<div class="dropdown">
									<button class="btn btn-sm btn-primary dropdown-toggle rounded-pill" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										<i class="fas fa-ellipsis-h"></i>
									</button>
									<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
										<a class="dropdown-item" href="bundling/edit?id=${row.id}">Edit</a>
										<a class="dropdown-item" href="#">Hapus</a>
									</div>
								</div>`;
					}
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
				$('#round-total-semua').text(settings.json.data.length);
			}
		});

		tablePaketAktif = $('#table-paket-aktif').DataTable({
			"processing": true,
			"serverSide": true,
			paging: true,
			info: true,
			filter: true,
			"ajax": {
				"url": "<?= base_url('bundling/getAll') ?>",
				"type": "GET"
			},
			language: {
				"emptyTable": "Tidak ada data yang tersedia pada tabel ini",
				"processing": '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>'
			},
			"columns": [{
					data: "package_image",
					render: function(data, type, row, meta) {
						return `<img src="<?= $this->config->item('url_client') . 'assets/images/bundlings/' ?>${data}" alt="${row.package_name}" class="img-fluid" style="width: 100px;">`;
					}
				},
				{
					data: "package_name"
				},
				{
					data: "price",
					render: function(data, type, row, meta) {
						return `Rp. ${number_format(data, 0, ',', '.')}`;
					}
				},
				{
					data: "stock",
					render: function(data, type, row, meta) {
						return data;
					}
				},
				{
					data: "total_terjual"
				},
				{
					data: "status",
					render: function(data, type, row, _meta) {
						let switchButton;

						if (row.status == 1 && row.switch_status == 'show') {
							switchButton = `<div class="custom-control custom-switch">
												<input disabled type="checkbox" checked class="custom-control-input" id="active_${_meta.row}">
												<label onclick="activeProduct(this)" data="${row.id}" class="custom-control-label" for="active_${_meta.row}">&nbsp;</label>
											</div>`;
						} else if (row.status == 0 && row.switch_status == 'show') {
							switchButton = `<div class="custom-control custom-switch">
												<input disabled type="checkbox" class="custom-control-input" id="active_${_meta.row}">
												<label onclick="activeProduct(this)" data="${row.id}" class="custom-control-label" for="active_${_meta.row}">&nbsp;</label>
											</div>`;
						} else {
							switchButton = `-`;
						}

						return switchButton;
					}
				},
				{
					data: "status",
					render: function(data, type, row, meta) {
						let button;
						if (row.status == 1 && row.switch_status == 'show') {
							button = `<span class="badge p-2 text-success" style="background-color:#D1E7DD;">Aktif</span>`;
						} else if (row.status == 0 && row.switch_status == 'show') {
							button = `<span class="badge p-2 text-danger" style="background-color:#E3E4E8;">Non Aktif</span>`;
						} else {
							button = `<span class="badge p-2 text-warning" style="background-color:#FFF3CD;">Akan Datang</span>`;
						}

						return button;
					}
				},
				{
					data: null,
					render: function(data, type, row, meta) {
						// dropdown action
						return `<div class="dropdown">
									<button class="btn btn-sm btn-primary dropdown-toggle rounded-pill" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										<i class="fas fa-ellipsis-h"></i>
									</button>
									<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
										<a class="dropdown-item" href="bundling/edit?id=${row.id}">Edit</a>
										<a class="dropdown-item" href="#">Hapus</a>
									</div>
								</div>`;
					}
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
				$('#round-total-aktif').text(settings.json.data.length);
			}
		});
		tablePaketAktif.columns(0).search('1').draw();

		tablePaketInactive = $('#table-paket-inactive').DataTable({
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
				"url": "<?= base_url('bundling/getAll') ?>",
				"type": "GET"
			},
			"columns": [{
					data: "package_image",
					render: function(data, type, row, meta) {
						return `<img src="<?= $this->config->item('url_client') . 'assets/images/bundlings/' ?>${data}" alt="${row.package_name}" class="img-fluid" style="width: 100px;">`;
					}
				},
				{
					data: "package_name"
				},
				{
					data: "price",
					render: function(data, type, row, meta) {
						return `Rp. ${number_format(data, 0, ',', '.')}`;
					}
				},
				{
					data: "stock",
					render: function(data, type, row, meta) {
						return data;
					}
				},
				{
					data: "total_terjual"
				},
				{
					data: "status",
					render: function(data, type, row, _meta) {
						let switchButton;

						if (row.status == 1 && row.switch_status == 'show') {
							switchButton = `<div class="custom-control custom-switch">
												<input disabled type="checkbox" checked class="custom-control-input" id="active_${_meta.row}">
												<label onclick="activeProduct(this)" data="${row.id}" class="custom-control-label" for="active_${_meta.row}">&nbsp;</label>
											</div>`;
						} else if (row.status == 0 && row.switch_status == 'show') {
							switchButton = `<div class="custom-control custom-switch">
												<input disabled type="checkbox" class="custom-control-input" id="active_${_meta.row}">
												<label onclick="activeProduct(this)" data="${row.id}" class="custom-control-label" for="active_${_meta.row}">&nbsp;</label>
											</div>`;
						} else {
							switchButton = `-`;
						}

						return switchButton;
					}
				},
				{
					data: "status",
					render: function(data, type, row, meta) {
						let button;
						if (row.status == 1 && row.switch_status == 'show') {
							button = `<span class="badge p-2 text-success" style="background-color:#D1E7DD;">Aktif</span>`;
						} else if (row.status == 0 && row.switch_status == 'show') {
							button = `<span class="badge p-2 text-danger" style="background-color:#E3E4E8;">Non Aktif</span>`;
						} else {
							button = `<span class="badge p-2 text-warning" style="background-color:#FFF3CD;">Akan Datang</span>`;
						}

						return button;
					}
				},
				{
					data: null,
					render: function(data, type, row, meta) {
						// dropdown action
						return `<div class="dropdown">
									<button class="btn btn-sm btn-primary dropdown-toggle rounded-pill" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										<i class="fas fa-ellipsis-h"></i>
									</button>
									<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
										<a class="dropdown-item" href="bundling/edit?id=${row.id}">Edit</a>
										<a class="dropdown-item" href="#">Hapus</a>
									</div>
								</div>`;
					}
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
				$('#round-total-inactive').text(settings.json.data.length);
			}
		});
		tablePaketInactive.columns(0).search('0').draw();


	});

	// ========================================================================================================
	// AJAX SEARCH
	// ========================================================================================================
	$('#title').on('keyup', function(e) {
		e.preventDefault();
		tableSemuaPaket.columns(1).search(this.value).draw();
		tablePaketAktif.columns(1).search(this.value).draw();
		tablePaketInactive.columns(1).search(this.value).draw();

		tableSemuaPaket.columns(0).search($('#status-produk').val()).draw();
		tablePaketAktif.columns(0).search($('#status-produk').val()).draw();
		tablePaketInactive.columns(0).search($('#status-produk').val()).draw();
	});

	// status search
	$('#status-produk').on('change', function(e) {
		e.preventDefault();
		const status = this.value;
		tableSemuaPaket.columns(0).search(status).draw();
		tablePaketAktif.columns(0).search(status).draw();
		tablePaketInactive.columns(0).search(status).draw();

		tableSemuaPaket.columns(1).search($('#title').val()).draw();
		tablePaketAktif.columns(1).search($('#title').val()).draw();
		tablePaketInactive.columns(1).search($('#title').val()).draw();
	});

	// sort data table
	$('#sort').on('change', function(e) {
		e.preventDefault();
		const sort = this.value;
		tableSemuaPaket.order([sort.split('-')]).draw();
		// tablePaketAktif.order([sort.split('-')]).draw();
		// tablePaketInactive.order([sort.split('-')]).draw();
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

					$('#table-semua-paket').DataTable().ajax.reload();
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
</script>
