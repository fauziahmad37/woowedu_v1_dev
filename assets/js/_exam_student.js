let csrfToken = document.querySelector('meta[name="csrf_token"]') ;
const form = document.forms['form-add'];
var is_update = false;

$(document).ready(function () {
	let table = $('#table-exam-student').DataTable({
		ajax: BASE_URL + 'asesmen/get_exam_student',
		serverSide: true,
		processing: true,
		columns: [
			{
				data: null,
				visible: false,
				render(data, type, row, meta){
					let es_id;
					if(row.exam_answer){
						es_id = row.exam_answer.es_id
					}
					return es_id;
				}
			},
			{
				data: 'nis'
			},
			{
				data: 'student_name',
			},
			{
				data: null,
				render(data, type, row, meta){
					let tanggalMengumpulkan;
					if(row.exam_answer && row.exam_answer.exam_submit){
						tanggalMengumpulkan = moment(row.exam_answer.exam_submit).format('D MMM Y, H:m');
					}
					return tanggalMengumpulkan
				}
			},
			{
				data: null,
				render(data, type, row, meta){
					let nilai;
					if(row.exam_answer && row.exam_answer.exam_total_nilai){
						nilai = row.exam_answer.exam_total_nilai
					}
					return nilai;
				}
			},
			{
				data: null,
				render(data, row, type, meta) {
					let view;
					if(type.exam_answer && type.exam_answer.exam_submit){
						view = `<div class="btn-group btn-group-sm float-right">
									<a href="${BASE_URL+'asesmen/show_answer?id='+type.exam_answer.exam_id+"&student_id="+type.student_id}" class="btn btn-primary view_asesmen"><i class="fa-solid fa-eye text-white"></i></a>
									<button type="button" class="btn btn-sm btn-success text-white give-score" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="fa-solid fa-pencil text-white"></i></button>
									
								</div>`;
					}
               	 	return view;
				}
			}
		],
		columnDefs: [{
			"defaultContent": "-",
			"targets": "_all"
		}]
	}).columns(3).search($('input[name="exam_id"]').val()).draw();

	/**
	 * Cari
	 */	 

	$('#cari').on('click', function(e){
		e.preventDefault();
		table.columns(0).search($('select[name="select-mapel"]').val()).draw();
		table.columns(1).search($('input[name="s_title"]').val()).draw();
	});
	
	/** 
	 * Edit Materi 
	*/
	$('#table-exam-student tbody').on('click', '.give-score', e => {
		is_update = true;
		let target = e.target;
		let row = table.rows($(target).parents('tr')).data();
		$('input[name="exam_student_id"]').val(row[0].exam_answer.es_id);
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
			},
			dataType: "JSON",
			success: function (response) {
				if(response.success){
					Swal.fire({
						title: "Sukses!",
						text: "Your file has been deleted.",
						icon: "success"
					});

					window.location.reload();
				}
			}
		});
	});
});

