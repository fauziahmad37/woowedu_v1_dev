let activeCard;
let jenisSoalActive;
let listCard = 0;
let listSoal = []; // ini akan terisi dengan list soal yang ada di masing-masing card
let typeOfQuestions = []; // ini akan terisi dengan jenis soal yang dipilih
let questionNumberActive = 0; // ini akan terisi dengan nomor soal yang sedang aktif

const mdlPairingEl = document.querySelector('#mdl-add-pairing');
const mdlPairing = new bootstrap.Modal(mdlPairingEl);
const mdlAddDragdropEl = document.getElementById('mdl-add-dragdrop');
const mdlAddDragdrop = new bootstrap.Modal(mdlAddDragdropEl, {
	keyboard: false,
	backdrop: 'static',
	focus: true
});

const csrfTokenHash = document.querySelector('meta[name="csrf_token"]');
const csrfTokenName = document.querySelector('meta[name="csrf_name"]');
const sortableInit = el => {
	return new Sortable(el, {
		sort: true,
		animation: 150,
		ghostClass: 'bg-primary-subtle',
		onUpdate: evt => {
			console.log(evt);
		}
	});
}

// create soal card
$('#create-soal').on('click', () => {

	// ====================== validasi ======================
	if ($('#select-mapel').val() == '') {
		alert('Harap pilih mata pelajaran terlebih dahulu!');
		return;
	}

	if ($('#select-kelas').val() == '') {
		alert('Harap pilih kelas terlebih dahulu!');
		return;
	}

	if ($('#select-category').val() == '') {
		alert('Harap pilih kategori terlebih dahulu!');
		return;
	}

	if ($('input[name="a_start"]').val() == '') {
		alert('Harap pilih tanggal mulai terlebih dahulu!');
		return;
	}

	if ($('input[name="a_end"]').val() == '') {
		alert('Harap pilih tanggal selesai terlebih dahulu!');
		return;
	}

	if ($('input[name="a_duration"]').val() == '') {
		alert('Harap masukan durasi terlebih dahulu!');
		return;
	}

	// ====================== end validasi ======================

	// ganti text button create-soal
	$('#create-soal').text('+ Buat Soal Lainnya');

	// hitung card-group-custom yang sudah ada untuk update nilai listCard
	listCard = $('.card-group-custom').length;

	let jumlah_soal = $('#a_jumlah_petanyaan').val();
	let jenis_soal = $('#a_jenis_pertanyaan').val();

	// ambil semua card-group-custom untuk mengambil jenis soal
	// kemudia buat kondisi jika jenis soal sudah ada maka tidak bisa membuat jenis soal yang sama
	let cardGroupCustom = $('.card-group-custom');
	let jenisSoal = [];
	cardGroupCustom.each((index, item) => {
		jenisSoal.push($(item).attr('jenis-soal'));
	});

	// validasi jenis soal
	if (jenisSoal.includes(jenis_soal)) {
		alert('Jenis soal sudah ada');
		return;
	}

	// validasi
	if (jenis_soal == '') {
		alert('Pilih jenis soal terlebih dahulu');
		return;
	}

	if ($('#a_jumlah_petanyaan').val() == '') {
		alert('Masukan jumlah soal terlebih dahulu');
		return;
	}

	// jenis soal
	if (jenis_soal == 1) {
		jenis_soal = 'Pilihan Ganda';
	} else if (jenis_soal == 2) {
		jenis_soal = 'Uraian';
	} else if (jenis_soal == 3) {
		jenis_soal = 'Benar atau Salah';
	} else if (jenis_soal == 4) {
		jenis_soal = 'Isi Yang Kosong';
	} else if (jenis_soal == 5) {
		jenis_soal = 'Menjodohkan';
	} else if (jenis_soal == 6) {
		jenis_soal = 'Seret Lepas';
	}

	let card = `
			<div class="card card-group-custom rounded-4 mb-3" data="${listCard}" jenis-soal="${$('#a_jenis_pertanyaan').val()}">
				<div class="card-header pt-3  rounded-4 rounded-bottom-0 bg-primary text-white">
					<div class="d-flex justify-content-between">
						<span>Soal ${jenis_soal}</span>



						<div class="dropdown text-white me-2" onclick="" style="cursor: pointer;">
							<i class="dropdown-toggle fa-solid fa-ellipsis-vertical px-2" data-bs-toggle="dropdown" aria-expanded="false"></i>
							<div class="dropdown-menu container text-center">							
								<div class="row row-cols-auto justify-content-center">

									<div class="col pe-0">
										<button class="btn btn-sm btn-danger text-white" onclick="deleteCard(this)">
											<i class="fa fa-trash"></i>
										</button>
									</div>

    								<div class="col">
										<button class="btn btn-sm btn-primary text-white" onclick="editCard(this)">
											<i class="fa fa-edit"></i>
										</button>
									</div>
								</div>
							</div>
						</div>

					</div>
				</div>

				<div class="card-body">
					<div class="text-center py-5 empty-soal-image">
						<img src="${BASE_URL}assets/images/icons/empty-folder.svg" class="img-fluid text-center" alt="soal">
						<h6 class="mt-4">Belum ada soal yang ditambahkan</h6>
					</div>

					<div class="list-soal-container"></div>
					<ol class="list-group list-group-flush list-group-numbered list-soal-container-ol"></ol>

					<div class="text-center pb-5 pt-3 add-soal-container">
						<button onClick="addSoal()" class="btn btn-lg add-soal" style="background-color: #D4D1E9; color: #281B93; border-color:#281B93; border-width: 1px; font-size: 12px;" data-bs-toggle="modal" data-bs-target="#optionAddQuestionModal"> 
							+ Tambahkan Soal (<span id="count-soal${listCard}">0</span> / <span id="total-soal${listCard}"> ${jumlah_soal}	</span>) 
						</button>
					</div>
				</div>

			</div>
		`;

	$('#add-soal-container').append(card);
	listCard++;
});

// create bank soal
function createBankSoal() {
	let jenisSoal = $('.card[data="' + activeCard + '"]').attr('jenis-soal');
	if (jenisSoal == 1) {
		// new tab window
		showPilihanGanda();

		// reset input and image pilihan ganda
		$('#pilihan_ganda_id').val('');
		$('.image-place-holder-pg').attr('src', BASE_URL + "assets/images/icons/tambahkan_gambar_pendukung.svg");
		$('#soalPilihanGanda').val('');
		$('.image-choice').html('');

		$('.card-item-choice .input-jawaban-pg').text('Ketik jawaban disini...');

		$('.card-item-choice .form-check-input').prop('checked', false);

		$('#responJawaban').prop('checked', false);
		$('.btn-respon-jawaban').addClass('d-none');

		$('.container-image-jawaban-benar-pg').find('.image-placeholder-jawaban-benar').attr('src', BASE_URL + "assets/images/icons/tambahkan_gambar_pendukung.svg");
		$('.container-image-jawaban-salah-pg').find('.image-placeholder-jawaban-salah').attr('src', BASE_URL + "assets/images/icons/tambahkan_gambar_pendukung.svg");

		$('.input-jawaban-benar-pg').html('<p class="place-soal-input text-white">Respon Jawaban Benar...</p>');
		$('.input-jawaban-salah-pg').html('<p class="place-soal-input text-white">Respon Jawaban Salah...</p>');

	}

	if (jenisSoal == 2 || jenisSoal == 4) {
		// new tab window
		// window.open(BASE_URL + 'asesmen/add_question?type=2', '_blank');
		localStorage.setItem('subject_id', ($('#select-mapel').val()));
		localStorage.setItem('class_id', ($('#select-kelas').val()));
		// new tab window
		showFillTheBlank();

		// reset input and image fill the blank
		$('#fiil_the_blank_id').val('');
		$('#image').val('');
		$('.image-place-holder').attr('src', BASE_URL + "assets/images/icons/tambahkan_gambar_pendukung.svg");
		$('#soalFillTheBlank').val('');

		$('#jawaban').html('<p class="place-jawaban text-white">Ketik jawaban di sini...</p>');
		$('.jawaban-alternatif-content').html('<p class="place-jawaban-alternatif text-white">Ketik jawaban di sini...</p>');
		$('.btn-respon-jawaban').addClass('d-none');
		$('.container-jawaban-alternatif').addClass('d-none');

		$('.image-placeholder-jawaban-benar-pg').attr('src', BASE_URL + "assets/images/icons/tambahkan_gambar_pendukung.svg");
		$('.image-placeholder-jawaban-salah-pg').attr('src', BASE_URL + "assets/images/icons/tambahkan_gambar_pendukung.svg");

		$('#image-jawaban-benar').val('');
		$('#image-jawaban-salah').val('');
		$('.input-jawaban-benar').html('<p class="place-soal-input text-white">Respon Jawaban Benar...</p>');
		$('.input-jawaban-salah').html('<p class="place-soal-input text-white">Respon Jawaban Salah...</p>');

		// reset radio button
		$('#pengaturanModalFillTheBlank').find('.form-check-input').prop('checked', false);

	}

	if (jenisSoal == 3) {
		// new tab window

		// clear true_or_false_id input
		$('#true_or_false_id').val('');

		showTrueOrFalse();
	}

	if (jenisSoal == 5) {
		// new tab window


		mdlPairing.show();
	}

	// soal true or false
	if (jenisSoal == 6) {
		mdlAddDragdrop.show();
	}
}

