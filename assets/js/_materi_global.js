let csrfToken = document.querySelector('meta[name="csrf_token"]') ;
const form = document.forms['form-add'];
var is_update = false;

$(document).ready(function () {
	let table = $('#myTable').DataTable({
		ajax: BASE_URL + 'materi/list_tutorial',
		serverSide: true,
		processing: true,
		columns: [
			{
				data: 'materi_global_id',
				visible: false
			},
			{
				data: 'title',
			},
			{
				data: 'updated_at',
				render(data, type, row, meta){
					return moment(data).format('DD MMM YYYY, H:m');
				}
			},
			{
				data: null,
				render(row, data, type, meta) {
					let file_size = Math.round(row.file_size/1000);
					res =  (file_size > 1000) ? (file_size/1000)+' MB' : file_size+' KB';
					return res;
				}
			},
			{
				data: null,
				render(data, type, row, meta) {
					var view = `<div class="btn-group btn-group-sm">
                                ${buttonGroupAction(row)}
                            </div>`;
               	 	return view;
				}
			}
		],
		columnDefs: [
		   {
				"targets": 4,
				"className": "text-center"
		   }
		]
	});

	// inisialisai table filter pertama kali di load
	table.on('init', function (e, setting, json){
		table.columns(2).search(true).draw().columns.adjust();
	});

	/**
	 * clik button create
	 * reset form
	 */
	$('#create').on('click', function(){
		form.reset();
	});

	/**
	 * clik button kembali 
	 */
	$('#kembali').on('click', function(){
		table.columns(1).search('').draw();
		table.columns(2).search(true).draw();
	});

	/**
	 * Cari
	 */	 

	$('#cari').on('click', function(e){
		e.preventDefault();
		table.columns(0).search($('input[name="title"]').val()).draw();
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
						icon: 'success',
						title: '<h4 class="text-success"></h4>',
						html: '<span class="text-success">'+resp.message+'</span>',
						timer: 5000
					});
				}else{
					Swal.fire({
						icon: 'error',
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
	 * Click Folder 
	*/
	$('#myTable tbody').on('click', '.folder', e => {
		e.preventDefault();
		let row = table.row($(e.target).parents('tr')).data();
		table.columns(1).search(row.materi_global_id).draw();
		table.columns(2).search('').draw();
	});

	/** 
	 * Delete Materi 
	*/
	$('#myTable tbody').on('click', '.btn.delete_materi',function(e){
		let row = table.row($(e.target).parents('tr')).data();
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
			if(!reslt.value)
						return false;
			$.ajax({
				type: "POST",
				url: BASE_URL + 'materi/delete',
				data: {materi_id: row.materi_id},
				dataType: "JSON",
				contentType: 'application/x-www-form-urlencoded',
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
						icon: 'success',
						title:`<h5 class="text-success text-uppercase">Sukses</h5>`,
						html: response.message
					});
				},
				error(err) {
					if(err.status == 500){
						Swal.fire({
							icon: 'error',
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
		is_update = true;
		let target = e.target;
		let row = table.rows($(target).parents('tr')).data();
		form['subject_id'].value = row[0].subject_id;
		form['materi_id'].value = row[0].materi_id;
		form['input_materi'].value = row[0].title;

		$('#exampleModal').modal('show');
	});

	function buttonGroupAction(row){
		let button;
		switch(row.materi_type){
			case 1: 
				// button = `<a href="${BASE_URL+'assets/files/materi/materi-global/'+row.materi_file}" class="btn btn-primary" target="_blank"><img src="./assets/themes/space/icons/download-icon-white.svg"></a>`;
				button = `<button class="btn btn-primary" onclick="checkFileExists('${BASE_URL+'assets/files/materi/materi-global/'+row.materi_file}')"><img src="./assets/themes/space/icons/download-icon-white.svg"></button>`;
				break;
			case 2: 
				button = `<a href="${row.materi_url}" class="btn btn-primary" target="_blank"><img src="./assets/themes/space/icons/link-icon-white.svg"></a>`;
				break;
			case 0:
				button = `<a class="btn btn-primary folder"><img src="./assets/themes/space/icons/folder-icon-white.svg"></a>`;
		}
		return button
	}
});




