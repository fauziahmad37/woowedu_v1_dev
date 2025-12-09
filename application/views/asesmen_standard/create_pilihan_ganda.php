<!-- <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet"> -->
<!-- tes ubah git -->
<!-- custom css -->
<style>

	html, body {
    height: auto !important;
    max-height: none;
    overflow-y: visible !important;
}


	.image-place-holder-pg {
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

	.card-multiple-choice .form-check-input {
		width: 24px !important;
		height: 24px !important;
	}

	.form-check-input:checked {
		background-color: #34C38F;
	}

	.input-jawaban {
		border-color: white;
		border-style: dashed;
		background-color: var(--bs-primary-300);
	}

  .swal2-popup .swal-wide-button {
	background-color:#281B93 !important;
        width: 100% !important;
    }
	.icon-faild {
		text-align:center;
		font-size:40px; 
		color:#f27474; 
		margin-bottom:10px;
	}
	/* .form-select {
		width: 120px;
	} */
</style>

<section class="header-options bg-white">
	<div class="container py-4">
		<div class="row">
			<div class="col-xl-6 col-lg-4 col-md-6 mb-2">
				<button class="btn-back btn btn-light border border-1 border-dark rounded-3 me-3">
					<i class="fa-solid fa-chevron-left"></i>
				</button>

				<button class="btn btn-light rounded-3" style="background-color: #E3E4E8;">
					<i class="fa-solid fa-list me-2"></i>
					Pilihan Ganda
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

				<button class="mb-2 btn btn-light rounded-3 btn-pengaturan" style="background-color: #E3E4E8;" data-bs-toggle="modal" data-bs-target="#pengaturanModal">
					<i class="fa fa-gear me-2"></i>
					Pengaturan Jawaban
				</button>

				<button class="mb-2 btn btn-primary rounded-3 ms-2 text-white simpan-jawaban-pg">
					Simpan Jawaban
				</button>
			</div>
		</div>
	</div>
</section>

<section class="main-content">

	<!-- main soal -->
	<div class="main-soal bg-primary container rounded-4 p-4" style="margin-top:40px;">

		<input type="hidden" id="pilihan_ganda_id" value="" />

		<div class="row">
			<div class="col-4">
				<div class="card-left bg-primary-300 border rounded-3">
					<!-- add image -->
					<div class="image-container-pg d-flex justify-content-center align-items-center" style="height: 100%;">
						<img class="image-place-holder-pg" src="<?= base_url('assets/images/icons/tambahkan_gambar_pendukung.svg') ?>" alt="image" class="rounded-3">
						<input type="file" accept="image/png, image/jpeg" name="input_image_question_pg" id="input_image_question_pg" style="display: none;">

						<!-- button delete & change image -->
						<div class="button-change-image d-flex justify-content-between mt-3">
							<button class="btn btn-light border-1 rounded-3 edit-pic-pg" style="width: 45%;"><i class="fas fa-edit"></i></button>
							<button class="btn btn-light border-1 rounded-3 delete-pic" style="width: 45%;"><i class="fas fa-trash"></i></button>
						</div>

					</div>
				</div>
			</div>

			<div class="col-8">
				<div class="card-left border bg-primary-300 rounded-3" style="border-color: #FFFFFF; height: 100%;">
					<input type="text" id="soalPilihanGanda" class="form-control border-none outline-none text-white input-question h-100 w-100" placeholder="Ketik pertanyaan soal di sini...">
				</div>
			</div>
		</div>

		<div class="container text-white rounded-2 pt-3" style="margin-top: 40px;">
			<div class="group-multiple-choice-card d-flex">
				<!-- multiple choice -->
				<div class="row card-multiple-choice" style="width: -webkit-fill-available;">
					<?php for ($i = 0; $i < 4; $i++) : ?>

						<div class="p-2 col rounded me-2 card-item-choice" style="background-color: rgba(255, 255, 255, 0.1);">

							<div class="button-group-choices">
								<button class="btn btn-light border-0 text-white me-2" onclick="deleteChoice(this)" style="background-color:rgba(255,255,255, 0.3);"><i class="fa fa-trash" aria-hidden="true"></i></button>
								<button class="btn btn-light border-0 text-white" onclick="addImageChoice(this)" style="background-color: rgba(255,255,255, 0.3);"><i class="fa-regular fa-image" aria-hidden="true"></i></button>
							</div>

							<input type="file" accept="image/png, image/jpeg" name="image-choice[]" style="display: none;">

							<div class="image-choice" style="max-height: 172px; object-fit: cover">
							</div>

							<div contenteditable="true" class="input-jawaban-pg rounded-2 border-1 mt-3 p-3" onclick="addChoiceText(this)" style="height: 200px;border-color: white;border-style: dashed;/* max-width: 200px; */">Ketik jawaban disini...</div>

							<!-- input checkbox true choice -->
							<div class="form-check my-3 d-flex justify-content-center">
								<input class="form-check-input" type="checkbox" value="" onclick="setTrueChoice(this)">
							</div>
						</div>

					<?php endfor; ?>
				</div>
				<div class="p-2 m-2 add-more-choice m-auto" style="">
					<div style="width:50px;">
						<button class="btn btn-light border-0 text-white" onclick="addMoreChoice(this)" style="background-color:rgba(255,255,255, 0.3);"><i class="fa fa-plus" aria-hidden="true"></i></button>
					</div>
				</div>
			</div>
		</div>

	</div>


	<!-- Respon jawaban -->
	<div class="respon-jawaban-container bg-primary container rounded-4 p-4 d-none" style="margin-top:40px;">
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
				<button class="btn btn-lg text-dark btn-simpan-respon-pg" style="background-color: rgba(255, 255, 255, 1);">
					Simpan Respon
				</button>

			</div>
		</div>

		<!-- respon jawaban benar -->
		<div class="row mt-4">
			<div class="col-4">
				<div class="bg-primary-300 container-image-jawaban-benar-pg rounded-3 border-1 d-flex justify-content-center align-items-center" style="min-height: 353px; border-style: dashed; border-color: #FFFFFF;">
					<img src="<?= base_url('assets/images/icons/tambahkan_gambar_pendukung.svg') ?>" alt="image" class="rounded-3 image-placeholder-jawaban-benar" style="width: 100%; height: 100%; cursor: pointer;">

					<!-- button delete & change image -->
					<div class="button-change-image-jawaban-benar d-flex justify-content-between mt-3">
						<button class="btn btn-light border-1 rounded-3 edit-pic-pg-jawaban-benar" style="width: 45%;"><i class="fas fa-edit"></i></button>
						<button class="btn btn-light border-1 rounded-3 delete-pic-jawaban-benar-pg" onclick="deleteImageJawabanBenarPG(this)" style="width: 45%;"><i class="fas fa-trash"></i></button>
					</div>
				</div>

				<!-- input image jawaban benar -->
				<input type="file" accept="image/png, image/jpeg" name="image-jawaban-benar" id="image-jawaban-benar-pg" style="display: none;">
			</div>
			<div class="col-8">
				<div class="card-right bg-primary-300 border rounded-3" style="height: 100%;">

					<input type="text" class="soal-content input-jawaban-benar-pg form-control border-none outline-none text-white input-question h-100 w-100" placeholder="Respon Jawaban Benar...">
				</div>
			</div>
		</div>

		<!-- response jawaban salah -->
		<div class="row mt-4">
			<div class="col-4">
				<div class="bg-primary-300 container-image-jawaban-salah-pg rounded-3 border-1 d-flex justify-content-center align-items-center" style="min-height: 353px; border-style: dashed; border-color: #FFFFFF;">
					<img src="<?= base_url('assets/images/icons/tambahkan_gambar_pendukung.svg') ?>" alt="image" class="rounded-3 image-placeholder-jawaban-salah" style="width: 100%; height: 100%; cursor: pointer;">

					<!-- button delete & change image -->
					<div class="button-change-image-jawaban-salah d-flex justify-content-between mt-3">
						<button class="btn btn-light border-1 rounded-3 edit-pic-pg-jawaban-salah" style="width: 45%;"><i class="fas fa-edit"></i></button>
						<button class="btn btn-light border-1 rounded-3 delete-pic-jawaban-salah-pg" onclick="deleteImageJawabanSalahPG(this)" style="width: 45%;"><i class="fas fa-trash"></i></button>
					</div>
				</div>

				<!-- input image jawaban salah -->
				<input type="file" accept="image/png, image/jpeg" name="image-jawaban-salah" id="image-jawaban-salah-pg" style="display: none;">
			</div>
			<div class="col-8">
				<div class="card-right bg-primary-300 border rounded-3" style="height: 100%;">

					<input type="text" class="soal-content input-jawaban-salah-pg form-control border-none outline-none text-white input-question h-100 w-100" placeholder="Respon Jawaban Salah...">
				</div>
			</div>
		</div>
	</div>

</section>

<!-- Modal Pengaturan -->
<div class="modal fade" id="pengaturanModal" tabindex="-1" aria-labelledby="pengaturanModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-primary text-white">
				<h1 class="modal-title fs-5" id="pengaturanModalLabel">Pengaturan Jawaban</h1>
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
							<input class="form-check-input" style="width: 40px;" type="checkbox" id="responJawaban">
							<label class="form-check-label" for="responJawaban"></label>
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
document.querySelector('.soal-pilihan-ganda-container').classList.remove('d-none');
document.body.style.overflow = 'hidden';
document.querySelector('.soal-pilihan-ganda-container').classList.add('d-none');
document.body.style.overflow = '';

// upload foto maks 2mb 

function validateFileInput(input) {
	const file = input.files[0];
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
			input.value = "";
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
			input.value = "";
		}
	}
}



// Untuk #input_image_question_pg
document.getElementById('input_image_question_pg').addEventListener('change', function () {
	validateFileInput(this);
});

// Untuk semua input jawaban
document.querySelectorAll('input[name="image-choice[]"]').forEach(function (input) {
	input.addEventListener('change', function () {
		validateFileInput(this);
	});
});

// end foto
</script>