// delete soal card
function deleteCard(e) {
	$(e).closest('.card').remove();
}

// edit soal card
function editCard(e) {
	let card = $(e).closest('.card');
	let data = card.attr('data');
	activeCard = data;

	$('#editJumlahSoalModal').modal('show');
}

// tinjau soal
function tinjauSoal(e) {
	let card = $(e).closest('.card');
	let questionId = card.find('input[name="question_id[]"]').val();

	$.ajax({
		type: "GET",
		url: BASE_URL + 'Asesmen_standard/get_question_fill_the_blank',
		data: {
			question_id: questionId
		},
		success: function (res) {
			if (res.success) {
				let soal = res.data;

				// section soal
				// jika soal hanya text
				if (soal.question && !soal.question_file) {
					$('#question').html(soal.question);
					$('.image-soal-container').addClass('d-none');
					$('.image-soal-container button').addClass('d-none');
				}

				// jika soal hanya gambar
				if (!soal.question && soal.question_file) {
					$('#question').html('');
					$('.image-soal-container').removeClass('d-none');
					$('.image-soal-container button').removeClass('d-none');
					$('.image-soal-container img').attr('src', ADMIN_URL + soal.question_file);
				}

				// jika soal text dan gambar
				if (soal.question && soal.question_file) {
					$('#question').html(soal.question);
					$('.image-soal-container').removeClass('d-none');
					$('.image-soal-container button').removeClass('d-none');
					$('.image-soal-container img').attr('src', ADMIN_URL + soal.question_file);
				}

				// hide multiple choice answer
				$('.multiple-choice-answer').addClass('d-none');
				// hide essay answer
				$('.essay-answer').addClass('d-none');
				// hide true or false answer
				$('.tof-answer').addClass('d-none');
				// hide fill the blank answer
				$('.ftb-answer').addClass('d-none');

				// jika soal pilihan ganda
				if (soal.type == '1') {
					// show multiple choice answer
					$('.multiple-choice-answer').removeClass('d-none');

					// clear card choice
					$('.multiple-choice-answer').html('');

					function choice(questionLetter, questionText, questionFile) {
						// jika soal hanya text
						if (questionText && !questionFile) {
							$('.multiple-choice-answer').append(`
								<div class="col rounded card-item-choice" style="max-width: 25%;">
									<div class="card-body text-dark p-3">
										<div class="d-flex flex-column input-jawaban-pg rounded-2 mt-3 p-3 bg-white" style="min-height: 200px; min-width: 200px;">
											<p class="m-auto h5">${questionText}</p>
										</div>
									</div>
									<div class="form-check my-3 d-flex justify-content-center">
										<input class="form-check-input" type="checkbox" value="${questionLetter}" onclick="setTrueChoice(this)">
									</div>
								</div>
							`);
						}

						// jika soal hanya gambar
						if (!questionText && questionFile) {
							$('.multiple-choice-answer').append(`
								<div class="col rounded card-item-choice" style="max-width: 25%;">
									<div class="card-body text-dark p-3">
										<div class="d-flex flex-column input-jawaban-pg rounded-2 mt-3 p-3 bg-white"  style="height: 200px;">
											<img class="m-auto" src="${ADMIN_URL + questionFile}" alt="choice-a" style="width: 150px; height: 150px;">
										</div>
									</div>
									<div class="form-check my-3 d-flex justify-content-center">
										<input class="form-check-input" type="checkbox" value="${questionLetter}" onclick="setTrueChoice(this)">
									</div>
								</div>
							`);
						}

						// jika soal text dan gambar
						if (questionText && questionFile) {
							$('.multiple-choice-answer').append(`
								<div class="col rounded card-item-choice" style="max-width: 25%;">
									<div class="card-body text-dark p-3">
										<div class="d-flex flex-column input-jawaban-pg rounded-2 mt-3 p-3 bg-white"  style="min-height: 200px;">
											<img class="m-auto" src="${ADMIN_URL + questionFile}" alt="choice-a" style="width: 150px; height: 150px;">
											<p class="m-auto h5 mt-2">${questionText}</p>
										</div>
									</div>
									<div class="form-check my-3 d-flex justify-content-center">
										<input class="form-check-input" type="checkbox" value="${questionLetter}" onclick="setTrueChoice(this)">
									</div>
								</div>
							`);
						}
					}

					// CARD CHOICE
					// CHOICE A
					if (soal.choice_a || soal.choice_a_file) {
						choice('a', soal.choice_a, soal.choice_a_file);
					}

					// CHOICE B
					if (soal.choice_b || soal.choice_b_file) {
						choice('b', soal.choice_b, soal.choice_b_file);
					}

					// CHOICE C
					if (soal.choice_c || soal.choice_c_file) {
						choice('c', soal.choice_c, soal.choice_c_file);
					}
					// CHOICE D
					if (soal.choice_d || soal.choice_d_file) {
						choice('d', soal.choice_d, soal.choice_d_file);
					}

					// CHOICE E
					if (soal.choice_e || soal.choice_e_file) {
						choice('e', soal.choice_e, soal.choice_e_file);
					}

					// checklist jawaban benar
					let inputCheck = $('.multiple-choice-answer .form-check-input');
					inputCheck.each((index, item) => {
						if (item.value.toLowerCase() == soal.answer.toLowerCase()) {
							$(item).prop('checked', true);
						}
					});

					// FOOTER SECTION
					$('.soal-footer .question-type').html(`<h5 class="m-0">
							<i class="fas fa-tasks" aria-hidden="true"></i>
							Pilihan Ganda
					</h5>`);
				}

				// jika soal uraian
				if (soal.type == '2') {
					// show essay answer
					$('.essay-answer').removeClass('d-none');

					// section soal
					$('#essay_answer').html(soal.answer);

					// FOOTER SECTION
					$('.soal-footer .question-type').html(`<h5 class="m-0">
							<i class="fa-solid fa-align-left"></i>
							Uraian
					</h5>`);
				}

				// jika soal benar atau salah
				if (soal.type == '3') {
					// show true or false answer
					$('.tof-answer').removeClass('d-none');

					// set jawaban benar atau salah
					if(soal.answer == 'true') {
						$('.tof-answer .form-check-input')[0].checked = true;
					} else {
						$('.tof-answer .form-check-input')[1].checked = true;
					} 

					// FOOTER SECTION
					$('.soal-footer .question-type').html(`<h5 class="m-0">
							<i class="fa-solid fa-check"></i>
							Benar atau Salah
					</h5>`);
				}
				
				// jika soal fill the blank
				if (soal.type == '4') {
					// show ftb answer
					$('.ftb-answer').removeClass('d-none');

					// section soal
					$('.ftb-answer .answer-container').html('');

					// loop text answer
					soal.answer.split('').forEach((item, index) => {
						$('.ftb-answer .answer-container').append(`
							<span class="h3 answer-item bg-primary-300 p-2 rounded-3 text-white m-1 d-inline-block" style="cursor: pointer; width: 50px; height:50px;">${item}</span>
						`);
					});

					// FOOTER SECTION
					$('.soal-footer .question-type').html(`<h5 class="m-0">
							<i class="fa-regular fa-square"></i>
							Isi Yang Kosong
					</h5>`);
				}

				// FOOTER SECTION
				$('.soal-footer .exam-title').text($('input[name="a_title"]').val());
				$('.soal-footer .subject-name').text($('select[name="select-mapel"] option:selected').text());
			}
		}
	});

	$('#modal-preview-question').modal('show');
}

// edit jumlah soal
function editJumlahSoal() {
	let card = $(`.card[data="${activeCard}"]`);
	let jumlahSoal = card.find('#total-soal' + activeCard).text();
	let newJumlahSoal = $('input[name="a_jumlah_petanyaan_new"]').val();

	// validasi jumlah soal
	if (newJumlahSoal < jumlahSoal) {
		alert('Jumlah soal tidak boleh kurang dari jumlah soal yang sudah ditambahkan');
		return;
	}

	card.find('#total-soal' + activeCard).text(newJumlahSoal);
	$('#editJumlahSoalModal').modal('hide');
}

// show modal question bank
$('#btn-question-bank-modal').on('click', () => {
	$('#optionAddQuestionModal').modal('hide');
});

