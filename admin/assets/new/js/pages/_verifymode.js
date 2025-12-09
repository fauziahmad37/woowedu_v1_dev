(($, base_url) => {
    'use strict';

    var csrfToken = document.querySelector('meta[name="csrf_token"]');
    var frm = document.forms['save-data'];
    /**
     ==========================================
     *          DATATABLE
     ==========================================
     */

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
									'<button class="btn btn-success edit_data"><i class="fa fa-pen"></i></button>' +
									'<button class="btn btn-warning add_door"><i class="fa fa-door-open"></i></button>' +
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
    // DOOR
    var tblDoor = $("#tbl_door").DataTable({
		processing: true,
        ajax: {
            url: base_url + 'api/verifyMode/getSelectDoor',
            type: 'GET',
            dataSrc: 'data',
            data(d) {
                d.verifymode = document.getElementById('vermod_id').value;
                return d;
            }
        },
        select: {
			style:	'multi', //banyak
			//style:	'os', //satu
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
			{data: 'door_id', visible: false},
			{data: 'door_name'},
			{data: 'alias'},
			{data: 'device_sn', visible: false}
		],
		language:{
			processing:   '<div class="d-flex flex-column align-items-center shadow">'
						+	'<span class="spinner-border text-info"></span>'
						+	'<h4 class="mt-2"><strong>Loading...</strong></h4>'
						+ '</div>'
		}
	});
	// DoorAlt
    var tblDoorAlt = $('#table-alt-door').DataTable({
        processing: true,
        ajax: {
            url: base_url + 'api/verifyMode/getEmptyDoor',
            dataSrc: 'data'
        },
        select: {
			style:	'multi', //banyak
			//style:	'os', //satu
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
			{data: 'door_id', visible: false},
			{data: 'door_name'},
			{data: 'alias'},
			{data: 'device_sn', visible: false}
		],
		language:{
			processing:   '<div class="d-flex flex-column align-items-center shadow">'
						+	'<span class="spinner-border text-info"></span>'
						+	'<h4 class="mt-2"><strong>Loading...</strong></h4>'
						+ '</div>'
		}
    });
	// DoorSel
    var tblDoorSel = $('#table-sel-door').DataTable({
        processing: true,
        ajax: {
            url: base_url + 'api/verifyMode/getSelectDoor',
            type: 'GET',
            dataSrc: 'data',
            data(d) {
                d.verifymode = document.getElementsByName('verify-id')[0].value;
                return d;
            }
        },
        select: {
			style:	'multi', //banyak
			//style:	'os', //satu
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
			{data: 'door_id', visible: false},
			{data: 'door_name'},
			{data: 'alias'},
			{data: 'device_sn', visible: false}
		],
		language:{
			processing:   '<div class="d-flex flex-column align-items-center shadow">'
						+	'<span class="spinner-border text-info"></span>'
						+	'<h4 class="mt-2"><strong>Loading...</strong></h4>'
						+ '</div>'
		}
    });

    // select
     $.ajax({
         url: base_url + 'api/timezone/get_select',
         type: 'GET',
         success(resp) {
            var res = resp;
            var s = document.getElementById('time_zones');
            for(var i=0; i <= res.data.length;i++) {
                var opt = document.createElement('option');
                opt.text = i === 0 ? '---------------------' : res.data[i-1].tz_name;
                opt.value = i === 0 ? '' : res.data[i-1].id;
                s.add(opt);
            }
         }
     });

    $('#time_zones').on('change', e => {
        //e.preventDefault();
        var val = e.target.value;
        if(val === 'null' || val === '') {
            var jam = document.getElementsByClassName('tz-value');
            for(var i=0;i<jam.length;i++) {
                var input = jam[i].getElementsByTagName('input')[0];
                input.value = '00:00';
            }
        } else {
           getTimezone(val);
        }
    });

	// tick
	$('#select_all1').on('click', e => {
		if(e.target.checked)
			tblVerMod.rows().select();
		else
			tblVerMod.rows().deselect();
	});
	$('#select_all2').on('click', e => {
		if(e.target.checked)
			tblDoor.rows().select();
		else
			tblDoor.rows().deselect();
	});

    $('#tbl_verifymode tbody').on('click', '.btn.add_door', e => {
        var data = tblVerMod.row($(e.target).parents('tr')).data();
        document.getElementsByName('verify-id')[0].value = data.ver_id;
        tblDoorAlt.ajax.reload();
        tblDoorSel.ajax.reload();
        $('#assign-verifymode').modal('show');
    });

	$('#tbl_verifymode tbody').on('click', 'tr', (e) => {
		let data = tblVerMod.row(e.target.parentNode.closest('tr')).data();
		if(!$(e.target).parent('tr').hasClass('selected'))
			document.getElementById('vermod_id').value = data.ver_id;
		else
			document.getElementById('vermod_id').value = '';
		tblDoor.ajax.reload();
	});

    /**
     =============================================
     *      FORM
     =============================================
     */

	let isUpdate = 0
	$('#tbl_verifymode tbody').on('click', '.btn.edit_data', e => {
		var nData = tblVerMod.row($(e.target).parents('tr')).data();
		isUpdate = 1;
		frm['ver_id'].value = nData.ver_id;
		frm['rule_name'].value = nData.code;
		frm['time_zones'].value = nData.timezone;
		for(var m in nData) 
		{
			//if(m !== 'created_by' && m !== 'created_by' 
			//  && m !== 'updated_at' && m !== 'updated_at' 
			//  && m !== 'edit_by' && m !== 'edit_time' && m !== 'timezone' 
			//  && m !== 'ver_id' && m !== 'id' && m !== 'tz_name') 
			//{
			var tzm = m.split('_');
			if(tzm[0] == 'sunday' || tzm[0] == 'monday' 
				|| tzm[0] === 'tuesday' || tzm[0] === 'wednesday' 
				|| tzm[0] === 'thursday' || tzm[0] === 'friday' || tzm[0] === 'saturday') 
			{
				var pJoin = [tzm[0], tzm[1], 'doormode'];
				var mJoin = [tzm[0], tzm[1], 'persmode'];
				document.getElementsByName('tz['+tzm[0]+']['+tzm[1]+'][doormode]')[0].value = (nData[pJoin.join('_')]) === null ? '' : nData[pJoin.join('_')];
				document.getElementsByName('tz['+tzm[0]+']['+tzm[1]+'][persmode]')[0].value = (nData[mJoin.join('_')]) === null ? '' : nData[mJoin.join('_')];
			}
		}
		getTimezone(nData.timezone);
		$('#modalAdd').modal('show');
	});

	$('#btn-new').on('click', e => {
		frm.reset();
		isUpdate = 0;
	});

	function getTimezone(val) {
		return  $.ajax({
			url: base_url + 'api/timezone/post_by_name',
			type: 'POST',
			data: 'tz_name='+val,
			beforeSend(xhr) {
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
				Swal.close();
				var res = resp;

				for(var m in res) 
				{
				   if(m !== 'remark' && m !== 'id' && m !== 'tz_name') 
				   {
						var tzm = res[m];
						for(var n in tzm) 
						{
							document.getElementsByName('tz['+m+']['+n+'][start]')[0].value = tzm[n].start;
							document.getElementsByName('tz['+m+']['+n+'][end]')[0].value = tzm[n].end;
						}
				   }
				}
			},
			error(err) {
				Swal.close();
				if(err.status) {
					console.error(err);
				}
			}
		});
	}

     frm.addEventListener('submit', e => {
         e.preventDefault();
         //var selContainer = document.getElementsByClassName('sel-value');
         frm['xsrf_token'].value = csrfToken.content;
		 let smt = {};
		 if(isUpdate === 1) {
			smt = {
				url: base_url + 'api/verifyMode/put',
				type: 'PUT',
				data: JSON.stringify($(e.target).serializeArray()),
				contentType: 'application/json'
			}
		 } else {
			 smt = {
				url: base_url + 'api/verifyMode/post',
				type: 'POST',
				data: $(e.target).serialize(),
				contentType: 'application/x-www-form-urlencoded'
			 };
		 }
         $.ajax({
             url: smt.url,
             type: smt.type,
             data: smt.data,
			 contentType: smt.contentType,
             beforeSend(xhr) {
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
				var res = resp;
				Swal.fire({
					type: res.err_status,
					title:'<h5 class="text-success text-uppercase">'+res.err_status+'</h5>',
					text: res.message
				});
				csrfToken.setAttribute('content', res.token);
			},
			error(err) {
				var response = JSON.parse(err.responseText);
				Swal.fire({
					type: response.err_status,
					title: '<h5 class="text-danger text-uppercase">'+response.err_status+'</h5>',
					html: response.message
				});
				csrfToken.setAttribute('content', res.token);
			},
            complete() {
                tblVerMod.ajax.reload();
            }
         });
     });

     $('#btn-submit-level').on('click', e => {
        var sel = tblDoorSel.rows();
        var count = sel.count(),
            data = sel.data(),
            coll = [];
        for(var i=0;i<count;i++) {
            coll.push(data[i]);
        }
        var vermod = document.getElementsByName('verify-id')[0];
        $.ajax({
            url: base_url + 'api/verifyMode/postPerson',
            type: 'POST',
            data: JSON.stringify({verifymode: vermod.value, door: coll}),
            contentType: 'application/json',
            beforeSend(xhr) {
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
               var res = resp;
               Swal.fire({
                   type: res.err_status,
                   title:'<h5 class="text-success text-uppercase">'+res.err_status+'</h5>',
                   text: res.message
               });
               //csrfToken.setAttribute('content', res.token);
           },
           error(err) {
               var response = JSON.parse(err.responseText);
               Swal.fire({
                   type: response.err_status,
                   title: '<h5 class="text-danger text-uppercase">'+response.err_status+'</h5>',
                   html: response.message
               });
               //csrfToken.setAttribute('content', res.token);
           },
           complete() {
               tblVerMod.ajax.reload();
           }
        });
     });

     // swtich table
     	// PINDAH SATU2 KOLOM KE KANAN
	let selDoorRows = null;
	$('#btn-toRight').on('click',  e => {
		e.preventDefault();
		selDoorRows = tblDoorAlt.rows({selected: true});
		if(selDoorRows.count() == 0) {
			window.alert('Please choose at least one row !!!');
			return false;
		}
		let data = selDoorRows.data();
		tblDoorSel.rows.add(data).draw();
		selDoorRows.remove().draw();
		data = null;	
	});
	// PINDAH SEMUA KE KOLOM KANAN
	$('#btn-toRightAll').on('click', e => {
		e.preventDefault();
		selDoorRows = tblDoorAlt.rows();
		if(selDoorRows.count() == 0) {
			window.alert('No data on table !!!');
			return false;
		}
		let data = selDoorRows.data();
		tblDoorSel.rows.add(data).draw();
		selDoorRows.remove().draw();
	});	
	// PINDAH SATU2 KOLOM KE KIRI
	$('#btn-toLeft').on('click', e => {
		e.preventDefault();
		selDoorRows = tblDoorSel.rows({selected: true});
		if(selDoorRows.count() == 0) {
			window.alert('Please choose at least one row !!!');
			return false;
		}
		let data = selDoorRows.data();
		tblDoorAlt.rows.add(data).draw();
		selDoorRows.remove().draw();
		data = null;
	});
	// PINDAH SEMUA KE KOLOM KIRI
	$('#btn-toLeftAll').on('click', e => {
		e.preventDefault();
		selDoorRows = tblDoorSel.rows();
		if(selDoorRows.count() == 0) {
			window.alert('No data on table !!!');
			return false;
		}
		let data = selDoorRows.data();
		tblDoorAlt.rows.add(data).draw();
		selDoorRows.remove().draw();
	});	

	// SHOW MODAL
	$('#tbl_level tbody').on('click', '.btn.add_door', e => {
		let target = e.target;
		let rowData = table1.row(target.parentNode.closest('tr')).data();
		
		AclForm['level-id'].value = rowData.level_id;
		tblDoorAlt.ajax.reload();
		tblDoorSel.ajax.reload();
		$('#assign-acl').modal('show');
	});

     // refresh table
     $('#btn-refresh1').on('click', e => {
         tblVerMod.ajax.reload();
     });

	

	 /**
	  ===========================================
	  * 		DELETE 
	  ===========================================
	  */

	  $('#btn-erase').on('click', e => {
		var data = tblVerMod.rows({selected: true}),
			arr = [];
		for(var i=0;i<data.count();i++) {
			arr.push(data.data()[i].ver_id);
		}

		Swal.fire({
			title: "Anda Yakin?",
			text: "Data yang dihapus tidak dapat dikembalikan",
			type: "warning",
			showCancelButton: true,
			confirmButtonClass: "btn btn-success mt-2",
			cancelButtonColor: "#f46a6a",
			confirmButtonText: "Ya, Hapus Data",
			cancelButtonText: "Tidak, Batalkan Hapus",
			//closeOnConfirm: false,
			//closeOnCancel: false
		}).then(function(t) {
			if (t.value) {

				$.ajax({
					url: base_url + 'api/verifyMode/delete',
					type: 'DELETE',
					data: JSON.stringify(arr),
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
					},
					complete() {
						tblVerMod.ajax.reload();
						//tblPerson.ajax.reload();
					}

				});
				
			}else if(t.dismiss === Swal.DismissReason.cancel){
				Swal.fire({
					title: "Batal Hapus",
					text: "Data batal dihapus :)",
					showConfirmButton: false,
					timer: 2000,
					type: "success"
				});
			}
		});

	  });

	  // DELETE DOOR

	  $('#delete_all2').on('click', e => {
		var data = tblDoor.rows({selected: true}),
			arr = [];
		console.log(data.data());
		for(var i=0;i<data.count();i++) {
			arr.push(data.data()[i].door_id);
		}
		var vermode = document.getElementById('vermod_id').value;
		if(data.count() === 0) {
			Swal.fire({
				title: "Tidak Ada Data Terpilih!",
				text: "Harap pilih data yang akan dihapus terlebih dahulu.",
				confirmButtonClass: "btn btn-warning mt-2",
				type: "warning"
			});
			return false;
		}
		let reqData = {
			vermod_id:vermode,
			door_id: arr
		};

		Swal.fire({
			title: "Anda Yakin?",
			text: "Data yang dihapus tidak dapat dikembalikan",
			type: "warning",
			showCancelButton: true,
			confirmButtonClass: "btn btn-success mt-2",
			cancelButtonColor: "#f46a6a",
			confirmButtonText: "Ya, Hapus Data",
			cancelButtonText: "Tidak, Batalkan Hapus",
			//closeOnConfirm: false,
			//closeOnCancel: false
		}).then(function(t) {
			if(t.value) {
				
				$.ajax({
					url: base_url + 'api/verifyMode/deleteDoor',
					type: 'DELETE',
					data: JSON.stringify(reqData),
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
					},
					complete() {
						tblVerMod.ajax.reload();
						tblDoor.ajax.reload();
					}

				});
				
			}else if(t.dismiss === Swal.DismissReason.cancel){
				Swal.fire({
					title: "Batal Hapus",
					text: "Data batal dihapus :)",
					showConfirmButton: false,
					timer: 2000,
					type: "success"
				});
			}
		});

	  });

	  /**
	   * 
	   * 		SEARCH
	   * 
	   */
	  var s_al_mode = document.getElementsByName('s_al_mode')[0],
	  	  s_al_timezone = document.getElementsByName('s_al_timezone')[0];
	  $('#search-level').on('click', e => {
		  if(s_al_mode.value !== '' || s_al_mode.value !== null)
		  	tblVerMod.columns(2).search(s_al_mode.value).draw();
		  if(s_al_timezone.value !== null || s_al_timezone.value !== '')
		  	tblVerMod.columns(4).search(s_al_timezone.value).draw();
	  });
	  $('#reset-level').on('click', e => {
			if(s_al_mode.value !== '' || s_al_mode.value !== null) {
				s_al_mode.value = null;
				tblVerMod.columns(2).search(s_al_mode.value).draw();
			}

			if(s_al_timezone.value !== null || s_al_timezone.value !== '') {
				s_al_timezone.value = null;
				tblVerMod.columns(4).search(s_al_timezone.value).draw();
			}
	  });

})(jQuery, document.querySelector('base').href);