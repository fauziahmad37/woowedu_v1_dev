$(document).ready(function () {

	// zoom image soal
	$('.btn-zoom').on('click', function () {
		// get image src
		let imgSrc = $(this).siblings('img').attr('src');
		// set image src to modal
		$('#modal-zoom-image .modal-body').html('<img class="img-fluid" src="' + imgSrc + '" alt="image-soal">');
		// show modal
		$('#modal-zoom-image').modal('show');
	});

	/**
	 * DATA TABLE KUNCI JAWABAN
	 */

	let table2 = $('#table-skema-penilaian').DataTable({
		ajax: BASE_URL + 'asesmen/get_skema_penilaian',
		serverSide: true,
		processing: true,
		columns: [
			{
				data: 'soal_id',
				visible: false
			},
			{
				data: 'no_urut',
			},
			{
				data: 'question',
			},
			{
				data: 'answer',
			},
		],
	}).columns(2).search($('input[name="exam_id"]').val()).draw();


	/* 
	* DATA TABLE STUDENT COLLECT
	* @type {DataTable}
	*/
	let studentTable = $('#student-table').DataTable({
		processing: true,
		serverSide: true,
		order: [],
		bSort: false,
		ajax: {
			url: BASE_URL + "asesmen_standard/get_student_collect",
			type: "POST",
			data: function (data) {
				data.class_id = $('input[name="class_id"]').val();
				data.exam_id = $('input[name="exam_id"]').val();
			}
		},
		columns: [{
			data: null,
			render: function (data, type, row, meta) {
				return meta.row + meta.settings._iDisplayStart + 1;
			}
		},
		{
			data: 'nis'
		},
		{
			data: 'student_name'
		},
		{
			data: 'exam_submit'
		},
		{
			data: 'status'
		},
		{
			data: 'exam_score'
		},
		{
			data: null,
			render: function (data, type, row) {
				let btn = '';

				if (row.status == 'Sudah Dinilai') {
					// btn = `<a href="${BASE_URL}asesmen_standard/detail/${row.exam_id}/${row.student_id}" class="btn btn-primary text-white">
					btn = `<a href="${BASE_URL}asesmen/show_answer?id=${row.exam_id}&student_id=${row.student_id}" class="btn btn-primary py-1 px-2 text-white rounded-circle">
							<i class="fas fa-eye"></i>
						</a>`;
				}

				if (row.status == 'Menunggu Penilaian') {
					btn = `<a href="${BASE_URL}asesmen/show_answer?id=${row.exam_id}&student_id=${row.student_id}" class="btn btn-primary py-1 px-2 text-white rounded-circle">
							<i class="fas fa-eye text-white"></i>
						</a>`;
				}

				return btn;
			}
		}
		],
	});

	$('#cari').on('click', function (e) {
		studentTable.columns(4).search($('select[name="a_status"]').val()).draw();
		studentTable.columns(2).search($('input[name="a_name"]').val()).draw();
	});

	$('#download-student-collect-exam').on('click', function () {
		var exam_id = $('input[name="exam_id"]').val();
		window.open(BASE_URL + `asesmen_standard/download_student_collect_exam?exam_id= ${exam_id}&class_id=${$('input[name="class_id"]').val()}`, "_blank");
	});
});

function giveScore(exam_student_id) {
	console.log(exam_student_id);
	$('input[name="exam_student_id"]').val(exam_student_id);
	$('#exampleModal').modal('show');
}
