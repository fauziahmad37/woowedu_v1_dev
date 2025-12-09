let soalIdActive;
let jenisSoalActive;
const wrongAudio = new Audio(BASE_URL + "assets/audios/wrong-47985.mp3");
const correctAudio = new Audio(BASE_URL + "assets/audios/correct-choice-43861.mp3");
const successAudio = new Audio(BASE_URL + "assets/audios/success-1-6297.mp3");
const failedAudio = new Audio(BASE_URL + "assets/audios/trumpet-fail-242645.mp3");
const wooshAudio = new Audio(BASE_URL + "assets/audios/swoosh-sound-effect-149890.mp3");

const period = JSON.parse(document.getElementById('period').textContent);


const getShuffledArr = arr => {
	const newArr = arr.slice()
	for (let i = newArr.length - 1; i > 0; i--) {
		const rand = Math.floor(Math.random() * (i + 1));
		[newArr[i], newArr[rand]] = [newArr[rand], newArr[i]];
	}
	return newArr
};



const saveJawabanOnBackground = async (currentSoal, answer) => {
	const soal = JSON.parse(localStorage.getItem("soal"));
	currentSoal.exam_answer = answer;
	soal.splice(soal.findIndex(item => item.soal_id == currentSoal.soal_id), 1, currentSoal);
	localStorage.setItem("soal", JSON.stringify(soal));
}


