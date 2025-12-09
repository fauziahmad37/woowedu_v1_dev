<!-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<!-- highcharts CDN -->
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

<style>
	#table-siswa_info {
		display: none;
	}

	.highcharts-credits {
		display: none;
	}

	.highcharts-exporting-group {
		display: none;
	}
</style>

<section class="explore-section section-padding" id="section_2">
	<div class="container">

		<div class="row mt-5">
			<div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12 mb-2 d-flex">
				<div class="bg-secondary-subtle rounded-4 p-3 d-inline-block"><i class="bi bi-people fs-3 mx-2 fw-bold"></i></div>
				<div class="d-inline-block ms-4 pt-3">
					<p class="fs-3 fw-bold text-secondary" style="line-height: 0px;"><?= $student['total']; ?></p>
					<p class="lh-1 fs-5 mt-4 text-body-secondary" style="line-height: 0px;">Jumlah Siswa</p>
				</div>
			</div>

			<div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12 mb-2 d-flex">
				<div class="bg-success-subtle rounded-4 p-3 d-inline-block"><i class="bi bi-people fs-3 mx-2 fw-bold"></i></div>
				<div class="d-inline-block ms-4 pt-3">
					<p class="fs-3 fw-bold text-success" style="line-height: 0px;"><?= $student['active']; ?></p>
					<p class="lh-1 fs-5 mt-4 text-body-secondary" style="line-height: 0px;">Jumlah Siswa Aktif</p>
				</div>
			</div>

			<div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12 mb-2 d-flex">
				<div class="bg-danger-subtle rounded-4 p-3 d-inline-block"><i class="bi bi-people fs-3 mx-2 fw-bold"></i></div>
				<div class="d-inline-block ms-4 pt-3">
					<p class="fs-3 fw-bold text-danger" style="line-height: 0px;"><?= $student['inactive']; ?></p>
					<p class="lh-1 fs-5 mt-4 text-body-secondary" style="line-height: 0px;">Jumlah Siswa Tidak Aktif</p>
				</div>
			</div>
		</div>
		<!-- <h4 class="">Semua Siswa</h4> -->
		<!-- <h6 class="mt-4">Ringkasan</h6> -->

		<!-- <div class="container border rounded p-4"> -->
		<!-- <h6>Absensi Siswa</h6> -->

		<!-- <div class="row d-flex">
			<div class="col-6 left-card-section">
				<div class="row h-100">

					<div class="col-6 pb-3 ps-3">
						<div class="card h-100 border-0 rounded-4 p-4" style="background: #D4D1E9;">
							<img src="assets/images/icons/peoples-round-icon.png" width="40" alt="jumlah-siswa">
							<h5 class="mt-3">300</h5>
							<p>Jumlah Siswa</p>
						</div>
					</div>
					
					<div class="col-6 pb-3 ps-3">
						<div class="card h-100 border-0 rounded-4 p-4" style="background: #DCFCE7;">
							<img src="assets/images/icons/people-round-icon.png" width="40" alt="jumlah-siswa">
							<h5 class="mt-3">280</h5>
							<p>Siswa Hadir</p>
						</div>
					</div>
					
					<div class="col-6 pb-3 ps-3">
						<div class="card h-100 border-0 rounded-4 p-4" style="background: #F8D7DA;">
							<img src="assets/images/icons/time-round-icon.png" width="40" alt="jumlah-siswa">
							<h5 class="mt-3">20</h5>
							<p>Siswa Tidak Hadir</p>
						</div>
					</div>
					
					<div class="col-6 pb-3 ps-3">
						<div class="card h-100 border-0 rounded-4 p-4" style="background: #FFF3CD;">
							<img src="assets/images/icons/clock-round-icon.png" width="40" alt="jumlah-siswa">
							<h5 class="mt-3">30</h5>
							<p>Siswa Terlambat</p>
						</div>
					</div>
					
				</div>
			</div>

			<div class="col-6 right-card-section">
				<div class="row">
					<div class="col-6 pb-3">
						<div class="card border-light-subtle rounded-4 shadow mb-4 h-100">
							<div class="card-header border-light-subtle rounded-top-4 text-center pt-3 text-white h6" style="background-color: var(--bs-primary); height: 50px;">
								Laki - Laki
							</div>
							<div class="card-body">
								<div id="absensi-laki-laki"></div>
							</div>
						</div>
					</div>
	
					<div class="col-6 pb-3">
						<div class="card border-light-subtle rounded-4 shadow mb-4 h-100">
							<div class="card-header border-light-subtle rounded-top-4 text-center pt-3 text-white h6" style="background-color: var(--bs-primary); height: 50px;">
								Perempuan
							</div>
							<div class="card-body">
								<div id="absensi-perempuan"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div> -->

		<div id="chart-absensi-harian" class="mt-5">

		</div>

		<!-- <div class="row d-flex align-items-stretch">	 -->
		<!-- <div class="col-xl-2 col-lg-2 col-md-3 col-sm-3 col-xs-12 mt-5">
					<div class="mb-3 circle-stat-card">
						<div class="d-flex flex-column" >
							<div class="row justify-content-around my-auto border-right">
								<div class="col-1 d-flex flex-column align-items-center">
									<span class="stat-circle mb-3">
										<span class="stat-number"></span>
									</span>
									<span>Jumlah Siswa</span>
								</div>
							</div>
						</div>
					</div>
				</div> -->

		<!-- <div class="col-xl-10 col-lg-10 col-md-9 col-sm-9 col-xs-12">
					<div>
						<canvas id="myChart"></canvas>
					</div>
				</div> -->

		<!-- <div class="col-xl-10 col-lg-10 col-md-9 col-sm-9 col-xs-12">

					<div class="row">
						<div class="col-6">
							<figure class="highcharts-figure">
								<div id="container-chart2"></div>
							</figure>
						</div>
						<div class="col-6">
							<figure class="highcharts-figure">
								<div id="container-chart"></div>
							</figure>
						</div>
					</div>

					
				</div> -->

		<!-- <div class="col-xl-6  mt-3">
					<div class="card border-light-subtle shadow mb-4 h-100">
						<div class="card-header border-light-subtle text-center pt-4" style="background-color: #cfe7ff; height: 65px;">
							Absensi
						</div>
						<div class="card-body">
							<table class="table table-bordered border-light-subtle">
								<thead>
									<tr>
									<th scope="col" class="text-white text-center" style="background-color: #30a3fc;">Total Murid <img src="assets/images/people-icon-dashboard.png" alt="" width="30"></th>
									<th scope="col" class="text-white text-center" style="background-color: #ee807f;">Terlambat <img src="assets/images/terlambat.png" alt="" width="30" ></th>
									<th scope="col" class="text-white text-center" style="background-color: #718ff1;">Izin <img src="assets/images/erly.png" alt="" width="30" ></th>
									<th scope="col" class="text-white text-center" style="background-color: #f29e70;">Sakit <img src="assets/images/sick.png" alt="" width="30" ></th>
									<th scope="col" class="text-white text-center" style="background-color: #f8c782;">Alfa <img src="assets/images/absence.png" alt="" width="30" ></th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td class="fw-bold" style="color: #30a3fc;">500</td>
										<td class="fw-bold" style="color: #30a3fc;">1</td>
										<td class="fw-bold" style="color: #30a3fc;">2</td>
										<td class="fw-bold" style="color: #30a3fc;">1</td>
										<td class="fw-bold" style="color: #30a3fc;">3</td>
									</tr>
									<tr>
										<td class="fw-bold" style="color: #30a3fc;">500</td>
										<td class="fw-bold" style="color: #30a3fc;">50</td>
										<td class="fw-bold" style="color: #30a3fc;">10</td>
										<td class="fw-bold" style="color: #30a3fc;">15</td>
										<td class="fw-bold" style="color: #30a3fc;">1</td>
									</tr>
								</tbody>
							</table>

						</div>
					</div>
				</div> -->


		<!-- <div class="col-xl-3  mt-3">
					<div class="card border-light-subtle shadow mb-4 h-100">
						<div class="card-header border-light-subtle text-center pt-4" style="background-color: #cfe7ff; height: 65px;">
							Laki - Laki
						</div>
						<div class="card-body">
							<div id="absensi-laki-laki"></div>
						</div>
					</div>
				</div>

				<div class="col-xl-3 mt-3">
					<div class="card border-light-subtle shadow mb-4 h-100">
						<div class="card-header border-light-subtle text-center pt-4" style="background-color: #cfe7ff; height: 65px;">
							Perempuan
						</div>
						<div class="card-body">
							<div id="absensi-perempuan"></div>
						</div>
					</div>
				</div>
				 -->
		<!-- </div> -->


		<!-- <div class="row d-flex align-items-stretch">
				<div class="col-xl-6 mt-3">
					<div class="card border-light-subtle shadow mb-4 h-100">
						<div class="card-header border-light-subtle text-center text-white pt-5" style="background-color: #6f8ff2; height: 100px;">
							Absen <img class="position-absolute" src="assets/images/calendar-clock.png" alt="" width="75" style="top: 10px; right: 10px">
						</div>
						<div class="card-body pt-5">
							<div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="92" aria-valuemin="0" aria-valuemax="100" style="height: 25px;">
								<div class="progress-bar" style="width: 92%; background: #4c7dfd"> 92%</div>
							</div>
							<div class="progress mt-2 mb-3" role="progressbar" aria-label="Basic example" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100" style="height: 25px;">
								<div class="progress-bar" style="width: 90%; background: #fe5e5e;"> 90%</div>
							</div>

							<p class="text-body-secondary m-0" style="font-size: 12px;">Informasi:</p>
							<p class="text-body-secondary m-0" style="font-size: 12px;"><span class="rounded me-1" style="margin-bottom: 5px; padding-right: 10px; background: #4c7dfd"></span> Laki-laki</p>
							<p class="text-body-secondary m-0" style="font-size: 12px;"><span class="rounded me-1" style="margin-bottom: 5px; padding-right: 10px; background: #fe5e5e"></span> Perempuan</p>
						</div>
					</div>
				</div>

				<div class="col-xl-6 mt-3">
					<div class="card border-light-subtle shadow mb-4 h-100">
						<div class="card-header border-light-subtle text-center text-white pt-5" style="background-color: #ee807f; height: 100px;">
							Terlambat <img class="position-absolute" src="assets/images/clock-warning.png" alt="" width="75" style="top: 10px; right: 10px">
						</div>
						<div class="card-body pt-5">
							<div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="5" aria-valuemin="0" aria-valuemax="100" style="height: 25px;">
								<div class="progress-bar" style="width: 5%; background: #4c7dfd"> 5%</div>
							</div>
							<div class="progress mt-2 mb-3" role="progressbar" aria-label="Basic example" aria-valuenow="3" aria-valuemin="0" aria-valuemax="100" style="height: 25px;">
								<div class="progress-bar" style="width: 3%; background: #fe5e5e;"> 3%</div>
							</div>

							<p class="text-body-secondary m-0" style="font-size: 12px;">Informasi:</p>
							<p class="text-body-secondary m-0" style="font-size: 12px;"><span class="rounded me-1" style="margin-bottom: 5px; padding-right: 10px; background: #4c7dfd"></span> Laki-laki</p>
							<p class="text-body-secondary m-0" style="font-size: 12px;"><span class="rounded me-1" style="margin-bottom: 5px; padding-right: 10px; background: #fe5e5e"></span> Perempuan</p>
						</div>
					</div>
				</div>
			</div> -->


		<!-- </div> -->

		<div class="row mt-5">
			<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-xs-12 mb-3">
				<select class="form-select" name="pilih-kelas">
					<option value="">Semua Kelas</option>
				</select>
			</div>

			<div class="col-xl-3 col-lg-4 col-md-5 col-sm-7 col-xs-8">
				<div class="input-group mb-3">
					<input type="text" class="form-control" placeholder="Pencarian siswa" aria-label="Pencarian siswa" aria-describedby="basic-addon2" name="nama-siswa">
					<div class="input-group-append">
						<button id="search" class="btn btn-primary text-white" type="button"><i class="bi bi-search"></i> Cari</button>
					</div>
				</div>
			</div>
		</div>

		<!-- <div class="position-relative mb-5">
			<button class="btn btn-light text-primary border border-primary border-2 rounded-4 position-absolute top-0 end-0" id="download"> 
				<i class="bi bi-download"></i>
				Download
			</button>
		</div> -->
		<div class="w-100 overflow-auto">
			<table class="table table-rounded table-hover" id="table-siswa" style="border: 1px solid rgba(0, 0, 0, .1);">
				<thead class="bg-primary text-white">
					<tr>
						<th>No</th>
						<th>Nama Siswa</th>
						<th>Kelas</th>
						<th>Email</th>
						<th>Status Terkini</th>
						<th>Aksi</th>
					</tr>
				</thead>
				<tbody id="table-body-content"></tbody>
			</table>
		</div>

		<div class="pagination mt-3"></div>

	</div>
