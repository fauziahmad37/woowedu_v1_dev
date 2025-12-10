<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<?php if ($this->session->flashdata('expired_soon')) : ?>
	<script>
		Swal.fire({
			width: '660px',
			showCancelButton: false,
			showConfirmButton: false,
			html: `<div class="text-start mx-4 mt-4">
			<img class="mb-4" src="<?= base_url() ?>assets/images/notification_empty.png" />
			<h6 class="text-warning text-left">Pemberitahuan: <?= $_SESSION['expired_soon']['message'] ?></h6>
			<p class="">Halo <?= $_SESSION['nama'] ?></p>
			<p class="">Akun demo Anda akan berakhir pada tanggal <?= date('d M Y, H:i', strtotime($_SESSION['expired_soon']['date_limit'])) ?>. Jangan lewatkan kesempatan untuk terus menikmati semua fitur premium kami dengan melakukan upgrade sekarang!</p>
			<div class="d-grid gap-2 d-md-flex justify-content-md-end">
				<button class="btn btn-danger text-white" type="button">Upgrade Sekarang</button>
			</div>
		</div>`,
		});
	</script>
<?php endif; ?>

<script>
	$('.btn-tab-siswa').on('click', function(e) {
		$('.tab-siswa').removeClass('d-block');
		$('.tab-guru').removeClass('d-block');
		$('.tab-siswa').removeClass('d-none');
		$('.tab-guru').removeClass('d-none');

		$('.tab-siswa').addClass('d-block');
		$('.tab-guru').addClass('d-none');

		$('.btn-tab-siswa').removeClass('btn-primary');
		$('.btn-tab-siswa').removeClass('btn-light');
		$('.btn-tab-guru').removeClass('btn-primary');
		$('.btn-tab-guru').removeClass('btn-light');

		$('.btn-tab-siswa').addClass('btn-primary');
		$('.btn-tab-guru').addClass('btn-light');
	});

	$('.btn-tab-guru').on('click', function(e) {
		$('.tab-siswa').removeClass('d-block');
		$('.tab-guru').removeClass('d-block');
		$('.tab-siswa').removeClass('d-none');
		$('.tab-guru').removeClass('d-none');

		$('.tab-guru').addClass('d-block');
		$('.tab-siswa').addClass('d-none');

		$('.btn-tab-siswa').removeClass('btn-primary');
		$('.btn-tab-siswa').removeClass('btn-light');
		$('.btn-tab-guru').removeClass('btn-primary');
		$('.btn-tab-guru').removeClass('btn-light');

		$('.btn-tab-guru').addClass('btn-primary');
		$('.btn-tab-siswa').addClass('btn-light');
	});

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
				text: 'Data Semua Siswa'
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
				pointFormat: '<b>{point.y:.f} siswa</b>'
			},
			series: [{
				name: 'Population',
				colors: [
					'#9b20d9', '#9215ac', '#861ec9', '#7a17e6', '#7010f9', '#691af3',
					'#6225ed', '#5b30e7', '#533be1', '#4c46db', '#4551d5', '#3e5ccf',
					'#3667c9', '#2f72c3', '#277dbd', '#1f88b7', '#1693b1', '#0a9eaa',
					'#03c69b', '#00f194'
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

	// #################### Chart Guru Aktif/Tidak Aktif ####################
	let teacherStatusChart = <?= json_encode($teacher_status_chart, true) ?>;
	let containerChart2 = document.querySelector('#container-chart2');
	if (containerChart2) {
		// Data retrieved from https://www.ssb.no/en/transport-og-reiseliv/landtransport/statistikk/bilparken
		// Radialize the colors
		// Highcharts.setOptions({
		// 	colors: Highcharts.map(Highcharts.getOptions().colors, function(color) {
		// 		return {
		// 			radialGradient: {
		// 				cx: 0.5,
		// 				cy: 0.3,
		// 				r: 0.7
		// 			},
		// 			stops: [
		// 				[0, color],
		// 				[1, Highcharts.color(color).brighten(-0.3).get('rgb')] // darken
		// 			]
		// 		};
		// 	})
		// });

		// Build the chart
		Highcharts.chart('container-chart2', {
			chart: {
				plotBackgroundColor: null,
				plotBorderWidth: null,
				plotShadow: false,
				type: 'pie'
			},
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
						enabled: true,
						format: '<span style="font-size: 1.2em"><b>{point.name}</b>' +
							'</span><br>' +
							'<span style="opacity: 0.6">{point.percentage:.1f}' +
							'% - {point.y:f} User</span>',
						connectorColor: 'rgba(128,128,128,0.5)'
					}
				}
			},
			series: [{
				name: 'Share',
				// data: [
				// 	{ name: 'Petrol', y: 938899 },
				// 	{ name: 'Diesel', y: 1229600 },
				// 	{ name: 'Electricity', y: 325251 },
				// 	{ name: 'Other', y: 238751 }
				// ]
				data: teacherStatusChart
			}]
		});

	}
