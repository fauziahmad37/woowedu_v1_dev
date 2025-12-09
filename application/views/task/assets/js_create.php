<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

<script>
	const classId = `<?= (isset($data['class_id'])) ? $data['class_id'] : '' ?>`,
		formTugas = document.forms['form-tugas'];


	var quillKeterangan = new Quill('#keterangan', {
		theme: 'snow'
	});

	$(document).ready(function() {
		$('#select_class').select2({
			placeholder: '-- Pilih Kelas --'
		});
	});

	//  Get All Kelas
	$.ajax({
		type: "GET",
		url: BASE_URL + 'Task/get_all_kelas',
		data: {},
		dataType: "JSON",
		success: function(response) {
			let data = response.data
			for (let i = 0; i < data.length; i++) {
				if (classId == data[i].class_id) {
					$('#select_class').append(`<option value="${data[i].class_id}" selected>${data[i].class_name}</option>`);
				} else {
					$('#select_class').append(`<option value="${data[i].class_id}">${data[i].class_name}</option>`);
				}
			}
		}
	});

	formTugas.addEventListener('submit', async e => {

		let keterangan = quillKeterangan.container.firstChild.innerHTML.replace(/<[^>]*>?/gm, ''); // untuk validasi data tidak boleh kosong.container.firstChild.innerHTML.replace(/<[^>]*>?/gm, ''); // untuk validasi data tidak boleh kosong 
		let tanggalStart = new Date($('#tanggal_start').val() + ' ' + $('#jamstart').val());
		let tanggalEnd = new Date($('#tanggal_end').val() + ' ' + $('#jamend').val());
		const title = formTugas['title'].value;


		if (tanggalStart > tanggalEnd) {
			Swal.fire({
				type: 'error',
				title: '<h4 class="text-danger"></h4>',
				html: '<span class="text-danger">Waktu awal tugas tidak boleh lebih besar dari waktu akhir!</span>',
				timer: 2000
			});
			e.preventDefault()
			return
		}

		if (title.trim().length == 0) {
			Swal.fire({
				type: 'error',
				title: '<h4 class="text-danger"></h4>',
				html: '<span class="text-danger">Judul tugas wajib terisi !!!</span>',
				timer: 2000
			});
			e.preventDefault()
			return;
		}

		if (quillKeterangan.getLength() <= 1 || quillKeterangan.getText().trim() == '') {
			Swal.fire({
				type: 'error',
				title: '<h4 class="text-danger"></h4>',
				html: '<span class="text-danger">Deskripsi tugas wajib terisi !!!</span>',
				timer: 2000
			});
			e.preventDefault()
			return;
		}

		$('textarea[name="keterangan"]').val(quillKeterangan.container.firstChild.innerHTML);

		// jquery ajax post

		e.preventDefault();
		$.ajax({
			type: "POST",
			// method: 'POST',
			url: BASE_URL + 'task/save',
			data: new FormData(e.target),
			cache: false,
			contentType: false,
			processData: false,
			success: function(response) {
				if (response.success) {
					Swal.fire({
						type: 'success',
						title: '<h4 class="text-success"></h4>',
						html: '<span class="text-success">' + response.message + '</span>',
						timer: 5000
					});
					setTimeout(e => {
						window.location.href = BASE_URL + 'task'
					}, 1500);
				} else {
					Swal.fire({
						type: 'error',
						title: '<h4 class="text-danger"></h4>',
						html: '<span class="text-danger">' + response.message + '</span>',
						timer: 5000
					});

					// set token
					$('input[name="csrf_token_name"]').val(response.token);
				}
			}
		});


		// try 
		// {
		// 	const formData = new FormData(e.target);
		// 	const f = await fetch(`${BASE_URL}/task/save`, {
		// 		method: 'POST',
		// 		body: formData
		// 	});

		// if(!f.ok)
		// {
		// 	Swal.fire({
		// 		type: 'error',
		// 		title: '<h4 class="text-danger"></h4>',
		// 		html: '<span class="text-danger"></span>',
		// 		timer: 2000
		// 	});
		// }
		// } 
		// catch (error) 
		// {

		// }

	});

	// <?php // if (!empty($_SESSION['simpan'])) : ?>

	// 	<?php // if ($_SESSION['simpan']['success'] == true) { 	?>
	// 		Swal.fire({
	// 			type: 'success',
	// 			title: '<h4 class="text-success"></h4>',
	// 			html: '<span class="text-success"><? //= $_SESSION['simpan']['message'] ?></span>',
	// 			timer: 5000
	// 		});
	// 		setTimeout(e => {
	// 			window.location.href = BASE_URL + 'task'
	// 		}, 1500);
	// 	<?php // } else { 	?>
	// 		Swal.fire({
	// 			type: 'error',
	// 			title: '<h4 class="text-danger"></h4>',
	// 			html: '<span class="text-danger"><? //= $_SESSION['simpan']['message'] 	?></span>',
	// 			timer: 5000
	// 		});
	// 	<?php // } 	?>

	// <?php // endif; ?>
</script>