// Data table list question bank
let tableListQuestionBank = $('#table-list-question-bank').DataTable({
	processing: true,
	serverSide: true,
	searching: true,
	ordering: false,
	ajax: {
		url: BASE_URL + "asesmen_standard/get_question_bank",
		type: "GET"
	},
	columns: [{
		data: "soal_id",
		visible: false
	},
	{
		data: null,
		visible: false
	},
	{
		data: null,
		visible: false
	},
	{
		data: "question"
	},
	{
		data: null,
		render: function (data, type, row) {
			let jenisSoal = '';
			if (row.type == 1) {
				jenisSoal = 'Pilihan Ganda';
			} else if (row.type == 2) {
				jenisSoal = 'Uraian';
			} else if (row.type == 3) {
				jenisSoal = 'Benar atau Salah';
			} else if (row.type == 4) {
				jenisSoal = 'Isi yang Kosong';
			} else if (row.type == 5) {
				jenisSoal = 'Menjodohkan';
			} else if (row.type == 6) {
				jenisSoal = 'Seret Lepas';
			}

			return jenisSoal;
		}
	},
	{
		data: null,
		render: function (data, type, row) {
			return `
						<button class="btn btn-sm btn-primary text-white" onclick="addQuestion(this)">
							<i class="fa fa-plus"></i>
						</button>
					`;
		}
	}
	]
});

// add question to card
function addSoal() {
	let dataCard = $(event.target).closest('.card').attr('data');
	let jenisSoal = $(event.target).closest('.card').attr('jenis-soal');
	jenisSoalActive = jenisSoal;
	activeCard = dataCard;

	// call table list question bank
	let questionIds = [];
	let inputQuestionIds = document.getElementsByName('question_id[]');
	inputQuestionIds.forEach((input) => {
		questionIds.push(input.value);
	});

	tableListQuestionBank.columns(0).search($('#select-mapel').val()).draw();
	tableListQuestionBank.columns(2).search(jenisSoal).draw();
	tableListQuestionBank.columns(5).search(questionIds).draw();
}

// add question to card
function addQuestion(e) {
	let data = tableListQuestionBank.row($(e).parents('tr')).data();
	let card = $(`.card[data="${activeCard}"]`);
	let listCardItem = card.find('.card').length;

	// ketika klik tambah item bank soal maka akan menghilangkan image empty soal
	card.find('.empty-soal-image').remove();

	// init list soal
	(listSoal[activeCard] == undefined) ? listSoal[activeCard] = [] : listSoal[activeCard];

	// push data soal
	listSoal[activeCard].push(data);

	// change text count soal
	let totalListSoal = listSoal[activeCard] == undefined ? 0 : listSoal[activeCard].length;

	// validasi jumlah soal
	if (listCardItem >= $('#total-soal' + activeCard).text()) {
		alert('Jumlah soal melebihi batas');
		return;
	}

	// append soal to card
	let listSoalContainer = card.find('.list-soal-container');
	let actionEdit;
	if (jenisSoalActive == 4) {
		actionEdit = 'editFillTheBlank(this)';
	} else if (jenisSoalActive == 2) {
		actionEdit = 'editFillTheBlank(this)';
	} else if (jenisSoalActive == 1) {
		actionEdit = 'editPilihanGanda(this)';
	} else if (jenisSoalActive == 3) {
		actionEdit = 'editTrueOrFalse(this)';
	} else if (jenisSoalActive == 5) {
		actionEdit = 'editPairing(this)';
	}


	function insertToList(uri, tmpl) {
		fetch(uri)
			.then(res => res.json())
			.then(res => {
				const data = res.data;

				const parentSoal = document.querySelector('div.card-group-custom[data="' + activeCard + '"]');
				const emptyStat = parentSoal.querySelector('.empty-soal-image');
				const dto = parentSoal.querySelector('ol.list-soal-container-ol');

				if (dto.querySelectorAll('li').length >= +document.querySelector('#total-soal' + activeCard).innerText) {
					alert('Jumlah soal melebihi batas');
					return false;
				}

				const li = document.createElement('li');

				li.classList.add('list-group-item', 'position-relative');
				li.innerHTML = tmpl(data);

				dto.appendChild(li);
				document.querySelector('#count-soal' + activeCard).innerText = dto.querySelectorAll('li').length;
				sortableInit(dto);
			})
			.catch(err => console.error(err));
	}

	switch (parseInt(jenisSoalActive)) {
		case 5:
			insertToList(`${BASE_URL}asesmen_standard/get_pairing_question_by_id?question_id=${data.soal_id}`, tmplPairingDisplay);
			break;
		case 6:
			insertToList(`${BASE_URL}asesmen_standard/get_one_dragdrop_question/${data.soal_id}`, tmplDragdropDisplay);
			break;
		default:
			listSoalContainer.append(`
				<div class="card border-start-0 border-top-0 border-end-0 mb-3 rounded-0 mt-5">

					<div class="card-header bg-white border-0 p-0">
						<div class="d-flex justify-content-between">
							<div class="btn btn-light border rounded-3 no-soal" style="width: 100px;">
								${listCardItem + 1}
							</div>

							
							<div class="d-flex btn-group-fill-the-blank" onmouseenter="showBtnGroupFillTheBlankHover(this)">
								${(data.response_correct_answer) ? `
								<button class="btn btn-light border rounded-3 me-2">
									<i class="far fa-lightbulb me-2"></i>Respon
								</button>` : ``}

								${(data.variation_answer) ? `
								<button class="btn btn-light border rounded-3 me-2">
									<i class="fa-solid fa-layer-group me-2"></i>Variasi
								</button>` : ``}

								${(data.alternative_answer) ? `
								<button class="btn btn-light border rounded-3 me-2">
									<i class="fa-solid fa-layer-group me-2"></i>Alternatif
								</button>` : ``}
								
								<button class="btn btn-light border rounded-3 me-2">
									<i class="fa-solid fa-ribbon me-2"></i>${data.point} Poin
								</button>
							</div>

							<div class="d-flex btn-group-fill-the-blank-hover d-none" onmouseleave="showBtnGroupFillTheBlank(this)">
								
								<button class="btn btn-light border rounded-3 me-2" onclick="tinjauSoal(this)">
									<i class="fa-solid fa-eye"></i> Tinjau Soal
								</button>
								
								<button class="btn btn-light border rounded-3 me-2" onclick="${actionEdit}">
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
								<input type="hidden" name="question_id[]" value="${data.soal_id}">
								
								${(data.question_file) ? `<img src="${ADMIN_URL + data.question_file}" class="img-fluid img-question" alt="Gambar data">` : ''}

								<p>${data.question}</p>

								${data.type == 1 ? soalPilihanGanda(data) : ''}
							</div>
						</div>
					</div>

				</div>
			`);
			$(`#count-soal${activeCard}`).text(listCardItem + 1);
			break;
	}


	// hapus data soal dari list question bank
	$(e).closest('tr').remove();

	// call table list question bank
	// fungsi ini digunakan untuk mengupdate list soal yang ada di table list question bank agar yang sudah dipilih tidak muncul lagi ketika pindah ke page selanjutnya
	let questionIds = [];
	let inputQuestionIds = document.getElementsByName('question_id[]');
	inputQuestionIds.forEach((input) => {
		questionIds.push(input.value);
	});

	// tambah question id yang di ambil dari row ini ke dalam list questionIds untuk di search ulang data table nya
	questionIds.push(data.soal_id);

	tableListQuestionBank.columns(5).search(questionIds).draw();
}

// delete list question in card
function deleteQuestionList(e) {
	let a = $(e).closest('.card-group-custom');
	let cardItemLength;
	// hapus data soal dari list soal
	console.log($(a).attr("jenis-soal"));
	if ([5, 6].includes(+$(a).attr("jenis-soal"))) {
		$(e).closest('li').remove();
		cardItemLength = a.find('li').length;
	}
	else {
		$(e).closest('.card').remove();
		// update count soal
		cardItemLength = (a.find('.card').length);
	}
	$('#count-soal' + a.attr('data')).text(cardItemLength);

	// reset nomor soal
	let listCard = a.find('.card');
	listCard.each((index, item) => {
		$(item).find('.no-soal').text(index + 1);
	});

}

