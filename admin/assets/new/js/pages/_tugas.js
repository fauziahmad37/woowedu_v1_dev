'use strict';
const base_url = document.querySelector('base').href;
const frm = document.forms['form-add'],
      materi = document.getElementsByName('a_tugas_materi')[0],
      kelas = document.getElementsByName('a_tugas_class')[0],
      guru = document.getElementsByName('a_tugas_guru')[0],
      s_materi = document.getElementById('s_materi'),
      s_kelas = document.getElementById('s_kelas'),
      s_guru = document.getElementById('s_guru'),
      s_code = document.getElementById('s_code'),
      btnAdd = document.getElementById('btn-add');


(async ($) => {

    await getKelas();
    await getMateri();
    await getTeacher();

    $(materi).selectpicker('val', null);
    $(kelas).selectpicker('val', null);
    $(guru).selectpicker('val', null);
    $(s_materi).selectpicker('val', null);
    $(s_kelas).selectpicker('val', null);
    $(s_guru).selectpicker('val', null);

    // data table
    const table = $('#tbl_tugas').DataTable({
        serverSide: true,
        processing: true,
        ajax: {
            url: `${base_url}api/tugas/getAll`,
            type: 'GET'
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
                data: 'task_id',
                visible: false
            },
            {
                data: 'code'
            },
            {
                data: 'materi_id',
                visible: false
            },
            {
                data: 'title'
            },
            {
                data: 'class_id',
                visible: false
            },
            {
                data: 'class_name'
            },
            {
                data: 'teacher_id',
                visible: false
            },
            {
                data: 'teacher_name'
            },
            {
                data: 'available_date'
            },
            {
                data: 'due_date'
            },
            {
                data: null,
                render(data, row, type, meta) {
                    var view = '<div class="btn-group btn-group-sm float-right">' +
                                    '<button class="btn btn-success edit_tugas"><i class="bx bx-edit-alt font-size-12"></i></button>' +
                                    '<button class="btn btn-sm btn-danger delete_tugas"><i class="bx bx-trash font-size-12"></i></button>' +
                                '</div>';
                    return view;
                }
            }
            
        ]
    });

    $('#btn-refresh').on('click', e => {
        table.ajax.reload();
    })

    $('#select_all').on('click', e => {
        if(e.target.checked)
            table.rows().select();
        else
            table.rows().deselect();
    });


    tinymce.init({
        selector: '#detail-tugas',
        height: 240
    });

    document.querySelector('input[name="a_tugas_file"]').addEventListener('change', e => {
        let file = e.target.files[0];
        document.querySelector('label[for="a_tugas_file"]').innerText = file.name;
    })

    // reset select
    document.getElementById('reset-materi').addEventListener('click', e => {
        $(materi).selectpicker('val', null);
    });

    document.getElementById('reset-class').addEventListener('click', e => {
        $(kelas).selectpicker('val', null);
    });

    document.getElementById('reset-guru').addEventListener('click', e => {
        $(guru).selectpicker('val', null);
    });

    // datepicker
    $('input[name="a_tugas_periode"]').daterangepicker({
        timePicker: true,
        timePicker24Hour: true,
        minDate: new Date(),
        drops: 'auto',
        locale: {
            format: "YYYY-MM-DD HH:mm:ss"
        }
    }, (startDate, endDate, label) => {
       
        const start = startDate.format("YYYY-MM-DD HH:mm:ss"),
              end   = endDate.format("YYYY-MM-DD HH:mm:ss");

        frm['a_tugas_start'].value = start;
        frm['a_tugas_end'].value = end;
    });
    
    $('input[name="a_tugas_periode"]').on('apply.daterangepicker', (e, picker) => {
        const start = picker.startDate.format("YYYY-MM-DD HH:mm:ss"),
        end   = picker.endDate.format("YYYY-MM-DD HH:mm:ss");

        frm['a_tugas_start'].value = start;
        frm['a_tugas_end'].value = end;
    })
    
    // text for selected option

    $(materi).on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) {
        e.stopPropagation();
        e.preventDefault();
        const el = e.target;
        if(clickedIndex != null)
            document.querySelector('input[name="a_tugas_materi_text"]').value = (el.options[clickedIndex]).innerText;
    });
      
    $(kelas).on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) {
        e.stopPropagation();
        e.preventDefault();
        const el = e.target;
        if(clickedIndex != null)
            document.querySelector('input[name="a_tugas_class_text"]').value = (el.options[clickedIndex]).innerText;
    });

    $(guru).on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) {
        e.stopPropagation();
        e.preventDefault();
        const el = e.target;
        if(clickedIndex != null)
            document.querySelector('input[name="a_tugas_guru_text"]').value = (el.options[clickedIndex]).innerText;
    });

    /**
     * ============================================================================
     *                          POST DATA
     * ============================================================================
     */

    let isUpdate = 0;

    btnAdd.addEventListener('click', e => {
        isUpdate = 0;
        frm.reset();
        $(materi).selectpicker('val', null);
        $(kelas).selectpicker('val', null);
        $(guru).selectpicker('val', null);
        document.querySelector('label[for="a_tugas_file"]').innerText = null;
        (document.getElementsByName('a_tugas_code')[0]).value = makeid(10);
    });

    $('#tbl_tugas tbody').on('click', '.btn.edit_tugas', e => {
        isUpdate = 1;
        const row = table.row(e.target.parentNode.closest('tr')).data();
        frm['a_id'].value = row.task_id;
        frm['a_tugas_code'].value = row.code;

        $(materi).selectpicker('val', row.materi_id);
        $(kelas).selectpicker('val', row.class_id);
        $(guru).selectpicker('val', row.teacher_id);
        tinymce.get('detail-tugas').setContent(row.note);
        frm['a_tugas_start'].value = row.available_date;
        frm['a_tugas_end'].value = row.due_date;
        frm['a_tugas_periode'].value = row.available_date + ' - ' + row.due_date;

        // tugas
        frm['a_tugas_materi_text'].value = row.title;
        frm['a_tugas_class_text'].value = row.class_name;
        frm['a_tugas_guru_text'].value = row.teacher_name;

        document.querySelector('label[for="a_tugas_file"]').innerText = row.task_file;
        $('#modal-add').modal('show');
    });


    // submit dispatcher
    document.getElementById('save-tugas').addEventListener('click', e => {
        const evt = new Event('submit');
        frm.dispatchEvent(evt);
    });


    frm.addEventListener('submit', e => {
        e.preventDefault();
        const fData = new FormData(e.target);
        
         // upload 
            $.ajax({
            xhr: function() {
                var xhr = new window.XMLHttpRequest();
                var prog = document.getElementById('import-progress-1');
                xhr.upload.addEventListener('progress', e1 => {
                    if(e1.lengthComputable) {
                        prog.removeAttribute('hidden');
                        var completed = (e1.loaded === e1.total) ? 90 : Math.round((e1.loaded / e1.total) * 100);
                        prog.getElementsByClassName('progress-bar')[0].setAttribute('aria-valuenow', completed);
                        prog.getElementsByClassName('progress-bar')[0].style.width = completed + '%';
                        prog.getElementsByClassName('progress-bar')[0].innerHTML = completed + '%';
                    }
                }, false);
                xhr.addEventListener('progress', e2 => {
                    if(e2.lengthComputable) {
                        prog.removeAttribute('hidden');
                        var completed = (e2.loaded === e2.total) ? 90 : Math.round((e2.loaded / e2.total) * 100);
                        prog.getElementsByClassName('progress-bar')[0].setAttribute('aria-valuenow', completed);
                        prog.getElementsByClassName('progress-bar')[0].style.width = completed + '%';
                        prog.getElementsByClassName('progress-bar')[0].innerHTML = completed + '%';
                    }
                }, false);

                return xhr;
            },
            url: isUpdate == 1 ? base_url + 'api/tugas/edit' : base_url + 'api/tugas/save',
            type: 'POST',
            data: fData,
            contentType: false,
            processData: false,
            success(reslv) {
                //console.log(reslv);
                var prog = document.getElementById('import-progress-1');

                prog.getElementsByClassName('progress-bar')[0].setAttribute('aria-valuenow', 100);
                prog.getElementsByClassName('progress-bar')[0].style.width = '100%';
                prog.getElementsByClassName('progress-bar')[0].innerHTML = '100%';

                var res = reslv;
                Swal.fire({
                    type: res.err_status,
                    title: '<h5 class="text-success text-uppercase">'+res.err_status+'</h5>',
                    html: res.message
                });
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
                var prog = document.getElementById('import-progress-1');
                prog.setAttribute('hidden', 'hidden');
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
            arr.push(data[i].task_id);
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
    $('#tbl_materi tbody').on('click', '.btn.delete_subject', e => {
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
                    erase(row.task_id, 0);
                }
            })
    });

     function erase(data, isBulk) {
        return $.ajax({
            url: base_url + 'api/tugas/delete',
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
     *               FILTERING
     ===================================================
     */

     $('#search-button').on('click', e => {
        if(s_code.value !== '' || s_code.value !== null)
            table.columns(2).search(s_code.value).draw();
        if(s_materi.value !== null || s_materi.value !== '')
            table.columns(3).search(s_materi.value).draw();
        if(s_kelas.value !== null || s_kelas.value !== '')
            table.columns(5).search(s_kelas.value).draw();
        if(s_guru.value !== null || s_guru.value !== '')
            table.columns(7).search(s_guru.value).draw();
        
    });

    $('#reset-search').on('click', e => {
        if(s_code.value !== '' || s_code.value !== null) {
            s_code.value = null;
            table.columns(2).search(s_code.value).draw();
        }
        if(s_materi.value !== null || s_materi.value !== '') {
            s_materi.value = null;
            table.columns(3).search(s_materi.value).draw();
        }
        if(s_guru.value !== null || s_guru.value !== '') {
            s_guru.value = null;
            table.columns(7).search(s_guru.value).draw();
        }
        if(s_kelas.value !== null || s_kelas.value !== '') {
            s_kelas.value = null;
            table.columns(5).search(s_kelas.value).draw();
        }
    });

})(jQuery);


// ambil data kels
async function getKelas() {
    try 
    {
        const f = await fetch(`${base_url}api/kelas/get_all`);
        let datas = await f.json();
        
        for(let d of datas.data)
        {
            let opt = document.createElement('option');
            opt.text = d.class_name;
            opt.value = d.class_id;
            kelas.add(opt);
            // search
            let opt1 = document.createElement('option');
            opt1.text = d.class_name;
            opt1.value = d.class_id;
            s_kelas.add(opt1);
        }
    }
    catch(err)
    {
        console.log(err);
    }
}

// ambil data mapel
async function getMateri() {
    try 
    {
        const f = await fetch(`${base_url}api/materi/getAll`);
        let datas = await f.json();
        console.log(datas);
        for(let d of datas.data)
        {
            let opt = document.createElement('option');
            opt.text = d.title;
            opt.value = d.materi_id;
            materi.add(opt);
            // search
            let opt1 = document.createElement('option');
            opt1.text = d.title;
            opt1.value = d.materi_id;
            s_materi.add(opt1);
        }
    }
    catch(err) 
    {
        console.log(err);
    }
}

// ambil data mapel
async function getTeacher() {
    try 
    {
        let teacher  = await fetch(`${base_url}api/teacher/get_all`);
        let getDatas = await teacher.json();
        guru.innerHTML = null;
        for(let item of getDatas.data) 
        {
            let opt = document.createElement('option');
            opt.value = item.teacher_id;
            opt.text  = item.teacher_name;
            guru.add(opt);

              // search
              let opt1 = document.createElement('option');
              opt1.value = item.teacher_id;
              opt1.text  = item.teacher_name;
              s_guru.add(opt1);
        }
    }
    catch(err) 
    {
        console.log(err);
    }
}