$(document).ready(function () {

	// ================================= create timer =================================


	var examId = $('input[name="exam_id"]').val();
	var start_time = moment().format('YYYY-MM-DD HH:mm:ss');
	var duration = period.duration;
	var remaining_time = duration * 60; // convert to seconds

	// jika examId tidak sama dengan examId yang ada di local storage
	// maka set local storage
	if (localStorage.getItem('examId') !== examId) {
		localStorage.setItem('examId', examId);
		localStorage.setItem('start_time', start_time);
		localStorage.setItem('remaining_time', remaining_time);
	}

	// jika examId sama dengan examId yang ada di local storage
	// maka ambil data dari local storage
	if (localStorage.getItem('examId') === examId) {
		start_time = localStorage.getItem('start_time');
		remaining_time = localStorage.getItem('remaining_time');
	}

	// append timer dengan interval 1 detik
	setInterval(() => {
		let now = moment().format('YYYY-MM-DD HH:mm:ss');
		let diff = moment(now).diff(moment(start_time), 'seconds'); // hitung selisih waktu
		let time = remaining_time - diff; // hitung sisa waktu

		// jika waktu habis maka submit form
		if (time <= 0) {
			switch (jenisSoalActive) {
				case "1":
					finishMultipleChoice();
					break;
				case "2":
					finishEssay();
					break;
				case "3":
					finishTrueOrFalse();
					break;
				case "4":
					finishFtb();
					break;
				default:
					window.location.href = BASE_URL + 'asesmen';
					break;
			}
		}

		let hours = Math.floor(time / 3600); // hitung jam
		let minutes = Math.floor((time % 3600) / 60); // hitung menit 
		let seconds = time % 60; // hitung detik

		$('#timer').text(`${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`);
	}, 1000);

	// ===================================== END =====================================


	// =================================================================================
	// ================================= expand screen =================================
	// =================================================================================

	$('.expand-screen').on('click', function () {
		if (document.fullscreenElement) {
			// change icon
			$('.expand-screen i').removeClass('fa-compress').addClass('fa-expand');
			closeFullscreen();
		} else {
			// change icon
			$('.expand-screen i').removeClass('fa-expand').addClass('fa-compress');
			openFullscreen();
		}
	});

	var elem = document.documentElement;

	function openFullscreen() {
		if (elem.requestFullscreen) {
			elem.requestFullscreen();
		} else if (elem.webkitRequestFullscreen) {
			/* Safari */
			elem.webkitRequestFullscreen();
		} else if (elem.msRequestFullscreen) {
			/* IE11 */
			elem.msRequestFullscreen();
		}
	}

	function closeFullscreen() {
		if (document.exitFullscreen) {
			document.exitFullscreen();
		} else if (document.webkitExitFullscreen) {
			/* Safari */
			document.webkitExitFullscreen();
		} else if (document.msExitFullscreen) {
			/* IE11 */
			document.msExitFullscreen();
		}
	}
	// ===================================== END =====================================


	// button zoom image
	$('.btn-zoom').click(function (e) {
		// proses zoom image with modal
		$('#modalImage').modal('show');
		let img = $(e.target).closest('.image-soal-container').find('img').attr('src');
		$('#modalImage img').attr('src', img);

	});

	// =================================== GET SOAL ===================================
	const getSoal = () => {

		// get local storage soal master
		let soalMaster = localStorage.getItem('soal_master');

		// jika soal master belum ada maka get soal from server
		if (!soalMaster) {
			$.ajax({
				type: 'POST',
				url: BASE_URL + 'Soal/get_soal_exam',
				data: {
					exam_id: $('input[name="exam_id"]').val(),
					student_id: $('input[name="student_id"]').val(),
					[csrfName.content]: csrfToken.content
				},
				dataType: 'json',
				success: function (response) {
					if (response.success) {
						// simpan soal ke dalam local storage soal master
						localStorage.setItem('soal_master', JSON.stringify(response.data));

						// set token
						csrfToken.setAttribute('content', response.token);

						// reload page
						location.reload();
					} else {
						csrfToken.setAttribute('content', response.token);
					}
				},
				complete: (xhr, status) => {
					const resp = xhr.responseJSON;
					csrfToken.setAttribute('content', resp.token);
				}
			});
		} else {
			// jika soal master sudah ada maka get soal from local storage
			soalMaster = JSON.parse(soalMaster);

			let soalUnanswered = [];
			let soalAnswered = [];

			// check local storage soal jika belum ada maka masukan data soal ke local storage
			if (!localStorage.getItem('soal')) {
				// looping soal master untuk menambahkan key exam_answer
				soalMaster.forEach((item) => {
					item.exam_answer = null;
				});

				localStorage.setItem('soal', JSON.stringify(soalMaster));

				// lakukan filter untuk mengambil soal yang belum dijawab
				soalUnanswered = soalMaster.filter((item) => item.exam_answer == null);

				// lakukan filter untuk mengambil soal yang sudah dijawab
				soalAnswered = soalMaster.filter((item) => item.exam_answer != null);
			} else {
				// lakukan filter untuk mengambil soal yang belum dijawab
				soalUnanswered = JSON.parse(localStorage.getItem('soal')).filter((item) => item.exam_answer == null);

				// lakukan filter untuk mengambil soal yang sudah dijawab
				soalAnswered = JSON.parse(localStorage.getItem('soal')).filter((item) => item.exam_answer != null);
			}

			// set text counter soal
			$('.counter-soal').text(soalAnswered.length + 1);

			// jika soal belum di jawab masih ada
			if (soalUnanswered.length > 0) {

				$.each(soalMaster, function (i, val) {
					if (val.soal_id == soalUnanswered[0].soal_id) {
						// JIKA TIPE SOAL FILL THE BLANK
						if (val.type == "4") {
							soalFillTheBlank(val);
						}

						// JIKA TIPE SOAL TRUE OR FALSE
						if (val.type == "3") {
							soalTrueOrFalse(val);
						}

						// JIKA TIPE SOAL ESSAY
						if (val.type == "2") {
							soalEssay(val);
						}

						// JIKA TIPE SOAL PILIHAN GANDA
						if (val.type == "1") {
							soalPilihanGanda(val);
						}

						if (val.type == "5") {
							const nomor = soalAnswered.length + 1;
							const dragitem = getShuffledArr(val.correct_answer.map(item => ({ "text": item.answer_key, "index": item.urutan, "isImage": item.has_image == 1 ? true : false })));
							const dropitem = val.correct_answer.map(item => ({ "text": item.answer_value, "index": item.urutan }));
							soalIdActive = val.soal_id;
							jenisSoalActive = val.type;
							// pengacak
							const shuffled = [];

							for (var i = 0; i < dragitem.length; i++) {
								shuffled.push({ "key": dragitem[i], "value": dropitem[i] });
							}
							// info judul dan mapel
							val = { ...val, ...period, "constipasi": shuffled, "nomor": nomor, "totalSoal": soalMaster.length };



							setMyQuestion(document.getElementById('student-pairing-question'), val);
						}

						if (val.type == "6") {
							soalIdActive = val.soal_id;
							jenisSoalActive = val.type;

							const nomor = soalAnswered.length + 1;
							const questionTag = document.createElement("div");
							questionTag.innerHTML = val.question;

							Array.from(questionTag.querySelectorAll(".drop-box")).forEach(i => {
								i.setAttribute("ondragover", "ddDragOver(event)");
								i.setAttribute("ondragleave", "ddDragLeave(event)");
								i.setAttribute("ondrop", "ddDrop(event)");
								i.classList.add("border", "border-5", "rounded", "px-3");
							});

							const answer = [];
							val.correct_answer.forEach(i => {
								answer.push({ "key": i.urutan, "value": i.answer_correct });
								if (i.answer_false) {
									answer.push({ "key": 0, "value": i.answer_false });
								}
							});

							val = { ...val, ...period, "question": questionTag.innerHTML, "answers": answer, "nomor": nomor, "totalSoal": soalMaster.length };

							setMyQuestion(document.getElementById('student-porkas-question'), val);
						}
					}
				});

			}

			// set total-soal
			$('.total-soal').text(soalMaster.length);
		}

	}

	getSoal();

	// button next soal

	$('.next-soal').on('click', function () {
		// get local storage master soal
		let soalMaster = JSON.parse(localStorage.getItem('soal_master'));

		// ambil soal yang belum dijawab
		let soalUnanswered = JSON.parse(localStorage.getItem('soal')).filter((item) => item.exam_answer == null || item.exam_answer == '');

		// ambil soal yang sudah dijawab
		let soalAnswered = JSON.parse(localStorage.getItem('soal')).filter((item) => item.exam_answer != null);

		// update counter soal
		$('.counter-soal').text(soalAnswered.length + 1);

		// set soalIdActive dan jenisSoalActive
		soalIdActive = soalUnanswered[0].soal_id;
		jenisSoalActive = soalUnanswered[0].type;

		// HIDE SEMUA CONTAINER SOAL
		$('#student-fill-the-blank-container').addClass('d-none');
		$('#student-true-or-false-container').addClass('d-none');
		$('#student-essay-container').addClass('d-none');
		$('#student-multiple-choice-container').addClass('d-none');

		// JIKA TIPE SOAL FILL THE BLANK
		if (soalUnanswered[0].type == "4") {
			soalFillTheBlank(soalUnanswered[0]);
		}

		// JIKA TIPE SOAL TRUE OR FALSE
		if (soalUnanswered[0].type == "3") {
			soalTrueOrFalse(soalUnanswered[0]);
		}

		// JIKA TIPE SOAL ESSAY
		if (soalUnanswered[0].type == "2") {
			soalEssay(soalUnanswered[0]);
		}

		// JIKA TIPE SOAL PILIHAN GANDA
		if (soalUnanswered[0].type == "1") {
			soalPilihanGanda(soalUnanswered[0]);
		}

		// JIKA TIPE SOAL MENJODOHKAN
		if (soalUnanswered[0].type == "5") {
			const unanswered = soalUnanswered[0];
			const nomor = soalAnswered.length + 1;
			const dragitem = getShuffledArr(unanswered.correct_answer.map(item => ({ "text": item.answer_key, "index": item.urutan, "isImage": item.has_image == 1 ? true : false })));
			const dropitem = unanswered.correct_answer.map(item => ({ "text": item.answer_value, "index": item.urutan }));
			soalIdActive = unanswered.soal_id;
			jenisSoalActive = unanswered.type;
			// pengacak
			const shuffled1 = [];

			for (var i = 0; i < dragitem.length; i++) {
				shuffled1.push({ "key": dragitem[i], "value": dropitem[i] });
			}
			// info judul dan mapel
			const input = { ...unanswered, ...period, "constipasi": shuffled1, "nomor": nomor, "totalSoal": soalMaster.length };

			setMyQuestion(document.getElementById('student-pairing-question'), input);
		}


		if (soalUnanswered[0].type == "6") {
			const unanswered = soalUnanswered[0];
			soalIdActive = unanswered.soal_id;
			jenisSoalActive = unanswered.type;

			const nomor = soalAnswered.length + 1;
			const questionTag = document.createElement("div");
			questionTag.innerHTML = unanswered.question;

			Array.from(questionTag.querySelectorAll(".drop-box")).forEach(i => {
				i.setAttribute("ondragover", "ddDragOver(event)");
				i.setAttribute("ondragleave", "ddDragLeave(event)");
				i.setAttribute("ondrop", "ddDrop(event)");
				i.classList.add("border", "border-5", "rounded", "px-3");
			});

			const answer = [];
			unanswered.correct_answer.forEach(i => {
				answer.push({ "key": i.urutan, "value": i.answer_correct });
				if (i.answer_false) {
					answer.push({ "key": 0, "value": i.answer_false });
				}
			});

			const input = { ...unanswered, ...period, "question": questionTag.innerHTML, "answers": answer, "nomor": nomor, "totalSoal": soalMaster.length };

			setMyQuestion(document.getElementById('student-porkas-question'), input);
		}

	});

	// =============================================================================================
	// =================================== SOAL FILL THE BLANK ====================================
	// =============================================================================================

	// event ketika kotak-kotak jawaban di klik 
	function setFocusAnswerItem() {
		$('.answer-item').on('focus', function () {
			// only allow one character
			$(this).on('input', function () {
				if ($(this).text().length > 1) {
					$(this).text($(this).text().slice(0, 1));
				}
			});

			// don't allow space or enter
			$(this).on('keydown', function (e) {
				if (e.keyCode == 13 || e.keyCode == 32) {
					e.preventDefault();
				}
			});

			// key right
			$(this).on('keydown', function (e) {
				if (e.keyCode == 39) {
					$(this).next().focus();
				}
			});

			// key left
			$(this).on('keydown', function (e) {
				if (e.keyCode == 37) {
					$(this).prev().focus();
				}
			});

		});

		// event ketika kotak-kotak jawaban di isi
		$('.answer-item').on('input', function () {
			let answer = '';
			$('.answer-item').each(function () {
				answer += $(this).text();
			});

			// jika jawaban sudah diisi maka enable button kirim jawaban
			if (answer.length > 0) {
				$('.submit-answer-fill-the-blank button').attr('disabled', false);
			} else {
				$('.submit-answer-fill-the-blank button').attr('disabled', true);
			}
		});

	}

	function soalFillTheBlank(data) {
		// set soalIdActive dan jenisSoalActive
		soalIdActive = data.soal_id;
		jenisSoalActive = data.type;

		$('#student-fill-the-blank-container').removeClass('d-none');

		// section soal
		$('#soalFillTheBlank').html(data.question);
		$('#student-fill-the-blank-container .image-soal-container img').attr('src', ADMIN_URL + data.question_file);
		$('#student-fill-the-blank-container .image-soal-container img').css('width', '300px');
		$('#student-fill-the-blank-container .image-soal-container img').css('height', '180px');

		// image position left
		if (data.image_position == 'left') {
			$('#student-fill-the-blank-container .image-soal-container').css('order', '1');
			$('#student-fill-the-blank-container .flex-item').css('order', '2');
		}

		// section jawaban
		$('#student-fill-the-blank-container .answer-container').html('');

		// looping jawaban
		for (let i = 0; i < data.answer.length; i++) {
			$('#student-fill-the-blank-container .answer-container').append(`
						<span contenteditable="true" class="h3 answer-item bg-primary-200 p-2 rounded-3 text-white m-1 d-inline-block" style="cursor: pointer; width: 50px; height:50px;"></span>`);
		}

		// disable & show button kirim jawaban
		$('.submit-answer-fill-the-blank button').attr('disabled', true);
		$('.submit-answer-fill-the-blank').removeClass('d-none');

		// jika button soal selanjut nya ada maka lakukan hide
		if (!$('.next-question-fill-the-blank').hasClass('d-none')) {
			$('.next-question-fill-the-blank').addClass('d-none');
		}

		// hide response jawaban
		$('.main-response-answer').addClass('d-none');

		// hide circle correc and incorrect
		$('.circle-correct').addClass('d-none');
		$('.circle-incorrect').addClass('d-none');

		// set focus answer item, ketika kotak jawaban di klik
		setFocusAnswerItem();
	}

	// ===================================== END =====================================

	// =============================================================================================
	// =================================== SOAL TRUE OR FALSE ===================================
	// =============================================================================================

	function soalTrueOrFalse(data) {
		// set soalIdActive dan jenisSoalActive
		soalIdActive = data.soal_id;
		jenisSoalActive = data.type;

		$('#student-true-or-false-container').removeClass('d-none');

		// section soal
		// jika soal hanya text
		if (data.question && !data.question_file) {
			$('#soalTrueOrFalse').html(data.question);
			$('#student-true-or-false-container .image-soal-container').addClass('d-none');
		}

		// jika soal hanya gambar
		if (!data.question && data.question_file) {
			$('#student-true-or-false-container .image-soal-container img').attr('src', ADMIN_URL + data.question_file);
			$('#student-true-or-false-container .image-soal-container img').css('width', '300px');
			$('#student-true-or-false-container .image-soal-container img').css('height', '180px');
		}

		// jika soal text dan gambar
		if (data.question && data.question_file) {
			$('#soalTrueOrFalse').html(data.question);
			$('#student-true-or-false-container .image-soal-container img').attr('src', ADMIN_URL + data.question_file);
			$('#student-true-or-false-container .image-soal-container img').css('width', '300px');
			$('#student-true-or-false-container .image-soal-container img').css('height', '180px');
		}


		// image position left
		if (data.image_position == 'left') {
			$('#student-true-or-false-container .image-soal-container').css('order', '1');
			$('#student-true-or-false-container .flex-item').css('order', '2');
		}

		// section jawaban
		$('#student-true-or-false-container .answer-container').html('');

		// looping jawaban
		$('#student-true-or-false-container .answer-container').append(`
					<div class="form-check form-switch">
						<input class="form-check-input" type="radio" name="answer" id="true" value="1">
						<label class="form-check-label" for="true">Benar</label>
					</div>
					<div class="form-check form-switch">
						<input class="form-check-input" type="radio" name="answer" id="false" value="0">
						<label class="form-check-label" for="false">Salah</label>
					</div>
				`);

		// remove background color white dari container pilhan jawaban
		$('.btn-choice-container').removeClass('bg-white');

		// uncheck radio button
		$('.form-check-input').prop('checked', false);

		// hide response jawaban
		$('.main-response-answer').addClass('d-none');

		// hide circle correc and incorrect
		$('.circle-correct').addClass('d-none');
		$('.circle-incorrect').addClass('d-none');

		// disable & show button kirim jawaban
		$('.submit-answer-true-or-false button').attr('disabled', true);
		$('.submit-answer-true-or-false').removeClass('d-none');

		// jika button soal selanjut nya ada maka lakukan hide
		if (!$('.next-question-true-or-false').hasClass('d-none')) {
			$('.next-question-true-or-false').addClass('d-none');
		}

		// event ketika jawaban di klik
		$('.form-check-input').on('click', function () {
			$('.submit-answer-true-or-false button').attr('disabled', false);
		});
	}

	// ===================================== END =====================================

	// =============================================================================================
	// =================================== SOAL ESSAY ===================================
	// =============================================================================================

	function soalEssay(data) {
		// set soalIdActive dan jenisSoalActive
		soalIdActive = data.soal_id;
		jenisSoalActive = data.type;

		$('#student-essay-container').removeClass('d-none');

		// section soal
		$('#soalEssay').html(data.question);

		if (data.question && data.question_file) { // jika soal text dan gambar
			$('#student-essay-container .image-soal-container').removeClass('d-none');
			$('#student-essay-container .image-soal-container').parent().removeClass('justify-content-center');
		}

		if (data.question && !data.question_file) { // jika soal hanya text
			$('#student-essay-container .image-soal-container').addClass('d-none');
			$('#student-essay-container .image-soal-container').parent().addClass('justify-content-center');
		}

		if (!data.question && data.question_file) { // jika soal hanya gambar
			$('#student-essay-container .image-soal-container').removeClass('d-none');
			$('#student-essay-container .image-soal-container').parent().removeClass('justify-content-center');
		}

		$('#student-essay-container .image-soal-container img').attr('src', ADMIN_URL + data.question_file);
		$('#student-essay-container .image-soal-container img').css('width', '300px');
		$('#student-essay-container .image-soal-container img').css('height', '180px');

		// section jawaban
		$('#essay_answer').text('Ketik jawaban di sini...');

		// disable & show button kirim jawaban
		$('.submit-answer-essay button').attr('disabled', true);
		$('.submit-answer-essay').removeClass('d-none');

		// jika button soal selanjut nya ada maka lakukan hide
		if (!$('.next-question-essay').hasClass('d-none')) {
			$('.next-question-essay').addClass('d-none');
		}

		// hide response jawaban
		$('.main-response-answer').addClass('d-none');

		// hide circle correc and incorrect
		$('.circle-correct').addClass('d-none');
		$('.circle-incorrect').addClass('d-none');

		// event ketika jawaban di isi
		$('#student-essay-container .soal-content').on('input', function () {
			if ($(this).text().length > 0) {
				$('.submit-answer-essay button').attr('disabled', false);
			} else {
				$('.submit-answer-essay button').attr('disabled', true);
			}
		});
	}

	// ===================================== END =====================================

	// =============================================================================================
	// =================================== SOAL PILIHAN GANDA ===================================
	// =============================================================================================

	function soalPilihanGanda(data) {
		// set soalIdActive dan jenisSoalActive
		soalIdActive = data.soal_id;
		jenisSoalActive = data.type;

		$('#student-multiple-choice-container').removeClass('d-none');

		// section soal
		$('#multipleChoiceQuestion').html(data.question);

		if (data.question_file) {
			$('#student-multiple-choice-container .image-soal-container img').attr('src', ADMIN_URL + data.question_file);
			$('#student-multiple-choice-container .image-soal-container img').css('width', '300px');
			$('#student-multiple-choice-container .image-soal-container img').css('height', '180px');
		} else {
			$('#student-multiple-choice-container .image-soal-container').addClass('d-none');
			$('#student-multiple-choice-container .image-soal-container').parent().addClass('justify-content-center');
		}

		// section jawaban
		$('.multiple-choice-answer').html('');
		$('.multiple-choice-answer').removeClass('d-none'); // show multiple choice answer

		$('.result-choice-true-pg').html('');
		$('.result-choice-true-pg').addClass('d-none'); // hide result choice true

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

		// CHOICE A
		if (data.choice_a || data.choice_a_file) {
			choice('a', data.choice_a, data.choice_a_file);
		}

		// CHOICE B
		if (data.choice_b || data.choice_b_file) {
			choice('b', data.choice_b, data.choice_b_file);
		}

		// CHOICE C
		if (data.choice_c || data.choice_c_file) {
			choice('c', data.choice_c, data.choice_c_file);
		}
		// CHOICE D
		if (data.choice_d || data.choice_d_file) {
			choice('d', data.choice_d, data.choice_d_file);
		}

		// CHOICE E
		if (data.choice_e || data.choice_e_file) {
			choice('e', data.choice_e, data.choice_e_file);
		}

		// card item choice click
		$('.card-item-choice').on('click', function () {
			$(this).find('.form-check-input').click();
			$('.submit-answer-multiple-choice button').removeAttr('disabled');
		});

		// disable & show button kirim jawaban
		$('.submit-answer-multiple-choice button').attr('disabled', true);
		$('.submit-answer-multiple-choice').removeClass('d-none');

		// jika button soal selanjut nya ada maka lakukan hide
		if (!$('.next-question-multiple-choice').hasClass('d-none')) {
			$('.next-question-multiple-choice').addClass('d-none');
		}

		// hide response jawaban
		$('.main-response-answer').addClass('d-none');

		// hide circle correc and incorrect
		$('.circle-correct').addClass('d-none');
		$('.circle-incorrect').addClass('d-none');

	}

});