// soal pilihan ganda
function soalPilihanGanda(a) {
	let pg = `
			<div class="row mt-3" style="line-height: 2;">
				
				${(a.choice_a && a.choice_a_file) ? `
					<div class="col-6">
						<p class="">A. ${a.choice_a}</p>
						${(a.choice_a_file) ? `<img src="${ADMIN_URL + a.choice_a_file}" class="img-fluid img-question-choice" alt="Gambar A">` : ''}
					</div> ` : ''}
				
				${(a.choice_a && !a.choice_a_file) ? `
					<div class="col-6">
						<p class="">A. ${a.choice_a}</p>
					</div> ` : ''}

				${(!a.choice_a && a.choice_a_file) ? `
					<div class="col-6">
						<p class="">A.</p>
						${(a.choice_a_file) ? `<img src="${ADMIN_URL + a.choice_a_file}" class="img-fluid img-question-choice" alt="Gambar A">` : ''}
					</div> ` : ''}
				
				${(a.choice_b && a.choice_b_file) ? `
					<div class="col-6">
						<p class="">B. ${a.choice_b}</p>
						${(a.choice_b_file) ? `<img src="${ADMIN_URL + a.choice_b_file}" class="img-fluid img-question-choice" alt="Gambar B">` : ''}
					</div> ` : ''}

				${(a.choice_b && !a.choice_b_file) ? `
					<div class="col-6">
						<p class="">B. ${a.choice_b}</p>
					</div> ` : ''}

				${(!a.choice_b && a.choice_b_file) ? `
					<div class="col-6">
						<p class="">B.</p>
						${(a.choice_b_file) ? `<img src="${ADMIN_URL + a.choice_b_file}" class="img-fluid img-question-choice" alt="Gambar B">` : ''}
					</div> ` : ''}
				
				${(a.choice_c && a.choice_c_file) ? `
					<div class="col-6">
						<p class="">C. ${a.choice_c}</p>
						${(a.choice_c_file) ? `<img src="${ADMIN_URL + a.choice_c_file}" class="img-fluid img-question-choice" alt="Gambar C">` : ''}
					</div> ` : ''}
				
				${(a.choice_c && !a.choice_c_file) ? `
					<div class="col-6">
						<p class="">C. ${a.choice_c}</p>
					</div> ` : ''}

				${(!a.choice_c && a.choice_c_file) ? `
					<div class="col-6">
						<p class="">C.</p>
						${(a.choice_c_file) ? `<img src="${ADMIN_URL + a.choice_c_file}" class="img-fluid img-question-choice" alt="Gambar C">` : ''}
					</div> ` : ''}

				${(a.choice_d && a.choice_d_file) ? `
					<div class="col-6">
						<p class="">D. ${a.choice_d}</p>
						${(a.choice_d_file) ? `<img src="${ADMIN_URL + a.choice_d_file}" class="img-fluid img-question-choice" alt="Gambar D">` : ''}
					</div> ` : ''}

				${(a.choice_d && !a.choice_d_file) ? `
					<div class="col-6">
						<p class="">D. ${a.choice_d}</p>
					</div> ` : ''}
				
				${(!a.choice_d && a.choice_d_file) ? `
					<div class="col-6">
						<p class="">D.</p>
						${(a.choice_d_file) ? `<img src="${ADMIN_URL + a.choice_d_file}" class="img-fluid img-question-choice" alt="Gambar D">` : ''}
					</div> ` : ''}

				${(a.choice_e && a.choice_e_file) ? `
					<div class="col-6">
						<p class="">E. ${a.choice_e}</p>
						${(a.choice_e_file) ? `<img src="${ADMIN_URL + a.choice_e_file}" class="img-fluid img-question-choice" alt="Gambar E">` : ''}
					</div> ` : ''}

				${(a.choice_e && !a.choice_e_file) ? `
					<div class="col-6">
						<p class="">E. ${a.choice_e}</p>
					</div> ` : ''}

				${(!a.choice_e && a.choice_e_file) ? `
					<div class="col-6">
						<p class="">D.</p>
						${(a.choice_e_file) ? `<img src="${ADMIN_URL + a.choice_e_file}" class="img-fluid img-question-choice" alt="Gambar E">` : ''}
					</div> ` : ''}
				
			</div>
		`;

	return pg;
}


// show fill pilihan ganda
function showPilihanGanda() {
	$('.soal-pilihan-ganda-container').removeClass('d-none');

	// cursor move to top
	$('html, body').animate({
		scrollTop: 0
	}, 500);

	// hide modal
	$('#optionAddQuestionModal').modal('hide');
}

// show fill the blank
function showFillTheBlank() {
	$('.soal-fill-the-blank-container').removeClass('d-none');

	// cursor move to top
	$('html, body').animate({
		scrollTop: 0
	}, 500);

	// hide modal
	$('#optionAddQuestionModal').modal('hide');
}


// show fill the blank
function showTrueOrFalse() {
	$('.soal-true-or-false-container').removeClass('d-none');

	// cursor move to top
	$('html, body').animate({
		scrollTop: 0
	}, 500);

	// clear input & image soal true or false
	$('#soalTrueOrFalse').val('');
	$('.image-place-holder-tof').attr('src', BASE_URL + "assets/images/icons/tambahkan_gambar_pendukung.svg");

	// uncheck card-choice-tof input checkbox
	$('.card-choice-tof input').prop('checked', false);

	// hide btn-respon-jawaban
	$('.btn-respon-jawaban').addClass('d-none');

	// uncheck input checkbox responJawabanTrueOrFalse
	$('#responJawabanTrueOrFalse').prop('checked', false);

	// clear respon jawaban
	$('.image-placeholder-jawaban-benar-tof').attr('src', BASE_URL + "assets/images/icons/tambahkan_gambar_pendukung.svg");
	$('.image-placeholder-jawaban-salah-tof').attr('src', BASE_URL + "assets/images/icons/tambahkan_gambar_pendukung.svg");

	$('.input-jawaban-benar-tof').val('');
	$('.input-jawaban-salah-tof').val('');


	// hide modal
	$('#optionAddQuestionModal').modal('hide');
}

/**
 * ****************************************************
 * 				BLOCK PAIRING QUESTION
 * ****************************************************
 */

// image preview
const imgPrev = (input, preview) => {
	const file = input.files;

	if (file && file.length > 0) {
		const fileReader = new FileReader();

		fileReader.onload = evt => {
			preview.setAttribute('src', evt.target.result);
		}

		fileReader.readAsDataURL(file[0]);
	}
}

const formPairing = document.forms['frm-pairing-question'];
const fileAddPairingContainer = formPairing.querySelector('.img-file-add-container');
const fileAddPairingPlaceholder = document.getElementById('img-addFile-pairing-placeholder');
const fileAddPairingInput = document.getElementById('pairing-file-add');
const fileAddPairingPreview = document.getElementById('img-pairing-preview');
const btnAddPairingQuestion = document.getElementById('add-pairing-questions');
const rowPairingQuestion = document.getElementById('row-pairing-question');
const btnSubmitPairingQuestion = document.getElementById('btn-submit-pairing-question');
const btnPairingConfig = document.querySelector('#btn-pairing-config');
const dialogPairingConfig = document.querySelector('#mdl-pairing-config');

const observer = new MutationObserver(mutators => {
	const mut = mutators[0];

	if (itemPairingIndex < 4)
		btnAddPairingQuestion.parentNode.closest('div.col-1').classList.remove('d-none');

	Array.from(mut.target.querySelectorAll('.delete-question')).forEach((item, idx) => {

		item.addEventListener('click', e => {
			const parentCol = e.target.parentNode.closest('div.col-pairing-question');

			if (itemPairingIndex <= 1)
				return false;

			parentCol.remove();

			itemPairingIndex--;
			Array.from(mut.target.querySelectorAll('div.col-pairing-question')).forEach((item, idx) => {

				if (!item)
					return false;

				item.dataset.index = idx;

				const qImage = item.querySelector('[id*="q-image-"');
				qImage.id = 'q-image-' + idx;

				const qImageLabel = item.querySelector('label[for*="q-image-"]');
				qImageLabel.setAttribute('for', 'q-image-' + idx);

				const qImagePreview = item.querySelector('[id*="q-image-preview-"]');
				qImagePreview.id = 'q-image-preview-' + idx;

				const imgArahan = item.querySelector('[id*=arahan-image-]');
				imgArahan.id = 'arahan-image-' + idx;

				const lblArahan = item.querySelector('label[for*="arahan-image-"]');
				lblArahan.setAttribute('for', 'arahan-image-' + idx);

				const chkForImage = item.querySelector('[name="q-image[]"]');
				chkForImage.value = idx;

				const inputKey = item.querySelector('[name="input-key[]"]');


			});
		});
	});

	chkImageSelection();

});


mdlPairingEl.addEventListener('show.bs.modal', e => {

	observer.observe(rowPairingQuestion, { childList: true, subtree: true });
});

mdlPairingEl.addEventListener('hide.bs.modal', e => {

	const child = fileAddPairingContainer.children;
	observer.disconnect();

});

fileAddPairingPreview.querySelector('button').addEventListener('click', e => {
	fileAddPairingContainer.classList.remove('d-none');
	fileAddPairingPreview.classList.add('d-none');
	fileAddPairingPreview.querySelector('img').src = '';
	fileAddPairingInput.value = '';
});

fileAddPairingInput.addEventListener('change', e => {
	fileAddPairingContainer.classList.add('d-none');
	fileAddPairingPreview.classList.remove('d-none');
	imgPrev(fileAddPairingInput, fileAddPairingPreview.querySelector('img'));
});

