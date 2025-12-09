<style>
	.button-change-image {
		position: absolute;
		bottom: 0;
		left: 0;
		right: 0;
	}

	.form-check-input {
		width: 24px;
		height: 24px;
	}

	.form-check-input:checked {
		background-color: var(--bs-success);
		border-color: #198754;
	}
</style>

<section class="d-flex h-100" style="margin-top: 40px;">

	<!-- Soal Utama -->
	<div class="h-100 main-soal bg-primary rounded-4 mx-3 w-100">
		<input type="hidden" id="true_or_false_id">

		<div class="d-flex justify-content-center align-items-center px-3 py-2">
			<div class="text-white border rounded-pill badge bg-primary text-center" style="margin-top: -15px; font-size: 14px; height: 30px; z-index: 1">
				<span>Question</span>
				<span class="counter-soal"></span> / <span class="total-soal"></span>
			</div>
		</div>

		<div class="d-flex border rounded-3 bg-primary-600 position-relative" style="margin-top: -20px;">
			<!-- image soal container -->
			<div class="image-soal-container m-3" style="height: 100%;">
				<img class="rounded-3 m-auto" src="<?= base_url('assets/images/icons/image-empty.png') ?>" alt="image" style="">

				<!-- button zoom image -->
				<button class="btn btn-zoom border-1 rounded-3 bg-white position-absolute" style="bottom: 20px; left: 22px;">
					<i class="fa-solid fa-magnifying-glass-plus"></i>
				</button>
			</div>

			<div class="" style="font-size: 18px;">
				<div id="soalTrueOrFalse" class="soal-content p-4 justify-content-center align-items-center d-flex text-white w-100" style="height: 100%;">
					ini adalah soal...
				</div>
			</div>
		</div>

		<!-- BUTTON TRUE OR FALSE -->
		<div class="row justify-content-center mt-4 btn-group-choice" style="height: 296px;">

			<div class="col-6" style="height: 260px;">
				<div class="bg-dark h-100 p-2 rounded-3 btn-choice-container">
					<button class="btn btn-success text-white w-100 h-100 border border-white" style="border-style: dashed !important;" onclick="setTrueChoiceButton(this)">Pilihan Benar</button>
				</div>

				<div class="form-check text-white text-center justify-content-center align-items-center d-flex">
					<input type="checkbox" class="form-check-input mt-3" id="choice_true" onclick="setTrueChoice(this)">
				</div>
			</div>

			<div class="col-6" style="height: 260px;">
				<div class="bg-dark h-100 p-2 rounded-3 btn-choice-container">
					<button class="btn btn-danger text-white w-100 h-100 border border-white" style="border-style: dashed !important;" onclick="setTrueChoiceButton(this)">Pilihan Salah</button>
				</div>

				<div class="form-check text-white text-center justify-content-center align-items-center d-flex">
					<input type="checkbox" class="form-check-input mt-3" id="choice_false" onclick="setTrueChoice(this)">
				</div>
			</div>

			<!-- Alert benar -->
			<div class="row alert-true-or-false-true rounded-3 text-white bg-success m-0 mt-3 p-3 d-none" style="">
				<p class="text-center h4">
					<i class="fa-solid fa-check-circle me-2"></i>
					Benar
				</p>
			</div>

			<!-- Alert salah -->
			<div class="row alert-true-or-false-false rounded-3 text-white bg-danger m-0 mt-3 p-3 d-none" style="">
				<p class="text-center h4">
					<i class="fa-solid fa-times-circle me-2"></i>
					Salah
				</p>
			</div>

		</div>

		<!-- GROUP FOOTER SOAL & BUTTON NEXT / FINISH -->
		<div class="d-flex footer-true-or-false rounded-3 text-white bg-primary-600 m-0 p-3" style="margin-top: 60px !important;">
			<div class="border-end px-3">
				<h5><?= $exam['title'] ?></h5>
				<h6><?= $exam['subject_name'] ?></h6>
			</div>

			<div class="px-3 mt-3">
				<h6><i class="fa-solid fa-list-check me-2"></i>Benar atau Salah</h6>
			</div>

			<div class="my-0 py-3 px-3 text-success h5 border-start d-none circle-correct">
				<i class="fa-solid fa-check-circle me-2"></i>
				Benar
			</div>

			<div class="my-0 py-3 px-3 text-danger h5 border-start d-none circle-incorrect">
				<i class="fa-solid fa-times-circle me-2"></i>
				Salah
			</div>

			<!-- button kirim jawaban -->
			<div class="flex-grow-1 text-end mt-2 submit-answer-true-or-false col-2">
				<button class="btn btn-secondary bg-primary-600 fw-bold" onclick="submitTrueOrFalse()">kirim jawaban
					<i class="fa-solid fa-paper-plane ms-2"></i>
				</button>
			</div>

			<!-- button soal selanjutnya -->
			<div class="flex-grow-1 text-end mt-2 text-end next-question-true-or-false col-2 m-auto d-none">
				<button class="btn btn-secondary bg-primary-600 fw-bold next-soal">Soal Selanjutnya
					<i class="fa-solid fa-arrow-right ms-2"></i>
				</button>
			</div>

			<!-- button akhiri ujian -->
			<div class="flex-grow-1 text-end mt-2 text-end finish-question-true-or-false col-2 m-auto d-none">
				<button class="btn btn-secondary bg-primary-600 fw-bold" onclick="finishExam()">Akhiri Ujian
					<i class="fa-solid fa-check ms-2"></i>
				</button>
			</div>

		</div>

	</div>

	<!-- response answer -->
	<div class="d-flex flex-column main-response-answer border rounded-3 d-none" style="min-width: 300px;">
		<div class="text-white border rounded-pill badge bg-primary text-center position-relative" style="font-size: 14px; top: -15px; align-self: center; width: 200px;">
			<span>Tanggapan Jawaban</span>
		</div>

		<div class="image-response-answer-container m-3" style="height: 200px;">
			<img class="rounded-3 m-auto w-100 h-100" src="<?= base_url('assets/images/icons/image-empty.png') ?>" alt="image">
		</div>

		<div class="response-answer-content p-4 text-white" style="">
			ini adalah jawaban...

		</div>
	</div>

