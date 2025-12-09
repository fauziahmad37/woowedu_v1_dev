(($, base_url) => {
	'use strict';

	const form 		= document.forms['form-add'];
	const sekolah 	= document.getElementsByName('slc-sekolah')[0];
	let sekolahIdUser = document.getElementsByName('sekolah_id')[0].value;
	let csrfToken 	= document.querySelector('meta[name="csrf_token"]');
	var formAdd = document.getElementById('form-add'),
		formEdit = document.getElementById('form-edit'),
		is_update = 0;


	getSekolah();
	async function getSekolah() {
		try 
		{
			const f = await fetch(`${base_url}api/sekolah/getAll`);
			let datas = await f.json();
			// console.log(datas);
			for(let d of datas.data)
			{
				let opt = document.createElement('option');
				if(d.sekolah_id == sekolahIdUser){
					opt.setAttribute('selected', 'selected');
				}
				opt.text = d.sekolah_nama;
				opt.value = d.sekolah_id;
				sekolah.add(opt);
			}
		}
		catch(err) 
		{
			console.log(err);
		} 
	
	}

	var table = $('#tbl_teacher').DataTable({
		serverSide: true,
		processing: true,
		ajax: {
			url: base_url + 'api/kepsek/get_all',
			type: 'GET',
			data: function (d) {
				return d;
			}
		}, select: {
			style: 'multi',
			selector: 'td:first-child'
		},
		columns: [
		{
			data: null,
			width: '30px',
			className: 'select-checkbox ',
			checkboxes: {
				selectRow: true
			},
			orderable: false,
			render(data, row, type, meta) {
				return '';
			}
		}, {
			data: 'sekolah_id',
			visible: false
		}, {
			data: 'teacher_id',
			visible: false
		}, {
			data: 'nik'
		}, {
			data: 'gender'
		}, {
			data: 'teacher_name'
		}, {
			data: 'address'
		}, {
			data: 'phone'
		}, {
			data: 'email'
		}, {
			data: null,
			render(data, row, type, meta) {
				var view = '<div class="btn-group btn-group-sm float-right">' +
					'<button class="btn btn-warning relasi_teacher d-none"><i class="bx bx-shuffle font-size-12"></i></button>' +
					'<button class="btn btn-success edit_teacher"><i class="bx bx-edit-alt font-size-12"></i></button>' +
					'<button class="btn btn-sm btn-danger delete_teacher"><i class="bx bx-trash font-size-12"></i></button>' +
					'</div>';
				return view;
			}
		}],
		pageLength: 8,
		language: {
			processing: '<div class="d-flex flex-column align-items-center shadow">'
				+ '<span class="spinner-border text-info"></span>'
				+ '<h4 class="mt-2"><strong>Loading...</strong></h4>'
				+ '</div>',
		}

	});

		/**
	 * SEARCH DATA
	 */
		document.getElementById('btn-search_teacher').addEventListener('click', e => {
			e.preventDefault();
			let class_name = document.getElementsByName('s_teacher_name')[0]; 
			if(typeof class_name.value !== 'undefined' || class_name.value !== null)
					table.column(4).search(class_name.value).draw();
	});

	document.getElementsByName('s_teacher_name')[0].addEventListener('keypress', e => {
		// If the user presses the "Enter" key on the keyboard
		if (e.key === "Enter") {
			// Cancel the default action, if needed
			e.preventDefault();
			// Trigger the button element with a click
			document.getElementById("btn-search_teacher").click();
		  }
	});


	//select_all
	$('#select_all').on('click', e => {
		if (e.target.checked)
			table.rows().select();
		else
			table.rows().deselect();
	});

	$('#btn-add').on('click', e => {
		is_update = false;
		form.reset();
		form['xsrf'].value = csrfToken.content;
	});

	$('#btn-refresh').on('click', e => {
		table.ajax.reload();
	});


	/**
	 * SAVE RELASI
	 */

	$('#tbl_teacher tbody').on('click', '.btn.relasi_teacher', e => {

		let row = table.row($(e.target).parents('tr')).data();
		$('#div_relasi').load(base_url + 'teacher/relasi?id=' + row.teacher_id);
		$('#relasi_xsrf').val(csrfToken.content);
		$('#relasi_teacher_id').val(row.teacher_id);
		$('#modal-relasi').modal('show');

	});




	$("#save-relasi").click(function () {
		$.ajax({
			url: base_url + 'api/teacher/set_relasi',
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
				Swal.fire({
					type: resp.err_status,
					title: `<h5 class="text-${resp.err_status} text-uppercase">${resp.err_status}</h5>`,
					html: resp.message
				});
				csrfToken.content = resp.token;
			},
			error(err) {
				var response = JSON.parse(err.responseText);
				Swal.fire({
					type: response.err_status,
					title: '<h5 class="text-danger text-uppercase">' + response.err_status + '</h5>',
					html: response.message
				});
				if (response.hasOwnProperty('token'))
					csrfToken.content = response.token;
			},
			complete() {
				table.ajax.reload();
			}
		});
	});




	//const btnSubmitRelasi = document.getElementById('save-relasi'); 



	// submit
	/* btnSubmitRelasi.addEventListener('click', e => {
		e.preventDefault();

		let frmObj = { 
				teacher_id: form['a_teacher_id'].value,
				teacher_name: form['a_teacher_name'].value, 
				nik: form['a_nik'].value, 
				address: form['a_address'].value, 
				phone: form['a_phone'].value, 
				email: form['a_email'].value, 
				xsrf_token: form['xsrf'].value
		};
		let conf = {};
		if(is_update) {
				conf = {
						url: base_url + 'api/teacher/edit_data',
						type: 'PUT',
						contentType: 'application/json',
						data: JSON.stringify(frmObj)
				};
		} else {
				conf = {
						url: base_url + 'api/teacher/add_data',
						type: 'POST',
						contentType: 'application/x-www-form-urlencoded',
						data: $.param(frmObj)
				};
		}

		$.ajax({
				url: conf.url,
				type: conf.type,
				data: conf.data,
				contentType: conf.contentType,
				beforeSend(xhr, obj) {
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
						Swal.fire({
			type: resp.err_status,
			title:`<h5 class="text-${resp.err_status} text-uppercase">${resp.err_status}</h5>`,
			html: resp.message
		});
		csrfToken.content = resp.token;
				},
				error(err) {
						let response = JSON.parse(err.responseText);
		Swal.fire({
			type: response.err_status,
			title: '<h5 class="text-danger text-uppercase">'+response.err_status+'</h5>',
			html: response.message
		});
		if(response.hasOwnProperty('token'))
			csrfToken.setAttribute('content', response.token);
				},
				complete() {
						table.ajax.reload();
				}
		});
		$('#modal-add').modal('hide');
}); */

	// END RELASI


	/**
	 * SAVE DATA
	 */
	const btnSubmit = document.getElementById('save-teacher');

	$('#tbl_teacher tbody').on('click', '.btn.edit_teacher', e => {
		is_update = true;
		let target = e.target;
		let row = table.rows($(target).parents('tr')).data();
		form['slc-sekolah'].value = row[0].sekolah_id;
		form['a_teacher_id'].value = row[0].teacher_id;
		form['a_teacher_name'].value = row[0].teacher_name;
		form['a_nik'].value = row[0].nik;
		form['a_gender'].value = row[0].gender;
		form['a_address'].value = row[0].address;
		form['a_phone'].value = row[0].phone;
		form['a_email'].value = row[0].email;
		form['xsrf'].value = csrfToken.content;
		$('#modal-add').modal('show');
	});

	// submit
	btnSubmit.addEventListener('click', e => {
		e.preventDefault();

		let frmObj = {
			sekolah_id: form['slc-sekolah'].value,
			teacher_id: form['a_teacher_id'].value,
			teacher_name: form['a_teacher_name'].value,
			nik: form['a_nik'].value,
			gender: form['a_gender'].value,
			address: form['a_address'].value,
			phone: form['a_phone'].value,
			email: form['a_email'].value,
			xsrf_token: form['xsrf'].value
		};
		let conf = {};
		if (is_update) {
			conf = {
				url: base_url + 'api/kepsek/edit_data',
				type: 'PUT',
				contentType: 'application/json',
				data: JSON.stringify(frmObj)
			};
		} else {
			conf = {
				url: base_url + 'api/kepsek/add_data',
				type: 'POST',
				contentType: 'application/x-www-form-urlencoded',
				data: $.param(frmObj)
			};
		}

		$.ajax({
			url: conf.url,
			type: conf.type,
			data: conf.data,
			contentType: conf.contentType,
			beforeSend(xhr, obj) {
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
				Swal.fire({
					type: resp.err_status,
					title: `<h5 class="text-${resp.err_status} text-uppercase">${resp.err_status}</h5>`,
					html: resp.message
				});
				csrfToken.content = resp.token;
			},
			error(err) {
				let response = JSON.parse(err.responseText);
				Swal.fire({
					type: response.err_status,
					title: '<h5 class="text-danger text-uppercase">' + response.err_status + '</h5>',
					html: response.message
				});
				if (response.hasOwnProperty('token'))
					csrfToken.setAttribute('content', response.token);
			},
			complete() {
				table.ajax.reload();
			}
		});
		$('#modal-add').modal('hide');
	});


	/**
	 * DELETE SINGLE
	 */
	$('#tbl_teacher tbody').on('click', '.btn.delete_teacher', e => {
		e.preventDefault();
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
			if (!reslt.value)
				return false;
			$.ajax({
				url: base_url + 'api/teacher/delete_data',
				type: 'DELETE',
				contentType: 'application/json',
				data: JSON.stringify({ bulk: false, data: row.teacher_id, xsrf_token: csrfToken.content }),
				beforeSend(xhr, obj) {
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
					Swal.fire({
						type: resp.err_status,
						title: `<h5 class="text-${resp.err_status} text-uppercase">${resp.err_status}</h5>`,
						html: resp.message
					});
					csrfToken.content = resp.token;
				},
				error(err) {
					let response = JSON.parse(err.responseText);
					Swal.fire({
						type: response.err_status,
						title: '<h5 class="text-danger text-uppercase">' + response.err_status + '</h5>',
						html: response.message
					});
					if (response.hasOwnProperty('token'))
						csrfToken.setAttribute('content', response.token);
				},
				complete() {
					table.ajax.reload();
				}
			});
		});
	});

	/**
	*DELETE MULTI
	*/
	document.querySelector('#delete_all').addEventListener('click', e => {
		e.preventDefault();
		let rows = table.rows({ selected: true }).data(),
			count = table.rows({ selected: true }).count();
		if (count === 0) {
			Swal.fire({
				title: "Tidak Ada Data Terpilih!",
				text: "Harap pilih data yang akan dihapus terlebih dahulu.",
				confirmButtonClass: "btn btn-warning mt-2",
				type: "warning"
			});
			return false;
		}

		let datas = [];
		for (let i = 0; i < rows.length; i++) {
			datas.push(rows[i].teacher_id);
		}
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
				url: base_url + 'api/teacher/delete_data',
				type: 'DELETE',
				contentType: 'application/json',
				data: JSON.stringify({ bulk: true, data: datas, xsrf_token: csrfToken.content }),
				beforeSend(xhr, obj) {
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
					Swal.fire({
						type: resp.err_status,
						title: `<h5 class="text-${resp.err_status} text-uppercase">${resp.err_status}</h5>`,
						html: resp.message
					});
					csrfToken.content = resp.token;
				},
				error(err) {
					let response = JSON.parse(err.responseText);
					Swal.fire({
						type: response.err_status,
						title: '<h5 class="text-danger text-uppercase">' + response.err_status + '</h5>',
						html: response.message
					});
					if (response.hasOwnProperty('token'))
						csrfToken.setAttribute('content', response.token);
				},
				complete() {
					table.ajax.reload();
				}
			});
		})
	});

	/**
	 * IMPORT DATA TEACHERS
	 */

	let impForm = document.forms['import-teacher'];
	let impFormat = impForm['import-format'],
		impFile = impForm['file-upload'];

	let importFormat = null,
		importFile = null;
	impFile.addEventListener('change', e => {
		e.preventDefault();
		let lbl = document.querySelector('.custom-file-label');
		lbl.innerHTML = e.target.files[0].name;
		importFile = e.target.files[0];
	});
	impForm.addEventListener('submit', e => {
		e.preventDefault();
		let fdata = new FormData();
		fdata.append('format', impFormat.value);
		fdata.append('upload-file', importFile);
		// upload 
		$.ajax({
			url: base_url + 'api/teacher/import',
			type: 'POST',
			data: fdata,
			contentType: false,
			processData: false,
			xhr() {
				var myXhr = $.ajaxSettings.xhr();
				if (myXhr.upload) {
					myXhr.upload.addEventListener('progress', (evt) => {
						if (evt.lengthComputable) {
							var percentage = (evt.loaded / evt.total) * 100;
							var progress = document.getElementById('import-progress').getElementsByClassName('progress-bar')[0];
							console.log(progress);
							progress.style.width = percentage + '%';
							progress.setAttribute('aria-valuenow', percentage);
						}

					}, false);
				}
				return myXhr;
			},
			success(reslv) {
				Swal.fire({
					type: 'success',
					title: `<h5 class="text-success text-uppercase">Berhasil</h5>`,
					html: 'Data berhasil diimport'
				});
				let progress = Math.ceil(reslv.prog / reslv.total * 100);
				console.log(progress);
			},
			error(err) {
				let response = JSON.parse(err.responseText);
				Swal.fire({
					type: response.err_status,
					title: '<h5 class="text-danger text-uppercase">' + response.err_status + '</h5>',
					html: response.message
				});
			},
			complete() {
				table.ajax.reload();
				$('#modal-import').modal('hide');
			}
		});

	});

})(jQuery, document.querySelector('base').href);