let itemPairingIndex = 1;
btnAddPairingQuestion.addEventListener('click', e => {
	itemPairingIndex++;
	if (itemPairingIndex > 4) {
		itemPairingIndex = 4;
		return false;
	}

	if (itemPairingIndex == 4) {
		const parent = e.target.parentNode.closest('div.col-1');
		parent.classList.add('d-none');
	}

	const items = document.getElementsByClassName('col-pairing-question');
	const lastItem = items[items.length - 1];
	const cloning = lastItem.cloneNode(true);

	const newElement = addNewQuestionColumn(cloning, itemPairingIndex);

	rowPairingQuestion.appendChild(newElement);
});

btnSubmitPairingQuestion.addEventListener('click', e => {
	const evt = new Event('submit');
	formPairing.dispatchEvent(evt);
});

btnPairingConfig.addEventListener('click', e => {
	new bootstrap.Modal(dialogPairingConfig).show();
});

formPairing.addEventListener('submit', async e => {
	e.preventDefault();
	const formData = new FormData(e.target);


	Swal.fire({
		icon: '',
		html: '<div class="d-flex flex-column align-items-center">'
			+ '<span class="spinner-border text-primary"></span>'
			+ '<h3 class="mt-2">Loading...</h3>'
			+ '<div>',
		showConfirmButton: false,
		width: '10rem'
	});

	Array.from(document.querySelectorAll('input[name="is-image-match[]"]')).forEach(i => {
		let chk = 'off';
		if (i.checked)
			chk = 'on';

		formData.append('image-match[]', chk);
	});
	formData.append('mapel', document.querySelector('#select-mapel').value);
	formData.append('kelas', document.querySelector('#select-kelas').value);
	formData.append('config[scores]', document.querySelector('#pairing-config-score').value);
	formData.append(csrfTokenName.content, csrfTokenHash.content);

	try {
		const f = await fetch(`${BASE_URL}asesmen_standard/create_pairing_question`, {
			method: 'POST',
			body: formData
		});

		Swal.close();

		const resp = await f.json();

		if (resp.token) {
			csrfTokenHash.setAttribute('content', resp.token);
		}

		if (!f.ok) {
			Swal.fire({
				icon: 'error',
				title: '<h5 class="text-danger text-uppercase">Error</h5>',
				html: '<span class="text-danger">' + resp.message + '</span>',
				timer: 2000
			});

			return false;

		}


		if (resp.soal_id) {
			fetch(`${BASE_URL}asesmen_standard/get_pairing_question_by_id?question_id=${resp.soal_id}`)
				.then(res => res.json())
				.then(res => {
					const data = res.data;

					const parentSoal = document.querySelector('div.card-group-custom[data="' + activeCard + '"]');
					const emptyStat = parentSoal.querySelector('.empty-soal-image');
					const dto = parentSoal.querySelector('ol.list-soal-container-ol');
					const li = document.createElement('li');


					if (!emptyStat.classList.contains('d-none'))
						emptyStat.classList.add('d-none');

					if (dto.querySelectorAll('li').length >= +document.querySelector('#total-soal' + activeCard).innerText) {
						alert('Jumlah soal melebihi batas');
						return false;
					}

					li.classList.add('list-group-item', 'position-relative');
					li.innerHTML = tmplPairingDisplay(data);

					dto.appendChild(li);
					document.querySelector('#count-soal' + activeCard).innerText = dto.querySelectorAll('li').length;
					sortableInit(dto);
				})
				.catch(err => err.responseText)
		}

		Swal.fire({
			icon: 'success',
			title: '<h4 class="text-success">Success</h4>',
			html: '<span class="text-success">' + resp.message + '</span>',
			timer: 2000
		}).then(t => {
			mdlPairing.hide();
		});

	} catch (err) {
		Swal.fire({
			icon: 'error',
			title: '<h5 class="text-danger text-uppercase">Error</h5>',
			html: '<span class="text-danger">Unkowm Error Ocucured</span>',
			timer: 2000
		});
	}



});

function addNewQuestionColumn(item, idx) {

	if (!item)
		return false;

	item.dataset.index = idx;

	const qImage = item.querySelector('#q-image-' + (idx - 1));
	qImage.id = 'q-image-' + idx;
	qImage.removeAttribute('src');

	const qImageLabel = item.querySelector('label[for="q-image-' + (idx - 1) + '"]');
	qImageLabel.setAttribute('for', 'q-image-' + idx);

	const qImagePreview = item.querySelector('#q-image-preview-' + (idx - 1));
	qImagePreview.id = 'q-image-preview-' + idx;

	const imgArahan = item.querySelector('#arahan-image-' + (idx - 1));
	imgArahan.id = 'arahan-image-' + idx;

	const lblArahan = item.querySelector('label[for="arahan-image-' + (idx - 1) + '"]');
	lblArahan.setAttribute('for', 'arahan-image-' + idx);

	const chkForImage = item.querySelector('[name="is-image-match[]"]');
	chkForImage.value = idx;

	const inputKey = item.querySelector('[name="input-key[]"]');


	if (chkForImage.checked) {
		chkForImage.checked = false;
		lblArahan.classList.add('d-none');
		chkForImage.removeAttribute('checked');
		inputKey.classList.remove('d-none');
		inputKey.removeAttribute('disabled');
		imgArahan.classList.add('d-none');
		qImagePreview.removeAttribute('src');
	}

	return item;
}

function chkImageSelection() {

	Array.from(document.querySelectorAll('div.col-pairing-question')).forEach((item, idx) => {
		const num = item.dataset.index;

		const imagePreview = item.querySelector(`#q-image-preview-${num}`);
		const lblImagePreview = item.querySelector('label[for="arahan-image-' + num + '"]');
		const inputKey = item.querySelector('[name="input-key[]"]');
		const filePairingKey = item.querySelector('[name="q-image[]"]');

		imagePreview.style.textIndent = '-10000px';

		item.querySelector('input[name="is-image-match[]"]').addEventListener('click', e => {

			if (e.target.checked) {
				lblImagePreview.classList.remove('d-none');
				inputKey.classList.add('d-none');

				lblImagePreview.addEventListener('dragover', dragOverImage);
				lblImagePreview.addEventListener('drop', dropImage);

				filePairingKey.addEventListener('change', loadFileFromLocal);
			}
			else {
				lblImagePreview.classList.add('d-none');
				inputKey.classList.remove('d-none');

				lblImagePreview.removeEventListener('dragover', dragOverImage);
				lblImagePreview.removeEventListener('drop', dropImage);
				imagePreview.removeAttribute('src');

				filePairingKey.removeEventListener('change', loadFileFromLocal);
			}
		});
	});
}

function dragOverImage(e) {
	e.preventDefault();
}

function dropImage(e) {
	e.preventDefault();

	const parent = e.target.parentNode.closest('div.col-pairing-question');
	const img = parent.querySelector('[id*="q-image-preview-"]');
	const file = e.dataTransfer.files;

	const fileInput = parent.querySelector('input[name="q-image[]"]');
	fileInput.files = file;

	imgPrev(fileInput, img);
}

function loadFileFromLocal(e) {
	e.preventDefault();

	const elem = e.target;
	const parent = e.target.parentNode.closest('div.col-pairing-question');
	const img = parent.querySelector('[id*="q-image-preview-"]');

	imgPrev(elem, img);

}

function tmplPairingDisplay(data) {
	const tmpl = `<div class="row py-4">
						<input type="text" class="d-none" name="question_id[]" value="${data.soal_id}"/>
						<div class="col-8 py-4"></div>
						<div class="col-4 pan-action position-relative">
							<div class="w-100 h-100 bg-white d-flex flex-wrap flex-grow-0 justify-content-end align-items-top btn-group-pairing-shown">
								<button type="button" class="btn btn-light d-flex align-items-center border rounded-3 me-2" disabled>
									<img src="assets/images/icons/light-bulb.svg" class="me-2" width="16" height="16">Respon
								</button>
								<button type="button" class="btn btn-light d-flex align-items-center border rounded-3 me-2" disabled>
									<img src="assets/images/icons/point-ribbon.svg" class="me-2" width="16" height="16">${data.point} Poin
								</button>
							</div>
							<div class="d-flex btn-group-pairing-hidden justify-content-end align-items-center position-absolute top-0 left-0">
										
								<button class="btn btn-light border rounded-3 me-2">
									<i class="fa-solid fa-eye"></i> Tinjau Soal
								</button>
								
								<button class="btn btn-light border rounded-3 me-2" onclick="">
									<i class="fa fa-edit me-2"></i> Ubah Soal
								</button>
								
								<button class="btn btn-light border rounded-3 me-2" onclick="deleteQuestionList(this)">
									<i class="fa fa-trash"></i>
								</button>
								
							</div>
						</div>
						${data.question_file ?
			`<div class="col-6 mt-3">
								<img src="${ADMIN_URL + data.question_file}" class="img-fluid" style="height: 180px"/>
							</div><div class="col-6"></div>` : ``
		}
						<div class="col-12 my-3">
							${data.question}
						</div>
						<div class="col-12">
							<div class="alert alert-primary mb-0" role="alert">
								<i class="bi bi-info-circle-fill"></i>&nbsp;Urutan teks dengan gambar akan diacak otomatis oleh system, ketika dalam tampilan siswa mengerjakan
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-6 d-flex flex-wrap">
						${data.answer.map(tpl => {
			let mpl = '<div class="col-6 d-flex align-items-center">';
			if (tpl.has_image == 1)
				mpl += '<img src="' + ADMIN_URL + tpl.answer_key + '" style="width: 140px; height: 100px">';
			else
				mpl += '<h6 class="m-0">' + tpl.answer_key + '</h6>';
			mpl += '<span class="fs-3 fw-bold d-block mx-3">&#8594;</span>';
			mpl += '<h6 class="m-0">' + tpl.answer_value + '</h6>';

			mpl += '</div>';

			return mpl;
		}).join('')}
						</div>
					</div>`;
	return tmpl;
}


