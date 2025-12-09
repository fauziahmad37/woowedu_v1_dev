'use strict';
const   base_url = document.querySelector('base').href,
		sekolah    = document.getElementsByName('slc-sekolah')[0]; 

function bulanIndonesia(bulan){
	switch(bulan) {
		case 0: bulan = "Januari"; break;
		case 1: bulan = "Februari"; break;
		case 2: bulan = "Maret"; break;
		case 3: bulan = "April"; break;
		case 4: bulan = "Mei"; break;
		case 5: bulan = "Juni"; break;
		case 6: bulan = "Juli"; break;
		case 7: bulan = "Agustus"; break;
		case 8: bulan = "September"; break;
		case 9: bulan = "Oktober"; break;
		case 10: bulan = "November"; break;
		case 11: bulan = "Desember"; break;
	}

 	return bulan 
}

(async $ => {
    await getSekolah(); 
     

    /**
     =================================================== 
     *                  VIEWS
     ===================================================
     */
        // DATA TABLE
        var table = $('#tbl-users').DataTable({
            serverSide: true,
            processing: true,
            ajax: {
                url: base_url + 'users/getTable',
            },
            select: {
		    	style:	'multi',
		    	selector: 'tr:not(.no-select) td:first-child'
		    },
            columns: [
                {
                    data: null,
                    className: 'select-checkbox',
                    width: '30',
                    checkboxes: {
                        selectRow: true
                    },
                    orderable: false,
                    render(data, type, row, _meta) {
                        return '';
                    }
                },
                {
                    data: 'userid',
                    visible: false
                },
                {
                    data: 'sekolah_id',
                    visible: false
                },
                {
                    data: 'username'
                },
                {
                    data: null,
					render(data, type, row, _meta){
						return row.sekolah_kode+` - `+row.sekolah_nama
					}
                },
                {
                    data: 'password',
                    visible: false
                },
                {
                    data: 'user_level',
                    visible: false
                },
                {
                    data: 'user_level_name'
                },
                {
                    data: 'date_limit',
					render(data){
						let tanggal =  new Date(data)
						return `${tanggal.getDate()} ${bulanIndonesia(tanggal.getMonth()).substring(0, 3)} ${tanggal.getFullYear()}, ${tanggal.getHours()}:${tanggal.getMinutes()}`
					},
                },
                {
                    data: 'active',
                    render(data, row, type, _meta) {
						console.log(data)
                        var checked = data == '1' ? 'checked' : '';
                        var view = '<div class="custom-control custom-switch">'
                                      + '<input disabled type="checkbox" '+checked+' class="custom-control-input" id="active_'+_meta.row+'">'
                                      + '<label class="custom-control-label" for="active_'+_meta.row+'">&nbsp;</label>'
                                    '</div>';
                        return view;
                    }
                },
                {
                    data: 'userid',
                    width: '30',
                    className: 'text-center',
                    render(data, row, type, meta) {
                        let btn = null;
                        if(data === '1')
                        btn = '<div class="btn-group btn-group-sm float-right" role="group" aria-label="Basic example">'
                                + '<button class="btn btn-success edit_data" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit"><i class="bx bx-edit-alt font-size-12"></i></button>'
                                + '</div>';
                        else
                            btn = '<div class="btn-group btn-group-sm float-right" role="group" aria-label="Basic example">'
									+ '<button class="btn btn-primary update_activation"><i class="fa fa-power-off font-size-12"></i></button>'
                                    + '<button class="btn btn-success edit_data" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit"><i class="bx bx-edit-alt font-size-12"></i></button>'
                                    + '<button class="btn btn-danger delete_data" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit"><i class="fa fa-trash font-size-12"></i></button>'
                                    + '</div>';
                        return btn;
                    }
                }
            ],
            createdRow: function(row, data, index) {
                if(data.userid === '1') {
                    row.classList.add('no-select');
                    row.getElementsByTagName('td')[0].classList.remove('select-checkbox');
                }
            },
            language:{
                processing:   '<div class="d-flex flex-column align-items-center shadow">'
                            +	'<span class="spinner-border text-info"></span>'
                            +	'<h4 class="mt-2"><strong>Loading...</strong></h4>'
                            + '</div>',
            }
        });

        // SELECT ALL TABLE
        $('#select_all').on('click', e => {
            if(e.target.checked)
                table.rows('tr:not(.no-select)').select();
            else
                table.rows().deselect();
        });

        // SELECT USER LEVEL
        $.get(base_url + 'users/getUserLevel')
        .done((resp) => {
            var level = document.getElementById('slc-userlevel');
            level.innerHTML = null;
            for(var i=0;i<resp.length;i++) {
                var opt = document.createElement('option');
                opt.value = resp[i].user_level_id;
                opt.text  = resp[i].user_level_name;
                level.add(opt);
            }
        });
        
    $(sekolah).selectpicker('val', null);
    // reset select
    document.getElementById('reset-sekolah').addEventListener('click', e => {
        $(sekolah).selectpicker('val', null);
    });

		
    $(sekolah).on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) {
        e.stopPropagation();
        e.preventDefault();
        const el = e.target;
        if(clickedIndex != null)
            document.querySelector('input[name="txt-sekolah"]').value = (el.options[clickedIndex]).innerText;
    });

	// ACTIVE INACTIVE USER
	$('#tbl-users tbody').on('click', '.custom-switch', e => {
		var row = table.row(e.target.parentNode.closest('tr')).data();
		var id = row.userid;
		var status = row.active == '1' ? 0 : 1;
		let data = {
			userid: id,
			active: status
		}
		
		// ajax post
		$.ajax({
			url: base_url + 'users/updateStatus',
			type: 'POST',
			data: JSON.stringify(data),
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
			}
		})
		.done(resp => {
			Swal.fire({
				type: resp.err_status,
				title:`<h5 class="text-${resp.err_status} text-uppercase">${resp.err_status}</h5>`,
				html: resp.message
			});
		})
		.fail(err => {
			let response = JSON.parse(err.responseText);
			Swal.fire({
				type: response.err_status,
				title: '<h5 class="text-danger text-uppercase">'+response.err_status+'</h5>',
				html: response.message
			});
		})
		.always(() => {
			table.ajax.reload();
		});
	});
		
		
    /**
     ===================================================
     *               SUBMIT DATA
     ===================================================
     */

     var formAdd = document.forms['form-add'],
         is_update = 0;

    $('#btn-new').on('click', e => {
        is_update = 0;
        formAdd.reset();
    });

    formAdd['txt-password'].addEventListener('focus', e => {
        if(e.target.value === '***')
            e.target.value = '';
    })
    formAdd['txt-password'].addEventListener('blur', e => {
        if(e.target.value === '' || e.target.value === null)
            e.target.value = '***';
    });

	/** 
	 * Edit Data
	*/

    $('#tbl-users tbody').on('click', '.btn.edit_data', e => {
        var row = table.row(e.target.parentNode.closest('tr')).data();

        formAdd['userid'].value = row.userid; 
        formAdd['txt-username'].value = row.username;
        formAdd['slc-userlevel'].value = row.user_level;
		$(sekolah).selectpicker('val', row.sekolah_id);
        formAdd['chk-active'].checked = row.active === '1' ? true : false;

        $('#modal-add').modal('show');
        is_update = 1;
    });

     formAdd.addEventListener('submit', e => {
         e.preventDefault();

         let url = is_update === 0 ? base_url + 'users/post' : base_url + 'users/put';

         $.ajax({
            url: url,
            type:'POST',
            data: $(e.target).serialize(),
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
				 
				  $('#modal-add').modal('hide');
     });


	 /**
	  * Update Activation
	  * 
	  */
	
	$('#tbl-users tbody').on('click', '.btn.update_activation', e => {
		var row = table.row(e.target.parentNode.closest('tr')).data();

		// set form value
		let form = document.forms['form-activation'];
		form['userid'].value = row.userid;
		
		// show modal
		$('#modal-activation').modal('show');
	});


	/**
	 * Submit Activation
	 */

	var formActivation = document.forms['form-activation'];
	formActivation.addEventListener('submit', e => {
		e.preventDefault();
		$.ajax({
			url: base_url + 'users/activation',
			type: 'POST',
			data: $(e.target).serialize(),
			beforeSend(xhr, obj) {
				Swal.fire({
					html: 	'<div class="d-flex flex-column align-items-center">'
					+ '<span class="spinner-border text-primary"></span>'
					+ '<h3 class="mt-2">Loading...</h3>'
					+ '<div>',
					showConfirmButton: false,
					width: '10rem'
				});
			}
		})
		.done(resp => {
			Swal.fire({
				type: resp.err_status,
				title: `<h5 class="text-${resp.err_status} text-uppercase">${resp.err_status}</h5>`,
				html: resp.message
			});
		})
		.fail(err => {
			let response = JSON.parse(err.responseText);
			Swal.fire({
				type: response.err_status,
				title: '<h5 class="text-danger text-uppercase">'+response.err_status+'</h5>',
				html: response.message
			});
		})
		.always(() => {
			table.ajax.reload();
			$('#modal-activation').modal('hide');
		});
	});



    /**
     ===================================================
     *               DELETE DATA
     ===================================================
     */

     // DELETE ALL
     $('#btn-deleteAll').on('click', e => {
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
            arr.push(data[i].userid);
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
    $('#tbl-users tbody').on('click', '.btn.delete_data', e => {
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
                    erase(row.userid, 0);
                }
            })
        });

     function erase(data, isBulk) {
        return $.ajax({
            url: base_url + '/users/delete',
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
     ===================================================
     *               SEARCH DATA
     ===================================================
     */

     var s_username     = document.getElementById('s_username'),
         s_userlevel    = document.getElementById('s_userlevel');

    $('#btn-search').on('click', e => {
        if(s_username.value !== '' || s_username.value !== null)
            table.columns(2).search(s_username.value).draw();
        if(s_userlevel.value !== '' || s_userlevel.value !== null)
            table.columns(5).search(s_userlevel.value).draw();
    });

    $('#reset-search').on('click', e => {
        var form = document.forms['form-search'];
        form.reset();
        table.columns(2).search(s_username.value).draw();
        table.columns(5).search(s_userlevel.value).draw();
    });   
})(jQuery);


async function getSekolah() {
	 try 
    {
        const f = await fetch(`${base_url}api/sekolah/getAll`);
        let datas = await f.json();
        console.log(datas);
        for(let d of datas.data)
        {
            let opt = document.createElement('option');
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