</section>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="<?= base_url('assets/js/jquery.redirect.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
	// const isMobile = navigator.userAgentData.mobile;

	$(document).ready(function() {
		// ISI DATA PILIH KELAS
		$.get(BASE_URL + 'student/get_class', function(data) {
			$.each(data, function(i, val) {
				$('select[name="pilih-kelas"]').append(`<option value="${val.class_id}">${val.class_name}</option>`);
			});

		});

		$('.pilih-kelas').select2();

		// ISI DATA TOTAL SISWA
		$.get(BASE_URL + 'student/get_total', function(data) {
			$('.stat-number').text(data.total_row);
		});
	});

	// TEKAN ENTER CARI SISWA
	$('input[name="nama-siswa"]').keyup(function(event) {
		if (event.keyCode === 13) $("#search").click();
	});

	// DOWNLOAD DATA STUDENT
	$('#download').click(function(e) {
		e.preventDefault();

		$.redirect(BASE_URL + "student/download", {
				kelas: $('select[name="pilih-kelas"]').val(),
				nama: $('input[name="nama-siswa"]').val()
			},
			"POST", "_blank");
	});
</script>

<!-- CHART MURID PER KELAS -->

<!-- CHART ABSENSI LAKI-LAKI -->
<script>
	$(document).ready(function() {
		let absensiLakiLaki = document.querySelector('#absensi-laki-laki');
		if (absensiLakiLaki) {
			Highcharts.chart('absensi-laki-laki', {
				chart: {
					plotBackgroundColor: null,
					plotBorderWidth: null,
					plotShadow: false,
					type: 'pie',
					height: 240,
					marginTop: (isUserUsingMobile()) ? -40 : -80
				},
				colors: ['#37d7cf', '#ee807f', '#6f8ff2', '#f19f6f', '#8e96c4', '#FF9655', '#FFF263', '#6AF9C4'],
				title: {
					text: '',
					align: 'left'
				},
				tooltip: {
					pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
				},
				accessibility: {
					point: {
						valueSuffix: '%'
					}
				},
				plotOptions: {
					pie: {
						allowPointSelect: true,
						cursor: 'pointer',
						dataLabels: {
							enabled: false
						},
						showInLegend: true,
					}
				},
				legend: {
					enabled: true,
					floating: false,
					borderWidth: 0,
					align: 'justify',
					layout: 'horizontal',
					verticalAlign: 'bottom',
					labelFormatter: function() {
						return '<span style="color:' + this.color + '">' + this.name + ': </span>(<b>' + this.y + '%)<br/>';
					},
					y: 20
				},
				series: [{
					name: 'Siswa',
					colorByPoint: true,
					data: [{
						name: 'Normal',
						y: 74.77,
						sliced: false,
						selected: false
					}, {
						name: 'Izin',
						y: 12.82
					}, {
						name: 'Sakit',
						y: 4.63
					}, {
						name: 'Terlambat',
						y: 2.44
					}, {
						name: 'Alfa',
						y: 2.02
					}],
					size: '60%',
					innerSize: '50%',
				}]
			});
		}
	});
