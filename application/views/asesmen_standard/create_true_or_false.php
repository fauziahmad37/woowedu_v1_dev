<!-- <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet"> -->

<!-- custom css -->
<style>
	.main-soal .card-left {
		height: 200px;
		background-color: rgba(255, 255, 255, 0.1);
		border-color: #FFFFFF;
	}

	.image-place-holder-tof {
		height: 100%;
		width: 100%;
		cursor: pointer;
	}

	.button-change-image,
	.button-change-image-jawaban-benar,
	.button-change-image-jawaban-salah {
		position: absolute;
		display: none !important;
	}

	.form-check-input {
		width: 24px;
		height: 24px;
	}

	.form-check-input:checked {
		background-color: #34C38F;
	}

	.input-jawaban {
		border-color: white;
		border-style: dashed;
		background-color: var(--bs-primary-300);
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
					Benar atau Salah
				</button>

				<span class="ms-3 btn" style="background-color: #E3E4E8; cursor: default;">
					Jumlah Soal (<span class="jumlah-soal-current">0</span>/<span class="jumlah-soal-active-max">10</span>)
				</span>
			</div>

			<!-- col-6 position right -->
			<div class="col-xl-6 col-lg-8 col-md-6 text-end">
				<button class="btn-respon-jawaban mb-2 btn btn-light border border-1 border-dark rounded-3 me-2 d-none" style="">
					<i class="far fa-lightbulb me-2"></i>
					Respon Jawaban
				</button>

				<button class="mb-2 btn btn-light rounded-3 btn-pengaturan" style="background-color: #E3E4E8;" data-bs-toggle="modal" data-bs-target="#pengaturanModalTrueOrFalse">
					<i class="fa fa-gear me-2"></i>
					Pengaturan Jawaban
				</button>

				<button class="mb-2 btn btn-primary rounded-3 ms-2 text-white simpan-jawaban-true-or-false">
					Simpan Jawaban
				</button>
			</div>
		</div>
	</div>
</section>

<section class="main-content">

	<!-- main soal -->
	<div class="main-soal bg-primary container rounded-4 p-4" style="margin-top:40px;">

		<input type="hidden" id="true_or_false_id" value="" />

		<!-- soal -->
		<div class="row">
			<!-- soal image -->
			<div class="col-4">
				<div class="card-left bg-primary-300 border rounded-3">
					<!-- add image -->
					<div class="image-container-tof d-flex justify-content-center align-items-center" style="height: 100%;">
						<img class="image-place-holder-tof" src="<?= base_url('assets/images/icons/tambahkan_gambar_pendukung.svg') ?>" alt="image" class="rounded-3">
						<input type="file" accept="image/png, image/jpeg" name="image" id="image" style="display: none;">

						<!-- button delete & change image -->
						<div class="button-change-image d-flex justify-content-between mt-3">
							<button class="btn btn-light border-1 rounded-3 edit-pic-tof" style="width: 45%;"><i class="fas fa-edit"></i></button>
							<button class="btn btn-light border-1 rounded-3 delete-pic" style="width: 45%;"><i class="fas fa-trash"></i></button>
						</div>

					</div>
				</div>
			</div>

			<!-- soal text -->
			<div class="col-8">
				<div class="card-left border bg-primary-300 rounded-3" style="border-color: #FFFFFF; height: 100%;">
					<input type="text" id="soalTrueOrFalse" class="form-control border-none outline-none text-white input-question h-100 w-100" placeholder="Ketik pertanyaan soal di sini..." />
				</div>
			</div>
		</div>

		<div class="container text-white rounded-2 pt-3" style="margin-top: 40px;">
			<div class="group-multiple-choice-card d-flex">
				<!-- multiple choice -->
				<div class="row card-choice-tof" style="width: -webkit-fill-available;">

					<div class="p-2 col rounded card-item-choice-tof me-2" style="background-color: rgba(255, 255, 255, 0.1);">

						<div class="input-jawaban bg-success rounded-2 border-1 mt-3 p-3 d-flex justify-content-center" style="height: 200px;border-color: white;border-style: dashed;">
							<p class="text-white text-center" style="align-self: center;">
								Pilihan Benar
							</p>
						</div>

						<!-- input checkbox true choice -->
						<div class="form-check my-3 d-flex justify-content-center">
							<input id="choice_true" class="form-check-input" type="checkbox" value="" onclick="setTrueChoice(this)" style="width: 24px !important; height: 24px !important;">
						</div>
					</div>

					<div class="p-2 col rounded card-item-choice-tof" style="background-color: rgba(255, 255, 255, 0.1);">

						<div class="input-jawaban bg-danger rounded-2 border-1 mt-3 p-3 d-flex justify-content-center" style="height: 200px;border-color: white;border-style: dashed;">
							<p class="text-white text-center" style="align-self: center;">
								Pilihan Salah
							</p>
						</div>

						<!-- input checkbox true choice -->
						<div class="form-check my-3 d-flex justify-content-center">
							<input id="choice_false" class="form-check-input" type="checkbox" value="" onclick="setTrueChoice(this)" style="width: 24px !important; height: 24px !important;">
						</div>
					</div>


				</div>
			</div>
		</div>

	</div>


	<!-- Respon jawaban -->
	<div class="respon-jawaban-container bg-primary container rounded-4 p-4 d-none" style="min-height: 600px; margin-top:40px;">
		<!-- Header respon jawaban -->
		<div class="row">
			<div class="col">
				<button class="btn btn-lg text-white btn-kembali-ke-soal" style="background-color: rgba(255, 255, 255, 0.4);">
					<i class="fa-solid fa-chevron-left"></i>
					Kembali ke soal
				</button>
			</div>
			<div class="col justify-content-end d-flex">
				<button class="btn btn-danger btn-lg text-white me-2 delete-respon-pg">
					<i class="fa-solid fa-trash"></i>
				</button>
				<button class="btn btn-lg text-dark btn-simpan-respon-tof" style="background-color: rgba(255, 255, 255, 1);">
					Simpan Respon
				</button>

			</div>
		</div>

		<!-- respon jawaban benar -->
		<div class="row mt-4">
			<div class="col-4">
				<div class="bg-primary-300 container-image-jawaban-benar-true-or-false rounded-3 border-1 d-flex justify-content-center align-items-center" style="min-height: 353px; border-style: dashed; border-color: #FFFFFF;">
					<img src="<?= base_url('assets/images/icons/tambahkan_gambar_pendukung.svg') ?>" alt="image" class="rounded-3 image-placeholder-jawaban-benar-tof" style="width: 100%; height: 100%; cursor: pointer;">

					<!-- button delete & change image -->
					<div class="button-change-image-jawaban-benar d-flex justify-content-between mt-3">
						<button class="btn btn-light border-1 rounded-3 edit-pic-jawaban-benar-tof" style="width: 45%;"><i class="fas fa-edit"></i></button>
						<button class="btn btn-light border-1 rounded-3 delete-pic-jawaban-benar-tof" onclick="deleteImageJawabanBenarTrueOrFalse()" style="width: 45%;"><i class="fas fa-trash"></i></button>
					</div>
				</div>

				<!-- input image jawaban benar -->
				<input type="file" accept="image/png, image/jpeg" name="image-jawaban-benar" id="image-jawaban-benar-true-or-false" style="display: none;">
			</div>
			<div class="col-8">
				<div class="card-right bg-primary-300 border rounded-3" style="height: 100%;">
					<input type="text" class="form-control border-none outline-none text-white input-question h-100 w-100 input-jawaban-benar-tof" placeholder="Respon Jawaban Benar..." />
				</div>
			</div>
		</div>

		<!-- response jawaban salah -->
		<div class="row mt-4">
			<div class="col-4">
				<div class="bg-primary-300 container-image-jawaban-salah-true-or-false rounded-3 border-1 d-flex justify-content-center align-items-center" style="min-height: 353px; border-style: dashed; border-color: #FFFFFF;">
					<img src="<?= base_url('assets/images/icons/tambahkan_gambar_pendukung.svg') ?>" alt="image" class="rounded-3 image-placeholder-jawaban-salah-tof" style="width: 100%; height: 100%; cursor: pointer;">

					<!-- button delete & change image -->
					<div class="button-change-image-jawaban-salah d-flex justify-content-between mt-3">
						<button class="btn btn-light border-1 rounded-3 edit-pic-jawaban-salah-tof" style="width: 45%;"><i class="fas fa-edit"></i></button>
						<button class="btn btn-light border-1 rounded-3 delete-pic-jawaban-salah-tof" onclick="deleteImageJawabanSalahTrueOrFalse()" style="width: 45%;"><i class="fas fa-trash"></i></button>
					</div>
				</div>

				<!-- input image jawaban salah -->
				<input type="file" accept="image/png, image/jpeg" name="image-jawaban-salah" id="image-jawaban-salah" style="display: none;">
			</div>
			<div class="col-8">
				<div class="card-right bg-primary-300 border rounded-3" style="height: 100%;">
					<input type="text" class="form-control border-none outline-none text-white input-question h-100 w-100 input-jawaban-salah-tof" placeholder="Respon Jawaban Salah..." />
				</div>
			</div>
		</div>
	</div>

</section>

<!-- Modal Pengaturan -->
<div class="modal fade" id="pengaturanModalTrueOrFalse" tabindex="-1" aria-labelledby="pengaturanModalTrueOrFalseLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-primary text-white">
				<h1 class="modal-title fs-5" id="pengaturanModalTrueOrFalseLabel">Pengaturan Jawaban</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
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

				<div class="row border-bottom pb-3 mt-3">
					<div class="col-6">
						<span>Respon Jawaban</span>
					</div>
					<div class="col-6 justify-content-end d-flex">
						<!-- toogle -->
						<div class="form-check form-switch">
							<input class="form-check-input" style="width: 40px;" type="checkbox" id="responJawabanTrueOrFalse">
							<label class="form-check-label" for="responJawabanTrueOrFalse"></label>
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
	$('.image-place-holder-tof').click(function() {
		$('#image').click();

		$('#image').change(function() {
			var file = $('#image')[0].files[0];
			var reader = new FileReader();
			reader.onload = function(e) {
				$('.image-place-holder-tof').attr('src', e.target.result);
			}
			reader.readAsDataURL(file);
		});


	});

	// image placeholder on hover
	$('.image-container-tof').mouseenter(function() {
		let image = $(this).find('.image-place-holder-tof').attr('src');
		if (image.includes('tambahkan_gambar_pendukung')) {
			console.log('tes tes tes');
			$('.button-change-image').css('display', 'none !important');
		} else {
			$('.button-change-image').css('display', 'flex');
			$('.button-change-image').css('z-index', '1000');
			$('.button-change-image').css('display', 'unset !important');

			$('.edit-pic-tof').css('display', 'unset');
			$('.delete-pic').css('display', 'unset');


			$('.image-place-holder-tof').css('opacity', '0.5');
		}
	});

	// mouse leave image container
	$('.image-container-tof').mouseleave(function() {
		$('.edit-pic-tof').css('display', 'none');
		$('.delete-pic').css('display', 'none');

		$('.image-place-holder-tof').css('opacity', '1');
	});

	// delete image
	$('.delete-pic').click(function() {
		$('.image-place-holder-tof').attr('src', '<?= base_url('assets/images/icons/tambahkan_gambar_pendukung.svg') ?>');
		$('.button-change-image').css('display', 'none !important');
	});

	// edit image
	$('.edit-pic-tof').click(function() {
		$('#image').click();

		$('#image').change(function() {
			var file = $('#image')[0].files[0];
			var reader = new FileReader();
			reader.onload = function(e) {
				$('.image-place-holder-tof').attr('src', e.target.result);
			}
			reader.readAsDataURL(file);
		});
	});

	// card-item-choice-tof di klik maka input check ter ceklis
	$('.card-item-choice-tof .input-jawaban').click(function() {
		$('.card-item-choice-tof .form-check-input').prop('checked', false);

		let input = $(this).next().find('input');
		input.prop('checked', true);
	});


	//  ======================================================================================================== //
	//  ========================================= Section Respon Jawaban  ====================================== //
	//  ======================================================================================================== //

	//  checkbox responJawabanTrueOrFalse change
	$('#responJawabanTrueOrFalse').change(function() {
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
		let responJawabanTrueOrFalseContainer = $('.respon-jawaban-container');

		mainSoal.addClass('d-none');
		responJawabanTrueOrFalseContainer.removeClass('d-none');
	});

	// btn-kembali-ke-soal click
	$('.btn-kembali-ke-soal').click(function() {
		let mainSoal = $('.main-soal');
		let responJawabanTrueOrFalseContainer = $('.respon-jawaban-container');

		mainSoal.removeClass('d-none');
		responJawabanTrueOrFalseContainer.addClass('d-none');
	});

	// input image jawaban benar
	$('.image-placeholder-jawaban-benar-tof').click(function() {

		$('#image-jawaban-benar-true-or-false').click();

		$('#image-jawaban-benar-true-or-false').change(function() {
			var file = $('#image-jawaban-benar-true-or-false')[0].files[0];
			var reader = new FileReader();
			reader.onload = function(e) {
				$('.container-image-jawaban-benar-true-or-false').find('img').attr('src', e.target.result);
			}
			reader.readAsDataURL(file);
		});

	});

	// image placeholder on hover
	$('.container-image-jawaban-benar-true-or-false').mouseenter(function() {
		let image = $(this).find('img').attr('src');

		// image opacity
		$('.container-image-jawaban-benar-true-or-false').css('opacity', '0.8');

		if (image.includes('tambahkan_gambar_pendukung')) {
			console.log('tes tes tes');
			$('.button-change-image-jawaban-benar').css('display', 'none !important');
		} else {
			$('.button-change-image-jawaban-benar').css('display', 'flex');
			$('.button-change-image-jawaban-benar').css('z-index', '1000');
			$('.button-change-image-jawaban-benar').css('display', 'unset !important');

			$('.edit-pic-jawaban-benar-tof').css('display', 'unset');
			$('.delete-pic-jawaban-benar-tof').css('display', 'unset');
		}
	});

	// mouse leave image container jawaban benar
	$('.container-image-jawaban-benar-true-or-false').mouseleave(function() {
		// image opacity
		$('.container-image-jawaban-benar-true-or-false').css('opacity', '1');

		$('.edit-pic-jawaban-benar-tof').css('display', 'none');
		$('.delete-pic-jawaban-benar-tof').css('display', 'none');
	});

	// input image jawaban salah
	$('.image-placeholder-jawaban-salah-tof').click(function() {

		$('#image-jawaban-salah').click();

		$('#image-jawaban-salah').change(function() {
			var file = $('#image-jawaban-salah')[0].files[0];
			var reader = new FileReader();
			reader.onload = function(e) {
				$('.container-image-jawaban-salah-true-or-false').find('img').attr('src', e.target.result);
			}
			reader.readAsDataURL(file);
		});

	});

	// image placeholder jawaban salah on hover
	$('.container-image-jawaban-salah-true-or-false').mouseenter(function() {
		let image = $(this).find('img').attr('src');

		// image opacity
		$('.container-image-jawaban-salah-true-or-false').css('opacity', '0.8');

		if (image.includes('tambahkan_gambar_pendukung')) {
			console.log('tes tes tes');
			$('.button-change-image-jawaban-salah').css('display', 'none !important');
		} else {
			$('.button-change-image-jawaban-salah').css('display', 'flex');
			$('.button-change-image-jawaban-salah').css('z-index', '1000');
			$('.button-change-image-jawaban-salah').css('display', 'unset !important');

			$('.edit-pic-jawaban-salah-tof').css('display', 'unset');
			$('.delete-pic-jawaban-salah-tof').css('display', 'unset');
		}
	});

	// mouse leave image container jawaban salah
	$('.container-image-jawaban-salah-true-or-false').mouseleave(function() {
		// image opacity
		$('.container-image-jawaban-salah-true-or-false').css('opacity', '1');

		$('.edit-pic-jawaban-salah-tof').css('display', 'none');
		$('.delete-pic-jawaban-salah-tof').css('display', 'none');
	});

	// edit image jawaban benar
	$('.edit-pic-jawaban-benar-tof').click(function() {
		$('.image-placeholder-jawaban-benar-tof').click();
	});

	// edit image jawaban salah
	$('.edit-pic-jawaban-salah-tof').click(function() {
		$('.image-placeholder-jawaban-salah-tof').click();
	});

	// hapus image jawaban benar
	function deleteImageJawabanBenarTrueOrFalse() {
		$('.image-placeholder-jawaban-benar-tof').attr('src', '<?= base_url('assets/images/icons/tambahkan_gambar_pendukung.svg') ?>');
		$('.button-change-image-jawaban-benar').css('display', 'none !important');
	}

	// hapus image jawaban salah
	function deleteImageJawabanSalahTrueOrFalse() {
		$('.image-placeholder-jawaban-salah-tof').attr('src', '<?= base_url('assets/images/icons/tambahkan_gambar_pendukung.svg') ?>');
		$('.button-change-image-jawaban-salah').css('display', 'none !important');
	}

	// button delete-respon-pg click
	$('.delete-respon-pg').on('click', function() {
		Swal.fire({
			title: 'Apakah Anda yakin?',
			text: "Anda tidak akan dapat mengembalikan ini!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#d33',
			cancelButtonColor: '#3085d6',
			confirmButtonText: 'Ya, hapus!',
			cancelButtonText: 'Batal'
		}).then((result) => {
			if (result.isConfirmed) {


				// ajax delete
				let pilihanGandaId = $('#true_or_false_id').val(); // get pilihan ganda id
				if (pilihanGandaId) { // jika pilihan ganda id tidak kosong maka hapus response jawaban

					let url = BASE_URL + 'Asesmen_standard/delete_response_jawaban_pilihan_ganda';
					$.ajax({
						type: "POST",
						url: url,
						data: {
							soal_id: $('#true_or_false_id').val()
						},
						dataType: "json",
						success: function(response) {
							if (response.success) {
								Swal.fire(
									'Dihapus!',
									'Respon jawaban berhasil dihapus.',
									'success'
								);
							} else {
								Swal.fire(
									'Gagal!',
									'Respon jawaban gagal dihapus.',
									'error'
								);
							}
						}
					});

				} else {
					Swal.fire(
						'Dihapus!',
						'Respon jawaban berhasil dihapus.',
						'success'
					);
				}


				$('.input-jawaban-benar-tof').val('');
				$('.input-jawaban-salah-tof').val('');

				$('.image-placeholder-jawaban-benar-tof').attr('src', BASE_URL + 'assets/images/icons/tambahkan_gambar_pendukung.svg');
				$('.image-placeholder-jawaban-salah-tof').attr('src', BASE_URL + 'assets/images/icons/tambahkan_gambar_pendukung.svg');


			}
		})
	});

	// btn-simpan-respon-tof click
	$('.btn-simpan-respon-tof').click(function() {
		alert('Simpan respon');

		$('.btn-kembali-ke-soal').click();
	});


	// ======================================================================================================== //
	// ================================================= Header =============================================== //
	// ======================================================================================================== //


	// button back click
	$('.btn-back').click(function() {
		// add display none soal-fill-the-blank-container
		$('.soal-true-or-false-container').addClass('d-none');
	});

	// button pengeturan click
	$('.btn-pengaturan').click(function() {
		$('#pengaturanModalTrueOrFalse').modal('show');

		//remove modal backdrop
		$('.modal-backdrop').remove();
	});

	// simpan jawaban
	$('.simpan-jawaban-true-or-false').click(function(e) {
		e.preventDefault();

		// AMBIL JUMLAH SOAL DAN LIMIT SOAL DARI HEADER, NANTI AKAN DI PAKAI UNTUK AUTO CLOSE KETIKA SOAL SUDAH MENCAPAI LIMIT
		let jumlahSoalCurrent = $(this).closest('.header-options').find('.jumlah-soal-current').text();
		jumlahSoalCurrent = parseInt(jumlahSoalCurrent);
		let limitSoalCurrent = $(this).closest('.header-options').find('.jumlah-soal-active-max').text();
		limitSoalCurrent = parseInt(limitSoalCurrent);

		let true_or_false_id = $('#true_or_false_id').val();
		let card = $(`.card[data="${activeCard}"]`); // get active card
		let listCardItem = card.find('.card').length;
		let totalSoal = $(`#total-soal${activeCard}`).text();

		let inputTrueChoice = $('#choice_true').is(':checked');
		let inputFalseChoice = $('#choice_false').is(':checked');

		// validation counter soal
		if (!true_or_false_id) { // jika bukan update soal maka validasi counter soal
			if (listCardItem >= totalSoal) {
				Swal.fire({
					icon: 'error',
					title: 'Gagal',
					text: 'Jumlah soal sudah mencapai batas maksimal',
				});
				return;
			}
		}

		// validation jika gambar dan text soal tidak di isi
		let imagePlaceHolderTof = $('.image-place-holder-tof').attr('src');
		let soal = $('#soalTrueOrFalse').val();
		if( imagePlaceHolderTof.includes('tambahkan_gambar_pendukung') && soal.includes('Ketik pertanyaan soal di sini...') ) {
			Swal.fire({
				icon: 'error',
				title: 'Gagal',
				text: 'Soal dan gambar soal harus diisi',
			});
			return;
		}

		// validation input true choice and false choice
		if (!inputTrueChoice && !inputFalseChoice) {
			Swal.fire({
				icon: 'error',
				title: 'Gagal',
				text: 'Pilihan benar dan salah harus diisi',
			});
			return;
		}

		let choiceAnswer = '';
		if (inputTrueChoice) {
			choiceAnswer = 'true';
		} else {
			choiceAnswer = 'false';
		}

		// ajax post
		let form = new FormData();

		let imageSoal = $('.image-place-holder-tof').attr('src');
		let removeImage = false;
		if (imageSoal.includes('tambahkan_gambar_pendukung')) {
			imageSoal = '';
			removeImage = true;
		} else {
			imageSoal = $('#image')[0].files[0];
			removeImage = false;
		}

		let mapel = $('#select-mapel').val();
		
		let image = imageSoal;
		let point = $('.soal-true-or-false-container #point').val();
		let type = jenisSoalActive;

		let imageJawabanBenar = $('#image-jawaban-benar-true-or-false')[0].files[0];
		let imageJawabanSalah = $('#image-jawaban-salah')[0].files[0];
		let responseJawabanBenar = $('.input-jawaban-benar-tof').val();
		let responseJawabanSalah = $('.input-jawaban-salah-tof').val();

		form.append('true_or_false_id', true_or_false_id);
		form.append('mapel', mapel);
		form.append('soal', soal);
		form.append('jawaban', choiceAnswer);
		form.append('image', image);
		form.append('removeImage', removeImage);
		form.append('imageJawabanBenar', imageJawabanBenar);
		form.append('imageJawabanSalah', imageJawabanSalah);
		form.append('responseJawabanBenar', responseJawabanBenar);
		form.append('responseJawabanSalah', responseJawabanSalah);
		form.append('point', point);
		form.append('type', type);
		form.append('csrf_token_name', $('meta[name="csrf_token"]').attr('content'));
		// form.append('multipleChoice', JSON.stringify(multipleChoice));

		let url = (true_or_false_id) ? BASE_URL + 'Asesmen_standard/update_true_or_false' : BASE_URL + 'Asesmen_standard/save_true_or_false';

		// loading

		Swal.fire({
			icon: 'info',
			title: 'Loading...',
			text: 'Mohon tunggu, data sedang diproses',
			allowOutsideClick: false,
			showConfirmButton: false,
			didOpen: () => {
				Swal.showLoading();
			}
		});
		// end loading
		$.ajax({
			url: url,
			type: 'POST',
			data: form,
			contentType: false,
			processData: false,
			success: function(res) {
				Swal.close();
				if (res.success) {
					Swal.fire({
						icon: 'success',
						title: 'Berhasil',
						text: res.message,
					});

					// reset csrf token
					$('meta[name="csrf_token"]').attr('content', res.csrf_token);

					// jika melakukan update soal maka tidak perlu update counter soal
					if (!true_or_false_id) {
						$(`#count-soal${activeCard}`).text(listCardItem + 1); // update counter soal

						// update jumlah soal current card
						$('.jumlah-soal-current').text(listCardItem + 1);
					}

					// add item to list soal
					let soal = res.data;
					let item = `
						<div class="card border-start-0 border-top-0 border-end-0 mb-3 rounded-0 mt-5">

							<div class="card-header bg-white border-0 p-0">
								<div class="d-flex justify-content-between">
									<div class="btn btn-light border rounded-3 no-soal" style="width: 100px;">
										${(true_or_false_id) ? questionNumberActive : listCardItem+1}
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
										
										<button class="btn btn-light border rounded-3 me-2" onclick="editTrueOrFalse(this)">
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

									
									</div>
								</div>
							</div>

						</div>
					`;

					// hide empty image
					$(`.card[data="${activeCard}"]`).find('.empty-soal-image').addClass('d-none');

					// jika melakukan update soal maka tidak perlu append item
					if (!true_or_false_id) {
						$(`.card[data="${activeCard}"]`).find('.list-soal-container').append(item);
					}

					if (true_or_false_id) {
						$(`.card[data="${activeCard}"]`).find('.list-soal-container').find(`input[value="${true_or_false_id}"]`).closest('.card').replaceWith(item);
					}

					// clear input
					$('#soalTrueOrFalse').val('');
					$('.image-place-holder-tof').attr('src', BASE_URL + 'assets/images/icons/tambahkan_gambar_pendukung.svg');

					// unchek choice_true and choice_false
					$('#choice_true').prop('checked', false);
					$('#choice_false').prop('checked', false);

					// clear respon jawaban
					$('.input-jawaban-benar-tof').val('');
					$('.input-jawaban-salah-tof').val('');

					// clear image jawaban
					$('.image-placeholder-jawaban-benar-tof').attr('src', BASE_URL + 'assets/images/icons/tambahkan_gambar_pendukung.svg');
					$('.image-placeholder-jawaban-salah-tof').attr('src', BASE_URL + 'assets/images/icons/tambahkan_gambar_pendukung.svg');

					// KETIKA JUMLAH SOAL SUDAH SAMA DENGAN LIMIT SOAL MAKA TUTUP MODAL
					if (jumlahSoalCurrent+1 >= limitSoalCurrent) {
						setTimeout(()=>{$('.btn-back').click()}, 2000);
					}

				} else {
					// reset token
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

	// Edit soal pilihan ganda
	function editTrueOrFalse(el) {

		// cursor move to top
		$('html, body').animate({
			scrollTop: 0
		}, 500);

		// set jenis soal active
		jenisSoalActive = $(el).closest('.card-group-custom').attr('jenis-soal');
		activeCard = $(el).closest('.card-group-custom').attr('data');

		// set active question number active
		questionNumberActive = $(el).closest('.card').find('.no-soal').text();

		// Get Counter Soal
		let countSoal = $(`#count-soal${activeCard}`).text();
		// set header options counter soal
		$('.jumlah-soal-current').text(countSoal);

		$('.soal-true-or-false-container').removeClass('d-none');

		let card = $(el).closest('.card');
		let questionId = card.find('input').val();

		// set value #true_or_false_id
		$('#true_or_false_id').val(questionId);

		// unchecked pengaturan jawaban - respon jawaban
		$('.btn-respon-jawaban').addClass('d-none');
		$('#responJawabanTrueOrFalse').prop('checked', false);

		// reset image response jawaban benar & salah
		$('.image-placeholder-jawaban-benar-tof').attr('src', BASE_URL + 'assets/images/icons/tambahkan_gambar_pendukung.svg');
		$('.image-placeholder-jawaban-salah-tof').attr('src', BASE_URL + 'assets/images/icons/tambahkan_gambar_pendukung.svg');

		// reset input text response jawaban benar & salah
		$('.input-jawaban-benar-true-or-false').text('');
		$('.input-jawaban-salah-true-or-false').text('');

		// reset image soal
		$('.image-place-holder-tof').attr('src', BASE_URL + 'assets/images/icons/tambahkan_gambar_pendukung.svg');



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

					// set data to input
					$('#soalTrueOrFalse').val(soal.question);

					if (soal.question_file) {
						$('.image-place-holder-tof').attr('src', ADMIN_URL + soal.question_file);

						// reset input image
						$('#image').val('');
					}

					// set respon jawaban
					$('.input-jawaban-benar-tof').val(soal.response_correct_answer);
					$('.input-jawaban-salah-tof').val(soal.response_wrong_answer);

					// set image jawaban
					if (soal.response_correct_answer_file) {
						$('.image-placeholder-jawaban-benar-tof').attr('src', ADMIN_URL + soal.response_correct_answer_file);
						$('input[name="image-jawaban-benar"]').val();
					}

					if (soal.response_wrong_answer_file) {
						$('.image-placeholder-jawaban-salah-tof').attr('src', ADMIN_URL + soal.response_wrong_answer_file);
						$('input[name="image-jawaban-salah"]').val();
					}

					// set pengaturan jawaban
					$('#point').val(soal.point);

					// set respon jawaban pengaturan
					if (soal.response_correct_answer || soal.response_correct_answer_file) {
						$('#responJawabanTrueOrFalse').prop('checked', true);
						$('.btn-respon-jawaban').removeClass('d-none');
					} else {
						$('#responJawabanTrueOrFalse').prop('checked', false);
					}

					// set pilihan ganda
					const cardItem = (choice, choiceImage, isAnswer) => {
						let item = `
							<div class="p-2 col rounded me-2 card-item-choice-tof" style="background-color: rgba(255, 255, 255, 0.1);">

								<div class="input-jawaban rounded-2 border-1 mt-3 p-3" style="min-height: 50px;border-color: white;border-style: dashed;/* max-width: 200px; */">${choice}</div>

								<!-- input checkbox true choice -->
								<div class="form-check my-3 d-flex justify-content-center">
									<input class="form-check-input" type="checkbox" value="" onclick="setTrueChoice(this)" ${isAnswer ? 'checked' : ''}>
								</div>
							
							</div>
						`;

						return item;
					}

					$('.group-multiple-choice-card').removeClass('d-none');
					$('.card-choice-tof').html(`
						<div class="p-2 col rounded card-item-choice-tof me-2" style="background-color: rgba(255, 255, 255, 0.1);">

							<div class="input-jawaban bg-success rounded-2 border-1 mt-3 p-3 d-flex justify-content-center" style="height: 200px;border-color: white;border-style: dashed;">
								<p class="text-white text-center" style="align-self: center;">
									Pilihan Benar
								</p>
							</div>

							<!-- input checkbox true choice -->
							<div class="form-check my-3 d-flex justify-content-center">
								<input id="choice_true" class="form-check-input" type="checkbox" value="" onclick="setTrueChoice(this)" ${(soal.answer == 'true') ? 'checked' : ''} style="width: 24px !important;">
							</div>
						</div>

						<div class="p-2 col rounded card-item-choice-tof" style="background-color: rgba(255, 255, 255, 0.1);">

							<div class="input-jawaban bg-danger rounded-2 border-1 mt-3 p-3 d-flex justify-content-center" style="height: 200px;border-color: white;border-style: dashed;">
								<p class="text-white text-center" style="align-self: center;">
									Pilihan Salah
								</p>
							</div>

							<!-- input checkbox true choice -->
							<div class="form-check my-3 d-flex justify-content-center">
								<input id="choice_false" class="form-check-input" type="checkbox" value="" onclick="setTrueChoice(this)" ${(soal.answer == 'false') ? 'checked' : ''} style="width: 24px !important;">
							</div>
						</div>
					`);

					// show respon jawaban
					if (soal.response_correct_answer) {
						$('.btn-respon-jawaban').removeClass('d-none');
					}


				}
			}
		});
	}

	// klik form-check-input jawaban benar
	function setTrueChoice(el) {
		// reset all form-check-input
		$('.form-check-input').prop('checked', false);

		// set form-check-input checked
		$(el).prop('checked', true);
	}



	// untuk foto max 2 mb

document.getElementById('image').addEventListener('change', function () {
	const file = this.files[0];
	const allowedTypes = [
		'image/jpeg', 'image/png',
		'application/pdf',
		'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
		'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
		'application/vnd.ms-powerpoint',
		'audio/mpeg',
		'video/mp4'
	];

	if (file) {
		if (!allowedTypes.includes(file.type)) {
			Swal.fire({
				html: `
					<div style="text-align: center;">
						<div class="icon-faild" style="font-size: 40px; color: red;">&#10060;</div>
						<h2 style="margin:0; font-size:1.4em;">Tipe File Tidak Didukung</h2>
						<p style="margin-top:8px;">Hanya file JPG, JPEG, PNG, DOCX, XLSX, PPT, PDF, MP3, dan MP4 yang diperbolehkan.</p>
					</div>
				`,
				showCloseButton: false,
				showConfirmButton: true,
				confirmButtonText: 'Upload Ulang',
				customClass: {
					confirmButton: 'swal-wide-button'
				}
			});
			this.value = "";
			return;
		}

		const maxSize = 2 * 1024 * 1024; 
		if (file.size > maxSize) {
			Swal.fire({
				html: `
					<div style="text-align: center;">
						<div class="icon-faild" style="font-size: 40px; color: red;">&#10060;</div>
						<h2 style="margin:0; font-size:1.4em;">Ukuran File Terlalu Besar</h2>
						<p style="margin-top:8px;">Ukuran file melebihi 2MB. Silakan pilih file yang lebih kecil atau gunakan tautan.</p>
					</div>
				`,
				showCloseButton: false,
				showConfirmButton: true,
				confirmButtonText: 'Upload Ulang',
				customClass: {
					confirmButton: 'swal-wide-button'
				}
			});
			this.value = "";
		}
	}
});

	// end max 2 mb


</script>
