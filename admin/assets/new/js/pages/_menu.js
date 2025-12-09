(($, base_url) => {
    'use strict';

    /**
     ===========================================
     *          VIEW DATA
     ===========================================
     */

     // DATATABLE INIT
    var table = $('#tbl-menuLevel').DataTable({
        serverSide: true,
        processing: true,
        ajax: {
            url: base_url + 'menu/getTable',
            type: 'GET'
        },
        select: {
			style:	'multi',
			selector: 'tr:not(.no-select) td:first-child'
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
            data: 'id',
            visible: false
        }, 
        {
            data: 'name'
        },
        {
            data: 'menu',
            visible: false
        },
        {
            data: 'id',
            width: '25',
            render(data, row, type, meta) {
                let btn = data === '1' ? null : '<div class="btn-group btn-group-sm float-right" role="group" aria-label="Basic example">'
                            + '<button class="btn btn-success edit_data" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit"><i class="bx bx-edit-alt font-size-12"></i></button>'
                            + '<button class="btn btn-danger delete_data" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit"><i class="fa fa-trash font-size-12"></i></button>'
                            '</div>';
                return btn;
            }
        }],
        createdRow: function(row, data, index) {
            if(data.id === '1') {
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

    // SEELCT ALL
    $('#select_all').on('click', e => {
        if(e.target.checked)
            table.rows('tr:not(.no-select)').select();
        else
            table.rows().deselect();
    });

    table.on('user-select', function(e, dt, type, cell, originalEvent) {
        console.log(originalEvent);
    });

    //  JSTREE INIT
    $('#menu-tree').jstree({
        core: {
            multiple: true,
            data: { 
                url: base_url + 'menu/getMenuTree',
                data(node) {
                    return {id : node.id};
                }
            }
        },
        checkbox: {
            'deselect_all': true,
            'three_state' : true, 
        },
        plugins: ['checkbox']
    })
    .bind("loaded.jstree", function(event, data) {
        $(this).jstree("open_all");
      });

    /**
     ===========================================
     *          SUBMIT FORM
     ===========================================
     */
   

    var is_update = 0,
        form = document.forms['form-add'];

    $('#btn-new').on('click', e => {
        is_update = 0;
    });

    // EDIT
    $('table tbody').on('click', '.btn.edit_data', e => {
        is_update = 1
        var data = table.row($(e.target).parents('tr')).data();
        form['uid'].value = data.id;
        form['level-name'].value = data.name;
        $('#menu-tree').jstree('select_node', data.menu.split(','));
        $('#modal-add').modal('show');
    })

     $('#save-level').on('click', e => {
         var menu = $('#menu-tree').jstree('get_selected');
         
         let datas = {
            id: form['uid'].value,
            name: form['level-name'].value,
            menu_id: menu
         };
         let conf = {};
         if(is_update === 0){
            conf.url = base_url + 'menu/post';
            conf.type = 'POST';
         } else {
            conf.url = base_url + 'menu/put';
            conf.type = 'PUT';
         }

         $.ajax({
             url: conf.url,
             type: conf.type,
             data: JSON.stringify(datas),
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
     });
    /**
     ===========================================
     *          DELETE DATA
     ===========================================
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
            arr.push(data[i].id);
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
    $('table tbody').on('click', '.btn.delete_data', e => {
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
                    erase(row.id, 0);
                }
            })
        });

     function erase(data, isBulk) {
        return $.ajax({
            url: base_url + '/menu/delete',
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

     var s_levelname  = document.getElementById('s_level_name');

    $('#btn-search').on('click', e => {
        if(s_levelname.value !== '' || s_levelname.value !== null)
            table.columns(2).search(s_levelname.value).draw();
    });

    $('#reset-search').on('click', e => {
        var form = document.forms['form-search'];
        form.reset();
        table.columns(2).search(s_levelname.value).draw();
    }); 

})(jQuery, document.querySelector('base').href);