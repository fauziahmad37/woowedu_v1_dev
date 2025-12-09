<!-- <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet"> -->

<!-- custom css -->
<style>
	input.form-check-input {
		width: 40px !important;
	}

	.button-change-image,
	.button-change-image-jawaban-benar,
	.button-change-image-jawaban-salah {
		position: absolute;
		display: none !important;
	}

	.jawaban-content, .jawaban-alternatif-content {
		cursor: text;
		margin-left: 5em;
		margin-right: 1em;
		background-color: rgba(255, 255, 255, 0.1);
		border-top-left-radius: 10px;
		border-top-right-radius: 10px;
		border-color: #FFFFFF;
		border-width: 2px;
		border-style: solid;
		border-top: unset;
		border-left: unset;
		border-right: unset;
	}

	/* .form-select {
		width: 120px;
	} */
</style>

<section class="header-options bg-white">
	<div class="container py-4">
		<div class="row">
			<div class="col-xl-6 col-lg-4 col-md-6 mb-2">
				<button class="btn-back btn btn-light border border-1 border-dark rounded-3 me-2">
					<i class="fa-solid fa-chevron-left"></i>
				</button>

				<button class="btn btn-light rounded-3" style="background-color: #E3E4E8;">
					<i class="fa-solid fa-list me-2"></i>
					<span class="soal-title">Isi Yang Kosong</span>
				</button>
			</div>

			<!-- col-6 position right -->
			<div class="col-xl-6 col-lg-8 col-md-6 text-end">
				<!-- <button class="mb-2 btn btn-light border border-1 border-dark rounded-3 me-2" style="">
					<i class="far fa-lightbulb me-2"></i>
					Respon Jawaban
				</button> -->

				<button class="mb-2 btn btn-light rounded-3 btn-pengaturan-fill-the-blank" style="background-color: #E3E4E8;" data-bs-toggle="modal" data-bs-target="#pengaturanModalFillTheBlank">
					<i class="fa fa-gear me-2"></i>
					Pengaturan Jawaban
				</button>

				<button class="mb-2 btn btn-primary rounded-3 ms-2 text-white simpan-jawaban-fill-the-blank">
					Simpan Jawaban
				</button>
			</div>
		</div>
	</div>
</section>