// =============================================================================================
// =================================== SOAL MENJODOHKAN  =======================================
// =============================================================================================


let totalDroppedItems = 0;

function pairingDragStart(e) {
	e.currentTarget.classList.add('opacity-25');
	const target = e.target;

	e.dataTransfer.setData("senderID", target.id);
}

/**
 * Draggable box When move listener
 * 
 * @param Event
 * @return mixed
 */
function pairingDragMove(e) {

}

/**
 * Draggable box When release listener
 * 
 * @param Event
 * @return mixed
 */
function pairingDragEnd(e) {
	e.target.classList.remove('opacity-25');
}

/**
 * Dropzone box When draggeble items overs the element listener
 * 
 * @param Event
 * @return mixed
 */
function pairingDragOver(e) {
	e.preventDefault();

	const elem = e.target;
	const posX = e.clientX;
	const posY = e.clientY;

	const parent = elem.parentNode.closest('.box-answer-body');

	if (parent.querySelector(".draggable-box")) {
		wrongAudio.play();
		parent.classList.add("bg-danger");
		return false;
	}

	parent.classList.add('bg-warning');
}

/**
 * Listeners when draggable item leave dropzone boxs
 * 
 * @param EventTarget
 * @return mixed
 */
function pairingDragLeave(e) {
	e.preventDefault();

	const elem = e.target;

	const parent = elem.parentNode.closest('.box-answer-body');
	parent.classList.remove('bg-warning');
	parent.classList.remove('bg-danger');
}

