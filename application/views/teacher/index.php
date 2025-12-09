<!-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> -->

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

<style>
	.highcharts-credits {
		display: none;
	}

	.disabled-link {
		pointer-events: none;
	}
</style>

<section class="explore-section section-padding" id="section_2">
	<div class="container">
		<h4>Semua Guru</h4>

		<div class="tab-guru">
			<div class="row mt-5">
				<div class="col-4 d-flex">
					<div class="bg-secondary-subtle rounded-4 p-3 d-inline-block"><i class="bi bi-people fs-3 mx-2 fw-bold"></i></div>
					<div class="d-inline-block ms-4 pt-3">
						<p class="fs-3 fw-bold text-secondary" style="line-height: 0px;"><?= $teacher_status_chart[0][1] + $teacher_status_chart[1][1] ?></p>
						<p class="lh-1 fs-5 mt-4 text-body-secondary" style="line-height: 0px;">Jumlah Guru</p>
					</div>
				</div>

				<div class="col-4 d-flex">
					<div class="bg-success-subtle rounded-4 p-3 d-inline-block"><i class="bi bi-people fs-3 mx-2 fw-bold"></i></div>
					<div class="d-inline-block ms-4 pt-3">
						<p class="fs-3 fw-bold text-success" style="line-height: 0px;"><?= $teacher_status_chart[0][1] ?></p>
						<p class="lh-1 fs-5 mt-4 text-body-secondary" style="line-height: 0px;">Jumlah Guru Aktif</p>
					</div>
				</div>

				<div class="col-4 d-flex">
					<div class="bg-danger-subtle rounded-4 p-3 d-inline-block"><i class="bi bi-people fs-3 mx-2 fw-bold"></i></div>
					<div class="d-inline-block ms-4 pt-3">
						<p class="fs-3 fw-bold text-danger" style="line-height: 0px;"><?= $teacher_status_chart[1][1] ?></p>
						<p class="lh-1 fs-5 mt-4 text-body-secondary" style="line-height: 0px;">Jumlah Guru Tidak Aktif</p>
					</div>
				</div>
			</div>

			<br><br>

			<figure class="highcharts-figure mt-5">
				<div id="container-chart"></div>
			</figure>
		</div>

		<!-- <div class="container border rounded p-4">
			<div class="row">	
				<div class="col-xl-2 col-lg-2 col-md-3 col-sm-3 col-xs-12">
					<div class="mb-3 circle-stat-card">
						<div class="d-flex flex-column" >
							<div class="row justify-content-around my-auto border-right">
								<div class="col-1 d-flex flex-column align-items-center">
									<span class="stat-circle mb-3">
										<span class="stat-number"></span>
									</span>
									<span>Jumlah Guru Aktif</span>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="col-xl-10 col-lg-10 col-md-9 col-sm-9 col-xs-12 mb-3 h-100">
					<h6 class="text-center">Total Tugas Dibuat</h6>
					
						<div class="card border rounded p-3 data-by-date">
							<input class="border-width-1 rounded-lg ml-3"  style="height: 40px; text-align:center; border-color: rgba(0, 0, 255, 0.3);" type="text" name="daterange" 
								value="<?php
										// if(isset($start)){ 
										// 	echo date('m/d/Y', strtotime($start));
										// } else { 
										// 	echo date('m', time()).'//1//'.date('Y', time()); 
										//}
										?> - <?php
												// if(isset($end)){ 
												// 	echo date('m/d/Y', strtotime($end)); 
												// }else{ 
												// 	echo date('m/d/Y', time()); 
												// }
												?>" />

							<div class="row data-content mt-4 justify-content-center">
								<canvas id="myChart" style="height: 200px;"></canvas>
							</div>
						</div>
				
				</div>
			</div>
		</div> -->

		<!-- <div class="row mt-3">
			<div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 col-xs-12">
				<select class="pilih-kelas form-control" name="pilih-kelas">
					<option value="">Semua Kelas</option>
				</select>
			</div>
		</div> -->

		<div class="container p-4 mt-3">
			<h6 class="total-data"></h6>

			<div class="row">
				<div class="col-xl-6 col-lg-6 col-md-8 col-sm-10 col-xs-10">
					<div class="d-flex justify-content-start">
						<input type="text" class="form-control me-2" placeholder="Pencarian guru" aria-label="Pencarian siswa" aria-describedby="basic-addon2" name="nama-siswa">
						<select type="text" class="form-select me-2" name="class_id">
							<option value="">-- Semua Kelas --</option>
							<?php foreach($kelas as $val): ?>
								<option value="<?=$val['class_id']?>"><?=$val['class_name']?></option>
							<?php endforeach ?>
						</select>
						<button id="search" class="btn btn-primary" type="button" style="flex: none;">Cari <i class="bi bi-search"></i></button>
					</div>
				</div>

				<div class="col-xl-6 col-lg-6 col-md-4 col-sm-2 col-xs-2" style="text-align: right;">
					<button class="btn btn-lg border-1 border-primary rounded-pill text-primary" id="download"><i class="bi bi-download"></i> Download</button>
				</div>
			</div>

			<div class="container mt-3 p-0">
				<table class="table table-rounded w-100" style="border-collapse: collapse;">
					<thead class="bg-primary text-white">
						<tr>
							<th>No</th>
							<th>Nama Guru</th>
							<th>Kelas</th>
							<th>Matapelajaran</th>
							<th>Status Terkini</th>
							<th>Aksi</th>
						</tr>
					</thead>
					<tbody id="table-body-content"></tbody>
				</table>

				<div class="pagination mt-3 justify-content-end"></div>
			</div>

		</div>

	</div>
