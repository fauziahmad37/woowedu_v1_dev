'use strict';
const base_url = document.querySelector('base').href,
      form = document.forms['form-add'],
      selectStudent = document.querySelector('select[name="a_children"]'),
      csrfToken = document.querySelector('meta[name="csrf_token"]') ;
let isUpdate = 0;

const table = $('#tbl-parent').DataTable({
    serverSide: true,
    processing: true,
    ajax: {
        url: new URL(base_url + 'orangtua/list')
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
            data: 'parent_id',
            visible: false
        },
        {
            data: 'username',
            visible: false
        },
        {
            data: 'name'
        },
		{
			data: 'sekolah_id',
			visible: false
		},
		{
			data: 'gender',
			visible: false
		},
        {
            data: 'wali_dari'
        },
        {
            data: 'address'
        },
        {
            data: 'phone'
        },
        {
            data: 'email'
        },
        {
            data: null,
            render(data, row, type, meta) {
                var view = '<div class="btn-group btn-group-sm float-right">'+ 
                                '<button class="btn btn-success edit_data"><i class="bx bx-edit-alt font-size-12"></i></button>' +
                                '<button class="btn btn-sm btn-danger delete_data"><i class="bx bx-trash font-size-12"></i></button>' +
                            '</div>';
                return view;
            }
        }
    ],
    language:{
        processing:   '<div class="d-flex flex-column align-items-center shadow">'
                    +	'<span class="spinner-border text-info"></span>'
                    +	'<h4 class="mt-2"><strong>Loading...</strong></h4>'
                    + '</div>',
    }
});

table.columns(4).search($('#sekolah_id').val()).draw();

// ########################### SEARCH STUDENT ###########################
$('#btn-search-parent').on('click', function(e){
	e.preventDefault();
	table.columns(3).search($('input[name="s_parent_name"]').val()).draw();
});

document.getElementsByName('s_parent_name')[0].addEventListener('keypress', e => {
	// If the user presses the "Enter" key on the keyboard
	if (e.key === "Enter") {
		// Cancel the default action, if needed
		e.preventDefault();
		// Trigger the button element with a click
		document.getElementById("btn-search-parent").click();
	  }
});

$('input[name="s_parent_name"]').on('search', e => {
    table.columns(3).search($('input[name="s_parent_name"]').val()).draw();
});
//select_all
$('#select_all').on('click', e => {
    if(e.target.checked)
        table.rows().select();
    else
        table.rows().deselect();
});

$('#btn-refresh').on('click', e => {
    table.ajax.reload();
});

// SELECT CHILDREN


/**
 * Description placeholder
 * @date 9/27/2023 - 3:14:01 PM
 *
 * @async
 * @returns {unknown}
 */
const getStudents = async () => {

    try 
    {
        const f = await fetch(base_url + 'api/student/get_all');
        const j = await f.json();
        
        return j.data;
    } 
    catch (err) 
    {
        console.log(err);
    }
}


/**
 * Description placeholder
 * @date 9/27/2023 - 3:13:56 PM
 *
 * @async
 * @returns {*}
 */
const setStudentSelect = async () => {
    try 
    {
        const students = [...await getStudents()];
        selectStudent.innerHTML = null;
    
        Array.from(students, item => {
            const option = document.createElement('option');

            option.text = `${item.student_name} - ${item.nis}`;
            option.value = item.student_id;

            selectStudent.add(option);
        });
    } 
    catch (err) 
    {
        console.log(err);
    }
}

const btnAdd = e => {
    isUpdate = 0;
    $('select[name="a_children"]').selectpicker('val', null);
    form.reset();
}

const btnEdit = e => {
    const data = table.row(e.target.parentNode.closest('tr')).data();
    isUpdate = 1;

    form['a_parent_id'].value = data.parent_id;
    form['a_username'].value = data.username;
    form['a_full_name'].value = data.name;
    form['a_address'].value = data.address;
    form['a_email'].value = data.email;
    form['a_gender'].value = data.gender;
    form['a_phone'].value = data.phone;
	// check if data student_if if empty
	// proses pengambilan data untuk selectpicker
	if(data.student_id){
		const students = data.student_id.split(',');
		$('select[name="a_children"]').selectpicker('val', students.map(i => +i));
	} else {
		$('select[name="a_children"]').selectpicker('val', null);
	}

    $('#modal-add').modal('show');
}


/**
 * Submit Form
 * @date 9/28/2023 - 10:13:02 PM
 *
 * @param {*} e
 */
const submit = async e => {
    e.preventDefault();

    let url = isUpdate == 0 ? base_url + 'orangtua/store' : base_url + 'orangtua/edit';
    const children = $('select[name="a_children"]').selectpicker('val');
    const entries = {...Object.fromEntries(new FormData(e.target)), a_children: children };
    const params = new URLSearchParams(entries);

    $.ajax({
        url: url,
        type: 'POST',
        data: params.toString(),
        contentType: 'application/x-www-form-urlencoded',
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
                html: resp.message,
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
}


/* ######################## DELETE DATA ################################*/
/**
 * DELETE SINGLE
 */
$('#tbl-parent tbody').on('click', '.btn.delete_data', e =>{
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
            url: base_url + 'orangtua/delete',
            type: 'DELETE',
            contentType: 'application/json',
            data: JSON.stringify({bulk: false, data: row.parent_id, xsrf_token: csrfToken.content}),
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
            datas.push(rows[i].parent_id);
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
            url: base_url + 'api/student/delete_data',
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
    });
});


(async $ => {

    await setStudentSelect();
    $('select[name="a_children"]').selectpicker({
        liveSearch: true,
        deselectAllText: true,
        noneSelectedText:  '- Pilih Murid -',
    });

    console.log(form.querySelectorAll('input'));

    document.querySelector('#btn-add').addEventListener('click', e => btnAdd(e));

    $('#tbl-parent tbody').on('click', '.btn.edit_data', e => btnEdit(e));

    form.addEventListener('submit', e => submit(e));

    document.getElementById('save-parent').addEventListener('click', e => {
        const evt = new Event('submit');
        form.dispatchEvent(evt);
    });

	/**
	 * IMPORT DATA
	 */

	let impForm = document.forms['import-ortu'];
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
					url: base_url + 'orangtua/import',
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

})(jQuery);