/**
 * Listeners when draggable item dropped in dropzone
 * 
 * @param EventTarget
 * @return mixed
 */
function pairingDrop(e) {
	e.preventDefault();
	const parent = e.target.parentNode.closest('.box-answer-body');

	const draggableItem = e.dataTransfer.getData("senderID");
	console.log(draggableItem);

	if (parent.querySelector(".draggable-box")) {
		parent.classList.remove('bg-danger');
		return false;
	}

	e.target.append(document.getElementById(draggableItem));
	e.target.querySelector("h4").classList.add('d-none');
	correctAudio.play();

	document.getElementById(draggableItem).querySelector(".remove-dropped").classList.remove("d-none");
	parent.classList.remove('bg-warning');
	totalDroppedItems++;

	if (totalDroppedItems >= document.querySelectorAll(".box-answer-body").length) {
		document.getElementById("btn-pairing-submit").disabled = false;
		totalDroppedItems = document.querySelectorAll(".box-answer-body").length;
		return false;
	}
}


/**
 * Remove DroppedItem
 *
 * @param {*} e 
 */
function removeDroppedPairing(e) {
	const parent = e.target.parentNode.closest(".draggable-box");
	const node = parent.cloneNode(true);

	const theID = node.id;
	const splitID = theID.split("-");
	const indukSemang = document.getElementById("indukSemang-" + splitID[1]);
	const dropzone = parent.parentNode.closest(".box-answer-drop");

	node.querySelector(".remove-dropped").classList.add("d-none");
	indukSemang.querySelector(".box-answer-head").appendChild(node);
	parent.remove();
	dropzone.querySelector("h4").classList.remove("d-none");
	totalDroppedItems--;

	if (totalDroppedItems < document.querySelectorAll(".box-answer-body").length)
		document.querySelector("#btn-pairing-submit").disabled = true;

}

