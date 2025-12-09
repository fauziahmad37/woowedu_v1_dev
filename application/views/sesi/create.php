<link href="<?=base_url()?>assets/libs/quilljs/quill.snow.css" rel="stylesheet">

<style>
	.select2-container .select2-selection--single{
		border-style: solid;
		border-width: 1px;
		border-color: #dee2e6;
		border-radius: 5px;
		height: 100%;
		min-height: 35px;
		padding-top: 7px;
	}

	@media screen and (max-width: 425px) {
		.button-save{
			text-align: end;
		}
	}
</style>

<?php 
	$user_level = $this->session->userdata('user_level');
	$user_level = ($user_level == 3) ? true : false;
?>

<section class="explore-section section-padding" id="section_2">
	<div class="container">

		<div class="col-12 text-center">
			<h4 class="mb-4">Buat Jadwal Sesi Baru</h4>
		</div>

		<div class="col-12">
			<form id="form-create-news" action="">
	
				<input type="hidden" id="id" name="id" value="<?=isset($data['sesi_id']) ? $data['sesi_id'] : '' ?>">
				<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>">

				<div class="mb-3 col-lg-8 col-md-10 col-sm-12 col-xs-12">
					<!-- <div class="mb-4 col-lg-12 col-md-12 col-sm-12 col-xs-12"> -->
						<label for="kelas" class="form-label">Kelas <span class="text-danger">*</span></label>
						<select required class="form-control" name="kelas" id="kelas">
							<option value="">-- Pilih Kelas --</option>
						</select>
					<!-- </div> -->
				</div>
	

				<div class="mb-3 col-lg-8 col-md-10 col-sm-12 col-xs-12">
					<div class="row">
						<div class="mb-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">
							<label for="title" class="form-label">Tanggal <span class="text-danger">*</span></label>
							<input disabled required type="date"  class="form-control" id="tanggal" name="tanggal" min="<?=date('Y-m-d')?>" value="<?=isset($data['sesi_date']) ? $data['sesi_date'] : ''?>" <?=(!$user_level) ? 'readonly' : '' ?>>
						</div>
	
						<div class="mb-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">
							<label for="title" class="form-label">Jam Mulai <span class="text-danger">*</span></label>
							<input disabled required type="time" class="form-control" id="jamstart" name="jamstart" value="<?=isset($data['sesi_jam_start']) ? $data['sesi_jam_start'] : ''?>" <?=(!$user_level) ? 'readonly' : '' ?>>
						</div> 
	
						<div class="mb-4 col-lg-4 col-md-4 col-sm-4 col-xs-4">
							<label for="title" class="form-label">Jam Akhir <span class="text-danger">*</span></label>
							<input disabled required type="time" class="form-control" id="jamend" name="jamend" value="<?=isset($data['sesi_jam_end']) ? $data['sesi_jam_end'] : ''?>" <?=(!$user_level) ? 'readonly' : '' ?>>
						</div>
					</div>
				</div>

				<div class="mb-3 col-lg-8 col-md-10 col-sm-12 col-xs-12">
					<label for="title" class="form-label">Judul <span class="text-danger">*</span></label>
					<input required type="text" class="form-control" id="title" name="title" value="<?=isset($data['sesi_title']) ? $data['sesi_title'] : ''?>" <?=(!$user_level) ? 'readonly' : '' ?>>
				</div>

				<div class="mb-3 col-lg-8 col-md-10 col-sm-12 col-xs-12">
					<!-- <div class="mb-4 col-lg-12 col-md-12 col-sm-12 col-xs-12"> -->
						<label for="materi" class="form-label">Mata Pelajaran <span class="text-danger">*</span></label>
						<select required class="form-control" name="materi" id="materi">
							<option value="">-- Pilih Mata Pelajaran --</option>
						</select>
					<!-- </div> -->
				</div>
	
				<div class="mb-3 col-lg-8 col-md-10 col-sm-12 col-xs-12">
					<label for="title" class="form-label">Catatan <span class="text-danger">*</span></label>
					<!-- <textarea rows="5" class="form-control" id="keterangan" name="keterangan"><?//=isset($data['sesi_note']) ? $data['sesi_note'] : ''?></textarea>  -->
					<div id="keterangan" class="form-control mb-3"><?=isset($data['sesi_note']) ? $data['sesi_note'] : '' ?></div>
				</div>
				
				<!-- <div class="mb-3 col-lg-8 col-md-10 col-sm-12 col-xs-12">
					<label for="title" class="form-label">Lampiran</label>
					<input type="file" class="form-control" id="lampiran" name="lampiran"  >
				</div> -->

				<div class="mb-3 button-save">
					<?php if($user_level) : ?>
						<a class="btn btn-primary text-white" type="submit" name="save">Simpan</a>
					<?php endif ?>
				</div>
			</form>
		</div>
	</div>
