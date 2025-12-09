// input image with placeholder
$('.image-place-holder-pg').click(function () {
	$('#input_image_question_pg').click();

	$('#input_image_question_pg').change(function () {
		var file = $('#input_image_question_pg')[0].files[0];
		var reader = new FileReader();
		reader.onload = function (e) {
			$('.image-place-holder-pg').attr('src', e.target.result);
		}
		reader.readAsDataURL(file);
	});


});

// image placeholder on hover
$('.image-container-pg').mouseenter(function () {
	let image = $(this).find('.image-place-holder-pg').attr('src');
	if (image.includes('tambahkan_gambar_pendukung')) {
		console.log('tes tes tes');
		$('.button-change-image').css('display', 'none !important');
	} else {
		$('.button-change-image').css('display', 'flex');
		$('.button-change-image').css('z-index', '1000');
		$('.button-change-image').css('display', 'unset !important');

		$('.edit-pic-pg').css('display', 'unset');
		$('.delete-pic').css('display', 'unset');


		$('.image-place-holder-pg').css('opacity', '0.5');
	}
});

// mouse leave image container
$('.image-container-pg').mouseleave(function () {
	$('.edit-pic-pg').css('display', 'none');
	$('.delete-pic').css('display', 'none');

	$('.image-place-holder-pg').css('opacity', '1');
});

// delete image
$('.delete-pic').click(function () {
	$('.image-place-holder-pg').attr('src', BASE_URL + 'assets/images/icons/tambahkan_gambar_pendukung.svg');
	$('.button-change-image').css('display', 'none !important');
});

// edit image
$('.edit-pic-pg').click(function () {
	$('#image').click();

	$('#image').change(function () {
		var file = $('#image')[0].files[0];
		var reader = new FileReader();
		reader.onload = function (e) {
			$('.image-place-holder-pg').attr('src', e.target.result);
		}
		reader.readAsDataURL(file);
	});
});

// input text jawaban pilihan ganda on blur
$('.input-jawaban-pg').on('blur', function (e) {
	if ($(this).text() == '') {
		$(this).text('Ketik jawaban disini...');
	}
});


//  ======================================================================================================== //
//  ========================================= Section Respon Jawaban  ====================================== //
//  ======================================================================================================== //