/**
 * Submit Pairing Question
 * 
 * @async
 * @param {EventTarget} evt
 */
async function submitPairingQuestion(evt) {
	const infoSoal = JSON.parse(localStorage.getItem("soal"));
	const currentSoal = infoSoal.find(item => item.soal_id == soalIdActive);
	const boxAnswer = document.getElementsByClassName("box-answer");
	let totalCorrect = [];
	let isSuccessAnswered = 0;

	Array.from(boxAnswer).forEach(item => {
		const dropzone = item.querySelector(".box-answer-drop");
		const draggable = item.querySelector(".draggable-box");

		draggable.querySelector("span").classList.add("d-none");

		if (dropzone.dataset.dropzoneSort == draggable.dataset.draggableSort) {
			totalCorrect.push("success");
			item.querySelector(".box-answer-body").classList.add("bg-success");
		}
		else {
			totalCorrect.push("fail");
			item.querySelector(".box-answer-body").classList.add("bg-danger");
		}
	});



	// ceck apakah jawabn benar atau salah
	if (totalCorrect.filter(item => item == "success").length == boxAnswer.length) {
		successAudio.play();
		confetti({
			particleCount: 150,
			spread: 70,
			origin: { y: 0.6 },
		});
		document.querySelector(".soal-footer").classList.add("is-success");
		document.getElementById("text-submit-status").innerHTML = '<i class="bi bi-check-circle-fill"></i>&nbsp;BENAR';
		document.getElementById("text-submit-status").classList.remove("top-100");
		document.getElementById("text-submit-status").classList.add("top-50");
		isSuccessAnswered = 1;
	}
	else {
		failedAudio.play();
		document.querySelector(".soal-footer").classList.add("is-failed");
		document.getElementById("text-submit-status").innerHTML = '<i class="bi bi-x-circle-fill"></i>&nbsp;SALAH';
		document.getElementById("text-submit-status").classList.remove("top-100");
		document.getElementById("text-submit-status").classList.add("top-50");
		isSuccessAnswered = 0;
	}


	Object.assign(currentSoal, { "result_answer": isSuccessAnswered });
	const answer = [...boxAnswer].map((item, index) => {
		const idx = index;

		const dragValue = item.querySelector(".draggable-box").dataset.value;
		const dropValue = item.querySelector(".box-answer-drop").dataset.value;

		return {
			"answer_key": dragValue,
			"answer_value": dropValue,
			"urutan": idx + 1
		}
	});

	await saveJawabanOnBackground(currentSoal, answer);

	setTimeout(() => {
		let textNode;

		document.getElementById("text-submit-status").innerHTML = null;
		document.getElementById("text-submit-status").classList.add("top-100");
		document.getElementById("text-submit-status").classList.remove("top-50");

		const span1 = document.createElement("span");
		span1.classList.add("d-flex", "align-items-center", "px-3", "border-start", "border-white");

		if (document.querySelector(".soal-footer").classList.contains("is-success")) {
			textNode = "BENAR";
			span1.classList.add("text-success");
		} else {
			textNode = "SALAH";
			span1.classList.add("text-danger");
		}

		const icon = document.createElement("i");
		if (document.querySelector(".soal-footer").classList.contains("is-success"))
			icon.classList.add("bi", "bi-check-circle-fill", "fs-4");
		else
			icon.classList.add("bi", "bi-x-circle-fill", "fs-4");
		span1.appendChild(icon);

		const hInfo = document.createElement("h5");
		hInfo.classList.add("mb-0", "ms-2");

		const contentInfo = document.createTextNode(textNode);
		hInfo.appendChild(contentInfo);
		span1.appendChild(hInfo);

		document.querySelector(".soal-footer").classList.remove("is-success", "is-failed");
		document.getElementById("btn-pairing-submit").classList.add("d-none");
		document.getElementById("btn-pairing-submit").disabled = true;
		
		const nmr = document.querySelector(".nomor");
		const numberSpan = nmr.querySelector("span").textContent.split("/");

		if(parseInt(numberSpan[0]) == parseInt(numberSpan[1])) {
			document.getElementById("btn-pairing-keluar").classList.remove("d-none");
			document.getElementById("btn-pairing-keluar").disabled = false;
		}
		else {
			document.getElementById("btn-pairing-next").classList.remove("d-none");
			document.getElementById("btn-pairing-next").disabled = false;
		}

		document.querySelector(".soal-footer").insertBefore(span1, document.getElementById("btn-pairing-keluar"));
	}, 2000);
}