<section class="main-content">

	<!-- main soal -->
	<div class="main-soal bg-primary container rounded-4 p-4" style="min-height: 600px; margin-top:40px;">

		<input type="hidden" id="fiil_the_blank_id" />
		
		<div class="row">
			<div class="col-4">
				<div class="card-left bg-primary-300 border rounded-3">
					<!-- add image -->
					<div class="image-container d-flex justify-content-center align-items-center" style="height: 100%;">
						<img class="image-place-holder" src="<?= base_url('assets/images/icons/tambahkan_gambar_pendukung.svg') ?>" alt="image" class="rounded-3" style="width: 100%; height: 100%; cursor: pointer;">
						<input type="file" accept="image/png, image/jpeg" name="image" id="image" style="display: none;">

						<!-- button delete & change image -->
						<div class="button-change-image d-flex justify-content-between mt-3">
							<button class="btn btn-light border-1 rounded-3 edit-pic" style="width: 45%;"><i class="fas fa-edit"></i></button>
							<button class="btn btn-light border-1 rounded-3 delete-pic" style="width: 45%;"><i class="fas fa-trash"></i></button>
						</div>


					</div>
				</div>
			</div>

			<div class="col-8">
				<div class="card-left bg-primary-300 border rounded-3" style="border-color: #FFFFFF; height: 100%;">
					<input type="text" id="soalFillTheBlank" class="form-control border-none outline-none text-white input-question h-100 w-100" placeholder="Ketik pertanyaan soal di sini..." />
				</div>
			</div>
		</div>

		<div class="container bg-primary-600 text-white rounded-2 pt-3" style="height: 124px; margin-top: 40px;">

			<div class="d-flex justify-content-between">

				<div contenteditable="true" id="jawaban" class="jawaban-content bg-primary-300 p-4 align-items-center text-white w-100">
					<p class="place-jawaban text-white">Ketik jawaban di sini...</p>

				</div>

				<div class="me-5" style="width: 100px;">
					<button class="btn-respon-jawaban btn btn-light border-0 border-1 text-white rounded-3 w-100 h-100 d-none" style="background-color: rgba(255, 255, 255, 0.1);">
						<i class="far fa-lightbulb h2"></i>
					</button>
				</div>
			</div>


		</div>

		<div class="container-jawaban-alternatif bg-primary-600 text-white rounded-2 pt-3 d-none" style="height: 154px; margin-top: 40px;">


			<p class="ms-3" style="color:rgba(255, 255, 255, 0.6)">Jawaban Alternatif</p>

			<div contenteditable="true" class="jawaban-alternatif-content bg-primary-300 p-4 align-items-center text-white w-75" style="margin-left: 6rem;">
				<p class="place-jawaban-alternatif text-white">Ketik jawaban di sini...</p>
			</div>


		</div>


	</div>


	<!-- Respon jawaban -->
	<div class="variasi-jawaban bg-primary container rounded-4 p-4 d-none" style="min-height: 600px; margin-top:40px;">
		<div class="row">
			<div class="col">
				<button class="btn btn-lg text-white btn-kembali-ke-soal" style="background-color: rgba(255, 255, 255, 0.4);">
					<i class="fa-solid fa-chevron-left"></i>
					Kembali ke soal
				</button>
			</div>
			<div class="col justify-content-end d-flex">
				<button class="btn btn-danger btn-lg text-white me-2 btn-delete-respon-jawaban">
					<i class="fa-solid fa-trash"></i>
				</button>
				<button class="btn btn-lg text-dark btn-simpan-respon" style="background-color: rgba(255, 255, 255, 1);">
					Simpan Respon
				</button>

			</div>
		</div>

		<!-- respon jawaban benar -->
		<div class="row mt-4">
			<div class="col-4">
				<div class="image-container-jawaban-benar bg-primary-300 rounded-3 border-1 d-flex justify-content-center align-items-center" style="min-height: 353px; border-style: dashed; border-color: #FFFFFF;">
					<img src="<?= base_url('assets/images/icons/tambahkan_gambar_pendukung.svg') ?>" alt="image" class="rounded-3 image-placeholder-jawaban-benar-essay" style="width: 100%; height: 100%; cursor: pointer;">

					<!-- button delete & change image -->
					<div class="button-change-image-jawaban-benar d-flex justify-content-between mt-3">
						<button class="btn btn-light border-1 rounded-3 edit-pic-jawaban-benar" style="width: 45%;"><i class="fas fa-edit"></i></button>
						<button class="btn btn-light border-1 rounded-3 delete-pic-jawaban-benar-essay" style="width: 45%;"><i class="fas fa-trash"></i></button>
					</div>
				</div>

				<!-- input image jawaban benar -->
				<input type="file" accept="image/png, image/jpeg" name="image-jawaban-benar-essay" id="image-jawaban-benar" style="display: none;">
			</div>
			<div class="col-8">
				<div class="card-right bg-primary-300 border rounded-3" style="height: 100%;">
					<input type="text" class="form-control border-none outline-none text-white input-question h-100 w-100 input-jawaban-benar" placeholder="Respon Jawaban Benar..." />
				</div>
			</div>
		</div>

		<!-- response jawaban salah -->
		<div class="row mt-4">
			<div class="col-4">
				<div class="image-container-jawaban-salah bg-primary-300 rounded-3 border-1 d-flex justify-content-center align-items-center" style="min-height: 353px; border-style: dashed; border-color: #FFFFFF;">
					<img src="<?= base_url('assets/images/icons/tambahkan_gambar_pendukung.svg') ?>" alt="image" class="rounded-3 image-placeholder-jawaban-salah-essay" style="width: 100%; height: 100%; cursor: pointer;">

					<!-- button delete & change image -->
					<div class="button-change-image-jawaban-salah d-flex justify-content-between mt-3">
						<button class="btn btn-light border-1 rounded-3 edit-pic-jawaban-salah" style="width: 45%;"><i class="fas fa-edit"></i></button>
						<button class="btn btn-light border-1 rounded-3 delete-pic-jawaban-salah" style="width: 45%;"><i class="fas fa-trash"></i></button>
					</div>
				</div>

				<!-- input image jawaban salah -->
				<input type="file" accept="image/png, image/jpeg" name="image-jawaban-salah-essay" id="image-jawaban-salah" style="display: none;">
			</div>
			<div class="col-8">
				<div class="card-right bg-primary-300 border rounded-3" style="height: 100%;">
					<input type="text" class="form-control border-none outline-none text-white input-question h-100 w-100 input-jawaban-salah" placeholder="Respon Jawaban Salah..." />
				</div>
			</div>
		</div>
	</div>