</script>

<!-- CHART ABSENSI Perempuan -->
<script>
	$(document).ready(function() {
		let absensiPerempuan = document.querySelector('#absensi-perempuan');
		if (absensiPerempuan) {
			Highcharts.chart('absensi-perempuan', {
				chart: {
					plotBackgroundColor: null,
					plotBorderWidth: null,
					plotShadow: false,
					type: 'pie',
					height: 240,
					marginTop: (isUserUsingMobile()) ? -40 : -80
				},
				colors: ['#37d7cf', '#ee807f', '#6f8ff2', '#f19f6f', '#8e96c4', '#FF9655', '#FFF263', '#6AF9C4'],
				title: {
					text: '',
					align: 'left'
				},
				tooltip: {
					pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
				},
				accessibility: {
					point: {
						valueSuffix: '%'
					}
				},
				plotOptions: {
					pie: {
						allowPointSelect: true,
						cursor: 'pointer',
						dataLabels: {
							enabled: false
						},
						showInLegend: true
					}
				},
				legend: {
					enabled: true,
					floating: false,
					borderWidth: 0,
					align: 'justify',
					layout: 'horizontal',
					verticalAlign: 'bottom',
					labelFormatter: function() {
						return '<span style="color:' + this.color + '">' + this.name + ': </span>(<b>' + this.y + '%)<br/>';
					},
					y: 20
				},
				series: [{
					name: 'Siswa',
					colorByPoint: true,
					data: [{
						name: 'Normal',
						y: 80.77,
						sliced: false,
						selected: false
					}, {
						name: 'Izin',
						y: 5.82
					}, {
						name: 'Sakit',
						y: 5.63
					}, {
						name: 'Terlambat',
						y: 1.44
					}, {
						name: 'Alfa',
						y: 0
					}],
					size: '60%',
					innerSize: '50%',
				}]
			});
		}
	});
