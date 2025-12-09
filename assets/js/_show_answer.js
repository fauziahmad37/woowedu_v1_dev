let studentId = $('input[name="student_id"]').val();
let examId = $('input[name="exam_id"]').val();

$(document).ready(function () {
	let table = $('#table-exam-answer').DataTable({
		ajax: BASE_URL + 'asesmen/get_exam_answer',
		serverSide: true,
		processing: true,
		columns: [
			{
				data: 'soal_id',
				visible: false
			},
			{
				data: 'question',
			},
			{
				data: null,
				render(data, type, row, meta){
					let tipe = (row.type == 1) ? `PG` : `Isian`;
					return tipe;
				}
			},
			{
				data: 'exam_answer',
			},
			{
				data: 'correct_answer',
				visible: (studentId) ? false : true
			},
			{
				data: null,
				render(data, type, row, meta){
					let keterangan = '';
					if(row.type == 1){
						if(row.exam_answer.toUpperCase() == row.correct_answer.toUpperCase()){
							keterangan = 'Benar';
						}else{
							keterangan = 'Salah';	
						}
					}
					return keterangan;
				}
			},
		],
	});
	
	table.columns(0).search($('input[name="student_id"]').val()).draw();
	table.columns(2).search($('input[name="exam_id"]').val()).draw();

});

/**
	 * Save Score
	 */
$('.save-score').on('click', e => {
	$.ajax({
		type: "POST",
		url: BASE_URL+"asesmen/save_score",
		data: {
			exam_student_id: $('input[name="exam_student_id"]').val(),
			score: $('input[name="score"]').val(),
			notes: $('textarea[name="notes"]').val(),
		},
		dataType: "JSON",
		success: function (response) {
			if(response.success){
				Swal.fire({
					title: "Sukses!",
					text: "Data Berhasil di Simpan.",
					icon: "success"
				});
				setTimeout(()=>{
					location.reload();
				}, 1500);
			}
		}
	});
});

/** 
 * Chart Donut
 * This chart is a donut chart that displays the percentage of correct and incorrect answers.
 */

const donnutChart = () => {
Highcharts.chart('donut-chart', {
	chart: {
		type: 'pie',
		custom: {},
		events: {
			render() {
				const chart = this,
					series = chart.series[0];
				let customLabel = chart.options.chart.custom.label;

				if (!customLabel) {
					customLabel = chart.options.chart.custom.label =
						chart.renderer.label(
							`<div style="text-align:center;">
							<h2 style="color:#34C38F; font-size: 2em; font-weight: 700;">${correctPercentage}%</h2>
							<br/>
							<h5 style="color: #74788D; font-size: 1em; margin-top:5px;">Keberhasilan</h5>
							</div>`
						)
						.css({
							color: '#000',
							textAnchor: 'middle'
						})
						.add();
				}

				const x = series.center[0] + chart.plotLeft,
					y = series.center[1] + chart.plotTop -
					(customLabel.attr('height') / 4);

				customLabel.attr({
					x,
					y
				});
				// Set font size based on chart diameter
				customLabel.css({
					fontSize: `${series.center[2] / 12}px`
				});
			}
		}
	},
	accessibility: {
		point: {
			valueSuffix: '%'
		}
	},
	title: {
		text: ''
	},
	subtitle: {
		text: ''
	},
	tooltip: {
		enabled: false,
	},
	legend: {
		enabled: false
	},
	plotOptions: {
		pie: {
			size: '130%',

		},
		series: {
			allowPointSelect: false,
			cursor: 'pointer',
			borderRadius: 0,
			dataLabels: [{
				enabled: false,
				distance: 20,
				format: '{point.name}'
			}, {
				enabled: false,
				distance: -15,
				format: '{point.percentage:.0f}%',
				style: {
					fontSize: '0.9em'
				}
			}],
			showInLegend: true
		}
	},
	series: [{
		name: 'Registrations',
		colorByPoint: true,
		innerSize: '85%',
		data: [{
			name: 'EV',
			y: wrongPercentage,
			color: '#E3E4E8'
		}, {
			name: 'Hybrids',
			y: correctPercentage,
			color: '#34C38F'
		}]
	}]
});
};

donnutChart();

