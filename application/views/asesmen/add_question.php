<div class="content mt-5">
	<form name="form-add" id="form-add" class="d-flex flex-column">
		<div class="row">
			<div class="col-12 col-lg-4">
				<h4 class="mb-4 text-underline">KETERANGAN</h4>
				<div class="row align-items-top mb-3">
					<div class="col-4">
						<input type="hidden" name="sekolah_id" value="<?= $this->session->userdata('sekolah_id') ?>" />
						<label class="m-0">Kode Soal <span class="text-danger"><strong>*</strong></span></label>
					</div>
					<div class="col-8 mb-3">
						<input type="text" class="form-control form-control-sm" name="a_soal_code" required />
					</div>
					<!-- <div class="col-4">
														<label class="m-0">No. Urut <span class="text-danger"><strong>*</strong></span></label>
													</div>
													<div class="col-8 mb-3">
														<input type="number" class="form-control form-control-sm" name="a_soal_no" min="1" required/>
													</div> -->
					<div class="col-4 pr-0">
						<label class="m-0">Mata Pelajaran <span class="text-danger"><strong>*</strong></span></label>
					</div>
					<div class="col-8 mb-3">
						<select class="form-select form-select-sm col-10" name="a_soal_subject" required>

						</select>
					</div>

					<div class="col-4">
						<label class="m-0">Jenis Soal <span class="text-danger"><strong>*</strong></span></label>
					</div>
					<div class="col-8 mb-3">
						<select class="form-select form-select-sm col-10" name="a_soal_type" required>
							<option value="0">-- Pilih Jenis Soal --</option>
							<option value="1">Pilihan Ganda</option>
							<option value="2">Essay</option>
							<!-- <option value="3">BENAR/SALAH</option> -->
						</select>
					</div>
					<div class="col-4">
						<label class="m-0">Jawaban <span class="text-danger"><strong>*</strong></span></label>
					</div>
					<div class="col-8 mb-3">
						<textarea class="form-control form-control-sm" name="a_soal_answer" rows="8" required></textarea>
						<small>Untuk jawaban pilihan ganda gunakan kunci (contoh: a, b)</small>
					</div>
					<div class="col-4">
						<label class="m-0">File pendukung</label>
					</div>
					<div class="col-8 mb-3">
						<div class="input-group mt-2">
							<input type="file" class="form-control form-control-sm" id="a_soal_file" name="a_soal_file">

						</div>
						<small>Upload file hanya support extensi video/mp4, image/png, image/jpeg, image/jpg, image/webp</small>
					</div>
				</div>

			</div>

			<div class="col">
				<div class="row">
					<div class="col-6">
						<h4 class="mb-4 text-underline">SOAL</h4>
					</div>
					<div class="col-6 text-end">
						<!-- <div class="btn-group btn-group-sm" role="toolbar">
							<button type="button" class="btn btn-info waves-light waves-effect dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="btn-import">
								<i class="fa fa-upload fa-fw"></i>  
							</button>
							<div class="dropdown-menu">
								<a class="dropdown-item text-white" type="button" data-toggle="modal" data-target="#modal-import">Import</a>
								<a class="dropdown-item" href="<? //= html_escape('assets/files/download/soal/soal.xlsx') 
																?>">Download template</a>
							</div>
						</div> -->

						<div class="dropdown">
							<button class="btn btn-info text-white dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" id="btn-import">
								<i class="fa fa-upload fa-fw"></i> Import
							</button>
							<ul class="dropdown-menu">
								<a class="dropdown-item" type="button" id="modal-import-button" data-bs-toggle="modal" data-bs-target="#modal-import">Import</a>
								<a class="dropdown-item" href="<?= $this->config->item('admin_url') . html_escape('assets/files/download/soal/soal_new.xlsx') ?>">Download template</a>
							</ul>
						</div>
					</div>
				</div>


				<div class="row">
					<div class="col-12">
						<div class="d-flex flex-column">
							<label for="detail-soal">Deskripsi Soal <span class="text-danger"><strong>*</strong></span< /label>
									<div id="editor" class="form-control mb-3" style="height: 250px"></div>
									<textarea hidden name="a_soal_detail" class="form-control w-100" id="detail-soal" rows="10"></textarea>
						</div>
					</div>
				</div>

				<div class="row d-none" id="mc">
					<div class="col-12">
						<h4>Jawaban Pilihan Ganda</h4>
						<table id="table-choices" class="table table-sm w-100">
							<thead>
								<tr>
									<th>Pilihan</th>
									<th>Teks</th>
									<th>File</th>
								</tr>
							</thead>
							<tbody>

							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

		<span class="w-100 d-flex flex-nogrow">
			<!-- PRogress bar-->
			<div class="progress w-100 mt-2 d-none" id="import-progress-1">
				<div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
			</div>
			<!-- end PRogress bar-->
		</span>
		<input type="hidden" name="a_id" />
		<input type="hidden" name="xsrf" />
		<span class="mt-3 w-100 d-flex flex-nogrow flex-nowrap justify-content-end">
			<input type="reset" class="btn btn-warning text-white" value="Ulangi">
			<input type="submit" class="btn btn-info text-white ms-2" value="Simpan">
		</span>
	</form>
