'use strict';
const base_url = document.querySelector('base').href, 
      mapel = document.getElementsByName('a_exam_subject')[0], 
      kategori = document.getElementsByName('a_exam_category')[0],
      btnAdd = document.getElementById('btn-add'),
      s_code = document.getElementById('s_code'), 
      s_mapel = document.getElementById('s_mapel'),
      fs = document.forms['form-search'],
      frm = document.forms['form-add'];


(async $ => {
    const table = $('#tbl_ujian').DataTable({
        serverSide: true,
        processing: true,
        ajax: {
            url: base_url + 'api/exam/getAll'
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
                data: 'id_ujian',
                visible:false
            },
            {
                data: 'kode_ujian'
            },
            {
                data: 'category_id',
                visible: false
            },
            {
                data: 'category_name',

            },
            {
                data: 'class_id',
                visible: false
            },
            {
                data: 'class_name'
            },
            {
                data:'subject_id',
                visible: false
            },
            {
                data: 'subject_code',
                visible: false
            },
            {
                data: 'subject_name'
            } ,
            {
                data: 'total_soal',
                render(data, type, row, meta) {
                    return (!data) ? 0 : data;
                }
            },
            {
                data: null,
                render(data, type, row, meta) {
                    var view = '<div class="btn-group btn-group-sm float-right">'+
                                    '<button class="btn bg-purple text-white add_soal" data-toggle="tooltip" title="Soal"><i class="fas fa-list-ol"></i></button>' +
                                    '<button class="btn btn-success edit_exam" data-toggle="tooltip" title="Edit"><i class="bx bx-edit-alt font-size-12"></i></button>' +
                                    '<button class="btn btn-sm btn-danger delete_exam" data-toggle="tooltip" title="Delete"><i class="bx bx-trash font-size-12"></i></button>' +
                                '</div>';
                    return view;
                }
            }
        ]
    });

    $('#btn-refresh').on('click', e => {
        table.ajax.reload();
    })
    
    document.getElementById('select_all').addEventListener('click', e => {

        if(e.target.checked)
            table.rows().select();
        else
            table.rows().deselect();
    });
 
    await getMapel();
    await getCategories();
 
    $(mapel).selectpicker('val', null);
    $(kategori).selectpicker('val', null);
 
    $(s_mapel).selectpicker('val', null);

 
	/**
	 * SAVE DATA
	 */
    let isUpdate = false;

    btnAdd.addEventListener('click', e => {
        isUpdate = false;
        frm.reset(); 
        $(mapel).selectpicker('val', null);
        $(kategori).selectpicker('val', null);
        frm['a_exam_code'].value = makeid(8);

    });

		const btnSubmit = document.getElementById('save-exam'); 
 

    $('#tbl_ujian tbody').on('click', '.btn.edit_exam', e => {
        isUpdate = true;
        let row = table.row(e.target.parentNode.closest('tr')).data();
        
        frm['a_exam_code'].value = row.kode_ujian;
        $(mapel).selectpicker('val', row.subject_id);  
        frm['a_id'].value = row.id_ujian; 
        $(kategori).selectpicker('val', row.category_id);

        $('#modal-add').modal('show');
    })

		// submit
		btnSubmit.addEventListener('click', e => {
        e.preventDefault();

				let frmObj = { 
						a_exam_code: frm['a_exam_code'].value,
						a_id: frm['a_id'].value, 
						a_exam_subject: frm['a_exam_subject'].value, 
						a_exam_category: frm['a_exam_category'].value 
				};
				let conf = {};
				if(isUpdate) {
						conf = {
								url: base_url + 'api/exam/edit',
								type: 'PUT',
								contentType: 'application/json',
								data: JSON.stringify(frmObj)
						};
				} else {
						conf = {
								url: base_url + 'api/exam/save',
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
            arr.push(data[i].id_ujian);
        }

        Swal.fire({
            title: "Anda Yakin ?",
            text: "Data yang dihapus tidak dapat dikembalikan",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn btn-success mt-2",
            cancelButtonColor: "#f46a6a",
            confirmButtonText: "Ya",
            cancelButtonText: "Tidak",
            closeOnConfirm: false,
            closeOnCancel: false
        }).then(t => {
            if(t.value) {
                erase(arr, 1);
            }
        })
     });

    // DELETE ONE
    $('#tbl_ujian tbody').on('click', '.btn.delete_exam', e => {
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
                    erase(row.id_ujian, 0);
                }
            })
        });

     function erase(data, isBulk) {
        return $.ajax({
            url: base_url + 'api/exam/delete',
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
      * ======================================
      *         SEARCH
      * ======================================
      */

        $('#search-button').on('click', e => {
            if(s_code.value !== '' || s_code.value !== null)
                table.columns(2).search(s_code.value).draw(); 
        });

        $('#reset-search').on('click', e => {
            var form = document.forms['form-search'];
            form.reset();
            table.columns(2).search(s_code.value).draw(); 
        });   

    /**
     * ==================================================================
     *              TAMBAH/EDIT SOAL
     * ==================================================================
     */

  

    const tblSelected = $('#tbl_selected').DataTable({
        serverSide: false,
        processing: true,
        ajax: {
            url: base_url + 'api/exam/getReserve',
            method: 'GET',
            dataSrc: 'data' 
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
                data: 'number' 
            },
            {
                data: 'soal_id',
                visible: false
            },
            {
                data: 'soal_code'
            },
            {
                data: 'type',
                render(data, row, type, _meta)
                {
                    switch(parseInt(data)) {
                        case 1:
                            return 'PG';
                        case 2: 
                            return 'Essay';
                        case 3:
                            return 'B/S';
                    }
                }
            },
            {
                data: 'bobot' 
            }
        ]
    });


    $('#tbl_ujian tbody').on('click', '.btn.add_soal', async e => {
        const row = table.row(e.target.parentNode.closest('tr')).data();

        document.getElementById('exam-id').value = row.id_ujian; 
        document.getElementById('mapel-id').value = row.subject_id;

        document.getElementById('n_exam_class_name').value = row.class_name;
        document.getElementById('n_exam_mapel_name').value = row.subject_name;
       
        $('#mdl-soal').modal('show');
    });

    const sel = document.querySelector('#n_exam_soal'),
          addSoal = document.getElementById('btn-addSoal');
    

    $('#mdl-soal').on('show.bs.modal', async e => {
        //tblReserve.ajax.reload();

        let exam  = document.getElementById('exam-id').value; 
        let mapel = document.getElementById('mapel-id').value;

        try 
        {
            const xhr = await fetch(`${base_url}api/exam/getReserve?exam=${exam}&kelas=${kelas}&mapel=${mapel}`)
            let datas = await xhr.json();
            let nData = datas.data;
            sel.innerHTML = null;
            const tipe = ['PG', 'Essay', 'B/S'];
            for(let d of nData)
            {
                let opt = document.createElement('option');
                opt.text = `${d.soal_code}_${tipe[parseInt(d.type) - 1]}`;
                opt.value = `${d.soal_id}`;
                sel.add(opt);
            }
            $(sel).selectpicker('val', null);

            let row = [];

            $(sel).on('changed.bs.select', (e, clickedIndex, isSelected, previousValue) => {
                let val = sel.options[clickedIndex].text;
                let split = val.split('_');
    
                let s = datas.data.find(v => v.soal_code == split[0]);
                row = [
                    null,
                    document.getElementById('n_exam_number').value,
                    s.soal_id,
                    s.soal_code,
                    document.getElementById('n_exam_bobot').value
                ];
                
                
            })

            addSoal.addEventListener('click', e => {
                tblSelected.rows.add(row).draw();
            })
        }
        catch(err)
        {

        }
    });

   

})(jQuery);

 

// ambil data mapel
async function getMapel() {
    try 
    {
        const f = await fetch(`${base_url}api/subject/getAll`);
        let datas = await f.json();
        console.log(datas);
        for(let d of datas.data)
        {
            let opt = document.createElement('option');
            opt.text = d.nama_mapel;
            opt.value = d.id_mapel;
            mapel.add(opt);
            // search
            let opt1 = document.createElement('option');
            opt1.text = d.nama_mapel;
            opt1.value = d.id_mapel;
            s_mapel.add(opt1);
        }
    }
    catch(err) 
    {
        console.log(err);
    }

}

async function getCategories() {

    try {
        const f = await fetch(`${base_url}api/exam/getAllCategories`);
        let datas = await f.json();

        for(let d of datas)
        {
            let opt = document.createElement('option');
            opt.text = d.category_name;
            opt.value = d.category_id;
            kategori.add(opt);
        }
    }
    catch(err) {
        console.log(err);
    }
}