</section>

<!-- Include the selectize library -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Include the Quill library -->
<script src="<?=base_url()?>assets/libs/quilljs/quill.js"></script>

<!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->

<!-- Initialize Quill editor -->
<script>

	// ################################# AJAX GET KELAS #################################

	$.ajax({
		type: "GET",
		url: BASE_URL + "kelas/getAllClassTeacher",
		data: {},
		dataType: "JSON",
		headers: {
			"Authorization": "Basic "
		},
		success: function (response) {
			let kelasId = '<?=!empty($data['class_id']) ? $data['class_id'] : null ?>';
			$.each(response.data, function (i, val) {
				if(kelasId == val.class_id){ 
					$('#kelas').append(`<option value="${val.class_id}" selected>${val.class_name}</option>`);
				} else {
					$('#kelas').append(`<option value="${val.class_id}">${val.class_name}</option>`);
				}
				
			});
		}
	});

	// ################################# Enable INPUT DATE & TIME #################################
	$('#kelas').on('change', (e) => {
		if($('#kelas').val()){
			$('#tanggal')[0].disabled = false;
			// $('#jamstart')[0].disabled = false;
			// $('#jamend')[0].disabled = false;
		} else {
			$('#tanggal')[0].disabled = true;
			// $('#jamstart')[0].disabled = true;
			// $('#jamend')[0].disabled = true;
		}
	});

	// ################################# GET  INPUT DATE & TIME #################################
	let sesiStart = [];
	let sesiEnd = [];
	let dataSesi = [];
	$('#tanggal').on('change', (e) => {
		$('#jamstart')[0].disabled = false;
		$('#jamend')[0].disabled = false;
		
		$('#jamstart').val();
		$('#jamend').val();
		$.ajax({
			type: "GET",
			url: BASE_URL + 'sesi/getSesiByDate',
			data: {
				date: $('#tanggal').val(),
				class_id: $('#kelas').val()
			},
			dataType: "JSON",
			success: function (response) {
				sesiStart = [];
				sesiEnd = [];
				
				// looping untuk menggabungkan tanggal dan jam sesi
				$.each(response.data, function (i, val) {
					dataSesi.push(val);
					sesiStart.push(moment(`${val.sesi_date} ${val.sesi_jam_start}`, 'YYYY-MM-DD HH:mm'));
					sesiEnd.push(moment(`${val.sesi_date} ${val.sesi_jam_end}`, 'YYYY-MM-DD HH:mm'));
				}); 
			}
		});
	});

	// ################################# VALIDASI JAM SESI #################################
	let selectStart;
	let selectEnd;
	$('#jamstart').on('change', (e)=>{
		let tanggal = $('#tanggal').val();
		selectStart = moment(`${tanggal} ${$('#jamstart').val()}`, 'YYYY-MM-DD HH:mm');
		selectEnd = moment(`${tanggal} ${$('#jamend').val()}`, 'YYYY-MM-DD HH:mm');

		$.each(sesiStart, function (i, val) { 

			let message = `Sesi sudah ada di jam tersebut sesi kelas ${dataSesi[i].class_name} jam mulai ${dataSesi[i].sesi_jam_start} sd  ${dataSesi[i].sesi_jam_end}`;

				// jika waktu start berada diantara waktu awal dan akhir yang ada
			if( (selectStart >= sesiStart[i]) && (selectStart <= sesiEnd[i]) ){
				warningAlert(message)
				return false
			} else if((selectEnd >= sesiStart[i]) && (selectEnd <= sesiEnd[i])){
				// jika waktu end berada diantara waktu awal dan akhir yang ada
				warningAlert(message)
				return false
			} else if((selectStart <= sesiStart[i]) && (selectEnd >= sesiEnd[i])){
				// jika waktu start dan end melompati waktu awal dan akhir yang ada
				warningAlert(message)
				return false
			} 
		});
	});

	$('#jamend').on('change', (e)=>{
		let tanggal = $('#tanggal').val();
		selectStart = moment(`${tanggal} ${$('#jamstart').val()}`, 'YYYY-MM-DD HH:mm');
		selectEnd = moment(`${tanggal} ${$('#jamend').val()}`, 'YYYY-MM-DD HH:mm');

		$.each(sesiStart, function (i, val) { 

			let message = `Sesi sudah ada di jam tersebut sesi kelas ${dataSesi[i].class_name} jam mulai ${dataSesi[i].sesi_jam_start} sd  ${dataSesi[i].sesi_jam_end}`;
				// jika waktu start berada diantara waktu awal dan akhir yang ada
			if( (selectStart >= sesiStart[i]) && (selectStart <= sesiEnd[i]) ){
				warningAlert(message)
				return false
			} else if((selectEnd >= sesiStart[i]) && (selectEnd <= sesiEnd[i])){
				// jika waktu end berada diantara waktu awal dan akhir yang ada
				warningAlert(message)
				return false
			} else if((selectStart <= sesiStart[i]) && (selectEnd >= sesiEnd[i])){
				// jika waktu start dan end melompati waktu awal dan akhir yang ada
				warningAlert(message)
				return false
			} 
		});
	});

	// ################################# INPUT KETERANGAN #################################
	var quill = new Quill('#keterangan', {
		theme: 'snow'
	});


	// ################################# SIMPAN #################################
	$('a[name="save"]').on('click', function(){
		let title 		= $('#title').val().trim(); 
		let tanggal 	= $('#tanggal').val(); 
		let jamstart 	= $('#jamstart').val(); 
		let jamend 		= $('#jamend').val();
		let materi_id 	= $('#materi').val();
		let class_id 	= $('#kelas').val();
		let keterangan 	= quill.container.firstChild.innerHTML;
		let id 			= $('#id').val();

		// Validasi judul
		if(!title) {
			warningAlert('Judul tidak boleh kosong'); 
			return;
		}

		// Validasi Tanggal
		if(!tanggal){
			warningAlert('Tanggal Harus di isi');
			return;
		}

		// Validasi jamstart
		if(!jamstart){
			warningAlert('Jam awal harus isi')
			return;
		}
		
		// Validasi jamstart
		if(!jamend){
			warningAlert('Jam akhir harus isi')
			return;
		}

		// VALIDASI JAM AWAL TIDAK BOLEH LEBIH BESAR DARI JAM AKHIR
		if(jamstart > jamend){
			warningAlert('Jam awal tidak boleh lebih besar dari jam akhir')
			return;
		}

		// Validasi pilih materi
		if(!materi_id){
			warningAlert('Materi harus di isi');
			return;
		}

		// Validasi pilih kelas
		if(!class_id){
			warningAlert('Kelas harus di isi');
			return;
		}

		// Validasi keterangan
		if(!stripHtml(keterangan)){
			warningAlert('Keterangan harus di isi');
			return;
		}

		$.ajax({
			type: "POST",
			url: BASE_URL+"sesi/create",
			data: {
				csrf_token_name: $('input[name="csrf_token_name"]').val(),
				title: title,
				tanggal: tanggal,
				jamstart: jamstart,
				jamend: jamend,
				materi_id: materi_id,
				class_id: class_id,
				keterangan: keterangan,
				id: id
			},
			dataType: "JSON",
			success: function (response) {
				if(response.success == true){
					Swal.fire({
						type: 'success',
						title: '<h4 class="text-success"></h4>',
						html: `<span class="text-success">${response.message}</span>`,
						timer: 5000
					});
					setTimeout(function(){
						window.location.href = BASE_URL+'sesi';
					}, 2000);
				}else{
					Swal.fire({
						type: 'error',
						title: '<h4 class="text-warning"></h4>',
						html: `<span class="text-warning">${response.message}</span>`,
						timer: 5000
					});

					document.getElementsByName('csrf_token_name')[0].value = response.token;
				}
			}
		});
	});

	// ################################# COMBO BOX MATERI #################################
	let user_level = <?=$this->session->userdata('user_level')?>;
	if(user_level != 3){ // jika user selain guru
		$('select[name="materi"]').select2({disabled: true});
	}

	$('select[name="materi"]').select2({
        theme: "bootstrap-5",
        data: materi,
        placeholder: '-- Pilih Mapel --',
        allowClear: true
    });


	// ################################# AJAX GET MATERI #################################
	$.ajax({
		type: "GET",
		url: BASE_URL+"/materi/getAllMateri",
		data: {},
		dataType: "JSON",
		success: function (response) {
			let subject_id = <?=isset($data['subject_id']) ? $data['subject_id'] : 0 ?>;
			(subject_id == 0) ? $('#materi').append(`<option value="" selected>-- Pilih Mata Pelajaran --</option>`) : '';
			$.each(response, function (i, val) { 
				if(subject_id == val.subject_id){
					$('#materi').append(`<option value="${val.subject_id}" selected>${val.subject_name}</option>`);
				}else{
					$('#materi').append(`<option value="${val.subject_id}">${val.subject_name}</option>`);
				}
			});
		}
	});

	function warningAlert(message){
		Swal.fire({
			type: 'info',
			title: '<h4 class="text-warning"></h4>',
			html: `<span class="text-warning">${message}</span>`,
			showConfirmButton: true
		});
	}

	function stripHtml(html)
	{
		let tmp = document.createElement("DIV");
		tmp.innerHTML = html;
		return tmp.textContent || tmp.innerText || "";
	}
</script>