//  checkbox responJawaban change
$('#responJawaban').change(function () {
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
$('.btn-respon-jawaban').click(function () {
	let mainSoal = $('.main-soal');
	let responJawabanContainer = $('.respon-jawaban-container');

	mainSoal.addClass('d-none');
	responJawabanContainer.removeClass('d-none');
});

// btn-kembali-ke-soal click
$('.btn-kembali-ke-soal').click(function () {
	let mainSoal = $('.main-soal');
	let responJawabanContainer = $('.respon-jawaban-container');

	mainSoal.removeClass('d-none');
	responJawabanContainer.addClass('d-none');
});

// input image jawaban benar
$('.image-placeholder-jawaban-benar').click(function () {

	$('#image-jawaban-benar-pg').click();

	$('#image-jawaban-benar-pg').change(function () {
		var file = $('#image-jawaban-benar-pg')[0].files[0];
		var reader = new FileReader();
		reader.onload = function (e) {
			$('.container-image-jawaban-benar-pg').find('img').attr('src', e.target.result);
		}
		reader.readAsDataURL(file);
	});

});

// image placeholder on hover
$('.container-image-jawaban-benar-pg').mouseenter(function () {
	let image = $(this).find('img').attr('src');

	// image opacity
	$('.container-image-jawaban-benar-pg').css('opacity', '0.8');

	if (image.includes('tambahkan_gambar_pendukung')) {
		console.log('tes tes tes');
		$('.button-change-image-jawaban-benar').css('display', 'none !important');
	} else {
		$('.button-change-image-jawaban-benar').css('display', 'flex');
		$('.button-change-image-jawaban-benar').css('z-index', '1000');
		$('.button-change-image-jawaban-benar').css('display', 'unset !important');

		$('.edit-pic-pg-jawaban-benar').css('display', 'unset');
		$('.delete-pic-jawaban-benar-pg').css('display', 'unset');
	}
});

// mouse leave image container jawaban benar
$('.container-image-jawaban-benar-pg').mouseleave(function () {
	// image opacity
	$('.container-image-jawaban-benar-pg').css('opacity', '1');

	$('.edit-pic-pg-jawaban-benar').css('display', 'none');
	$('.delete-pic-jawaban-benar-pg').css('display', 'none');
});

// input image jawaban salah
$('.image-placeholder-jawaban-salah').click(function () {

	$('#image-jawaban-salah-pg').click();

	$('#image-jawaban-salah-pg').change(function () {
		var file = $('#image-jawaban-salah-pg')[0].files[0];
		var reader = new FileReader();
		reader.onload = function (e) {
			$('.container-image-jawaban-salah-pg').find('img').attr('src', e.target.result);
		}
		reader.readAsDataURL(file);
	});

});

// image placeholder jawaban salah on hover
$('.container-image-jawaban-salah-pg').mouseenter(function () {
	let image = $(this).find('img').attr('src');

	// image opacity
	$('.container-image-jawaban-salah-pg').css('opacity', '0.8');

	if (image.includes('tambahkan_gambar_pendukung')) {
		console.log('tes tes tes');
		$('.button-change-image-jawaban-salah').css('display', 'none !important');
	} else {
		$('.button-change-image-jawaban-salah').css('display', 'flex');
		$('.button-change-image-jawaban-salah').css('z-index', '1000');
		$('.button-change-image-jawaban-salah').css('display', 'unset !important');

		$('.edit-pic-pg-jawaban-salah').css('display', 'unset');
		$('.delete-pic-jawaban-salah-pg').css('display', 'unset');
	}
});

// mouse leave image container jawaban salah
$('.container-image-jawaban-salah-pg').mouseleave(function () {
	// image opacity
	$('.container-image-jawaban-salah-pg').css('opacity', '1');

	$('.edit-pic-pg-jawaban-salah').css('display', 'none');
	$('.delete-pic-jawaban-salah-pg').css('display', 'none');
});

// Edit image jawaban benar
$('.edit-pic-pg-jawaban-benar').click(function () {
	$('#image-jawaban-benar-pg').click();
});

// Edit image jawaban salah
$('.edit-pic-pg-jawaban-salah').click(function () {
	$('#image-jawaban-salah-pg').click();
});

// input-jawaban-benar-pg click
// $('.input-jawaban-benar-pg').click(function() {
// 	if($(this).text() == 'Respon Jawaban Benar...') {
// 		$(this).text('');
// 	}
// });

// input-jawaban-salah-pg click
// $('.input-jawaban-salah-pg').click(function() {
// 	if($(this).text() == 'Respon Jawaban Salah...') {
// 		$(this).text('');
// 	}
// });

// input-jawaban-benar-pg on blur
// $('.input-jawaban-benar-pg').on('blur', function(e) {
// 	if ($(this).text() == '') {
// 		$(this).text('Respon Jawaban Benar...');
// 	}
// });

// input-jawaban-salah-pg on blur
// $('.input-jawaban-salah-pg').on('blur', function(e) {
// 	if ($(this).text() == '') {
// 		$(this).text('Respon Jawaban Salah...');
// 	}
// });

// hapus image jawaban benar
function deleteImageJawabanBenarPG(e) {
	$('.image-placeholder-jawaban-benar').attr('src', BASE_URL + 'assets/images/icons/tambahkan_gambar_pendukung.svg');
	$('.button-change-image-jawaban-benar').css('display', 'none !important');
}

// hapus image jawaban salah
function deleteImageJawabanSalahPG(e) {
	$('.image-placeholder-jawaban-salah').attr('src', BASE_URL + 'assets/images/icons/tambahkan_gambar_pendukung.svg');
	$('.button-change-image-jawaban-salah').css('display', 'none !important');
}

// button delete-respon-pg click
$('.delete-respon-pg').on('click', function () {
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
			let pilihanGandaId = $('#pilihan_ganda_id').val(); // get pilihan ganda id
			if (pilihanGandaId) { // jika pilihan ganda id tidak kosong maka hapus response jawaban

				let url = BASE_URL + 'Asesmen_standard/delete_response_jawaban_pilihan_ganda';
				$.ajax({
					type: "POST",
					url: url,
					data: {
						soal_id: $('#pilihan_ganda_id').val()
					},
					dataType: "json",
					success: function (response) {
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

			$('.input-jawaban-benar-pg').val('');
			$('.input-jawaban-salah-pg').val('');

			$('.image-placeholder-jawaban-benar').attr('src', BASE_URL + 'assets/images/icons/tambahkan_gambar_pendukung.svg');
			$('.image-placeholder-jawaban-salah').attr('src', BASE_URL + 'assets/images/icons/tambahkan_gambar_pendukung.svg');

			$('#image-jawaban-benar-pg').val('');
			$('#image-jawaban-salah-pg').val('');			

		}
	})
});

// btn-simpan-respon-pg click
$('.btn-simpan-respon-pg').click(function () {
	alert('Simpan respon');

	$('.btn-kembali-ke-soal').click();
});


// ======================================================================================================== //
// ================================================= Header =============================================== //
// ======================================================================================================== //


// button back click
$('.btn-back').click(function () {
	// add display none soal-fill-the-blank-container
	$('.soal-pilihan-ganda-container').addClass('d-none');
});

// button pengeturan click
$('.btn-pengaturan').click(function () {
	$('#pengaturanModal').modal('show');

	//remove modal backdrop
	$('.modal-backdrop').remove();
});

// simpan jawaban
$('.simpan-jawaban-pg').click(function (e) {
	e.preventDefault();

	let pilihan_ganda_id = $('#pilihan_ganda_id').val();
	let card = $(`.card[data="${activeCard}"]`); // get active card
	let listCardItem = card.find('.card').length;
	let totalSoal = $(`#total-soal${activeCard}`).text();

	// validation counter soal
	if (!pilihan_ganda_id) { // jika bukan update soal maka validasi counter soal
		if (listCardItem >= totalSoal) {
			Swal.fire({
				icon: 'error',
				title: 'Gagal',
				text: 'Jumlah soal sudah mencapai batas maksimal',
			});
			return;
		}
	}

	// validasi jika gambar soal dan text soal masih kosong
	let imageContainerPg = $('.image-place-holder-pg').attr('src');
	let soal = $('#soalPilihanGanda').val();
	if (imageContainerPg.includes('tambahkan_gambar_pendukung') && !soal) {
		Swal.fire({
			icon: 'error',
			title: 'Gagal',
			text: 'Gambar soal dan text soal tidak boleh kosong',
		});
		return;
	}

	// validasi jika pilihan gambar atau text jawaban masih kosong
	let cardItemChoice = $('.card-multiple-choice .card-item-choice');
	let choiceKosong = false;
	cardItemChoice.each(function () {
		let imageChoice = $(this).find('img').attr('src');
		let textChoice = $(this).find('.input-jawaban-pg').text();
		if (!imageChoice && textChoice == 'Ketik jawaban disini...') {
			Swal.fire({
				icon: 'error',
				title: 'Gagal',
				text: 'Gambar pilihan dan text pilihan tidak boleh kosong',
			});
			choiceKosong = true;
		}
	});
	if (choiceKosong) {
		return;
	}

	// validasi jika checkbox true choice tidak ada yang terpilih
	let trueChoice = false;
	$('.card-item-choice').each(function () {
		let checkbox = $(this).find('.form-check-input').is(':checked');
		if (checkbox) {
			trueChoice = true;
		}
	});

	if (!trueChoice) {
		Swal.fire({
			icon: 'error',
			title: 'Gagal',
			text: 'Pilih salah satu jawaban yang benar',
		});
		return;
	}

	// ajax post
	let form = new FormData();

	let imageSoal = $('.image-place-holder-pg').attr('src');
	let removeImage = false;
	if (imageSoal.includes('tambahkan_gambar_pendukung')) {
		imageSoal = '';
		removeImage = true;
	} else {
		imageSoal = $('#input_image_question_pg')[0].files[0];
		removeImage = false;
	}

	let mapel = $('#select-mapel').val();
	let jawaban = $('#jawaban').text();
	let imageJawabanBenar = $('#image-jawaban-benar-pg')[0].files[0];
	let imageJawabanSalah = $('#image-jawaban-salah-pg')[0].files[0];
	let responseJawabanBenar = $('.input-jawaban-benar-pg').val();
	let responseJawabanSalah = $('.input-jawaban-salah-pg').val();
	let point = $('.soal-pilihan-ganda-container #point').val();
	let type = jenisSoalActive;

	let multipleChoice = [];
	$('.card-item-choice').each(function () {
		let choice = $(this).find('.input-jawaban-pg').text();
		if (choice == 'Ketik jawaban disini...') {
			choice = '';
		}

		let image = $(this).find('img').attr('src');
		let trueChoice = $(this).find('.form-check-input').is(':checked');

		multipleChoice.push({
			choice: choice,
			image: image,
			trueChoice: trueChoice
		});
	});

	form.append('pilihan_ganda_id', pilihan_ganda_id);
	form.append('mapel', mapel);
	form.append('soal', soal);
	form.append('jawaban', jawaban);
	form.append('image', imageSoal);
	form.append('removeImage', removeImage);
	form.append('imageJawabanBenar', imageJawabanBenar);
	form.append('imageJawabanSalah', imageJawabanSalah);
	form.append('responseJawabanBenar', responseJawabanBenar);
	form.append('responseJawabanSalah', responseJawabanSalah);
	form.append('point', point);
	form.append('type', type);
	form.append('multipleChoice', JSON.stringify(multipleChoice));
	form.append('csrf_token_name', $('meta[name="csrf_token"]').attr('content'));

	let url = (pilihan_ganda_id) ? BASE_URL + 'Asesmen_standard/update_pilihan_ganda' : BASE_URL + 'Asesmen_standard/save_pilihan_ganda';

	$.ajax({
		url: url,
		type: 'POST',
		data: form,
		contentType: false,
		processData: false,
		success: function (res) {

			if (res.success) {
				Swal.fire({
					icon: 'success',
					title: 'Berhasil',
					text: res.message,
				});

				// jika melakukan update soal maka tidak perlu update counter soal
				if (!pilihan_ganda_id) {
					$(`#count-soal${activeCard}`).text(listCardItem + 1); // update counter soal
				}

				// add item to list soal
				let soal = res.data;
				let item = `
					<div class="card border-start-0 border-top-0 border-end-0 mb-3 rounded-0 mt-5">

						<div class="card-header bg-white border-0 p-0">
							<div class="d-flex justify-content-between">
								<div class="btn btn-light border rounded-3 no-soal" style="width: 100px;">
									${(pilihan_ganda_id) ? questionNumberActive : listCardItem + 1}
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
									
									<button class="btn btn-light border rounded-3 me-2" onclick="editPilihanGanda(this)">
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

									${soal.type == 1 ? soalPilihanGanda(soal) : ''}
								</div>
							</div>
						</div>

					</div>
				`;

				// hide empty image
				$(`.card[data="${activeCard}"]`).find('.empty-soal-image').addClass('d-none');

				// jika melakukan update soal maka tidak perlu append item
				if (!pilihan_ganda_id) {
					$(`.card[data="${activeCard}"]`).find('.list-soal-container').append(item);
				}

				if (pilihan_ganda_id) {
					$(`.card[data="${activeCard}"]`).find('.list-soal-container').find(`input[value="${pilihan_ganda_id}"]`).closest('.card').replaceWith(item);
				}

				// clear input
				$('#soalPilihanGanda').val('');
				$('#jawaban').text('');
				$('.image-place-holder-pg').attr('src', BASE_URL + 'assets/images/icons/tambahkan_gambar_pendukung.svg');

				// clear input image choice
				$('.card-item-choice').each(function () {
					$(this).find('.input-jawaban-pg').text('Ketik jawaban disini...');
					$(this).find('.input-jawaban-pg').css('height', '200px');
					$(this).find('.image-choice').html('');
					$(this).find('.form-check-input').prop('checked', false);
				});

				// clear respon jawaban
				$('.input-jawaban-benar-pg').val('');
				$('.input-jawaban-salah-pg').val('');

				// clear image jawaban
				$('.image-placeholder-jawaban-benar').attr('src', BASE_URL + 'assets/images/icons/tambahkan_gambar_pendukung.svg');
				$('.image-placeholder-jawaban-salah').attr('src', BASE_URL + 'assets/images/icons/tambahkan_gambar_pendukung.svg');

				// update csrf token
				$('meta[name="csrf_token"]').attr('content', res.csrf_token);

			} else {
				Swal.fire({
					icon: 'error',
					title: 'Gagal',
					text: res.message,
				});

				// update csrf token
				$('meta[name="csrf_token"]').attr('content', res.csrf_token);
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
function editPilihanGanda(el) {

	// cursor move to top
	$('html, body').animate({
		scrollTop: 0
	}, 500);

	jenisSoalActive = 1; // set jenis soal active
	activeCard = $(el).closest('.card-group-custom').attr('data'); // set active card

	// set active question number active
	questionNumberActive = $(el).closest('.card').find('.no-soal').text();

	$('.soal-pilihan-ganda-container').removeClass('d-none');

	let card = $(el).closest('.card');
	let questionId = card.find('input').val();

	// set value #pilihan_ganda_id
	$('#pilihan_ganda_id').val(questionId);

	// unchecked pengaturan jawaban - respon jawaban
	$('.btn-respon-jawaban').addClass('d-none');
	$('#responJawaban').prop('checked', false);

	// reset image response jawaban benar & salah
	$('.image-placeholder-jawaban-benar').attr('src', BASE_URL + 'assets/images/icons/tambahkan_gambar_pendukung.svg');
	$('.image-placeholder-jawaban-salah').attr('src', BASE_URL + 'assets/images/icons/tambahkan_gambar_pendukung.svg');

	// reset input text response jawaban benar & salah
	$('.input-jawaban-benar-pg').val('');
	$('.input-jawaban-salah-pg').val('');

	// reset image soal
	$('.image-place-holder-pg').attr('src', BASE_URL + 'assets/images/icons/tambahkan_gambar_pendukung.svg');



	// get data soal
	$.ajax({
		url: BASE_URL + 'Asesmen_standard/get_question_fill_the_blank',
		type: 'GET',
		data: {
		question_id: questionId
	},
		success: function (res) {
			if (res.success) {
				let soal = res.data;

				// set data to input
				$('#soalPilihanGanda').val(soal.question);
				$('#jawaban').text(soal.answer);

				if (soal.question_file) {
					$('.image-place-holder-pg').attr('src', ADMIN_URL + soal.question_file);

					// reset input image
					$('#image').val('');
				}

				// set respon jawaban
				$('.input-jawaban-benar-pg').val(soal.response_correct_answer);
				$('.input-jawaban-salah-pg').val(soal.response_wrong_answer);

				// set image jawaban
				if (soal.response_correct_answer_file) {
					$('.image-placeholder-jawaban-benar').attr('src', ADMIN_URL + soal.response_correct_answer_file);
					$('input[name="image-jawaban-benar"]').val();
				}

				if (soal.response_wrong_answer_file) {
					$('.image-placeholder-jawaban-salah').attr('src', ADMIN_URL + soal.response_wrong_answer_file);
					$('input[name="image-jawaban-salah"]').val();
				}

				// set pengaturan jawaban
				$('#point').val(soal.point);

				// set respon jawaban
				if (soal.response_correct_answer || soal.response_correct_answer_file) {
					$('#responJawaban').prop('checked', true);
					$('.btn-respon-jawaban').removeClass('d-none');
				} else {
					$('#responJawaban').prop('checked', false);
				}

				// set pilihan ganda
				const cardItem = (choice, choiceImage, isAnswer) => {
					let item = `
						<div class="p-2 col rounded me-2 card-item-choice" style="background-color: rgba(255, 255, 255, 0.1);">
							<div class="button-group-choices">
								<button class="btn btn-light border-0 text-white me-2" onclick="deleteChoice(this)" style="background-color:rgba(255,255,255, 0.3);"><i class="fa fa-trash" aria-hidden="true"></i></button>
								<button class="btn btn-light border-0 text-white" onclick="addImageChoice(this)" style="background-color: rgba(255,255,255, 0.3);"><i class="fa-regular fa-image" aria-hidden="true"></i></button>
							</div>
							
							<input type="file" accept="image/png, image/jpeg" name="image-choice[]" style="display: none;">

							<div class="image-choice" style="max-height: 172px; object-fit: cover">
								${(choiceImage) ? `<img src="${ADMIN_URL + choiceImage}" class="img-fluid rounded mt-2" alt="Gambar Jawaban" style="height: 170px; width: -webkit-fill-available;">` : ''}
							</div>

							<div contenteditable="true" class="input-jawaban-pg rounded-2 border-1 mt-3 p-3" onclick="addChoiceText(this)" style="min-height: 50px;border-color: white;border-style: dashed;/* max-width: 200px; */">${choice}</div>

							<!-- input checkbox true choice -->
							<div class="form-check my-3 d-flex justify-content-center">
								<input class="form-check-input" type="checkbox" value="" onclick="setTrueChoice(this)" ${isAnswer ? 'checked' : ''}>
							</div>
						
						</div>
					`;

					return item;
				}

				$('.group-multiple-choice-card').removeClass('d-none');
				$('.card-multiple-choice').html('');

				// append item pilihan ganda
				if (soal.choice_a || soal.choice_a_file) $('.card-multiple-choice').append(cardItem(soal.choice_a, soal.choice_a_file, soal.answer.toLowerCase() == 'a'));
				if (soal.choice_b || soal.choice_b_file) $('.card-multiple-choice').append(cardItem(soal.choice_b, soal.choice_b_file, soal.answer.toLowerCase() == 'b'));
				if (soal.choice_c || soal.choice_c_file) $('.card-multiple-choice').append(cardItem(soal.choice_c, soal.choice_c_file, soal.answer.toLowerCase() == 'c'));
				if (soal.choice_d || soal.choice_d_file) $('.card-multiple-choice').append(cardItem(soal.choice_d, soal.choice_d_file, soal.answer.toLowerCase() == 'd'));
				if (soal.choice_e || soal.choice_e_file) $('.card-multiple-choice').append(cardItem(soal.choice_e, soal.choice_e_file, soal.answer.toLowerCase() == 'e'));

				// show respon jawaban
				if (soal.response_correct_answer) {
					$('.btn-respon-jawaban').removeClass('d-none');
				}


			}
		}
	});
}

//  hapus item pilihan ganda
function deleteChoice(el) {
	// check if choice is less than 2
	if ($(el).closest('.card-multiple-choice').find('.card-item-choice').length <= 2) {
		Swal.fire({
			icon: 'error',
			title: 'Gagal',
			text: 'Pilihan jawaban tidak boleh kurang dari 2',
		});
		return;
	}

	$(el).closest('.card-item-choice').remove();
}

// tambah item pilihan ganda
function addMoreChoice(el) {

	// check if choice is more than 5
	if ($(el).closest('.group-multiple-choice-card').find('.card-item-choice').length >= 5) {
		Swal.fire({
			icon: 'error',
			title: 'Gagal',
			text: 'Pilihan jawaban tidak boleh lebih dari 5',
		});
		return;
	}

	let item = `
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
	`;

	$(el).closest('.group-multiple-choice-card').find('.card-multiple-choice').append(item);

	// input text jawaban pilihan ganda on blur
	$('.input-jawaban-pg').on('blur', function (e) {
		if ($(this).text() == '') {
			$(this).text('Ketik jawaban disini...');
		}
	});
}

// tambah gambar item pilihan ganda
function addImageChoice(el) {
	let input = $(el).closest('.card-item-choice').find('input[type="file"]');
	let inputJawaban = $(el).closest('.card-item-choice').find('.input-jawaban-pg');

	input.click();

	input.change(function () {
		var file = input[0].files[0];
		var reader = new FileReader();
		reader.onload = function (e) {
			let image = `
				<img src="${e.target.result}" class="img-fluid mt-2" alt="Gambar Jawaban" style="height: 170px; width: -webkit-fill-available;">
			`;

			$(el).closest('.card-item-choice').find('.image-choice').html(image);
			// ubah tinggi input jawaban
			inputJawaban.css('height', '50px');
		}
		reader.readAsDataURL(file);
	});
}

// klik input jawaban
function addChoiceText(el) {
	if ($(el).text().includes('Ketik jawaban disini...')) {
		$(el).text('');
	}
}

// klik form-check-input jawaban benar
function setTrueChoice(el) {
	// reset all form-check-input
	$('.form-check-input').prop('checked', false);

	// set form-check-input checked
	$(el).prop('checked', true);
}