</script>

<script>
	// $(document).ready(function () {
	// 	let userLevel = <? //=$this->session->userdata('user_level') ?? trim('')?>;
	// 	if(userLevel == 6){
	// 		guruChart();
	// 	}
	// });
</script>

<!-- CHART MURID PER KELAS -->
<!-- <script>
	const ctx = document.getElementById('muridPerKelas');
	let dataMurid = <? //=json_encode($student_class)?>;

	let labels 		= [];
	let jumlahMurid = [];

	$.each(dataMurid, function (i, val) { 
		 labels.push(val.class_name);
		 jumlahMurid.push(val.value);
	});

	new Chart(ctx, {
		type: 'bar',
		data: {
			// labels: ['1.1', '1.2', '2.1', '2.2', '3.1', '3.2'],
			labels: labels,
			datasets: [
					{
						label: 'Kelas',
						// data: [35, 30, 29, 28, 30, 25,35, 30, 29, 28, 30, 25,35, 30, 29, 28, 30, 25],
						data: jumlahMurid,
						backgroundColor: [
							'rgba(255, 99, 132, 0.9)',
							'rgba(255, 159, 64, 0.9)',
							'rgba(255, 205, 86, 0.9)',
							'rgba(75, 192, 192, 0.9)',
							'rgba(54, 162, 235, 0.9)',
							'rgba(153, 102, 255, 0.9)',
							'rgba(201, 203, 207, 0.9)',
							'rgba(255, 99, 132, 0.9)',
							'rgba(255, 159, 64, 0.9)',
							'rgba(255, 205, 86, 0.9)',
							'rgba(75, 192, 192, 0.9)',
							'rgba(54, 162, 235, 0.9)',
							'rgba(153, 102, 255, 0.9)',
							'rgba(201, 203, 207, 0.9)'
						],
						borderWidth: 1
					}
				]
		},
		options: {
			scales: {
			y: {
				beginAtZero: true
			}
			}
		}
	});
</script> -->

<!-- CHART GURU -->
<script>
	// function guruChart(){
	// 	// Create root and chart
	// 	var root = am5.Root.new("guruChart");
	// 	var chart = root.container.children.push( 
	// 	am5percent.PieChart.new(root, {
	// 			layout: root.verticalHorizontal
	// 		}) 
	// 	);

	// 	var Teacherdata = <? //=json_encode($teacher_status) ?>;

	// 	// Create series
	// 	var series = chart.series.push(
	// 		am5percent.PieSeries.new(root, {
	// 			name: "Series",
	// 			valueField: "sales",
	// 			categoryField: "country"
	// 		})
	// 	);

	// 	// SETTING WARNA
	// 	series.get("colors").set("colors", [
	// 		am5.color(0x556ee6),
	// 		am5.color(0xf46a6a),
	// 		am5.color(0x5aaa95),
	// 		am5.color(0x86a873),
	// 		am5.color(0xbb9f06)
	// 	]);

	// 	series.data.setAll(Teacherdata);

	// 	// Add legend
	// 	var legend = chart.children.push(am5.Legend.new(root, {
	// 		centerX: am5.percent(50),
	// 		x: am5.percent(70),
	// 		layout: root.horizontalLayout
	// 	}));

	// 	legend.data.setAll(series.dataItems);

	// }

	const newsCarousel = document.getElementById('carouselExampleCaptions');

	/**
	 * 
	 * Listener for boottrap caoursel
	 * 
	 * @param {boostrap.Carousel}
	 * @return void
	 */
	newsCarousel.addEventListener('slide.bs.carousel', e => {
		e.stopPropagation();
	});
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const today = new Date();

    // format YYYY-MM-DD
    const formatDate = (date) => {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    };

    // tanggal hari ini
    const todayFormatted = formatDate(today);

    // tanggal 1 bulan berjalan
    const firstDayOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
    const firstDayFormatted = formatDate(firstDayOfMonth);

    document.getElementById("start-date").value = firstDayFormatted;
    document.getElementById("end-date").value = todayFormatted;
});
</script>
