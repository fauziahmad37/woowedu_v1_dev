let csrfToken = document.querySelector('meta[name="csrf_token"]') ;
let nomor_content_1 = 0, nomor_content_2 = 0; let no_use = 0;

const form = document.forms['form-add'];
const formNS = document.forms['frm-input'];
var is_update = false;
var buttonPilihAktif = 1;
let exam_id; 
let mapp = [];

$(document).ready(function () {

	
	/**
	 * radio button bank soal
	 */
	$('input[name="flexRadioDefault"]').on('click', function(e){
		if(e.currentTarget.value == 'sendiri'){
			$('.tambah-pertanyaan').removeClass('d-none');
			$('.tambah-bagian-baru').addClass('d-none');
		}else{
			$('.tambah-pertanyaan').addClass('d-none');
			$('.tambah-bagian-baru').removeClass('d-none');
		}
	});

	/**
	 * DATA TABLE PILIH SOAL
	 */

	let table = $('#tabelPilihSoal').DataTable({
		ajax: BASE_URL + 'asesmen/getAll',
		serverSide: true,
		processing: true,
		columns: [
			{
				data: 'soal_id',
				visible: false
			},
			{
				data: 'tema_title',
			},
			{
				data: 'sub_tema_title'
			},
			{
				data: 'question'
			},
			{
				data: null,
				render(data, row, type, meta) {
					var view = `<div class="btn-group btn-group-sm float-right">
                                <button class="btn btn-warning detail_soal"><i class="fa-solid fa-eye text-white"></i></button>
                                <button class="btn btn-success add_soal"><i class="fa-solid fa-plus text-white"></i></button>
							</div>`;
               	 	return view;
				}
			}
		],
		createdRow: function(row, data, dataIndex, cells) {
			if(mapp.includes(data.soal_id)) {
				row.classList.add('d-none');
			}
			
		}
	});

	/**
	 * Button pilih-pertanyaan di klik
	 * lakukan refresh table
	 */

	// pertanyaan bagian 1
	$('button.pilih-pertanyaan-1').on('click', function(e) {
		e.preventDefault();
		buttonPilihAktif = 1;

		if(document.querySelectorAll('.card-question-1').length > 0)
		{
			nomor_content_1 = document.querySelectorAll('.card-question-1').length;
			console.log(nomor_content_1);
		}

		console.log(formNS['select-mapel'].value);

		if(!formNS['select-mapel'].value || !formNS['a_jenis_pertanyaan_1'].value) return false;

		var card = document.getElementsByClassName('soal-id');
		mapp = [...card].map(val => parseInt(val.value));

		table.columns(0)
			.search($('select[name="select-mapel"]').val())
			.draw();
			
		table.columns(2).search($('select[name="a_jenis_pertanyaan_1"]').val()).draw();

		$('#exampleModal').modal('show');
		
	});

	// pertanyaan bagian 2
	$('button.pilih-pertanyaan-2').on('click', function(e){
		e.preventDefault();
		buttonPilihAktif = 2;

		if(!formNS['select-mapel'].value || !formNS['a_jenis_pertanyaan_2'].value) return false;


		var card = document.getElementsByClassName('soal-id');
		mapp = [...card].map(val => parseInt(val.value));

		table.columns(0)
			 .search($('select[name="select-mapel"]').val()).draw();
		table.columns(2).search($('select[name="a_jenis_pertanyaan_2"]').val()).draw();

		$('#exampleModal').modal('show');
	});

	/**
	 * function klik add_soal
	 */

	$('#tabelPilihSoal > tbody').on('click', '.btn.add_soal', e => {
        isUpdate = 1;
        const count = table.row(e.target.parentNode.closest('tr')).count(),
              item = table.row(e.target.parentNode.closest('tr')).data();

		// kondisi jika jumlah pertanyaan sudah melebihi batas
		let jumlahPertanyaan = document.querySelector(`input[name="a_jumlah_petanyaan_${buttonPilihAktif}"]`).value;
		let cardQuestion = document.getElementsByClassName(`card-question-${buttonPilihAktif}`);

		if(jumlahPertanyaan <= cardQuestion.length){
			return alert('Sudah melewati batas jumlah pertanyaan')
		}
		
		// jika pertanyaan PG / Isian
		let jenisSoal = $(`select[name="a_jenis_pertanyaan_${buttonPilihAktif}"]`).val();

		switch(buttonPilihAktif)
		{
			case 1:
				nomor_content_1++;
				no_use = nomor_content_1;
				break;
			case 2:
				nomor_content_2++;
				no_use = nomor_content_2;
				break;
		}
		
		if(jenisSoal == 1){
			htmlContent = pilihanGanda(buttonPilihAktif, no_use, item);
		} else {
			htmlContent = essay(buttonPilihAktif, no_use, item);
		}

		$(`.content-${buttonPilihAktif}`).append(htmlContent);
			e.target.parentNode.closest('tr').classList.add('d-none');
        
    });

	const containerViewSoal = document.getElementById('view-soal');
	$('#tabelPilihSoal > tbody').on('click', '.btn.detail_soal', e => {
		const row = table.row(e.target.parentNode.closest('tr'));
		const data = row.data();

		containerViewSoal.innerHTML = parseInt(data.type) == 1 ? previewPilihanGanda(buttonPilihAktif, 0, data) : previewEssay(buttonPilihAktif, 0, data);
		new bootstrap.Modal('#mdl-view-soal').show();
	});

	document.getElementById('mdl-view-soal').addEventListener('hide.bs.modal', e => {
		containerViewSoal.innerHTML = null;
	});

	// =============================== Content Pilihan Ganda & Essay ==========================
	function pilihanGanda(buttonPilihAktif, nomor, item){
		return `<div class="card card-question-${buttonPilihAktif} p-3">
					<div class="col text-end mb-2">
						<button class="btn btn-sm btn-danger delete-card"><i class="fa fa-trash text-white"></i></button>
					</div>
					<input type="hidden" class="soal-id" name="soal_id[${buttonPilihAktif}][${nomor}]" value="${item.soal_id}">

					<p class="w-100"><span class="nomor float-left me-2">${nomor}.</span> ${item.question}</p>
					${hasImage(item.question_file)}

					<div class="row">
						<div class="col-6">
							${(item.choice_a || item.choice_a_file) ? `<p>A. `+item.choice_a+`</p>` : ''}
							${(item.choice_a_file) ? `<img width="150" src="${ADMIN_URL+item.choice_a_file}">` : '' }
						</div>
						<div class="col-6">
							${(item.choice_b || item.choice_b_file) ? `<p>B. `+item.choice_b+`</p>` : ''}
							${(item.choice_b_file) ? `<img width="150" src="${ADMIN_URL+item.choice_b_file}">` : '' }
						</div>
						<div class="col-6">
							${(item.choice_c || item.choice_c_file) ? `<p>C. `+item.choice_c+`</p>` : ''}
							${(item.choice_c_file) ? `<img width="150" src="${ADMIN_URL+item.choice_c_file}">` : '' }
						</div>
						<div class="col-6">
							${(item.choice_d || item.choice_d_file) ? `<p>D. `+item.choice_d+`</p>` : ''}
							${(item.choice_d_file) ? `<img width="150" src="${ADMIN_URL+item.choice_d_file}">` : '' }
						</div>
						<div class="col-6">
							${(item.choice_e || item.choice_e_file) ? `<p>E. `+item.choice_e+`</p>` : ''}
							${(item.choice_e_file) ? `<img width="150" src="${ADMIN_URL+item.choice_e_file}">` : '' }
						</div>
						<div class="col-6">
							${(item.choice_f ) ? `<p>F. `+item.choice_f+`</p>` : ''}
							${(item.choice_f_file) ? `<img width="150" src="${ADMIN_URL+item.choice_f_file}">` : '' }
						</div>
					</row>
					
				</div>
			<br>`
	}

	function essay(buttonPilihAktif, nomor, item){
		return `<div class="card card-question-${buttonPilihAktif} p-3">
					<div class="col text-end mb-2">
						<button class="btn btn-sm btn-danger delete-card"><i class="fa fa-trash text-white"></i></button>
					</div>
					<input type="hidden" class="soal-id" name="soal_id[${buttonPilihAktif}][${nomor}]" value="${item.soal_id}">
					<p><span class="nomor">${nomor}.</span> ${item.question}</p>
				</div>
			<br>`;
	}

	function previewPilihanGanda(buttonPilihAktif, nomor, item){
		return `	
					<input type="hidden" class="soal-id" name="soal_id[${buttonPilihAktif}][${nomor}]" value="${item.soal_id}">

					<p class="w-100">${item.question}</p>
					${hasImage(item.question_file)}

					<div class="row">
						<div class="col-6">
							${(item.choice_a || item.choice_a_file) ? `<p>A. `+item.choice_a+`</p>` : ''}
							${(item.choice_a_file) ? `<img width="150" src="${ADMIN_URL+item.choice_a_file}">` : '' }
						</div>
						<div class="col-6">
							${(item.choice_b || item.choice_b_file) ? `<p>B. `+item.choice_b+`</p>` : ''}
							${(item.choice_b_file) ? `<img width="150" src="${ADMIN_URL+item.choice_b_file}">` : '' }
						</div>
						<div class="col-6">
							${(item.choice_c || item.choice_c_file) ? `<p>C. `+item.choice_c+`</p>` : ''}
							${(item.choice_c_file) ? `<img width="150" src="${ADMIN_URL+item.choice_c_file}">` : '' }
						</div>
						<div class="col-6">
							${(item.choice_d || item.choice_d_file) ? `<p>D. `+item.choice_d+`</p>` : ''}
							${(item.choice_d_file) ? `<img width="150" src="${ADMIN_URL+item.choice_d_file}">` : '' }
						</div>
						<div class="col-6">
							${(item.choice_e || item.choice_e_file) ? `<p>E. `+item.choice_e+`</p>` : ''}
							${(item.choice_e_file) ? `<img width="150" src="${ADMIN_URL+item.choice_e_file}">` : '' }
						</div>
						<div class="col-6">
							${(item.choice_f ) ? `<p>F. `+item.choice_f+`</p>` : ''}
							${(item.choice_f_file) ? `<img width="150" src="${ADMIN_URL+item.choice_f_file}">` : '' }
						</div>
					</row>
					
				
			<br>`
	}

	function previewEssay(buttonPilihAktif, nomor, item){
		return `<div class="card card-question-${buttonPilihAktif} p-3">
					
					<input type="hidden" class="soal-id" name="soal_id[${buttonPilihAktif}][${nomor}]" value="${item.soal_id}">
					<p> ${item.question}</p>
				</div>
			<br>`;
	}

	function hasImage(url){
		let image = '';
		if(url != null){
			image += `<img src="${ADMIN_URL+url}" width="250" ></img>`
		}
		return image
	}

	/**
	 * BUTTON delete-card DI KLIK
	 */
	$(`.content-1`).on('click', '.card button.btn', function(e){
		e.currentTarget.parentNode.parentNode.remove(); // hapus card nya
		const content = document.getElementsByClassName('content-1')[0];
		// reset nomor urut soal
		const noBaru =  content.getElementsByClassName('nomor');
		nomor_content_1 = noBaru.length;
		for(let i=0; i<noBaru.length; i++){
			content.getElementsByClassName('soal-id')[i].name = "soal_id[1]["+ +(i+1) +"]";
			noBaru[i].innerHTML = i+1;
		}
	});

	$(`.content-2`).on('click', '.card button.btn', function(e){
		e.currentTarget.parentNode.parentNode.remove(); // hapus card nya
		
		const content = document.getElementsByClassName('content-2')[0];
		// reset nomor urut soal
		const noBaru = content.getElementsByClassName('nomor');

		nomor_content_2 = noBaru.length;
		for(let i=0; i<noBaru.length; i++){
			content.getElementsByClassName('soal-id')[i].name = "soal_id[2]["+ +(i+1) +"]";
			noBaru[i].innerHTML = i+1;
		}
	});

	/**
	 * Button Simpan Draft di klik
	 */
	$('.simpan-draft').on('click', function(e){
		let title 		= $('input[name="a_title"]').val();
		let start_dt	= $('input[name="a_start"]').val();
		let end_dt		= $('input[name="a_end"]').val();

		// input validation
		if(!title) { alert('Nama asesmen tidak boleh kosong');  return}
		if(!start_dt) { alert('Tanggal mulai tidak boleh kosong');  return}
		if(!end_dt) { alert('Tanggal akhir tidak boleh kosong');  return}

		postData()
		window.location.href = base_url+'asesmen';
	});

	/**
	 * Button Pratinjau
	 */

	// $('.pratinjau').on('click', function(){
	// 	postData()
	// 	window.location.href = BASE_URL+'asesmen/view/'+exam_id;
	// });

	// function postData(){
	// 	let selectKelas = $('select[name="select-kelas"]').val();
	// 	let selectMapel = $('select[name="select-mapel"]').val();
	// 	let title 		= $('input[name="a_title"]').val();
	// 	let desc		= $('textarea[name="deskripsi"]').val();
	// 	let start_dt	= $('input[name="a_start"]').val();
	// 	let end_dt		= $('input[name="a_end"]').val();
	// 	let teacher_id	= $('input[name="teacher_id"]').val();
	// 	let category_id	= $('select[name="select-category"]').val();

	// 	// input validation
	// 	if(!title) { alert('Nama asesmen tidak boleh kosong');  return}
	// 	if(!start_dt) { alert('Tanggal mulai tidak boleh kosong');  return}
	// 	if(!end_dt) { alert('Tanggal akhir tidak boleh kosong');  return}

	// 	exam_id		= $('input[name="exam_id"]').val();

	// 	let inputSoal 	= document.querySelectorAll('input[name="soal_id[]"]');
	// 	let soal_ids = [];
	// 	for(let i=0; i<inputSoal.length; i++) soal_ids.push(inputSoal[i].value);

	// 	$.ajax({
	// 		url: BASE_URL + 'asesmen/save_draft',
	// 		type: 'POST',
	// 		async: false,
	// 		data: {
	// 			code: makeid(10),
	// 			class_id: selectKelas,
	// 			subject_id: selectMapel,
	// 			title: title,
	// 			desc: desc,
	// 			start_dt: start_dt,
	// 			end_dt: end_dt,
	// 			tipe: 0,
	// 			status: 0,
	// 			teacher_id: teacher_id,
	// 			category_id: category_id,
	// 			exam_id: exam_id,
	// 			soal_ids: soal_ids
	// 		},
	// 		contentType: 'application/x-www-form-urlencoded',
	// 		beforeSend(xhr) {
	// 						Swal.fire({
	// 							html: 	'<div class="d-flex flex-column align-items-center">'
	// 							+ '<span class="spinner-border text-primary"></span>'
	// 							+ '<h3 class="mt-2">Loading...</h3>'
	// 							+ '<div>',
	// 							showConfirmButton: false,
	// 							width: '10rem'
	// 							});
	// 		},
	// 		success(resp) {
	// 			let response = JSON.parse(resp);
	// 			if(response.success){
	// 				Swal.fire({
	// 					icon: 'success',
	// 					title: '<h4 class="text-success"></h4>',
	// 					html: '<span class="text-success">'+response.message+'</span>',
	// 					timer: 5000
	// 				});

	// 				exam_id = response.exam_id
	// 			}else{
	// 				Swal.fire({
	// 					icon: 'error',
	// 					title: '<h4 class="text-danger"></h4>',
	// 					html: '<span class="text-danger">'+response.message+'</span>',
	// 					timer: 5000
	// 				});
	// 			}
	// 		},
	// 		error(err) {
	// 			var response = JSON.parse(err);
	// 			Swal.fire({
	// 				type: response.message,
	// 				title: '<h5 class="text-danger text-uppercase">'+response.message+'</h5>',
	// 				html: response.message
	// 			});
	// 		},
	// 		complete() {
	// 			table.ajax.reload();
	// 		}
	// 	});
	// }

	/**
	 * JIKA EXAM ID ADA MAKA ISI DATA CARD SOAL
	 */
	exam_id = $('input[name="exam_id"]').val();
	if(exam_id != ''){
		$.ajax({
			type: "GET",
			url: BASE_URL + 'asesmen/get_soal_by_exam_id',
			data: { exam_id: exam_id },
			dataType: "JSON",
			success: function (res) {

				Array.from(res, val => {
					let soal;
					if(parseInt(val.type) == 1)
						soal = pilihanGanda(parseInt(val.grouping), val.no_urut, val);
					if(parseInt(val.type) == 2)
						soal = essay(parseInt(val.grouping), val.no_urut, val);

					$(`.content-${val.grouping}`).append(soal);

				});
			},
			error: err => {

			}
		});
	}


	/**
	 * BUTTON PUBLISH DI KLIK
	 * @method POST
	 
	$('.publish').on('click', function(e){

		let selectKelas = $('select[name="select-kelas"]').val();
		let selectMapel = $('select[name="select-mapel"]').val();
		let title 		= $('input[name="a_title"]').val();
		let desc		= $('textarea[name="deskripsi"]').val();
		let start_dt	= $('input[name="a_start"]').val();
		let end_dt		= $('input[name="a_end"]').val();
		let teacher_id	= $('input[name="teacher_id"]').val();
		let category_id	= $('select[name="select-category"]').val();
		let exam_id		= $('input[name="exam_id"]').val();

		// input validation
		if(!title) { alert('Nama asesmen tidak boleh kosong');  return }
		if(!start_dt) { alert('Tanggal mulai tidak boleh kosong');  return }
		if(!end_dt) { alert('Tanggal akhir tidak boleh kosong');  return }

		let inputSoal 	= document.querySelectorAll('input[name="soal_id[]"]');
		let soal_ids = [];
		for(let i=0; i<inputSoal.length; i++) soal_ids.push(inputSoal[i].value);

		$.ajax({
			url: BASE_URL + 'asesmen/publish',
			type: 'POST',
			data: {
				code: makeid(10),
				class_id: selectKelas,
				subject_id: selectMapel,
				title: title,
				desc: desc,
				start_dt: start_dt,
				end_dt: end_dt,
				tipe: 0,
				status: 1,
				teacher_id: teacher_id,
				category_id: category_id,
				exam_id: exam_id,
				soal_ids: soal_ids
			},
			contentType: 'application/x-www-form-urlencoded',
			beforeSend(xhr) {
							Swal.fire({
								html: 	'<div class="d-flex flex-column align-items-center">'
								+ '<span class="spinner-border text-primary"></span>'
								+ '<h3 class="mt-2">Loading...</h3>'
								+ '<div>',
								showConfirmButton: false,
								width: '10rem'
								});
			},
			success(resp) {
				let response = JSON.parse(resp);
				if(response.success){
					Swal.fire({
						icon: 'success',
						title: '<h4 class="text-success"></h4>',
						html: '<span class="text-success">'+response.message+'</span>',
						timer: 5000
					});

					// window.location.href = base_url+'asesmen';
				}else{
					Swal.fire({
						icon: 'error',
						title: '<h4 class="text-danger"></h4>',
						html: '<span class="text-danger">'+response.message+'</span>',
						timer: 5000
					});
				}
			},
			error(err) {
				var response = JSON.parse(err);
				Swal.fire({
					type: response.message,
					title: '<h5 class="text-danger text-uppercase">'+response.message+'</h5>',
					html: response.message
				});
			},
			complete() {
				// table.ajax.reload();
			}
		});
	});
	*/
});

