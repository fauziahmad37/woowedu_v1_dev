(function($, base_url) {
    'use strict';
	var csrfToken = document.querySelector('meta[name="csrf_token"]');

    var tblVerMod = $('#tbl_verifymode').DataTable({
        serverSide: true,
        processing: true,
        ajax: base_url + 'api/verifyMode/getTable',
        select: {
			style:	'multi', //banyak
			//style:	'os', //satu
			selector: 'td:first-child'
		},
		columns: [
			{
				data:null,
				width: '30px',
				className: 'select-checkbox ',
				checkboxes: {
					selectRow: true
				},
				orderable: false,
				render(data, row, type, meta) {
					return '';
				}
			},
			{
				data: 'ver_id',
				visible: false
			},
			{
				data: 'code',
				className: 'align-middle'
			},
			{
				data: 'timezone',
				visible: false
			},
			{
				data: 'tz_name',
				className: 'align-middle'
			},
			{
				data: null,
				render(data, row, type, meta) {
					var btn = '<div class="btn-group btn-group-sm">' +
									'<button class="btn btn-success add_person"><i class="fa fa-user"></i></button>' +
							   '</div>';
					return btn;
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

	// Table Person
	let tblPerson = $('#tbl_person').DataTable({
        serverSide: false,
        processing: true,
        //pageLength: 8,
        //ajax: {
        //    url: base_url + 'api/accessLevel/getLevelPerson',
        //    type: 'GET',
        //    dataSrc: 'data',
        //    data(d) {
        //        d.level_id = document.querySelector('#level-id').value;
        //        return  d;
        //    }
        //},
		select: {
			style:	'multi', //banyak
			// style:	'os', //satu
			selector: 'td:first-child'
		},
        columns: [
            {
                data:null,
				width: '10px',
				className: 'select-checkbox ',
				checkboxes: {
					selectRow: true
				},
				orderable: false,
				render(data, row, type, meta) {
					return '';
				}
            },
            {
                data: 'host_id',
                visible: false
            },
            {
                data: 'host_nip'
            },
            {
                data: 'host_name'
            },
            {
                data: 'department_id',
                visible: false
            },
            {
                data: 'department_name'
            }
        ],
		language:{
			processing:   '<div class="d-flex flex-column align-items-center shadow">'
						+	'<span class="spinner-border text-info"></span>'
						+	'<h4 class="mt-2"><strong>Loading...</strong></h4>'
						+ '</div>',
		}
    }); 

     // TABLE PERSON - ALT
     let tblPersonAlt = $('#tbl-person-alt').DataTable({
        serverSide: false,
        processing: true,
        pageLength: 8,
        ajax: {
            url: base_url + 'api/verifyModePerson/getEmptyPerson',
            dataSrc: 'data'
        },
		select: {
			style:	'multi', //banyak
			// style:	'os', //satu
			selector: 'td:first-child'
		},
        columns: [
            {
                data:null,
				width: '10px',
				className: 'select-checkbox ',
				checkboxes: {
					selectRow: true
				},
				orderable: false,
				render(data, row, type, meta) {
					return '';
				}
            },
            {
                data: 'host_id',
                visible: false
            },
            {
                data: 'host_nip'
            },
            {
                data: 'host_name'
            },
            {
                data: 'department_id',
                visible: false
            },
            {
                data: 'department_name'
            }
        ],
		language:{
			processing:   '<div class="d-flex flex-column align-items-center shadow">'
						+	'<span class="spinner-border text-info"></span>'
						+	'<h4 class="mt-2"><strong>Loading...</strong></h4>'
						+ '</div>',
		}
        
    });
    // TABLE PERSON - SELECTED
    let tblPersonSel = $('#tbl-person-select').DataTable({
        serverSide: false,
        processing: true,
        //pageLength: 8,
        ajax: {
            url: base_url + 'api/accessLevel/getLevelPerson',
            type: 'GET',
            dataSrc: 'data',
            data(d) {
                d.level_id = document.querySelector('#vermod-id').value;
                return  d;
            }
        },
		select: {
			style:	'multi', //banyak
			// style:	'os', //satu
			selector: 'td:first-child'
		},
        columns: [
            {
                data:null,
				width: '10px',
				className: 'select-checkbox ',
				checkboxes: {
					selectRow: true
				},
				orderable: false,
				render(data, row, type, meta) {
					return '';
				}
            },
            {
                data: 'host_id',
                visible: false
            },
            {
                data: 'host_nip'
            },
            {
                data: 'host_name'
            },
            {
                data: 'department_id',
                visible: false
            },
            {
                data: 'department_name'
            }
        ],
		language:{
			processing:   '<div class="d-flex flex-column align-items-center shadow">'
						+	'<span class="spinner-border text-info"></span>'
						+	'<h4 class="mt-2"><strong>Loading...</strong></h4>'
						+ '</div>',
		}
    }); 

	 /**
     ==============================
     *      LIST TABLE
     ==============================
     */
    	// PINDAH SATU2 KOLOM KE KANAN
		let selDoorRows = null;
		$('#btn-toRight').on('click',  e => {
			e.preventDefault();
			selDoorRows = tblPersonAlt.rows({selected: true});
			console.log(selDoorRows);
			if(selDoorRows.count() == 0) {
				window.alert('Please choose at least one row !!!');
				return false;
			}
			let data = selDoorRows.data();
			tblPersonSel.rows.add(data).draw();
			selDoorRows.remove().draw();
			data = null;	
		});
		// PINDAH SEMUA KE KOLOM KANAN
		$('#btn-toRightAll').on('click', e => {
			e.preventDefault();
			selDoorRows = tblPersonAlt.rows();
			if(selDoorRows.count() == 0) {
				window.alert('No data on table !!!');
				return false;
			}
			let data = selDoorRows.data();
			tblPersonSel.rows.add(data).draw();
			selDoorRows.remove().draw();
		});	
		// PINDAH SATU2 KOLOM KE KIRI
		$('#btn-toLeft').on('click', e => {
			e.preventDefault();
			selDoorRows = tblPersonSel.rows({selected: true});
			if(selDoorRows.count() == 0) {
				window.alert('Please choose at least one row !!!');
				return false;
			}
			let data = selDoorRows.data();
			tblPersonAlt.rows.add(data).draw();
			selDoorRows.remove().draw();
			data = null;
		});
		// PINDAH SEMUA KE KOLOM KIRI
		$('#btn-toLeftAll').on('click', e => {
			e.preventDefault();
			selDoorRows = tblPersonSel.rows();
			if(selDoorRows.count() == 0) {
				window.alert('No data on table !!!');
				return false;
			}
			let data = selDoorRows.data();
			tblPersonAlt.rows.add(data).draw();
			selDoorRows.remove().draw();
		});	


    $('#tbl_verifymode tbody').on('click', '.btn.add_person', e => {
        let target = e.target;
		var rule = tblVerMod.row(target.parentNode.closest('tr'));
        document.getElementById('vermod-id').value = rule.data().id;
        $('#add-person-verifymode').modal('show');
    });

	$('#saveAll').on('click', e => {
		var rec = tblPersonSel.rows();
		var count = rec.count(),
			data = rec.data();
		var arr = [];
		for(var i=0;i<rec.count();i++) 
		{
			arr.push(data[i].host_id);
		}
		console.log(arr);
		$.ajax({
			url: base_url + 'api/verifyModePerson/postPerson',
			type: 'POST',
			data: JSON.stringify({vermod_id: document.getElementById('vermod-id').value, person_id: arr, xsrf_token: csrfToken.content}),
			contentType: 'application/json',
			beforeSend: (xhr, obj) => {
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
					title: resp.err_status,
					text: resp.message,
					showConfirmButton: false,
					timer: 2000,
					type: resp.err_status
				});
				//csrfToken.content = resp.token
			},
			error(err) {
				let response = JSON.parse(err.responseText);
				Swal.fire({
					title: response.err_status,
					text: response.message,
					showConfirmButton: false,
					timer: 2000,
					type: response.err_status
				});
				//console.log(response);
				//if(response.hasOwnProperty('token'))
				//    csrfToken.content = response.token;
			}
		});
	});

})(jQuery, document.querySelector('base').href);