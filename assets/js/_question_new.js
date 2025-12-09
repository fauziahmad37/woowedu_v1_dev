'use strict';
const base_url = document.querySelector('base').href,
      mapel    = document.getElementsByName('a_soal_subject')[0], 
      tipe     = document.getElementsByName('a_soal_type')[0],
      mc       = document.getElementById('mc'),
      btnSave  = document.getElementById('save-soal'),
      frm      = document.forms['form-add'],
      btnAdd   = document.getElementById('btn-add'),
	  sekolahId= document.getElementsByName('sekolah_id')[0].value,
      userToken = document.querySelector('meta[name="x_type_token"]');
      

// check if edit

const hasParams = new URLSearchParams(window.location.search);
const params = Object.fromEntries(hasParams);

(async $ => {
 
    await getMapel();

    (document.getElementsByName('a_soal_code')[0]).value = makeid(10);

    

    /**
     * ========================================
     *          EDITOR
     * ========================================
     */
    // tinymce.init({
    //     selector: '#detail-soal',
    //     plugins: ['image', 'advlist', 'lists'],
    //     toolbar: 'undo redo | blocks |  styleselect | fontsizeselect |' +
    //              'bold italic underline backcolor | alignleft aligncenter ' +
    //              'alignright alignjustify | bullist numlist outdent indent | subscript superscript visualaid styles',
    //     height: 500,
    //     video_template_callback(data) {
    //         return '<video width="' + data.width + '" height="' + data.height + '"' + (data.poster ? ' poster="' + data.poster + '"' : '') + ' controls="controls">\n' + '<source src="' + data.source + '"' + (data.sourcemime ? ' type="' + data.sourcemime + '"' : '') + ' />\n' + (data.altsource ? '<source src="' + data.altsource + '"' + (data.altsourcemime ? ' type="' + data.altsourcemime + '"' : '') + ' />\n' : '') + '</video>';
    //     },
    //     image_advtab: true,
    //     file_picker_types: 'file image',
    //     file_picker_callback: function(callback, value, meta) {
    //         // if file is media (video or audio)

    //             let input = document.getElementsByName('a_soal_file')[0];
    //             //input.type = 'file';
    //             if(meta.filetype == 'image')
    //                 input.accept = "image/png,image/webp,image/jpg,image/jpeg";
                
    //             input.addEventListener('change', e => {
    //                 let file = e.target.files[0];
    //                 const reader = new FileReader();
    //                 reader.addEventListener('load', e => {
    //                     let id = "blobid" + (new Date()).getTime();
    //                     let blobCache =  tinymce.activeEditor.editorUpload.blobCache;
    //                     let base64 = reader.result.split(",")[1];
    //                     let blobInfo = blobCache.create(id, file, base64);
    //                     blobCache.add(blobInfo);
    //                     callback(blobInfo.blobUri(), {name: file.name});
    //                 })
    //                 reader.readAsDataURL(file);
    //             });
    //             input.click();
    //     }
    // });

   
    // $(tipe).selectpicker('val', null);

    $(tipe).on('change.bs.select', e => {
        // check if selection is multiple choice
        const tbody = document.getElementById('table-choices').querySelector('tbody');
        if(e.target.value == 1)
        {
            mc.classList.remove('d-none');
            // const number = ['a', 'b', 'c', 'd', 'e', 'f'];
            const number = ['a', 'b', 'c', 'd'];
            // call tbody
            
            for(let i=0; i < number.length;i++)
            {
                // create row
                const row = tbody.insertRow(i);

                // first cell
                const first = row.insertCell(0);
                first.innerHTML = `<input type="text" class="form-control form-control-sm mb-1" name="pg[${i}][key]" pattern="/[a-d]/" value="${number[i]}" readonly>`;

                // second cell
                const second = row.insertCell(1);
                second.innerHTML = `<input type="text" class="form-control form-control-sm mb-1" name="pg[${i}][value]">`;

                // third cell
                const third = row.insertCell(2);
                third.innerHTML = `<span>
                                        <input type="file" name="pg[${i}][file]" id="pg-${i}-file" accept="image/jpeg,image/png,image/webp" class="d-none">
                                        <label id="label-${i}-file" for="pg-${i}-file" class="btn btn-sm btn-primary text-white">Pilih File</label>
                                    </span>`;
                
                document.getElementById(`pg-${i}-file`).addEventListener('change', e => {
                    let file = e.target.files[0];
                    document.getElementById(`label-${i}-file`).innerText = file.name;
                });

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
     * ========================================
     *          check if is edit
     * ========================================
     */

     if(params.hasOwnProperty('edit') && params.edit == 1)
     {
         console.log('ada');
         const script = JSON.parse(document.getElementById('data-soal').textContent);
         document.querySelector('input[name="a_soal_code"]').setAttribute('readonly', 'readonly');
         frm['a_soal_code'].value = params.kode;
         $(mapel).selectpicker('val', script.subject_id); 
         $(tipe).selectpicker('val', script.type);
         frm['a_soal_answer'].value = script.answer;
         frm['a_soal_detail'].value = script.question;
         frm['a_id'].value = script.soal_id;
         let choiceFiles = (!script.question_file) ? null : script.question_file.split('/');

         document.getElementById('file-label').innerText = typeof(choiceFiles) == 'undefined' || choiceFiles == null ? null : choiceFiles[choiceFiles.length - 1];

         if(script.type === '1')
         {
            const tbody = document.getElementById('table-choices').querySelector('tbody');
             mc.classList.remove('d-none');
             const number = ['a', 'b', 'c', 'd', 'e', 'f'];
             // call tbody
 
             for(let i=0; i < number.length;i++)
             {
                 let n = 'choice_'+number[i];
                 let file = 'choice_'+number[i]+'_file';
                 let val = script[n];
                 // create row
                 const row = tbody.insertRow(i);
 
                 // first cell
                 const first = row.insertCell(0);
                 first.classList.add('w-25');
                 first.innerHTML = `<input type="text" class="form-control form-control-sm mb-1" name="pg[${i}][key]" pattern="/[a-f]/" value="${number[i]}" readonly>`;
 
                 // second cell
                 const second = row.insertCell(1);
                 second.innerHTML = `<input type="text" class="form-control form-control-sm mb-1" name="pg[${i}][value]" value='${val}'>`;
 
                 // third cell
                 let hasFile = script[file] !== null ? script[file] : 'Pilih File';
                 const third = row.insertCell(2);
                 third.innerHTML = `<span>
                                        <input type="file" name="pg[${i}][file]" id="pg-${i}-file" accept="image/jpeg,image/png,image/webp" class="d-none">
                                        <label id="label-${i}-file" for="pg-${i}-file" class="btn btn-sm bg-pink text-white">${hasFile}</label>
                                    </span>`;
                
                document.getElementById(`pg-${i}-file`).addEventListener('change', e => {
                    let file = e.target.files[0];
                    document.getElementById(`label-${i}-file`).innerText = file.name;
                });
 
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

         }
         //$(mapel).selectpicker('val', );
         //$(mapel).selectpicker('val');
     }


	/**
	 * Initialize Quill editor
	 */
	var quill = new Quill('#editor', {
		theme: 'snow'
	});

	$('#form-add').submit(e => {
		$('#detail-soal').val(quill.container.firstChild.innerHTML);
	});

    /**
     * =========================================
     *          reset form create soal
     * =========================================
     */
	frm.addEventListener('reset', function(e){
		// RESET KODE SOAL
		let soalCode = document.getElementsByName('a_soal_code');
        const tbl = document.getElementById('table-choices');

        tbl.querySelector('tbody').innerHTML = null;
        mc.classList.add('d-none');

		setTimeout(function(){
			soalCode[0].value = makeid(10)
		}, 100);

		// RESET QUILLJS
		quill.setText('\n');
	 });

    /**
     *  POST
     * 
     */
    document.getElementsByName('a_soal_file')[0].addEventListener('change', e => {
        let file = e.target.files[0];
        document.getElementById('file-label').innerText = file.name;
    });

    $('#tambahPertanyaanModal').on('hide.bs.modal', e => {

        let soalCode = document.getElementsByName('a_soal_code');
        const tbl = document.getElementById('table-choices');

        frm.reset();

        tbl.querySelector('tbody').innerHTML = null;
        mc.classList.add('d-none');

		setTimeout(function(){
			soalCode[0].value = makeid(10)
		}, 100);

		// RESET QUILLJS
		quill.setText('\n');
    });


    frm.addEventListener('submit', e => {
        e.preventDefault();
        const fData = new FormData(e.target);
        let url = (params.hasOwnProperty('edit') && +params.edit == 1) ? ADMIN_URL + 'api/soal/edit' : ADMIN_URL + 'api/soal/save';
        var prog = document.getElementById('import-progress-1');
         
        // upload 
            $.ajax({
            xhr: function() {
                var xhr = new window.XMLHttpRequest();
                
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
            url: url,
            type: 'POST',
            data: fData,
            headers: {
                'X-Type-Token': userToken.content
            },
            contentType: false,
            processData: false,
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
            success(reslv) {
                //console.log(reslv);
                var prog = document.getElementById('import-progress-1');

                prog.getElementsByClassName('progress-bar')[0].setAttribute('aria-valuenow', 100);
                prog.getElementsByClassName('progress-bar')[0].style.width = '100%';
                prog.getElementsByClassName('progress-bar')[0].innerHTML = '100%';

                var res = reslv;
				if(res.err_status == 'success') {
					Swal.fire({
						icon: res.err_status,
						type: res.err_status,
						title: '<h5 class="text-success text-uppercase">'+res.err_status+'</h5>',
						html: '<span class="text-success">'+res.message+'</span>',
						timer: 1500
					})
					.then(t => {
						$('#tambahPertanyaanModal').modal('hide');
					});

					return
				} 

				Swal.fire({
					icon: res.err_status,
					type: res.err_status,
					title: '<h5 class="text-danger text-uppercase">'+res.err_status+'</h5>',
					html: '<span class="text-danger">'+res.message+'</span>',
					timer: 1500
				})
				.then(t => {
					$('#tambahPertanyaanModal').modal('hide');
				});

				
            },
            error(err) {
                let response = JSON.parse(err.responseText);

                Swal.fire({
                    icon: 'error',
                    type: 'error',
                    title: '<h5 class="text-danger text-uppercase">ERROR</h5>',
                    html: '<span class="text-danger">'+((!response.message) ? 'Data gagal di simpan' : response.message)+ '</span>',
                    timer: 1500
                });
            },
            complete() {
                //table.ajax.reload();
                // prog.addAttribute('hidden');
				frm.reset();
            }
        });

		
    });

    // btnSave.addEventListener('click', e => {
    //     const evt = new Event('submit');
    //     frm.dispatchEvent(evt);
    // });

})(jQuery);

 

// ambil data mapel
async function getMapel() {
    try 
    {
        const f = await fetch(`${ADMIN_URL}api/subject/getAll?sekolah_id=${sekolahId}`, {
            headers: {
                'X-Type-Token': userToken.content
            }
        });
        let datas = await f.json();
        
        mapel.add(new Option("-- Pilih Mata Pelajaran --", 0));

        for(let d of datas.data)
        {
            const opt = new Option(d.nama_mapel, d.id_mapel);
            // opt.text = d.nama_mapel;
            // opt.value = d.id_mapel;
            mapel.add(opt);
            // search
            // let opt1 = document.createElement('option');
            // opt1.text = d.nama_mapel;
            // opt1.value = d.id_mapel;
            // s_mapel.add(opt1);
        }

    }
    catch(err) 
    {
        console.log(err);
    }

}