chkImageSelection();


/**
 * ****************************************************
 * 				BLOCK DRAGDROP QUESTION
 * ****************************************************
 */

const frmDragdrop = document.forms['frm-add-dragdrop'];
const ddBtnSubmit = document.getElementById('btn-submit-dragdrop-question');
const ddCloseModal = document.getElementById('dragdrop-close-modal');
const ddOpenConfig = document.getElementById('btn-dragdrop-config');
const ddCloseConfig = document.getElementById('dragdrop-close-config');
const ddMdlConfig = new bootstrap.Modal(document.getElementById('mdl-dragdrop-config'), {
	focus: false
});

const ddQuestionText = document.getElementById('dragdrop-question-text');
const ddQuestionTag = document.getElementById('dragdrop-question-tag');
const ddBtnOkQuestionTag = document.getElementById('ok-question-tag');
const ddBtnCancelQuestionTag = document.getElementById('cancel-question-tag');
const ddBtnAddQuestion = document.querySelector('#add-question-tag');
const ddInputNewQuestion = ddQuestionTag.querySelector('input');
const ddInputTextAlt = document.querySelector('input[name="dragdrop-question-text"]');
const ddLblFileAdd = document.querySelector('label[for="dragdrop-file-add"]');
const ddFileAddPlaceholder = document.getElementById('img-addFile-dragdrop-placeholder');
const ddFileAddPreview = document.getElementById('img-dragdrop-preview');
const ddFileAddInput = document.getElementById('dragdrop-file-add');
const fileAddContainer = ddLblFileAdd.querySelector('.img-file-add-container');
const ddPreviewOverlay = ddFileAddPreview.querySelector('.preview-overlay');
const ddCorrectAnswer = document.getElementById('dragdrop-answer-correct');
const ddFalseAnswer = document.getElementById('dragdrop-answer-false');
const ddSwitchFalseAnswer = document.getElementById('dragdrop-false-answer');
const ddFalseAnswerContainer = document.getElementById('dragdrop-false-answer-container');
const ddInputFalseAnswerContainer = document.getElementById('dragdrop-input-false-answer-container');
const ddBtnFalseAnswer = document.getElementById('dragdrop-btn-false-answer');
const ddOkFalseAnswer = document.getElementById('dragdrop-ok-false-answer');
const ddCancelFalseAnswer = document.getElementById('dragdrop-cancel-false-answer');
const ddScore = document.getElementById('dragdrop-config-score');
const ddResponJawaban = document.getElementById('dragdrop-respon-jawaban');
const ddAnswerResponse = document.getElementById('dragdrop-response-answer');
const ddBtnAnswerResponse = document.getElementById('btn-dragdrop-response-answer');
const ddBtnBackAnswerResponse = document.getElementById('dragdrop-response-back');
const ddImgResponseCorrectPlaceholder = document.getElementById('img-responseCorrect-placeholder');
const ddImgResponseCorrectPreview = document.getElementById('img-dragdrop-responseCorrect-preview');
const ddLblResponseCorrect = document.querySelector('label[for="dragdrop-img-response-correct"]')
const ddFileResponseCorrect = document.getElementById('dragdrop-img-response-correct');
const ddImgResponseFalsePlaceholder = document.getElementById('img-responseFalse-placeholder');
const ddImgResponseFalsePreview = document.getElementById('img-dragdrop-responseFalse-preview');
const ddLblResponseFalse = document.querySelector('label[for="dragdrop-img-response-false"]')
const ddFileResponseFalse = document.getElementById('dragdrop-img-response-false');
const ddBtnClearResponseAnswer = document.getElementById('dragdrop-clear-response-answer');


const observeTagAnswer = new MutationObserver(mutators => {
	console.log(mutators);

	Array.from([...mutators], item => {

		// observe tag container
		if (item.target.classList.contains('tag-container')) {

			const parentTag = item.target.parentNode.closest('div#dragdrop-answer-correct');
			if (item.target.children.length > 0)
				parentTag.querySelector('.content-info').innerText = 'Opsi jawaban benar';
			else
				parentTag.querySelector('.content-info').innerText = 'Pilihan opsi jawaban akan ada disini...';

		}


		// Observe drag drop question 
		if (item.target.id == 'dragdrop-question-text') {

			if (item.removedNodes && item.removedNodes.length > 0) {

				Array.from(item.removedNodes).forEach(removed => {
					if (removed.nodeName == 'SPAN' && removed.classList.contains('input-text-answer')) {
						const noUrut = removed.dataset.noUrut;
						const dataAnswr = ddCorrectAnswer.querySelector('[data-no-urut="' + noUrut + '"]');
						dataAnswr.remove();
					}
				})


			}


			if (item.addedNodes && item.addedNodes.length > 0) {

				Array.from(item.addedNodes).forEach(added => {

					if (added.nodeName == 'SPAN' && added.classList.contains('input-text-answer')) {
						const cloning = added.cloneNode(true);
						ddCorrectAnswer.querySelector('.tag-container').appendChild(cloning);

					}
				});


			}


		}

	});

});

mdlAddDragdropEl.addEventListener('show.bs.modal', e => {
	observeTagAnswer.observe(e.target, { childList: true, subtree: true, attributes: false });

});

mdlAddDragdropEl.addEventListener('hidden.bs.modal', e => {
	// resetAll();
	observeTagAnswer.disconnect();
});

// document.querySelector('#dragdrop-open-modal').addEventListener('click', e => {
// 	mdlAddDragdrop.show();
// });

ddCloseModal.addEventListener('click', e => {
	mdlAddDragdrop.hide();
});

ddOpenConfig.addEventListener('click', e => {
	ddMdlConfig.show();
});

ddCloseConfig.addEventListener('click', e => {
	ddMdlConfig.hide();
});

frmDragdrop['file-add'].addEventListener('change', e => {
	fileAddContainer.classList.add('d-none');
	ddFileAddPreview.classList.remove('d-none');
	imgPrev(e.target, ddFileAddPreview.querySelector('img'));

});

