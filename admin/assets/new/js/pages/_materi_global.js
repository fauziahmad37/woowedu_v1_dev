'use strict';
const base_url  = document.querySelector('base').href,
        frm       = document.forms['form-add']
    ;

let materiGlobalIdSelected = null;

(async $ => {

    /**
     *  DATATABLE
     * 
     */
    const table = $('#tbl_materi').DataTable({
        serverSide: true,
        processing: true,
        ajax: {
            url: base_url + 'api/materi/getAllGlobal'
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
                data: 'materi_global_id',
                visible: false
            },					
            {
                data: 'title'
            } , 						
            {
                data: 'updated_at',
				render(data, row, type, meta){
					return moment(data).format('DD MMM YYYY, H:m');
				}
            } ,
			{
				data: 'file_size',
				render(data, type, row, meta) {
					let file_size = Math.round(data/1000);
					let res = (file_size > 1000) ? (file_size/1000)+' MB' : file_size+' KB';
					return res;
				}
				
			},       
            {
                data: null,
				render(data, type, row, meta) {
					var view = `<div class="btn-group btn-group-sm">
                                ${buttonGroupAction(row)}
								<a class="btn btn-danger delete"><i class="fa fa-trash text-white"></i></a>
                            </div>`;
               	 	return view;
				}
            }

        ],
		columnDefs: [
			{
				 "targets": 4,
				 "className": "text-center"
			}
		]
    }).columns(2).search(true).draw().columns.adjust();
	
	// inisialisai table filter pertama kali di load
	// table.on('init', function (e, setting, json){
	// 	table.columns(2).search(true).draw().columns.adjust();
	// });

	/**
	 * clik button kembali 
	 */
	$('#kembali').on('click', function(){
		table.columns(1).search('').draw();
		table.columns(2).search(true).draw();
	});

	/** 
	 * Click Folder 
	*/
	$('#tbl_materi tbody').on('click', '.folder', e => {
		e.preventDefault();
		let row = table.row($(e.target).parents('tr')).data();
		table.columns(1).search(row.materi_global_id).draw();
		table.columns(2).search('').draw();
	});

	function buttonGroupAction(row){
		let button;
		switch(parseInt(row.materi_type)){
			case 1: 
				button = `<a href="${base_url.replace('admin','')+'assets/files/materi/materi-global/'+row.materi_file}" class="btn btn-success" target="_blank"><i class="fa fa-download text-white"></i></a>`;
				break;
			case 2: 
				button = `<a href="${row.materi_url}" class="btn btn-success" target="_blank"><i class="fa fa-link text-white"></i></a>`;
				break;
			case 0:
				button = `<a class="btn btn-success folder"><i class="fa fa-folder text-white"></i></a>`;
		}
		return button
	}

	/**
	 * TREE JS FOLDER INIT
	 */
	$(function () {

		// 6 create an instance when the DOM is ready
		$('.jstree').jstree();
		// 7 bind to events triggered on the tree
		$('.jstree').on("changed.jstree", function (e, data) {
		//   console.log(data.node.li_attr.value);
		});
		// 8 interact with the tree - either way is OK
		$('button').on('click', function () {
		//   $('#jstree').jstree(true).select_node('child_node_1');
		//   $('#jstree').jstree('select_node', 'child_node_1');
		//   $.jstree.reference('#jstree').select_node('child_node_1');
			// let jstree = $('#jstree').jstree().element[0];
			// console.log($("#jstree").jstree("get_selected"));
		});
	});

	/**
	 * BUTTON CREATE FOLDER KLIK
	 */
	$('input[name="create-folder"]').on('click', function(){
		let folderName = $('input[name="folder_name"]').val();
		materiGlobalIdSelected = $(".jstree").jstree("get_selected")[0];
		$.ajax({
			type: "POST",
			url: base_url+"api/materi/create_folder",
			data: {
				materi_global_id: materiGlobalIdSelected,
				folder_name: folderName
			},
			dataType: "JSON",
			success: function (res) {
				
			}
		});
	});

     //select_all
     $('#select_all').on('click', e => {
        if(e.target.checked)
            table.rows().select();
        else
            table.rows().deselect();
    });

    $('#btn-refresh').on('click', e => {
        s_title.value = '';
        table.ajax.reload();
    });


    /**
     *  POST
     */
    let isUpdate = 0;

    // when btn add clicked
    $('btn-add').on('click', e => {
        isUpdate = 0;
        frm.reset();
        frm['a_id'].value = null;
    });

	/**
	 * TREE JS ON CHANGE
	 */
	$('.jstree').on("changed.jstree", function (e, data) {
		// ketika folder di klik lakukan isi data ke input parent_id
		$('input[name="parent_id"]').val(data.selected[0]);
	});

	/**
	 * COMBO BOX TYPE CHANGE
	 */
	$('select[name="a_type"]').on('change', function(a){
		if(a.currentTarget.value == 'link'){
			$('.input-link-group').removeClass('d-none');
			$('.input-file-group').addClass('d-none');
		}else if(a.currentTarget.value == 'file'){
			$('.input-file-group').removeClass('d-none');
			$('.input-link-group').addClass('d-none');
		}
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
            arr.push(data[i].materi_id);
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
    $('#tbl_materi tbody').on('click', '.delete', e => {
		console.log('delete')
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
                    erase(row.materi_global_id, 0);
                }
            })
        });

     function erase(data, isBulk) {
        return $.ajax({
            url: base_url + 'api/materi/delete_global',
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
     *  for filter datatable
     * 
     */

     /**
	   * 
	   * 		SEARCH
	   * 
	   */
	  
        $('#search-button').on('click', e => {
            if(s_title.value !== '' || s_title.value !== null)
                table.columns(2).search(s_title.value).draw(); 
            if(s_mapel.value !== null || s_mapel.value !== '')
                table.columns(5).search(s_mapel.value).draw();
        });
        $('#reset-search').on('click', e => {
            if(s_title.value !== '' || s_title.value !== null) {
                s_title.value = null;
                table.columns(2).search(s_title.value).draw();
            } 
            if(s_mapel.value !== null || s_mapel.value !== '') {
                s_mapel.value = null;
                table.columns(5).search(s_mapel.value).draw();
            }
        });
			
})(jQuery);