// ===================================== END =====================================

// =============================================================================================
// =================================== SOAL DRAGDROP  ==========================================
// =============================================================================================

/**
 * On Drag Start
 * 
 * @param {*} e 
 */
function ddDragStart(e) {
	e.currentTarget.classList.add('opacity-25');
	const target = e.target;
	const sets = { ...target.dataset };
	const dropbox = e.target.parentNode.closest(".drop-box");

	if (dropbox) {
		Object.assign(sets, { "value": dropbox.dataset.value });
	}

	e.dataTransfer.setData("sets", JSON.stringify(sets));
}

/**
 *  Drag End
 * 
 * @param {Event} e 
 */
function ddDragEnd(e) {
	e.target.classList.remove('opacity-25');
}

/**
 * Drag oVer
 * 
 * @param {*} e 
 */
function ddDragOver(e) {
	e.preventDefault();

	if (e.target.querySelector('.dragbox')) {
		e.target.classList.add("border-danger");
		return false;
	}

	e.target.classList.add("border-info");
}

/**
 * Drag Leave
 * 
 * @param {*} e 
 */
function ddDragLeave(e) {
	e.preventDefault();

	e.target.classList.remove("border-success", "border-danger", "border-info");
}

/**
 * On Drop
 * 
 * @param {*} e 
 */
function ddDrop(e) {
	e.preventDefault();

	e.target.classList.remove("border-success", "border-danger", "border-info");
	const dropzone = e.target;

	const set = JSON.parse(e.dataTransfer.getData('sets'));
	const dragbox = document.querySelector('.dragbox[data-index="' + set.index + '"]');

	if (e.target.querySelector('.dragbox')) {
		e.target.classList.remove("border-danger");
		wrongAudio.play();
		return false;
	}

	dropzone.classList.remove("px-3");

	dropzone.innerHTML = null;
	dropzone.append(dragbox);
	correctAudio.play();

	// cek jika semua dropzone sudah terisi
	const isFilled = [];

	Array.from(document.querySelectorAll(".drop-box")).forEach(i => {

		if (i.querySelector(".dragbox")) {
			isFilled.push(1);
		}
		else {
			isFilled.push(0);
		}

	});

	if (!isFilled.includes(0)) {
		document.getElementById("btn-dd-submit").disabled = false;
	}
}

// CONTAINER 

function ddContainerDragOver(e) {
	e.preventDefault();

}

function ddContainerDragLeave(e) {
	e.preventDefault();
}

function ddContainerDrop(e) {
	e.preventDefault();

	wooshAudio.play();
	const set = JSON.parse(e.dataTransfer.getData('sets'));
	const dragbox = document.querySelector('.dragbox[data-index="' + set.index + '"]');

	document.querySelector(`.dragbox-container[data-index="${set.index}"]`).append(dragbox);

	const dropbox = document.querySelector(`.drop-box[data-value="${set.value}"]`);
	dropbox.classList.add("px-3");
	dropbox.innerHTML = '....';

	const isFilled = [];
	Array.from(document.querySelectorAll(".drop-box")).forEach(i => {

		if (i.querySelector(".dragbox")) {
			isFilled.push(1);
		}
		else {
			isFilled.push(0);
		}

	});

	if (isFilled.includes(0)) {
		document.getElementById("btn-dd-submit").disabled = true;
	}

}