</script>

<!-- CHART ABSEN NORMAL ABNORMAL  -->

<script>
	// const ctx = document.getElementById('myChart');
	// let data = <?//=json_encode($kelas)?>;

	// let labels 		= [];
	// let jumlahMurid = [];

	// $.each(data, function (i, val) { 
	// 	 labels.push(val.class_name);
	// 	 jumlahMurid.push(val.value);
	// });

	// new Chart(ctx, {
	// 	type: 'bar',
	// 	data: {
	// 		// labels: ['1.1', '1.2', '2.1', '2.2', '3.1', '3.2'],
	// 		labels: labels,
	// 		datasets: [
	// 				{
	// 					label: 'Jumlah Siswa',
	// 					// data: [35, 30, 29, 28, 30, 25,35, 30, 29, 28, 30, 25,35, 30, 29, 28, 30, 25],
	// 					data: jumlahMurid,
	// 					backgroundColor: [
	// 						'rgba(255, 99, 132, 0.9)',
	// 						'rgba(255, 159, 64, 0.9)',
	// 						'rgba(255, 205, 86, 0.9)',
	// 						'rgba(75, 192, 192, 0.9)',
	// 						'rgba(54, 162, 235, 0.9)',
	// 						'rgba(153, 102, 255, 0.9)',
	// 						'rgba(201, 203, 207, 0.9)',
	// 						'rgba(255, 99, 132, 0.9)',
	// 						'rgba(255, 159, 64, 0.9)',
	// 						'rgba(255, 205, 86, 0.9)',
	// 						'rgba(75, 192, 192, 0.9)',
	// 						'rgba(54, 162, 235, 0.9)',
	// 						'rgba(153, 102, 255, 0.9)',
	// 						'rgba(201, 203, 207, 0.9)'
	// 					],
	// 					borderWidth: 1
	// 				}
	// 			]
	// 	},
	// 	options: {
	// 		scales: {
	// 		y: {
	// 			beginAtZero: true
	// 		}
	// 		}
	// 	}
	// });