</section>

<!-- Modal Pengaturan -->
<div class="modal fade" id="pengaturanModalFillTheBlank" tabindex="-1" aria-labelledby="pengaturanModalFillTheBlankLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-primary text-white">
				<h1 class="modal-title fs-5" id="pengaturanModalFillTheBlankLabel">Pengaturan Jawaban</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">

				<!-- point -->
				<div class="row border-bottom pb-3">
					<div class="col-6">
						<span>Nilai Jawaban</span>
					</div>
					<div class="col-6 justify-content-end d-flex">
						<select id="point" class="form-select" aria-label="Default select example">
							<option value="10">10 Poin</option>
							<option value="20">20 Poin</option>
							<option value="30">30 Poin</option>
							<option value="40">40 Poin</option>
							<option value="50">50 Poin</option>
							<option value="60">60 Poin</option>
							<option value="70">70 Poin</option>
							<option value="80">80 Poin</option>
							<option value="90">90 Poin</option>
							<option value="100">100 Poin</option>
						</select>
					</div>
				</div>

				<!-- respon jawaban -->
				<div class="row border-bottom pb-3 mt-3">
					<div class="col-6">
						<span>Respon Jawaban</span>
					</div>
					<div class="col-6 justify-content-end d-flex">
						<!-- toogle -->
						<div class="form-check form-switch">
							<input class="form-check-input" type="checkbox" id="responJawabanFillTheBlank">
							<label class="form-check-label" for="responJawabanFillTheBlank"></label>
						</div>
					</div>
				</div>

				<!-- variasi jawaban -->
				<div class="row border-bottom pb-3 mt-3">
					<div class="col-6">
						<span>Variasi Jawaban (besar atau kecil)</span>
					</div>
					<div class="col-6 justify-content-end d-flex">
						<!-- toogle -->
						<div class="form-check form-switch">
							<input class="form-check-input" type="checkbox" id="variasiJawaban">
							<label class="form-check-label" for="variasiJawaban"></label>
						</div>
					</div>
				</div>

				<!-- jawaban alternatif -->
				<div class="row pb-3 mt-3">
					<div class="col-6">
						<span>Jawaban Alternatif</span>
					</div>
					<div class="col-6 justify-content-end d-flex">
						<!-- toogle -->
						<div class="form-check form-switch">
							<input class="form-check-input" type="checkbox" id="jawabanAlternatif">
							<label class="form-check-label" for="jawabanAlternatif"></label>
						</div>
					</div>
				</div>


			</div>
		</div>
	</div>
</div>

<!-- <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script> -->