</section>

<script>
	// klik form-check-input jawaban benar
	function setTrueChoice(el) {
		// reset all form-check-input
		$('.form-check-input').prop('checked', false);

		// set form-check-input checked
		$(el).prop('checked', true);

		// set btn-choice-container background color
		$(el).closest('.btn-group-choice').find('.btn-choice-container').removeClass('bg-white');
		$(el).closest('.col-6').find('.btn-choice-container').addClass('bg-white');

		// enable button kirim jawaban
		$('.submit-answer-true-or-false button').attr('disabled', false);
	}

	// klik button pilihan benar atau salah
	function setTrueChoiceButton(el) {
		// reset all form-check-input
		$('.form-check-input').prop('checked', false);

		// set form-check-input checked
		$(el).closest('.col-6').find('.form-check-input').prop('checked', true);

		// set btn-choice-container background color
		$(el).closest('.btn-group-choice').find('.btn-choice-container').removeClass('bg-white');
		$(el).closest('.btn-choice-container').addClass('bg-white');

		// enable button kirim jawaban
		$('.submit-answer-true-or-false button').attr('disabled', false);
	}

	// proses kirim jawaban
	function submitTrueOrFalse() {
		// ambil data soal master dari local storage
		let soalMaster = JSON.parse(localStorage.getItem('soal_master'));
		$.each(soalMaster, function(i, val) {
			if (val.soal_id == soalIdActive) {
				// proses ambil jawaban siswa dari element
				let choiceTrue = $('#choice_true').prop('checked');
				let choiceFalse = $('#choice_false').prop('checked');

				let answerItems = (choiceTrue) ? 'true' : 'false';

				let resultAnswer = false;
				// proses pengecekan jawaban siswa dengan jawaban benar
				if (answerItems.toLowerCase() == val.answer.toLowerCase()) {
					resultAnswer = true;
				}

				$('.submit-answer-true-or-false').addClass('d-none'); // hide button submit

				// jika soal adalah soal terakhir
				let counter = $('#student-true-or-false-container .counter-soal').text();
				let totalSoal = $('.total-soal').text();
				if (counter == totalSoal) {
					$('.next-question-true-or-false').addClass('d-none'); // hide button next soal
					$('.finish-question-true-or-false').removeClass('d-none'); // show button finish exam
				}

				// remove checked form-check-input & set background color
				$('.form-check-input').prop('checked', false);
				$('.btn-choice-container').removeClass('bg-white');

				// set form-check-input checked and background color
				if (val.answer.toLowerCase() == 'true') {
					$('#choice_true').prop('checked', true);
					$('#choice_true').closest('.col-6').find('.btn-choice-container').addClass('bg-white');
				} else {
					$('#choice_false').prop('checked', true);
					$('#choice_false').closest('.col-6').find('.btn-choice-container').addClass('bg-white');
				}

				// jika jawaban benar lakukan hide atau show element
				if (resultAnswer) {
					(new Audio(BASE_URL + "assets/audios/success-1-6297.mp3")).play(); // play audio

					// confetti animation
					confetti({
						particleCount: 150,
						spread: 70,
						origin: {
							y: 0.6
						},
					});

					$('.circle-correct').removeClass('d-none');
					$('.circle-incorrect').addClass('d-none');

					$('.footer-true-or-false').addClass('d-none');
					$('.alert-true-or-false-true').removeClass('d-none');

					setTimeout(() => {
						$('.footer-true-or-false').removeClass('d-none');
						$('.alert-true-or-false-true').addClass('d-none');

						// jika soal memiliki response jawaban benar
						// jika response jawaban berupa gambar dan text
						if (val.response_correct_answer && val.response_correct_answer_file) {
							$('.main-response-answer').removeClass('d-none');
							$('.image-response-answer-container').removeClass('d-none');
							$('.image-response-answer-container img').attr('src', ADMIN_URL + val.response_correct_answer_file);
							$('.response-answer-content').html(val.response_correct_answer);
						}

						// jika response jawaban berupa text
						if (val.response_correct_answer && !val.response_correct_answer_file) {
							$('.main-response-answer').removeClass('d-none');
							$('.image-response-answer-container').addClass('d-none');
							$('.response-answer-content').html(val.response_correct_answer);
						}

					}, 3000);


				} else {
					(new Audio(BASE_URL + "assets/audios/trumpet-fail-242645.mp3")).play();

					// tampilkan element jawaban salah di footer
					$('.circle-incorrect').removeClass('d-none');
					$('.circle-correct').addClass('d-none');

					$('.footer-true-or-false').addClass('d-none');
					$('.alert-true-or-false-false').removeClass('d-none');

					setTimeout(() => {
						$('.footer-true-or-false').removeClass('d-none');
						$('.alert-true-or-false-false').addClass('d-none');

						// jika soal memiliki response jawaban salah
						// jika response jawaban berupa gambar dan text
						if (val.response_wrong_answer && val.response_wrong_answer_file) {
							$('.main-response-answer').removeClass('d-none');
							$('.image-response-answer-container').removeClass('d-none');
							$('.image-response-answer-container img').attr('src', ADMIN_URL + val.response_wrong_answer_file);
							$('.response-answer-content').html(val.response_wrong_answer);
						}

						// jika response jawaban berupa text
						if (val.response_wrong_answer && !val.response_wrong_answer_file) {
							$('.main-response-answer').removeClass('d-none');
							$('.image-response-answer-container').addClass('d-none');
							$('.response-answer-content').html(val.response_wrong_answer);
						}

					}, 3000);

				}

				// update local storage soal dengan jawaban siswa
				let soal = JSON.parse(localStorage.getItem('soal'));
				soal.forEach((item, idx) => {
					if (item.soal_id == soalIdActive) {
						soal[idx]['exam_answer'] = answerItems;
						soal[idx]['result_answer'] = (resultAnswer) ? 1 : 0;
					}
				});

				// lakukan penghitungan kembali jumlah soal yang belum di jawab
				let soalNotAnswered = soal.filter((item) => !item.exam_answer);

				if (soalNotAnswered.length == 0) {
					$('.next-question-true-or-false').addClass('d-none'); // hide button next soal
					$('.finish-question-true-or-false').removeClass('d-none'); // show button finish exam
				} else {
					$('.next-question-true-or-false').removeClass('d-none'); // show button next soal
					$('.finish-question-true-or-false').addClass('d-none'); // hide button finish exam
				}

				// update kembali local storage soal dengan soal yang sudah terisi jawaban
				localStorage.setItem('soal', JSON.stringify(soal));
			}
		});
	}

</script>