</div>

<!-- Modal Import -->
<section class="modal fade" tabindex="-1" id="modal-import" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content border-0">
			<div class="modal-header bg-primary">
				<h5 class="modal-title text-capitalize text-light text-shadow">import Data</h5>
				<button type="button" class="btn-close text-light" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form name="import-soal" class="container">
					<div class=" form-group row align-items-center">
						<span class="col-4">
							<label>File Upload</label>
						</span>
						<span class="col-8">
							<div class="custom-file">
								<!-- <label for="file-upload" class="form-label xl_label">Pilih XL File</label> -->
								<input type="file" class="form-control" id="file-upload" name="file-upload" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
							</div>
						</span>
					</div>

					<div class="row mt-5">
						<div class="ml-auto">
							<!-- <button id="preview" class="btn btn-secondary mr-2">Preview</button> -->
							<button disabled class="btn btn-info text-white" id="preview" type="button" data-bs-toggle="modal" data-bs-target="#previewModal" data-bs-dismiss="modal">Preview</button>
						</div>
					</div>

				</form>
				<!-- PRogress bar-->
				<!-- end PRogress bar-->
			</div>
		</div>
	</div>
</section>
<!-- End Modal Import -->

<!-- previewModal -->
<div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel" aria-hidden="false">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header bg-primary text-white">
				<h5 class="modal-title" id="previewModalLabel">Preview Data</h5>
				<button type="button" class="btn-close text-light" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<!-- Table will be inserted here -->
			</div>

			<div class="progress mt-3 d-none" id="import-progress">
				<div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
			</div>

			<div class="modal-footer">
				<!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
				<button id="upload" class="btn btn-info text-white">Upload</button>

			</div>
		</div>
	</div>
</div>

<!-- 
<script src="<?= base_url() ?>assets/js/libs/xlsx/xlsx.js"></script>
<script src="<?= base_url() ?>assets/js/libs/xlsx/jszip.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/jszip.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.8.0/xlsx.js"></script>