// submit
/**
* Submit Pairing Question
* 
* @async
* @param {EventTarget} evt
*/
async function submitDragdropQuestion(evt) {
	const infoSoal = JSON.parse(localStorage.getItem("soal"));
	const currentSoal = infoSoal.find(item => item.soal_id == soalIdActive);
	const boxAnswer = document.getElementsByClassName("drop-box");
	let totalCorrect = [];
	let isSuccessAnswered = 0;

	Array.from(boxAnswer).forEach(i => {
		const dragbox = i.querySelector(".dragbox");

		if (i.dataset.value == dragbox.dataset.key)
			totalCorrect.push("success");
		else
			totalCorrect.push("failed");
	});


	// ceck apakah jawabn benar atau salah
	if (totalCorrect.filter(item => item == "success").length == boxAnswer.length) {
		successAudio.play();
		confetti({
			particleCount: 150,
			spread: 70,
			origin: { y: 0.6 },
		});
		document.querySelector(".soal-footer").classList.add("is-success");
		document.getElementById("text-dd-submit").innerHTML = '<i class="bi bi-check-circle-fill"></i>&nbsp;BENAR';
		document.getElementById("text-dd-submit").classList.remove("top-100");
		document.getElementById("text-dd-submit").classList.add("top-50");
		isSuccessAnswered = 1;
	}
	else {
		failedAudio.play();
		document.querySelector(".soal-footer").classList.add("is-failed");
		document.getElementById("text-dd-submit").innerHTML = '<i class="bi bi-x-circle-fill"></i>&nbsp;SALAH';
		document.getElementById("text-dd-submit").classList.remove("top-100");
		document.getElementById("text-dd-submit").classList.add("top-50");
		isSuccessAnswered = 0;
	}


	Object.assign(currentSoal, { "result_answer": isSuccessAnswered });
	const answer = [...boxAnswer].map((item, index) => {
		const idx = index;
		const value = item.dataset.key;

		return {
			"current_answer": value,
			"urutan": idx + 1
		};

	});

	await saveJawabanOnBackground(currentSoal, answer);

	setTimeout(() => {
		let textNode;

		document.getElementById("text-dd-submit").innerHTML = null;
		document.getElementById("text-dd-submit").classList.add("top-100");
		document.getElementById("text-dd-submit").classList.remove("top-50");

		const span1 = document.createElement("span");
		span1.classList.add("d-flex", "align-items-center", "px-3", "border-start", "border-white");

		if (document.querySelector(".soal-footer").classList.contains("is-success")) {
			textNode = "BENAR";
			span1.classList.add("text-success");
		} else {
			textNode = "SALAH";
			span1.classList.add("text-danger");
		}

		const icon = document.createElement("i");
		if (document.querySelector(".soal-footer").classList.contains("is-success"))
			icon.classList.add("bi", "bi-check-circle-fill", "fs-4");
		else
			icon.classList.add("bi", "bi-x-circle-fill", "fs-4");
		span1.appendChild(icon);

		const hInfo = document.createElement("h5");
		hInfo.classList.add("mb-0", "ms-2");

		const contentInfo = document.createTextNode(textNode);
		hInfo.appendChild(contentInfo);
		span1.appendChild(hInfo);

		document.querySelector(".soal-footer").classList.remove("is-success", "is-failed");
		document.getElementById("btn-dd-submit").classList.add("d-none");
		document.getElementById("btn-dd-submit").disabled = true;

		const nmr = document.querySelector(".nomor");
		const numberSpan = nmr.querySelector("span").textContent.split("/");

		if(parseInt(numberSpan[0]) == parseInt(numberSpan[1])) {
			document.getElementById("btn-dd-keluar").classList.remove("d-none");
			document.getElementById("btn-dd-keluar").disabled = false;
		}
		else {
			document.getElementById("btn-dd-next").classList.remove("d-none");
			document.getElementById("btn-dd-next").disabled = false;
		}
		
//
		document.querySelector(".soal-footer").insertBefore(span1, document.getElementById("btn-dd-keluar"));
	}, 2000);
}



// ===================================== END =====================================


