'use strict';
const form = document.forms['form-add'];
const frmFilter = document.forms['frm-filter']
const embedFile = document.querySelector('#embed-file');
const btnAdd = document.getElementById('create');
const s_jenis_materi = document.getElementsByName('select-mapel')[0];
const a_jenis_materi = document.getElementsByName('a_jenis_materi')[0];
const teacherId = (document.getElementsByName('select-teacher')[0]) ? document.getElementsByName('select-teacher')[0].value : $('input[name="teacher_id"]').val();
const sekolah_id = $('input[name="sekolah_id"]').val();
const classLevelId = $('input[name="class_level_id"]').val();
const classId = $('input[name="class_id"]').val();
const userLevel = $('input[name="user_level"]').val();
// const isMobile = navigator.userAgentData.mobile;

let isUpdate = 0;
const lvlGuru = [1, 6, 10];


/**
 * Data table init
 * @date 9/11/2023 - 3:58:36 PM
 *
 * @type {*}
 */
const table = $('#tbl-materi').DataTable({
    ajax: BASE_URL + 'materi/list_materi_sekolah',
    serverSide: true,
    processing: true,
	bInfo : !isUserUsingMobile(),
	oLanguage: {
		"sUrl": "https://cdn.datatables.net/plug-ins/1.10.9/i18n/Indonesian.json"
	},
	pagingType: "full_numbers",
	language: {
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
            data: 'subject_id',
            visible: false
        },
        {
            data: 'type',
            visible: false
        },
        {
            data: 'materi_file',
            visible: false
        },
		{
            data: 'subject_name'
        },
		{
            data: 'title'
        },
        {
            data: 'teacher_name',
			visible: false
        },
		{
			data: 'null',
			render(data, type, row, meta){
				return (row.edit_at) ? moment(row.edit_at).format('D MMM YYYY, HH:mm') : '';
			}
		},
		{
            data: 'file_size',
			render(data, type, row, meta){
				if(row.file_size < 1000){
					return Math.round(row.file_size) + ' Byte'
				}

				if(row.file_size >= 1000 && row.file_size < 1000000 ){
					return (Math.round(row.file_size/1000)) + ' KB'
				}

				if(row.file_size >= 1000000 && row.file_size < 1000000000 ){
					return (Math.round(row.file_size/1000000)) + ' MB'
				}
			}
        },
		{
			data:null,
			class: 'text-center',
			render(data, row, type, meta){
				let icon;
				if(type.type == 'file'){
					icon = `<a href="./assets/files/materi/${type.materi_file}" class="btn btn-clear text-primary" target="_blank">
								<img src="./assets/themes/space/icons/file-text-fill.svg">
							</a>`;
				}else if(type.type == 'link'){
					icon = `<a href="${type.materi_file}" class="btn btn-clear text-primary" target="_blank">
								<img src="./assets/themes/space/icons/link-45deg.svg">
							</a>`; 
				}else{
					icon = `<a href="./assets/files/materi/${type.materi_file}" class="btn btn-clear text-primary" target="_blank">
								<img src="./assets/themes/space/icons/file-text-fill.svg">
							</a>`;
				}
				let buttonGroup = `<div class="btn-group btn-group-sm float-right">${icon}</div>`; 
				
				return buttonGroup
			}
		},
        {
            data: null,
			visible: (userLevel == 6) ? true : false,
			// visible: (teacherId) ? true : false,
			class: 'text-center',
            render(data, row, type, meta) {
                const level = window.atob(window.localStorage.getItem('level'));
				let view = '';
                if(lvlGuru.includes(+level))
                    view += `<div>
									<button class="btn btn-sm btn-success edit_materi"><img src="./assets/themes/space/icons/pencil-square.svg"></button>
                                	<button class="btn btn-sm btn-danger delete_materi"><i class="bi bi-trash-fill text-white font-size-12"></i></button>
								</div>`;
                
                return view;
            },
            width: '60px'
        }
    ],
});

// table.columns(1).search(teacherId).draw();
// table.columns(2).search(classLevelId).draw();
table.columns(3).search(classId).draw();


/**
 * Get Subjects From api
 * @date 9/11/2023 - 3:40:18 PM
 *
 * @async
 * @returns {Response}
 */
const getSubject = async () => {
    try
    {
        // const url = new URL(`${ADMIN_URL}/api/subject/getAll?sekolah_id=${sekolah_id}`);
        const url = new URL(`${BASE_URL}/Materi/getAllSubject`);
        const f = await fetch(url.href);

        return await f.json();
    }
    catch(err)
    {
        console.log(err);
    }
}

/**
 * Get Subjects From Teacher
 * @date 9/11/2023 - 3:40:18 PM
 *
 * @async
 * @returns {Response}
 */
// const getTeacher = async () => {
//     try
//     {
//         const f = await fetch(`${BASE_URL}teacher/getAll`);