<script>
	// file input
	let file = document.getElementById('file-upload');
	const sekolah_id = '<?= $this->session->userdata('sekolah_id') ?>';
	let token = '<?= $this->security->get_csrf_hash() ?>';
	let xlData;

	// button import modal event listener
	$('#modal-import-button').on('click', (e) => {
		$('#file-upload').val('');
		$('#preview').prop('disabled', true);
	});

	// preview soal button event listener
	$('#preview').on('click', function() {
		file = document.getElementById('file-upload');
		console.log(file);

		const reader = new FileReader();

		reader.onload = (event) => {
			const data = new Uint8Array(event.target.result);
			const workbook = XLSX.read(data, {
				type: 'array'
			});
			const sheet_name_list = workbook.SheetNames;
			xlData = XLSX.utils.sheet_to_json(workbook.Sheets[sheet_name_list[0]]);
		};

	});

	// event listener
	file.addEventListener('change', (event) => {
		const file = event.target.files[0];
		const reader = new FileReader();

		reader.onload = (event) => {
			const data = new Uint8Array(event.target.result);
			const workbook = XLSX.read(data, {
				type: 'array'
			});
			const sheet_name_list = workbook.SheetNames;
			xlData = XLSX.utils.sheet_to_json(workbook.Sheets[sheet_name_list[0]]);

			const pertanyaan = function() {
				return xlData.map(row => row['Pertanyaan']);
			}();

			const mapel = function() {
				return xlData.map(row => row['Kategori Pembelajaran']);
			}();

			// validate data "No Soal"
			const noSoal = function() {
				return xlData.map(row => row['No Soal']);
			}();

			// check if all column is not empty
			for (let i = 0; i < xlData.length; i++) {
				// check if No Soal is not empty
				if (xlData[i]['No Soal'] == undefined) {
					let resNoSoal = { success: false, message: 'Kolom "No Soal" kosong pada baris ' + (i + 1) + '. Harap isi semua data.' };
					responseAlert(resNoSoal);
					// reset file input
					$('#file-upload').val('');
					return;
				}

				// check if Pertanyaan & Jawaban Benar is not empty
				// Pertanyaan atau opsi jawaban kosong di baris X. Harap lengkapi semua data.
				if (xlData[i]['Pertanyaan'] == undefined || xlData[i]['Jawaban Benar'] == undefined) {
					let resPertanyaan = { success: false, message: 'Pertanyaan atau opsi jawaban kosong di baris ' + (i + 1) + '. Harap lengkapi semua data.' };
					responseAlert(resPertanyaan);
					// reset file input
					$('#file-upload').val('');
					return;
				}

				// check if Pilihan A, B, C, D is not empty
				if (xlData[i]['Pilihan A'] == undefined || xlData[i]['Pilihan B'] == undefined || xlData[i]['Pilihan C'] == undefined || xlData[i]['Pilihan D'] == undefined) {
					let resPilihan = { success: false, message: 'Kolom "Pilihan A", "Pilihan B", "Pilihan C", dan "Pilihan D" tidak boleh kosong di baris ' + (i + 1) };
					responseAlert(resPilihan);
					// reset file input
					$('#file-upload').val('');
					return;
				}
			}

			// check if noSoal is sequential
			for (let i = 1; i < noSoal.length; i++) {
				if (noSoal[i - 1] == undefined) {
					continue;
				}

				let prev = parseInt(noSoal[i - 1]);

				// Nomor soal tidak berurutan pada baris X.
				if (parseInt(noSoal[i]) !== prev + 1) {
					let resNoSoal = { success: false, message: 'Nomor soal tidak berurutan pada baris ' + (i + 1) + '. Harap urutkan nomor soal.' };
					responseAlert(resNoSoal);
					// reset file input
					$('#file-upload').val('');
					return;
				}
			}

			// check answer is correct only for A, B, C, D
			for (let i = 1; i < xlData.length; i++) {
				const answer = xlData[i]['Jawaban Benar'].toLowerCase();

				// Format jawaban benar tidak valid di baris X. Harap gunakan A, B, C, atau D.
				if (answer !== 'a' && answer !== 'b' && answer !== 'c' && answer !== 'd') {
					alert('Format jawaban benar tidak valid di baris ' + (i + 1) + '. Harap gunakan A, B, C, atau D.');
					// reset file input
					$('#file-upload').val('');
					return;
				}
			}

			// cek di database untuk mapel yang tidak ada
			const cekMapel = async () => {
				const response = await $.ajax({
					url: '<?= base_url('asesmen/cek_mapel') ?>',
					type: 'POST',
					async: false,
					data: {
						mapel: mapel,
						sekolah_id: sekolah_id,
						csrf_token_name: token
					}
				});

				token = response.token;

				if (response.success == false) {
					alert(response.message);
					// reset file input
					$('#file-upload').val('');
					return false;
				} else {
					return true;
				}
			};

			// cek pertanyaan duplicate
			const cekPertanyaanDuplicate = async () => {

				const response = await $.ajax({
					url: '<?= base_url('asesmen/cek_pertanyaan_duplikat') ?>',
					type: 'POST',
					async: false,
					data: {
						pertanyaan: pertanyaan,
						sekolah_id: sekolah_id,
						csrf_token_name: token
					}
				});

				token = response.token;

				if (response.success == false) {
					alert(response.message);
					// reset file input
					$('#file-upload').val('');
					return false;
				} else {
					return true;
				}
			};

			cekMapel().then((result) => {
				if (result) {
					cekPertanyaanDuplicate().then((result) => {
						if (result) {
							$('#preview').prop('disabled', false);
							$('#upload').prop('disabled', false);
						}
					});
				} else {
					$('#preview').prop('disabled', true);
					$('#upload').prop('disabled', true);
				}
			});

		};


		reader.readAsArrayBuffer(file);
	});

	// post data to server
	const postData = async () => {
		// get row data from table
		let table = document.getElementById('preview-table');
		let rows = table.rows;

		data = [];

		for (let i = 1; i < rows.length; i++) {
			let row = rows[i];
			let cells = row.cells;

			data.push({
				'No Soal': cells[0].innerText,
				'Pertanyaan': cells[1].innerText,
				'Jawaban Benar': cells[2].innerText,
				'Pilihan A': cells[3].innerText,
				'Pilihan B': cells[4].innerText,
				'Pilihan C': cells[5].innerText,
				'Pilihan D': cells[6].innerText,
				'Kategori Pembelajaran': cells[7].innerText,
			});
		}

		$.ajax({
			url: '<?= base_url('asesmen/import_soal_pg') ?>',
			type: 'POST',
			contentType: "application/json; charset=utf-8",
			dataType: "json",
			data: JSON.stringify({
				data: data,
				sekolah_id: sekolah_id,
			}),
			xhr: function() {
				var xhr = new window.XMLHttpRequest();
				xhr.upload.addEventListener("progress", function(evt) {
					if (evt.lengthComputable) {
						var percentComplete = evt.loaded / evt.total;
						percentComplete = parseInt(percentComplete * 100);
						$('#import-progress').removeClass('d-none');
						$('#import-progress .progress-bar').css('width', percentComplete + '%');
						$('#import-progress .progress-bar').html(percentComplete + '%');
					}
				}, false);

				setTimeout(() => {
					$('#import-progress').addClass('d-none');
					window.location.reload();
				}, 3000);

				return xhr;
			},
			success: function(response) {
				console.log(response);
			},
		});
	};

	// upload button
	$('#upload').click(function() {
		// uploading data button disabled and show progress animation
		$('#upload').prop('disabled', true);
		$('#upload').html('Uploading...');

		// progress bar
		$('#import-progress').removeClass('d-none');

		postData();
	});

	// preview button
	$('#preview').click(function(e) {
		// create table
		let table = `<table class="table table-bordered" id="preview-table">
				<thead class="bg-info text-white">
					<tr>
						<th>No Soal</th>
						<th>Pertanyaan</th>
						<th>Jawaban Benar</th>
						<th>Pilihan A</th>
						<th>Pilihan B</th>
						<th>Pilihan C</th>
						<th>Pilihan D</th>
						<th>Kategori Pembelajaran</th>
						<th>Aksi</th>
					</tr>
				</thead>
			<tbody>`;

		for (let i = 0; i < xlData.length; i++) {
			table += '<tr>';
			table += '<td>' + xlData[i]['No Soal'] + '</td>';
			table += '<td>' + xlData[i]['Pertanyaan'] + '</td>';
			table += '<td>' + xlData[i]['Jawaban Benar'] + '</td>';
			table += '<td>' + xlData[i]['Pilihan A'] + '</td>';
			table += '<td>' + xlData[i]['Pilihan B'] + '</td>';
			table += '<td>' + xlData[i]['Pilihan C'] + '</td>';
			table += '<td>' + xlData[i]['Pilihan D'] + '</td>';
			table += '<td>' + xlData[i]['Kategori Pembelajaran'] + '</td>';
			table += `<td>
					<button class="btn btn-danger btn-sm text-white" onclick="hapusRow(this)">
						<i class="fa fa-trash"></i>
					</button>
				</td>`;
			table += '</tr>';
		}

		table += '</tbody></table>';

		// show modal
		$('#previewModal .modal-body').html(table);
		$('#previewModal').modal('show');
	});

	function hapusRow(e) {
		const row = e.parentNode.parentNode;
		row.parentNode.removeChild(row);
	}

	function responseAlert(res){
		if(res.success){
			Swal.fire({
				title: 'Berhasil',
				text: res.message,
				icon: 'success',
				confirmButtonText: 'OK'
			});
		}else{
			Swal.fire({
				title: 'Gagal',
				text: res.message,
				icon: 'error',
				confirmButtonText: 'OK'
			});
		}
	}
</script>