function nextPage(event) {

	let soalMaster = JSON.parse(localStorage.getItem('soal_master'));

	// ambil soal yang belum dijawab
	let soalUnanswered = JSON.parse(localStorage.getItem('soal')).filter((item) => item.exam_answer == null || item.exam_answer == '');

	// ambil soal yang sudah dijawab
	let soalAnswered = JSON.parse(localStorage.getItem('soal')).filter((item) => item.exam_answer != null);

	if(soalUnanswered.length == 0)
	{
		return false;
	}

	// update counter soal
	$('.counter-soal').text(soalAnswered.length + 1);

	// set soalIdActive dan jenisSoalActive
	soalIdActive = soalUnanswered[0].soal_id;
	jenisSoalActive = soalUnanswered[0].type;

	// HIDE SEMUA CONTAINER SOAL
	$('#student-fill-the-blank-container').addClass('d-none');
	$('#student-true-or-false-container').addClass('d-none');
	$('#student-essay-container').addClass('d-none');
	$('#student-multiple-choice-container').addClass('d-none');

	// JIKA TIPE SOAL FILL THE BLANK
	if (soalUnanswered[0].type == "4") {
		soalFillTheBlank(soalUnanswered[0]);
	}

	// JIKA TIPE SOAL TRUE OR FALSE
	if (soalUnanswered[0].type == "3") {
		soalTrueOrFalse(soalUnanswered[0]);
	}

	// JIKA TIPE SOAL ESSAY
	if (soalUnanswered[0].type == "2") {
		soalEssay(soalUnanswered[0]);
	}

	// JIKA TIPE SOAL PILIHAN GANDA
	if (soalUnanswered[0].type == "1") {
		soalPilihanGanda(soalUnanswered[0]);
	}

	// JIKA TIPE SOAL MENJODOHKAN
	if (soalUnanswered[0].type == "5") {
		
		const unanswered = soalUnanswered[0];
		const nomor = soalAnswered.length + 1;
		const dragitem = getShuffledArr(unanswered.correct_answer.map(item => ({ "text": item.answer_key, "index": item.urutan, "isImage": item.has_image == 1 ? true : false })));
		const dropitem = unanswered.correct_answer.map(item => ({ "text": item.answer_value, "index": item.urutan }));
		soalIdActive = unanswered.soal_id;
		jenisSoalActive = unanswered.type;
		// pengacak
		const shuffled1 = [];

		for(var i=0;i<dragitem.length;i++) {
			shuffled1.push({ "key": dragitem[i], "value": dropitem[i] });
		}
		// info judul dan mapel
		const input = {...unanswered, ...period, "constipasi": shuffled1, "nomor": nomor, "totalSoal": soalMaster.length };

		console.log(input);

		setMyQuestion(document.getElementById('student-pairing-question'), input);
	}

	
	if(soalUnanswered[0].type == "6")
	{
		
		const unanswered = soalUnanswered[0];
		soalIdActive = unanswered.soal_id;
		jenisSoalActive = unanswered.type;

		const nomor = soalAnswered.length + 1;
		const questionTag = document.createElement("div");
		questionTag.innerHTML = unanswered.question;
		
		Array.from(questionTag.querySelectorAll(".drop-box")).forEach(i => {
			i.setAttribute("ondragover", "ddDragOver(event)");
			i.setAttribute("ondragleave", "ddDragLeave(event)");
			i.setAttribute("ondrop", "ddDrop(event)");
			i.classList.add("border", "border-5", "rounded", "px-3");
		});

		const answer = [];
		unanswered.correct_answer.forEach(i => {
			answer.push({"key": i.urutan, "value": i.answer_correct });
			if(i.answer_false) {
				answer.push({"key": 0, "value": i.answer_false });
			}
		});

		const input = {...unanswered, ...period,  "question": questionTag.innerHTML, "answers": answer, "nomor": nomor,  "totalSoal": soalMaster.length };


		setMyQuestion(document.getElementById('student-porkas-question'), input);
	}
}

function endAssesment(e) {
	const soal = JSON.parse(localStorage.getItem("soal"));

	console.log(soal);
}


Handlebars.registerHelper("adminUrl", str => `${ADMIN_URL}/${str}`);
Handlebars.registerHelper("unsafe", str => {
	return new Handlebars.SafeString(str)
})
Handlebars.registerHelper('ifCond', function (v1, operator, v2, options) {

	switch (operator) {
		case '==':
			return (v1 == v2) ? options.fn(this) : options.inverse(this);
		case '===':
			return (v1 === v2) ? options.fn(this) : options.inverse(this);
		case '!=':
			return (v1 != v2) ? options.fn(this) : options.inverse(this);
		case '!==':
			return (v1 !== v2) ? options.fn(this) : options.inverse(this);
		case '<':
			return (v1 < v2) ? options.fn(this) : options.inverse(this);
		case '<=':
			return (v1 <= v2) ? options.fn(this) : options.inverse(this);
		case '>':
			return (v1 > v2) ? options.fn(this) : options.inverse(this);
		case '>=':
			return (v1 >= v2) ? options.fn(this) : options.inverse(this);
		case '&&':
			return (v1 && v2) ? options.fn(this) : options.inverse(this);
		case '||':
			return (v1 || v2) ? options.fn(this) : options.inverse(this);
		default:
			return options.inverse(this);
	}
});

function setMyQuestion(id, data) {
	const tmpl = id.innerHTML;
	const hb = Handlebars.compile(tmpl);
	const html = hb(data);
	document.forms['frm-soal'].innerHTML = html;
}


// submit exam
function logoutExam() {
	// play audio success
	(new Audio(BASE_URL + "assets/audios/trumpet-success.mp3")).play();

	switch (jenisSoalActive) {
		case "1":
			finishMultipleChoice();
			break;
		case "2":
			finishEssay();
			break;
		case "3":
			finishTrueOrFalse();
			break;
		case "4":
			finishFtb();
		default:
			window.location.href = BASE_URL + "/asesmen";
			break;
	}

	// remove local storage
	removeLocalStorage();
}

// finish exam
function finishExam(e) {
	let timer = $('#timer').text();
	let time = timer.split(':');

	// convert time to second
	let second = parseInt(time[0]) * 3600 + parseInt(time[1]) * 60 + parseInt(time[2]);
	let examCompletionTime = localStorage.getItem('remaining_time') - second;

	// proses kirim jawaban ke server
	$.ajax({
		type: "POST",
		url: "asesmen_standard/finish_exam",
		data: {
			soal: localStorage.getItem('soal'),
			exam_id: localStorage.getItem('examId'),
			student_id: $('input[name="student_id"]').val(),
			time: examCompletionTime,
			class_id: JSON.parse($('#period').text()).class_id
		},
		dataType: "JSON",
		success: function (response) {

			if (response.success) {
				// show alert success
				$('#modalFinishExam').modal({
					backdrop: 'static',
					keyboard: false
				});

				// show modal finish exam
				$('#modalFinishExam').modal('show');

				// delete local storage
				removeLocalStorage();
			}
		}
	});
}

function removeLocalStorage() {
	$('input[name="exam_id"]').val('');
	localStorage.removeItem('examId');
	localStorage.removeItem('start_time');
	localStorage.removeItem('remaining_time');
	localStorage.removeItem('soal');
	localStorage.removeItem('soal_master');
}
