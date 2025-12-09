let csrfToken = document.querySelector('meta[name="csrf_token"]');
const form = document.forms['form-add'];
var is_update = false;
let teacher_id = $('input[name="teacher_id"]').val();
let student_id = $('input[name="student_id"]').val();
let class_id = $('input[name="class_id"]').val();
let btnGandakan = ``;

$(document).ready(function () {
	let table = $('#table-asesmen-standar').DataTable({
		ajax: BASE_URL + 'asesmen/getAsesmen',
		oLanguage: {
			sUrl: "https://cdn.datatables.net/plug-ins/1.10.9/i18n/Indonesian.json"
		},
		serverSide: true,
		processing: true,
		bSort: false,
		columns: [
			{
				data: 'materi_id',
				visible: false
			},
			{
				data: 'title',
			},
			{
				data: 'subject_name',
			},
			{
				data: 'class_name',
				visible: (student_id) ? false : true,
			},
			{
				data: 'start_date',
				class: 'text-center',
				render(data, type, row, meta) {
					return moment(row.start_date).format('DD MMM YYYY HH:mm');
				}
			},
			{
				data: 'end_date',
				class: 'text-center',
				render(data, type, row, meta) {
					return moment(row.end_date).format('DD MMM YYYY HH:mm');
				}
			},
			{
				data: 'exam_submit',
				class: 'text-center',
				visible: (student_id) ? true : false,
				render(data, type, row, meta) {
					let submitDt;
					let startDate = new Date(row.start_date);
					if (row.exam_submit) {
						submitDt = moment(row.exam_submit).format('DD MMM YYYY, HH:mm');
					} else {
						submitDt = `<h5><span class="badge text-danger bg-danger-subtle p-2" style="font-weight: 600; min-width: 140px;">Belum dikerjakan</span></h5>`
					
						if (startDate.getTime() > (new Date().getTime())) {
							submitDt = `<h5><span class="badge text-warning bg-warning-subtle p-2" style="font-weight: 600; min-width: 140px;">Belum Berlangsung</span></h5>`
						}
					}
					return submitDt
				}
			},
			{
				data: null,
				class: 'text-center',
				visible: (student_id) ? true : false,
				render(data, type, row, meta) {
					let status;
					let startDate = new Date(row.start_date);
					let endDate = new Date(row.end_date);
					// jika exam_submit nya ada dan exam_total_nilai nya null maka status nya menunggu penilaian
					if (row.exam_submit && !row.exam_total_nilai) {
						status = `<h5><span class="badge text-danger bg-danger-subtle p-2" style="font-weight: 600; min-width: 140px;">Menunggu penilaian</span></h5>`
						// jika tidak ada exam_submit
					} else if (row.exam_submit && row.exam_total_nilai) {
						status = `<h5><span class="badge text-success bg-success-subtle p-2" style="font-weight: 600; min-width: 140px;">Sudah dinilai</span></h5>`
					} else {
						status = `-`
						if(endDate.getTime() < (new Date().getTime())){
							status = `<h5><span class="badge text-dark bg-dark-subtle p-2" style="font-weight: 600; min-width: 140px;">Tidak Mengerjakan</span></h5>`
						}

						if(startDate.getTime() > (new Date().getTime())){
							status = `<h5><span class="badge text-warning bg-warning-subtle p-2" style="font-weight: 600; min-width: 140px;">Belum Berlangsung</span></h5>`
						}
						
					}
					return status
				}
			},
			{
				data: null,
				class: 'text-center',
				visible: (student_id) ? true : false,
				render(data, type, row, meta) {
					return (row.exam_total_nilai) ? `<strong>${row.exam_total_nilai}</strong>` : `-`
				}
			},
			{
				data: null,
				class: 'text-center',
				visible: (teacher_id) ? true : false,
				render(data, type, row, meta) {
					let status;
					const startDate = new Date(row.start_date);
					const endDate = new Date(row.end_date);

					if (row.status == 0) {
						status = `<span class="badge text-dark p-2 w-100" style="font-size: 12px; font-weight:600; background-color: #D4D1E9;">Draft</span>`
					} else {
						// STATUS SEDANG BERLANGSUNG
						if(startDate.getTime() <= (new Date().getTime()) && endDate.getTime() >= (new Date().getTime())){
							status = `<span class="badge bg-success-subtle text-success p-2" style="font-size: 12px; font-weight:600;">Sedang berlangsung</span>`
						}

						// STATUS BERAKHIR
						if(endDate.getTime() < (new Date().getTime())){
							status = `<span class="badge bg-secondary-subtle text-secondary p-2" style="font-size: 12px; font-weight:600; min-width: 140px;">Berakhir</span>`
						}

						// STATUS AKAN DATANG
						if(startDate.getTime() > (new Date().getTime())){
							status = `<span class="badge bg-warning-subtle text-warning p-2" style="font-size: 12px; font-weight:600; min-width: 140px;">Akan datang</span>`
						}
					}

					return status;
				}
			},
			{
				data: null,
				className: 'text-center',
				render(data, row, type, meta) {
					const startDate = new Date(type.start_date);
					const endDate = new Date(type.end_date);

					var view = '';

					if (teacher_id) {
						view = `<div class="drop-down">
								<button class="btn btn-primary btn-sm show-action rounded-circle" data-bs-toggle="dropdown" aria-expanded="false">
									<i class="fa-solid fa-ellipsis-h text-white"></i>
								</button>
								<div class="dropdown-menu text-center p-2 shadow border-0" style="min-width: 5rem;">
									${(teacher_id) ? `<a href="${BASE_URL + 'asesmen/view/' + type.exam_id}" class="btn btn-sm btn-primary view_asesmen" style="width: 34px;"><i class="fa-solid fa-eye text-white" style="margin-left: -2px;"></i></a>
									<button class="btn btn-sm btn-danger delete_materi"><i class="fa-solid fa-trash text-white"></i></button>` : ``}
									
									${(teacher_id && type.status == 0) ? `<a href="${BASE_URL + 'asesmen_standard/edit/' + type.exam_id}" class="btn btn-sm btn-success edit_materi" style="width: 34px;"><i class="fa-solid fa-pencil text-white"></i></a>` : ``}

									${(student_id && (type.status == 1) && !type.exam_submit && startDate.getTime() <= (new Date().getTime()) && endDate.getTime() >= (new Date().getTime())) ? `<a role="button" class="btn btn-sm btn-primary view_student">
										 <i class="fa-solid fa-file-pen text-white"></i></a>` : ``}

									${(student_id && type.exam_total_nilai) ? `<a href="${BASE_URL + 'asesmen/show_answer?id=' + type.exam_id + '&student_id=' + student_id}" class="btn btn-sm btn-primary view_asesmen" style="width: 34px;"><i class="fa-solid fa-eye text-white" style="margin-left: -2px;"></i></a>` : ``} 
								</div>
							</div>`;
					}

					if (student_id) {
						if( type.status == 1 && type.exam_submit && type.exam_total_nilai){
							view = `<a href="${BASE_URL + 'asesmen/show_answer?id=' + type.exam_id + '&student_id=' + student_id}" class="btn btn-primary rounded-circle view_asesmen" style="width: 34px;"><i class="fa-solid fa-eye text-white" style="margin-left: -2px;"></i></a>`;
						}

						if( type.status == 1 && !type.exam_submit && startDate.getTime() <= (new Date().getTime()) && endDate.getTime() >= (new Date().getTime())){
							view = `<a class="btn btn-primary rounded-circle view_student" style="width: 34px;"><i class="fa-solid fa-file-pen text-white"></i></a>`;
						}

					}

					return view;
				}
			}
		],
	});

	table.columns(3).search(teacher_id).draw();
	table.columns(4).search(class_id).draw();
	table.columns(5).search(0).draw();
	table.columns(6).search(student_id).draw();

	// tabel asesmen mandiri
	let table2 = $('#table-asesmen-khusus').DataTable({
		ajax: BASE_URL + 'asesmen/getAsesmenKhusus',
		serverSide: true,
		processing: true,
		columns: [
			{
				data: 'exam_id',
				visible: false
			},
			{
				data: 'materi_id',
				visible: false
			},
			{
				data: null,
				render(data, row, type, meta) {
					let section = ``;

					if (teacher_id) {
						section = `<a>${type.title}</a>
							${(type.status == 0) ? `<span class="ms-2" style="background-color: #DAEBFF;">Dalam Draft</span>` : ''}`;
					} else {
						section = `<a>${type.title}</a>
							${(type.status == 0) ? `<span class="ms-2" style="background-color: #DAEBFF;">Dalam Draft</span>` : ''}`;
					}
					return section;
				}
			},
			{
				data: 'subject_name',
			},
			{
				data: 'student_name',
				visible: (student_id) ? false : true
			},
			{
				data: 'class_name',
				visible: (student_id) ? false : true
			},
			{
				data: 'start_date',
			},
			{
				data: 'exam_submit',
			},
			{
				data: null,
				className: 'text-center',
				render(data, row, type, meta) {
					var view = `<div class="btn-group btn-group-sm float-right">
                            	${(teacher_id) ? `<a href="${BASE_URL + 'asesmen/view/' + type.exam_id}" class="btn btn-primary rounded-circle view_asesmen"><i class="fa-solid fa-eye text-white"></i></a>` : ``}
 
								${(type.status == 1 && student_id) ? `<a href="${BASE_URL + 'asesmen/do_exercise/' + type.exam_id}" class="btn btn-primary view_student">
								<i class="fa-solid fa-file-pen text-white"></i></a>
								<button class="btn btn-sm btn-danger delete_asesmen"><i class="fa-solid fa-trash text-white"></i></button>` : ``}

								${(type.exam_submit && student_id) ? `<a href="${BASE_URL + 'asesmen/show_answer?id=' + type.exam_id + '&student_id=' + student_id}" class="btn btn-primary view_asesmen"><i class="fa-solid fa-eye text-white"></i></a>` : ``}
                            </div>`;
					return view;
				}
			}
		],
	});

	table2.columns(6).search(student_id).draw();
	table2.columns(5).search(1).draw();

	/**
	 * Cari
	 */

	$('#cari').on('click', function (e) {
		e.preventDefault();
		table.columns(0).search($('select[name="select-mapel"]').val()).draw();
		table.columns(1).search($('input[name="s_title"]').val()).draw();
		table.columns(4).search($('select[name="select-kelas"]').val()).draw();
	});


	/**
	 * GET Data Soal
	 */
	// $.ajax({
	// 	type: "GET",
	// 	url: BASE_URL+"asesmen/get_soal_by_exam_id",
	// 	data: {
	// 		exam_id: $('input[name="exam_id"]').val()
	// 	},
	// 	dataType: "JSON",
	// 	success: function (res) {
	// 		for(let i=0; i<res.length; i++){
	// 			if(res[i].type == 1){
	// 				$('.content-1').append(pilihanGanda(1, i+1, res[i]));
	// 			}else{
	// 				$('.content-2').append(essay(2, i+1, res[i]));
	// 			}
	// 		}	
	// 	}
	// });

	// =============================== Content Pilihan Ganda & Essay ==========================
	// function pilihanGanda(buttonPilihAktif, nomor, item){
	// 	return `<div class="card-question-${buttonPilihAktif} p-3">

	// 				<input type="hidden" name="soal_id[]" value="${item.soal_id}">
	// 				<p><span class="nomor">${nomor++}</span>) ${item.question}</p>
	// 				${hasImage(item.question_file)}

	// 				<div class="row">
	// 				<div class="col-6">
	// 				${(item.choice_a != null) ? `<p>A. `+item.choice_a+`</p>` : ''}
	// 				${(item.choice_a_file != null) ? `<img width="150" src="${base_url+'admin/'+item.choice_a_file}">` : '' }
	// 				</div>
	// 				<div class="col-6">
	// 				${(item.choice_b != null) ? `<p>B. `+item.choice_b+`</p>` : ''}
	// 				${(item.choice_b_file != null) ? `<img width="150" src="${base_url+'admin/'+item.choice_b_file}">` : '' }
	// 				</div>
	// 				<div class="col-6">
	// 				${(item.choice_c != null) ? `<p>C. `+item.choice_c+`</p>` : ''}
	// 				${(item.choice_c_file != null) ? `<img width="150" src="${base_url+'admin/'+item.choice_c_file}">` : '' }
	// 				</div>
	// 				<div class="col-6">
	// 				${(item.choice_d != null) ? `<p>D. `+item.choice_d+`</p>` : ''}
	// 				${(item.choice_d_file != null) ? `<img width="150" src="${base_url+'admin/'+item.choice_d_file}">` : '' }
	// 				</div>
	// 				<div class="col-6">
	// 				${(item.choice_e != null) ? `<p>E. `+item.choice_e+`</p>` : ''}
	// 				${(item.choice_e_file != null) ? `<img width="150" src="${base_url+'admin/'+item.choice_e_file}">` : '' }
	// 				</div>
	// 				<div class="col-6">
	// 				${(item.choice_f != null) ? `<p>F. `+item.choice_f+`</p>` : ''}
	// 				${(item.choice_f_file != null) ? `<img width="150" src="${base_url+'admin/'+item.choice_f_file}">` : '' }
	// 				</div>
	// 				</row>
	// 				<div class="line"></div>
	// 			</div>
	// 		<br>`
	// }

	// function essay(buttonPilihAktif, nomor, item){
	// 	return `<div class="card-question-${buttonPilihAktif} p-3">

	// 				<input type="hidden" name="soal_id[]" value="${item.soal_id}">
	// 				<p><span class="nomor">${nomor}</span>) ${item.question}</p>
	// 			</div>
	// 		<br>`;
	// }

	// function hasImage(url){
	// 	let image = '';
	// 	if(url != null){
	// 		image += `<img src="${BASE_URL+'admin/'+url}" width="250" ></img>`
	// 	}
	// 	return image
	// }

	/**
	 * SUBMIT
	 */
	$("#save-relasi").click(function () {
		$.ajax({
			url: BASE_URL + 'materi/set_relasi',
			type: 'POST',
			data: $("#form-relasi").serialize(),
			contentType: 'application/x-www-form-urlencoded',
			beforeSend(xhr) {
				Swal.fire({
					html: '<div class="d-flex flex-column align-items-center">'
						+ '<span class="spinner-border text-primary"></span>'
						+ '<h3 class="mt-2">Loading...</h3>'
						+ '<div>',
					showConfirmButton: false,
					width: '10rem'
				});
			},
			success(resp) {

				if (resp.success) {
					Swal.fire({
						icon: 'success',
						title: '<h4 class="text-success"></h4>',
						html: '<span class="text-success">' + resp.message + '</span>',
						timer: 5000
					});
				} else {
					Swal.fire({
						icon: 'error',
						title: '<h4 class="text-danger"></h4>',
						html: '<span class="text-danger">' + resp.message + '</span>',
						timer: 5000
					});
				}
			},
			error(err) {
				var response = JSON.parse(err);
				Swal.fire({
					type: response.message,
					title: '<h5 class="text-danger text-uppercase">' + response.message + '</h5>',
					html: response.message
				});
			},
			complete() {
				table.ajax.reload();
			}
		});
	});

	// button relasi di klik
	$('#myTable tbody').on('click', '.btn.relasi_teacher', e => {

		let row = table.row($(e.target).parents('tr')).data();
		$('#div_relasi').load(BASE_URL + 'materi/relasi?id=' + row.teacher_id + '&materi_id=' + row.materi_id);
		$('#relasi_materi_id').val(row.materi_id);
		$('#modal-relasi').modal('show');

	});

	/** 
	 * Delete Asesmen Khusus / Mandiri 
	*/
	$('#table-asesmen-khusus tbody').on('click', '.btn.delete_asesmen', function (e) {
		let row = table2.row($(e.target).parents('tr')).data();
		Swal.fire({
			title: "Anda Yakin?",
			text: "Data yang dihapus tidak dapat dikembalikan",
			type: "warning",
			showCancelButton: true,
			confirmButtonClass: "btn btn-success mt-2",
			cancelButtonColor: "#f46a6a",
			confirmButtonText: "Ya, Hapus Data",
			cancelButtonText: "Tidak, Batalkan Hapus"
		}).then(reslt => {
			if (!reslt.value)
				return false;
			$.ajax({
				type: "POST",
				url: BASE_URL + 'asesmen/delete',
				data: { exam_id: row.exam_id },
				dataType: "JSON",
				contentType: 'application/x-www-form-urlencoded',
				beforeSend(xhr, obj) {
					Swal.fire({
						html: '<div class="d-flex flex-column align-items-center">'
							+ '<span class="spinner-border text-primary"></span>'
							+ '<h5 class="mt-2">Loading...</h5>'
							+ '<div>',
						showConfirmButton: false,
						width: '20rem'
					});
				},
				success: function (response) {
					Swal.fire({
						type: 'success',
						title: `<h5 class="text-success text-uppercase">Sukses</h5>`,
						html: response.message
					});
					window.location.href = BASE_URL + `asesmen`;
				},
				error(err) {
					if (err.status == 500) {
						Swal.fire({
							type: 'error',
							title: `<h5 class="text-${err.statusText} text-uppercase">Error ${err.status}</h5>`,
							html: err.statusText
						});
					}
				},
				complete() {
					table.ajax.reload();
				}
			});
		});
	});

	/** 
	 * Edit Materi 
	*/
	$('#myTable tbody').on('click', '.btn.edit_materi', e => {
		is_update = true;
		let target = e.target;
		let row = table.rows($(target).parents('tr')).data();
		form['subject_id'].value = row[0].subject_id;
		form['materi_id'].value = row[0].materi_id;
		form['input_materi'].value = row[0].title;

		$('#exampleModal').modal('show');
	});

	/**
	 * Delete Materi
	 */
	$('#table-asesmen-standar').on('click', '.delete_materi', e => {
		let target = e.target;
		let row = table.rows($(target).parents('tr')).data();

		Swal.fire({
			title: "Anda Yakin?",
			text: "Data yang dihapus tidak dapat dikembalikan",
			type: "warning",
			showCancelButton: true,
			confirmButtonClass: "btn btn-success mt-2",
			cancelButtonColor: "#f46a6a",
			confirmButtonText: "Ya, Hapus Data",
			cancelButtonText: "Tidak, Batalkan Hapus"
		}).then(reslt => {
			if (!reslt.value)
				return false;

			$.ajax({
				type: "POST",
				url: BASE_URL + "asesmen/delete",
				data: {
					exam_id: row[0].exam_id
				},
				dataType: "JSON",
				beforeSend(xhr, obj) {
					Swal.fire({
						html: '<div class="d-flex flex-column align-items-center">'
							+ '<span class="spinner-border text-primary"></span>'
							+ '<h5 class="mt-2">Loading...</h5>'
							+ '<div>',
						showConfirmButton: false,
						width: '20rem'
					});
				},
				success: function (response) {
					Swal.fire({
						type: 'success',
						icon: 'success',
						title: `<h5 class="text-success text-uppercase">Sukses</h5>`,
						html: '<span class="text-success">' + response.message + '</span>',
						timer: 2000
					});
				},
				error: function (err) {
					Swal.fire({
						type: 'error',
						icon: 'error',
						title: `<h5 class="text-danger text-uppercase">Error</h5>`,
						html: '<span class="text-danger"></span>',
						timer: 2000
					});
				},
				complete: function () {
					table.ajax.reload();
				}
			});

		});

	});

	/**
	 * Gandakan Asesmen
	 */
	let exam_id;
	$('#table-asesmen-standar').on('click', '.copy_asesmen', e => {
		$('#gandakanAsesmenModal').modal('show');
		let target = e.target;
		let row = table.rows($(target).parents('tr')).data();
		exam_id = row[0].exam_id;
	});

	$('#table-asesmen-standar').on('click', '.btn.view_student', e => {
		const row = table.row(e.target.parentNode.closest('tr')).data();
		const btnStartExam = document.getElementById('btnStartExam');

		btnStartExam.href = `${BASE_URL + 'asesmen_standard/do_exercise/' + row.exam_id}`;
		$('#rulesDialog').modal('show');
	});

	$('.submit-gandakan-asesmen').on('click', function () {
		$.ajax({
			type: "POST",
			url: BASE_URL + "asesmen/copy_asesmen",
			data: { exam_id: exam_id },
			dataType: "JSON",
			success: function (response) {
				if (response.success) {
					Swal.fire({
						icon: 'success',
						title: `<h5 class="text-success text-uppercase">Sukses</h5>`,
						html: response.message
					});
					$('#gandakanAsesmenModal').modal('hide');
					window.location.href = BASE_URL + 'asesmen';
				}
			}
		});
	});
});