<script>
	// input image with placeholder
	$('.image-place-holder').click(function() {
		$('#image').click();

		$('#image').change(function() {
			var file = $('#image')[0].files[0];
			var reader = new FileReader();
			reader.onload = function(e) {
				$('.image-place-holder').attr('src', e.target.result);
			}
			reader.readAsDataURL(file);
		});

	});

	// image placeholder on hover
	$('.image-container').mouseenter(function() {
		let image = $(this).find('.image-place-holder').attr('src');
		if (image.includes('tambahkan_gambar_pendukung')) {
			console.log('tes tes tes');
			$('.button-change-image').css('display', 'none !important');
		} else {
			$('.button-change-image').css('display', 'flex');
			$('.button-change-image').css('z-index', '1000');
			$('.button-change-image').css('display', 'unset !important');

			$('.edit-pic').css('display', 'unset');
			$('.delete-pic').css('display', 'unset');


			$('.image-place-holder').css('opacity', '0.5');
		}
	});

	// mouse leave image container
	$('.image-container').mouseleave(function() {
		$('.edit-pic').css('display', 'none');
		$('.delete-pic').css('display', 'none');

		$('.image-place-holder').css('opacity', '1');
	});

	// delete image
	$('.delete-pic').click(function() {
		$('.image-place-holder').attr('src', '<?= base_url('assets/images/icons/tambahkan_gambar_pendukung.svg') ?>');
		$('.button-change-image').css('display', 'none !important');
	});

	// edit image
	$('.edit-pic').click(function() {
		$('#image').click();

		$('#image').change(function() {
			var file = $('#image')[0].files[0];
			var reader = new FileReader();
			reader.onload = function(e) {
				$('.image-place-holder').attr('src', e.target.result);
			}
			reader.readAsDataURL(file);
		});
	});

	// Jawaban Content click
	$('.jawaban-content').click(function() {
		if ($('.jawaban-content').text().includes('Ketik jawaban di sini...')) {
			$('.jawaban-content').text('');
		}
	});

	// Jawaban Content on blur
	$('.jawaban-content').blur(function() {
		if ($('.jawaban-content').text() == '') {
			$('.jawaban-content').text('Ketik jawaban di sini...');
		}
	});

	// Jawaban Alternatif Content click
	$('.jawaban-alternatif-content').click(function() {
		if ($('.jawaban-alternatif-content').text().includes('Ketik jawaban di sini...')) {
			$('.jawaban-alternatif-content').text('');
		}
	});


	//  ======================================================================================================== //
	//  ========================================= Section Respon Jawaban  ====================================== //
	//  ======================================================================================================== //

	//  checkbox responJawabanFillTheBlank change
	$('#responJawabanFillTheBlank').change(function() {
		if ($(this).is(':checked')) {
			$(this).prop('checked', true);

			// show button respon jawaban
			$('.btn-respon-jawaban').removeClass('d-none');
		} else {
			$(this).prop('checked', false);

			// hide button respon jawaban
			$('.btn-respon-jawaban').addClass('d-none');
		}
	});

	// btn-respon-jawaban click
	$('.btn-respon-jawaban').click(function() {
		let mainSoal = $('.main-soal');
		let variasiJawaban = $('.variasi-jawaban');

		mainSoal.addClass('d-none');
		variasiJawaban.removeClass('d-none');
	});

	// btn-kembali-ke-soal click
	$('.btn-kembali-ke-soal').click(function() {
		let mainSoal = $('.main-soal');
		let variasiJawaban = $('.variasi-jawaban');

		mainSoal.removeClass('d-none');
		variasiJawaban.addClass('d-none');
	});

	// input image jawaban benar
	$('.image-placeholder-jawaban-benar-essay').click(function() {

		$('#image-jawaban-benar').click();

		$('#image-jawaban-benar').change(function() {
			var file = $('#image-jawaban-benar')[0].files[0];
			var reader = new FileReader();
			reader.onload = function(e) {
				$('.image-placeholder-jawaban-benar-essay').attr('src', e.target.result);
			}
			reader.readAsDataURL(file);
		});

	});

	// image placeholder on hover
	$('.image-placeholder-jawaban-benar-essay').mouseenter(function() {
		let image = $(this).attr('src');

		// image opacity
		$('.image-placeholder-jawaban-benar-essay').css('opacity', '0.8');

		if (image.includes('tambahkan_gambar_pendukung')) {
			console.log('tes tes tes');
			$('.button-change-image-jawaban-benar').css('display', 'none !important');
		} else {
			$('.button-change-image-jawaban-benar').css('display', 'flex');
			$('.button-change-image-jawaban-benar').css('z-index', '1000');
			$('.button-change-image-jawaban-benar').css('display', 'unset !important');

			$('.edit-pic-jawaban-benar').css('display', 'unset');
			$('.delete-pic-jawaban-benar-essay').css('display', 'unset');
		}
	});

	// mouse leave image container jawaban benar
	$('.image-placeholder-jawaban-benar-essay').mouseleave(function() {
		// image opacity
		$('.image-placeholder-jawaban-benar-essay').css('opacity', '1');

		$('.edit-pic-jawaban-benar').css('display', 'none');
		$('.delete-pic-jawaban-benar-essay').css('display', 'none');
	});

	// edit-pic-jawaban-benar click
	$('.edit-pic-jawaban-benar').click(function() {
		$('.image-placeholder-jawaban-benar-essay').click();
	});

	// edit-pic-jawaban-salah click
	$('.edit-pic-jawaban-salah').click(function() {
		$('.image-placeholder-jawaban-salah-essay').click();
	});

	// delete-pic-jawaban-benar click
	$('.delete-pic-jawaban-benar-essay').click(function() {
		$('.image-placeholder-jawaban-benar-essay').attr('src', BASE_URL + 'assets/images/icons/tambahkan_gambar_pendukung.svg');
		$('.button-change-image-jawaban-benar').css('display', 'none !important');

		$('input[name="image-jawaban-benar-essay"]').val('');
	});

	// delete-pic-jawaban-salah click
	$('.delete-pic-jawaban-salah').click(function() {
		$('.image-container-jawaban-salah').find('img').attr('src', BASE_URL + 'assets/images/icons/tambahkan_gambar_pendukung.svg');
		$('.button-change-image-jawaban-salah').css('display', 'none !important');

		$('input[name="image-jawaban-salah-essay"]').val('');
	});

	// input image jawaban salah
	$('.image-placeholder-jawaban-salah-essay').click(function() {

		$('#image-jawaban-salah').click();

		$('#image-jawaban-salah').change(function() {
			var file = $('#image-jawaban-salah')[0].files[0];
			var reader = new FileReader();
			reader.onload = function(e) {
				$('.image-placeholder-jawaban-salah-essay').attr('src', e.target.result);
			}
			reader.readAsDataURL(file);
		});

	});

	// image placeholder jawaban salah on hover
	$('.image-container-jawaban-salah').mouseenter(function() {
		let image = $(this).find('img').attr('src');

		// image opacity
		$('.image-container-jawaban-salah').css('opacity', '0.8');

		if (image.includes('tambahkan_gambar_pendukung')) {
			console.log('tes tes tes');
			$('.button-change-image-jawaban-salah').css('display', 'none !important');
		} else {
			$('.button-change-image-jawaban-salah').css('display', 'flex');
			$('.button-change-image-jawaban-salah').css('z-index', '1000');
			$('.button-change-image-jawaban-salah').css('display', 'unset !important');

			$('.edit-pic-jawaban-salah').css('display', 'unset');
			$('.delete-pic-jawaban-salah').css('display', 'unset');
		}
	});

	// mouse leave image container jawaban salah
	$('.image-container-jawaban-salah').mouseleave(function() {
		// image opacity
		$('.image-container-jawaban-salah').css('opacity', '1');

		$('.edit-pic-jawaban-salah').css('display', 'none');
		$('.delete-pic-jawaban-salah').css('display', 'none');
	});

	// btn-simpan-respon click
	$('.btn-simpan-respon').click(function() {
		alert('Simpan respon');

		$('.btn-kembali-ke-soal').click();
	});

	// btn-delete-respon-jawaban click
	$('.btn-delete-respon-jawaban').click(function() {
		$('.input-jawaban-benar').val('');
		$('.input-jawaban-salah').val('');
		$('.image-container-jawaban-salah').find('img').attr('src', BASE_URL + 'assets/images/icons/tambahkan_gambar_pendukung.svg');
		$('.image-container-jawaban-salah').find('img').attr('src', BASE_URL + 'assets/images/icons/tambahkan_gambar_pendukung.svg');
	});

	//  ======================================================================================================== //
	//  ============================== Section Jawaban Alternatif / Variasi Jawaban ============================ //
	//  ======================================================================================================== //

	//  checkbox jawabanAlternatif change
	$('#jawabanAlternatif').change(function() {
		if ($(this).is(':checked')) {
			$(this).prop('checked', true);

			// show jawaban alternatif
			$('.container-jawaban-alternatif').removeClass('d-none');

		} else {
			$(this).prop('checked', false);

			// hide jawaan alternatif
			$('.container-jawaban-alternatif').addClass('d-none');
		}

	});

	$('.jawaban-alternatif-content').on('blur', function() {
		if ($('.jawaban-alternatif-content').text() == '') {
			$('.jawaban-alternatif-content').text('Ketik jawaban di sini...');
		}
	});

	// ======================================================================================================== //
	// ================================================= Header =============================================== //
	// ======================================================================================================== //


	// button back click
	$('.btn-back').click(function() {

		// uncheck semua checkbox yang ada di pengaturan jawaban
		$('#responJawabanFillTheBlank').prop('checked', false);
		$('#variasiJawaban').prop('checked', false);
		$('#jawabanAlternatif').prop('checked', false);

		// hide button respon jawaban
		$('.btn-respon-jawaban').addClass('d-none');

		// hide jawaban alternatif
		$('.container-jawaban-alternatif').addClass('d-none');

		// add display none soal-fill-the-blank-container
		$('.soal-fill-the-blank-container').addClass('d-none');

	});

	// button pengeturan click
	$('.btn-pengaturan-fill-the-blank').click(function() {
		$('#pengaturanModalFillTheBlank').modal('show');

		//remove modal backdrop
		$('.modal-backdrop').remove();
	});

	// simpan jawaban
	$('.simpan-jawaban-fill-the-blank').click(function(e) {
		e.preventDefault();

		let fiil_the_blank_id = $('#fiil_the_blank_id').val();
		let card = $(`.card[data="${activeCard}"]`); // get active card
		let listCardItem = card.find('.card').length;
		let totalSoal = $(`#total-soal${activeCard}`).text();

		// validation counter soal
		if(!fiil_the_blank_id) { // jika bukan update soal maka validasi counter soal
			if (listCardItem >= totalSoal) {
				Swal.fire({
					icon: 'error',
					title: 'Gagal',
					text: 'Jumlah soal sudah mencapai batas maksimal',
				});
				return;
			}
		}

		// ajax post
		let form = new FormData();

		let mapel = $('#select-mapel').val();
		let soal = $('#soalFillTheBlank').val();
		let jawaban = $('#jawaban').text();
		let image = $('#image')[0].files[0];
		
		let responseJawabanCheck = $('#responJawabanFillTheBlank')[0].checked;
		let jawabanAlternatifCheck = $('#jawabanAlternatif')[0].checked;

		let imageJawabanBenar = $('#image-jawaban-benar')[0].files[0];

		let deleteImageJawabanBenar = false;
		let imagePlaceholderJawabanBenarEssay = $('.image-placeholder-jawaban-benar-essay').attr('src');
		if (imagePlaceholderJawabanBenarEssay.includes('tambahkan_gambar_pendukung')) {
			deleteImageJawabanBenar = true;
		} else {
			deleteImageJawabanBenar = false;
		}
		
		let deleteImageJawabanSalah = false;
		let imagePlaceholderJawabanSalahEssay = $('.image-placeholder-jawaban-salah-essay').attr('src');
		if (imagePlaceholderJawabanSalahEssay.includes('tambahkan_gambar_pendukung')) {
			deleteImageJawabanSalah = true;
		} else {
			deleteImageJawabanSalah = false;
		}

		let imageJawabanSalah = $('#image-jawaban-salah')[0].files[0];


		let responseJawabanBenar = $('.input-jawaban-benar').val();
		let responseJawabanSalah = $('.input-jawaban-salah').val();
		let responseJawabanAlternatif = $('.jawaban-alternatif-content').text();
		let variasiJawaban = $('#variasiJawaban').is(':checked') ? 1 : 0;
		let point = $('.soal-fill-the-blank-container #point').val();
		let type = jenisSoalActive;

		form.append('fiil_the_blank_id', fiil_the_blank_id);
		form.append('mapel', mapel);
		form.append('soal', soal);
		form.append('jawaban', jawaban);
		form.append('image', image);
		
		form.append('imageJawabanBenar', imageJawabanBenar);
		form.append('imageJawabanSalah', imageJawabanSalah);
		form.append('deleteImageJawabanBenar', deleteImageJawabanBenar);
		form.append('deleteImageJawabanSalah', deleteImageJawabanSalah);

		form.append('responseJawabanBenar', responseJawabanBenar);
		form.append('responseJawabanSalah', responseJawabanSalah);
		form.append('responseJawabanAlternatif', responseJawabanAlternatif);
		form.append('variasiJawaban', variasiJawaban);
		form.append('point', point);
		form.append('type', type);
		form.append('csrf_token_name', $('meta[name="csrf_token"]').attr('content'));

		form.append('responseJawabanCheck', responseJawabanCheck);
		form.append('jawabanAlternatifCheck', jawabanAlternatifCheck);

		let url = (fiil_the_blank_id) ? BASE_URL + 'Asesmen_standard/update_fill_the_blank' : BASE_URL + 'Asesmen_standard/save_fill_the_blank';

		$.ajax({
			url: url,
			type: 'POST',
			data: form,
			contentType: false,
			processData: false,
			success: function(res) {

				if (res.success) {
					
					Swal.fire({
						icon: 'success',
						title: 'Berhasil',
						text: res.message,
					});

					// reset csrf token
					$('meta[name="csrf_token"]').attr('content', res.csrf_token);
					
					// jika melakukan update soal maka tidak perlu update counter soal
					if (!fiil_the_blank_id) {
						$(`#count-soal${activeCard}`).text(listCardItem + 1); // update counter soal
					}

					// add item to list soal
					let soal = res.data;
					console.log(soal);
					let item = `
						<div class="card border-start-0 border-top-0 border-end-0 mb-3 rounded-0 mt-5">

							<!-- card header -->
							<div class="card-header bg-white border-0 p-0">
								<div class="d-flex justify-content-between">
									<div class="btn btn-light border rounded-3 no-soal" style="width: 100px;">
										${(fiil_the_blank_id) ? questionNumberActive : listCardItem+1}
									</div>

									
									<div class="d-flex btn-group-fill-the-blank" onmouseenter="showBtnGroupFillTheBlankHover(this)">
										${(soal.response_correct_answer) ? `
										<button class="btn btn-light border rounded-3 me-2">
											<i class="far fa-lightbulb me-2"></i>Respon
										</button>` : ``}

										${(soal.variation_answer) ? `
										<button class="btn btn-light border rounded-3 me-2">
											<i class="fa-solid fa-layer-group me-2"></i>Variasi
										</button>` : ``}

										${(soal.alternative_answer) ? `
										<button class="btn btn-light border rounded-3 me-2">
											<i class="fa-solid fa-layer-group me-2"></i>Alternatif
										</button>` : ``}
										
										<button class="btn btn-light border rounded-3 me-2">
											<i class="fa-solid fa-ribbon me-2"></i>${soal.point} Poin
										</button>
									</div>

									<div class="d-flex btn-group-fill-the-blank-hover d-none" onmouseleave="showBtnGroupFillTheBlank(this)">
										
										<button class="btn btn-light border rounded-3 me-2" onclick="tinjauSoal(this)">
											<i class="fa-solid fa-eye"></i> Tinjau Soal
										</button>
										
										<button class="btn btn-light border rounded-3 me-2" onclick="editFillTheBlank(this)">
											<i class="fa fa-edit me-2"></i> Ubah Soal
										</button>
										
										<button class="btn btn-light border rounded-3 me-2" onclick="deleteQuestionList(this)">
											<i class="fa fa-trash"></i>
										</button>
										
									</div>
								</div>
							</div>

							<div class="card-body p-0 pb-3 mt-3">
								<div class="d-flex justify-content-between">
									<div>
										<input type="hidden" name="question_id[]" value="${soal.id}">
										
										${(soal.question_file) ? `<img src="${ADMIN_URL + soal.question_file}" class="img-fluid img-question" alt="Gambar Soal">` : ''}

										<p>${soal.question}</p>

										${soal.type == 1 ? soalPilihanGanda(data) : ''}
									</div>
								</div>
							</div>

						</div>
					`;

					// hide empty image
					$(`.card[data="${activeCard}"]`).find('.empty-soal-image').addClass('d-none');
					
					// jika tidak ada fiil_the_blank_id langsung append item di paling bawah
					if (!fiil_the_blank_id) {
						$(`.card[data="${activeCard}"]`).find('.list-soal-container').append(item);
					}
					
					// jika ada fiil_the_blank_id maka replace item yang sudah ada
					if (fiil_the_blank_id) {						
						$(`.card[data="${activeCard}"]`).find('.list-soal-container').find(`input[value="${fiil_the_blank_id}"]`).closest('.card').replaceWith(item);
					}

					// pengaturan jawaban
					$('#responJawabanFillTheBlank').prop('checked', false);
					$('#variasiJawaban').prop('checked', false);
					$('#jawabanAlternatif').prop('checked', false);

					// clear input
					$('.image-place-holder').attr('src', BASE_URL + 'assets/images/icons/tambahkan_gambar_pendukung.svg');
					$('#soalFillTheBlank').val('');
					$('#jawaban').text('Ketik jawaban di sini...');
					$('.btn-respon-jawaban').addClass('d-none');

					// jawaban alternatif
					$('.jawaban-alternatif-content').text('Ketik jawaban di sini...');
					$('.container-jawaban-alternatif').addClass('d-none');

					// clear respon jawaban
					$('.input-jawaban-benar').val('');
					$('.input-jawaban-salah').val('');

					// clear image respon jawaban
					$('.image-placeholder-jawaban-benar-essay').attr('src', BASE_URL + 'assets/images/icons/tambahkan_gambar_pendukung.svg');
					$('.image-placeholder-jawaban-salah-essay').attr('src', BASE_URL + 'assets/images/icons/tambahkan_gambar_pendukung.svg');


				} else {
					// reset csrf token
					$('meta[name="csrf_token"]').attr('content', res.csrf_token);

					Swal.fire({
						icon: 'error',
						title: 'Gagal',
						text: res.message,
					});
				}
			}
		});
	});

	// show btn-group-fill-the-blank-hover on mouse enter
	function showBtnGroupFillTheBlankHover(el) {
		$(el).addClass('d-none');
		$(el).next().removeClass('d-none');
	}

	// show btn-group-fill-the-blank on mouse leave
	function showBtnGroupFillTheBlank(el) {
		$('.btn-group-fill-the-blank').removeClass('d-none');
		$('.btn-group-fill-the-blank-hover').addClass('d-none');
	}

	// Edit soal fill the blank
	function editFillTheBlank(el) {

		// cursor move to top
		$('html, body').animate({
			scrollTop: 0
		}, 500);

		// set active question number active & jenis soal active
		questionNumberActive = $(el).closest('.card').find('.no-soal').text();
		jenisSoalActive = $(el).closest('.card-group-custom').attr('jenis-soal');

		// set active card
		activeCard = $(el).closest('.card-group-custom').attr('data');

		$('.soal-fill-the-blank-container').removeClass('d-none');

		let card = $(el).closest('.card');
		let questionId = card.find('input').val();

		// set value #fiil_the_blank_id
		$('#fiil_the_blank_id').val(questionId);

		// get data soal
		$.ajax({
			url: BASE_URL + 'Asesmen_standard/get_question_fill_the_blank',
			type: 'GET',
			data: {
				question_id: questionId
			},
			success: function(res) {
				if (res.success) {
					let soal = res.data;

					// ubah title soal
					if(soal.type == '2') {
						$('.soal-title').text('Uraian');
					} else {
						$('.soal-title').text('Isi Yang Kosong');
					}

					// set pengaturan jawaban
					$('#point').val(soal.point);
					$('#responJawabanFillTheBlank').prop('checked', (soal.response_correct_answer) ? true : false);
					$('#variasiJawaban').prop('checked', (soal.variation_answer) ? true : false);
					$('#jawabanAlternatif').prop('checked', (soal.alternative_answer) ? true : false);

					// set data to input
					$('#soalFillTheBlank').val(soal.question);
					$('#jawaban').text(soal.answer);

					if (soal.question_file) {
						$('.image-place-holder').attr('src', ADMIN_URL + soal.question_file);
						
						// reset input image
						$('#image').val('');
					} else {
						$('.image-place-holder').attr('src', BASE_URL + 'assets/images/icons/tambahkan_gambar_pendukung.svg');
					}

					// set respon jawaban
					if(soal.response_correct_answer) {
						$('.input-jawaban-benar').val(soal.response_correct_answer);
					} else {
						$('.input-jawaban-benar').val('');
					}

					if(soal.response_wrong_answer) {
						$('.input-jawaban-salah').val(soal.response_wrong_answer);
					} else {
						$('.input-jawaban-salah').val('');
					}

					// set image jawaban
					if (soal.response_correct_answer_file) {
						$('.image-placeholder-jawaban-benar-essay').attr('src', ADMIN_URL + soal.response_correct_answer_file);
						$('input[name="image-jawaban-benar"]').val();
					} else {
						$('.image-placeholder-jawaban-benar-essay').attr('src', BASE_URL + 'assets/images/icons/tambahkan_gambar_pendukung.svg');
					}

					if (soal.response_wrong_answer_file) {
						$('.image-placeholder-jawaban-salah-essay').attr('src', ADMIN_URL + soal.response_wrong_answer_file);
						$('input[name="image-jawaban-salah"]').val();
					} else {
						$('.image-placeholder-jawaban-salah-essay').attr('src', BASE_URL + 'assets/images/icons/tambahkan_gambar_pendukung.svg');
					}

					// set jawaban alternatif
					$('.jawaban-alternatif-content').text(soal.alternative_answer);


					// show alternatif jawaban
					if (soal.alternative_answer) {
						$('.container-jawaban-alternatif').removeClass('d-none');
					} else {
						$('.container-jawaban-alternatif').addClass('d-none');
					}

					// show respon jawaban
					if (soal.response_correct_answer) {
						$('.btn-respon-jawaban').removeClass('d-none');
					} else {
						$('.btn-respon-jawaban').addClass('d-none');
					}


				}
			}
		});
	}
</script>
