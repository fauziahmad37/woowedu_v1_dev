// ======================================= INISIALISASI TABEL =======================================
$(document).ready(function () {
	let table = new DataTable('#myTable');
	$('.dt-layout-cell.dt-start').addClass('d-none');
});

// ======================================= SIMPAN NILAI =======================================
$("#save-nilai").click(function(){
	var _ts_id = $("#ts_id").val();
	var _task_nilai = $("#task_nilai").val();
	var _task_comment_nilai = $("#task_comment_nilai").val();
	
	$.ajax({
		type: "POST",
		url: BASE_URL+"task/save_nilai",
		data: {
			ts_id: _ts_id, 
			task_nilai: _task_nilai, 
			task_comment_nilai: _task_comment_nilai,
			csrf_token_name: $('input[name="csrf_token_name"]').val(),
		},
		dataType: "JSON",
		success: function (response) {
			Swal.fire({
				icon: 'success',
				title: '<h4 class="text-success"></h4>',
				html: '<span class="text-success">Berhasil</span>',
				timer: 5000
			});

			setInterval(function(){
				window.location.reload();
			}, 2000);
		}
	});
});

// ======================================= EDIT NILAI =======================================
$('#myTable').on('click', 'tbody .input-nilai', e => {
	let row = e.target.closest('tr');
	let nilai 	= row.children[5].innerText;
	let comment = row.children[6].innerText;
	let ts_id 	= e.currentTarget.attributes.data.value;

	$('#task_nilai').val(nilai);
	$('#task_comment_nilai').val(comment);

	$("#ts_id").val(ts_id);
	$("#taskcomment").modal('show');
});

// ======================================= CLOSE MODAL NILAI =======================================
$("#close-nilai").click(function(){
	$("#taskcomment").modal('hide');
});

// ======================================= DELETE TUGAS =======================================
$('.delete-task').on('click', () => {
	let id = $('#task_id').val();
	
	const swalWithBootstrapButtons = Swal.mixin({
		customClass: {
			cancelButton: "btn btn-light border-secondary-subtle me-2",
		  	confirmButton: "btn btn-danger text-white",
		},
		buttonsStyling: false
	  });

	swalWithBootstrapButtons.fire({
        title: "Hapus Tugas",
        text: "Data yang dihapus tidak dapat dikembalikan",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, Hapus Data",
        cancelButtonText: "Tidak, Batalkan Hapus",
		reverseButtons: true,
        closeOnConfirm: false,
        closeOnCancel: false
    }).then(t => {
		
        if(t.value) {
            $.ajax({
				type: "DELETE",
				url: BASE_URL+"task/delete/"+id,
				success: function (response) {
					if(response.success){
						Swal.fire({
							icon: 'success',
							title: '<h4 class="text-success"></h4>',
							html: `<span class="text-success">${response.message}</span>`,
							timer: 5000
						});
						setTimeout(()=>{
							window.location.href = BASE_URL+'task';
						}, 3000);
					}else{
						Swal.fire({
							icon: 'error',
							title: '<h4 class="text-danger"></h4>',
							html: `<span class="text-danger">${response.message}</span>`,
							timer: 5000
						});
					}
				}
			});
        }
    })
});

