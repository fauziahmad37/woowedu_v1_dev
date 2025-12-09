<!-- load amchart4 -->
<script src="<?= base_url() . 'assets/new/libs/amcharts/core.js' ?>"></script>
<script src="<?= base_url() . 'assets/new/libs/amcharts/charts.js' ?>"></script>
<script src="<?= base_url() . 'assets/new/libs/amcharts/animated.js' ?>"></script>
<!-- load chart.js -->
<script src="<?= base_url() . 'assets/new/libs/amcharts/npm/chart.js' ?>"></script>

<div class="row">
	<div class="col-12">


		<div class="clearfix"></div>
		<hr>


		<!-- card 4 item horizontal row -->
		<div class="card-group-custom row">
			<div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-12">
				<div class="card">
					<div class="card-body">

						<div class="card-title">
							<h5 class="d-inline">Total Pendapatan</h5>
							<div class="image-container d-inline float-right" style="background-color: #F0EEFC">
								<img src="<?= base_url('assets/images/icons/credit-card-icon.png') ?>" alt="Total Pendapatan">
							</div>
						</div>

						<h4 class="text-dark" style="font-weight: 600;">Rp 500.000</h4>

						<p class="card-text">With supporting</p>

						<hr>
						<a href="<?= base_url('publisher') ?>" class="h5" style="color: #351E99; font-weight: 600;">Lihat Detail</a>
						<img src="<?= base_url('assets/images/icons/caret-right-icon.svg') ?>" class="float-right" alt="Lihat Detail" style="width: 20px; height: 20px;">
					</div>
				</div>

			</div>

			<div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-12">
				<div class="card">
					<div class="card-body">

						<div class="card-title">
							<h5 class="d-inline">Total Pendapatan</h5>
							<div class="image-container d-inline float-right" style="background-color: #EAFFFE">
								<img src="<?= base_url('assets/images/icons/shopping-cart-check-icon.png') ?>" alt="Total Pendapatan">
							</div>
						</div>

						<h4 class="text-dark" style="font-weight: 600;">Rp 500.000</h4>

						<p class="card-text">With supporting</p>

						<hr>
						<a href="<?= base_url('publisher') ?>" class="h5" style="color: #351E99; font-weight: 600;">Lihat Detail</a>
						<img src="<?= base_url('assets/images/icons/caret-right-icon.svg') ?>" class="float-right" alt="Lihat Detail" style="width: 20px; height: 20px;">
					</div>
				</div>

			</div>

			<div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-12">
				<div class="card">
					<div class="card-body">

						<div class="card-title">
							<h5 class="d-inline">Total Pendapatan</h5>
							<div class="image-container d-inline float-right" style="background-color: #FFECEC">
								<img src="<?= base_url('assets/images/icons/user-red-icon.png') ?>" alt="Total Pendapatan">
							</div>
						</div>

						<h4 class="text-dark" style="font-weight: 600;">Rp 500.000</h4>

						<p class="card-text">With supporting</p>

						<hr>
						<a href="<?= base_url('publisher') ?>" class="h5" style="color: #351E99; font-weight: 600;">Lihat Detail</a>
						<img src="<?= base_url('assets/images/icons/caret-right-icon.svg') ?>" class="float-right" alt="Lihat Detail" style="width: 20px; height: 20px;">
					</div>
				</div>

			</div>

			<div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-xs-12">
				<div class="card">
					<div class="card-body">

						<div class="card-title">
							<h5 class="d-inline">Total Pendapatan</h5>
							<div class="image-container d-inline float-right" style="background-color: #FFF6ED">
								<img src="<?= base_url('assets/images/icons/box-icon.png') ?>" alt="Total Pendapatan">
							</div>
						</div>

						<h4 class="text-dark" style="font-weight: 600;">Rp 500.000</h4>

						<p class="card-text">With supporting</p>

						<hr>
						<a href="<?= base_url('publisher') ?>" class="h5" style="color: #351E99; font-weight: 600;">Lihat Detail</a>
						<img src="<?= base_url('assets/images/icons/caret-right-icon.svg') ?>" class="float-right" alt="Lihat Detail" style="width: 20px; height: 20px;">
					</div>
				</div>
			</div>




		</div>

		<!-- Grafik statik penjualan -->
		<div class="card bar-chart">
			<div class="card-body">
				<h5 class="card-title">Statistik Penjualan</h5>
				<canvas id="statistik-penjualan" width="400" height="100"></canvas>
			</div>
		</div>

	</div>