frmDragdrop.addEventListener('submit', async e => {
	e.preventDefault();
	const formData = new FormData(e.target);

	formData.append('mapel', document.querySelector('#select-mapel').value);
	formData.append('kelas', document.querySelector('#select-kelas').value);

	const questionText = createQuestionText(ddQuestionText);
	formData.append('question', questionText);

	const answerData = ddCorrectAnswer.querySelector('.tag-container').children;
	Array.from(answerData).forEach((item, idx) => {
		formData.append('answer[' + idx + '][text]', item.innerText);
		formData.append('answer[' + idx + '][wordsCount]', item.dataset.wordCount);
	});

	// CONFIG
	formData.append('config[score]', ddScore.value);
	if (ddResponJawaban.checked) {
		formData.append('config[funfact]', 1);
		formData.append('funfactFile[correct]', ddFileResponseCorrect.files[0]);
		formData.append('funfactText[correct]', document.querySelector('input[name="dd-answer-correct-text"]').value);
		formData.append('funfactFile[false]', ddFileResponseFalse.files[0]);
		formData.append('funfactText[false]', document.querySelector('input[name="dd-answer-false-text"]').value);
	}
	else {
		formData.append('config[funfact]', 0);
	}

	if (ddSwitchFalseAnswer.checked) {
		formData.append('config[falseAnswer]', 1);

		Array.from(ddFalseAnswerContainer.children).forEach((item, idx) => {
			formData.append('falseAnswer[' + idx + '][text]', item.innerText);
			formData.append('falseAnswer[' + idx + '][wordsCount]', item.dataset.wordCount);
		});
	}
	else {
		formData.append('config[falseAnswer]', 0);
	}

	formData.append(csrfTokenName.content, csrfTokenHash.content);


	Swal.fire({
		icon: '',
		html: '<div class="d-flex flex-column align-items-center">'
			+ '<span class="spinner-border text-primary"></span>'
			+ '<h3 class="mt-2">Loading...</h3>'
			+ '<div>',
		showConfirmButton: false,
		width: '10rem'
	});


	try {
		const f = await fetch(`${BASE_URL}/asesmen_standard/create_dragdrop_question`, {
			method: 'POST',
			body: formData
		});

		const resp = await f.json();

		Swal.close();

		csrfTokenHash.setAttribute('content', resp.token);

		if (!resp.err_status == 'error' || await f.ok == false) {
			Swal.fire({
				icon: 'error',
				title: '<h5 class="text-danger text-uppercase">Error</h5>',
				html: '<span class="text-danger">' + resp.message + '</span>',
				timer: 2000
			});

			return false;
		}

		Swal.fire({
			icon: 'success',
			title: '<h5 class="text-success text-uppercase">Success</h5>',
			html: '<span class="text-success">' + resp.message + '</span>',
			timer: 2000
		})
			.then(t => {

				if (!resp.id)
					return false;

				fetch(`${BASE_URL}asesmen_standard/get_one_dragdrop_question/${resp.id}`)
					.then(res => res.json())
					.then(res => {
						const data = res.data;

						const parentSoal = document.querySelector('div.card-group-custom[data="' + activeCard + '"]');
						const emptyStat = parentSoal.querySelector('.empty-soal-image');
						const dto = parentSoal.querySelector('ol.list-soal-container-ol');
						const li = document.createElement('li');

						if (!emptyStat.classList.contains('d-none'))
							emptyStat.classList.add('d-none');

						if (dto.querySelectorAll('li').length >= +document.querySelector('#total-soal' + activeCard).innerText) {
							alert('Jumlah soal melebihi batas');
							return false;
						}

						li.classList.add('list-group-item', 'position-relative');
						li.innerHTML = tmplDragdropDisplay(data);

						dto.appendChild(li);
						document.querySelector('#count-soal' + activeCard).innerText = dto.querySelectorAll('li').length;
						sortableInit(dto);
					})
					.catch(err => console.error(err));

			});
	}
	catch (err) {
		Swal.fire({
			icon: 'error',
			title: '<h5 class="text-danger text-uppercase">Error</h5>',
			html: '<span class="text-danger">An Error Occured !!!</span>',
			timer: 2000
		});
		console.error(err);
	}
});

ddBtnSubmit.addEventListener('click', e => {
	frmDragdrop.dispatchEvent(new Event('submit'));
});


ddQuestionText.addEventListener('keydown', e => {

	if (e.which == 113)
		ddBtnAddQuestion.click();

});

ddPreviewOverlay.querySelector('button').addEventListener('click', e => {
	fileAddContainer.classList.remove('d-none');
	ddFileAddPreview.classList.add('d-none');
	ddFileAddPreview.querySelector('img').removeAttribute('src');
	frmDragdrop['file-add'].value = '';
});

ddBtnAddQuestion.addEventListener('click', e => {
	const spanQuestionTag = ddInputNewQuestion.parentNode.closest('span');
	spanQuestionTag.classList.remove('d-none');
	ddInputNewQuestion.removeAttribute('disabled');
	ddInputNewQuestion.focus();
	ddBtnAddQuestion.classList.add('d-none');
});

ddBtnOkQuestionTag.addEventListener('click', insertTagQuestion);

ddBtnCancelQuestionTag.addEventListener('click', e => {
	clearInputAnswerTag();
});

ddInputNewQuestion.addEventListener('input', e => {
	e.preventDefault();
	e.stopPropagation();

	// Add Window Event Listener that we
	const winPreventEnter = we => {

		if (we.which == 13 || we.which == 27) {
			we.preventDefault();
			return false;
		}

	}

	if (!e.target.disabled) {
		window.addEventListener('keyup', winPreventEnter);
		window.addEventListener('keydown', winPreventEnter);
		window.addEventListener('keypress', winPreventEnter);
	} else {
		window.removeEventListener('keyup', winPreventEnter);
		window.removeEventListener('keydown', winPreventEnter);
		window.removeEventListener('keypress', winPreventEnter);
	}


});

ddInputNewQuestion.addEventListener('keyup', e => {
	e.stopPropagation();
	if (e.keyCode == 13)
		insertTagQuestion(e);
	if (e.keyCode == 27)
		clearInputAnswerTag();

});

ddSwitchFalseAnswer.addEventListener('click', e => {
	const parentCol = ddFalseAnswer.parentNode.closest('div.col');

	if (e.target.checked)
		parentCol.classList.remove('d-none');
	else
		parentCol.classList.add('d-none');
});

ddBtnFalseAnswer.addEventListener('click', e => {
	ddInputFalseAnswerContainer.querySelector('span').classList.remove('d-none');
	ddInputFalseAnswerContainer.querySelector('input').removeAttribute('disabled');
	ddInputFalseAnswerContainer.querySelector('input').focus();
	e.target.classList.add('d-none');
});

ddOkFalseAnswer.addEventListener('click', e => {
	const input = document.createElement('span');
	input.disabled = true;
	input.style.width = 'auto';
	input.classList.add('mx-1', 'px-2', 'py-1', 'bg-white', 'rounded', 'text-dark', 'input-false-answer');
	input.contentEditable = false;
	input.role = 'textbox';
	input.dataset.name = 'false-answer[]';
	input.dataset.wordCount = ddInputFalseAnswerContainer.querySelector('input').value.trim().length;
	input.innerHTML = ddInputFalseAnswerContainer.querySelector('input').value.trim();

	ddFalseAnswerContainer.appendChild(input);
	ddCancelFalseAnswer.click();
});

ddCancelFalseAnswer.addEventListener('click', e => {
	ddInputFalseAnswerContainer.querySelector('span').classList.add('d-none');
	ddInputFalseAnswerContainer.querySelector('input').value = null;
	ddInputFalseAnswerContainer.querySelector('input').disabled = true;
	ddBtnFalseAnswer.classList.remove('d-none');
});

ddInputFalseAnswerContainer.querySelector('input').addEventListener('keyup', e => {

	if (e.which == 13)
		ddOkFalseAnswer.click();

	if (e.which == 27)
		ddCancelFalseAnswer.click();
});

ddResponJawaban.addEventListener('click', e => {

	if (e.target.checked)
		ddBtnAnswerResponse.classList.remove('d-none');
	else
		ddBtnAnswerResponse.classList.add('d-none');
});

ddBtnAnswerResponse.addEventListener('click', e => {
	const elem = e.target;

	if (elem.ariaChecked == 'false') {
		elem.ariaChecked = true;
		ddAnswerResponse.classList.add('show');
		e.target.classList.remove('active');
		e.target.classList.add('border', 'border-primary', 'bg-custom-assesment', 'text-primary');
	}
	else {
		elem.ariaChecked = false;
		ddAnswerResponse.classList.remove('show');
		e.target.classList.add('active');
		e.target.classList.remove('border', 'border-primary', 'bg-custom-assesment', 'text-primary');
	}
});

ddBtnBackAnswerResponse.addEventListener('click', e => {
	ddBtnAnswerResponse.ariaChecked = false;
	ddAnswerResponse.classList.remove('show');
	ddBtnAnswerResponse.classList.add('active');
	ddBtnAnswerResponse.classList.remove('border', 'border-primary', 'bg-custom-assesment', 'text-primary');
});

ddFileResponseCorrect.addEventListener('change', e => {
	const placeholderContainer = ddLblResponseCorrect.querySelector('.img-file-add-container');

	placeholderContainer.classList.add('d-none');
	ddImgResponseCorrectPreview.classList.remove('d-none');
	imgPrev(ddFileResponseCorrect, ddImgResponseCorrectPreview.querySelector('img'))
});

ddImgResponseCorrectPreview.querySelector('.preview-overlay button').addEventListener('click', e => {
	const placeholderContainer = ddLblResponseCorrect.querySelector('.img-file-add-container');

	placeholderContainer.classList.remove('d-none');
	ddImgResponseCorrectPreview.classList.add('d-none');
	ddFileResponseCorrect.value = '';
});

ddFileResponseFalse.addEventListener('change', e => {
	const placeholderContainer = ddLblResponseFalse.querySelector('.img-file-add-container');

	placeholderContainer.classList.add('d-none');
	ddImgResponseFalsePreview.classList.remove('d-none');
	imgPrev(ddFileResponseFalse, ddImgResponseFalsePreview.querySelector('img'))
});

ddImgResponseFalsePreview.querySelector('.preview-overlay button').addEventListener('click', e => {
	const placeholderContainer = ddLblResponseFalse.querySelector('.img-file-add-container');

	placeholderContainer.classList.remove('d-none');
	ddImgResponseFalsePreview.classList.add('d-none');
	ddFileResponseFalse.value = '';
});