//         return await f.json();
//     }
//     catch(err)
//     {
//         console.log(err);
//     }
// }

const resetForm = () => {
	$('select[name="a_jenis_materi"]').val(null).trigger('change');
    $('select[name="a_materi_subject"]').val(null).trigger('change');
    form.reset();
}

/**
 * Button Add Click Handler
 * @date 9/13/2023 - 10:02:26 AM
 *
 * @param {*} e
 */
const btnAddClick = e => {
    isUpdate = 0;
	// a_jenis_materi.removeAttribute('disabled');
	// remove jenis materi option disabled
	for(let i=0; i < form['a_jenis_materi'].length; i++){
		// remove disabled
		form['a_jenis_materi'][i].removeAttribute('disabled');

		// remove selected
		form['a_jenis_materi'][i].removeAttribute('selected');
	}

	// remove materi subject option disabled
	for(let i=0; i < form['a_materi_subject'].length; i++){
		// remove disabled
		form['a_materi_subject'][i].removeAttribute('disabled');

		// remove selected
		form['a_materi_subject'][i].removeAttribute('selected');
	}

	jenisMaterChange();
    resetForm();
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
	if(type == 'link'){
		document.getElementsByClassName('lampiran')[0].classList.add('d-none');
		document.getElementsByClassName('link')[0].classList.remove('d-none');
	}else if(type == 'file'){
		document.getElementsByClassName('lampiran')[0].classList.remove('d-none');
		document.getElementsByClassName('link')[0].classList.add('d-none');
	} else {
		document.getElementsByClassName('lampiran')[0].classList.add('d-none');
		document.getElementsByClassName('link')[0].classList.add('d-none');
	}
}

/**
 * Button Update Click Handler
 * @date 9/13/2023 - 9:59:51 AM
 *
 * @param {HTMLButtonElement} 
 */
const btnUpdateClick = e => {
	resetForm();
    isUpdate = 1;
    const count = table.row(e.target.parentNode.closest('tr')).count(),
          item = table.row(e.target.parentNode.closest('tr')).data();

    form['a_id'].value = item.materi_id;
    form['a_materi_title'].value = item.title;
    form['a_materi_subject_text'].value = item.subject_name;
    form['a_jenis_materi'].value = item.type;

	// document.getElementsByName('a_jenis_materi')[0].setAttribute('disabled', 'disabled');
    $('select[name="a_materi_subject"]').val(item.subject_id).trigger('change');
    //document.getElementById("preview").src = item.materi_file;

	// set jenis materi option disabled
	for(let i=0; i < form['a_jenis_materi'].length; i++){
		if(form['a_jenis_materi'][i].value == item.type){
			form['a_jenis_materi'][i].setAttribute('selected', 'selected');
			form['a_jenis_materi'][i].removeAttribute('disabled');
		} else {
			form['a_jenis_materi'][i].setAttribute('disabled', 'disabled');
		}
	}

	// set materi subject option disabled
	for(let i=0; i < form['a_materi_subject'].length; i++){
		if(form['a_materi_subject'][i].value == item.subject_id){
			form['a_materi_subject'][i].setAttribute('selected', 'selected');
			form['a_materi_subject'][i].removeAttribute('disabled');
		} else {
			form['a_materi_subject'][i].setAttribute('disabled', 'disabled');
		}
	}

	if (item.type == 'link') $('input[name="a_tautan"]').val(item.materi_file); 
	jenisMaterChange(item.type)

    $('#modal-add').modal('show');
}

/**
 * Input File Handler
 * @date 9/12/2023 - 4:33:04 PM
 *
 * @async
 * @returns {*}
 */
const inputFileHandler = e => {
    if(!e.target.files && !e.target.files[0])
        throw new Error("No File Uploaded");

    const reader = new FileReader();
    // reader.onload = e => embedFile.src = e.target.result;
    reader.readAsDataURL(e.target.files[0]);
}

/**
 * submit form
 * @date 9/11/2023 - 3:51:34 PM
 *
 * @async
 * @param {e: HTMLFormElement} 
 * @returns {*}
 */
const submitMateri = async e => {
    e.preventDefault();
    
    try
    {
        let url = isUpdate == 1 ? new URL(`${BASE_URL}materi/edit`) : new URL(`${BASE_URL}materi/save`);
        const formData = new FormData(e.srcElement);

        const f = await fetch(url.href, {
            method: 'POST',
            body: formData
        });

        const resp = await f.json();

       if(!f.ok)
       {
            Swal.fire({
                type: resp.err_status,
                title: '<h5 class="text-danger text-uppercase">'+resp.err_status+'</h5>',
                html: `<h5 class="text-danger">${resp.message} </h5>`,
                timer: 1500
            });
			
			// SET TOKEN
			$('input[name="csrf_token_name"]').val(resp.token);


            return false;
       } 

       Swal.fire({
            type: resp.err_status,
            title:`<h5 class="text-${resp.err_status} text-uppercase">${resp.err_status}</h5>`,
            html: `<h5 class="text-success">${resp.message} </h5>`,
            timer: 1500
        })
        .then(t => $('#modal-add').modal('hide'));

		// SET TOKEN
		$('input[name="csrf_token_name"]').val(resp.token);

		table.ajax.reload();
    }
    catch(err)
    {
        console.log(err.responseText);
    }
}