</script>

<script>
	let listKelas = <?= $list_kelas ?>;
	// let absentDates = <?//= $absent_dates ?>;
	//absentDates = absentDates.map(function(num) {
	//	return num + 1
	//}); // tiap2 tanggal + 1 karna dari php tanggal nya di awali dengan angka 0

	let men = <?= $student_men ?>;
	let woman = <?= $student_woman ?>;

	let chart = Highcharts.chart('chart-absensi-harian', {
		chart: {
			type: 'column'
		},
		title: {
			text: '',
			align: 'left'
		},
		// subtitle: {
		// 	text: 'Data Bulan',
		// 	align: 'left'
		// },
		xAxis: {
			// categories: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
			// categories: absentDates,
			categories: listKelas,
			crosshair: true,
			accessibility: {
				description: 'Kelas'
			}
		},
		yAxis: {
			min: 0,
			title: {
				text: 'Banyaknya Siswa'
			}
		},
		tooltip: {
			valueSuffix: ' (Siswa)'
		},
		plotOptions: {
			column: {
				pointPadding: 0.2,
				borderWidth: 0
			}
		},
		series: [{
				name: 'Laki-laki',
				// data: [200, 175, 185, 198, 159, 167, 178, 165, 196, 165]
				data: men
			},
			{
				name: 'Perempuan',
				// data: [98, 78, 86, 98, 87, 79, 86, 98, 67, 98]
				data: woman
			}
		]
	});


	// ##################### CHART MURID PER KELAS #####################
	// let dataMurid = <?=json_encode($dataCartKelas, true); ?>
	// let dataAbsensi = [
	//         ['Masuk', 73.86],
	//         ['Izin', 5.52],
	//         ['Sakit', 2.98],
	//         ['Tanpa Keterangan', 1.90],
	//     ];

	// let containerChart = document.querySelector('#container-chart');
	// if(containerChart){
	// 	Highcharts.chart('container-chart', {
	// 		chart: {
	// 			type: 'column'
	// 		},
	// 		title: {
	// 			text: 'Data Semua Siswa'
	// 		},
	// 		subtitle: {
	// 			text: ''
	// 		},
	// 		xAxis: {
	// 			type: 'category',
	// 			labels: {
	// 				autoRotation: [-45, -90],
	// 				style: {
	// 					fontSize: '13px',
	// 					fontFamily: 'Verdana, sans-serif'
	// 				}
	// 			}
	// 		},
	// 		yAxis: {
	// 			min: 0,
	// 			title: {
	// 				text: 'Siswa'
	// 			}
	// 		},
	// 		legend: {
	// 			enabled: false
	// 		},
	// 		tooltip: {
	// 			pointFormat: '<b>{point.y:.f} siswa</b>'
	// 		},
	// 		series: [{
	// 			name: 'Population',
	// 			colors: [
	// 				'#dc3545', '#198754', '#0d6efd', '#ffc107', '#6f42c1', '#fd7e14', '#0dcaf0',
	// 				'#7f3b3b', '#F36EF4', '#2b7c85', '#4551d5', '#3e5ccf',
	// 				'#3667c9', '#2f72c3', '#277dbd', '#1f88b7', '#1693b1', '#0a9eaa',
	// 				'#03c69b',  '#00f194'
	// 			],
	// 			colorByPoint: true,
	// 			groupPadding: 0,
	// 			data: dataMurid,
	// 			dataLabels: {
	// 				enabled: true,
	// 				rotation: -90,
	// 				color: '#FFFFFF',
	// 				inside: true,
	// 				verticalAlign: 'top',
	// 				format: '{point.y:f}', // one decimal
	// 				y: 10, // 10 pixels down from the top
	// 				style: {
	// 					fontSize: '13px',
	// 					fontFamily: 'Verdana, sans-serif'
	// 				}
	// 			}
	// 		}]
	// 	});
	// }

	// let containerChart2 = document.querySelector('#container-chart2');
	// if(containerChart2){
	// 	Highcharts.setOptions({
	// 		colors: Highcharts.map(Highcharts.getOptions().colors, function (color) {
	// 			return {
	// 				radialGradient: {
	// 					cx: 0.5,
	// 					cy: 0.3,
	// 					r: 0.7
	// 				},
	// 				stops: [
	// 					[0, color],
	// 					[1, Highcharts.color(color).brighten(-0.3).get('rgb')] // darken
	// 				]
	// 			};
	// 		})
	// 	});

	// 	Highcharts.chart('container-chart2', {
	// 		chart: {
	// 			plotBackgroundColor: null,
	// 			plotBorderWidth: 0,
	// 			plotShadow: false
	// 		},
	// 		title: {
	// 			text: 'Absensi<br>Agustus<br>2024',
	// 			align: 'center',
	// 			verticalAlign: 'middle',
	// 			y: 60,
	// 			style: {
	// 				fontSize: '1.1em'
	// 			}
	// 		},
	// 		tooltip: {
	// 			pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
	// 		},
	// 		accessibility: {
	// 			point: {
	// 				valueSuffix: '%'
	// 			}
	// 		},
	// 		plotOptions: {
	// 			pie: {
	// 				dataLabels: {
	// 					enabled: true,
	// 					distance: -50,
	// 					style: {
	// 						fontWeight: 'bold',
	// 						color: 'white'
	// 					}
	// 				},
	// 				startAngle: -90,
	// 				endAngle: 90,
	// 				center: ['50%', '75%'],
	// 				size: '110%'
	// 			}
	// 		},
	// 		series: [{
	// 			type: 'pie',
	// 			innerSize: '50%',
	// 			name: 'Kehadiran',
	// 			colors: [
	// 				'#dc3545', '#198754', '#0d6efd', '#ffc107', '#6f42c1', '#fd7e14', '#0dcaf0',
	// 				'#7f3b3b', '#F36EF4', '#2b7c85', '#4551d5', '#3e5ccf',
	// 				'#3667c9', '#2f72c3', '#277dbd', '#1f88b7', '#1693b1', '#0a9eaa',
	// 				'#03c69b',  '#00f194'
	// 			],
	// 			data: dataAbsensi,
	// 		}]
	// 	});
	// }
