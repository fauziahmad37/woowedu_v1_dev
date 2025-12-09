let csrfToken = document.querySelector('meta[name="csrf_token"]') ;
const form = document.forms['form-add'];
let isUpdate=0;
const a_jenis_materi = document.getElementsByName('a_jenis_materi')[0];
let teacherId = document.getElementsByName('teacher_id')[0].value;
const modalAdd = new bootstrap.Modal(document.getElementById('modal-add'));
// const isMobile = navigator.userAgentData.mobile;

$(document).ready(function () {
	const table = $('#myTable').DataTable({
		ajax: BASE_URL + 'materi/list_materi_saya',
		serverSide: true,
		processing: true,
		"bInfo" : !isUserUsingMobile(),
		"oLanguage": {
			"sUrl": "https://cdn.datatables.net/plug-ins/1.10.9/i18n/Indonesian.json"
		},
		"pagingType": "full_numbers",
		"language": {
			"paginate": {
				"first": null,
				"previous": "<",
				"next": ">",
				"last": null,
			}
		},
		ordering: false,
		columns: [
			{
				data: 'materi_id',
				visible: false
			},
			{
				data: 'subject_name',
			},
			{
				data: 'title',
			},
			{
				data: 'edit_at'
			},
			{
				data: null,
				render(row, data, type, meta) {
					let fileSize;
					if(type.materi_file_size < 1000) fileSize = type.materi_file_size + ' KB';
					if(type.materi_file_size >= 1000) fileSize = (type.materi_file_size / 1000) + ' MB';
					if(!type.materi_file_size) fileSize = '0 KB';
					return fileSize
				}
			},
			{
				data: 'materi_file',
				class: 'text-center',
				render(data, row, type, meta) {
					return `${(type.type == 'file') ? `<a href="${BASE_URL+'assets/files/materi/'+data}" target="_blank">
						<img src="${BASE_URL+'assets/themes/space/icons/file-text-fill.svg'}" width="25">
					</a>` : `<a href="${data}" target="_blank"><img src="${BASE_URL+'assets/themes/space/icons/link-45deg.svg'}" width="25"></a>` }`;
				}	
			},
			{
				data: null,
				render(data, row, type, meta) {
					var view = '<div class="btn-group btn-group-sm">'+
                                '<button class="btn btn-sm me-1 rounded-2 btn-primary relasi_teacher"><i class="fa-solid fa-share-from-square text-white"></i></button>' +
                                '<button class="btn btn-sm me-1 rounded-2 btn-success edit_materi"><i class="fa-solid fa-pencil text-white"></i></button>' +
                                '<button class="btn btn-sm me-1 rounded-2 btn-sm btn-danger delete_materi"><i class="fa-solid fa-trash text-white"></i></button>' +
                            '</div>';
               	 	return view;
				}
			}
		]
	});

	table.columns(0).search(teacherId).draw();
	
	/**
	 * Button Add Click Handler
	 * @date 9/13/2023 - 10:02:26 AM
	 *
	 * @param {*} e
	 */
	const btnAddClick = e => {
		isUpdate = 0;
		a_jenis_materi.removeAttribute('disabled');
		resetForm();
	}

	const resetForm = () => {
		$('select[name="a_jenis_materi"]').val(null).trigger('change');
		form.reset();
	}

	/**
	 * Kondisi Option Jenis Materi
	 */
	if(a_jenis_materi){
		a_jenis_materi.addEventListener('change', function(e){
			jenisMaterChange(e.target.value)
		});
	}

	function jenisMaterChange(type){
		// jika type materi adalah link maka tampilkan input tautan
		// jika type materi adalah file maka tampilkan input file
		if(type == 'link'){
			document.getElementsByClassName('lampiran')[0].classList.add('d-none');
			document.getElementsByClassName('link')[0].classList.remove('d-none');
		}else{
			document.getElementsByClassName('lampiran')[0].classList.remove('d-none');
			document.getElementsByClassName('link')[0].classList.add('d-none');
		}
	}

	/**
	 * clik button create
	 * reset form
	 */
	$('#create').on('click', function(){
		btnAddClick();

		// ketika create materi baru pilihan jenis materi & mapel di enable
		let jenisMateri = document.getElementsByName('a_jenis_materi')[0];
		let jenisMateriOption = jenisMateri.options;
		for(let i=0; i < jenisMateriOption.length; i++){
			// enable semua option
			jenisMateriOption[i].removeAttribute('disabled');
		}

		// enable semua option mapel
		let mapel = document.getElementsByName('a_materi_subject')[0];
		let mapelOption = mapel.options;
		for(let i=0; i < mapelOption.length; i++){
			mapelOption[i].removeAttribute('disabled');
		}

		// jenisMateri.disabled = false;

		// let mapel = document.getElementsByName('a_materi_subject')[0];
		// mapel.disabled = false;

		// UBAH TITLE MODAL
		document.getElementsByClassName('modal-header')[0].children[0].innerHTML = "Buat Materi Baru"

		
		form.reset();
	});

	/**
	 * Cari
	 */	 

	$('#cari').on('click', function(e){
		e.preventDefault();
		table.columns(1).search($('select[name="select-mapel"]').val()).draw();
	});

	/**
	 * SUBMIT
	 */
	$("#save-relasi").click(function(){
		$.ajax({
			url: BASE_URL + 'materi/set_relasi',
			type: 'POST',
			data: $("#form-relasi").serialize(),
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
				
				if(resp.success){
					Swal.fire({
						type: 'success',
						title: '<h4 class="text-success"></h4>',
						html: '<span class="text-success">'+resp.message+'</span>',
						timer: 5000
					});
				}else{
					Swal.fire({
						type: 'error',
						title: '<h4 class="text-danger"></h4>',
						html: '<span class="text-danger">'+resp.message+'</span>',
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
				table.ajax.reload();
			}
		});
	});	 
	
	// button relasi di klik
	$('#myTable tbody').on('click', '.btn.relasi_teacher', e => {
		
		let row = table.row($(e.target).parents('tr')).data(); 
		$('#div_relasi').load(BASE_URL + 'materi/relasi?id='+row.teacher_id+'&materi_id='+row.materi_id);
		$('#relasi_materi_id').val(row.materi_id);
		$('#modal-relasi').modal('show');
		
	});

	/** 
	 * Delete Materi 
	*/
	$('#myTable tbody').on('click', '.btn.delete_materi',function(e){
		let row = table.row($(e.target).parents('tr')).data();
		Swal.fire({
			title: "Anda Yakin?",
			text: "Data yang dihapus tidak dapat dikembalikan",
			// type: "warning",
			showCancelButton: true,
			// confirmButtonClass: "btn btn-success mt-2",
			cancelButtonColor: "#f46a6a",
			confirmButtonText: "Ya, Hapus Data",
			cancelButtonText: "Tidak, Batalkan Hapus" 
		}).then(reslt => {
			if(!reslt.value)
						return false;
			$.ajax({
				type: "POST",
				url: BASE_URL + 'materi/delete',
				data: JSON.stringify({materi_id: row.materi_id}),
				contentType: 'application/json',
				beforeSend(xhr, obj) {
					Swal.fire({
						html: 	'<div class="d-flex flex-column align-items-center">'
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
						title:`<h5 class="text-success text-uppercase">Sukses</h5>`,
						html: response.message
					});
				},
				error(err) {
					if(err.status == 500){
						Swal.fire({
							type: 'error',
							title:`<h5 class="text-${err.statusText} text-uppercase">Error ${err.status}</h5>`,
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
		resetForm();

		isUpdate = 1;
		let target = e.target;
		let item = table.row($(target).parents('tr')).data();

		// ketika edit materi pilihan jenis materi & mapel di disable
		// let jenisMateri = document.getElementsByName('a_jenis_materi')[0];
		// jenisMateri.disabled = true;

		// let mapel = document.getElementsByName('a_materi_subject')[0];
		// mapel.disabled = true;

		// UBAH TITLE MODAL
		document.getElementsByClassName('modal-header')[0].children[0].innerHTML = "Ubah Materi"

		form['a_jenis_materi'].value = '';
		form['a_materi_subject'].value = item.subject_id;
		form['a_id'].value = item.materi_id;
		form['a_materi_title'].value = item.title;

		$('select[name="a_materi_subject"]').val(item.subject_id).trigger('change'); // agar posisi option mapel sesuai dengan data yang di edit

		// disable option jenis materi yang tidak sesuai dengan data yang di edit
		for(let i=0; i < form['a_jenis_materi'].length; i++){
			if(form['a_jenis_materi'][i].value == item.type){
				form['a_jenis_materi'][i].setAttribute('selected', 'selected');
				form['a_jenis_materi'][i].removeAttribute('disabled');
			} else {
				form['a_jenis_materi'][i].setAttribute('disabled', 'disabled');
			}
		}

		// disable option mapel yang tidak sesuai dengan data yang di edit
		for(let i=0; i < form['a_materi_subject'].length; i++){
			if(form['a_materi_subject'][i].value == item.subject_id){
				form['a_materi_subject'][i].setAttribute('selected', 'selected');
				form['a_materi_subject'][i].removeAttribute('disabled');
			} else {
				form['a_materi_subject'][i].setAttribute('disabled', 'disabled');
			}
		}

		// jenis materi di set sesuai dengan data yang di edit
		$('select[name="a_jenis_materi"]').val(item.type).trigger('change');
	
		// jika materi type file maka isi input tautan dengan materi_file
		if (item.type == 'link') $('input[name="a_tautan"]').val(item.materi_file); 

		jenisMaterChange(item.type)

		$('#modal-add').modal('show');
	});

	document.getElementById('save-subject').addEventListener('click', async e => {
		form.dispatchEvent(new Event('submit'));
	});
	
	form.addEventListener('submit', async e => { 
		await submitForm(e);
		table.ajax.reload();
	 });
	

});

async function submitForm(e) {
	e.preventDefault();
	const url = isUpdate == 0 ? `${BASE_URL}/materi/save` : `${BASE_URL}/materi/edit`
	const formData = new FormData(e.target);

	Swal.fire({
		html: 	'<div class="d-flex flex-column align-items-center">'
		+ '<span class="spinner-border text-primary"></span>'
		+ '<h5 class="mt-2">Loading...</h5>'
		+ '<div>',
		showConfirmButton: false,
		width: '20rem'
	});

	try 
	{
		const f = await fetch(url, {
			body: formData,
			method: 'POST'
		});

		const resp = await f.json();

		Swal.close();

		if(!f.ok)
		{
			Swal.fire({
				type: 'error',
				title: '<h4 class="text-danger">ERROR</h4>',
				html: '<span class="text-danger">'+resp.message+'</span>',
				timer: 2000
			});

			document.getElementsByName('csrf_token_name')[0].value = resp.token;
			return;
		}
		Swal.fire({
			type: 'success',
			title: '<h4 class="text-success">SUCCESS</h4>',
			html: '<span class="text-success">'+resp.message+'</span>',
			timer: 2000
		}).then(t => {
			$('#modal-add').modal('hide');
			// if(t.value) {
				// modalAdd.hide();
			// }
			$('input[name="csrf_token_name"]').val(resp.token);
		})
		
	} 
	catch (error) 
	{
		
		Swal.fire({
			type: 'error',
			title: '<h4 class="text-danger">ERROR</h4>',
			html: '<span class="text-danger">'+resp.message+'</span>',
			timer: 2000
		});
	}
}

