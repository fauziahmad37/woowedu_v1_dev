'use strict';
const base_url = document.querySelector('base').href,
      mapel    = document.getElementsByName('a_soal_subject')[0], 
      tipe     = document.getElementsByName('a_soal_type')[0],
      mc       = document.getElementById('mc'),
      btnSave  = document.getElementById('save-soal'),
      frm      = document.forms['form-add'],
			// s_kelas = document.getElementById('s_kelas'),
			
			s_jenis = document.getElementById('s_jenis'),
      btnAdd   = document.getElementById('btn-add'),
	  userToken = document.querySelector('meta[name="x_type_token"]');

let sekolah_id = document.getElementsByName('sekolah_id')[0].value;
let s_mapel = document.getElementById('s_mapel');

(async $ => {

 
    await getKelas();
    await getMapel();
    await getJenis();

    // $(s_kelas).selectpicker('val', null);
    $(s_mapel).selectpicker('val', null);
    $(s_jenis).selectpicker('val', null);
		
    (document.getElementsByName('a_soal_code')[0]).value = makeid(10);

    const table = $('#tbl_soal').DataTable({
        serverSide: true,
        processing: true,
        ajax: {
            url: base_url + 'api/soal/getAll',
			headers: {
                'X-Type-Token': userToken.content
            },
        },
        select: {
			style:	'multi',
			selector: 'tr:not(.no-select) td:first-child'
		},
        columns:[
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
                data: 'soal_id',
                visible: false
            },
            {
                data: 'code'
            },
            {
                data: 'id_kelas',
                visible: false
            },
            {
                data: 'nama_kelas',
            },
            {
                data: 'subject_id',
                visible: false
            },
            {
                data: 'sekolah_id',
				visible: false
            },
            {
                data: 'nama_mapel',
            },
            {
                data: 'type',
                render(data, row, type, meta) {
                    switch(data)
                    {
                        case '1':
                            return 'Pilihan Ganda';
                        case '2':
                            return 'Essay';
                        case '3':
                            return 'Benar Salah';
						case '4':
							return 'Isi Yang Kosong';
						case '5':
							return 'Menjodohkan';
						case '6':
							return 'Seret dan Lepas';
						default:
							return 'Lainnya';
                    }
                }
            },
            {
                data: 'question'
            },
            {
                data: 'answer'
            },
            {
                data: null,
                render(data, row, type, meta) {
                    var view = '<div class="btn-group btn-group-sm float-right">'+
                                    '<button class="btn btn-success edit_soal"><i class="bx bx-edit-alt font-size-12"></i></button>' +
                                    '<button class="btn btn-sm btn-danger delete_soal"><i class="bx bx-trash font-size-12"></i></button>' +
                                '</div>';
                    return view;
                }
            }
        ]
    });

	// table draw column 6 sekolah_id
	table.columns(6).search($('input[name="sekolah_id"]').val()).draw();

    $('#btn-refresh').on('click', e => {
        table.ajax.reload();
    })

    $('#select_all').on('click', e => {
        if(e.target.checked)
            table.rows().select();
        else
            table.rows().deselect();
    });

    $('#tbl_soal tbody').on('click', '.btn.edit_soal', e => {
        let data = table.row(e.target.parentNode.closest('tr')).data();
        window.location.href = base_url + 'soal/forms/?edit=1&kode='+data.code;
    });

    /**
     * ========================================
     *          EDITOR
     * ========================================
     */
    // tinymce.init({
    //     selector: '#detail-soal',
    //     plugins: ['image', 'FMathEditor', 'advlist', 'lists'],
    //     toolbar: 'undo redo | blocks |  styleselect | fontsizeselect |' +
    //              'bold italic underline backcolor | alignleft aligncenter ' +
    //              'alignright alignjustify | bullist numlist outdent indent | FMathEditor',
    //     height: 150,
    //     video_template_callback(data) {
    //         return '<video width="' + data.width + '" height="' + data.height + '"' + (data.poster ? ' poster="' + data.poster + '"' : '') + ' controls="controls">\n' + '<source src="' + data.source + '"' + (data.sourcemime ? ' type="' + data.sourcemime + '"' : '') + ' />\n' + (data.altsource ? '<source src="' + data.altsource + '"' + (data.altsourcemime ? ' type="' + data.altsourcemime + '"' : '') + ' />\n' : '') + '</video>';
    //     },
    //     image_advtab: true,
    //     file_picker_types: 'file image',
    //     file_picker_callback: function(callback, value, meta) {
    //         // if file is media (video or audio)

    //             // let input = document.getElementsByName('a_soal_file')[0];
    //             // //input.type = 'file';
    //             // if(meta.filetype == 'image')
    //             //     input.accept = "image/png,image/webp,image/jpg,image/jpeg";
                
    //             // input.addEventListener('change', e => {
    //             //     let file = e.target.files[0];
    //             //     const reader = new FileReader();
    //             //     reader.addEventListener('load', e => {
    //             //         let id = "blobid" + (new Date()).getTime();
    //             //         let blobCache =  tinymce.activeEditor.editorUpload.blobCache;
    //             //         let base64 = reader.result.split(",")[1];
    //             //         let blobInfo = blobCache.create(id, file, base64);
    //             //         blobCache.add(blobInfo);
    //             //         callback(blobInfo.blobUri(), {name: file.name});
    //             //     })
    //             //     reader.readAsDataURL(file);
    //             // });
    //             // input.click();
    //     }
    // });
    $(mapel).selectpicker('val', null); 
    $(tipe).selectpicker('val', null);

    $(tipe).on('change.bs.select', e => {
        // check if selection is multiple choice
        const tbody = document.getElementById('table-choices').querySelector('tbody');
        if(e.target.value == 1)
        {
            mc.classList.remove('d-none');
            const number = ['a', 'b', 'c', 'd', 'e', 'f'];
            // call tbody
            
            for(let i=0; i < number.length;i++)
            {
                // create row
                const row = tbody.insertRow(i);

                // first cell
                const first = row.insertCell(0);
                first.innerHTML = `<input type="text" class="form-control form-control-sm mb-1" name="pg[][key]" pattern="/[a-f]/" value="${number[i]}" readonly>`;

                // second cell
                const second = row.insertCell(1);
                second.innerHTML = `<input type="text" class="form-control form-control-sm mb-1" name="pg[][value]">`;

                // third cell
                const third = row.insertCell(2);
                third.innerHTML = `<input type="file" name="pg[][file]" accept="image/jpeg,image/png,image/webp">`;

                // // col
                // const col = document.createElement('div');
                // col.classList.add('col-12', 'col-lg-6');
                // // input 
                // const pg = document.createElement('input-choice');
                // pg.setAttribute('name', 'pg');
                // pg.setAttribute('key', number[i]);
                // pg.setAttribute('value', '');
                // col.appendChild(pg);
                // mc.appendChild(col);
            }
            // choice.appendChild(tbody);
            // mc.appendChild(choice);
        }
        else {
            mc.classList.add('d-none');
            tbody.innerHTML = null;
        }
        
    });

    /**
     * =========================================
     *          reset select
     * =========================================
     */
    document.getElementById('reset-subject').addEventListener('click', e => {
        $(mapel).selectpicker('val', null);
    });

 
    document.getElementById('reset-type').addEventListener('click', e => {
        $(tipe).selectpicker('val', null);
        mc.innerHTML = null;
    })

    /**
     *  POST
     * 
     */
 

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
            url: base_url + 'api/soal/save',
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
            arr.push(data[i].soal_id);
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
    $('#tbl_soal tbody').on('click', '.btn.delete_soal', e => {
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
                    erase(row.soal_id, 0);
                }
            })
        });

     function erase(data, isBulk) {
        return $.ajax({
            url: base_url + 'api/soal/delete',
            type: 'DELETE',
            data: JSON.stringify({data: data, isBulk: isBulk}),
			headers: {"X-Type-Token": userToken.content},
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
		s_mapel = document.getElementById('s_mapel');
        // if(s_kelas.value !== '' || s_kelas.value !== null)
        //     table.columns(3).search(s_kelas.value).draw();
        if(s_mapel.value !== null || s_mapel.value !== '')
            table.columns(7).search(s_mapel.value).draw();
        if(s_jenis.value !== null || s_jenis.value !== '')
            table.columns(5).search(s_jenis.value).draw(); 
        
    });

    $('#reset-search').on('click', e => {
        // if(s_kelas.value !== '' || s_kelas.value !== null) {
        //     s_kelas.value = null;
        //     table.columns(3).search(s_kelas.value).draw();
        // }
        if(s_mapel.value !== null || s_mapel.value !== '') {
            s_mapel.value = null;
            table.columns(7).search(s_mapel.value).draw();
        }
        if(s_jenis.value !== null || s_jenis.value !== '') {
            s_jenis.value = null;
            table.columns(5).search(s_jenis.value).draw();
        } 
    });
    // btnSave.addEventListener('click', e => {
    //     const evt = new Event('submit');
    //     frm.dispatchEvent(evt);
    // });

     /**
         * 

         * IMPORT DATA
        */

      let impForm = document.forms['import-soal'];
      let impZip = impForm['zip-upload'],
          impFile = impForm['file-upload'];
  
      let importZip = null,
          importFile = null;
      /*for(let i of impFormat) {
          i.addEventListener('click', e => {
              importFormat = i.value;
          });
          // TODO
          // 1. Buat input file accept mime type by file fformat radio btn
      }
      console.log(importFormat);
      */
      impFile.addEventListener('change', e => {
          e.preventDefault();
          let lbl = document.querySelector('.xl_label');
          lbl.innerHTML = e.target.files[0].name;
          importFile = e.target.files[0];
      });
			
      impZip.addEventListener('change', e => {
          e.preventDefault();
          let lbl = document.querySelector('.zip_label');
          lbl.innerHTML = e.target.files[0].name;
          importZip = e.target.files[0];
      });
			
      impForm.addEventListener('submit', e => {
          e.preventDefault();
          let fdata = new FormData();
          fdata.append('upload-zip', importZip);
          fdata.append('upload-file', importFile);
		  fdata.append('sekolah_id', document.getElementsByName('sekolah_id')[0].value);
          // upload 
          $.ajax({
              xhr: function() {
                 var xhr = new window.XMLHttpRequest();

				// set header token
				// xhr.setRequestHeader('X-Type-Token', userToken.content);

                 var prog = document.getElementById('import-progress');
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
              url: base_url + 'api/soal/import',
              type: 'POST',
              data: fdata,
              contentType: false,
              processData: false,
			  headers: {
				"X-Type-Token": userToken.content
				},
              success(reslv) {
                  //console.log(reslv);
                 var prog = document.getElementById('import-progress');

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
                  table.ajax.reload();
                  
              }
          });
     });

$('#modal-import').on('hidden.bs.modal', function(e) {
 impForm.reset();
 impForm.querySelector('.custom-file-label').innerHTML = 'Pilih File';
 var progress = document.getElementById('import-progress');
 if(progress.hasAttribute('style')) {
     progress.getElementsByClassName('progress-bar')[0].removeAttribute('style');
     progress.getElementsByClassName('progress-bar')[0].setAttribute('aria-valuenow', 0);
     progress.getElementsByClassName('progress-bar')[0].innerHTML = '';
 }

 if(!progress.hasAttribute('hidden'))
     progress.hidden = true;
 
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
 
            // search
            let opt1 = document.createElement('option');
            opt1.text = d.class_name;
            opt1.value = d.class_level_id;
            // s_kelas.add(opt1);
        }
    }
    catch(err)
    {
        console.log(err);
    }
}

// ambil data mapel
async function getMapel() {
    try 
    {
        const f = await fetch(`${base_url}api/subject/getAll?sekolah_id=${sekolah_id}`);
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

// ambil data jenis
async function getJenis() {
    
	let opt1 = document.createElement('option');
	opt1.text = 'Pilihan Ganda';
	opt1.value = 1;
	s_jenis.add(opt1);

	let opt2 = document.createElement('option');
	opt2.text = 'Isian';
	opt2.value = 2;
	s_jenis.add(opt2);	

	let opt3 = document.createElement('option');	
	opt3.text = 'Benar/Salah';
	opt3.value = 3;
	s_jenis.add(opt3);		
						
}