</section>

<!-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> -->
<script src="<?= base_url('assets/js/jquery.redirect.js') ?>"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->
<!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script> -->
<!-- <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script> -->

<script>
	$(document).ready(function() {

		// #################### Chart Total Murid per Kelas #################### 
		let dataMurid = <?= json_encode($dataCartKelas, true) ?>;
		console.log(dataMurid)
		let containerChart = document.querySelector('#container-chart');
		if (containerChart) {
			Highcharts.chart('container-chart', {
				chart: {
					type: 'column'
				},
				title: {
					text: ''
				},
				subtitle: {
					text: ''
				},
				xAxis: {
					type: 'category',
					labels: {
						autoRotation: [-45, -90],
						style: {
							fontSize: '13px',
							fontFamily: 'Verdana, sans-serif'
						}
					}
				},
				yAxis: {
					min: 0,
					title: {
						text: 'Siswa'
					}
				},
				legend: {
					enabled: false
				},
				tooltip: {
					pointFormat: '<b>{point.y:.f} Guru</b>'
				},
				plotOptions: {
					series: {
						pointWidth: 20
					}
				},
				series: [{
					name: 'Population',
					colors: [
						'#281B93', '#281B93', '#281B93', '#281B93'
					],
					colorByPoint: true,
					groupPadding: 0,
					data: dataMurid,
					dataLabels: {
						enabled: true,
						rotation: -90,
						color: '#FFFFFF',
						inside: true,
						verticalAlign: 'top',
						format: '{point.y:f}', // one decimal
						y: 10, // 10 pixels down from the top
						style: {
							fontSize: '13px',
							fontFamily: 'Verdana, sans-serif'
						}
					}
				}]
			});
		}


		// ISI DATA PILIH KELAS
		// $.get(BASE_URL+'teacher/get_class', function(data){
		// 	$.each(data, function (i, val) { 
		// 		 $('select[name="pilih-kelas"]').append(`<option value="${val.class_id}">${val.class_name}</option>`);
		// 	});

		// });

		// $('.pilih-kelas').select2();

		// ISI DATA TOTAL GURU AKTIF
		$.get(BASE_URL + 'teacher/get_total', function(data) {
			$('.stat-number').text(data.total_row);
		});

		// INPUT DATE RANGE
		// $('input[name="daterange"]').daterangepicker({
		// 	opens: 'right',
		// 	minYear: 2000,
		// 	maxYear: 2025,
		// 	showDropdowns: true,
		// 	ranges: {
		// 		'Today': [moment().startOf('day'), moment().endOf('day')],
		// 		'Yesterday': [moment().subtract(1, 'days').startOf('day'), moment().subtract(1, 'days').endOf('day')],
		// 		'Last 7 Days': [moment().subtract(6, 'days').startOf('day'), moment().endOf('day')],
		// 		'This Month': [moment().startOf('month').startOf('day'), moment().endOf('month').endOf('day')],
		// 	}
		// }, function(start, end, label) {
			// console.log(start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
			// $('#myChart').html('');
			// getLineChart(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
		// });

	});

	// JALANKAN GRAFIK PERTAMA KALI KETIKA HALAMAN DI LOAD
	// let startDate = moment().startOf('month').startOf('day').format('YYYY-MM-DD');
	// let endDate = moment().startOf('day').format('YYYY-MM-DD');

	// getLineChart(startDate, endDate);

	// FUNGSI UNTUK UBAH DATA LINE CHART
	// function getLineChart(start, end) {
	// 	$.ajax({
	// 		type: "POST",
	// 		url: BASE_URL + "teacher/get_task_chart",
	// 		data: {
	// 			start: start,
	// 			end: end
	// 		},
	// 		dataType: "JSON",
	// 		success: function(response) {
	// 			drawLineChart(response);
	// 		}
	// 	});
	// }


	var currentPage = 1;
	load_data(1, 10);

	// KETIKA BUTTON CARI DI KLIK
	$('#search').on('click', function(e) {
		load_data();
	});

	// create function load data
	function load_data(page = 1, limit = 10) {
		let namaGuru = $('input[name="nama-siswa"]').val();

		$.ajax({
			type: "GET",
			url: BASE_URL + "teacher/search",
			data: {
				page: page,
				limit: limit,
				filter: {
					namaGuru: namaGuru, 
					class_id: $('select[name="class_id"]').val()
				}
			},
			success: function(response) {
				$('.total-data').text(response.total_records + ' Guru');

				$('#table-body-content').html('');
				$.each(response.data, function(key, value) {
					$('#table-body-content').append(`
						<tr class="bg-clear">
							<td>${key+1}</td>
							<td>${value.teacher_name}</td>
							<td>${value.teacher_class}</td>
							<td>${value.mapel}</td>
							<td class="text-center">${(value.status == 1) ? '<span class="bg-success-subtle p-2 rounded-3">Aktif</span>' : '<span class="btn bg-danger-subtle p-2 rounded-3">Tidak Aktif</span>'}</td>
							<td class="btn-eye text-center"><a href="${BASE_URL+'teacher/detail/'+value.teacher_id}" class="bg-primary p-2 rounded-3"><i class="bi bi-eye text-white"></i></a></td>
						</tr>
					`);
				});

				$('.pagination').html('');
				for (let i = 0; i < response.total_pages; i++) {
					if (currentPage == i + 1) {
						$('.pagination').append(`
							<li class="page-item active"><a class="page-link" href="#" onclick="page(${i+1}, event)">${i+1}</a></li>
						`);
					} else {
						$('.pagination').append(`
							<li class="page-item"><a class="page-link" href="#" onclick="page(${i+1}, event)">${i+1}</a></li>
						`);
					}

				}
			}
		});
	}

	// JIKA PAGE NUMBER DI KLIK
	function page(pageNumber, e) {
		e.preventDefault();
		currentPage = pageNumber;
		load_data(pageNumber);
	}

	// DOWNLOAD DATA STUDENT
	$('#download').click(function(e) {
		e.preventDefault();
		$.redirect(BASE_URL + "teacher/download", "GET", "_blank");
	});