formNS.addEventListener('submit', async e => false);
document.querySelector('.publish').addEventListener('click', async e => {
	await postAssesment(formNS, 1, BASE_URL + 'asesmen');
});
document.querySelector('.pratinjau').addEventListener('click', async e => {
	await postAssesment(formNS, 0, base_url+'asesmen/view/');
});
document.querySelector('.simpan-draft').addEventListener('click', async e => {
	await postAssesment(formNS, 0, base_url+'asesmen/create_standar/');
});



/**
 * Publish Assement
 *
 * @async
 * @param {*} e
 * @returns {*}
 */
async function postAssesment(e, is_draft=0, redirect='') {
	let obj = {};

	Swal.fire({
		icon: '',
		html: 	'<div class="d-flex flex-column align-items-center">'
				+ '<span class="spinner-border text-primary"></span>'
				+ '<h3 class="mt-2">Loading...</h3>'
				+ '<div>',
		showConfirmButton: false,
		width: '10rem'
	});

	try 
	{
		const formData = new FormData(e);
		obj = Object.fromEntries(formData.entries());

		Object.assign(obj, {status: is_draft});


		const f = await fetch(BASE_URL + 'asesmen/save', {
			method: 'POST',
			body: new URLSearchParams(obj).toString(),
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded'
			}
		});

		Swal.close();

		const resp = await f.json();

		if(!f.ok)
		{
			Swal.fire({
				icon: 'error',
				title: '<h5 class="text-danger text-uppercase">Error</h5>',
				html: '<span class="text-danger">'+resp.message+'</span>',
				timer: 2000
			});

			// set token
			$('input[name="csrf_token_name"]').val(resp.token);
			return;
		}

		Swal.fire({
			icon: 'success',
			title: '<h4 class="text-success">Success</h4>',
			html: '<span class="text-success">'+resp.message+'</span>',
			timer: 2000
		})

		setTimeout(() => {
			window.location.href = BASE_URL + 'asesmen';
		} , 2000);
		// .then(t => {
		// 		var red = redirect;
		// 		if(is_draft == 0)
		// 		 red = redirect + '/' + resp.id;

		// 	window.location.href = red;
		// });		
	} 
	catch (error) {
		Swal.fire({
			icon: 'error',
			title: '<h5 class="text-danger text-uppercase">Error</h5>',
			html: '<span class="text-danger">Gagal menerbitkan Asesmen !!!</span>',
			timer: 2000
		});

	}
}