/**
 * View Data
 * @date 9/13/2023 - 1:53:24 PM
 *
 * @param {HTMLButtonElement} e
 */
const showData = e => {
    let judul = document.querySelector('#judul'),
        tema = document.querySelector('#tema'),
        subTema = document.querySelector('#sub-tema'),
        note = document.querySelector('#note'),
        fileLink = document.querySelector('#file-link'),
        item = table.row(e.target.parentNode.closest('tr')).data();

    tema.innerText = item.tema_title.replace(' : ', '. ');
    subTema.innerText = item.sub_tema_title.replace(' : ', '. ');
    judul.innerText = item.title.replace(' : ', ': ');
    note.innerHTML = item.note;
    fileLink.href = new URL(`${BASE_URL}/assets/files/materi/` + item.materi_file).href;
   
    $('#modal-show').modal('show');
}

/*
============================
        DELETE
============================
*/

function erase(data, isBulk) {
    return $.ajax({
        url: BASE_URL + 'materi/delete',
        type: 'POST',
        data: JSON.stringify({
			materi_id: data,
			teacher_id: document.getElementsByName('teacher_id')[0].value,
			isBulk: isBulk
		}),
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
                type: resp.success,
                title:`<h5 class="text-success text-uppercase">Sukses!</h5>`,
                html: resp.message
            });
            //csrfToken.content = resp.token;
        },
        error(err) {
            let response = JSON.parse(err.responseText);
            Swal.fire({
                type: response.message,
                title: '<h5 class="text-danger text-uppercase">'+response.message+'</h5>',
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


 // DELETE ONE
$('#tbl-materi tbody').on('click', '.btn.delete_materi', e => {
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
            erase(row.materi_id, 0);
        }
    })
});


/**
 * ************************************
 *          SEARCH / FILTER
 * ************************************
 */

const filter = async e => {
    e.preventDefault();

	let mapel = ($('#select-mapel').val() !== null) ? $('#select-mapel').val() : '';
	// let teacher = ($('#select-teacher').val() !== null) ? $('#select-teacher').val() : '';

    try 
    {
    //    if($('#select-mapel').val())
            table.columns(0).search(mapel).draw();
            table.columns(1).search(teacher).draw();
        
    } 
    catch (err) 
    {
        
    }
}

(async ($, table) => {

    const materi = [...(await getSubject()).data].map(x => ({ id: x.subject_id, text: x.subject_name }));
    // const teacher = [...(await getTeacher()).data].map(x => ({ id: x.teacher_id, text: x.teacher_name }));

    $('#tbl-materi > tbody').on('click', '.btn.view_materi', e => {
        isUpdate = 1;
    });

    // Materi
    $('select[name="select-mapel"]').select2({
        theme: "bootstrap-5",
        data: materi,
        placeholder: 'Pilih Mapel',
        allowClear: true,
		dropdownParent: $("#modal-add"),
		dropdownAutoWidth: true
    });
	$('select[name="select-mapel"]').val(null).trigger('change');

    // teacher
    // $('select[name="select-teacher"]').select2({
    //     theme: "bootstrap-5",
    //     data: teacher,
    //     placeholder: 'Pilih Guru',
    //     allowClear: true
    // });
    // $('select[name="select-teacher"]').val(null).trigger('change');

    // Submit
    form.addEventListener('submit', async e => await submitMateri(e));
    document.querySelector('#save-subject').addEventListener('click', e => {
        const evt = new Event("submit");
        form.dispatchEvent(evt);
    });

    // Input File
    document.querySelector('#videoFile').addEventListener('change', inputFileHandler);
    
	// Button Add
    if(btnAdd)
        btnAdd.addEventListener('click', e => btnAddClick(e));
    
	// Update Click
    $('#tbl-materi > tbody').on('click', '.btn.edit_materi', e => btnUpdateClick(e));
    $('#tbl-materi > tbody').on('click', '.btn.view_materi', e => showData(e));
    //

    // Filter
    $('#select-mapel').select2({
        theme: "bootstrap-5",
        data: materi,
        placeholder: 'Pilih Mapel',
        allowClear: true
    })
    .on('select2:clear', e => {
        table.columns(0).search('').draw();
    });

    $('#select-mapel').val(null).trigger('change');

	if(frmFilter){
		frmFilter.addEventListener('submit', async e => await filter(e));
	}

})(jQuery, table);
