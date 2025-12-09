(async ($, base_url) => {
    'use strict';

		const form = document.forms['form-add'];
			
    let csrfToken = document.querySelector('meta[name="csrf_token"]') ;
				
    var formAdd = document.getElementById('form-add'),
				formEdit = document.getElementById('form-edit'),
        is_update = 0;
				
    var table = $('#tbl_sekolah').DataTable({
        serverSide: true,
        processing: true,
        ajax: {
            url: base_url + 'api/sekolah/getAll',
            type: 'POST',
            data: function(d) {   
                return d;
            }
        }, 
		select: {
			style:	'multi', 
			selector: 'td:first-child'
		},
        columns: [  
			{
				data:null,
				width: '30px',
				className: 'select-checkbox ',
				checkboxes: {
					selectRow: true
				},
				orderable: false,
				render(data, row, type, meta) {
					return '';
				}
			},
			{
				data: 'sekolah_id',
				visible: false
			}, 
			{
				data: 'sekolah_kode'
			},
			{
				data: 'sekolah_nama'
			},
			{
				data: 'sekolah_alamat'
			}, 
			{
				data: 'sekolah_phone',
				visible: true
			}, 
			{
				data: 'kode_aktivasi',
				visible: false
			}, 
			{
				data: null,
				render(data, row, type, meta) {
					var view = '<div class="btn-group btn-group-sm float-right">'+
									'<button class="btn btn-success edit_sekolah"><i class="bx bx-edit-alt font-size-12"></i></button>' +
									'<button class="btn btn-sm btn-danger delete_kelas"><i class="bx bx-trash font-size-12"></i></button>' +
								'</div>';
					return view;
				}
			}
		],
		pageLength: 8,
		language:{
			processing:   '<div class="d-flex flex-column align-items-center shadow">'
						+	'<span class="spinner-border text-info"></span>'
						+	'<h4 class="mt-2"><strong>Loading...</strong></h4>'
						+ '</div>',
		}

    });
 
 
    //select_all
    $('#select_all').on('click', e => {
        if(e.target.checked)
            table.rows().select();
        else
            table.rows().deselect();
    });

    $('#btn-add').on('click', e => {
        is_update = false;
		// document.getElementsByName('a_kode_aktivasi')[0].closest('.align-items-center').classList.remove('d-none');
        form.reset(); 
        form['xsrf'].value = csrfToken.content;
    });

    $('#btn-refresh').on('click', e => {
        table.ajax.reload();
    });


	/**
	 * SEARCH DATA
	 */
	document.getElementById('btn-search_class').addEventListener('click', e => {
			e.preventDefault();
			let sekolah_nama = document.getElementsByName('s_sekolah_nama')[0]; 
			if(typeof sekolah_nama.value !== 'undefined' || sekolah_nama.value !== null)
					table.column(2).search(sekolah_nama.value).draw();
	});

	document.getElementsByName('s_sekolah_nama')[0].addEventListener('keypress', e => {
		// If the user presses the "Enter" key on the keyboard
		if (e.key === "Enter") {
			// Cancel the default action, if needed
			e.preventDefault();
			// Trigger the button element with a click
			document.getElementById("btn-search_class").click();
		  }
	});



	/**
	 * SAVE DATA
	 */
	const btnSubmit = document.getElementById('save-kelas'); 

	$('#tbl_sekolah tbody').on('click', '.btn.edit_sekolah', e => {
		is_update = true;
		let target = e.target;
		let row = table.row($(target).parents('tr')).data(); 
		form['a_sekolah_id'].value = row.sekolah_id;
		form['a_sekolah_kode'].value = row.sekolah_kode;
		form['a_sekolah_nama'].value = row.sekolah_nama;
		form['a_sekolah_alamat'].value = row.sekolah_alamat;
		form['a_sekolah_phone'].value = row.sekolah_phone;
		form['a_kode_aktivasi'].value = row.kode_aktivasi;
		form['a_kode_aktivasi'].closest('.align-items-center').classList.add('d-none');
		form['xsrf'].value = csrfToken.content; 
		$('#modal-add').modal('show');
	});

 // submit
	btnSubmit.addEventListener('click', e => {
		e.preventDefault();

		let frmObj = { 
				sekolah_id: form['a_sekolah_id'].value,
				sekolah_kode: form['a_sekolah_kode'].value, 
				sekolah_nama: form['a_sekolah_nama'].value, 
				sekolah_alamat: form['a_sekolah_alamat'].value, 
				sekolah_phone: form['a_sekolah_phone'].value, 
				kode_aktivasi: form['a_kode_aktivasi'].value, 
				xsrf_token: form['xsrf'].value
		};
		let conf = {};
		if(is_update) {
				conf = {
						url: base_url + 'api/sekolah/edit_data',
						type: 'PUT',
						contentType: 'application/json',
						data: JSON.stringify(frmObj)
				};
		} else {
				conf = {
						url: base_url + 'api/sekolah/add_data',
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
});
		

	/**
	 * DELETE SINGLE
	 */
  $('#tbl_sekolah tbody').on('click', '.btn.delete_kelas', e =>{
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
				if(!reslt.value)
						return false;
				$.ajax({
					url: base_url + 'api/sekolah/delete_data',
					type: 'DELETE',
					contentType: 'application/json',
					data: JSON.stringify({bulk: false, data: row.sekolah_id, xsrf_token: csrfToken.content}),
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
      });
   });
	 
	 /**
	 *DELETE MULTI
	 */
	document.querySelector('#delete_all').addEventListener('click', e => {
		e.preventDefault();
		let rows = table.rows({selected: true}).data(),
			count = table.rows({selected: true}).count();
		if(count === 0) {
			Swal.fire({
				title: "Tidak Ada Data Terpilih!",
				text: "Harap pilih data yang akan dihapus terlebih dahulu.",
				confirmButtonClass: "btn btn-warning mt-2",
				type: "warning"
			});
      return false;
    }

		let datas = [];
		for(let i=0;i<rows.length;i++) { 
				datas.push(rows[i].class_id);
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
			if(!reslt.value)
					return false;
			$.ajax({
				url: base_url + 'api/kelas/delete_data',
				type: 'DELETE',
				contentType: 'application/json',
				data: JSON.stringify({bulk: true, data: datas, xsrf_token: csrfToken.content}),
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
		})
  	});	 

})(jQuery, document.querySelector('base').href);
