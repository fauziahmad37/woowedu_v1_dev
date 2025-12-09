'use strict';
const base_url = document.querySelector('base').href, 
      btnAdd = document.getElementById('btn-add'),
      btnRefresh = document.getElementById('btn-refresh'),
      btnDeleteAll = document.getElementById('delete_all'),
	  sekolahId = document.getElementsByName('sekolah_id')[0].value;

(async ($) => {
 
    /**
     *  DATATABLE 
     */
    const table = $('#tbl_mapel').DataTable({
        serverSide: true,
        ajax: {
            url: base_url + 'api/subject/getAll?sekolah_id='+sekolahId,
            method: 'POST'
        },
        select: {
			style:	'multi',  
			selector: 'td:first-child'
		},
        columns: [
            {
                data: null,
                className: 'select-checkbox',
                checkboxes: {
                    selectRow: true
                },
                orderable: false,
                render(data, type, row, _meta) {
                    return '';
                }
            },
            {
                data: 'id_mapel',
                visible: false
            },
            {
                data: 'kode_mapel'
            },
            {
                data: 'nama_mapel'
            },
            {
                data: 'id_kelas',
                visible: false
            },
            {
                data: 'nama_kelas'
            },
            {
                data: null,
                className: 'align-center',
                render(data, row, type, meta) {
                    var view = '<div class="btn-group btn-group-sm float-right">'+
                                    '<button class="btn btn-success edit_subject"><i class="bx bx-edit-alt font-size-12"></i></button>' +
                                    '<button class="btn btn-sm btn-danger delete_subject"><i class="bx bx-trash font-size-12"></i></button>' +
                                '</div>';
                    return view;
                }
            }
        ]
    });

	/**
	 * SEARCH DATA
	 */
	document.getElementById('search-button').addEventListener('click', e => {
		e.preventDefault();
		let class_name = document.getElementsByName('s_sn')[0]; 
		if(typeof class_name.value !== 'undefined' || class_name.value !== null)
				table.column(3).search(class_name.value).draw();
	});

	document.getElementsByName('s_sn')[0].addEventListener('keypress', e => {
		// If the user presses the "Enter" key on the keyboard
		if (e.key === "Enter") {
			// Cancel the default action, if needed
			e.preventDefault();
			// Trigger the button element with a click
			document.getElementById("search-button").click();
		}
	});

    $('#select_all').on('click', e => {
        if(e.target.checked)
            table.rows().select();
        else
            table.rows().deselect();
    });


    let frmAdd = document.forms['form-add']; 
    let isUpdate = false;		
    $('#tbl_mapel > tbody').on('click', '.btn.edit_subject', e => {
 
        isUpdate = true;
        const count = table.row(e.target.parentNode.closest('tr')).count(),
              item = table.row(e.target.parentNode.closest('tr')).data();

        frmAdd['a_subject_code'].value = item.kode_mapel;
        frmAdd['a_subject_name'].value = item.nama_mapel;
        frmAdd['a_subject_class_level'].value = item.id_kelas;
        frmAdd['a_id'].value = item.id_mapel; 
        frmAdd['a_subject_thumbnail_pic'].value = ''; 
        frmAdd['a_subject_detail_pic'].value = ''; 
        $('#modal-add').modal('show');
				
				document.querySelector('.thumbnail-label').innerHTML = '';							
				document.querySelector('.detail-label').innerHTML = '';							
        
    });


    btnAdd.addEventListener('click', e => {
        isUpdate = false; 
        frmAdd.reset();
    });		
		
    /**
     *  FORM SUBMIT
     * 
     */

		let a_id = frmAdd['a_id'];
		let subject_code = frmAdd['a_subject_code'];
		let subject_class = frmAdd['a_subject_class_level'];
		let subject_name = frmAdd['a_subject_name'];
		let thumbFile = frmAdd['a_subject_thumbnail_pic'];
		let detFile = frmAdd['a_subject_detail_pic'];
		let thumbnailFile = null;
		let detailFile = null;
		
		thumbFile.addEventListener('change', e => {
			e.preventDefault();  
			let lbl = document.querySelector('.thumbnail-label');
			lbl.innerHTML = e.target.files[0].name;			
			thumbnailFile = e.target.files[0];
		});

		detFile.addEventListener('change', e => {
			e.preventDefault();  
			let lbl = document.querySelector('.detail-label');
			lbl.innerHTML = e.target.files[0].name;					
			detailFile = e.target.files[0];
		});		

		frmAdd.addEventListener('submit', e => {
            e.preventDefault();
            let fdata = new FormData(); 
            let ajaxurl ;
            if(isUpdate)
                    ajaxurl = 'api/subject/edit';
            else
                    ajaxurl = 'api/subject/save';
            fdata.append('a_id', a_id.value); 
            fdata.append('sekolah_id', sekolahId); 
            fdata.append('a_subject_code', subject_code.value); 
            fdata.append('a_subject_name', subject_name.value); 
            fdata.append('a_subject_class', subject_class.value); 
            fdata.append('thumb-file', thumbnailFile); 
            fdata.append('detail-file', detailFile); 
            $.ajax({
                    url: base_url + '' + ajaxurl,
                    type: 'POST',
                    data: fdata,
                    contentType: false,
                    processData: false,
                    xhr() {
                            var myXhr = $.ajaxSettings.xhr();
                            if (myXhr.upload) {
                        
                            }
                            return myXhr;
                    },
                    success(reslv) {
                        Swal.fire({
                            type: 'success',
                            title:`<h5 class="text-success text-uppercase">Berhasil</h5>`,
                            html: 'Data berhasil disimpan'
                        });							
                        let progress = Math.ceil(reslv.prog / reslv.total * 100);
                        console.log(progress);
                    },
                    error(err) {
                            let response = JSON.parse(err.responseText);
                            Swal.fire({
                                type: response.err_status,
                                title: '<h5 class="text-danger text-uppercase">'+response.err_status+'</h5>',
                                html: response.message
                            });
                    },
                    complete() {				
                            table.ajax.reload();
                            $('#modal-add').modal('hide');
                    }
            }); 
            
    });	



     /**
     ===================================================
     *               DELETE DATA
     ===================================================
     */

     // DELETE ALL
     $('#delete_all').on('click', e => {
        var rows = table.rows({selected: true});
        var data = rows.data(),
            count = rows.count();
        var arr = [];
        if(count === 0) {
            Swal.fire({
				type: "error",
				title:'<h5 class="text-danger text-uppercase">Error</h5>',
				text: "No row selected !!!"
			});
			return false;
        }

        for(var i =0; i< count; i++) {
            arr.push(data[i].id_mapel);
        }

        Swal.fire({
            title: "Anda Yakin ?",
            text: "Data yang dihapus tidak dapat dikembalikan",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn btn-success mt-2",
            cancelButtonColor: "#f46a6a",
            confirmButtonText: "Ya, Hapus Data",
            cancelButtonText: "Tidak, Batalkan Hapus",
            closeOnConfirm: false,
            closeOnCancel: false
        }).then(t => {
            if(t.value) {
                erase(arr, 1);
            }
        })
     });

    // DELETE ONE
    $('#tbl_mapel tbody').on('click', '.btn.delete_subject', e => {
        var row = table.row($(e.target).parents('tr')).data();
        Swal.fire({
            title: "Anda Yakin ?",
            text: "Data yang dihapus tidak dapat dikembalikan",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn btn-success mt-2",
            cancelButtonColor: "#f46a6a",
            confirmButtonText: "Ya, Hapus Data",
            cancelButtonText: "Tidak, Batalkan Hapus",
            closeOnConfirm: false,
            closeOnCancel: false
            }).then(t => {
                if(t.value) {
                    erase(row.id_mapel, 0);
                }
            })
        });

     function erase(data, isBulk) {
        return $.ajax({
            url: base_url + 'api/subject/delete',
            type: 'DELETE',
            data: JSON.stringify({data: data, isBulk: isBulk}),
            contentType: 'application/json',
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
				//csrfToken.content = resp.token;
            },
            error(err) {
                let response = JSON.parse(err.responseText);
				Swal.fire({
					type: response.err_status,
					title: '<h5 class="text-danger text-uppercase">'+response.err_status+'</h5>',
					html: response.message
				});
				//if(response.hasOwnProperty('token'))
				//	csrfToken.setAttribute('content', response.token);
            },
            complete() {
                table.ajax.reload();
            }
        });
     }



	 /**
	 * IMPORT DATA
	 */

	let impForm = document.forms['import-area'];
	let impFormat = impForm['import-format'],
			impFile = impForm['file-upload'];

	let importFormat = null,
		importFile = null;
		impFile.addEventListener('change', e => {
			e.preventDefault();
			
			let lbl = document.querySelectorAll('.custom-file-label')[2];
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
					url: base_url + 'api/subject/import',
					type: 'POST',
					data: fdata,
					contentType: false,
					processData: false,
					xhr() {
							var myXhr = $.ajaxSettings.xhr();
							if (myXhr.upload) {
									myXhr.upload.addEventListener('progress', (evt) => {
											if(evt.lengthComputable) {
													var percentage = (evt.loaded/evt.total) * 100;
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
							title:`<h5 class="text-success text-uppercase">Berhasil</h5>`,
							html: 'Data berhasil diimport'
						});							
						let progress = Math.ceil(reslv.prog / reslv.total * 100);
						console.log(progress);
					},
					error(err) {
							let response = JSON.parse(err.responseText);
							Swal.fire({
								type: response.err_status,
								title: '<h5 class="text-danger text-uppercase">'+response.err_status+'</h5>',
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

 