ddBtnClearResponseAnswer.addEventListener('click', e => {
	const correctLabel = ddLblResponseCorrect.querySelector('.img-file-add-container');
	const wrongLabel = ddLblResponseFalse.querySelector('.img-file-add-container');
	correctLabel.classList.remove('d-none');
	wrongLabel.classList.remove('d-none');
	// CORRET ANSWER
	ddImgResponseCorrectPreview.querySelector('img').removeAttribute('src');
	ddImgResponseCorrectPreview.querySelector('.preview-overlay').classList.add('d-none');
	ddFileResponseCorrect.value = '';
	document.querySelector('input[name="dd-answer-correct-text"]').value = '';
	// WORING ANSWER
	ddImgResponseFalsePreview.querySelector('img').removeAttribute('src');
	ddImgResponseFalsePreview.querySelector('.preview-overlay').classList.add('d-none');
	ddFileResponseFalse.value = '';
	document.querySelector('input[name="dd-answer-false-text"]').value = '';
});

function insertTagQuestion(e) {

	const input = document.createElement('span');
	input.disabled = true;
	input.style.width = 'auto';
	input.classList.add('mx-1', 'px-2', 'py-1', 'bg-white', 'rounded', 'text-dark', 'input-text-answer');
	input.contentEditable = false;

	if ((ddInputNewQuestion.value.trim()).length == 0)
		return false;


	input.innerText = ddInputNewQuestion.value;
	input.dataset.wordCount = ddInputNewQuestion.value.trim().length;
	input.dataset.name = 'correct-answer[]';
	input.dataset.noUrut = document.querySelectorAll('.input-text-answer') && document.querySelectorAll('.input-text-answer').length > 0
		? document.querySelectorAll('.input-text-answer').length : 1;
	input.role = 'textbox';
	clearInputAnswerTag();

	ddQuestionText.appendChild(input);
	ddQuestionText.focus();
	moveCursorToEnd(ddQuestionText);
	// ddCorrectAnswer.querySelector('.tag-container').appendChild(input);
}

function moveCursorToEnd(el) {
	const selection = window.getSelection();
	selection.selectAllChildren(el);
	selection.collapseToEnd();
}

function clearInputAnswerTag() {
	const spanQuestionTag = ddInputNewQuestion.parentNode.closest('span');
	spanQuestionTag.classList.add('d-none');
	ddInputNewQuestion.disabled = true;
	ddBtnAddQuestion.classList.remove('d-none');
	ddInputNewQuestion.value = '';
}

function resetAll() {
	clearInputAnswerTag();
	if (ddSwitchFalseAnswer.checked) {
		ddSwitchFalseAnswer.checked = false;
		ddFalseAnswer.parentNode.closest('div.col').classList.add('d-none');
		ddFalseAnswerContainer.innerHTML = null;
	}
	ddQuestionText.innerHTML = null;
	ddCorrectAnswer.querySelector('.tag-container').innerHTML = null;
	if (ddFileAddPreview.querySelector('img').src.length > 0)
		ddPreviewOverlay.querySelector('button').click();
	if (ddSwitchFalseAnswer.checked) {
		ddSwitchFalseAnswer.checked = false;
		ddBtnClearResponseAnswer.click();
	}
}

function createQuestionText(element) {
	const nodeChildren = element.childNodes;
	let txtInputQuestion = '';
	if (nodeChildren && nodeChildren.length > 0) {
		txtInputQuestion = Array.from(nodeChildren).map(item => {
			let out = '';

			if (item.nodeName == 'SPAN' && item.classList.contains('input-text-answer')) {
				const wordsCount = item.dataset.wordCount;
				let emptyStr = '';
				out = '<span class="drop-box" data-value="'+item.dataset.noUrut+'">....</span>';
			}
			else {
				out = item.textContent;
			}

			return out;
		})
			.join('');

	}

	return txtInputQuestion;
}

function tmplDragdropDisplay(data) {
	const tmpl = `<div class="row py-4">
						<input type="text" class="d-none" name="question_id[]" value="${data.soal_id}"/>
						<div class="col-8 py-4"></div>
						<div class="col-4 pan-action position-relative">
							<div class="w-100 h-100 bg-white d-flex flex-wrap flex-grow-0 justify-content-end align-items-top btn-group-pairing-shown">
								<button type="button" class="btn btn-light d-flex align-items-center border rounded-3 me-2" disabled>
									<img src="assets/images/icons/light-bulb.svg" class="me-2" width="16" height="16">Respon
								</button>
								<button type="button" class="btn btn-light d-flex align-items-center border rounded-3 me-2" disabled>
									<img src="assets/images/icons/point-ribbon.svg" class="me-2" width="16" height="16">10 Poin
								</button>
							</div>
							<div class="d-flex btn-group-pairing-hidden justify-content-end align-items-center position-absolute top-0 left-0">
										
								<button class="btn btn-light border rounded-3 me-2">
									<i class="fa-solid fa-eye"></i> Tinjau Soal
								</button>
								
								<button class="btn btn-light border rounded-3 me-2" onclick="">
									<i class="fa fa-edit me-2"></i> Ubah Soal
								</button>
								
								<button class="btn btn-light border rounded-3 me-2" onclick="deleteQuestionList(this)">
									<i class="fa fa-trash"></i>
								</button>
								
							</div>
						</div>
						${data.question_file ?
			`<div class="col-6 my-3">
								<img src="${ADMIN_URL + data.question_file}" class="img-fluid" height="260"/>
							</div><div class="col-6"></div>` : ``
		}
						<div class="col-12">
							<h5>${data.question}</h5>
						</div>
						
					</div>
					<div class="row">
						<div class="col-6 d-flex flex-wrap">
						${data.answer.map(tpl => {
			let mpl = '<div class="col-6 d-flex align-items-center mb-2 fs-5">';
			mpl += '<i class="bi bi-record-circle"></i>';
			mpl += '<span class="d-inline-block ms-1">' + tpl.answer_correct + '</span>';
			mpl += '</div>';
			if (tpl.answer_false) {
				mpl += '<div class="col-6 d-flex align-items-center mb-2 fs-5">';
				mpl += '<i class="bi bi-record-circle"></i>';
				mpl += '<span class="d-inline-block ms-1">' + tpl.answer_false + '</span>';
				mpl += '</div>';
			}
			return mpl;
		}).join('')}
						</div>
					</div>`;

	return tmpl;
}

// Simpan Draft Asesmen
let isPublish = false;
$('#save-draft').on('click', () => {
	// validation jika waktu mulai lebih besar dari waktu berakhir
	let start_date = $('#a_start').val();
	let end_date = $('#a_end').val();
	if (start_date > end_date) {
		Swal.fire({
			icon: 'error',
			title: 'Gagal',
			text: 'Waktu mulai tidak boleh lebih besar dari waktu berakhir',
		});
		return;
	}

	// validasi jika soal belum diisi
	if($('input[name="question_id[]"]').length == 0) {
		Swal.fire({
			icon: 'error',
			title: 'Gagal',
			text: 'Soal belum diisi',
		});
		return;
	}

	let exam_id = $('input[name="exam_id"]').val();
	let cardGroupCustom = $('.card-group-custom');

	listSoal = [];
	cardGroupCustom.each((index, item) => {
		let data = $(item).attr('data');
		listSoal[data] = [];
		console.log(item);
		const listSoalItem = item.querySelectorAll('input[name="question_id[]"]');

		Array.from(listSoalItem).forEach((item2, index2) => {
			listSoal[data][index2] = {
				soal_id: item2.value,
				point: 0,
				type: $(item).attr('jenis-soal'),
			}
		});
	});

	let data = {
		teacher_id: $('input[name="teacher_id"]').val(),
		exam_id: $('input[name="exam_id"]').val(),
		subject_id: $('#select-mapel').val(),
		class_id: $('#select-kelas').val(),
		category_id: $('#select-category').val(),
		title: $('#a_title').val(),
		description: $('#deskripsi').val(),
		start_date: $('#a_start').val(),
		end_date: $('#a_end').val(),
		duration: $('#a_duration').val(),
		is_update: $('input[name="is_update"]').is(':checked'),
		questions: listSoal,
		is_publish: isPublish,
	};

	if (exam_id) {
		data.exam_id = exam_id;
	}

	$.ajax({
		url: BASE_URL + 'asesmen_standard/save_draft',
		type: 'POST',
		data: data,
		success: function (res) {
			if (res.success) {
				Swal.fire({
					icon: 'success',
					title: 'Berhasil',
					text: 'Berhasil menyimpan draft asesmen',
				});

				// set exam_id
				// $('input[name="exam_id"]').val(res.data.exam_id);

				window.location.href = BASE_URL + 'asesmen';
			} else {
				Swal.fire({
					icon: 'error',
					title: 'Gagal',
					text: 'Gagal menyimpan draft asesmen',
				});
			}
		}
	});
});

// publish asesmen
$('#publish').on('click', () => {
	isPublish = true;
	$('#save-draft').trigger('click');
});