</div>

<!-- list ebook terlaris menggunakan datatable ajax -->
<div class="row">
	<div class="col-8">
		<div class="card">
			<div class="card-body">
				<h5 class="card-title">Ebook Terlaris</h5>
				<table id="table-ebook-terlaris" class="table table-striped table-bordered" style="width:100%">
					<thead>
						<tr>
							<th>No</th>
							<th>Judul Ebook</th>
							<th>Penulis</th>
							<th>Terjual</th>
							<th>Profit</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>1</td>
							<td>Belajar PHP</td>
							<td>John Doe</td>
							<td>100</td>
							<td>Rp 500.000</td>
						</tr>
						<tr>
							<td>2</td>
							<td>Belajar Javascript</td>
							<td>John Doe</td>
							<td>100</td>
							<td>Rp 500.000</td>
						</tr>
						<tr>
							<td>3</td>
							<td>Belajar HTML</td>
							<td>John Doe</td>
							<td>100</td>
							<td>Rp 500.000</td>
						</tr>
						<tr>
							<td>4</td>
							<td>Belajar CSS</td>
							<td>John Doe</td>
							<td>100</td>
							<td>Rp 500.000</td>
						</tr>
						<tr>
							<td>5</td>
							<td>Belajar Laravel</td>
							<td>John Doe</td>
							<td>100</td>
							<td>Rp 500.000</td>
						</tr>
					</tbody>
				</table>

				<!-- pagination -->
				<div class="row">
					<div class="col-12">
						<nav aria-label="Page navigation example">
							<ul class="pagination justify-content-end">
								<li class="page-item disabled">
									<a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
								</li>
								<li class="page-item"><a class="page-link" href="#">1</a></li>
								<li class="page-item"><a class="page-link" href="#">2</a></li>
								<li class="page-item"><a class="page-link" href="#">3</a></li>
								<li class="page-item">
									<a class="page-link" href="#">Next</a>
								</li>
							</ul>
						</nav>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-4">
		<!-- pie chart amchart4 -->
		<div class="card">
			<div class="card-body">
				<h6>Statistik Penjualan</h6>
				<h5 class="card-title">Paket Langganan</h5>
				<div id="chart-paket-langganan" style="height: 400px;"></div>
			</div>
		</div>
	</div>
</div>

<!-- list history pesanan -->
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<h5 class="card-title">History Pesanan</h5>
				<table id="table-history-pesanan" class="table table-striped table-bordered" style="width:100%">
					<thead>
						<tr>
							<th>No</th>
							<th>Nama Pemesan</th>
							<th>Judul Ebook</th>
							<th>Jumlah</th>
							<th>Total</th>
							<th>Status</th>
							<th>Tanggal</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>1</td>
							<td>John Doe</td>
							<td>Belajar PHP</td>
							<td>1</td>
							<td>Rp 500.000</td>
							<td>Selesai</td>
							<td>2022-03-01</td>
						</tr>
						<tr>
							<td>2</td>
							<td>John Doe</td>
							<td>Belajar Javascript</td>
							<td>1</td>
							<td>Rp 500.000</td>
							<td>Selesai</td>
							<td>2022-03-01</td>
						</tr>
						<tr>
							<td>3</td>
							<td>John Doe</td>
							<td>Belajar HTML</td>
							<td>1</td>
							<td>Rp 500.000</td>
							<td>Selesai</td>
							<td>2022-03-01</td>
						</tr>
						<tr>
							<td>4</td>
							<td>John Doe</td>
							<td>Belajar CSS</td>
							<td>1</td>
							<td>Rp 500.000</td>
							<td>Selesai</td>
							<td>2022-03-01</td>
						</tr>
						<tr>
							<td>5</td>
							<td>John Doe</td>
							<td>Belajar Laravel</td>
							<td>1</td>
							<td>Rp 500.000</td>
							<td>Selesai</td>
							<td>2022-03-01</td>
						</tr>
					</tbody>
				</table>

				<!-- pagination -->
				<div class="row">
					<div class="col-12">
						<nav aria-label="Page navigation example">
							<ul class="pagination justify-content-end">
								<li class="page-item disabled">
									<a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
								</li>
								<li class="page-item"><a class="page-link" href="#">1</a></li>
								<li class="page-item"><a class="page-link" href="#">2</a></li>
								<li class="page-item"><a class="page-link" href="#">3</a></li>
								<li class="page-item">
									<a class="page-link" href="#">Next</a>
								</li>
							</ul>
						</nav>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>