</script>

<script>
	// ######################################### DATA TABLE SISWA #########################################

	// INISIALISASI TABLE TUGAS
	var tableSiswa = $('#table-siswa').DataTable({
		serverSide: true,
		ordering: false,
		ajax: {
			url: BASE_URL + 'student/search',
			method: 'GET',
			data: {}
		},
		// drawCallback: function (settings) { 
		// 	// Here the response
		// 	var response = settings.json;
			
		// 	// draw chart absensi harian
		// 	chart.series[0].setData([response.total_m]);
		// 	chart.series[1].setData([response.total_f]);
		// },
		select: {
			style: 'multi',
			selector: 'td:first-child'
		},
		columns: [{
			data: 'student_id',
			// visible: false
			render: function(data, type, row, meta) {
				return meta.row + meta.settings._iDisplayStart + 1;
			}
		}, {
			data: 'student_name'
		}, {
			data: 'class_name'
		}, {
			data: 'email'
		}, {
			data: 'active',
			class: 'text-center',
			render(data, type, row, meta) {
				let status = (data == 1) ? '<h6><span class="badge bg-success">Aktif</span></h6>' : '<h6><span class="badge bg-danger">Tidak Aktif</span></h6>';
				return status;
			}
		}, {
			data: null,
			class: 'text-center',
			render(data, type, row, meta) {
				return `<a href="${BASE_URL+'student/detail/'+row.student_id}" class="btn btn-primary btn-sm rounded-2"><i class="bi bi-eye"></i></a>`;
			}
		}]
	});

	/**
	 * ************************************
	 *          SEARCH / FILTER
	 * ************************************
	 */

	$('#search').on('click', function() {
		tableSiswa.columns(0).search($('input[name="nama-siswa"]').val()).draw();
	});

	$('select[name="pilih-kelas"]').on('change', function(e) {
		tableSiswa.columns(1).search(e.currentTarget.value).draw();
	});
</script>