</script>

<!-- CHART MURID PER KELAS -->
<script>
	// var myChart;

	// function drawLineChart(data) {

	// 	const ctx = document.getElementById('myChart').getContext("2d");
	// 	let dataChart = data;
	// 	let labels = [];
	// 	let jumlahTask = [];

	// 	$.each(dataChart, function(i, val) {
	// 		labels.push(val.tanggal);
	// 		jumlahTask.push(val.value);
	// 	});

	// 	let config = {
	// 		type: 'line',
	// 		data: {
	// 			// labels: ['1.1', '1.2', '2.1', '2.2', '3.1', '3.2'],
	// 			labels: labels,
	// 			datasets: [{
	// 				label: 'Tugas',
	// 				// data: [35, 30, 29, 28, 30, 25,35, 30, 29, 28, 30, 25,35, 30, 29, 28, 30, 25],
	// 				data: jumlahTask,
	// 				fill: false,
	// 				borderColor: 'rgb(75, 192, 192)',
	// 				tension: 0.1,
	// 			}]
	// 		},
	// 		options: {
	// 			scales: {
	// 				y: {
	// 					beginAtZero: true
	// 				}
	// 			},
	// 			responsive: true,
	// 			maintainAspectRatio: false
	// 		}
	// 	}

	// 	if (myChart) {
	// 		myChart.destroy();
	// 		myChart = new Chart(ctx, config);
	// 	} else {
	// 		myChart = new Chart(ctx, config);
	// 	}
	// }
</script>