<!-- modal notification -->
<div class="modal fade" id="modal-notification" tabindex="-1" aria-labelledby="modal-notification" aria-hidden="true">
	<div class="modal-dialog modal-dialog-top">
		<div class="modal-content p-2">
			<!-- tab content pesanan & progress stock -->
			<di class="row notif-header">
				<div class="col-6 header-item active" data="pesanan">
					<h6>Pesanan</h6>
				</div>
				<div class="col-6 header-item" data="pengingat-stok">
					<h6>Pengingat Stok</h6>
				</div>
			</di>

			<div class="notif-body">
				<div id="pesanan">
					<!-- card pesanan -->
					<?php for ($i = 0; $i < 5; $i++) : ?>
						<dic class="card p-2">
							<!-- image left and right date -->
							<dic class="d-flex justify-content-start">
								<img class="d-inline" src="<?= base_url() . 'assets/images/icons/shopping-bag-fill.svg' ?>" alt="shopping-bag-fill" style="width: 24px; height: 24px;">
								<h6 style="margin-top: 5px; margin-left: 3px;">15 Nov</h6>
							</dic>

							<h5>1 Pembelian Ebook</h5>
							<p>Silahkan cek detail transaksi pembelian ebook anda</p>
						</dic>
					<?php endfor; ?>

				</div>
				<div id="pengingat-stok">
					<!-- card pengingat stok -->
					<?php for ($i = 0; $i < 5; $i++) : ?>
						<dic class="card p-2">
							<!-- image left and right date -->
							<dic class="d-flex justify-content-start">
								<img class="d-inline" src="<?= base_url() . 'assets/images/icons/alarm-warning-fill.svg' ?>" alt="box-seam" style="width: 24px; height: 24px;">
								<h6 class="text-danger" style="margin-top: 5px; margin-left: 3px;">15 Nov</h6>
							</dic>

							<h5 class="text-danger">Stok Ebook “Nama Ebook” Hampir Habis</h5>
							<p>Silahkan atur ulang ketersediaan stok Ebook anda</p>
						</dic>
					<?php endfor; ?>
				</div>
			</div>
		</div>
	</div>
</div>


<script>
	// show modal when document first load
	$(document).ready(function() {
		$('#modal-notification').modal('show');

		// change tab content
		$('.header-item').click(function() {
			$('.header-item').removeClass('active');
			$(this).addClass('active');

			// show content
			$('.notif-body>div').hide();
			$('#' + $(this).attr('data')).show();
		});
	});

	var ctx = document.getElementById('statistik-penjualan').getContext('2d');
	var myChart = new Chart(ctx, {
		type: 'bar',
		data: {
			labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],

			datasets: [{
				label: 'Ebook',
				// width bar
				barThickness: 20,

				// 2 bar every month, 1 for total sales, 1 for total profit
				data: [12, 19, 3, 5, 2, 3, 10, 15, 20, 25, 30, 35],
				backgroundColor: [
					'#00B3A7'
				],
				borderWidth: 1

			}, {

				label: 'Paket Bundling',
				// width bar
				barThickness: 20,

				// 2 bar every month, 1 for total sales, 1 for total profit
				data: [10, 15, 5, 8, 3, 5, 12, 18, 25, 30, 35, 40],

				// background color solid
				backgroundColor: [
					'#351E99',
				],

			}]
		},
		options: {
			scales: {
				y: {
					beginAtZero: true
				}
			}
		}
	});

	// amchart4 pie chart
	am4core.ready(function() {

		// Themes begin
		am4core.useTheme(am4themes_animated);
		// Themes end

		var chart = am4core.create("chart-paket-langganan", am4charts.PieChart);
		chart.hiddenState.properties.opacity = 0; // this creates initial fade-in

		chart.data = [{
				"country": "Paket Langganan 1 Bulan",
				"value": 401
			},
			{
				"country": "Paket Langganan 3 Bulan",
				"value": 300
			},
			{
				"country": "Paket Langganan 6 Bulan",
				"value": 200
			},
		];

		var series = chart.series.push(new am4charts.PieSeries());
		series.dataFields.value = "value";
		// series.dataFields.radiusValue = "value";
		series.dataFields.category = "country";
		// series.slices.template.cornerRadius = 6;
		series.colors.step = 3;

		// hide label pie chart
		series.labels.template.disabled = true;

		series.hiddenState.properties.endAngle = -90;

		chart.legend = new am4charts.Legend();

	}); // end amchart4 pie chart
</script>
